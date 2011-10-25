{include file=header.tpl}
<!-- START:	admin/reporting_messages_edit.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <a href='admin.php?extension=Reporting_System&amp;=show_summary'>{$_REPORT_SUMMARY}</a>
            :: {$_REPORT_ANNOUNCE_NEW_MESG}
        </div>
        <p />
        <form method ='post' action='admin.php' name='messages'>
            <div>
                <input type='hidden' name='extension' value='Reporting_System'>
                <input type='hidden' name='action' value='do_change_messages'>
            </div>
            {section name=msg loop=$MESSAGES}
            <table style='width: 100%; background-color: {cycle values="#ccc,#e6e6e6"};' cellspacing='10px;'>
                <tr>
                    <td style='font-size: 10pt; font-weight: bold;'>
                        {$_REPORT_SUBJECT}:
                    </td>
                    <td>
                        <input type='hidden' name='mesg_id[]' value='{$MESSAGES[msg].id}'>
                        <input type='text' name='subject[]' maxlength='255' value='{$MESSAGES[msg].subj}'>
                    </td>
                </tr>
                <tr>
                    <td style='font-size: 10pt; font-weight: bold;' valign='top'>
                        {$_REPORT_MESG_TO_ANNOUNCE}
                    </td>
                    <td>
                        <textarea name='system_message[]' cols='60' rows='10'>{$MESSAGES[msg].content}</textarea>
                    </td>
                </tr>
            </table>
            <p />
            {/section}
            <table style='width: 100%;'>
                <tr>
                    <td style='text-align: center; width: 50%;'>
                        <input type='submit' name='submit' value='{$_REPORT_ANNOUNCE_MES}' class='input_btn'>
                    </td>
                    <td style='text-align: center; width: 50%;'>
                        <input type='reset' value='{$_REPORT_CLEAR_MESG}' class='input_btn'>
                    </td>
                </tr>
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END:	admin/reporting_messages_edit.tpl -->
{include file=footer.tpl}
