{include file='header.tpl'}
<!-- START: 	admin/logging_clear.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Logging&amp;action=show_log'>{$_LOG_SHOW_LOG}</a> :: 
            {$_LOG_CLEAR_LOG} ::
            <a href='admin.php?extension=Logging&amp;action=show_log_type_actions'>Configure Log Types</a>
        </div>
        <p />
        <form name='log_type' method='post' action='admin.php'>
            <div>
                <input type='hidden' name='extension' value='Logging'>
                <input type='hidden' name='action' value='do_clear_log'>
            </div>
            <table class='logging'>
                <tr>
                    <td>
                        <select name='type' onChange='javascript:document.log_type.submit()'>
                            <option value='' />:: Choose Action ::
                            <option value='delete' />Delete Entries of Selected Type From Log
                        </select>
                    </td>
                </tr>
            </table>
            <p />
            <table class='col_main_table'>
                <tr>
                    <td class='logging_main_item_header' style='width: 1%;'></td>
                    <td class='logging_main_item_header'>Log Type</td>
                </tr>
            {section name=logs loop=$LOG_TYPES}
                <tr class='logging_row_entry'>
                    <td>
                        <input type='checkbox' name='log_type[]' value='{$LOG_TYPES[$smarty.section.logs.index].type}' />
                    </td>
                    <td style='padding-left: 10px'>{$LOG_TYPES[$smarty.section.logs.index].type}</td>
                </tr>
            {/section}
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END: 	admin/logging_clear.tpl -->
{include file='footer.tpl'}
