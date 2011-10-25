{include file='header.tpl'}
<!-- START:     admin/reports/log_entry_counts.tpl -->
<table class='main'>
    <tr>
        <td style='width: 80%; vertical-align:top;'>
            <div style="text-align: center;">
                <!-- Put extension specific links here -->
            </div>
            <p />
            <form method='post' action='admin.php'>
            <input type='hidden' name='extension' value='Reporting_System'>
            <input type='hidden' name='action' value='show_report'>
            <input type='hidden' name='report_id' value='{$REPORT_ID}'>
            <table align='center' cellspacing='10'>
                <tr>
                    <td style='vertical-align: top'>
                        {if $TYPES}
                            <span style='font-weight: bold;'>Log Type (Use Ctrl to Select More Than One)</span>
                            <p />
                            <select name='log_type[]' multiple='multiple' size='30'>
                            {section name=type loop=$TYPES}
                                <option value='{$TYPES[$smarty.section.type.index]}' />{$TYPES[$smarty.section.type.index]}
                            {/section}
                            </select>
                        {else}
                            No log types defined
                        {/if}
                    </td>
                    <td style='vertical-align: top'>
                        {if $TYPES}
                            <span style='font-weight: bold;'>Plot Period</span>
                            <p />
                            <select name='period'>
                                <option value='day' />Day
                                <option value='week' />Week
                                <option value='month' />Month
                                <option value='all' />All
                            </select>
                            <p />&nbsp;<p />
                            <input type='submit' name='submit' value='Plot Graph' class='input_btn'>
                        {/if}
                    </td>
                    <td style='width: 100%;'>
                        <img src='{$REPORT_GRAPH}' />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- END:       admin/reports/log_entry_counts.tpl -->
{include file='footer.tpl'}
