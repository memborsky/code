{include file='header_empty.tpl'}
<!-- START: admin/login.tpl -->
    <table style='width: 100%'>
        <div>
            <h2>{$_WELCOME_BANNER}</h2>
        </div>
    </table>
    <p />
    {if $_MESSAGE}
        <font class='login_failure'>{$_MESSAGE}</font>
    {/if}
    <table style='width: 100%;'>
        <tr>
            <td class='login_announce'>
            <a href='admin.php'><img src='images/flare_logo.png' width='150' height='200' alt='{$_LOGIN_LOGO}' title='{$_LOGIN_LOGO}'></a>
            </td>
            <td rowspan='2' valign='top'>
                <table class='login_news'><tr><td>
                <h3>{$_LOGIN_SYS_ANNOUNCE}</h3>
                    <table style='width: 100%;'>
                        <tr>
                        <td class='login_announce_header'>Date Posted</td>
                        <td class='login_announce_header'>Announcement</td>
                        </tr>
                        {section name=news loop=$MESG_CONTENT}
                        <tr class='{cycle values="login_news_row_one,login_news_row_two"}'>
                            <td>{$MESG_CONTENT[news].date}</td>
                            <td>
                                {$MESG_CONTENT[news].subject|truncate:100} ( <a href='index.php?extension=Authentication&amp;action=announcement&amp;id={$MESG_CONTENT[news].id}'>read more</a> )</td>
                        </tr>
                        {sectionelse}
                        <tr>
                            <td></td>
                            <td>{$_NO_NEWS}</td>
                        </tr>
                        {/section}
                    </table>
                </td></tr></table>

                <p />
                <table class='login_news'>
                    <tr style='font-weight: bold;'>
                        <td>Posted By: {$AUTHOR} on {$DATE}</td>
                    </tr>
                    <tr>
                        <td>{$LOGIN_PAGE_MESG}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class='login_announce'>
                <div id='login_creds'>
                    <form method='post' action='index.php'>
                        <div>
                            <input type='hidden' name='extension' value='Authentication'>
                            <input type='hidden' name='action' value='login'>
                        </div>
                        <div>
                            {$_USR_USERNAME} : <br>
                            <input type='text' name='username' class='top'>
                        </div>
                        <p />
                        <div>
                            {$_USR_PASSWORD} : <br>
                            <input type='password' name='password' class='middle'>
                        </div>
                        <p />
                        <input type='submit' name='submit' value='{$_LOGIN_BUTTON}' class='input_btn'>
                    </form>
                </div>
            </td>
        </tr>
    </table>
    <p />
<!-- END: admin/login.tpl -->
{include file='footer.tpl'}
