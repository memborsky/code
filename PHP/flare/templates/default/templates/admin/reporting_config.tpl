{include file=header.tpl}
<!-- START:	reporting_reports.tpl -->
<table class='main'>
<tr>
    <td width='80%' valign='top'>
        <div style="text-align:center;">
            <a href='admin.php?extension=Reporting_System&amp;=show_summary'>Summary</a>
            :: <a href='admin.php?extension=Reporting_System&amp;action=show_add_system_message'>Announce New System Message</a>
            :: {$_SETTINGS}
        </div>
        <p />
    &nbsp;<p />
    <form method ='post' action='admin.php' name='groups'>
    <input type='hidden' name='extension' value='Groups'>
    <input type='hidden' name='action' value='update_group_info'>
    <table width='100%'>
        <tr>
            <td class='groups_actions'>
                <select name='group_action' onChange='javascript:actions(document.groups.group_action.options[document.groups.group_action.selectedIndex].value)'>
                {if $GROUP_LIST}
                    <option value='' />:: {$_WITH_SELECTED} ::
                    <option value='show_change_groups' />{$_REPORT_SYS_CHANGE_GROUPS}
                    <option value='do_delete_groups' />{$_REPOR_SYS_DEL_GROUPS}
                {else}
                    <option value='' />:: {$_NO_ACTIONS} ::
                {/if}
                </select>
            </td>
        </tr>
    </table>
    <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header'>
                <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
            </td>
            <td class='acct_main_item_header'>
                Group Name
            </td>
            <td class='acct_main_item_header'>
                Group Admin
            </td>
            <td class='acct_main_item_header'>
                Creation Date
            </td>
            <td class='acct_main_item_header'>
                Home Directory
            </td>
        </tr>
    </table>
    </td>
</tr>
</table>
<!-- END:	reporting_reports.tpl -->
{include file=footer.tpl}
