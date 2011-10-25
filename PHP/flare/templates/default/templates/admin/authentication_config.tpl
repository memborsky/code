{include file='header.tpl'}
<!-- START: 	admin/authentication_config.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            {$_SETTINGS}
        </div>
    <p />
    <form method ='post' action='admin.php' name='config'>
        <div>
            <input type='hidden' name='extension' value='Authentication'>
            <input type='hidden' name='action' value='do_save_settings'>
        </div>
        <table style='width: 100%;'>
        <tr>
            <td>Extension is visible to normal users</td>
            <td>
                {if $VISIBLE == 1}
                    <input type='radio' name='visible' value='1' checked='checked' />Yes
                    <input type='radio' name='visible' value='0' />No
                {else}
                    <input type='radio' name='visible' value='1' />Yes
                    <input type='radio' name='visible' value='0' checked='checked' />No
                {/if}
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.use_auth.desc}</td>
            <td>
                {if $CONFIG.use_auth.value == 1}
                    <input type='radio' name='{$CONFIG.use_auth.name}' value='1' checked='checked' />Yes
                    <input type='radio' name='{$CONFIG.use_auth.name}' value='0'/>No
                {else}
                    <input type='radio' name='{$CONFIG.use_auth.name}' value='1'/>Yes
                    <input type='radio' name='{$CONFIG.use_auth.name}' value='0' checked='checked' />No
                {/if}
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.auth_type.desc}</td>
            <td>
                <select name='{$CONFIG.auth_type.name}'>
                    <option value='{$CONFIG.auth_type.value}' />{$CONFIG.auth_type.value}
                    <option value='{$CONFIG.auth_type.value}' />-----
                    <option value='db' />Database
                    <option value='kerb' />Kerberos
                    <option value='ldap' />LDAP
                    <option value='adldap' />Active Directory
                </select>
            </td>
        </tr>
        </table>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: center'>
                    <input type='submit' name='submit' value='{$_SAVE_CHGS}' class='input_btn' />
                    <input type='reset' value='{$_UNDO_CHGS}' class='input_btn' />
                </td>
            </tr>
        </table>
    </form>
</td>
</tr>
</table>
<!-- END: 	admin/authentication_config.tpl -->
{include file='footer.tpl'}
