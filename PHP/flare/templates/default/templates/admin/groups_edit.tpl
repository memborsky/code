{include file='header.tpl'}
<!-- START: 	admin/groups_edit.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='index.php?extension=Groups&amp;action=show_groups'>{$_GROUPS_LIST}</a> :: 
            <a href='index.php?extension=Groups&amp;action=show_create_group'>{$_GROUPS_CREATE}</a> :: 
            <a href='admin.php?extension=Groups&amp;action=show_settings'>Settings</a> ::
            <a href='index.php?extension=Groups&amp;action=show_invites_create'>{$_GROUPS_INVITE_USER}</a>
        </div>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='groups_section_header'>
                    {$_GROUPS_EDIT_WELCOME}
                </td>
            </tr>
        </table>
        <p />
        <form method ='post' action='index.php' name='groups'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='do_edit_group'>
            </div>
            <table style='width: 100%;'>
                <tr>
                    <td style='width: 30%;'>
                        <span style='font-weight: bold;'>{$_GROUPS_GROUP_NAME}</span>
                    </td>
                    <td style='width: 70%;'>
                        <input type='hidden' name='group_id' maxlength='32' value='{$GROUP_ID}' />
                        <input type='text' name='group_name' maxlength='255' value='{$GROUP_NAME}' disabled='disabled' class='input_txt' />
                    </td>
                </tr>
                <tr>
                    <td valign='top'>
                        <span style='font-weight: bold;'>{$_GROUPS_CURRENT_MEMBERS}</span>
                        <p />
                        {$_GROUPS_CURRENT_MEMBERS_FYI}
                    </td>
                    <td>
                        <select name='group_members[]' multiple='true' size='10' style='width: 70%;'>
                            {section name=group_members loop=$MEMBER_LIST}
                                <option value='{$MEMBER_LIST[$smarty.section.group_members.index].user_id}' />{$MEMBER_LIST[$smarty.section.group_members.index].username}
                            {/section}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='vertical-align: top;'>
                        <span style='font-weight: bold;'>Current Share Amount</span>
                        <p />
                        {$_GROUPS_SHARE_AMNT_MESG}
                    </td>
                    <td style='vertical-align: top;'>
                        <input type='hidden' name='old_share_amount' value='{$SHARE_AMOUNT}'>
                        <input type='text' name='new_share_amount' value='{$SHARE_AMOUNT}' size='10' maxlength='10' class='input_btn'> Megabytes. You have {$TOTAL_QUOTA_REMAINING} Megabytes available that you can share.
                    </td>
                </tr>
                <tr>
                    <td style='vertical-align: top;'>
                        <span style='font-weight: bold;'>Group Type</span>
                    </td>
                    <td style='vertical-align: top;'>
                        {if $GROUP_TYPE == 1}
                            <input type='radio' name='group_type' value='trusted' onClick='javascript:group_desc("trusted")' CHECKED />Trusted
                            <input type='radio' name='group_type' value='distribution' onClick='javascript:group_desc("distribution")' />Distribution
                        {else}
                            <input type='radio' name='group_type' value='trusted' onClick='javascript:group_desc("trusted")' />Trusted
                            <input type='radio' name='group_type' value='distribution' onClick='javascript:group_desc("distribution")' CHECKED />Distribution
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div id='trusted' style='display: block;'>
                            In a trusted group, all group members can write to the group folder, and everyone can read from the group folder.
                        </div>
                        <div id='distribution' style='display: none;'>
                            In a distribution group, only the owner of the group can write to the folder. Everyone else can only read from the folder.
                        </div>
                    </td>
                </tr>
            </table>
            <p />
            <table style='width: 100%;'>
                <tr>
                    <td style='text-align: center;'>
                        <input type='submit' name='submit' value='{$_SAVE_CHGS}' class='input_btn'>
                    </td>
                    <td style='text-align: center;'>
                        <input type='reset' value='{$_RESET_FORM}' class='input_btn'>
                    </td>
                </tr>
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END:	admin/groups_edit.tpl -->
{include file='footer.tpl'}
