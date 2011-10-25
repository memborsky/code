{include file='header.tpl'}
<!-- START:	bookmarks_edit.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align:center;'>
            <!-- Put extension specific links here -->
            <a href='index.php?extension=Filesystem&amp;action=show_manage_bookmarks'>{$_BKMKS_VIEW}</a>
        </div>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td class='bkmks_section_header'>
                    {$_BKMKS_WELCOME}
                </td>
            </tr>
        </table>
        <p />
        <form method='post' action='index.php' name='bookmarks'>
            <div>
                <input type='hidden' name='extension' value='Filesystem'>
                <input type='hidden' name='action' value='do_save_edited_bookmark'>
            </div>
            <table class='bkmks_edit_table' cellpadding='5'>
                <tr>
                    <td class='bkmks_edit_col_one'>{$_NAME}</td>
                    <td class='bkmks_edit_col_one'>{$_LOCATION}</td>
                    <td class='bkmks_edit_col_one'>{$_DESCRIPTION}</td>
                </tr>
                {section name=bookmarks loop=$BOOKMARK_LIST}
                <tr>
                    <td class='bkmks_edit_col_two'>
                        <input type='hidden' name='bookmark_id[]' value='{$BOOKMARK_LIST[$smarty.section.bookmarks.index][0]}'>
                        <input type='text' name='bookmark_name[]' value='{$BOOKMARK_LIST[$smarty.section.bookmarks.index][1]}' maxlength='255' class='input_txt' style='width: 100%;'/>
                    </td>
                    <td class='bkmks_edit_col_two'>
                        <input type='text' name='bookmark_link[]' size='50' value='{$BOOKMARK_LIST[$smarty.section.bookmarks.index][2]}' disabled='disabled' class='input_txt' style='width: 100%;'/>
                    </td>
                    <td class='bkmks_edit_col_two'>
                        <input type='text' name='bookmark_desc[]' size='50' value='{$BOOKMARK_LIST[$smarty.section.bookmarks.index][3]}' maxlength='255' class='input_txt' style='width: 100%;'/>
                    </td>
                </tr>
                {/section}
            </table>
            <table style='width: 100%;'>
                <tr>
                    <td style='text-align: right;'>
                        <input type='submit' name='submit' value='{$_SAVE_CHGS}' class='input_btn'>
                    </td>
                    <td style='text-align: left;'>
                        <input type='reset' value='{$_UNDO_CHGS}' class='input_btn'>
                    </td>
                </tr>
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END:	bookmarks_edit.tpl -->
{include file='footer.tpl'}
