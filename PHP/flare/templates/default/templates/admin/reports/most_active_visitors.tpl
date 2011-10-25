{include file='header.tpl'}
<!-- START:     admin/reports/most_active_visitors.tpl -->
<table class='main'>
    <tr>
        <td style='width: 80%; vertical-align:top;'>
            <div style="text-align: center;">
                <!-- Put extension specific links here -->
                <a href='admin.php?extension=Reporting_System&amp;action=show_report&amp;report_id={$REPORT_ID}&amp;period=day'>Day Report</a> ::
                <a href='admin.php?extension=Reporting_System&amp;action=show_report&amp;report_id={$REPORT_ID}&amp;period=week'>Weekly Report</a> ::
                <a href='admin.php?extension=Reporting_System&amp;action=show_report&amp;report_id={$REPORT_ID}&amp;period=month'>Monthly Report</a> ::
                <a href='admin.php?extension=Reporting_System&amp;action=show_report&amp;report_id={$REPORT_ID}&amp;period=all'>All Hits</a><p />
            </div>
            <p />
            <table align='center'>
                <tr>
                    <td style='width: 100%;'>
                        <img src='{$REPORT_GRAPH}' />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- END:       admin/reports/most_active_visitors.tpl -->
{include file='footer.tpl'}
