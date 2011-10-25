{include file='header.tpl'}
<!-- START: 	groups_add.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Groups&amp;action=show_groups'>{$_GROUPS_LIST}</a> 
            :: {$_GROUPS_CREATE}
            :: <a href='admin.php?extension=Groups&amp;action=show_settings'>Settings</a>
        </div>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='groups_section_header'>
                    {$_GROUPS_CREATE_WELCOME}
                </td>
            </tr>
        </table>
        <p />
        <form method ='post' action='index.php' name='groups'>
            <div>
                <input type='hidden' name='extension' value='Groups'>
                <input type='hidden' name='action' value='do_create_group'>
            </div>
            <table style='width: 100%;'>
                <tr>
                    <td style='width: 30%;'>
                        <span style='font-weight: bold;'>{$_GROUPS_GROUP_NAME}</span>
                    </td>
                    <td style='width: 70%;'>
                        <input type='text' name='group_name' maxlength='255' class='input_txt' />
                        <a href='#' onClick='check_availability()'>{$_GROUPS_CHECK_AVAIL}</a>
                    </td>
                </tr>
                <tr>
                    <td style='vertical-align: top;'>
                        <span style='font-weight: bold;'>{$_GROUPS_ADMIN}</span>
                    </td>
                    <td>
                        <select name='group_admin' style='width: 70%;'>
                            {section name=group_admin loop=$MEMBER_LIST}
                                <option value='{$MEMBER_LIST[$smarty.section.group_admin.index][0]}' />{$MEMBER_LIST[$smarty.section.group_admin.index][1]}
                            {/section}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='vertical-align: top;'>
                        <span style='font-weight'>{$_GROUPS_INITIAL_MEMBERS}</span>
                        <p />
                        {$_GROUPS_INITIAL_MEMBERS_FYI}
                    </td>
                    <td>
                        <select name='group_members[]' multiple='multiple' size='10' style='width: 70%;'>
                            {section name=group_members loop=$MEMBER_LIST}
                                <option value='{$MEMBER_LIST[$smarty.section.group_members.index][0]}' />{$MEMBER_LIST[$smarty.section.group_members.index][1]}
                            {/section}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='vertical-align: top;'>
                        <span style='font-weight: bold;'>Share Amount</span>
                        <p />
                        This amount will be subtracted from<br />your total storage amount
                    </td>
                    <td style='vertical-align: top;'>
                        <input type='text' name='share_amount' value='100' size='10' maxlength='10' class='input_txt'> Megabytes
                    </td>
                </tr>
            </table>
            <p />
            <table style='width: 100%;'>
                <tr>
                    <td style='text-align: center;'>
                        <input type='submit' name='submit' value='{$_GROUPS_NEW_GROUP}' class='input_btn'>
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
<!-- END:	groups_add.tpl -->
{include file='footer.tpl'}
