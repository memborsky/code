{include file='header.tpl'}
<!-- START: 	groups_invite_user.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='index.php?extension=Groups&amp;action=show_groups'>{$_GROUPS_LIST}</a> :: 
            <a href='index.php?extension=Groups&amp;action=show_create_group'>{$_GROUPS_CREATE}</a> :: 
            {$_GROUPS_INVITE_USER}
        </div>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='groups_section_header'>
                    {$_GROUPS_INVITE_WELCOME}
                </td>
            </tr>
        </table>
        <p />
        <form method ='post' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='do_invites_create'>
            </div>
            <table style='width: 100%;'>
                <tr>
                    <td style='width: 30%;'>
                        <span style='font-weight: bold;'>Group</span>
                    </td>
                    <td style='width: 70%;'>
                        <select name='group_id'>
                            {section name=group_list loop=$GROUP_LIST}
                                <option value='{$GROUP_LIST[$smarty.section.group_list.index][0]}' />{$GROUP_LIST[$smarty.section.group_list.index][1]}
                            {sectionelse}
                                <option value='' />{$_GROUPS_NO_GROUPS_ADMIN}
                            {/section}
                        </select>
                    </td>
                </tr>
                {if $GROUP_LIST}
                <tr>
                    <td style='vertical-align: top;'>
                        <span style='font-weight: bold;'>{$_GROUPS_MEMBERS_ADD}</span>
                        <p />
                        ( {$_GROUPS_MEMBERS_ADD_FYI} )
                    </td>
                    <td>
                        <select name='group_members[]' multiple='true' size='10' style='width: 70%;'>
                            {section name=group_members loop=$MEMBER_LIST}
                                <option value='{$MEMBER_LIST[$smarty.section.group_members.index][0]}' />{$MEMBER_LIST[$smarty.section.group_members.index][1]}
                            {/section}
                        </select>
                    </td>
                </tr>
            </table>
            <p />
            <table style='width: 100%;'>
                <tr>
                    <td style='text-align: center;'>
                        <input type='submit' name='submit' value='{$_GROUPS_SEND_INVITE}' class='input_btn'>
                    </td>
                    <td style='align: center;'>
                        <input type='reset' value='{$_RESET_FORM}' class='input_btn'>
                    </td>
                </tr>
            {/if}
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END:	groups_invite_user.tpl -->
{include file='footer.tpl'}
