{strip}
{if $Action == ""}
        {$Pollsinfo}<br /><br />

    {if $Pollid == "0"}
        <div align="center">{$Nopolls}</div><br />
    {/if}

    {if $Pollid != "0"}
        {$Lastpoll}<br /><br />
        <div align="center">
            <table width="100%">
                <tr>
                    <td colspan="2"><b>{$Question}</b>{if $PlayerRank == "Admin"} (<a href="polls.php?action=edit">{$smarty.const.EDIT}</a>){/if}</td>
                </tr>
                {if $isAnyAnswer == false}
                <tr>
                    <td colspan="2"><b>{$Noanswer}</b></td>
                </tr>
                {/if}
                {if $isAnyAnswer == true}
                    {if $Voting == "Y"}
                        <form method="post" action="polls.php?action=vote&amp;poll={$Pollid}">
                        {section name=poll loop=$Answers}
                               <tr>
                                   <td width="5%"><input type="radio" name="answer" value="{$Answers[poll]}" /></td><td>{$Answers[poll]}</td>
                               </tr>
                           {/section}
                            <tr>
                              <td colspan="2" align="center"><input type="submit" value="{$Asend}" /></td>
                          </tr>
                     </form>
                    {/if}
                    {if $Voting == "N"}
                        {section name=poll2 loop=$Answers}
                            <tr>
                                <td width="70%">{$Answers[poll2]}</td><td> - {$Tvotes}: {$Votes[poll2]} ({$Percentvotes[poll2]} %)</td>
                            </tr>
                        {/section}
                    {/if}
                {/if}
                <tr>
                    <td colspan="2">{$Sumvotes} <b>{$Summaryvotes} ({$Summaryvoting} %)</b> {$Tmembers}</td>
                </tr>
                <tr>
                    {if $Days > "0"}
                        <td colspan="2">{$Polldays} {$Days} {$Tdays}</td>
                    {/if}
                    {if $Days == "0"}
                        <td colspan="2">{$Pollend}</td>
                    {/if}
                </tr>
            </table>
        </div><br /><br />
        <a href="polls.php?action=comments&amp;poll={$Pollid}">{$Acomments}</a>: {$Commentsamount}
        <br /><br /><br />
        <a href="polls.php?action=last">{$Alast10}</a>
    {/if}
{/if}

{if $Action == "vote"}
    <div align="center">{$Message}<br /><br />
    <a href="polls.php">{$Aback}</a></div>
{/if}

{if $Action == "last"}
    {$Lastinfo}:<br /><br />
    <div align="center">
        <table width="100%">
            {section name=poll3 loop=$Questions}
                <tr>
                    <td colspan="2"><b>{$Questions[poll3]}</b></td>
                </tr>
                    {section name=poll4 loop=$Answers[poll3]}
                        <tr>
                            <td width="70%">{$Answers[poll3][poll4]}</td><td> - {$Tvotes}: {$Votes[poll3][poll4]} ({$Percentvotes[poll3][poll4]} %)</td>
                        </tr>
                    {/section}
                <tr>
                    <td colspan="2">
                        {$Sumvotes} <b>{$Summaryvotes[poll3]} ({$Percentvoting[poll3]} %)</b> {$Tmembers}<br />
                        <a href="polls.php?action=comments&amp;poll={$Pollid[poll3]}">{$Acomments}</a>: {$Commentsamount[poll3]}<br /><br />
                    </td>
                </tr>
            {/section}
        </table>
    </div><br /><br /><br />
    <a href="polls.php">{$Aback}</a>
{/if}

{if $Action == "comments"}
    {if $Amount == "0"}
        <center>{$Nocomments}</center>
    {/if}
    {if $Amount > "0"}
        {section name=update loop=$Tauthor}
            <div class="overflow"><fieldset style="width:90%">
            <legend><b>{$Tauthor[update]}</b> {if $Tdate[update] != ""} ({$Tdate[update]}) {/if}{$Writed}:</legend> {if $Rank == "Admin" || $Rank == "Staff"} <center>(<a href="polls.php?action=comments&amp;step=delete&amp;cid={$Cid[update]}">{$Adelete}</a>)</center> {/if}<br />
            {$Tbody[update]}
            </fieldset></div>
        {/section}
    {/if}
    <br /><br /><center>
    <form method="post" action="polls.php?action=comments&amp;step=add">
        {$Addcomment}:<br /><textarea name="body" rows="20" cols="70"></textarea><br />
        <input type="hidden" name="pid" value="{$Poll}" />
        <input type="submit" value="{$Aadd}" />
    </form></center>
    <br /><br />
    <a href="polls.php">{$Aback}</a>
{/if}
{if $Action == "edit"}
    {$Message}
        {if $Pollid == "0"}
            <div align="center">{$Nopolls}</div><br />
        {/if}

        {if $Pollid != "0"}
              <form method="post" action="polls.php?action=edit&amp;step=Apply">
                {$LangQuestion} <br /><textarea name="question" cols="60" rows="2">{$Question}</textarea><br />
                {$Polldays} <input type="text" size="5" name="days" value="{$Days}"/> {$Tdays}<br />
                {section name=poll loop=$Answers}
                   {$Answer} <input type="text" name="answer{$smarty.section.poll.index}" size="60" value="{$Answers[poll]}" /><br />
                {/section}
                <input type="hidden" name="amount" value="{$Amount}" />
                <input type="hidden" name="pid" value="{$Pollid}" />
                <br /><br /><input type="submit" value="{$ApplyChange}" />
             </form>
        {/if}
{/if}
{/strip}
