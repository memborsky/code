{include file=header.tpl}
<!-- START: 	admin/help_add_topic.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Help&amp;action=show_all_topics'>{$_HELP_ADM_SHOW_TOPICS}</a> ::
            {$_HELP_ADM_ADD_TOPIC} ::
            <a href='admin.php?extension=Help&amp;action=show_feedback'>Show Feedback</a> ::
            <a href='admin.php?extension=Help&amp;action=show_settings'>Settings</a>
        </div>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='accts_section_header'>
                    {$_HELP_WELCOME}
                </td>
            </tr>
        </table>
    <p />
    <form method='post' action='admin.php' name='help'>
        <div>
            <input type='hidden' name='extension' value='Help'>
            <input type='hidden' name='action' value='do_add_topic'>
        </div>
        <table style='width: 100%;'>
            <tr>
                <td style='vertical-align: top;'>
                    {$_HELP_ADM_PARENT_CATEGORY}:
                </td>
                <td>
                    <select name='parent_topic'>
                            <option value='' />{$_NONE}
                        {section name=parent_topic loop=$TOPIC_LIST}
                            <option value='{$TOPIC_LIST[$smarty.section.parent_topic.index].help_id}' />{$TOPIC_LIST[$smarty.section.parent_topic.index].name}
                        {sectionelse}
                            <option value='' />{$_NONE}
                        {/section}
                    </select>
                </td>
            </tr>
            <tr>
                <td>Type:</td>
                <td>
                    <input type='radio' name='type' value='topic' CHECKED />Topic
                    <input type='radio' name='type' value='category' />Category
                </td>
            </tr>
            <tr>
                <td style='vertical-align: top;'>{$_HELP_ADM_REQUIRED_LEVEL}</td>
                <td>
                    <select name='user_level'>
                        {section name=user_level loop=$USER_LEVEL}
                            <option value='{$USER_LEVEL[$smarty.section.user_level.index].user_level}' />{$USER_LEVEL[$smarty.section.user_level.index].user_level}
                        {sectionelse}
                            <option value='1000' />1000
                        {/section}
                    </select>
                </td>
            </tr>
            <tr>
                <td style='vertical-align: top;'>{$_NAME}:</td>
                <td><input type='text' name='topic_name' size='50' class='input_txt'></td>
            </tr>
            <tr>
                <td style='vertical-align: top;'>{$_HELP_CONTENT}:</td>
                <td><textarea cols='70' rows='10' name='topic_content' class='input_txt'></textarea></td>
            </tr>
        </table>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td style='text-align: right;'>
                    <input type='submit' name='submit' value='{$_HELP_ADM_ADD_TOPIC}' class='input_btn'>
                </td>
                <td style='text-align: left;'>
                    <input type='reset' name='reset' value='{$_RESET_FORM}' class='input_btn'>
                </td>
            </tr>
        </table>
    </form>
    </td>
</tr>
</table>
<!-- END: 	admin/help_add_topic.tpl -->
{include file=footer.tpl}
