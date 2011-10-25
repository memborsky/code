{include file=header.tpl}
<!-- START: 	admin/groups_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            {$_GROUPS_ADM_SHOW_ALL_GROUPS}
            :: <a href='admin.php?extension=Groups&amp;action=show_add_group'>{$_GROUPS_CREATE}</a>
            :: <a href='admin.php?extension=Groups&amp;action=show_settings'>Settings</a>
        </div>
        <p />
        <table class='accts_section_header'>
            <tr>
                <td class='accts_section_header'>
                    {$_ACCTS_WELCOME}
                </td>
            </tr>
        </table>
        <p />
        <form method ='post' action='admin.php' name='groups'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='update_group_info'>
            </div>
            <table style='width: 100%;'>
                <tr>
                    <td class='groups_actions'>
                        <select name='group_action' onChange='javascript:actions(document.groups.group_action.options[document.groups.group_action.selectedIndex].value)'>
                        {if $GROUP_LIST}
                            <option value='' />:: {$_WITH_SELECTED} ::
                            <option value='show_edit_group' />{$_GROUPS_CHANGE_GROUPS}
                            <option value='do_delete_groups' />{$_GROUPS_DELETE_GROUPS}
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
                        {$_GROUPS_GROUP_NAME}
                    </td>
                    <td class='acct_main_item_header'>
                        {$_GROUPS_ADMIN}
                    </td>
                    <td class='acct_main_item_header'>
                        {$_GROUPS_CREATION_DATE}
                    </td>
                    <td class='acct_main_item_header'>
                        {$_GROUPS_GROUPDIR}
                    </td>
                    <td class='acct_main_item_header'>
                        {$_GROUPS_QUOTA_TOTAL}
                    </td>
                    <td class='acct_main_item_header'>
                        {$_GROUPS_QUOTA_FREE}
                    </td>
                    <td class='acct_main_item_header'>
                        Group Type
                    </td>
                    <td class='acct_main_item_header'>
                        {$_GROUPS_NUM_MEMBERS}
                    </td>
                </tr>
                {section name=group_list loop=$GROUP_LIST}
                    <tr style='background: #fff;'>
                        <td>
                            <input type='checkbox' name='group_id[]' value='{$GROUP_LIST[$smarty.section.group_list.index].group_id}' />
                        </td>
                        <td>
                            {$GROUP_LIST[$smarty.section.group_list.index].group_name|truncate:50}
                        </td>
                        <td>
                            <a href='admin.php?extension=Accounts&amp;action=show_change_accounts&amp;account[0]={$GROUP_LIST[$smarty.section.group_list.index].admin_id}'>{$GROUP_LIST[$smarty.section.group_list.index].username}</a>
                        </td>
                        <td>
                            {$GROUP_LIST[$smarty.section.group_list.index].creation_date}
                        </td>
                        <td>
                            <a href='index.php?extension=Filesystem&amp;action=show_files&amp;path={$GROUP_LIST[$smarty.section.group_list.index].truncated_dir}'>{$GROUP_LIST[$smarty.section.group_list.index].home_dir|truncate:50}</a>
                        </td>
                        <td>
                            {$GROUP_LIST[$smarty.section.group_list.index].quota_total}
                        </td>
                        <td>
                            {$GROUP_LIST[$smarty.section.group_list.index].quota_free}
                        </td>
                        <td>
                            {if $GROUP_LIST[$smarty.section.group_list.index].group_type == 1}
                                Trusted
                            {else}
                                Distribution
                            {/if}
                        </td>
                        <td style='text-align: center;'>
                            <a href='#' onClick="javascript:window.open('admin.php?extension=Groups&amp;action=show_group_members&amp;group_id={$GROUP_LIST[$smarty.section.group_list.index].group_id}','popup','width=800,height=400,resizable=yes,scrollbars=yes')">{$GROUP_LIST[$smarty.section.group_list.index].count}</a>
                        </td>
                    </tr>
                {sectionelse}
                    <tr style='background: #fff;'>
                        <td colspan='9'>
                            {$_GROUPS_NO_GROUPS_EXIST}
                        </td>
                    </tr>
                {/section}
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END:	admin/groups_main.tpl -->
{include file=footer.tpl}
