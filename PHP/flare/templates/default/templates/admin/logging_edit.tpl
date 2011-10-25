{include file='header.tpl'}
<!-- START: 	admin/logging_edit.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Logging&amp;action=show_log_type_actions'>Show All Log Types</a> ::
            Add New Log Type
        </div>
        <p />
        <form name='edit_type' method='post' action='admin.php'>
            <div>
                <input type='hidden' name='extension' value='Logging'>
                <input type='hidden' name='action' value='do_edit_log_type'>
            </div>
            <p />
            <table class='col_main_table'>
                <tr>
                    <td class='logging_main_item_header'>Log Type</td>
                    <td class='logging_main_item_header'>Content</td>
                </tr>
                {section name=log_info loop=$LOG_INFO}
                <tr>
                    <td align='center'>
                        <input type='text' name='type[{$LOG_INFO[$smarty.section.log_info.index].type_id}]' maxlength='32' value='{$LOG_INFO[$smarty.section.log_info.index].type}' class='input_txt' />
                    </td>
                    <td align='center'>
                        <input type='text' name='content[{$LOG_INFO[$smarty.section.log_info.index].type_id}]' maxlength='255' size='80' value='{$LOG_INFO[$smarty.section.log_info.index].content}' class='input_txt' />
                    </td>
                </tr>
                {/section}
                <tr><td>&nbsp;</td></tr>
            </table>
            <p />
            <table style='width: 100%; text-align: center;'>
                <tr>
                    <td align='center'><input type='submit' name='submit' value='Save Changes' class='input_btn'/></td>
                    <td align='center'><input type='reset' value='Undo Changes' class='input_btn'/></td>
                </tr>
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END: 	admin/logging_edit.tpl -->
{include file='footer.tpl'}
