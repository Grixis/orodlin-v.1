<?php
// Orodlin Team
// eyescream
/**
 * Smarty {slider} function plugin.
 * Creates slider from 2 "div"&gt"; fields and a bit of jQuery code.
 * You pass the name of function that is called when slider is moved. Function must accept 4 parameters:
 * function examplefun(cordx, cordy, x , y)
 * See slider documentation in jQuery's Interface plugin for details.
 *
 * If you don't pass the function name, code attempts to create a default one. To work it needs 2 ID's:
 * - ID of the element that must be updated with changed value
 * - max possible value
 * This default function updates html element using "value", not "innerHTML". So it's usage is limited to form inputs etc.
 *
 * Input:
 *  fun - name of the Javascript updater function. EITHER THIS OR BOTH OTHER MUST BE SET.
 *  max - slider's range
 *  assocId - HTML "id" of the element that will be updated when slider's handle moves.
 *
 * Example:
 * {slider fun=examplefun}
 * {slider max=$PlayerGold assocId=amount}
 */
function smarty_function_slider($params, &$smarty)
{
    if (!in_array('fun', array_keys($params)) && !(in_array('assocId', array_keys($params)) && in_array('max', array_keys($params))))
    {
        $smarty->trigger_error("slider: either 'fun' or 'assocId' and 'max' parameter must be set");
        return;
    }
    if (in_array('assocId', array_keys($params)))
    {
        $functionBody = 'function '.$params['assocId'].'Fun(cx,cy,x,y){document.getElementById(\''.$params['assocId']. '\').value=Math.round(cx/100*'.$params['max'].')}';
        $functionName = $params['assocId'].'Fun';
    }
    else
        $functionName = $params['fun'];

    static $trackNumber = 1;
    static $handleNumber = 1;
    $strCode = '<div id="track'.$trackNumber.'" class="slider"><div id="handle'.$handleNumber.'" class="handle"></div></div>'.
        '<script type="text/javascript" language="javascript">';
    if(isset($functionBody))
        $strCode .= $functionBody;
    return $strCode.' $(\'#track'.$trackNumber++.'\').Slider({accept:\'#handle'.$handleNumber++.'\', onSlide:'.$functionName.'});</script>';
}
?>