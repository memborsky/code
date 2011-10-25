{include file=header.tpl}
<!-- START: 	admin/help_feedback_reply.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Help&amp;action=show_all_topics'>{$_HELP_ADM_SHOW_TOPICS}</a> ::
            <a href='admin.php?extension=Help&amp;action=show_add_topic'>{$_HELP_ADM_ADD_TOPIC}</a> ::
            <a href='admin.php?extension=Help&amp;action=show_feedback'>Show Feedback</a> ::
            <a href='admin.php?extension=Help&amp;action=show_settings'>{$_SETTINGS}</a>
        </div>
        <p />
        <form method='post' action='admin.php' name='help'>
        <div>
            <input type='hidden' name='extension' value='Help'>
            <input type='hidden' name='action' value='do_reply_feedback'>
        </div>
        {section name=fbk loop=$FEEDBACK}
        <table style='width: 100%; background-color: {cycle values="#ccc,#e6e6e6"};' cellspacing='10px;'>
            <tr>
                <td style='vertical-align: top;'>
                    Send Email From
                </td>
                <td>
                    <input type='hidden' name='id[]' value='{$FEEDBACK[fbk].id}'>
                    <input type='text' name='email_from[]' value='{$FROM}' maxlength='60' size='60'>
                </td>
            </tr>
            <tr>
                <td style='vertical-align: top;'>
                    Email Recipient
                </td>
                <td>
                    <input type='text' name='recipient[]' value='{$FEEDBACK[fbk].to}' size='60' maxlength='60'>
                </td>
            </tr>
            <tr>
                <td>Email Subject</td>
                <td>
                    <input type='text' name='email_subject[]' value='{$FEEDBACK[fbk].subj}' size='60' maxlength='255'/>
                </td>
            </tr>
            <tr>
                <td style='vertical-align: top;'>Email Body</td>
                <td>
                    <textarea cols='80' rows='10' name='email_body[]'>{$FEEDBACK[fbk].orig}</textarea>
                </td>
            </tr>
        </table>
        <p />
        { /section }
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: right; width: 50%;'>
                    <input type='submit' name='submit' value='Send Email' class='input_btn'>
                </td>
                <td style='text-align: left; width: 50%;'>
                    <input type='reset' name='reset' value='{$_RESET_FORM}' class='input_btn'>
                </td>
            </tr>
        </table>
    </form>
    </td>
</tr>
</table>
<!-- END: 	admin/help_feedback_reply.tpl -->
{include file=footer.tpl}
