{include file='header_empty.tpl'}
<!-- START: 	feedback.tpl -->
<table class='main'>
<tr>
    <td class='myfiles_list_main'>
        <div class='myfiles_section_header'>
            <h3>{$_HELP_FEEDBACK}</h3>
        </div>
        <p />
        <div style='width: 100%; text-align: left'>
            {$_HELP_FEEDBACK_MESG}
        </div>
        <form name='feedback' method='post' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Help'>
                <input type='hidden' name='action' value='do_leave_feedback'>
                <input type='hidden' name='date' value='{$DATE}'>
            </div>
            <table style='width: 100%; text-align: center'>
                <tr>
                    <td style='vertical-align: top;'>
                        {$_EMAIL}:
                    </td>
                    <td style='text-align: left;'>
                        <input type='text' name='email' value='anonymous' maxlength='64' class='input_txt'/>
                    </td>
                </tr>
                <tr>
                    <td style='vertical-align: top'>
                        {$_HELP_FEEDBACK_SHORT}:
                    </td>
                    <td style='text-align: left;'>
                        <input type='text' name='short_desc' maxlength='255' class='input_txt'/>
                    </td>
                </tr>
                <tr>
                    <td style='vertical-align: top;'>
                        {$_HELP_FEEDBACK}:
                    </td>
                    <td style='text-align: left'>
                        <textarea name='content' cols='40' rows='10'></textarea>
                    </td>
                </tr>
            </table>
            <table style='width: 100%; text-align: left;'>
                <tr>
                    <td style='text-align: center'>
                        <input type='submit' name='submit' value='{$_HELP_FEEDBACK_SEND}' class='input_btn'>
                    </td>
                    <td style='text-align: left;'>
                        <input type='reset' name='reset' class='input_btn'>
                    </td>
                </tr>
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END:	feedback.tpl -->
{include file='footer.tpl'}
