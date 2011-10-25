<q!-- START: 	admin/accounts_add.tpl -->
{include file='header.tpl'}
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Accounts&amp;action=show_user_list'>{$_ACCTS_ADM_SHOW_ALL_ACCOUNTS}</a> ::
            {$_ACCTS_ADM_ADD_ACCT} ::
            <a href='admin.php?extension=Accounts&amp;action=show_settings'>{$_SETTINGS}</a>

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
    <form method='post' action='admin.php' name'mass_accounts' enctype="multipart/form-data">
        <div>
            <input type='hidden' name='extension' value='Accounts'>
            <input type='hidden' name='action' value='do_mass_add_account'>
        </div>
    <table class='col_main_table'>
    <tr>
        <td class='acct_main_item_header'>
            Mass Account Creation
        </td>
    </tr>
    <tr>
        <td>
            <table style='width: 100%; background: #e6e6e6;'>
                <tr>
                    <td class='accts_main_col_one'>
                        Upload the text file containing account names
                    </td>
                    <td class='accts_main_col_one'><input type='file' size='40' name='mass_account_file' class='input_btn'></td>
                    <td>
                    The text file must be a file that contains one student email account<br>
                    per line. The email account must include the usual suffix (@school.edu) or<br>
                    whatever is used by the organization.
                    <p>
                    Note that when accounts are created, default values for all fields except for<br>
                    the username and password will be used. Passwords will be the timestamp of<br>
                    when the account was created.
                    </td>
            </table>
        </td>
    </tr>
    <table>
    <p />
    <table style='width: 100%;'>
        <tr>
            <td style='text-align: center;'>
                <input type='submit' name='submit' value='Batch Create Accounts' class='input_btn'>
            </td>
        </tr>
    </table>
    </form>
    </form>

    <p />

    <form method ='post' action='admin.php' name='accounts'>
        <div>
            <input type='hidden' name='extension' value='Accounts'>
            <input type='hidden' name='action' value='do_add_account'>
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
                        <input type='text' name='user_fname' maxlength='32' size='32' class='input_txt' />
                    </td>
                    <td class='accts_main_col_three'></td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_LNAME}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='text' name='user_lname' maxlength='32' size='32' class='input_txt' />
                    </td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_GENDER}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='radio' name='user_gender' value='m' />{$_MALE}
                        <input type='radio' name='user_gender' value='f' />{$_FEMALE}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    <table class='col_main_table'>
    <tr>
        <td class='acct_main_item_header'>
            {$_ACCTS_ADM_ACCT_INFO}
        </td>
    </tr>
    <tr>
        <td>
            <table style='width: 100%; background: #e6e6e6;'>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_ADM_USERNAME}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='text' name='user_username' maxlength='32' size='32' onblur="document.accounts.user_homedir.value='{$_USER_DIR}'+document.accounts.user_username.value+'/';document.accounts.user_groupdir.value='{$_GROUP_DIR}'" class='input_txt' />
                    </td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_ORG_EMAIL}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='text' name='user_email' maxlength='32' class='input_txt' />{$_ORG_EXT}
                    </td>
                    <td class='accts_main_col_three'></td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_ADM_AUTH_TYPE}
                    </td>
                    <td class='accts_main_col_two'>
                        <select name='auth_type' />
                            <option value='db'>{$_DATABASE}
                            <option value='krb5'>{$_KERBEROS}
                            <option value='ldap'>LDAP
                            <option value='adldap'>Active Directory
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_USR_PASSWORD}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='text' name='user_password' maxlength='32' size='32' class='input_txt' />
                    </td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_ADM_HOMEDIR}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='text' name='user_homedir' maxlength='255' size='32' class='input_txt' />
                    </td>
                    <td class='accts_main_col_three'></td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_ADM_GROUPDIR}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='text' name='user_groupdir' maxlength='255' size='32' class='input_txt' />
                    </td>
                    <td class='accts_main_col_three'></td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    <p />
    <table style='width: 100%;'>
        <tr>
            <td style='text-align: center;'>
                <input type='submit' name='submit' value='{$_ACCTS_ADM_CREATE_ACCOUNT}' class='input_btn'>
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
<!-- END: 	admin/accounts_add.tpl -->
{include file='footer.tpl'}
