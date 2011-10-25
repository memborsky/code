{include file=header.tpl}
<!-- START: 	admin/email_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            Send New Email ::
            <a href='admin.php?extension=Email&amp;action=show_mailing_lists'>Mailing Lists</a> ::
            <a href='admin.php?extension=Email&amp;action=show_settings'>{$_SETTINGS}</a>
        </div>
        <p />
        <form method='post' action='admin.php' name='help'>
        <div>
            <input type='hidden' name='extension' value='Email'>
            <input type='hidden' name='action' value='do_write_email'>
        </div>
            <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header'>
                    Email Recipients
                </td>
            </tr>
            <tr>
                <td width='50%'>
                    <div style='font-weight: bold; text-align: center;'>Individual Recipients</div>
                </td>
                <td width='50%'>
                    <div style='font-weight: bold; text-align: center;'>Mailing Lists</div>
                </td>
            </tr>
            <tr style='background-color: #fff;'>
                <td width='50%'>
                    <select name='recipients[]' multiple='multiple' style='width: 100%;' size='10' class='input_txt'>
                        {section name=usr loop=$USERS}
                            <option value='{$USERS[usr].email}' />{$USERS[usr].username}
                        {/section}
                    </select>
                </td>
                <td width='50%'>
                    <select name='mailing_lists[]' multiple='multiple' style='width: 100%;' size='10' class='input_txt'>
                        {section name=lst loop=$LISTS}
                            <option value='{$LISTS[lst].id}' />{$LISTS[lst].name}
                        {/section}
                    </select>
                </td>
            </tr>
            </table>
            <p />
            <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header'>
                    Email Subject
                </td>
            </tr>
            <tr style='background-color: #fff;'>
                <td>
                    <input type='text' name='email_subject' class='input_txt' style='width: 100%;'/>
                </td>
            </tr>
            </table>
            <p />
            <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header'>
                    Email Body
                </td>
            </tr>
            <tr style='background-color: #fff;'>
                <td>
                    <textarea id='textblock' cols='80' rows='10' name='email_body' class='input_txt' style='width: 100%; height: 200px;'></textarea>
                </td>
            </tr>
        </table>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: right;'>
                    <input type='submit' name='submit' value='Send Email' class='input_btn'>
                </td>
                <td style='text-align: left;'>
                    <input type='reset' name='reset' value='{$_RESET_FORM}' class='input_btn'>
                </td>
            </tr>
        </table>
    </form>
    </td>
</tr>
</table>
<!-- END: 	admin/email_main.tpl -->
{include file=footer.tpl}
