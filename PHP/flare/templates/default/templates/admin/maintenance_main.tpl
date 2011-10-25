{include file=header.tpl}
<!-- START:	admin/maintenance_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            Summary
        </div>
        <p />
    <p />
    <form method ='post' action='admin.php' name='tasks'>
        <div>
            <input type='hidden' name='extension' value='Maintenance'>
            <input type='hidden' name='action' value='show_task_info'>
        </div>
        <p />&nbsp;<p />
        <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header' style='width: 75%'>
                    Maintenance Mode
                </td>
                <td class='acct_main_item_header' style='width: 25%'></td>
            </tr>
            <tr style='background-color: #fff;'>
                <td>
                    Maintenance Mode allows an admin to lock the Flare installation so that the admin can update the system and debug any problems that may arise from updating. Users will be shown a "maintenance" page while Maintenance Mode is in effect. Maintenance Mode lasts for 2 hours, or until the admin re-clicks the Maintenance Mode button to disable it.
                    <p />
                    Note that you dont need to be in Maintenance Mode to run any of the tasks below.
                </td>
                <td align='center'>
                    { if $MAINTENANCE_MODE }
                    <input type='button' name='change_mode' value='Deactivate Maintenance Mode' class='input_btn' onClick='actions("do_maintenance_mode_off")'>
                    { else }
                    <input type='button' name='change_mode' value='Activate Maintenance Mode' class='input_btn' onClick='actions("do_maintenance_mode_on")'>
                    { /if }
                </td>
            </tr>
        </table>
        <p />
        <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header' style='width: 50%;'>
                    Task Name
                </td>
                <td class='acct_main_item_header' style='width: 50%;'>
                    Description
                </td>
                <td></td>
            </tr>
            {section name=tsk loop=$TASK_LIST}
            <tr style='background: #fff;'>
                <td>
                    <input type='hidden' name='task_id[{$TASK_LIST[tsk].task_id}]' value='{$TASK_LIST[tsk].task_id}' />
                    <a href='admin.php?extension=Maintenance&amp;action=show_task&amp;task_id={$TASK_LIST[tsk].task_id}'>{$TASK_LIST[tsk].name}</a>
                </td>
                <td>
                    {$TASK_LIST[tsk].description}
                </td>
                <td>
                    <a href='admin.php?extension=Maintenance&amp;action=run_task&amp;task_id={$TASK_LIST[tsk].task_id}'>Run</a>
                </td>
            </tr>
            {sectionelse}
            <tr style='background: #fff;'>
                <td colspan='8'>
                    No tasks currently exist
                </td>
            </tr>
            {/section}
        </table>
    </form>
    </td>
</tr>
</table>
<!-- END:	admin/maintenance_main.tpl -->
{include file=footer.tpl}
