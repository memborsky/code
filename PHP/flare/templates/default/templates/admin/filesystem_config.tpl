{include file='header.tpl'}
<!-- START: 	admin/filesystem_config.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            {$_SETTINGS}
            :: <a href='index.php?extension=Filesystem&amp;action=show_files'>View Filesystem</a>
            :: <a href='admin.php?extension=Filesystem&amp;action=show_permissions'>Change File/Folder Permissions</a>
        </div>
    <p />
    <form method ='post' action='admin.php' name='config'>
        <div>
            <input type='hidden' name='extension' value='Filesystem'>
            <input type='hidden' name='action' value='do_save_settings'>
        </div>
        <table style='width: 100%;'>
        <tr>
            <td>{$CONFIG.max_ul_size.desc}</td>
            <td>
                <input type='text' name='{$CONFIG.max_ul_size.name}' size='50' value='{$CONFIG.max_ul_size.value}' class='input_txt' />
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.use_quotas.desc}</td>
            <td>
                {if $CONFIG.use_quotas.value == 1}
                    <input type='radio' name='{$CONFIG.use_quotas.name}' value='1' checked='checked' />Yes
                    <input type='radio' name='{$CONFIG.use_quotas.name}' value='0' />No
                {else}
                    <input type='radio' name='{$CONFIG.use_quotas.name}' value='1' />Yes
                    <input type='radio' name='{$CONFIG.use_quotas.name}' value='0' checked='checked' />No
                {/if}
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.directory_permissions.desc}</td>
            <td>
                <input type='text' name='{$CONFIG.directory_permissions.name}' size='50' value='{$CONFIG.directory_permissions.value}' class='input_txt'/>
            </td>
        </tr>
        <tr>
            <td>{$CONFIG.file_permissions.desc}</td>
            <td>
                <input type='text' name='{$CONFIG.file_permissions.name}' size='50' value='{$CONFIG.file_permissions.value}' class='input_txt' />
            </td>
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
<!-- END: 	admin/filesystem_config.tpl -->
{include file='footer.tpl'}
