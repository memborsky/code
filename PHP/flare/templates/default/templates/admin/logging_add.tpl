{include file='header.tpl'}
<!-- START: 	admin/logging_add.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Logging&amp;action=show_log_type_actions'>{$_LOG_SHOW_TYPES}</a> ::
            {$_LOG_ADD_NEW_TYPE}
        </div>
        <p />
        <form name='add_type' method='post' action='admin.php'>
            <div>
                <input type='hidden' name='extension' value='Logging'>
                <input type='hidden' name='action' value='do_add_log_type'>
            </div>
            <p />
            <table class='col_main_table'>
                <tr>
                    <td class='logging_main_item_header'>{$_LOG_TYPE}</td>
                    <td class='logging_main_item_header'>{$_LOG_CONTENT}</td>
                </tr>
                <tr>
                    <td>
                        <input type='text' name='type' maxlength='64' class='input_txt' />
                    </td>
                    <td>
                        <input type='text' name='content' maxlength='255' size='80' class='input_txt' />
                    </td>
                </tr>
            </table>
            <table style='width: 100%;'>
                <tr>
                    <td style='text-align: center;'>
                        <input type='submit' name='submit' value='{$_LOG_ADD_TYPE}' class='input_btn'>
                    </td>
                    <td style='text-align: center;'>
                        <input type='reset' value='{$_RESET_FORM}' class='input_btn'>
                    </td>
                </tr>
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END: 	admin/logging_add.tpl -->
{include file='footer.tpl'}
