{include file='header.tpl'}
<!-- START:     admin/tasks/task_clear_tmp_table.tpl -->
<table class='main'>
    <tr>
        <td style='width: 80%; vertical-align:top;'>
            <div style="text-align: center;">
                <!-- Put extension specific links here -->
            </div>
            <p />
            <form method='post' action='admin.php' name='clear'>
                <div>
                    <input type='hidden' name='extension' value='Maintenance'>
                    <input type='hidden' name='action' value='run_task'>
                    <input type='hidden' name='task_id' value='{$TASK_ID}'>
                </div>
                <table align='center' bgcolor='#ccc' width='50%'>
                    <tr>
                        <td style='font-weight: bold;'></td>
                        <td style='font-weight: bold;'>Temp Data</td>
                    </tr>
                {section name=clear loop=$TMP}
                    <tr bgcolor='#fff'>
                        <td>{$TMP[clear].tmp_id}</td>
                        <td>{$TMP[clear].data}</td>
                    </tr>
                {sectionelse}
                    <tr bgcolor='#fff'>
                        <td colspan='2'>The tmp table is empty</td>
                    </tr>
                {/section}
                </table>
                <p />
                <table align='center'>
                    <tr>
                        { if $TMP }
                        <td><input type='submit' name='clear' value='Clear Table' class='input_btn'></td>
                        { else }
                        <td><input type='submit' name='clear' value='Clear Table' class='input_btn' disabled='disabled'></td>
                        { /if }
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
<!-- END:       admin/tasks/task_clear_jobs_table.tpl -->
{include file='footer.tpl'}
