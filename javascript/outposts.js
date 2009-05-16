// Orodlin Team
// eyescream


// Activates tab "i" to emulate normal links, but ajax-powered. Usage: for example main menu text
function tab(i)
{
    $('#tabcontainer').triggerTab(i);
    return false;
}

// Function for outpost listing slider - level handles "from ... to ...".
function levels(cx,cy,x,y)
{
    var max = document.getElementById('biggestOutpost').innerHTML / 100;
    //document.getElementById('debug').innerHTML = $('#track1').SliderGetValues();
    document.getElementById('slevel').value = Math.round($('#track1').SliderGetValues()[0][0][0] * max);
    document.getElementById('elevel').value = Math.round($('#track1').SliderGetValues()[0][1][0] * max);
}

// Outpost shop, "size" slider - function to update fields with required minerals.
function size(cx,cy,x,y)
{
    var base = parseInt(document.getElementById('size').innerHTML);
    var maxlevel = parseInt(document.getElementById('maxsize').innerHTML);
    var add = document.getElementById('level').value = Math.round(cx * maxlevel / 100);

    document.getElementById('size-gold').innerHTML = add * (2 * base + add + 3) * 125;
    document.getElementById('size-plat').innerHTML = add * 10;
    document.getElementById('size-pine').innerHTML = add * (2 * base + add - 1) / 2;
}

// Outpost shop, "lairs" slider.
function lairs(cx,cy,x,y)
{
    var base = parseInt(document.getElementById('baselairs').innerHTML);
    var maxlevel = parseInt(document.getElementById('maxlairs').innerHTML);
    var add = document.getElementById('lairs').value = Math.round(cx * maxlevel / 100);
    var neededMeteor = add * (2 * base + add + 1) / 2;

    document.getElementById('lair-mete').innerHTML = neededMeteor;
    document.getElementById('lair-gold').innerHTML = add * (2 * base + add + 3) * 25;
    document.getElementById('lair-crys').innerHTML = neededMeteor * 5;
}

// Outpost shop, "barracks" slider.
function barracks(cx,cy,x,y)
{
    var base = parseInt(document.getElementById('basebarracks').innerHTML);
    var maxlevel = parseInt(document.getElementById('maxbarracks').innerHTML);
    var add = document.getElementById('barracks').value = Math.round(cx * maxlevel / 100);
    var neededMeteor = add * (2 * base + add + 1) / 2;

    document.getElementById('barr-mete').innerHTML = neededMeteor;
    document.getElementById('barr-gold').innerHTML = add * (2 * base + add + 3) * 25;
    document.getElementById('barr-adam').innerHTML = neededMeteor * 5;
}

// Outpost shop, "warriors-archers" sliders.
function warriors(cx,cy,x,y)
{
    var max = parseInt(document.getElementById('maxsoldiers').innerHTML);
    var war = document.getElementById('warriors').value = Math.round(cx * max / 100);
    var arr = parseInt(document.getElementById('archers').value);
    if (war + arr > max)
    {
        document.getElementById('archers').value = max - war;
        //$('#track5').SliderSetValues([[(max-war)*100/200,0]]);
    }
    sum();
}

function archers(cx,cy,x,y)
{
    var max = parseInt(document.getElementById('maxsoldiers').innerHTML);
    var war = parseInt(document.getElementById('warriors').value);
    var arr = document.getElementById('archers').value = Math.round(cx * max / 100);
    if (war + arr > max)
    {
        document.getElementById('warriors').value = max - arr;
        //update warriors slider
    }
    sum();
}

// Outpost shop, sum money spent on army and machines.
function sum()
{
    var val = (parseInt(document.getElementById('archers').value) + parseInt(document.getElementById('warriors').value)) * 25 + (parseInt(document.getElementById('barricades').value) + parseInt(document.getElementById('catapults').value)) * 35;
    if (val > parseInt(document.getElementById('treasury').innerHTML))
        document.getElementById('armysum').innerHTML = '<blink>' + val + '</blink>';
    else
        document.getElementById('armysum').innerHTML = val;
}

// Outpost shop, "barricades-catapults" slider.
function barricades(cx,cy,x,y)
{
    var max = document.getElementById('maxmachines').innerHTML;
    var fort = document.getElementById('barricades').value = Math.round(cx * max / 100);
    var catapult = Math.round(document.getElementById('catapults').value);
    if (fort + catapult > max)
        document.getElementById('catapults').value = max - fort;
    sum();
}

function catapults(cx,cy,x,y)
{
    var max = document.getElementById('maxmachines').innerHTML;
    var catapult = document.getElementById('catapults').value = Math.round(cx * max / 100);
    var fort = Math.round(document.getElementById('barricades').value);
    if (fort + catapult > max)
        document.getElementById('barricades').value = max - catapult;
    sum();
}

// Outpost veterans. Function to be called back when equipent is dropped on image.
function dropEquipment(drag)
{
    $(drag).hide("slow");
    var eid = $(drag).attr('id');
    var eid = eid.substring(1, eid.length);
    $(this).parent().find("input[@type=hidden]").attr("value", eid).attr("value");
    $(this).next().html($(drag).html()); // Insert item's name to next <td>
}

function clearEquipment()
{
    $(this).parent().parent().find(".name").html('');    // go "up" (from <input> to <td>) and back
    $(this).parent().find("input[@type=hidden]").attr("value", 0);
}

function submitVeteran()
{
    return false;
}

function testRings()
{
    if ($('#ring1').parent().find("input[@type=hidden]").attr("value") != 0 && $('#ring1').parent().find("input[@type=hidden]").attr("value") == $('#ring2').parent().find("input[@type=hidden]").attr("value"))
    {
        alert('Nie możesz założyć tego samego pierscienia 2 razy');// Any idea to use more than one language?
        return false;
    }
    return true;
}

// Called after veteran div has ben dropped on <table> for edition.
function dropVeteran(drag)
{
    $(drag).hide();
    var vetId = $(drag).attr('id');
    vetId = vetId.substring(3, vetId.length);
    $.post('outposts.php?view=veterans',{ vid: vetId}, function(msg){$('.formupdate').html(msg);});
}