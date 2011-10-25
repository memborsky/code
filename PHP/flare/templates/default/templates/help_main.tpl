{include file=header.tpl}
<!-- START: 	help_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
        </div>
        <p />
        <table class='accts_section_header'>
            <tr>
                <td class='accts_section_header'>
                    {$_HELP_WELCOME}
                </td>
            </tr>
        </table>
        <p />
        <table style='width: 100%;'>
        <tr>
            <td>
                <table class='groups_section_table'>
                    <tr class='acct_main_item_header'>
                        <td>{$_HELP_TOPICS}</td>
                    </tr>
                    <tr style='background: #e6e6e6;'>
                        <td>
                            <ul>
                                {section name=topics loop=$TOPICS_LIST}
                                    <li><a href='#{$TOPICS_LIST[$smarty.section.topics.index].topic_id}'>{$TOPICS_LIST[$smarty.section.topics.index].name}</a>
                                {sectionelse}
                                    <li>{$_HELP_NO_TOPICS}
                                {/section}
                            </ul>
                        </td>
                    </tr>
                </table>
                <p />&nbsp;<p />
                <table class='groups_section_table'>
                    <tr class='acct_main_item_header'>
                        <td>{$_HELP_SUB_SECTIONS}</td>
                    </tr>
                    <tr style='background: #e6e6e6;'>
                        <td>
                            <ul>
                                {section name=subsections loop=$SUB_SECTIONS}
                                    <li><a href='index.php?extension=Help&amp;action=show_help_topic&amp;topic_id={$SUB_SECTIONS[$smarty.section.subsections.index].topic_id}'>{$SUB_SECTIONS[$smarty.section.subsections.index].name}</a>
                                {sectionelse}
                                    <li>{$_HELP_BOTTOM}
                                {/section}
                            </ul>
                        </td>
                    </tr>
                </table>
                <p />&nbsp;<p />

                {if $TOPICS_LIST}
                    <table class='groups_section_table'>
                        <tr class='acct_main_item_header'>
                            <td>{$_HELP_CONTENT}</td>
                        </tr>
                            {section name=topics loop=$TOPICS_LIST}
                                <tr style='background: #e6e6e6;'>
                                    <td>
                                        <a name='{$TOPICS_LIST[$smarty.section.topics.index].topic_id}'></a>
                                        <table style='width: 100%;'>
                                            <tr>
                                                <td style='vertical-align: top; width: 50%;'>
                                                    {$TOPICS_LIST[$smarty.section.topics.index].name}
                                                </td>
                                                <td style='vertical-align: top; width: 50%; background: #e6e6e6;'>
                                                    {$TOPICS_LIST[$smarty.section.topics.index].content}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            {/section}
                    </table>
                {/if}
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>
<!-- END: 	help_main.tpl -->
{include file=footer.tpl}
