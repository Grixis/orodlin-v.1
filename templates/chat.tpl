{strip}
<div style="width: 100%; height: 400px; margin: auto;">
  <div id="chat">
    <div id="window" style="width: 74%;float:left;padding: 0; height: 400px; overflow: auto; border: solid 1px gray;">
      <noscript>{$smarty.const.E_JS}</noscript>
    </div>
    <div id="users" style="width: 25%;float:left;padding: 0;border: solid 1px gray;height: 400px; margin: 0px; overflow: auto;">
    </div>
  <form method="post" action="chatmsgs.php" id="sender">
    <center><input type="text" name="msg" id="msg" style="width: 500px;margin-top: 5px;" maxlength="254" /> <input type="submit" value="{$smarty.const.A_SEND}" style="width: 70px;"/></center>
  </form>
  <div id="system"></div>
  {$smarty.const.CHOOSE_REFRESH_TIME}
  <select id="refresh" onChange="setRefreshTime()">
      <option value="1000">1</option>
      <option value="2000">2</option>
      <option value="3000">3</option>
      <option value="5000" selected="selected">5</option>
      <option value="10000">10</option>
      <option value="20000">20</option>
      <option value="30000">30</option>
  </select> 
  {$smarty.const.A_ROOM} 
  <select id="room" name="room">
	<option value="izba">{$smarty.const.A_INN1}</option>
	<option value="piwnica" {if $Room == "piwnica"}selected="selected"{/if}>{$smarty.const.A_INN2}</option>
  </select>

</div>
<script type="text/javascript">
var playerid = {$Playerid};
var room = '{$Room}';
{literal}
function stripHTMLtags(str)
{
    var mystr='';
    var chr='';
    var skip=false;
    var skipcancel=false;

    for (tx=0; tx<str.length; tx++)
    {
        if (skipcancel==true){skip=false;}
        chr=str.charAt(tx);
        if (chr=='<'){skip=true;skipcancel=false;}
        else if (chr=='>' && skip==true){skipcancel=true;}
        if (skip==false) mystr=mystr+chr;
    }
    return mystr;
}

function stripslashes(str)
{
    str=str.replace(/\\'/g,'&apos;');
    str=str.replace(/\\&quot;/g,'&quot;');
    str=str.replace(/\\\\/g,'\\');
    str=str.replace(/\\0/g,'\0');
    return str;
}
var timeout = 5000;
var visited = 0;

function setRefreshTime()
{
timeout = document.getElementById("refresh").options[document.getElementById("refresh").selectedIndex].value;
}

function privMessage(privid)
{
	currentmsg = document.getElementById("msg").value;
	if (currentmsg[0] != '!' || currentmsg[1] != 'w')
	{
		document.getElementById("msg").value = '!w '+privid+' '+currentmsg;
	}
	document.getElementById("msg").focus();
};

function getData()
{
    $.getJSON('chatmsgs.php?room='+room+'&visited='+visited, {}, function(json){
        setResponse(json);
    });


    visited = 1;
}
function chatRefresh()
{
	setTimeout('chatRefresh()', timeout);
	getData();
}
chatRefresh();

$(document).ready(function(){
  $('#sender').submit(function(){
    text = $('#msg').val();
    if(text == '')
    {
      return false;
    }
    $.post('chatmsgs.php?room='+room+'&visited='+visited, {msg:text});
    $('#msg').val("");
	getData();
    return false;
  });
  $('#room').change(function(){
	room = $(this).val();
	$('#window').empty();
	$('<li><b>{/literal}{$smarty.const.ROOM_CHANGED_TO}{literal} '+room+'</b></li>').appendTo("#window");
	visited = 0;
  });
});

function setResponse(json)
{
      $('#users').empty();

      for(x in json.users)
      {
         $('<div id="'+json.users[x].userid +'"><a class="priv" href="" onclick="privMessage('+json.users[x].userid+')" style="color:#A33A3A">P</a> <a href="view.php?view='+json.users[x].userid+'">'+json.users[x].user+'</a></div>').appendTo("#users");
      }

      $('#users').find('.priv').click(function(){
		  return false;
	  });

      for(x in json.msg)
      {
        if (json.msg[x].sender != '')
        {
          json.msg[x].sender = stripslashes(json.msg[x].sender);
        }
        if (json.msg[x].ownerid != 0)
        {
          json.msg[x].owner = stripslashes(json.msg[x].owner);
        }
        if (json.msg[x].text != '')
        {
          json.msg[x].text = stripslashes(json.msg[x].text);
        }
        if(json.msg[x].ownerid != 0 && json.msg[x].sender != '')
        {
          if(playerid == json.msg[x].senderid) $('<li id="'+json.msg[x].id +'" style="font-weight: bolder;" title="'+json.msg[x].date+'">{/literal}{if $Gender == 'M'}{$smarty.const.YOU_TOLD_M}{else}{$smarty.const.YOU_TOLD_F}{/if}{literal} <a href="view.php?view='+json.msg[x].ownerid+'" title="">'+json.msg[x].owner+'</a>: '+json.msg[x].text+'</li>').appendTo("#window");
          else
          {
            if (json.msg[x].gender == 'M')
            {
              toldString = '{/literal}{$smarty.const.M_TOLD}{literal}';
            }
            else
            {
              toldString = '{/literal}{$smarty.const.F_TOLD}{literal}';
            }
          $('<li id="'+json.msg[x].id +'" style="font-weight: bolder;" title="'+json.msg[x].date+'"><a href="view.php?view='+json.msg[x].senderid+'" title="">'+json.msg[x].sender+'</a> '+toldString+' '+json.msg[x].text+'</li>').appendTo("#window");
          }
        }
        else if(json.msg[x].sender != '')
        {
          if(playerid == json.msg[x].senderid)
          {
            $('<li id="'+json.msg[x].id +'" title="'+json.msg[x].date+'"><a href="view.php?view='+json.msg[x].senderid+'" style="color:#A33A3A">'+stripHTMLtags(json.msg[x].sender)+'</a>: '+json.msg[x].text+'</li>').appendTo("#window");
          }
          else
          {
            $('<li id="'+json.msg[x].id +'" title="'+json.msg[x].date+'"><a href="view.php?view='+json.msg[x].senderid+'">'+json.msg[x].sender+'</a>: '+json.msg[x].text+'</li>').appendTo("#window");
          }
        }
        else
        {
          $('<li id="'+json.msg[x].id +'" title="'+json.msg[x].date+'">&gt;&gt;&gt; '+json.msg[x].text+'</li>').appendTo("#window")
        }
        var objDiv = document.getElementById("window");
        objDiv.scrollTop = objDiv.scrollHeight;

      }

    $("#system").empty();
    str = json.users.length+'{/literal}{$smarty.const.PLAYERS}{literal}<br>';
    str = '<center>' + str + json.number +'{/literal}{$smarty.const.MESSAGES}{literal}</center>'
    $("#system").append(str);

}
{/literal}
</script>
</div>
{/strip}
