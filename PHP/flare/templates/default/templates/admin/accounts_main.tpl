{include file='header.tpl'}
<!-- START: 	admin/accounts_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align:top;'>
        <div style="text-align: center;">
            <!-- Put extension specific links here -->
            {$_ACCTS_ADM_SHOW_ALL_ACCOUNTS} ::
            {if $_AUTH_TYPE != "krb5"}
                <a href='admin.php?extension=Accounts&amp;action=show_add_account'>{$_ACCTS_ADM_ADD_ACCT}</a> ::
            {/if}
            <a href='admin.php?extension=Accounts&amp;action=show_settings'>{$_SETTINGS}</a>
        </div>
        <p />
        &nbsp;<p />
        <form method ='post' action='admin.php' name='accounts'>
            <div>
                <input type='hidden' name='extension' value='Accounts'>
                <input type='hidden' name='action' value='update_acct_info'>
            </div>
            <table>
                <tr>
                    <td></td>
                    <td class='groups_actions'>
                        <select name='account_action' onChange='javascript:actions(document.accounts.account_action.options[document.accounts.account_action.selectedIndex].value)'>
                        {if $ACCOUNT_LIST_A || $ACCOUNT_LIST_P || $ACCOUNT_LIST_D}
                            <option value='' />:: {$_WITH_SELECTED} ::
                            <optgroup label="Modification">
                            <option value='show_change_accounts' />{$_ACCTS_CHG_ACCTS}
                            <option value='do_activate_accounts' />{$_ACCTS_ACTIVATE}
                            <option value='do_deactivate_accounts' />{$_ACCTS_DEACTIVATE}
                            </optgroup>
                            <optgroup label="Deletion">
                            <option value='do_delete_accounts' />{$_ACCTS_DELETE}
                            { if $USE_JOBS }
                            <option value='do_purge_accounts' />{$_ACCTS_PURGE}
                            { /if }
                            </optgroup>
                        {else}
                            <option value='' />:: {$_NO_ACTIONS} ::
                        {/if}
                        </select>
                    </td>
                </tr>
            </table>
            <table class='col_main_table'>
                <tr>
                    <td>
                        <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                    </td>
                    <td class='acct_main_item_header'>
                        {$_ACCTS_ADM_USERNAME}
                    </td>
                    <td class='acct_main_item_header'>
                        {$_ACCTS_ADM_REALNAME}
                    </td>
                    <td class='acct_main_item_header'>
                        {$_ACCTS_ADM_HOMEDIR}
                    </td>
                    <td class='acct_main_item_header'>
                        {$_ACCTS_ADM_GROUPDIR}
                    </td>
                    <td class='acct_main_item_header'>
                        {$_ACCTS_ADM_LAST_LOGIN}
                    </td>
                    <td class='acct_main_item_header'>	
                        {$_ACCTS_ADM_AUTH_TYPE}
                    </td>
                </tr>
            {section name=ala loop=$ACCOUNT_LIST_A}
                <tr class='accts_main_adm_user_row_a'>
                    <td><input type='checkbox' name='account[]' value='{$ACCOUNT_LIST_A[ala].user_id}'></td>
                    <td>{$ACCOUNT_LIST_A[ala].username}</td>
                    <td>{$ACCOUNT_LIST_A[ala].realname}</td>
                    <td>{$ACCOUNT_LIST_A[ala].home_dir}</td>
                    <td>{$ACCOUNT_LIST_A[ala].group_dir}</td>
                    <td>{$ACCOUNT_LIST_A[ala].last_login}</td>
                    <td style='text-align: center;'>{$ACCOUNT_LIST_A[ala].auth_type}</td>
                </tr>
            {/section}
            {section name=alp loop=$ACCOUNT_LIST_P}
                <tr class='accts_main_adm_user_row_p'>
                    <td><input type='checkbox' name='account[]' value='{$ACCOUNT_LIST_P[alp].user_id}'></td>
                    <td>{$ACCOUNT_LIST_P[alp].username}</td>
                    <td>{$ACCOUNT_LIST_P[alp].realname}</td>
                    <td>{$ACCOUNT_LIST_P[alp].home_dir}</td>
                    <td>{$ACCOUNT_LIST_P[alp].group_dir}</td>
                    <td>{$ACCOUNT_LIST_P[alp].last_login}</td>
                    <td style='text-align: center;'>{$ACCOUNT_LIST_P[alp].auth_type}</td>
                </tr>
            {/section}
            {section name=ald loop=$ACCOUNT_LIST_D}
                <tr class='accts_main_adm_user_row_d'>
                    <td><input type='checkbox' name='account[]' value='{$ACCOUNT_LIST_D[ald].user_id}'></td>
                    <td>{$ACCOUNT_LIST_D[ald].username}</td>
                    <td>{$ACCOUNT_LIST_D[ald].realname}</td>
                    <td>{$ACCOUNT_LIST_D[ald].home_dir}</td>
                    <td>{$ACCOUNT_LIST_D[ald].group_dir}</td>
                    <td>{$ACCOUNT_LIST_D[ald].last_login}</td>
                    <td style='text-align: center;'>{$ACCOUNT_LIST_D[ald].auth_type}</td>
                </tr>
            {/section}
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END: 	admin/accounts_main.tpl -->
{include file='footer.tpl'}
