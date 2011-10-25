{include file='header.tpl'}
<!-- START: 	admin/filesystem_owners.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align:top;'>
        <div style="text-align: center;">
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Filesystem&amp;action=show_settings'>{$_SETTINGS}</a>
            :: <a href='index.php?extension=Filesystem&amp;action=show_files'>View Filesystem</a>
            :: <a href='admin.php?extension=Filesystem&amp;action=show_permissions'>Change File/Folder Permissions</a>
        </div>
        <p />
        &nbsp;<p />
        <form method ='post' action='admin.php' name='owners'>
            <div>
                <input type='hidden' name='extension' value='Filesystem'>
                <input type='hidden' name='action' value='do_change_owner'>
            </div>
            <table class='col_main_table'>
                <tr>
                    <td class='acct_main_item_header'>
                        File
                    </td>
                    <td class='acct_main_item_header'>
                        Owner
                    </td>
                    <td class='acct_main_item_header'>
                        Group
                    </td>
                </tr>
            {section name=owners loop=$OWNERS}
                <tr class='accts_main_adm_user_row_a'>
                    <input type='hidden' name='item[]' value='{$OWNERS[$smarty.section.owners.index].file_id}'></td>
                    <td>
                        {$OWNERS[$smarty.section.owners.index].file}
                    </td>
                    <td>
                        <select name='owner[{$OWNERS[$smarty.section.owners.index].file_id}]'>
                            <option value='{$OWNERS[$smarty.section.owners.index].user_id}'>{$OWNERS[$smarty.section.owners.index].username}
                            <option value='-'>No Owner
                            <option value='{$OWNERS[$smarty.section.owners.index].user_id}'>----------
                            {section name=users loop=$ALL_USERS}
                            <option value='{$ALL_USERS[$smarty.section.users.index].user_id}'>{$ALL_USERS[$smarty.section.users.index].username}
                            {/section}
                        </select>
                    </td>
                    <td>
                        <select name='group[{$OWNERS[$smarty.section.owners.index].file_id}]'>
                            <option value='{$OWNERS[$smarty.section.owners.index].group_id}'>{$OWNERS[$smarty.section.owners.index].group_name}
                            <option value='-'>No Group
                            <option value='{$OWNERS[$smarty.section.owners.index].group_id}'>----------
                            {section name=groups loop=$ALL_GROUPS}
                            <option value='{$ALL_GROUPS[$smarty.section.groups.index].group_id}'>{$ALL_GROUPS[$smarty.section.groups.index].group_name}
                            {/section}
                        </select>
                    </td>
                </tr>
            {sectionelse}
                <tr class='accts_main_adm_user_row_a'>
                    <td colspan='6'>
                        Could not retrieve owners, maybe permissions need to be set first?
                    </td>
                </tr>
            {/section}
            </table>
            <p />
            <div style='text-align: center'>
                <input type='submit' name='submit' value='Change Ownership' class='input_btn'>
                <input type='reset' name='reset' value='Reset Changes' class='input_btn'>
            </div>
        </form>
    </td>
</tr>
</table>
<!-- END: 	admin/filesystem_owners.tpl -->
{include file='footer.tpl'}
