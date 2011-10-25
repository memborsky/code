{include file=header.tpl}
<!-- START: 	admin/email_mailing_lists.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Email&amp;action=show_write_email'>Send New Email</a> ::
            Mailing Lists ::
            <a href='admin.php?extension=Email&amp;action=show_create_mailing_list'>Create Mailing List</a> ::
            <a href='admin.php?extension=Email&amp;action=show_settings'>{$_SETTINGS}</a>
        </div>
        <p />
        <form method='post' action='admin.php' name='email'>
        <div>
            <input type='hidden' name='extension' value='Email'>
            <input type='hidden' name='action' value='do_edit_mailing_list'>
        </div>
        <table>
            <tr>
                <td></td>
                <td class='groups_actions'>
                    <select name='email_action' onChange='javascript:actions(document.email.email_action.options[document.email.email_action.selectedIndex].value)'>
                    {if $MAILING_LISTS}
                        <option value='' />:: {$_WITH_SELECTED} ::
                        <option value='show_change_mailing_list' />Change Mailing List
                        <option value='do_delete_mailing_list' />Delete Mailing List
                    {else}
                        <option value='' />:: {$_NO_ACTIONS} ::
                    {/if}
                    </select>
                </td>
            </tr>
        </table>
        <p />
        <table class='col_main_table'>
            <tr>
                <td style='width: 1%;'>
                    <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                </td>
                <td style='font-weight: bold;'>
                    Mailing List Name
                </td>
                <td style='font-weight: bold;'>
                    Creation Date
                </td>
                <td style='font-weight: bold;'>
                    Number of People On List
                </td>
            </tr>
            { section name=lst loop=$MAILING_LISTS }
            <tr style='background-color: #fff;'>
                <td>
                    <input type='checkbox' name='mailing_list[]' value='{$MAILING_LISTS[lst].id}'>
                </td>
                <td>
                    {$MAILING_LISTS[lst].name}
                </td>
                <td>
                    {$MAILING_LISTS[lst].date}
                </td>
                <td>
                    {$MAILING_LISTS[lst].members}
                </td>
            </tr>
            { sectionelse }
            <tr style='background-color: #fff;'>
                <td colspan='3'>
                    No mailing lists exist right now
                </td>
                <td></td>
            </tr>
            { /section }
        </table>
    </td>
</tr>
</table>
<!-- END: 	admin/email_mailing_lists.tpl -->
{include file=footer.tpl}
