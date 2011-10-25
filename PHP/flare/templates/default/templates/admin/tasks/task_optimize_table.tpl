{include file='header.tpl'}
<!-- START:     admin/tasks/optimize_table.tpl -->
<table class='main'>
    <tr>
        <td style='width: 80%; vertical-align:top;'>
            <div style="text-align: center;">
                <!-- Put extension specific links here -->
            </div>
            <p />
            <form method='post' action='admin.php' name='accounts'>
                <div>
                    <input type='hidden' name='extension' value='Maintenance'>
                    <input type='hidden' name='action' value='run_task'>
                    <input type='hidden' name='task_id' value='{$TASK_ID}'>
                </div>
                <table align='center' bgcolor='#ccc' width='50%'>
                    <tr>
                        <td style='font-weight: bold;'>Table Name</td>
                        <td style='font-weight: bold;'>Size</td>
                        <td style='font-weight: bold;'>Overhead</td>
                    </tr>
                {section name=optimize loop=$OPTIMIZE_TABLE}
                    <tr style='background-color: #fff;'>
                        <td>{$OPTIMIZE_TABLE[$smarty.section.optimize.index].table_name}</td>
                        <td>{$OPTIMIZE_TABLE[$smarty.section.optimize.index].size}</td>
                        <td>{$OPTIMIZE_TABLE[$smarty.section.optimize.index].overhead}</td>
                    </tr>
                {/section}
                    <tr style='background-color: #fff;'>
                        <td style='font-weight: bold;'>Totals</td>
                        <td style='font-weight: bold;'>{$TOTAL_SIZE}</td>
                        <td style='font-weight: bold;'>{$TOTAL_OVERHEAD}</td>
                    </tr>
                </table>
                <p />
                <table align='center'>
                    <tr>
                        <td><input type='submit' name='optimize' value='Optimize Tables' class='input_btn'></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
<!-- END:       admin/tasks/optimize_table.tpl -->
{include file='footer.tpl'}
