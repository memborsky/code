{include file='header.tpl'}
<!-- START: 	groups_request_join.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='index.php?extension=Groups&amp;action=show_groups'>{$_GROUPS_LIST}</a> :: 
            <a href='index.php?extension=Groups&amp;action=show_create_group'>{$_GROUPS_CREATE}</a> :: 
            <a href='index.php?extension=Groups&amp;action=show_invites_create'>{$_GROUPS_INVITE_USER}</a> ::
            {$_GROUPS_REQUEST_JOIN}
        </div>
        <p />
        <table class='accts_section_header'>
            <tr>
                <td class='accts_section_header'>
                    Request to Join a Group
                </td>
            </tr>
        </table>
        <p />
        <form method='post' action='index.php' name='groups'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='do_request_join'>
            </div>
            <table class='col_main_table'>
                <tr>
                    <td style='width: 1%'>
                        <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                    </td>
                    <td class='acct_main_item_header'>
                        Group Name
                    </td>
                    <td class='acct_main_item_header'>
                        Group Administrator
                    </td>
                </tr>
            {section name=gps loop=$GROUPS}
                <tr class='accts_main_adm_user_row_a'>
                    <td><input type='checkbox' name='groups[{$GROUPS[gps].group_id}]' value='{$GROUPS[gps].admin_id}'></td>
                    <td>{$GROUPS[gps].group_name}</td>
                    <td>{$GROUPS[gps].username}</td>
                </tr>
            {/section}
            </table>
    <p />
    <table style='width: 100%;'>
        <tr>
            <td align='center'>
                <input type='submit' name='submit' value='Send Request to Join' class='input_btn'>
            </td>
        </tr>
    </table>
    </form>
</td>
</tr>
</table>
<!-- END: 	groups_request_join.tpl -->
{include file='footer.tpl'}
