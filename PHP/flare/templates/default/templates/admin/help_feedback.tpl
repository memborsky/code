{include file=header.tpl}
<!-- START: 	admin/help_feedback.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Help&amp;action=show_all_topics'>{$_HELP_ADM_SHOW_TOPICS}</a> ::
            <a href='admin.php?extension=Help&amp;action=show_add_topic'>{$_HELP_ADM_ADD_TOPIC}</a> ::
            Show Feedback ::
            <a href='admin.php?extension=Help&amp;action=show_settings'>Settings</a>
        </div>
        <p />
        <form method ='post' action='admin.php' name='help'>
            <div>
                <input type='hidden' name='extension' value='Help'>
                <input type='hidden' name='action' value='do_delete_feedback'>
            </div>
            <table>
                <tr>
                    <td></td>
                    <td class='groups_actions'>
                        <select name='feedback_action' onChange='javascript:actions(document.help.feedback_action.options[document.help.feedback_action.selectedIndex].value)'>
                        {if $FEEDBACK}
                            <option value='' />:: {$_WITH_SELECTED} ::
                            <optgroup label="Contact">
                            <option value='show_reply_feedback' />Reply to Feedback
                            </optgroup>
                            <optgroup label="Modification">
                            <option value='do_mark_read' />Mark as Read
                            <option value='do_mark_unread' />Mark as Unread
                            </optgroup>
                            <optgroup label="Deletion">
                            <option value='do_delete_feedback' />Delete Feedback
                            </optgroup>
                        {else}
                            <option value='' />:: {$_NO_ACTIONS} ::
                        {/if}
                        </select>
                    </td>
                </tr>
            </table>
            <table class='col_main_table'>
            <tr>
                <td>
                    <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                </td>
                <td class='acct_main_item_header'>
                    Short Description of Feedback
                </td>
                <td class='acct_main_item_header'>
                    Date Sent
                </td>
                <td class='acct_main_item_header'>
                    Email of Sender
                </td>
            </tr>
            {section name=fbk loop=$FEEDBACK}
            { if $FEEDBACK[fbk].status == 'U' }
            <tr style='background: #fc6;'>
            { else }
            <tr style='background: #fff;'>
            { /if }
                <td>
                    <input type='checkbox' name='feedback_id[]' value='{$FEEDBACK[fbk].feedback_id}' />
                </td>
                <td style='width: 55%;'>
                    <a href='#' onClick='javascript:read_feedback({$FEEDBACK[fbk].feedback_id})'>{$FEEDBACK[fbk].desc|truncate:150}</a>
                </td>
                <td style='width: 20%;'>
                    {$FEEDBACK[fbk].date}
                </td>
                <td style='width: 25%;'>
                    {$FEEDBACK[fbk].email}
                </td>
            </tr>
            {sectionelse}
            <tr style='background: #fff;'>
                <td>There is currently no feedback available</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            {/section}
            </table>
        </form>
    </td>
</tr>
</table>
<!-- START:	admin/help_feedback.tpl -->
{include file=footer.tpl}
