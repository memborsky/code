{include file='header.tpl'}
<!-- START: 	accounts_change_passwd.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <form method='post' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Accounts'>
                <input type='hidden' name='action' value='do_change_password'>
                <input type='hidden' name='user_id' value='{$USER_ID}'>
            </div>
            <div style="text-align:center;">
                <!-- Put extension specific links here -->
                <a href='index.php?extension=Accounts&amp;action=show_settings'>{$_ACCTS_SHOW_SETTINGS}</a> :: 
                {$_ACCTS_CHG_PASSWD}
            </div>
            <p />
            <table class='accts_section_header'>
                <tr>
                    <td class='accts_section_header'>
                        {$_ACCTS_CHG_PASSWD_WELCOME}
                    </td>
                </tr>
            </table>
            <p />
            <table class='col_main_table'>
                <tr>
                    <table style='width: 100%; background: #e6e6e6;'>
                    <tr>	
                        <td class='acct_main_item_header' colspan='2'>
                            {$_ACCTS_CHG_PASSWD}
                        </td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_CUR_PASSWD}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='password' name='current_password' value='{$CUR_PASSWORD}' class='input_txt'>
                        </td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_NEW_PASSWD}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='password' name='new_password' class='input_txt'>
                        </td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_VER_PASSWD}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='password' name='verify_password' class='input_txt'>
                        </td>
                    </table>
                </tr>
            </table>
            <p />
            <table style='width: 100%;'>
                <tr>
                    <td style='text-align:right;'>
                        <input type='submit' name='submit' value='{$_ACCTS_CHG_PASSWD}' class='input_btn'>
                    </td>
                    <td style='text-align:left;'>
                        <input type='reset' value='{$_ACCTS_UNDO_SETTINGS}' class='input_btn'>
                    </td>
                </tr>
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END:	accounts_change_passwd.tpl -->
{include file='footer.tpl'}
