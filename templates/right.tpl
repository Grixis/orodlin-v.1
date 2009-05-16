<div id="right"><div class="topmenu"></div><div class="submenu">
            <div class="imghead"><img src="" alt="img_Lista graczy w grze" width="103" height="30" /></div>
            <div>
{section name=players loop=$List}
{$List[players]}
{/section}{if $Online==0}{$smarty.const.NOPLAYERS}{/if}</div></div><div class="bottommenu"></div></div>
