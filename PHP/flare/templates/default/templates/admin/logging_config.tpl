{include file='header.tpl'}
<!-- START: 	admin/logging_config.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            Show All Log Types ::
            <a href='admin.php?extension=Logging&amp;action=show_add_log_type'>Add New Log Type</a>
        </div>
        <p />
        <form name='log_action' method='post' action='admin.php'>
            <div>
                <input type='hidden' name='extension' value='Logging'>
            </div>
            <table class='logging_config_actions'>
                <tr>
                    <td>
                        <select name='action' onChange='javascript:document.log_action.submit()'>
                            {if $LOG_TYPES}
                                <option value='' />:: With Selected ::
                                <option value='show_edit_log_type' />Edit Type(s)
                                <option value='do_delete_log_type' />Delete Type(s)
                            {else}
                                <option value='' />:: No Log Types Defined ::
                            {/if}
                        </select>
                    </td>
                </tr>
            </table>
            <p />
            <table class='col_main_table'>
                <tr>
                    <td class='logging_main_item_header'></td>
                    <td class='logging_main_item_header'>Log Type</td>
                    <td class='logging_main_item_header'>Content</td>
                    <td class='logging_main_item_header'>Description</td>
                </tr>
            {section name=log_types loop=$LOG_TYPES}
                <tr class='logging_row_entry'>
                    <td>
                        <input type='checkbox' name='log_type[{$LOG_TYPES[$smarty.section.log_types.index].type_id}]' value='{$LOG_TYPES[$smarty.section.log_types.index].type}' />
                    </td>
                    <td>{$LOG_TYPES[$smarty.section.log_types.index].type}</td>
                    <td>{$LOG_TYPES[$smarty.section.log_types.index].content}</td>
                    <td>{$LOG_TYPES[$smarty.section.log_types.index].description}</td>
                </tr>
            {/section}
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END: 	admin/logging_config.tpl -->
{include file='footer.tpl'}
