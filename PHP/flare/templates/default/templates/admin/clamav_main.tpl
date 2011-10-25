{include file=header.tpl}
<!-- START:	admin/clamav_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            {$_CLAM_SCANS} ::
            <a href='admin.php?extension=ClamAV&amp;action=show_schedule_scan'>{$_CLAM_SCHEDULE_SCAN}</a> ::
            <a href='admin.php?extension=ClamAV&amp;action=show_settings'>{$_SETTINGS}</a>
        </div>
    <p />
    <form method ='post' action='admin.php' name='scans'>
        <div>
            <input type='hidden' name='extension' value='ClamAV'>
            <input type='hidden' name='action' value='show_scan_results'>
        </div>
        <p />
            <table>
                <tr>
                    <td></td>
                    <td class='groups_actions'>
                        <select name='clamav_action' onChange='javascript:actions(document.scans.clamav_action.options[document.scans.clamav_action.selectedIndex].value)'>
                        {if $SCAN_LIST}
                            <option value='' />:: {$_WITH_SELECTED} ::
                            <option value='do_delete_scan' />{$_CLAM_DELETE_RESULTS}
                            <option value='do_reschedule_scan' />{$_CLAM_RESCHEDULE_SCAN}
                        {else}
                            <option value='' />:: {$_NO_ACTIONS} ::
                        {/if}
                        </select>
                    </td>
                </tr>
            </table>
        <table class='col_main_table'>
            <tr>
                <td>
                    <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                </td>
                <td class='acct_main_item_header' style='width: 50%;'>
                    {$_CLAM_DATE_SCHEDULED}
                </td>
                <td class='acct_main_item_header' style='width: 25%;'>
                    {$_CLAM_SCAN_STATUS}
                </td>
                <td class='acct_main_item_header' style='width: 25%;'>
                    {$_CLAM_SCAN_RESULTS}
                </td>
            </tr>
            {section name=scn loop=$SCAN_LIST}
            <tr style='background: #fff;'>
                <td>
                    {if $SCAN_LIST[scn].status != 'R'}
                    <input type='checkbox' name='scan_id[{$SCAN_LIST[scn].scan_id}]' value='{$SCAN_LIST[scn].scan_id}' />
                    {/if}
                </td>
                <td>
                    <a href='admin.php?extension=ClamAV&amp;action=show_scan_details&amp;scan_id={$SCAN_LIST[scn].scan_id}'>{$SCAN_LIST[scn].name}</a>
                </td>
                <td align='center'>
                    {if $SCAN_LIST[scn].status == 'P'}
                        <span style='font-weight: bold; color: orange;'>{$_CLAM_PENDING}</span>
                    {elseif $SCAN_LIST[scn].status == 'R'}
                        <span style='font-weight: bold; color: blue;'>{$_CLAM_RUNNING}</span>
                    {else}
                        <span style='font-weight: bold; color: green;'>{$_CLAM_FINISHED}</span>
                    {/if}
                </td>
                <td align='center'>
                    {if $SCAN_LIST[scn].status == 'F'}
                    <a href='admin.php?extension=ClamAV&action=show_scan_results&scan_id={$SCAN_LIST[scn].scan_id}'>{$_CLAM_VIEW_RESULTS}</a>
                    {/if}
                </td>
            </tr>
            {sectionelse}
            <tr style='background: #fff;'>
                <td colspan='8'>
                    {$_CLAM_NO_SCANS_SCHEDULED}
                </td>
            </tr>
            {/section}
        </table>
    </form>
    </td>
</tr>
</table>
<!-- END:	admin/clamav_main.tpl -->
{include file=footer.tpl}
