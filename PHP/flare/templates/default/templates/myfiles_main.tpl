<!-- START: 	myfiles_main.tpl -->
{include file='header.tpl'}
<table class='main'>
<tr>
    <td class='myfiles_list_main'>
        <!-- START: Form to run actions on files -->
        <form name='filesform' method='post' action='index.php'>
            <div>
                <input type='hidden' name='extension' value='Filesystem'>
                <input type='hidden' name='action' value='show_files'>
                <input type='hidden' name='base_dir' value='{$ROOT}'>
                <input type='hidden' name='dir' value=''>
                <input type='hidden' name='rename' value=''>
            </div>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            {$_MYFILES_SHOW_FILES} ::
            <a href='#' onClick='javascript:window.open("index.php?extension=Filesystem&amp;action=show_upload_files&amp;path={$ROOT}","FlareUpload","width=600,height=400,scrollbars=yes,resizable=no")'>{$_MYFILES_UPLOAD_FILES}</a> ::
            <a href='index.php?extension=Filesystem&amp;action=show_manage_bookmarks'>{$_MYFILES_MANAGE_BOOKMARKS}</a>
        </div>
        <p />
        <table width='100%'>
            <tr>
                <td class='myfiles_section_header'>
                    {$_MYFILES_WELCOME}
                </td>
            </tr>
        </table>
        <p />
        <table width='100%'>
            <tr>
                <td style='width: 50%'>
                    <a href='#' onClick='javascript:history.back()'><img src='images/back.png' alt='Back' title='Back' /></a>

                {if $FILE_MODE == "show"}
                    <a href='index.php?extension=Filesystem&amp;action=show_files&amp;path={$ROOT}'><img src='images/reload.png' alt='Refresh Folder' title='Refresh Folder' /></a>
                {elseif $FILE_MODE == "copy"}
                    <a href='index.php?extension=Filesystem&amp;action=do_action_copy&amp;path={$ROOT}'><img src='images/reload.png' alt='Refresh Folder' title='Refresh Folder' /></a>
                {elseif $FILE_MODE == "cut"}
                    <a href='index.php?extension=Filesystem&amp;action=do_action_cut&amp;path={$ROOT}'><img src='images/reload.png' alt='Refresh Folder' title='Refresh Folder' /></a>
                {/if}

                {if $FILE_MODE == "show"}
                    <a href='index.php?extension=Filesystem&amp;action=show_files'><img src='images/home.png' alt='{$_MYFILES_HOME}' title='{$_MYFILES_HOME}' /></a>
                {elseif $FILE_MODE == "copy"}
                    <a href='index.php?extension=Filesystem&amp;action=do_action_copy'><img src='images/home.png' alt='{$_MYFILES_HOME}' title='{$_MYFILES_HOME}' /></a>
                {elseif $FILE_MODE == "cut"}
                    <a href='index.php?extension=Filesystem&amp;action=do_action_cut'><img src='images/home.png' alt='{$_MYFILES_HOME}' title='{$_MYFILES_HOME}' /></a>
                {/if}

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <input type='image' src='images/cut.png' alt='{$_MYFILES_MOVE_SELECTED}' title='{$_MYFILES_MOVE_SELECTED}' onClick='javascript:actions("do_action_cut")' />
                    <input type='image' src='images/copy.png' alt='{$_MYFILES_COPY_SELECTED}' title='{$_MYFILES_COPY_SELECTED}' onClick='javascript:actions("do_action_copy")' />
                    {if $FILE_MODE == "show"}
                    <img src='images/paste_gray.png' alt='{$MYFILES_PASTE_SELECTED}' title='{$_MYFILES_PASTE_SELECTED}' />
                    {else}
                    <input type='image' src='images/paste.png' alt='{$_MYFILES_PASTE_SELECTED}' title='{$_MYFILES_PASTE_SELECTED}' onClick='javascript:actions("do_action_paste")' />
                    {/if}

                    <input type='image' src='images/rename.png' alt='{$_MYFILES_RENAME_SELECTED}' title='{$_MYFILES_RENAME_SELECTED}' onClick='javascript:actions("do_action_rename")' />

                    <input 	type='image' src='images/delete.png' alt='{$_MYFILES_DELETE_SELECTED}' title='{$_MYFILES_DELETE_SELECTED}' onClick='javascript:actions("do_action_delete")' />

                    <input type='image' src='images/folder_new.png' onClick='javascript:actions("do_action_new_dir")' alt='{$_MYFILES_NEW_DIR}' title='{$_MYFILES_NEW_DIR}'>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <input type='image' src='images/bookmark.png' alt='{$_MYFILES_BKMK_SELECTED}' title='{$_MYFILES_BKMK_SELECTED}' onClick='javascript:actions("do_action_bookmark")' />

                    <input type='image' src='images/mimetypes/zip.png' alt='{$_MYFILES_ZIP_SELECTED}' title='{$_MYFILES_ZIP_SELECTED}' onClick='javascript:actions("show_action_archive")' />

                    <input type='image' src='images/extract.png' alt='{$_MYFILES_EXTRACT_SELECTED}' title='{$_MYFILES_EXTRACT_SELECTED}' onClick='javascript:actions("do_extract_archive")' />

                    <input type='image' src='images/browser.png' onClick='javascript:window.open("{$MYWEBSITE}")' alt='{$_MYFILES_BROWSER}' title='{$_MYFILES_BROWSER}'>
                </td>
                <td class='bookmarks' style='width: 50%'>
                    {if $BOOKMARK_LIST != ""}
                        <select name='bookmark' onChange='window.location=document.filesform.bookmark.options[document.filesform.bookmark.selectedIndex].value'>
                            <option value='' />:: {$_MYFILES_BOOKMARKS} ::
                        {section name=bookmarks loop=$BOOKMARK_LIST}
                            <option value='index.php?extension=Filesystem&amp;action=show_files&amp;path={$BOOKMARK_LIST[$smarty.section.bookmarks.index][0]}'>{$BOOKMARK_LIST[$smarty.section.bookmarks.index][1]}</option>
                        {/section}
                        </select>
                    {/if}
                </td>
            </tr>
        </table>
        <div><br></div>
        <table class='myfiles_show_files_table'>
            <tr class='myfiles_show_files_header'>
                <td>
                    <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                </td>
                <td colspan='2'>
                    {$_FILE}
                </td>
                <td class='myfiles_size'>
                    {$_SIZE}
                </td>
                <td class='myfiles_date_modified'>
                    {$_DATE_MODIFIED}
                </td>
            </tr>

            {if !$DIRECTORIES && !$FILES}
            <tr><td>{$_MYFILES_FOLDER_EMPTY}</td></tr>
            {/if}

            <!-- START: Print out all DIRECTORY information -->
            {section name=dirs loop=$DIRECTORIES}
            <tr class='myfiles_show_files_row' onMouseOver="this.style.backgroundColor='#006699'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#e6e6e6';">
            <td style='width: 1%;'>
                {if $DIRECTORIES[$smarty.section.dirs.index].disp != "../"}
                <input type='checkbox' name='item[]' value='{$DIRECTORIES[$smarty.section.dirs.index].file}' />
                {/if}
            </td>
            <td style='width: 16px;'>
                <img src='images/folder.gif' width='16' height='16' alt='{$DIRECTORIES[$smarty.section.dirs.index].file}' title='{$DIRECTORIES[$smarty.section.dirs.index].file}'>
            </td>
            <td style='text-align: left;'>
                {if $FILE_MODE == "cut"}
                <a href='index.php?extension=Filesystem&amp;action=do_action_cut&amp;path={$DIRECTORIES[$smarty.section.dirs.index].root}&amp;file={$DIRECTORIES[$smarty.section.dirs.index].file}'>{$DIRECTORIES[$smarty.section.dirs.index].disp|truncate:50}</a>
                {elseif $FILE_MODE == "copy"}
                <a href='index.php?extension=Filesystem&amp;action=do_action_copy&amp;path={$DIRECTORIES[$smarty.section.dirs.index].root}&amp;file={$DIRECTORIES[$smarty.section.dirs.index].file}'>{$DIRECTORIES[$smarty.section.dirs.index].disp|truncate:50}</a>
                {else}
                <a href='index.php?extension=Filesystem&amp;action=show_files&amp;path={$DIRECTORIES[$smarty.section.dirs.index].root}&amp;file={$DIRECTORIES[$smarty.section.dirs.index].file}'>{$DIRECTORIES[$smarty.section.dirs.index].disp|truncate:50}</a>
                {/if}
            </td>
            <td style='width: 20%; text-align: left;'>-</td>
            <td style='width: 30%; text-align: left;'>{$DIRECTORIES[$smarty.section.dirs.index].date}</td>
            </tr>
            {sectionelse}
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            {/section}
            <!-- END: Print out all DIRECTORY information -->

            <!-- START: Print out all FILE information -->
            {section name=files loop=$FILES}
            <tr class='myfiles_show_files_row' onMouseOver="this.style.backgroundColor='#006699'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#e6e6e6';">
            <td style='width: 1%;'>
                <input type='checkbox' name='item[]' value='{$FILES[$smarty.section.files.index].file}' />
            </td>
            <td style='width: 16px;'>
                <img src='{$FILES[$smarty.section.files.index].icon}' width='16' height='16' alt='{$FILES[$smarty.section.files.index].file|truncate:50}' title='{$FILES[$smarty.section.files.index].file|truncate:50}' />
            </td>
            <td style='text-align: left;'>
                <a href='index.php?extension=Filesystem&amp;action=do_download_file&amp;path={$ROOT}&amp;file={$FILES[$smarty.section.files.index].file}'>{$FILES[$smarty.section.files.index].file|truncate:50}</a>
            </td>
            <td style='width: 20%; text-align: left;'>{$FILES[$smarty.section.files.index].size}</td>
            <td style='width: 30%; text-align: left;'>{$FILES[$smarty.section.files.index].date}</td>
            </tr>
            {sectionelse}
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            {/section}
            <!-- END: Print out all FILE information -->
        </table>
        </form>
        <p />
        <!-- END: Form to run actions on files -->
        <table width='100%'>
            <tr>
                <td style='width: 50%; vertical-align: top;'>
                    {$_MYFILES_FOLDER_INFO} : 
                    {if $ROOT}
                        {$ROOT}
                    {else}
                        {$_MYFILES_HOME}
                    {/if}
                </td>
                <td style='text-align: right; width: 50%;'>
                    {$TOTAL_ITEMS} {$_ITEMS} - {$TOTAL_FILES} {$_FILES} ( {$TOTAL_SIZE} {$_TOTAL} ) - {$TOTAL_DIRS} {$_FOLDERS}

                    {if $QUOTA_TOTAL}
                    <p />
                    You are using <span style='font-weight: bold;'>{$QUOTA_USED}</span> 
                    of your total <span style='font-weight: bold;'>{$QUOTA_TOTAL}</span>
                    {/if}
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
<!-- END: 	myfiles_main.tpl -->
{include file='footer.tpl'}
