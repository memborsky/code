{include file='header.tpl'}
<!-- START: 	admin/settings_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            {$_SETTINGS} ::
            <a href='admin.php?extension=Settings&amp;action=show_install_extensions'>Install/Remove Extensions</a>
        </div>
    <p />
    <form method ='post' action='admin.php' name='config'>
        <div>
            <input type='hidden' name='extension' value='Settings'>
            <input type='hidden' name='action' value='do_save_settings'>
        </div>
        <table style='width: 100%;'>
        <tr>
            <td>{$CONFIG.default_extension.desc}</td>
            <td>
                <input type='text' name='{$CONFIG.default_extension.name}' size='50' value='{$CONFIG.default_extension.value}' class='input_txt'/>
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.use_debug.desc}</td>
            <td>
                {if $CONFIG.use_debug.value == 1}
                    <input type='radio' name='{$CONFIG.use_debug.name}' value='1' checked='checked' />Yes
                    <input type='radio' name='{$CONFIG.use_debug.name}' value='0' />No
                {else}
                    <input type='radio' name='{$CONFIG.use_debug.name}' value='1' />Yes
                    <input type='radio' name='{$CONFIG.use_debug.name}' value='0' checked='checked' />No
                {/if}
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.update.desc}</td>
            <td>
                {if $CONFIG.update.value == 1}
                    <input type='radio' name='{$CONFIG.update.name}' value='1' checked='checked' />Yes
                    <input type='radio' name='{$CONFIG.update.name}' value='0' />No
                {else}
                    <input type='radio' name='{$CONFIG.update.name}' value='1' />Yes
                    <input type='radio' name='{$CONFIG.update.name}' value='0' checked='checked' />No
                {/if}
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.version.desc}</td>
            <td>
                <input type='text' name='{$CONFIG.version.name}' size='50' value='{$CONFIG.version.value}' disabled='disabled' class='input_txt'/>
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.update_interval.desc}</td>
            <td>
                <input type='text' name='{$CONFIG.update_interval.name}' size='50' value='{$CONFIG.update_interval.value}' class='input_txt'/>
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.last_update_check.desc}</td>
            <td>
                <input type='text' name='{$CONFIG.last_update_check.name}' size='50' value='{$CONFIG.last_update_check.value}' class='input_txt'/>
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.use_strict.desc}</td>
            <td>
                {if $CONFIG.use_strict.value == 1}
                    <input type='radio' name='{$CONFIG.use_strict.name}' value='1' checked='checked' />Yes
                    <input type='radio' name='{$CONFIG.use_strict.name}' value='0' />No
                {else}
                    <input type='radio' name='{$CONFIG.use_strict.name}' value='1' />Yes
                    <input type='radio' name='{$CONFIG.use_strict.name}' value='0' checked='checked' />No
                {/if}
            </td>
        </tr>
        </table>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: center;'>
                    <input type='submit' name='submit' value='Save Settings' class='input_btn'/>
                    <input type='reset' value='Undo Changes' class='input_btn'/>
                </td>
            </tr>
        </table>
    </form>
</td>
</tr>
</table>
<!-- END: 	admin/settings_main.tpl -->
{include file='footer.tpl'}
