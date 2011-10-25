{include file='header.tpl'}
<!-- START: 	filesystem_permissions.tpl -->
<table class='main'>
<tr>
    <td class='myfiles_list_main'>
        <!-- START: Form to run actions on files -->
        <form name='filesform' method='post' action='admin.php'>
            <div>
                <input type='hidden' name='extension' value='Filesystem'>
                <input type='hidden' name='action' value='show_permissions'>
                <input type='hidden' name='base_dir' value='{$ROOT}'>
                <input type='hidden' name='dir' value=''>
                <input type='hidden' name='rename' value=''>
            </div>
            <div style="text-align:center;">
                <!-- Put extension specific links here -->
                <a href='admin.php?extension=Filesystem&amp;action=show_settings'>{$_SETTINGS}</a>
                :: <a href='index.php?extension=Filesystem&amp;action=show_files'>View Filesystem</a>
                :: Change File/Folder Permissions
            </div>
            <p />&nbsp;<p />
            <table width='100%'>
                <tr>
                    <td class='bookmarks' style='width: 50%'>
                        <select name='permission_action' onChange='javascript:actions(document.filesform.permission_action.options[document.filesform.permission_action.selectedIndex].value)'>
                            <option value='' />:: With Selected ::
                            <option value='do_update_permissions'>Update Permissions</option>
                            <option value='show_change_owner'>Change File/Folder Ownership</option>
                        </select>
                    </td>
                </tr>
            </table>
            <table class='myfiles_show_files_table'>
                <tr class='myfiles_show_files_header'>
                    <td>
                        <input type='checkbox' name='check_uncheck' onChange='javascript:checkall()' />
                    </td>
                    <td colspan='2'>
                        {$_FILE}
                    </td>
                    <td class='myfiles_adm_size'>
                        {$_SIZE}
                    </td>
                    <td class='myfiles_adm_date_modified'>
                        {$_DATE_MODIFIED}
                    </td>
                    <td class='myfiles_adm_date_modified'>
                        {$_OWNER}
                    </td>
                    <td class='myfiles_adm_date_modified'>
                        {$_GROUP}
                    </td>
                    <td class='myfiles_adm_permissions' colspan='3'>
                        Owner Permissions (_r_w_x_)
                    </td>
                    <td class='myfiles_adm_permissions' colspan='3'>
                        Group Permissions (_r_w_x_)
                    </td>
                    <td class='myfiles_adm_permissions' colspan='3'>
                        Others Permissions (_r_w_x_)
                    </td>
                </tr>

            {if !$DIRECTORIES && !$FILES}
            <tr><td>{$_MYFILES_FOLDER_EMPTY}</td></tr>
            {/if}

            <!-- START: Print out all DIRECTORY information -->
            {section name=dirs loop=$DIRECTORIES}
            <tr class='myfiles_show_files_row' onMouseOver="this.style.backgroundColor='#006699'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#e6e6e6';">
            <td style='width: 1%;'>
                <input type='hidden' name='item_id[]' value='{$DIRECTORIES[$smarty.section.dirs.index].id}' />
                <input type='hidden' name='file[{$DIRECTORIES[$smarty.section.dirs.index].id}]' value='{$DIRECTORIES[$smarty.section.dirs.index].file}' />
                <input type='checkbox' name='item[]' value='{$DIRECTORIES[$smarty.section.dirs.index].id}' />
            </td>
            <td style='width: 16px;'>
                <img src='images/folder.gif' width='16' height='16' alt='{$DIRECTORIES[$smarty.section.dirs.index].file}' title='{$DIRECTORIES[$smarty.section.dirs.index].file}'>
            </td>
            <td style='text-align: left;'>
                <a href='admin.php?extension=Filesystem&amp;action=show_permissions&amp;path={$DIRECTORIES[$smarty.section.dirs.index].root}&amp;file={$DIRECTORIES[$smarty.section.dirs.index].file}'>{$DIRECTORIES[$smarty.section.dirs.index].disp|truncate:40}</a>
            </td>
            <td style='width: 12%; text-align: left;'>-</td>
            <td style='width: 12%; text-align: left;'>{$DIRECTORIES[$smarty.section.dirs.index].date}</td>
            <td style='width: 12%; text-align: left;'>
                {$DIRECTORIES[$smarty.section.dirs.index].owner}
            </td>
            <td style='width: 12%; text-align: left;'>
                {$DIRECTORIES[$smarty.section.dirs.index].group}
            </td>
            <td style='width: 1; text-align: center; background: #ffc;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#ffc';">
                <input type='hidden' name='type[{$DIRECTORIES[$smarty.section.dirs.index].id}]' value='{$DIRECTORIES[$smarty.section.dirs.index].type}'>
                {if $DIRECTORIES[$smarty.section.dirs.index].o_read == '-'}
                    <input type='checkbox' name='o_read[{$DIRECTORIES[$smarty.section.dirs.index].id}]'>
                {else}
                    <input type='checkbox' name='o_read[{$DIRECTORIES[$smarty.section.dirs.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #ffc;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#ffc';">
                {if $DIRECTORIES[$smarty.section.dirs.index].o_write == '-'}
                    <input type='checkbox' name='o_write[{$DIRECTORIES[$smarty.section.dirs.index].id}]'>
                {else}
                    <input type='checkbox' name='o_write[{$DIRECTORIES[$smarty.section.dirs.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #ffc;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#ffc';">
                {if $DIRECTORIES[$smarty.section.dirs.index].o_exec == '-'}
                    <input type='checkbox' name='o_exec[{$DIRECTORIES[$smarty.section.dirs.index].id}]'>
                {else}
                    <input type='checkbox' name='o_exec[{$DIRECTORIES[$smarty.section.dirs.index].id}]' CHECKED>
                {/if}
            </td>

            <td style='width: 1; text-align: center; background: #6c9;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#6c9';">
                {if $DIRECTORIES[$smarty.section.dirs.index].g_read == '-'}
                    <input type='checkbox' name='g_read[{$DIRECTORIES[$smarty.section.dirs.index].id}]'>
                {else}
                    <input type='checkbox' name='g_read[{$DIRECTORIES[$smarty.section.dirs.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #6c9;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#6c9';">
                {if $DIRECTORIES[$smarty.section.dirs.index].g_write == '-'}
                    <input type='checkbox' name='g_write[{$DIRECTORIES[$smarty.section.dirs.index].id}]'>
                {else}
                    <input type='checkbox' name='g_write[{$DIRECTORIES[$smarty.section.dirs.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #6c9;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#6c9';">
                {if $DIRECTORIES[$smarty.section.dirs.index].g_exec == '-'}
                    <input type='checkbox' name='g_exec[{$DIRECTORIES[$smarty.section.dirs.index].id}]'>
                {else}
                    <input type='checkbox' name='g_exec[{$DIRECTORIES[$smarty.section.dirs.index].id}]' CHECKED>
                {/if}
            </td>

            <td style='width: 1; text-align: center; background: #099;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#099';">
                {if $DIRECTORIES[$smarty.section.dirs.index].e_read == '-'}
                    <input type='checkbox' name='e_read[{$DIRECTORIES[$smarty.section.dirs.index].id}]'>
                {else}
                    <input type='checkbox' name='e_read[{$DIRECTORIES[$smarty.section.dirs.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #099;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#099';">
                {if $DIRECTORIES[$smarty.section.dirs.index].e_write == '-'}
                    <input type='checkbox' name='e_write[{$DIRECTORIES[$smarty.section.dirs.index].id}]'>
                {else}
                    <input type='checkbox' name='e_write[{$DIRECTORIES[$smarty.section.dirs.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #099;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#099';">
                {if $DIRECTORIES[$smarty.section.dirs.index].e_exec == '-'}
                    <input type='checkbox' name='e_exec[{$DIRECTORIES[$smarty.section.dirs.index].id}]'>
                {else}
                    <input type='checkbox' name='e_exec[{$DIRECTORIES[$smarty.section.dirs.index].id}]' CHECKED>
                {/if}
            </td>
            </tr>
            {sectionelse}
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
                <input type='hidden' name='item_id[]' value='{$FILES[$smarty.section.files.index].id}' />
                <input type='hidden' name='file[{$FILES[$smarty.section.files.index].id}]' value='{$FILES[$smarty.section.files.index].file}' />
                <input type='checkbox' name='item[]' value='{$FILES[$smarty.section.files.index].id}' />
            </td>
            <td style='width: 16px;'>
                <img src='{$FILES[$smarty.section.files.index].icon}' width='16' height='16' alt='{$FILES[$smarty.section.files.index].file|truncate:50}' title='{$FILES[$smarty.section.files.index].file|truncate:50}' />
            </td>
            <td style='text-align: left;'>
                <a href='index.php?extension=Filesystem&amp;action=do_download_file&amp;path={$ROOT}&amp;file={$FILES[$smarty.section.files.index].file}'>{$FILES[$smarty.section.files.index].file|truncate:40}</a>
            </td>
            <td style='width: 12%; text-align: left;'>{$FILES[$smarty.section.files.index].size}</td>
            <td style='width: 12%; text-align: left;'>{$FILES[$smarty.section.files.index].date}</td>
            <td style='width: 12%; text-align: left;'>
                {$FILES[$smarty.section.files.index].owner}
            </td>
            <td style='width: 12%; text-align: left;'>
                {$FILES[$smarty.section.files.index].group}
            </td>
            <td style='width: 1; text-align: center; background: #ffc;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#ffc';">
                <input type='hidden' name='type[{$FILES[$smarty.section.files.index].id}]' value='{$FILES[$smarty.section.files.index].type}'>
                {if $FILES[$smarty.section.files.index].o_read == '-'}
                    <input type='checkbox' name='o_read[{$FILES[$smarty.section.files.index].id}]'>
                {else}
                    <input type='checkbox' name='o_read[{$FILES[$smarty.section.files.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #ffc;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#ffc';">
                {if $FILES[$smarty.section.files.index].o_write == '-'}
                    <input type='checkbox' name='o_write[{$FILES[$smarty.section.files.index].id}]'>
                {else}
                    <input type='checkbox' name='o_write[{$FILES[$smarty.section.files.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #ffc;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#ffc';">
                {if $FILES[$smarty.section.files.index].o_exec == '-'}
                    <input type='checkbox' name='o_exec[{$FILES[$smarty.section.files.index].id}]'>
                {else}
                    <input type='checkbox' name='o_exec[{$FILES[$smarty.section.files.index].id}]' CHECKED>
                {/if}
            </td>

            <td style='width: 1; text-align: center; background: #6c9;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#6c9';">
                {if $FILES[$smarty.section.files.index].g_read == '-'}
                    <input type='checkbox' name='g_read[{$FILES[$smarty.section.files.index].id}]'>
                {else}
                    <input type='checkbox' name='g_read[{$FILES[$smarty.section.files.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #6c9;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#6c9';">
                {if $FILES[$smarty.section.files.index].g_write == '-'}
                    <input type='checkbox' name='g_write[{$FILES[$smarty.section.files.index].id}]'>
                {else}
                    <input type='checkbox' name='g_write[{$FILES[$smarty.section.files.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #6c9;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#6c9';">
                {if $FILES[$smarty.section.files.index].g_exec == '-'}
                    <input type='checkbox' name='g_exec[{$FILES[$smarty.section.files.index].id}]'>
                {else}
                    <input type='checkbox' name='g_exec[{$FILES[$smarty.section.files.index].id}]' CHECKED>
                {/if}
            </td>

            <td style='width: 1; text-align: center; background: #099;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#099';">
                {if $FILES[$smarty.section.files.index].e_read == '-'}
                    <input type='checkbox' name='e_read[{$FILES[$smarty.section.files.index].id}]'>
                {else}
                    <input type='checkbox' name='e_read[{$FILES[$smarty.section.files.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #099;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#099';">
                {if $FILES[$smarty.section.files.index].e_write == '-'}
                    <input type='checkbox' name='e_write[{$FILES[$smarty.section.files.index].id}]'>
                {else}
                    <input type='checkbox' name='e_write[{$FILES[$smarty.section.files.index].id}]' CHECKED>
                {/if}
            </td>
            <td style='width: 1; text-align: center; background: #099;' onMouseOver="this.style.backgroundColor='#069'; this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#099';">
                {if $FILES[$smarty.section.files.index].e_exec == '-'}
                    <input type='checkbox' name='e_exec[{$FILES[$smarty.section.files.index].id}]'>
                {else}
                    <input type='checkbox' name='e_exec[{$FILES[$smarty.section.files.index].id}]' CHECKED>
                {/if}
            </td>		
            </tr>
            {sectionelse}
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
                <td style='width: 50%;'>
                    {$_MYFILES_FOLDER_INFO} : 
                    {if $ROOT}
                        {$ROOT}
                    {else}
                        {$_MYFILES_HOME}
                    {/if}
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
<!-- END: 	filesystem_permissions.tpl -->
{include file='footer.tpl'}
