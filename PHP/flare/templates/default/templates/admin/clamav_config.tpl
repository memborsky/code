{include file='header.tpl'}
<!-- START: 	admin/clamav_config.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=ClamAV&amp;action=show_scans'>{$_CLAM_SCANS}</a> ::
            <a href='admin.php?extension=ClamAV&amp;action=show_schedule_scan'>{$_CLAM_SCHEDULE_SCAN}</a> ::
            {$_SETTINGS}
        </div>
    <p />
    <form method ='post' action='admin.php' name='config'>
        <div>
            <input type='hidden' name='extension' value='ClamAV'>
            <input type='hidden' name='action' value='do_save_settings'>
        </div>
    <table style='width: 100%;'>
    <tr>
        <td>{$CONFIG.scan_root.desc}</td>
        <td><input type='text' name='{$CONFIG.scan_root.name}' value='{$CONFIG.scan_root.value}' size='50' class='input_txt' /></td>
    </tr>
    <tr>
        <td>{$CONFIG.quarentine_dir.desc}</td>
        <td><input type='text' name='{$CONFIG.quarentine_dir.name}' value='{$CONFIG.quarentine_dir.value}' size='50' class='input_txt' /></td>
    </tr>
    <tr>
        <td>{$CONFIG.auto_move_infected.desc}</td>
        <td>
            {if $CONFIG.auto_move_infected.value == 1}
                <input type='radio' name='{$CONFIG.auto_move_infected.name}' checked='checked' />{$_YES}
                <input type='radio' name='{$CONFIG.auto_move_infected.name}' />{$_NO}
            {else}
                <input type='radio' name='{$CONFIG.auto_move_infected.name}' />{$_YES}
                <input type='radio' name='{$CONFIG.auto_move_infected.name}' checked='checked' />{$_NO}
            {/if}
        </td>
    </tr>
    <tr>
        <td>{$CONFIG.clamscan_bin.desc}</td>
        <td><input type='text' name='{$CONFIG.clamscan_bin.name}' value='{$CONFIG.clamscan_bin.value}' size='50' class='input_txt' /></td>
    </tr>
    </table>
    <p />
    <table style='width: 100%;'>
        <tr>
            <td style='text-align: center;'>
                <input type='submit' name='submit' value='{$_SAVE_CHGS}' class='input_btn' />
                <input type='reset' value='{$_UNDO_CHGS}' class='input_btn' />
            </td>
        </tr>
    </table>
    </form>
</td>
</tr>
</table>
<!-- END: 	admin/clamav_config.tpl -->
{include file='footer.tpl'}
