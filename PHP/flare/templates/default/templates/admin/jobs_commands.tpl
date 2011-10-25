{include file=header.tpl}
<!-- START:	admin/jobs_commands.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            Summary ::
            <a href='admin.php?extension=Jobs&amp;action=schedule_new_job'>Schedule a New Job</a>
            <a href='admin.php?extension=Jobs&amp;action=schedule_new_job'>Available Commands</a>
        </div>
        <p />
    <p />
    <form method ='post' action='admin.php' name='jobs'>
        <div>
            <input type='hidden' name='extension' value='Jobs'>
            <input type='hidden' name='action' value='show_job_info'>
        </div>
        <p />&nbsp;<p />
        <table class='col_main_table'>
            <tr>
                <td class='acct_main_item_header' style='width: 75%'>
                    Commands
                </td>
            </tr>
            { section name=cmds loop=$CMD_LIST }
            <tr style='background-color: #fff;'>
                <td style='padding-left: 20px;'>
                    {$CMD_LIST[cmds].cmd}
                </td>
            </tr>
            { sectionelse }
            <tr style='background-color: #fff;'>
                <td>
                    No commands found
                </td>
            </tr>
            { /section }
        </table>
    </form>
    </td>
</tr>
</table>
<!-- END:	admin/jobs_commands.tpl -->
{include file=footer.tpl}
