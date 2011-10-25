<?php
/**
* @package Language
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/

/**
* Copyright (C) 2004-2005 Tim Rupp
* Please direct all questions and comments to TARupp01@indianatech.net
*
* This program is free software; you can redistribute it and/or modify it under the terms of
* the GNU General Public License as published by the Free Software Foundation; either version
* 2 of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along with this program;
* if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston,
* MA 02111-1307, USA.
*/

/**
* Prevent direct access to the file
*/
defined( '_FLARE_INC' ) or die( "You can't access this file directly." );

/* MyFiles page specific texts */
define ("_MYFILES_WELCOME", "My Files on F.U.E.L");
define ("_MYFILES_UPLOAD_WELCOME", "Upload files to F.U.E.L");
define ("_MYFILES_DELETE_SUCCESS", "Selected item(s) removed successfully");
define ("_MYFILES_DELETE_FAILURE", "You must select at least one item to remove!");
define ("_MYFILES_DELETE_RETURN_LINK", "<a href='index.php?extension=Filesystem'>Back to My files</a>");
define ("_MYFILES_DIR_NO_EXIST", "The specified item does not exist!");
define ("_MYFILES_DIR_NO_EXIST_RETURN_LINK", "<a href='index.php?extension=Filesystem&amp;action=show_files'>Back to My Files</a>");
define ("_MYFILES_SHOW_FILES", "Show My Files");
define ("_MYFILES_UPLOAD_FILES", "Upload File(s)");
define ("_MYFILES_RESET_FIELDS", "Reset Fields");
define ("_MYFILES_BOOKMARKS", "Bookmarks");
define ("_MYFILES_MANAGE_BOOKMARKS", "Manage Bookmarks");
define ("_MYFILES_MOVE_SELECTED", "Move Selected To...");
define ("_MYFILES_COPY_SELECTED", "Copy Selected To...");
define ("_MYFILES_PASTE_SELECTED", "Paste Selected Here...");
define ("_MYFILES_RENAME_SELECTED", "Rename the First Selected Item");
define ("_MYFILES_DELETE_SELECTED", "Delete Selected");
define ("_MYFILES_BKMK_SELECTED", "Bookmark Selected");
define ("_MYFILES_ZIP_SELECTED", "Create ZIP Archive With Selected");
define ("_MYFILES_EXTRACT_SELECTED", "Extract Selected Archives");
define ("_MYFILES_FOLDER_EMPTY", "Folder is empty");
define ("_MYFILES_FOLDER_INFO", "Folder Information");
define ("_MYFILES_NUM_UL_FIELDS", "Number of Upload Fields");
define ("_MYFILES_CHG_UL_CNT", "Change Upload Count");
define ("_MYFILES_BROWSER", "View My Webpage");
define ("_MYFILES_HOME", "Home");
define ("_MYFILES_NEW_DIR", "Create New Folder");
define ("_MYFILES_NEW_DIR_SUCCESS", "Successfully created new directory");
define ("_MYFILES_NEW_DIR_FAILURE", "Failed to create new directory");
define ("_MYFILES_NEW_DIR_RETURN_LINK", "<a href='index.php?extension=Filesystem&amp;action=show_files'>Return to My Files</a>");
define ("_MYFILES_UPLOAD_SUCCESS", "Successfully uploaded all files");
define ("_MYFILES_UPLOAD_FAILURE", "Failed to upload file(s)");
define ("_MYFILES_UPLOAD_RETURN_LINK", "<a href='#' onClick='javascript:refresh_list();return false;'>Close Window</a>");
define ("_MYFILES_NO_PERM", "Permissions Denied");
define ("_MYFILES_NO_WRITE_PERM", "You do not have permission to write to this directory. Your files were not uploaded.");
define ("_MYFILES_COPY_FAILURE_NONE", "You must select at least one item before you can copy it!");
define ("_MYFILES_COPY_FAILURE_NONE_RETURN_LINK", "<a href='index.php?extension=Filesystem&amp;action=show_files'>Return to My Files</a>");
define ("_MYFILES_CUT_FAILURE_NONE", "You must select at least one item before you can cut it!");
define ("_MYFILES_CUT_FAILURE_NONE_RETURN_LINK", "<a href='index.php?extension=Filesystem&amp;action=show_files'>Return to My Files</a>");
define ("_MYFILES_ARCHIVE_SELECT_TYPE", "Select the type of archive you want to create...");
define ("_MYFILES_ARCHIVE_REMEMBER_TYPE", "Use this type of archive for the remainder of the time I'm logged in.");
define ("_MYFILES_ARCHIVE_ITEMS", "... and the following items will be placed in the archive.");
define ("_MYFILES_ARCHIVE_NAME", "Now just give your archive a name.");
define ("_MYFILES_ARCHIVE_WELCOME", "Create an Archive");
define ("_MYFILES_ARCHIVE_CREATE", "Create Archive");
define ("_MYFILES_ARCHIVE_NONE", "You must select at least one item to archive!");
define ("_MYFILES_ARCHIVE_NONE_RETURN_LINK", "<a href='index.php?extension=Filesystem&amp;action=show_files'>Return to My Files</a>");
define ("_MYFILES_ARCHIVE_ZIP_CATEGORY_DESC", "About:<p />A compressed (archive) file. Can contain one or many files as well as a directory structure. "
    . "On the Internet, large graphics and programs are usually compressed into ZIP files and then made available for download. After you download this file you "
    . "need to use a decompression software program to 'unzip' the file.");
