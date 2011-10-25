{include file='header.tpl'}
<!-- START: 	accounts_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            {$_ACCTS_SHOW_SETTINGS}
            {if $_AUTH_TYPE != "krb5" && $_AUTH_TYPE != "adldap"}
            :: <a href='index.php?extension=Accounts&amp;action=show_change_password'>{$_ACCTS_CHG_PASSWD}</a>
            {/if}
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
        <form method ='post' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Accounts'>
                <input type='hidden' name='action' value='update_acct_info'>
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
                        <input type='text' name='user_fname' value='{$FNAME}' maxlength='32' size='32' class='input_txt' />
                    </td>
                    <td class='accts_main_col_three'></td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_LNAME}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='text' name='user_lname' value='{$LNAME}' maxlength='32' size='32' class='input_txt'/>
                    </td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_GENDER}
                    </td>
                    <td class='accts_main_col_two'>
                        {if $GENDER == 'm'}
                            <input type='radio' name='user_gender' value='m' CHECKED />{$_MALE}
                            <input type='radio' name='user_gender' value='f' />{$_FEMALE}
                        {else}
                            <input type='radio' name='user_gender' value='m' />{$_MALE}
                            <input type='radio' name='user_gender' value='f' CHECKED />{$_FEMALE}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_AGE}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='text' name='user_age' value='{$AGE}' size='2' maxlength='2' class='input_txt'/>
                    </td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_COUNTRY}
                    </td>
                    <td class='accts_main_col_two'>
                        <select name='user_country'>
                        {if $COUNTRIES}
                            {$COUNTRIES}
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
                        <input type='text' name='user_occupation' maxlength='64' value='{$OCCUPATION}' size='50' class='input_txt'/>
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
                        <input type='text' name='user_org_email' maxlength='64' value='{$ORG_EMAIL}' size='30' class='input_txt' disabled='disabled'/>
                    </td>
                    <td class='accts_main_col_three'></td>
                </tr>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_ALT_EMAIL}
                    </td>
                    <td class='accts_main_col_two'>
                        <input type='text' name='user_email' maxlength='64' value='{$ALT_EMAIL}' size='30' class='input_txt'/>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    <table class='col_main_table'>
    <tr>
        <td class='acct_main_item_header'>
            {$_ACCTS_MISC}
        </td>
    </tr>
    <tr>
        <td>
            <table style='width: 100%; background: #e6e6e6;'>
                <tr>
                    <td class='accts_main_col_one'>
                        {$_ACCTS_VIEW_PUBLIC}
                    </td>
                    <td class='accts_main_col_two'>
                        {if $PUBLIC == '1'}
                            <input type='radio' name='user_public' value='1' CHECKED />{$_YES}
                            <input type='radio' name='user_public' value='0' />{$_NO}
                        {else}
                            <input type='radio' name='user_public' value='1' />{$_YES}
                            <input type='radio' name='user_public' value='0' CHECKED />{$_NO}
                        {/if}
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
            <td align='center'>
                <input type='submit' name='submit' value='{$_SAVE_CHGS}' class='input_btn'>
            </td>
            <td align='center'>
                <input type='reset' value='{$_UNDO_CHGS}' class='input_btn'>
            </td>
        </tr>
    </table>
    </form>
</td>
</tr>
</table>
<!-- END: 	accounts_main.tpl -->
{include file='footer.tpl'}
