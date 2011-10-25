{include file=header.tpl}
<!-- START: 	admin/email_mailing_list_edit.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            Send New Email ::
            Mailing Lists ::
            <a href='admin.php?extension=Email&amp;action=show_settings'>{$_SETTINGS}</a>
        </div>
        <p />
        <form method='post' action='admin.php' name='help'>
        <div>
            <input type='hidden' name='extension' value='Email'>
            <input type='hidden' name='action' value='do_change_mailing_list'>
        </div>
        <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header'>
                    <span style='font-weight: bold;'>List Name:</span>
                </td>
            </tr>
            <tr style='background-color: #fff;'>
                <td style='vertical-align: top;' colspan='2'>
                    <input type='hidden' name='list_id' value='{$LIST_ID}'>
                    <input type='text' name='list_name' value='{$NAME}' size='60' class='input_txt' style='border-color: #fff; padding-left: 20px;'>
                </td>
            </tr>
        </table>
        <p />
        <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header' style='vertical-align: top; width: 50%; text-align: center;'>
                    Select Members to Remove
                </td>
                <td class='acct_main_item_header' style='vertical-align: top; width: 50%; text-align: center;'>
                    Select Members to Add
                </td>
            </tr>
            <tr>
                <td style='background-color: #fff;' valign='top'>
                    <table class='col_main_table'>
                    {section name=cur loop=$CURRENT}
                        <tr style='background-color: #fff;'><td>
                        <input type='checkbox' value='{$CURRENT[cur].id}' name='rm_recipients[]'>{$CURRENT[cur].name}
                        </td></tr>
                    {sectionelse}
                        <tr><td>
                        This mailing list does not have any members
                        </td></tr>
                    {/section}
                    </table>
                </td>
                <td style='background-color: #fff;' valign='top'>
                    <table class='col_main_table'>
                    {section name=rem loop=$REMAINING}
                        <tr style='background-color: #fff;'><td>
                        <input type='checkbox' value='{$REMAINING[rem].id}' name='add_recipients[]'>{$REMAINING[rem].name}
                        </td></tr>
                    {sectionelse}
                        <tr><td>
                        There are no users available to add to this list
                        </td></tr>
                    {/section}
                    </table>
                </td>
            </tr>
        </table>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: center;'>
                    <input type='submit' name='submit' value='Save Changes' class='input_btn'>
                </td>
            </tr>
        </table>
    </form>
    </td>
</tr>
</table>
<!-- END: 	admin/email_mailing_list_edit.tpl -->
{include file=footer.tpl}