define ("_MYFILES_ARCHIVE_GZIP_CATEGORY_DESC", "About:<p />Gzip does not archive files, it only compresses them, which is why it is often seen in conjunction with<br />"
    . "a separate archiving tool (most popularly tar). The usual file extension for gzipped files is .gz. Unix software is often distributed as files ending<br />"
    . "with .tar.gz or .tgz, called tarballs. They are files first packaged with tar and then compressed with gzip."
    . "<p />As mentioned in the above description, this option will create a gzip compressed tar archive.");
define ("_MYFILES_ARCHIVE_BZIP2_CATEGORY_DESC", "About:<p />bzip2 compresses most files more effectively than more traditional gzip or ZIP, but is slower.<br />"
    . "bzip2 gets within ten to fifteen percent of the 'best' class of compression algorithms currently known, although it is roughly twice as fast "
    . "at compression and six times faster at decompression.");
define ("_MYFILES_ARCHIVE_TAR_CATEGORY_DESC", "About<p />It is used widely to archive and unarchive files, which means to accumulate a large collection of files into<br />"
    . "a single archive file (packer), while preserving file system information such as user and group permissions, dates, and directory structures. If<br />"
    . "one then wants to compress the archive, one uses a separate program that is specialised in compression. tar is most commonly used in tandem with<br />"
    . "an external compression utility such as gzip, bzip2 or, formerly, compress, since it has no built in data compression facilities. These compression<br />"
    . "utilities generally only compress a single file, hence the pairing with tar, which can produce a single file from many files."
    . "<p />It should be noted that this option will NOT compress your archive. It will only archive the selected items and place it in a .tar file which is<br />"
    . "basically a glorified directory that preserves the attributes of files mentioned about.");
define ("_MYFILES_ADM_SETTINGS_SAVED", "Settings for the Filesystem Extension have been saved");
define ("_MYFILES_ADM_SETTINGS_SAVED_RETURN_LINK", "<a href='admin.php?extension=Filesystem&amp;action=show_settings'>Return to Filesystem Admin</a>");
define ("_MYFILES_UPLOAD_OVER_QUOTA_FAILURE", "The file could not be uploaded because you do not have enough space available.");

/* Bookmarks page specific texts */
define ("_BKMKS_WELCOME", "Bookmarks");
define ("_BKMKS_MADE_SUCCESS", "Bookmark(s) created successfully");
define ("_BKMKS_MADE_FAILURE_NO_DIR", "You must select one or more <b>folders</b> to create a bookmark from!");
define ("_BKMKS_MADE_FAILURE", "Some of the selected items were not bookmarked.");
define ("_BKMKS_MADE_RETURN_LINK", "<a href='index.php?extension=Filesystem'>Return to My Files</a>");
define ("_BKMKS_DELETE_SUCCESS", "Selected bookmarks were deleted successfully");
define ("_BKMKS_DELETE_FAILURE", "Selected bookmarks were <b>not</b> deleted successfully");
define ("_BKMKS_DELETE_RETURN_LINK", "<a href='index.php?extension=Filesystem&action=show_manage_bookmarks'>Return to Manage Bookmarks</a>");
define ("_BKMKS_EDIT_SUCCESS", "Selected bookmarks were updated successfully");
define ("_BKMKS_EDIT_FAILURE", "Selected bookmarks were <b>not</b> updated successfully");
define ("_BKMKS_EDIT_RETURN_LINK", "<a href='index.php?extension=Filesystem&action=show_manage_bookmarks'>Return to Manage Bookmarks</a>");
define ("_BKMKS_NONE_SELECTED", "You must have selected at least one bookmark to perform this action");
define ("_BKMKS_NONE_SELECTED_RETURN_LINK", "<a href='index.php?extension=Filesystem&action=show_manage_bookmarks'>Return to Manage Bookmarks</a>");
define ("_BKMKS_VIEW", "View Bookmark(s)");
define ("_BKMKS_NAME", "Bookmark Name");
define ("_BKMKS_NO_BKMKS", "No Bookmarks Found");

?>
