<!-- START: 	myfiles_archive.tpl -->
{include file='header.tpl'}
<table class='main'>
<tr>
    <!-- START: Form to run actions on files -->
    <form name='filesform' method='post' action='index.php'>
    <input type='hidden' name='extension' value='Filesystem'>
    <input type='hidden' name='action' value='do_action_archive'>
    <input type='hidden' name='base_dir' value='{$ROOT}'>

    <td width='80%' valign='top'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
            <a href='index.php?extension=Filesystem&amp;action=show_files'>{$_MYFILES_SHOW_FILES}</a> ::
            <a href='index.php?extension=Filesystem&amp;action=show_manage_bookmarks'>{$_MYFILES_MANAGE_BOOKMARKS}</a>
        </div>
        <p />
        <table width='100%'>
            <tr>
                <td class='myfiles_section_header'>
                    {$_MYFILES_ARCHIVE_WELCOME}
                </td>
            </tr>
        </table>
        &nbsp;<br />
        {$_MYFILES_ARCHIVE_SELECT_TYPE}
        &nbsp;<p />
        <table class='myfiles_show_files_table' cellspacing='0' align='center' cellpadding='10' bgcolor='#cccccc'>
            <tr>
                <td width='30%'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='radio' name='archive_type' value='zip' checked='true' onClick='javascript:archive_desc("zip")' /> Zip
                </td>
                <td rowspan='4' valign='top'>
                <div id='zip' style='display: block;'>
                    {$_MYFILES_ARCHIVE_ZIP_CATEGORY_DESC}
                </div>
                <div id='gzip' style='display: none;'>
                    {$_MYFILES_ARCHIVE_GZIP_CATEGORY_DESC}
                </div>
                <div id='bzip2' style='display: none;'>
                    {$_MYFILES_ARCHIVE_BZIP2_CATEGORY_DESC}
                </div>
                <div id='tar' style='display: none;'>
                    {$_MYFILES_ARCHIVE_TAR_CATEGORY_DESC}
                </div>
                </td>
            </tr>
            <tr>
                <td width='30%'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='radio' name='archive_type' value='gzip' onClick='javascript:archive_desc("gzip")' /> Gzip
                </td>
            </tr>
            <tr>
                <td width='30%'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='radio' name='archive_type' value='bzip2' onClick='javascript:archive_desc("bzip2")' /> Bzip2
                </td>
            </tr>
            <tr>
                <td width='30%'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='radio' name='archive_type' value='tar' onClick='javascript:archive_desc("tar")' /> Tar
                </td>
            </tr>
            <tr>
                <td width='30%' colspan='2'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='checkbox' name='remember_type' /> {$_MYFILES_ARCHIVE_REMEMBER_TYPE}
                </td>
            </tr>
        </table>
        <p />&nbsp;<p />
        {$_MYFILES_ARCHIVE_ITEMS}
        <br />
        <table class='myfiles_show_files_table' cellspacing='0' align='center' bgcolor='#cccccc'>
            <tr>
                <td>
                    <ul>
                    {section name=file_list loop=$FILE_LIST}
                        <input type='hidden' name='item[]' value='{$FILE_LIST[$smarty.section.file_list.index]}'>
                        <li>{$FILE_LIST[$smarty.section.file_list.index]}
                    {/section}
                    </ul>
                </td>
            </tr>
        </table>
        <p />&nbsp;<p />
        {$_MYFILES_ARCHIVE_NAME}
        <br />
        <table class='myfiles_show_files_table' cellspacing='10' align='center' bgcolor='#cccccc'>
            <tr>
                <td align='center'>
                    <input type='text' name='archive_name' maxlength='255' size='100' class='input_txt'>
                </td>
            </tr>
        </table>
        <p />&nbsp;<p />
        <p align='center' /><input type='submit' name='submit' value='{$_MYFILES_ARCHIVE_CREATE}' class='input_btn'>
        </form>
    </td>
</tr>
</table>
<!-- END: 	myfiles_archive.tpl -->
{include file='footer.tpl'}
