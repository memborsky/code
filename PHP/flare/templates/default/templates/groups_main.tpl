<!-- START: 	groups_main.tpl -->
{include file='header.tpl'}
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            {$_GROUPS_LIST} :: 
            <a href='index.php?extension=Groups&amp;action=show_create_group'>{$_GROUPS_CREATE}</a> :: 
            <a href='index.php?extension=Groups&amp;action=show_invites_create'>{$_GROUPS_INVITE_USER}</a> ::
            <a href='index.php?extension=Groups&amp;action=show_request_join'>{$_GROUPS_REQUEST_JOIN}</a>
        </div>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='groups_section_header'>
                    {$_GROUPS_WELCOME}
                </td>
            </tr>
        </table>	
        <p />
        <form name='my_admin_group_actions' method='post' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='show_groups'>
            </div>
        <table class='groups_section_table'>
        <tr class='groups_main_item_header'>
            <td style='width: 50%;'>
                {$_GROUPS_MINE}
            </td>
            <td class='groups_actions'>
                <select name='group_action' onChange='javascript:actions(document.my_admin_group_actions.group_action.options[document.my_admin_group_actions.group_action.selectedIndex].value)'>
                {if $GROUPS_ADMIN}
                    <option value='' />:: {$_WITH_SELECTED} ::
                    <option value='my_admin_edit' />{$_GROUPS_EDIT_GROUPS}
                    <option value='my_admin_delete' />{$_GROUPS_DELETE_GROUPS}
                {else}
                    <option value='' />:: {$_NO_ACTIONS} ::
                {/if}
                </select>
            </td>
        </tr>
        <tr style='background: #e6e6e6;'>
            <td colspan='2'>
                <table style='width: 100%;'>
                    {section name=groups_admin loop=$GROUPS_ADMIN}
                    <tr>
                        <td class='groups_main_checkbox'>
                            <input type='checkbox' name='group_id[]' value='{$GROUPS_ADMIN[$smarty.section.groups_admin.index].group_id}' />
                        </td>
                        <td class='groups_main_col_one'>
                            <a href='index.php?extension=Filesystem&amp;path=/groups/{$GROUPS_ADMIN[$smarty.section.groups_admin.index].group_name}'>{$GROUPS_ADMIN[$smarty.section.groups_admin.index].group_name}</a>
                        </td>
                    </tr>
                    {sectionelse}
                    <tr>
                        <td class='groups_main_col_one'>
                            {$_NONE}
                        </td>
                    </tr>
                    {/section}
                </table>
            </td>
        </tr>
        </table>
        <p />
        </form>
        <form method='post' action='index.php' name='my_groups_in'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='do_group_in_actions'>
            </div>
        <table class='groups_section_table'>
        <tr>
            <td class='groups_main_item_header'>
                {$_GROUPS_IN}
            </td>
            <td class='groups_actions'>
                <select name='group_action' onChange='javascript:actions(document.my_groups_in.group_action.options[document.my_groups_in.group_action.selectedIndex].value)'>
                {if $GROUPS_NOT_ADMIN}
                    <option value='' />:: {$_WITH_SELECTED} ::
                    <option value='my_groups_in_withdraw' />{$_GROUPS_LEAVE_GROUPS}
                {else}
                    <option value='' />:: {$_NO_ACTIONS} ::
                {/if}
                </select>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <table style='width: 100%; background: #e6e6e6;'>
                    {section name=groups_not_admin loop=$GROUPS_NOT_ADMIN}
                    <tr>
                        <td class='groups_main_checkbox'>
                            <input type='checkbox' name='group_id[]' value='{$GROUPS_NOT_ADMIN[$smarty.section.groups_not_admin.index].group_id}' />
                        </td>
                        <td class='groups_main_col_one'>
                            <a href='index.php?extension=Filesystem&amp;path=/groups/{$GROUPS_NOT_ADMIN[$smarty.section.groups_not_admin.index].group_name}'>{$GROUPS_NOT_ADMIN[$smarty.section.groups_not_admin.index].group_name}</a>
                        </td>
                    </tr>
                    {sectionelse}
                    <tr>
                        <td class='groups_main_col_one'>
                            {$_NONE}
                        </td>
                    </tr>
                    {/section}
                </table>
            </td>
        </tr>
        </table>
        </form>
        <p />
        <form method='post' name='invites_mine' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='do_invites_mine_actions'>
            </div>
        <table style='width: 100%;'>
            <tr>
                <td class='groups_section_header'>
                    {$_GROUPS_INVITES_MINE}
                </td>
            </tr>
        </table>
        <p />
        <table class='groups_section_table'>
        <tr>
            <td class='groups_main_item_header'>
                {$_GROUPS_INVITES}
            </td>
            <td class='groups_actions'>
                <select name='group_action' onChange='javascript:actions(document.invites_mine.group_action.options[document.invites_mine.group_action.selectedIndex].value)'>
                {if $INVITES_PENDING_MINE}
                    <option value='' />:: {$_WITH_SELECTED} ::
                    <option value='accept_invite' />{$_GROUPS_ACCEPT_INVITE}
                    <option value='decline_invite' />{$_GROUPS_DECLINE_INVITE}
                {else}
                    <option value='' />:: {$_NO_ACTIONS} ::
                {/if}
                </select>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <table style='width: 100%; background: #e6e6e6;'>
                    {section name=invites_mine loop=$INVITES_PENDING_MINE}
                        <tr>
                            <td class='groups_main_checkbox'>
                                <input type='checkbox' name='invites_mine[]' value='{$INVITES_PENDING_MINE[$smarty.section.invites_mine.index].group_id}'>
                            </td>
                            <td class='groups_main_col_one'>
                                {$_GROUPS_RECEIVED_INVITE_FROM} <span style='font-weight: bold'>{$INVITES_PENDING_MINE[$smarty.section.invites_mine.index].username}</span> {$_GROUPS_TO_JOIN} <span style='font-weight: bold;'>{$INVITES_PENDING_MINE[$smarty.section.invites_mine.index].group_name}</span>
                            </td>
                        </tr>
                    {sectionelse}
                        <tr>
                            <td class='groups_main_col_one'>
                                {$_NONE}
                            </td>
                        </tr>
                    {/section}
                </table>
            </td>
        </tr>
        </table>
        </form>
        <p />
        <form method='post' name='invites_sent' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='do_invites_sent_actions'>
            </div>
        <table class='groups_section_table'>
        <tr>
            <td class='groups_main_item_header'>
                {$_GROUPS_INVITES_SENT}
            </td>
            <td class='groups_actions'>
                <select name='group_action' onChange='javascript:actions(document.invites_sent.group_action.options[document.invites_sent.group_action.selectedIndex].value)'>
                {if $INVITES_PENDING_SENT}
                    <option value='' />:: {$_WITH_SELECTED} ::
                    <option value='retract_invite' />{$_GROUPS_RETRACT_INVITE}
                {else}
                    <option value='' />:: {$_NO_ACTIONS} ::
                {/if}
                </select>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <table style='width: 100%; background: #e6e6e6;'>
                    {section name=invites_sent loop=$INVITES_PENDING_SENT}
                        <tr>
                            <td class='groups_main_checkbox'>
                                <input type='checkbox' name='invites_sent[{$INVITES_PENDING_SENT[$smarty.section.invites_sent.index].to_user_id}]' value='{$INVITES_PENDING_SENT[$smarty.section.invites_sent.index].group_id}'>
                            </td>
                            <td class='groups_main_col_one'>
                                Invite sent to <span style='font-weight: bold;'>{$INVITES_PENDING_SENT[$smarty.section.invites_sent.index].to_user}</span> to join group <span style='font-weight: bold;'>{$INVITES_PENDING_SENT[$smarty.section.invites_sent.index].group_name}</span>
                            </td>
                        </tr>
                    {sectionelse}
                        <tr>
                            <td class='groups_main_col_one'>
                                {$_NONE}
                            </td>
                        </tr>
                    {/section}
                </table>
            </td>
        </tr>
        </table>
        </form>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='groups_section_header'>
                    My Requests on F.U.E.L
                </td>
            </tr>
        </table>
        <p />
        <form method='post' name='requests_sent' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='do_requests_sent_actions'>
            </div>
        <table class='groups_section_table'>
        <tr>
            <td class='groups_main_item_header'>
                Requests That I Have Sent to Join Other Groups
            </td>
            <td class='groups_actions'>
                <select name='group_action' onChange='javascript:actions(document.requests_sent.group_action.options[document.requests_sent.group_action.selectedIndex].value)'>
                {if $REQUESTS_PENDING_SENT}
                    <option value='' />:: {$_WITH_SELECTED} ::
                    <option value='retract_request' />{$_GROUPS_RETRACT_REQUEST}
                {else}
                    <option value='' />:: {$_NO_ACTIONS} ::
                {/if}
                </select>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <table style='width: 100%; background: #e6e6e6;'>
                    {section name=rqst loop=$REQUESTS_PENDING_SENT}
                        <tr>
                            <td class='groups_main_checkbox'>
                                <input type='checkbox' name='requests_sent[]' value='{$REQUESTS_PENDING_SENT[rqst].request_id}]'>
                            </td>
                            <td class='groups_main_col_one'>
                                Request sent to <span style='font-weight: bold;'>{$REQUESTS_PENDING_SENT[rqst].username}</span> to join group <span style='font-weight: bold;'>{$REQUESTS_PENDING_SENT[rqst].group_name}</span>
                            </td>
                        </tr>
                    {sectionelse}
                        <tr>
                            <td class='groups_main_col_one'>
                                {$_NONE}
                            </td>
                        </tr>
                    {/section}
                </table>
            </td>
        </tr>
        </table>
        </form>
        <p />
        <form method='post' name='requests_received' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='do_requests_received_actions'>
            </div>
        <table class='groups_section_table'>
        <tr>
            <td class='groups_main_item_header'>
                Requests That I Have Received From Others
            </td>
            <td class='groups_actions'>
                <select name='group_action' onChange='javascript:actions(document.requests_received.group_action.options[document.requests_received.group_action.selectedIndex].value)'>
                {if $REQUESTS_PENDING_RECEIVED}
                    <option value='' />:: {$_WITH_SELECTED} ::
                    <option value='deny_request' />{$_GROUPS_DENY_REQUEST}
                    <option value='allow_request' />{$_GROUPS_ALLOW_REQUEST}
                {else}
                    <option value='' />:: {$_NO_ACTIONS} ::
                {/if}
                </select>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <table style='width: 100%; background: #e6e6e6;'>
                    {section name=rqst loop=$REQUESTS_PENDING_RECEIVED}
                        <tr>
                            <td class='groups_main_checkbox'>
                                <input type='checkbox' name='requests_received[{$REQUESTS_PENDING_RECEIVED[rqst].user_id}]' value='{$REQUESTS_PENDING_RECEIVED[rqst].group_id}'>
                            </td>
                            <td class='groups_main_col_one'>
                                Received a request from <span style='font-weight: bold;'>{$REQUESTS_PENDING_RECEIVED[rqst].username}</span> to join your group <span style='font-weight: bold;'>{$REQUESTS_PENDING_RECEIVED[rqst].group_name}</span>
                            </td>
                        </tr>
                    {sectionelse}
                        <tr>
                            <td class='groups_main_col_one'>
                                {$_NONE}
                            </td>
                        </tr>
                    {/section}
                </table>
            </td>
        </tr>
        </table>
        </form>
    </td>
</tr>
</table>
<!-- END: 	groups_main.tpl -->
{include file='footer.tpl'}
