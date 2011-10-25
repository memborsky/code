{include file='header.tpl'}
<!-- START:     admin/reports/close_to_quota_groups.tpl -->
<table class='main'>
    <tr>
        <td style='width: 80%; vertical-align:top;'>
            <div style="text-align: center;">
                <!-- Put extension specific links here -->
                <a href='admin.php?extension=Reporting_System&amp;action=show_report&amp;report_id={$REPORT_ID}&amp;top=10'>Top 10</a> ::
                <a href='admin.php?extension=Reporting_System&amp;action=show_report&amp;report_id={$REPORT_ID}&amp;top=25'>Top 25</a> ::
                <a href='admin.php?extension=Reporting_System&amp;action=show_report&amp;report_id={$REPORT_ID}&amp;top=50'>Top 50</a> ::
                <a href='admin.php?extension=Reporting_System&amp;action=show_report&amp;report_id={$REPORT_ID}&amp;top=100'>Top 100</a>
            </div>
            &nbsp;<p />
            <div style='text-align: center;'>
                <img src='{$REPORT_GRAPH}' />
            </div>
        </td>
    </tr>
</table>
<!-- END:       admin/reports/close_to_quota_groups.tpl -->
{include file='footer.tpl'}
