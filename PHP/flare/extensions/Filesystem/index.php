<?php
/**
* @package Filesystem
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/

/**
* Copyright (C) 2004-2005 Indiana Tech Open Source Committee
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

/**
* Require the class that will perform all operations
*/
require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');
require_once(ABSPATH.'/extensions/Filesystem/lang/lang-'.$cfg['language'].'.php');

$page = new MyFiles();
$page->__set("db", $db);
$page->__set("tpl", $tpl);
$page->__set("log", $log);
$page->__set("cfg", $cfg);
$page->__set("ext", $ext);

switch($flare_action) {
    case "show_upload_files":
        $path = $page->normalize_dir(import_var('path', 'G'));

        /**
        * Check to see if we need to change the number of upload boxes to display
        */
        $box_count = import_var('box_count', 'G');

        if (!is_numeric($box_count))
            $box_count = 5;

        $page->show_upload_files($path, $box_count);
        break;
    case "do_upload_files":
        $root 		= import_var('home_dir', 'S');
        $path 		= import_var('path', 'P');

        $dest_dir 	= $page->normalize_dir($root . '/' . $path);
        $user_id	= import_var('user_id', 'S');

        /**
        * The group ID of the folder that we're putting the new folder in
        */
        @$group_id 	= $page->get_group_id($dest_dir);
        $group_id	= ($group_id) ? $group_id : '-';

        if($page->permission_check($dest_dir, $user_id, $group_id, "write")) {
            $page->do_upload_files($dest_dir, $user_id, $group_id);
        } else if (is_admin($user_id, $page->ext->__get('extension_id'))){
            /**
            * We need to get the user_id of the folder instead of
            * assuming the one from the session because it is possible
            * that the admin could be deleting a file from another
            * users directory
            */
            $user_id = $page->get_user_id($dest_dir);

            $page->do_upload_files($dest_dir, $user_id, $group_id);
        } else {
            $page->tpl->assign("_MESSAGE", _MYFILES_NO_WRITE_PERM);

            $page->tpl->display("actions_done.tpl");
        }
        break;
    case "do_download_file":
        $root 		= import_var('home_dir', 'S');
        $user_id	= import_var('user_id', 'S');

        $path = $page->normalize_dir($page->strip_bad_navigation(import_var('path', 'G')));
        $file = $page->strip_bad_navigation(import_var('file', 'G'));

        /**
        * Normalize the file to download
        */
        $item 		= $page->normalize_file($root . '/' . $path . '/' . $file);

        /**
        * Normalize the directory the file is in
        */
        $item_dir	= $page->normalize_dir($root . '/' . $path . '/');

        /**
        * We dont need to check if the item is a file or directory because you can
        * only download files. Therefore we search based on the normalized file we made
        */
        $group_id 	= $page->get_group_id($item);
        $group_id	= ($group_id) ? $group_id : '-';

        /**
        * To download, the user or group must have read access
        */
        if($page->permission_check($item_dir, $user_id, $group_id, "read")) {
            $page->do_download_file($root . $path, $file);
        } else if (is_admin($user_id, $page->ext->__get('extension_id'))){
            $page->do_download_file($root . $path, $file);
        } else {
            $page->tpl->assign("_MESSAGE", _MYFILES_NO_PERM);

            $page->tpl->display("actions_done.tpl");
        }
        break;
    case "show_manage_bookmarks":
        /**
        * Needed for selecting which bookmarks are returned
        */
        $user_id = import_var('user_id', 'S');

        $page->show_manage_bookmarks($user_id);
        break;
    case "show_edit_bookmark":
        $user_id = import_var('user_id', 'S');
        $bookmarks = import_var('bookmark_id','P');

        $page->show_edit_bookmark($bookmarks, $user_id);
        break;
    case "do_save_edited_bookmark":
        /**
        * Store the user_id locally to make writing code easier
        */
        $user_id 	= import_var('user_id', 'S');

        /**
        * Likewise, store all the data sent to us in local
        * variables to make writing the remaining code easier
        */
        $bookmark_id	= import_var('bookmark_id', 'P'); 	// type: array
        $bookmark_link 	= import_var('bookmark_link', 'P'); 	// type: array
        $bookmark_name 	= import_var('bookmark_name', 'P'); 	// type: array
        $bookmark_desc 	= import_var('bookmark_desc', 'P'); 	// type: array

        $id = '';
        $link = '';
        $name = '';
        $desc = '';

        if (count($bookmark_id) == 0) {
            $page->tpl->assign('_MESSAGE', _BKMKS_NONE_SELECTED);
            $page->tpl->assign('_RETURN_LINK', _BKMKS_NONE_SELECTED_RETURN_LINK);
        } else {
            for ($x = 0; $x < count($bookmark_id); $x++) {
                $id	= $bookmark_id[$x];
                $link	= $bookmark_link[$x];
                $name	= htmlentities($bookmark_name[$x], ENT_QUOTES);
                $desc	= htmlentities($bookmark_desc[$x], ENT_QUOTES);

                $page->do_save_edited_bookmark($user_id, $id, $link, $name, $desc);
            }

            /**
            * Assign success template variables
            */
            $page->tpl->assign('_MESSAGE', _BKMKS_EDIT_SUCCESS);
            $page->tpl->assign('_RETURN_LINK', _BKMKS_EDIT_RETURN_LINK);
        }

        $page->tpl->display('actions_done.tpl');
        break;
    case "do_delete_bookmark":
        $user_id 	= import_var('user_id', 'S');
        $bookmarks 	= import_var('bookmark_id', 'P');

        if (count($bookmarks) == 0) {
            $page->tpl->assign("_MESSAGE", _BKMKS_DELETE_FAILURE);
            $page->tpl->assign("_RETURN_LINK", _BKMKS_DELETE_RETURN_LINK);
        } else {
            foreach ($bookmarks as $key => $val) {
                $page->do_delete_bookmark($val, $user_id);
            }

            $page->tpl->assign("_MESSAGE", _BKMKS_DELETE_SUCCESS);
            $page->tpl->assign("_RETURN_LINK", _BKMKS_DELETE_RETURN_LINK);
        }

        $page->tpl->display('actions_done.tpl');
        break;
    case "do_action_delete":
        $items 		= import_var('item', 'P');
        $root 		= $page->normalize_dir(import_var('home_dir', 'S'));
        $path		= $page->normalize_dir(import_var('base_dir', 'P'));
        $user_id	= import_var('user_id', 'S');

        if (count($items) == 0) {
            /**
            * User didnt select anything to delete. Finish here
            */
            $page->tpl->assign("_MESSAGE", _MYFILES_DELETE_FAILURE);
            $page->tpl->assign("_RETURN_LINK", _MYFILES_DELETE_RETURN_LINK);
            $page->tpl->display('actions_done.tpl');
        } else {
            foreach ($items as $key => $val) {
                if (is_dir($page->normalize_dir($root . '/' . $path . '/' . $val))) {
                    $to_delete = $page->normalize_dir($root . '/' . $path . '/' . $val);

                    /**
                    * The group ID of the folder that we're putting the new folder in
                    */
                    @$group_id 	= $page->get_group_id($to_delete);
                    $group_id	= ($group_id) ? $group_id : '-';

                    /**
                    * To delete a directory, the user or group must have write permissions
                    */
                    if($page->permission_check($to_delete, $user_id, $group_id, "write")) {
                        $page->do_delete_dirs($to_delete, $user_id, $group_id);
                    } else if (is_admin($user_id, $page->ext->__get('extension_id'))){
                        /**
                        * We need to get the user_id of the folder instead of
                        * assuming the one from the session because it is possible
                        * that the admin could be deleting a file from another
                        * users directory
                        */
                        @$user_id = $page->get_user_id($to_delete);

                        $page->do_delete_dirs($to_delete, $user_id, $group_id);
                    }
                } else {
                    $to_delete = $page->normalize_file($root . '/' . $path . '/' . $val);
                    /**
                    * The group ID of the folder that we're putting the new folder in
                    */

                    $group_id 	= $page->get_group_id($to_delete);
                    $group_id	= ($group_id) ? $group_id : '-';

                    /**
                    * To delete a file, the user or group must have write permissions
                    */
                    if($page->permission_check($to_delete, $user_id, $group_id, "write")) {
                        $page->do_delete_files($to_delete, $user_id, $group_id);
                    } else if (is_admin($user_id, $page->ext->__get('extension_id'))){
                        /**
                        * We need to get the user_id of the folder instead of
                        * assuming the one from the session because it is possible
                        * that the admin could be deleting a file from another
                        * users directory
                        */
                        @$user_id = $page->get_user_id($to_delete);

                        $page->do_delete_files($to_delete, $user_id, $group_id);
                    }
                }
            }
            $page->show_home_folder($root, $user_id, $path, "show");
        }
        break;
    case "do_action_bookmark":
        $items		= import_var('item', 'P');
        $root 		= $page->normalize_dir(import_var('home_dir', 'S'));

        /**
        * Storing user_id in a local variable because
        * we're going to use it in the upcoming SQL
        */
        $user_id 	= import_var('user_id', 'S');

        if (count($items) == 0) {
            /**
            * Make sure the user doesnt try to bookmark nothing
            */
            $page->tpl->assign("_MESSAGE", _BKMKS_MADE_FAILURE_NO_DIR);
            $page->tpl->assign("_RETURN_LINK", _BKMKS_MADE_RETURN_LINK);
        } else {
            $page->do_bookmark_page($root, $items, $user_id);

            /**
            * Since we're finished, send a message saying we are
            */
            if ($page->__get("error")) {
                /**
                * Assign the list to template variables
                */
                $page->tpl->assign("_MESSAGE", _BKMKS_MADE_FAILURE);
                $page->tpl->assign("_RETURN_LINK", _BKMKS_MADE_RETURN_LINK);
            } else {
                /**
                * Otherwise, assign a success message to the template
                */
                $page->tpl->assign("_MESSAGE", _BKMKS_MADE_SUCCESS);
                $page->tpl->assign("_RETURN_LINK", _BKMKS_MADE_RETURN_LINK);
            }
        }
        $page->tpl->display("actions_done.tpl");
        break;
    case "do_action_cut":
        $user_id	= import_var('user_id', 'S');
        $root 		= $page->normalize_dir(import_var('home_dir', 'S'));

        if ($_POST) {
            /**
            * The first time around will place the
            * paths to the files in the session
            */
            $cut_list 	= import_var('item', 'P');
            $path 		= $page->normalize_dir(import_var('base_dir', 'P'));
        } elseif ($_SESSION['file_list'] != "") {
            /**
            * For every subsequent time around, we need to persist with the paths
            * in the session until the user pastes their files
            */
            $cut_list	= import_var('file_list', 'S');
            $path = $page->strip_bad_navigation(import_var('path', 'G'));
            $file = $page->strip_bad_navigation(import_var('file', 'G'));
            $path = $page->normalize_dir($path . '/' . $file);
        }


        if ($_POST) {
            /**
            * User has clicked the button to do a cut, assign
            * the paths cut to the session and notify the user that they
            * just cut their selected files and folders
            */
            $page->do_action_cut($cut_list, $root, $user_id, $path);
        } elseif ($_SESSION['file_list'] != "") {
            /**
            * If the user isnt posting again, they're probably navigating directories
            * to get to the directory they want to paste the files into.
            * We persist the data here with sessions instead of grabbing from post again
            */
            $page->show_home_folder($root, $user_id, $path, "cut");
        }
        break;
    case "do_action_copy":
        $user_id	= import_var('user_id', 'S');
        $root 		= $page->normalize_dir(import_var('home_dir', 'S'));

        if ($_POST) {
            /**
            * The first time around will place the paths to the files in the
            * session
            */
            $copy_list 	= import_var('item', 'P');
            $path 		= $page->normalize_dir(import_var('base_dir', 'P'));
        } elseif ($_SESSION['file_list'] != "") {
            /**
            * For every subsequent time around, we need to persist with the paths
            * in the session until the user pastes their files
            */
            $copy_list	= import_var('file_list', 'S');
            $path = $page->strip_bad_navigation(import_var('path', 'G'));
            $file = $page->strip_bad_navigation(import_var('file', 'G'));
            $path = $page->normalize_dir($path . '/' . $file);
        }

        if ($_POST) {
            /**
            * User has clicked the button to do a cut, assign
            * the paths cut to the session and notify the user that they
            * just cut their selected files and folders
            */
            $page->do_action_copy($copy_list, $root, $user_id, $path);
        } elseif ($_SESSION['file_list'] != "") {
            /**
            * If the user isnt posting again, their probably navigating directories
            * to get to the directory they want to paste the files into.
            * We persist the data here with sessions instead of grabbing from post again
            */
            $page->show_home_folder($root, $user_id, $path, "copy");
        }
        break;
    case "do_action_paste":
        $paste_list 	= import_var('file_list', 'S');
        $root		= $page->normalize_dir(import_var('home_dir', 'S'));
        $dest		= $page->normalize_dir(import_var('base_dir', 'P'));
        $user_id	= import_var('user_id', 'S');

        $item_dir	= $page->normalize_dir($root . '/' . $dest . '/');

        /**
        * We dont need to check if the item is a file or directory because you can
        * only download files. Therefore we search based on the normalized file we made
        */
        $group_id 	= $page->get_group_id($item_dir);
        $group_id	= ($group_id) ? $group_id : '-';

        /**
        * To download, the user or group must have read access
        */
        if($page->permission_check($item_dir, $user_id, $group_id, "write")) {
            $page->do_action_paste($root, $paste_list, $dest, $user_id);
            $page->show_home_folder($root, $user_id, $dest, "show");
        } else if (is_admin($user_id, $page->ext->__get('extension_id'))){
            $page->do_action_paste($root, $paste_list, $dest, $user_id);
            $page->show_home_folder($root, $user_id, $dest, "show");
        } else {
            $page->tpl->assign("_MESSAGE", _MYFILES_NO_PERM);

            $page->tpl->display("actions_done.tpl");
        }
        break;
    case "do_action_rename":
        $file		= import_var('item', 'P');
        $dir		= import_var('base_dir', 'P');
        $new_name	= import_var('rename', 'P');
        $root		= import_var('home_dir', 'S');
        $user_id	= import_var('user_id', 'S');

        $source = $page->normalize_dir($root . '/' . $file[0]);
        $dest = $root . '/' . $dir . '/' . $new_name;

        if (!is_dir($source)) {
            $source = $page->normalize_file($source);
            $dest = $page->normalize_file($dest);

            $tmp = explode(".", $new_name);
            $ext = explode(".", $file[0]);
            $count = count($tmp);
            $count2 = count($ext);

            if ($count > 1) {
                $dest = $dest;
            } else {
                if ($count2 > 1) {
                    $dest .= "." . $ext[count($ext) - 1];
                } else {
                    $dest = $dest;
                }
            }
        } else {
            $dest = $page->normalize_dir($dest);
        }

        $page->do_action_rename($source, $dest);
        $page->show_home_folder($root, $user_id, $dir, "show");
        break;
    case "show_action_archive":
        $archive_type 	= import_var('archive_type', 'S');
        $file_list	= import_var('item', 'P');
        $base_dir	= import_var('base_dir', 'P');
        $user_id	= import_var('user_id', 'S');
        $root		= import_var('home_dir', 'S');

        $file_list = @array_filter($file_list, array($page, "filter_root_paths"));
        $file_list = @array_values($file_list);

        foreach ($file_list as $key => $val) {
            $tmplist[] = $base_dir . $val;
        }

        $file_list = $tmplist;

        if (import_var('archive_type', 'S') == "") {
            $page->tpl->assign("ROOT", $base_dir);

            if (count($file_list) == 0) {
                /**
                * Assign language constants
                */
                $page->tpl->assign('_MESSAGE', _MYFILES_ARCHIVE_NONE);
                $page->tpl->assign('_RETURN_LINK', _MYFILES_ARCHIVE_NONE_RETURN_LINK);

                /**
                * Display the page
                */
                $page->tpl->display('actions_done.tpl');
                exit;
            } else {
                /**
                * Assign language constants
                */
                $page->tpl->assign(array(
                    '_MYFILES_ARCHIVE_WELCOME' 		=> _MYFILES_ARCHIVE_WELCOME,
                    '_MYFILES_ARCHIVE_ZIP_CATEGORY_DESC' 	=> _MYFILES_ARCHIVE_ZIP_CATEGORY_DESC,
                    '_MYFILES_ARCHIVE_GZIP_CATEGORY_DESC' 	=> _MYFILES_ARCHIVE_GZIP_CATEGORY_DESC,
                    '_MYFILES_ARCHIVE_BZIP2_CATEGORY_DESC'	=> _MYFILES_ARCHIVE_BZIP2_CATEGORY_DESC,
                    '_MYFILES_ARCHIVE_TAR_CATEGORY_DESC'	=> _MYFILES_ARCHIVE_TAR_CATEGORY_DESC,
                    '_MYFILES_ARCHIVE_SELECT_TYPE'		=> _MYFILES_ARCHIVE_SELECT_TYPE,
                    '_MYFILES_ARCHIVE_REMEMBER_TYPE'	=> _MYFILES_ARCHIVE_REMEMBER_TYPE,
                    '_MYFILES_ARCHIVE_ITEMS'		=> _MYFILES_ARCHIVE_ITEMS,
                    '_MYFILES_ARCHIVE_NAME'			=> _MYFILES_ARCHIVE_NAME,
                    '_MYFILES_ARCHIVE_CREATE' 		=> _MYFILES_ARCHIVE_CREATE,
                    '_MYFILES_SHOW_FILES' 			=> _MYFILES_SHOW_FILES,
                    '_MYFILES_MANAGE_BOOKMARKS' 		=> _MYFILES_MANAGE_BOOKMARKS)
                );

                /**
                * Assign dynamic content
                */
                $page->tpl->assign(array(
                    'FILE_LIST' 	=> $file_list,
                    'JS_INC'	=> 'myfiles_archive.tpl'));

                /**
                * Display the page
                */
                $page->tpl->display('myfiles_archive.tpl');
            }
        } else {
            $archive_type = import_var('archive_type', 'S');
            $page->do_action_archive($archive_type, $file_list);
            $page->show_home_folder($root, $user_id, $base_dir, "show");
        }
        break;
    case "do_action_archive":
        $file_list	= import_var('item', 'P');
        $archive_name	= import_var('archive_name', 'P');
        $remember_type	= import_var('remember_type', 'P');
        $base_dir	= import_var('base_dir', 'P');
        $user_id	= import_var('user_id', 'S');
        $root		= import_var('home_dir', 'S');
        $paste_path	= $root . '/' . $base_dir;

        if(import_var('archive_type', 'P') != "")
            $archive_type = import_var('archive_type', 'P');
        else
            $archive_type = import_var('archive_type', 'S');

        if($file_list > 0) {
            $page->do_action_archive($archive_name, $archive_type, $file_list, $root, $paste_path);
            $page->show_home_folder($root, $user_id, $base_dir, "show");
        } else
            $page->show_home_folder($root, $user_id, $base_dir, "show");
        break;
    case "do_extract_archive":
        $root 		= import_var('home_dir', 'S');
        $user_id	= import_var('user_id', 'S');
        $base_dir	= import_var('base_dir', 'P');
        $archive 	= import_var('item', 'P');

        $path = $page->normalize_dir($root . $base_dir);

        foreach ($archive as $key => $val) {
            // The group ID of the folder that we're putting the new folder in
            $group_id 	= $page->get_group_id($path);
            $group_id	= ($group_id) ? $group_id : '-';

            $page->do_extract_archive($path, $val, $user_id, $group_id);
        }

        $page->show_home_folder($root, $user_id, $base_dir, "show");
        break;
    case "do_action_new_dir":
        /**
        * root is the users home directory
        */
        $root 		= import_var('home_dir', 'S');

        // User's ID
        $user_id	= import_var('user_id', 'S');

        // base_dir is the directory we're in minus the users root
        $base_dir	= import_var('base_dir', 'P');

        // dir is the directory we're trying to make
        $dir		= $page->strip_forbidden_chars(import_var('dir', 'P'), "dir");

        // Contains all the path necessary to create the directory, minus the users home directory
        $path 		= $base_dir . str_replace(" ", "_", $dir);

        // The current directory we're trying to make a folder in
        $current_dir 	= $page->normalize_dir($root . '/' . $base_dir . '/');

        // The group ID of the folder that we're putting the new folder in
        $group_id 	= $page->get_group_id($current_dir);
        $group_id	= ($group_id) ? $group_id : '-';

        $path = $page->normalize_dir($path);

        if($page->permission_check($current_dir, $user_id, $group_id, "write")) {
            if($page->do_new_dir($root, $path, $user_id, $group_id)) {
                $page->tpl->assign('_MESSAGE', _MYFILES_NEW_DIR_FAILURE);
                $page->tpl->assign('_RETURN_LINK', "<a href='index.php?extension=Filesystem&amp;action=show_files&amp;path=$current_dir'>Return to MyFiles</a>");
                $page->tpl->display('actions_done.tpl');
            } else
                $page->show_home_folder($root, $user_id, $base_dir, "show");
        } else if (is_admin($user_id, $page->ext->__get('extension_id'))){
            if($page->do_new_dir($root, $path, $user_id, $group_id)) {
                $page->tpl->assign('_MESSAGE', _MYFILES_NEW_DIR_FAILURE);
                $page->tpl->assign('_RETURN_LINK', "<a href='index.php?extension=Filesystem&amp;action=show_files&amp;path=$current_dir'>Return to MyFiles</a>");
                $page->tpl->display('actions_done.tpl');
            } else
                $page->show_home_folder($root, $user_id, $base_dir, "show");
        } else {
            $page->show_home_folder($root, $user_id, $base_dir, "show");
        }
        break;
    default:
    case "show_files":
        if ($_POST) {
            $path = $page->strip_bad_navigation(import_var('base_dir', 'P'));
        } else {
            $path = $page->strip_bad_navigation(import_var('path', 'G'));
            $file = $page->strip_bad_navigation(import_var('file', 'G'));
            $path = $page->normalize_dir($path . '/' . $file);
        }

        $root 		= import_var('home_dir', 'S');
        $user_id	= import_var('user_id', 'S');


        if ($path == "") {
            $path .= "/";
        } else {
            if(substr($path, -1, 1) != "/")
                $path .= "/";
        }

        $current_dir = $page->normalize_dir($root . '/' . $path . '/');

        // The group ID of the folder that we're putting the new folder in
        $group_id 	= $page->get_group_id($current_dir);
        $group_id	= ($group_id) ? $group_id : '-';

        if($page->permission_check($current_dir, $user_id, $group_id, "execute")) {
            $page->show_home_folder($root, $user_id, $path, "show");
        } else if (is_admin($user_id, $page->ext->__get('extension_id'))){
            $page->show_home_folder($root, $user_id, $path, "show");
        } else {
            $page->tpl->assign('_MESSAGE', "Permission Denied");
            $page->tpl->assign('_RETURN_LINK', "<a href='index.php?extension=Filesystem&amp;action=show_files'>Return to MyFiles</a>");
            $page->tpl->display('actions_done.tpl');
        }
        break;
}

?>
