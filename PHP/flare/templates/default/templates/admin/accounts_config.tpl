{include file='header.tpl'}
<!-- START: 	admin/accounts_config.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Accounts&amp;action=show_user_list'>Show All Accounts</a> ::
            {if $_AUTH_TYPE != "krb5"}
                <a href='admin.php?extension=Accounts&amp;action=show_add_account'>{$_ACCTS_ADM_ADD_ACCT}</a> ::
            {/if}
            Settings
            
        </div>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='accts_section_header'>
                    {$_ACCTS_WELCOME}
                </td>
            </tr>
        </table>
    <p />
    <form method ='post' action='admin.php' name='config'>
        <div>
            <input type='hidden' name='extension' value='Accounts'>
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
        <td>{$CONFIG.template.desc}</td>
        <td><input type='text' name='{$CONFIG.template.name}' value='{$CONFIG.template.value}' class='input_txt' /></td>
    </tr>
    <tr>
        <td>{$CONFIG.user_dir.desc}</td>
        <td><input type='text' name='{$CONFIG.user_dir.name}' value='{$CONFIG.user_dir.value}' size='50' class='input_txt' /></td>
    </tr>
    <tr>
        <td>{$CONFIG.group_dir.desc}</td>
        <td><input type='text' name='{$CONFIG.group_dir.name}' value='{$CONFIG.group_dir.value}' size='50' class='input_txt' /></td>
    </tr>
    <tr>
        <td>{$CONFIG.idle_timeout.desc}</td>
        <td><input type='text' name='{$CONFIG.idle_timeout.name}' value='{$CONFIG.idle_timeout.value}' class='input_txt' /></td>
    </tr>
    </table>
    <p />
    <table style='width: 100%;'>
        <tr>
            <td style='text-align: center;'>
                <input type='submit' name='submit' value='Save Settings' class='input_btn' />
                <input type='reset' value='{$_UNDO_CHGS}' class='input_btn' />
            </td>
        </tr>
    </table>
    </form>
</td>
</tr>
</table>
<!-- END: 	admin/accounts_config.tpl -->
{include file='footer.tpl'}
