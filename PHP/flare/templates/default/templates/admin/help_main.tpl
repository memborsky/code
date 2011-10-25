{include file=header.tpl}
<!-- START: 	admin/help_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            {$_HELP_ADM_SHOW_TOPICS} ::
            <a href='admin.php?extension=Help&amp;action=show_add_topic'>{$_HELP_ADM_ADD_TOPIC}</a> ::
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
    <form method ='post' action='admin.php' name='help'>
        <div>
            <input type='hidden' name='extension' value='Help'>
            <input type='hidden' name='action' value='update_topic'>
        </div>
        <table>
            <tr>
                <td></td>
                <td class='groups_actions'>
                    <select name='topic_action' onChange='javascript:actions(document.help.topic_action.options[document.help.topic_action.selectedIndex].value)'>
                    {if $TOPIC_LIST}
                        <option value='' />:: {$_WITH_SELECTED} ::
                        <option value='show_edit_topic' />Edit Topics
                        <option value='do_delete_topics' />Delete Topics
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
                Topic Name	
            </td>
            <td class='acct_main_item_header'>
                Brief Content Overview
            </td>
            <td class='acct_main_item_header'>
                Minimum Required User Level
            </td>
        </tr>
        {section name=topic_list loop=$TOPIC_LIST}
        <tr style='background: #fff;'>
            <td>
                <input type='checkbox' name='topic_id[]' value='{$TOPIC_LIST[$smarty.section.topic_list.index].help_id}' />
            </td>
            <td style='width: 25%;'>
                {$TOPIC_LIST[$smarty.section.topic_list.index].name}
            </td>
            <td style='width: 50%;'>
                {$TOPIC_LIST[$smarty.section.topic_list.index].content|truncate:100}
            </td>
            <td style='width: 25%;'>
                {$TOPIC_LIST[$smarty.section.topic_list.index].level}
            </td>
        </tr>
        {sectionelse}
        <tr style='background: #fff;'>
            <td>There are currently no topics to display. Add one now!</td>
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
<!-- START:	admin/help_main.tpl -->
{include file=footer.tpl}
