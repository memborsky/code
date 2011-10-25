{include file=header.tpl}
<!-- START:	admin/reporting_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            Summary
            :: <a href='admin.php?extension=Reporting_System&amp;action=show_add_system_message'>Announce New System Message</a>
        </div>
        <p />
    <p />
    <form method ='post' action='admin.php' name='messages'>
        <div>
            <input type='hidden' name='extension' value='Reporting_System'>
            <input type='hidden' name='action' value='update_message_info'>
        </div>
        <table style='width: 100%;'>
            <tr>
                <td class='groups_actions'>
                    <select name='message_action' onChange='javascript:actions(document.messages.message_action.options[document.messages.message_action.selectedIndex].value)'>
                    {if $MESSAGE_LIST}
                        <option value='' />:: {$_WITH_SELECTED} ::
                        <option value='show_change_messages' />Edit Message(s)
                        <option value='do_delete_messages' />Delete Message(s)
                    {else}
                        <option value='' />:: {$_NO_ACTIONS} ::
                    {/if}
                    </select>
                </td>
            </tr>
        </table>
        <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header' style='width: 1%;'>
                    <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                </td>
                <td class='acct_main_item_header'>
                    Message
                </td>
                <td class='acct_main_item_header'>
                    Date Posted
                </td>
                <td class='acct_main_item_header'>
                    Posted By
                </td>
            </tr>
            {section name=msg loop=$MESSAGE_LIST}
                <tr style='background: #fff;'>
                    <td>
                        <input type='checkbox' name='mesg_id[]' value='{$MESSAGE_LIST[msg].mesg_id}' />
                    </td>
                    <td>
                        {$MESSAGE_LIST[msg].subject}
                    </td>
                    <td>
                        {$MESSAGE_LIST[msg].date}
                    </td>
                    <td>
                        {$MESSAGE_LIST[msg].author}
                    </td>
                </tr>
            {sectionelse}
                <tr style='background: #fff;'>
                    <td colspan='8'>
                        No messages currently exist
                    </td>
                </tr>
            {/section}
        </table>
    </form>

    <p />&nbsp;<p />

    <form method='post' action='admin.php' name='reports'>
        <div>
            <input type='hidden' name='extension' value='Reporting_System'>
            <input type='hidden' name='action' value='update_report_info'>
        </div>
        <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header' style='width: 50%;'>
                    Report Name
                </td>
                <td class='acct_main_item_header' style='width: 50%;'>
                    Description
                </td>
            </tr>
            {section name=rpt loop=$REPORT_LIST}
            <tr style='background: #fff;'>
                <td>
                    <a href='admin.php?extension=Reporting_System&amp;action=show_report&amp;report_id={$REPORT_LIST[rpt].report_id}' value='{$REPORT_LIST[rpt].report_id}' />
                    {$REPORT_LIST[rpt].name}
                </td>
                <td>
                    {$REPORT_LIST[rpt].description}
                </td>
            </tr>
            {sectionelse}
            <tr style='background: #fff;'>
                <td colspan='8'>
                    No reports currently exist
                </td>
            </tr>
            {/section}
        </table>
    </form>
    </td>
</tr>
</table>
<!-- END:	admin/reporting_main.tpl -->
{include file=footer.tpl}
