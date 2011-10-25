{include file='header.tpl'}
<!-- START: 	admin/help_config.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            <a href='admin.php?extension=Help&amp;action=show_all_topics'>{$_HELP_ADM_SHOW_TOPICS}</a> ::
            <a href='admin.php?extension=Help&amp;action=show_add_topic'>{$_HELP_ADM_ADD_TOPIC}</a> ::
            <a href='admin.php?extension=Help&amp;action=show_feedback'>{$_HELP_ADM_FEEDBACK}</a> ::
            {$_SETTINGS}
        </div>
    <p />
    <form method ='post' action='admin.php' name='config'>
        <div>
            <input type='hidden' name='extension' value='Help'>
            <input type='hidden' name='action' value='do_save_settings'>
        </div>
    <table style='width: 100%;'>
    <tr>
        <td>Extension is visible to normal users</td>
        <td>
            {if $VISIBLE == 1}
                <input type='radio' name='visible' value='1' checked='checked' />Yes
                <input type='radio' name='visible' value='0' />No
            {else}
                <input type='radio' name='visible' value='1' />Yes
                <input type='radio' name='visible' value='0' checked='checked' />No
            {/if}
        </td>
    </tr>
    </table>
    <p />
    <table style='width: 100%;'>
        <tr>
            <td style='text-align: center;'>
                <input type='submit' name='submit' value='Save Settings' class='input_btn' />
                <input type='reset' value='Undo Change' class='input_btn' />
            </td>
        </tr>
    </table>
    </form>
</td>
</tr>
</table>
<!-- END: 	admin/help_config.tpl -->
{include file='footer.tpl'}
