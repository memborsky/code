{include file=header.tpl}
<!-- START:	admin/jobs_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            Summary ::
            <a href='admin.php?extension=Jobs&amp;action=schedule_new_job'>Schedule a New Job</a> ::
            <a href='admin.php?extension=Jobs&amp;action=show_commands'>Show Commands</a>
        </div>
        <p />
    <p />
    <form method ='post' action='admin.php' name='jobs'>
        <div>
            <input type='hidden' name='extension' value='Jobs'>
            <input type='hidden' name='action' value='show_job_info'>
        </div>
        <p />
        <table>
            <tr>
                <td></td>
                <td class='groups_actions'>
                    <select name='account_action' onChange='javascript:actions(document.accounts.account_action.options[document.accounts.account_action.selectedIndex].value)'>
                    {if $ACCOUNT_LIST_A || $ACCOUNT_LIST_P || $ACCOUNT_LIST_D}
                        <option value='' />:: {$_WITH_SELECTED} ::
                        <optgroup label="Modification">
                        <option value='show_change_accounts' />Change Accounts
                        <option value='do_activate_accounts' />Activate Accounts
                        <option value='do_deactivate_accounts' />Deactivate Accounts
                        </optgroup>
                        <optgroup label="Deletion">
                        <option value='do_delete_accounts' />Delete Accounts
                        { if $USE_JOBS }
                        <option value='do_purge_accounts' />Purge All Accounts
                        { /if }
                        </optgroup>
                    {else}
                        <option value='' />:: {$_NO_ACTIONS} ::
                    {/if}
                    </select>
                </td>
            </tr>
        </table>
        <p />
        <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header' style='width: 33%;'>
                    Scheduled Jobs
                </td>
                <td class='acct_main_item_header' style='width: 33%;'>
                    Last Run
                </td>
                <td class='acct_main_item_header' style='width: 33%;'>
                    Run Interval
                </td>
            </tr>
            {section name=job loop=$JOB_LIST}
            <tr style='background: #fff;'>
                <td>
                    <input type='hidden' name='task_id[{$TASK_LIST[tsk].task_id}]' value='{$TASK_LIST[tsk].task_id}' />
                    <a href='admin.php?extension=Maintenance&amp;action=show_task&amp;task_id={$TASK_LIST[tsk].task_id}'>{$TASK_LIST[tsk].name}</a>
                </td>
                <td>
                    {$JOB_LIST[job].description}
                </td>
            </tr>
            {sectionelse}
            <tr style='background: #fff;'>
                <td colspan='8'>
                    No jobs found
                </td>
            </tr>
            {/section}
        </table>
    </form>
    </td>
</tr>
</table>
<!-- END:	admin/jobs_main.tpl -->
{include file=footer.tpl}
