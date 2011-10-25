{include file='header.tpl'}
<!-- START: 	admin/email_config.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Email&amp;action=show_write_email'>Send New Email</a> ::
            Settings

        </div>
        <p />
    <form method ='post' action='admin.php' name='config'>
        <div>
            <input type='hidden' name='extension' value='Email'>
            <input type='hidden' name='action' value='do_save_settings'>
        </div>
    <table style='width: 100%;'>
    <tr>
        <td>{$CONFIG.mail_from.desc}</td>
        <td><input type='text' name='{$CONFIG.mail_from.name}' value='{$CONFIG.mail_from.value}' size='50' class='input_txt' /></td>
    </tr>
    <tr>
        <td>{$CONFIG.mail_server.desc}</td>
        <td><input type='text' name='{$CONFIG.mail_server.name}' value='{$CONFIG.mail_server.value}' size='50' class='input_txt' /></td>
    </tr>
    <tr>
        <td>{$CONFIG.mail_server.desc}</td>
        <td><input type='text' name='{$CONFIG.mail_port.name}' value='{$CONFIG.mail_port.value}' size='50' class='input_txt' /></td>
    </tr>
    </table>
    <p />
    <table style='width: 100%;'>
        <tr>
            <td style='text-align: center;'>
                <input type='submit' name='submit' value='Save Settings' class='input_btn' />
                <input type='reset' value='Undo Changes' class='input_btn' />
            </td>
        </tr>
    </table>
    </form>
</td>
</tr>
</table>
<!-- END: 	admin/email_config.tpl -->
{include file='footer.tpl'}
