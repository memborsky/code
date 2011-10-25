{include file='header.tpl'}
<!-- START: 	admin/groups_config.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Groups&amp;action=show_groups'>{$_GROUPS_ADM_SHOW_ALL_GROUPS}</a> 
            :: <a href='admin.php?extension=Groups&amp;action=show_add_group'>Create Group</a>
            :: Settings
        </div>
    <p />
    <form method ='post' action='admin.php' name='config'>
        <div>
            <input type='hidden' name='extension' value='Filesystem'>
            <input type='hidden' name='action' value='do_save_settings'>
        </div>
        <table style='width: 100%;'>
        </table>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: center;'>
                    <input type='submit' name='submit' value='Save Settings' class='input_btn' />
                    <input type='reset' value='Undo Changes' class='input_btn' />
                </td>
            </tr>
        </table>
    </form>
</td>
</tr>
</table>
<!-- END: 	admin/filesystem_config.tpl -->
{include file='footer.tpl'}
