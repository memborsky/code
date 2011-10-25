{include file='header.tpl'}
<!-- START:	bookmarks_main.tpl -->
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style='text-align: center;'>
            <!-- Put extension specific links here -->
            <a href='index.php?extension=Filesystem&amp;action=show_files'>{$_MYFILES_SHOW_FILES}</a> ::
            {$_BKMKS_VIEW}
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
            </div>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr class='myfiles_show_files_header'>
                <td style='width: 1%;'>
                        <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                </td>
                <td style='width: 30%;'>{$_BKMKS_NAME}</td>
                <td style='width: 30%;'>{$_LINK}</td>
                <td style='width: 40%;'>{$_DESCRIPTION}</td>
                </tr>
                {section name=bookmarks loop=$BOOKMARK_LIST}
                <tr class='myfiles_show_files_row'>
                <td>
                    <input type='checkbox' name='bookmark_id[]' value='{$BOOKMARK_LIST[$smarty.section.bookmarks.index][0]}' />
                </td>
                <td>
                    <!-- Contains the link Name -->
                <a href='index.php?extension=Filesystem&amp;action=show_files&amp;path={$BOOKMARK_LIST[$smarty.section.bookmarks.index][1]}'>{$BOOKMARK_LIST[$smarty.section.bookmarks.index][2]}</a>
                </td>
                <td>
                    <!-- Contains the link location -->
                    {$BOOKMARK_LIST[$smarty.section.bookmarks.index][1]}
                </td>
                <td>
                    <!-- Contains the link Description -->
                    {$BOOKMARK_LIST[$smarty.section.bookmarks.index][3]}
                </td>
                </tr>
                {/section}
                {if $BOOKMARK_LIST}
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class='bookmarks'>
                    {$_WITH_SELECTED} ...
                        <select name='action' onChange="document.bookmarks.submit()">
                            <option value='' />{$_CHOOSE_ACTION}
                            <option value='show_edit_bookmark' />{$_EDIT}
                            <option value='do_delete_bookmark' />{$_DELETE}
                        </select>
                    </td>
                </tr>
                {else}
                <tr>
                    <td>&nbsp;</td>
                    <td colspan='3'>{$_BKMKS_NO_BKMKS}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class='bookmarks'>
                    {$_WITH_SELECTED} ...
                        <select name='action' onChange="document.bookmarks.submit()">
                            <option value='' />:: {$_NO_ACTIONS} ::
                        </select>
                    </td>
                </tr>
                {/if}
                <!-- END: Print out all bookmark information -->
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END:	bookmarks_main.tpl -->
{include file='footer.tpl'}
