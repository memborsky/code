<!-- START: 	myfiles_main.tpl -->
{include file='header_empty.tpl'}
<table class='main'>
<tr>
    <td style='width: 80%; vertical-align: top;'>
        <div style="text-align:center;">
            <!-- Put extension specific links here -->
        </div>
        <p />
        <table style='width: 100%;'>
            <tr>
                <td valign='top'>
                    <table style='width: 100%;'>
                        <tr>
                            <td class='myfiles_section_header'>
                                {$_MYFILES_UPLOAD_WELCOME}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {$_MYFILES_NUM_UL_FIELDS}
                            </td>
                            <td>
                            <form name='upload_count' action='index.php'>
                                <div>
                                    <input type='hidden' name='extension' value='Filesystem'>
                                    <input type='hidden' name='action' value='show_upload_files'>
                                    <input type='hidden' name='path' value='{$ROOT}'>
                                    <select name='box_count' onChange="document.upload_count.submit()">
                                        <option value='1' />{$_MYFILES_CHG_UL_CNT}
                                        <option value='5' />5
                                        <option value='10' />10
                                        <option value='15' />15
                                        <option value='20' />20
                                    </select>
                                </div>
                            </form>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <form name='upload' enctype="multipart/form-data" action='index.php' method='post'>
            <div>
                <input type='hidden' name='extension' value='Filesystem'>
                <input type='hidden' name='action' value='do_upload_files'>
                <input type='hidden' name='path' value='{$ROOT}'>
            </div>
            <table width='100%'>
                <!-- loop through for each input box -->
                {section name=input loop=$BOX_COUNT}
                <tr class='myfiles_upload_row'>
                    <td>
                        {$smarty.section.input.index_next}.
                    </td>
                    <td>
                        <input type='file' name='file_upload_array[]' size='40' class='input_btn'>
                    </td>
                </tr>
                {sectionelse}
                <tr class='myfiles_upload_row'>
                    <td>
                        1.
                    </td>
                    <td valign='top'>
                        <input type='file' name='file_upload_array[]' size='40' class='input_btn'>
                    </td>
                </tr>
                {/section}
            </table>
            <p />
            <table style='width: 100%;'>
                <tr>
                    <td align='right'>
                        <input type="button" value='{$_MYFILES_UPLOAD_FILES}' onClick='javascript:submit_upload()' class='input_btn'/>
                    </td>
                    <td align='left'>
                        <input type='reset' value='{$_MYFILES_RESET_FIELDS}' class='input_btn'/>
                    </td>
                </tr>
            </table>
        </form>
    </td>
</tr>
</table>
<!-- END: 	myfiles_main.tpl -->
{include file='footer.tpl'}
