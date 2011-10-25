{include file=header.tpl}
<!-- START:	admin/clamav_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=ClamAV&amp;action=show_scans'>{$_CLAM_SCANS}</a> ::
            <a href='admin.php?extension=ClamAV&amp;action=show_schedule_scan'>{$_CLAM_SCHEDULE_SCAN}</a> ::
            <a href='admin.php?extension=ClamAV&amp;action=show_settings'>{$_SETTINGS}</a>
        </div>
    <p />
    <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header' colspan='2'>
                {$_CLAM_SCAN_DETAILS}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;'>
                {$_CLAM_SCAN_NAME}
            </td>
            <td style='width: 50%;'>
                {$SCAN_NAME}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;'>
                {$_CLAM_SCAN_STATUS}
            </td>
            <td style='width: 50%;'>
                {if $SCAN_STATUS == 'P'}
                    <span style='font-weight: bold; color: orange;'>{$_CLAM_PENDING}</span>
                {elseif $SCAN_STATUS == 'R'}
                    <span style='font-weight: bold; color: blue;'>{$_CLAM_RUNNING}</span>
                {else}
                    <span style='font-weight: bold; color: green;'>{$_CLAM_FINISHED}</span>
                {/if}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;' valign='top'>
                {$_CLAM_COMMAND_USED}
            </td>
            <td style='width: 50%;' valign='top'>
                {$SCAN_CMD}
            </td>
        </tr>
    </table>
    </td>
</tr>
</table>
<!-- END:	admin/clamav_main.tpl -->
{include file=footer.tpl}
