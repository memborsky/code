{include file='header.tpl'}
<!-- START:     admin/tasks/task_remove_old_reports.tpl -->
<table class='main'>
    <tr>
        <td style='width: 100%; vertical-align:top;'>
            <div style="text-align: center;">
                <!-- Put extension specific links here -->
            </div>
            <p />
            <table width='100%'>
                <tr>
                    <td width='50%' valign='top'>
                        <form method='post' action='admin.php' name='maintenance'>
                        <div>
                            <input type='hidden' name='extension' value='Maintenance'>
                            <input type='hidden' name='action' value='run_task'>
                            <input type='hidden' name='task_id' value='{$TASK_ID}'>
                        </div>
                        <table align='center' bgcolor='#ccc' width='100%'>
                            <tr>
                                <td align='center'>
                                    <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                                </td>
                                <td style='font-weight: bold;'>Report Filename</td>
                                <td style='font-weight: bold;'>Date Last Modified</td>
                            </tr>
                        {section name=gph loop=$GRAPHS}
                            <tr style='background-color: #fff;'>
                                <td align='center'><input type='checkbox' name='report[]' value='{$GRAPHS[gph].name}'></td>
                                <td><span class='hyperlink' onClick='change_report_img("{$GRAPHS[gph].link}")'>{$GRAPHS[gph].name}</span></td>
                                <td>{$GRAPHS[gph].date}</td>
                            </tr>
                        {sectionelse}
                            <tr style='background-color: #fff;'>
                                <td colspan='3'>
                                    No reports found
                                </td>
                            </tr>
                        {/section}
                        </table>
                    </td>
                    <td width='50%' valign='top'>
                        { if $GRAPHS }
                        <fieldset>
                            <legend>Report Graph Viewer</legend>
                                <img id='report_graph_viewer' src=''>
                        </fieldset>
                        { /if }
                    </td>
                </tr>
            </table>
            <p />
            <table align='center'>
                <tr>
                    { if $GRAPHS }
                    <td><input type='submit' name='optimize' value='Remove Selected Reports' class='input_btn'></td>
                    { else }
                    <td><input type='submit' name='optimize' value='Remove Selected Reports' class='input_btn' disabled='disabled'></td>
                    { /if }
                </tr>
            </table>
            </form>
        </td>
    </tr>
</table>
<!-- END:       admin/tasks/task_remove_old_reports.tpl -->
{include file='footer.tpl'}
