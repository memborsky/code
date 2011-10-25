{include file='header.tpl'}
<!-- START: 	groups_create.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='index.php?extension=Groups&amp;action=show_groups'>{$_GROUPS_LIST}</a> :: 
            {$_GROUPS_CREATE} :: 
            <a href='index.php?extension=Groups&amp;action=show_invites_create'>{$_GROUPS_INVITE_USER}</a>
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
                <td style='width: 30%; vertical-align: top;'>
                    <b>{$_GROUPS_GROUP_NAME}</b>
                </td>
                <td style='width: 70%;'>
                    {literal}
                    <input type='text' id='groupname' name='group_name' maxlength='64' onKeyUp="char_counter('groupname','full','{CHAR} characters left.', 64);" size='65' class='input_txt'/>
                    {/literal}
                    <br><span id="full" class="minitext">{$_GROUPS_CHARS_LEFT}</span>
                    <a href='#' onClick='javascript:check_availability()'>{$_GROUPS_CHECK_AVAIL}</a>
                </td>
            </tr>
            <tr>
                <td style='vertical-align: top;'>
                    <span style='font-weight: bold;'>{$_GROUPS_INITIAL_MEMBERS}</span>
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
                    <span style='font-weight: bold;'>{$_GROUPS_SHARE_AMNT}</span>
                    <p />
                    {$_GROUPS_SHARE_AMNT_SUB}
                </td>
                <td style='vertical-align: top;'>
                    <input type='hidden' name='total_quota' value='{$TOTAL_QUOTA_REMAINING}'>
                    <input type='text' name='share_amount' value='100' size='10' maxlength='10' onKeyUp='verify_quota();' class='input_txt'/> Megabytes from your total {$TOTAL_QUOTA_REMAINING}
                </td>
            </tr>
            <tr>
                <td style='vertical-align: top;'>
                    <span style='font-weight: bold;'>{$_GROUPS_TYPE}</span>
                </td>
                <td style='vertical-align: top;'>
                    <input type='radio' name='group_type' value='trusted' onClick='javascript:group_desc("trusted")' checked='checked' />{$_GROUPS_TRUSTED}
                    <input type='radio' name='group_type' value='distribution' onClick='javascript:group_desc("distribution")' />{$_GROUPS_DISTRO}
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div id='trusted' style='display: block;'>
                        {$_GROUPS_TRUSTED_NFO}
                    </div>
                    <div id='distribution' style='display: none;'>
                        {$_GROUPS_DISTRO_NFO}
                    </div>
                </td>
            </tr>
        </table>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: center;'>
                    <input type='button' name='submit_btn' value='{$_GROUPS_NEW_GROUP}' onClick="javascript:verify_data()" class='input_btn'/>
                </td>
                <td style='text-align: center;'>
                    <input type='reset' value='{$_RESET_FORM}' class='input_btn'/>
                </td>
            </tr>
        </table>
        </form>
    </td>
</tr>
</table>
<!-- END:	groups_create.tpl -->
{include file='footer.tpl'}
