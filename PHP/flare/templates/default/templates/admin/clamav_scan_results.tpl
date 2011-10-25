{include file=header.tpl}
<!-- START:	admin/clamav_scan_results.tpl -->
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
                {$_CLAM_SCAN_SUMMARY}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;'>
                {$_CLAM_KNOWN_VIRUSES}
            </td>
            <td style='width: 50%;'>
                {$KNOWN_VIRUSES}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;'>
                {$_CLAM_ENGINE_VERSION}
            </td>
            <td style='width: 50%;'>
                {$ENGINE_VERSION}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;'>
                {$_CLAM_SCAN_DIRS}
            </td>
            <td style='width: 50%;'>
                {$SCANNED_DIRECTORIES}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;'>
                {$_CLAM_SCANNED_FILES}
            </td>
            <td style='width: 50%;'>
                {$SCANNED_FILES}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;'>
                {$_CLAM_INFECTED_FILES}
            </td>
            <td style='width: 50%;'>
                {$INFECTED_FILES}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;'>
                {$_CLAM_DATA_SCANNED}
            </td>
            <td style='width: 50%;'>
                {$DATA_SCANNED}
            </td>
        </tr>
        <tr style='background: #fff;'>
            <td style='width: 50%;'>
                {$_CLAM_TIME}
            </td>
            <td style='width: 50%;'>
                {$TIME}
            </td>
        </tr>
    </table>
    <p />
    <table class='col_main_table'>
        <tr>
            <td class='acct_main_item_header'>
                {$_CLAM_INFECTED_LIST}
            </td>
        </tr>
        {section name=scn loop=$INFECTED_LIST}
        <tr style='background: #fff;'>
            <td>
                {$INFECTED_LIST[scn]}
            </td>
        </tr>
        {sectionelse}
        <tr style='background: #fff;'>
            <td colspan='8'>
                {$_CLAM_NONE_INFECTED}
            </td>
        </tr>
        {/section}
    </table>
    </td>
</tr>
</table>
<!-- END:	admin/clamav_scan_results.tpl -->
{include file=footer.tpl}
