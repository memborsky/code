{include file='header.tpl'}
<!-- START: 	admin/logging_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            {$_LOG_SHOW_LOG} :: 
            <a href='admin.php?extension=Logging&amp;action=show_clear_log'>{$_LOG_CLEAR_LOG}</a> ::
            <a href='admin.php?extension=Logging&amp;action=show_log_type_actions'>{$_LOG_CONFIG_LOG_TYPES}</a>
        </div>
        <p />
        <form name='log_type' method='post' action='admin.php'>
            <div>
                <input type='hidden' name='extension' value='Logging'>
                <input type='hidden' name='action' value='sort_log_type'>
            </div>
            <table class='logging'>
                <tr>
                    <td>
                        <select name='type' onChange='javascript:document.log_type.submit()'>
                            <option value='' />:: Show Entries of Selected Type ::
                            <option value='all' />ALL ENTRIES
                            {section name=log_entries loop=$LOG_ENTRIES}
                                <option value='{$LOG_ENTRIES[$smarty.section.log_entries.index]}' />{$LOG_ENTRIES[$smarty.section.log_entries.index]}
                            {sectionelse}
                                <option value='' />:: No Log Types Defined ::
                            {/section}
                        </select>
                    </td>
                </tr>
            </table>
            <p />
            <table class='logging_page_header'>
                <tr>
                    <td style='width: 5%; text-align: center;'>
                        {$FIRST}
                    </td>
                    <td style='width: 5%; text-align: center;'>
                        {$BACK}
                    </td>
                    <td style='width: 80%; text-align: center;'>
                        {$LINKS}
                    </td>
                    <td style='width: 5%; text-align: center;'>
                        {$NEXT}
                    </td>
                    <td style='width: 5%; text-align: center;'>
                        {$LAST}
                    </td>
                </tr>
            </table>
            <table class='col_main_table'>
                <tr>
                    <td class='logging_main_item_header'></td>
                    <td class='logging_main_item_header'>Log Type</td>
                    <td class='logging_main_item_header'>Date</td>
                    <td class='logging_main_item_header'>Time</td>
                    <td class='logging_main_item_header'>Remote IP</td>
                    <td class='logging_main_item_header'>Content</td>
                </tr>
            {section name=logs loop=$LOGS}
                <tr class='logging_row_entry'>
                    <td>
                        <input type='checkbox' name='log_id' value='{$LOGS[$smarty.section.logs.index][0]}' />
                    </td>
                    <td>{$LOGS[$smarty.section.logs.index][1]}</td>
                    <td>{$LOGS[$smarty.section.logs.index][2]}</td>
                    <td>{$LOGS[$smarty.section.logs.index][3]}</td>
                    <td>{$LOGS[$smarty.section.logs.index][4]}</td>
                    <td>{$LOGS[$smarty.section.logs.index][5]|truncate:80:"...":true}</td>
                </tr>
            {/section}
            </table>
            <table class='logging_page_header'>
                <tr>
                    <td style='width: 5%; text-align: center;'>
                        {$FIRST}
                    </td>
                    <td style='width: 5%; text-align: center;'>
                        {$BACK}
                    </td>
                    <td style='width: 80%; text-align: center;'>
                        {$LINKS}
                    </td>
                    <td style='width: 5%; text-align: center;'>
                        {$NEXT}
                    </td>
                    <td style='width: 5%; text-align: center;'>
                        {$LAST}
                    </td>
                </tr>
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END: 	admin/logging_main.tpl -->
{include file='footer.tpl'}
