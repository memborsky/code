{include file='header.tpl'}
<!-- START:     admin/tasks/task_fix_file_permissions.tpl -->
<table class='main'>
    <tr>
        <td style='width: 80%; vertical-align:top;'>
            <div style="text-align: center;">
                <!-- Put extension specific links here -->
            </div>
            <p />
            <form method='post' action='admin.php' name='task'>
                <div>
                    <input type='hidden' name='extension' value='Maintenance'>
                    <input type='hidden' name='action' value='run_task'>
                    <input type='hidden' name='task_id' value='{$TASK_ID}'>
                    <input type='hidden' name='type' value=''>
                </div>
                <table align='center' bgcolor='#ccc' width='50%'>
                    <tr>
                        <td style='font-weight: bold;'>Problems Found</td>
                    </tr>
                    <tr style='background-color: #fff;'>
                        <td style='font-weight: bold;'>Number of Dead Permissions</td>
                        <td style='font-weight: bold;'>{ $cdead }</td>
                        { if $dead }
                        <td align='center'><input type='button' value='Fix' onClick='javascript:actions("dead");' class='input_btn'></td>
                        { else }
                        <td align='center'><input type='button' value='Fix' onClick='javascript:actions("dead");' class='input_btn' disabled='disabled'></td>
                        { /if }
                    </tr>
                    <tr style='background-color: #fff;'>
                        <td style='font-weight: bold;'>Number of Missing File Permissions</td>
                        <td style='font-weight: bold;'>{ $cmissing_file }</td>
                        { if $missing_file }
                        <td align='center'><input type='button' value='Fix' onClick='javascript:actions("missing_file");' class='input_btn'></td>
                        { else }
                        <td align='center'><input type='button' value='Fix' onClick='javascript:actions("missing_file");' class='input_btn' disabled='disabled'></td>
                        { /if }
                    </tr>
                    <tr style='background-color: #fff;'>
                        <td style='font-weight: bold;'>Number of Missing Directory Permissions</td>
                        <td style='font-weight: bold;'>{ $cmissing_dir }</td>
                        { if $missing_dir }
                        <td align='center'><input type='button' value='Fix' onClick='javascript:actions("missing_dir");' class='input_btn'></td>
                        { else }
                        <td align='center'><input type='button' value='Fix' onClick='javascript:actions("missing_dir");' class='input_btn' disabled='disabled'></td>
                        { /if }
                    </tr>
                </table>

                <p />

                <table align='center' bgcolor='#ccc' width='50%'>
                    <tr>
                        <td style='font-weight: bold;'>Dead Permissions</td>
                    </tr>
                {section name=dd loop=$dead}
                    <tr style='background-color: #fff;'>
                        <input type='hidden' name='dead[]' value='{$dead[dd].id}'>
                        <td>{$dead[dd].file}</td>
                    </tr>
                {sectionelse}
                    <tr style='background-color: #fff;'><td>No Dead Permissions Found</td></tr>
                {/section}
                </table>

                <p />

                <table align='center' bgcolor='#ccc' width='50%'>
                    <tr>
                        <td style='font-weight: bold;'>Missing File Permissions</td>
                    </tr>
                {section name=mf loop=$missing_file}
                    <tr style='background-color: #fff;'>
                        <input type='hidden' name='missing_file[]' value='{$missing_file[mf].id}'>
                        <td>{$missing_file[mf].file}</td>
                    </tr>
                {sectionelse}
                    <tr style='background-color: #fff;'><td>No Missing File Permissions</td></tr>
                {/section}
                </table>

                <p />

                <table align='center' bgcolor='#ccc' width='50%'>
                    <tr>
                        <td style='font-weight: bold;'>Missing Directory Permissions</td>
                    </tr>
                {section name=md loop=$missing_dir}
                    <tr style='background-color: #fff;'>
                        <input type='hidden' name='missing_dir[]' value='{$missing_dir[md].id}'>
                        <td>{$missing_dir[md].dir}</td>
                    </tr>
                {sectionelse}
                    <tr style='background-color: #fff;'><td>No Missing Directory Permissions</td></tr>
                {/section}
                </table>
            </form>
        </td>
    </tr>
</table>
<!-- END:       admin/tasks/task_fix_file_permissions.tpl -->
{include file='footer.tpl'}
