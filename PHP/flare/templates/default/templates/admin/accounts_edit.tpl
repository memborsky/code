<!-- START: admin/accounts_edit.tpl -->
{include file='header.tpl'}
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Accounts&amp;action=show_user_list'>{$_ACCTS_ADM_SHOW_ALL_ACCOUNTS}</a> ::
            {if $AUTH_TYPE != "krb5"}
                <a href='admin.php?extension=Accounts&amp;action=show_add_account'>{$_ACCTS_ADM_ADD_ACCT}</a> ::
            {/if}
            <a href='admin.php?extension=Accounts&amp;action=show_settings'>{$_SETTINGS}</a>
        </div>

        {section name=acct loop=$ACCOUNT_LIST}
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='accts_section_header'>
                    {$_ACCTS_ADM_ACCOUNT}: {$ACCOUNT_LIST[acct].username}<br />
                    {$_ACCTS_ADM_REGISTERED}: {$ACCOUNT_LIST[acct].register_date}
                </td>
            </tr>
        </table>
        <p />
        <form method ='post' action='admin.php'>
            <div>
                <input type='hidden' name='extension' value='Accounts'>
                <input type='hidden' name='action' value='do_change_accounts'>
                <input type='hidden' name='user_id[{$ACCOUNT_LIST[acct].user_id}]' value='{$ACCOUNT_LIST[acct].user_id}'>
            </div>

        <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header'>
                {$_ACCTS_PERSONAL_INFO}
            </td>
        </tr>
        <tr>
            <td>
                <table style='width: 100%; background: #e6e6e6;'>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_FNAME}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_fname[{$ACCOUNT_LIST[acct].user_id}]' value='{$ACCOUNT_LIST[acct].fname}' maxlength='32' size='32' class='input_txt' />
                        </td>
                    <td class='accts_main_col_three'></td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_LNAME}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_lname[{$ACCOUNT_LIST[acct].user_id}]' value='{$ACCOUNT_LIST[acct].lname}' maxlength='32' size='32' class='input_txt' />
                        </td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_GENDER}
                        </td>
                        <td class='accts_main_col_two'>
                            {if $ACCOUNT_LIST[acct].gender == 'm'}
                                <input type='radio' name='user_gender[{$ACCOUNT_LIST[acct].user_id}]' value='m' checked='checked' />{$_MALE}
                                <input type='radio' name='user_gender[{$ACCOUNT_LIST[acct].user_id}]' value='f' />{$_FEMALE}
                            {else}
                                <input type='radio' name='user_gender[{$ACCOUNT_LIST[acct].user_id}]' value='m' />{$_MALE}
                                <input type='radio' name='user_gender[{$ACCOUNT_LIST[acct].user_id}]' value='f' checked='checked' />{$_FEMALE}
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_AGE}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_age[{$ACCOUNT_LIST[acct].user_id}]' value='{$ACCOUNT_LIST[acct].age}' size='2' maxlength='2' class='input_txt' />
                        </td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_COUNTRY}
                        </td>
                        <td class='accts_main_col_two'>
                            <select name='user_country[{$ACCOUNT_LIST[acct].user_id}]'>
                            {if $ACCOUNT_LIST[acct].country != ""}
                                {$ACCOUNT_LIST[acct].country}
                            {else}
                                <option value=''>{$_ACCTS_NO_COUNTRY}</option>
                            {/if}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_OCCUPATION}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_occupation[{$ACCOUNT_LIST[acct].user_id}]' maxlength='64' value='{$ACCOUNT_LIST[acct].occupation}' size='50' class='input_txt' />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>
        <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header'>
                {$_ACCTS_CNCT_SETTINGS}
            </td>
        </tr>
        <tr>
            <td>
                <table style='width: 100%; background: #e6e6e6;'>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ORG_EMAIL}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_org_email[{$ACCOUNT_LIST[acct].user_id}]' maxlength='64' value='{$ACCOUNT_LIST[acct].org_email}' size='30' class='input_txt' />
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ALT_EMAIL}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_email[{$ACCOUNT_LIST[acct].user_id}]' maxlength='64' value='{$ACCOUNT_LIST[acct].alt_email}' size='30' class='input_txt' />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>
        <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header'>
                {$_ACCTS_ADM_USER_ROOTS}
            </td>
        </tr>
        <tr>
            <td>
                <table style='width: 100%; background: #e6e6e6;'>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ADM_HOMEDIR}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_home_dir[{$ACCOUNT_LIST[acct].user_id}]' value='{$ACCOUNT_LIST[acct].home_dir}' maxlength='255' size='50' class='input_txt' />
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ADM_GROUPDIR}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_group_dir[{$ACCOUNT_LIST[acct].user_id}]' value='{$ACCOUNT_LIST[acct].group_dir}' maxlength='255' size='50' class='input_txt'/>
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>
        <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header'>
                {$_ACCTS_ADM_PASSWD_RESET}
            </td>
        </tr>
        <tr>
            <td>
                <table style='width: 100%; background: #e6e6e6;'>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ADM_AUTH_TYPE}
                        </td>
                        <td class='accts_main_col_two'>
                            <select name='auth_type[{$ACCOUNT_LIST[acct].user_id}]'>
                            {section name=auth loop=$AUTH_TYPES}
                                {if $ACCOUNT_LIST[acct].auth_type == $AUTH_TYPES[auth]}
                                    <option value='{$AUTH_TYPES[auth]}' selected='selected'>{$AUTH_TYPES[auth]}
                                    {else}
                                    <option value='{$AUTH_TYPES[auth]}'>{$AUTH_TYPES[auth]}
                                    {/if}

                            {/section}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_NEW_PASSWD}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='password' name='user_new_password[{$ACCOUNT_LIST[acct].user_id}]' maxlength='255' size='50' class='input_txt' />
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_VER_USR_PASSWORD}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='password' name='user_new_password_verify[{$ACCOUNT_LIST[acct].user_id}]' maxlength='255' size='50' class='input_txt' />
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>
        <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header'>
                {$_ACCTS_ADM_OPTIONAL_MEDIA}
            </td>
        </tr>
        <tr>
            <td>
                <table style='width: 100%; background: #e6e6e6;'>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_THEME}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_theme[{$ACCOUNT_LIST[acct].user_id}]' value='{$ACCOUNT_LIST[acct].theme}' maxlength='255' size='50' class='input_txt' />
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ADM_USER_LEVEL}
                        </td>
                        <td class='accts_main_col_two'>
                            <input type='text' name='user_level[{$ACCOUNT_LIST[acct].user_id}]' value='{$ACCOUNT_LIST[acct].user_level}' maxlength='255' size='50' class='input_txt' />
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ADM_STATUS}
                        </td>
                        <td class='accts_main_col_two'>
                            <select name='user_status[{$ACCOUNT_LIST[acct].user_id}]'>
                                {section name=status loop=$STATUS_LIST}
                                    {if $ACCOUNT_LIST[acct].status == $STATUS_LIST[status].status}
                                    <option value='{$STATUS_LIST[status].status}' selected='selected'>{$STATUS_LIST[status].name}
                                    {else}
                                    <option value='{$STATUS_LIST[status].status}'>{$STATUS_LIST[status].name}
                                    {/if}
                                {/section}
                            </select>
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ADM_PUBLIC_VIEW}
                        </td>
                        <td class='accts_main_col_two'>
                            {if $ACCOUNT_LIST[acct].public == 1}
                            <input type='radio' name='user_public[{$ACCOUNT_LIST[acct].user_id}]' value='1' checked='checked'>{$_YES}
                            <input type='radio' name='user_public[{$ACCOUNT_LIST[acct].user_id}]' value='0'>{$_NO}
                            {else}
                            <input type='radio' name='user_public[{$ACCOUNT_LIST[acct].user_id}]' value='1'>{$_YES}
                            <input type='radio' name='user_public[{$ACCOUNT_LIST[acct].user_id}]' value='0' checked='checked'>{$_NO}
                            {/if}
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                </table>
            </td>
        </tr>
        </table>
        <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header'>
                {$_PRIVILEGES}
            </td>
        </tr>
        <tr>
            <td>
                <table style='width: 100%; background: #e6e6e6;'>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ADM_PRIVILEGES}
                        </td>
                        <td class='accts_main_col_two'>
                            {$_ACCTS_ADM_PRIVILEGES_MESG}
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                    {section name=perms loop=$ACCOUNT_LIST[acct].admin_privs}
                    <tr>
                        <td class='accts_main_col_one'></td>
                        <td class='accts_main_col_two'>
                        {if $ACCOUNT_LIST[acct].admin_privs[perms].allowed == 1}
                        <input type='checkbox' name='auth_perm[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].admin_privs[perms].extension_id}]' checked='checked'>
                        {else}
                        <input type='checkbox' name='auth_perm[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].admin_privs[perms].extension_id}]'>
                        {/if}
                        {$ACCOUNT_LIST[acct].admin_privs[perms].name}
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                    {/section}
                </table>
            </td>
        </tr>
        </table>
        <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header'>
                {$_SERVICES}
            </td>
        </tr>
        <tr>
            <td>
                <table style='width: 100%; background: #e6e6e6;'>
                    <tr>
                        <td class='accts_main_col_one'>
                            {$_ACCTS_ADM_SERVICES}
                        </td>
                        <td class='accts_main_col_two'>
                            {$_ACCTS_ADM_SERVICES_MESG}
                        </td>
                        <td class='accts_main_col_three'></td>
                    </tr>
                    {section name=srvc loop=$ACCOUNT_LIST[acct].services}
                    <tr>
                        <td class='accts_main_col_one'></td>
                        <td class='accts_main_col_two' colspan='2'>
                            <table style='width: 100%; background-color: {cycle values="#d0d0d0,#eee"}'>
                            <tr>
                            <td>
                                <span style='font-weight: bold;'>{$ACCOUNT_LIST[acct].services[srvc].display}</span>
                            </td>
                            <tr>
                            <tr>
                        {if $ACCOUNT_LIST[acct].services[srvc].activated == 0}
                        
                        <td width='33%' style='text-align: center'>
                        <input type='radio' name='service[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].services[srvc].name}]' value='0' checked='checked'>Not Activated
                        </td>
                        <td width='33%' style='text-align: center'>
                        <input type='radio' name='service[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].services[srvc].name}]' value='1'>Activated
                        </td>
                        <td width='33%' style='text-align: center'>
                        <input type='radio' name='service[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].services[srvc].name}]' value='2'>Disabled
                        </td>
                        
                        {elseif $ACCOUNT_LIST[acct].services[srvc].activated == 1}

                        <td width='33%' style='text-align: center'>
                        <input type='radio' name='service[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].services[srvc].name}]' value='0'>Not Activated
                        </td>
                        <td width='33%' style='text-align: center'>
                        <input type='radio' name='service[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].services[srvc].name}]' value='1' checked='checked'>Activated
                        </td>
                        <td width='33%' style='text-align: center'>
                        <input type='radio' name='service[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].services[srvc].name}]' value='2'>Disabled
                        </td>

                        { else }

                        <td width='33%' style='text-align: center'>
                        <input type='radio' name='service[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].services[srvc].name}]' value='0'>Not Activated
                        </td>
                        <td width='33%' style='text-align: center'>
                        <input type='radio' name='service[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].services[srvc].name}]' value='1'>Activated
                        </td>
                        <td width='33%' style='text-align: center'>
                        <input type='radio' name='service[{$ACCOUNT_LIST[acct].user_id}][{$ACCOUNT_LIST[acct].services[srvc].name}]' value='2' checked='checked'>Disabled
                        </td>
                        
                        {/if}
                            </tr>
                            </table>
                        </td>
                    </tr>
                    {/section}
                </table>
            </td>
        </tr>
        <table>
        <p />
        <hr width='50%'>
        <p />
        {/section}
        <p />
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: center;'>
                    <input type='submit' name='submit' value='{$_SAVE_CHGS}' class='input_btn'>
                </td>
                <td style='text-align: center;'>
                    <input type='reset' value='{$_UNDO_CHGS}' class='input_btn'>
                </td>
            </tr>
        </table>
        </form>
    </td>
</tr>
</table>
<!-- END: admin/accounts_edit.tpl -->
{include file='footer.tpl'}
