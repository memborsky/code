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
* Require filesystem abstraction layer to extend in MyFiles
*/
require_once (ABSPATH.'/extensions/Filesystem/class.Filesystem.php');

/**
* MyFiles userland tools
*
* Acts as a layer of abstraction to the Filesystem class
* it extends. It contains tools to act on Filesystem
* items en-masse for the user.
*
* @package Filesystem
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class MyFiles extends Filesystem {
    /**
    * Database object used to connect to and query database
    *
    * @access public
    * @var object
    */
    public $db;

    /**
    * Template object used to display pages
    *
    * @access public
    * @var object
    */
    public $tpl;

    /**
    * Logging object used to log actions to the database
    *
    * @access public
    * @var object
    */
    public $log;

    /**
    * Contains all settings stored in the database that relate to this extension
    *
    * @access public
    * @var object
    */
    public $cfg;

    /**
    * Contains extension specific properties not available in the Configuration object
    *
    * @access public
    * @var object
    */
    public $ext;

    /**
    * Contains a list of all items that the script encountered problems with when
    * operating on
    *
    * @access public
    * @var array
    */
    public $failure_list;

    /**
    * Contains a list of all items that already existed when a particular operation
    * was being performed on a given list of items
    *
    * @access public
    * @var array
    */
    public $already_exists_list;

    /**
    * Creates an instance of MyFiles class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. It begins by creating empty arrays for
    * both the failure and already_exists arrays that may be
    * used by later methods when performing Filesystem tasks
    *
    * @access public
    */
    public function __construct() {
        $this->failure_list = array();
        $this->already_exists_list = array();

        parent::__construct();
    }

    /**
    * Show the contents of a directory
    *
    * This method will display the contents of a directory and assign
    * any template variables to the templates so that they will display
    * correctly
    *
    * @access public
    * @param string $root The root of the directory to show
    * @param integer $user_id The ID of the user viewing the directory
    * @param string $path The path from the root of the directory to show
    * @param string $mode The current mode, either 'show', 'cut', or 'copy'
    */
    public function show_home_folder($root, $user_id, $path = "", $mode = "show") {
        // Variable declaration
        $dir 		= $this->strip_bad_navigation($path);
        $sql 		= array (
                    "mysql"	=> array (
                        'bookmarks' => "SELECT `link`,`name` FROM "._PREFIX."_bookmarks WHERE user_id=':1'",
                        'quotas' => "SELECT quota_total, quota_used FROM "._PREFIX."_users WHERE user_id=':1' LIMIT 1"
                    )
                );

        $bookmarks	= array();
        $quota_result	= array();

        // Set the users path to whatever they specified, and
        // to their home root if they didnt specify anything
        $path = ($path == "/") ? '' : $path;

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['bookmarks']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['quotas']);

        // Execute it
        $stmt1->execute($user_id);

        // Read in all our bookmarks that we have created. Will be used in building select box
        while ($row = $stmt1->fetch_row()) {
            $bookmarks[] = array($row[0], $row[1]);
        }

        if ($this->cfg['use_quotas']) {
            $stmt2->execute($user_id);
            $quota_result = $stmt2->fetch_assoc();

            $quota_used	= $this->format_space($quota_result['quota_used']);
            $quota_total	= $this->format_space($quota_result['quota_total']);

            if ($quota_used < 0)
                $quota_used = 0;
        }

        // Get the contents of the directory
        $this->ls_dir($root, $dir);

        if ($this->__get("error")) {
            // If we got an error while listing the directory, then the dir doesnt exist
            $this->tpl->assign('_MESSAGE', _MYFILES_DIR_NO_EXIST);
            $this->tpl->assign('_RETURN_LINK', _MYFILES_DIR_NO_EXIST_RETURN_LINK);

            $this->tpl->display('actions_done.tpl');
        } else {
            // Assign language constants
            $this->tpl->assign(array(
                '_MYFILES_WELCOME'          => _MYFILES_WELCOME,
                '_MYFILES_SHOW_FILES'       => _MYFILES_SHOW_FILES,
                '_MYFILES_UPLOAD_FILES'     => _MYFILES_UPLOAD_FILES,
                '_MYFILES_BOOKMARKS'        => _MYFILES_BOOKMARKS,
                '_MYFILES_MANAGE_BOOKMARKS' => _MYFILES_MANAGE_BOOKMARKS,
                '_MYFILES_MOVE_SELECTED'    => _MYFILES_MOVE_SELECTED,
                '_MYFILES_COPY_SELECTED'    => _MYFILES_COPY_SELECTED,
                '_MYFILES_RENAME_SELECTED'  => _MYFILES_RENAME_SELECTED,
                '_MYFILES_DELETE_SELECTED'  => _MYFILES_DELETE_SELECTED,
                '_MYFILES_PASTE_SELECTED'   => _MYFILES_PASTE_SELECTED,
                '_MYFILES_BKMK_SELECTED'    => _MYFILES_BKMK_SELECTED,
                '_MYFILES_ZIP_SELECTED'     => _MYFILES_ZIP_SELECTED,
                '_MYFILES_EXTRACT_SELECTED' => _MYFILES_EXTRACT_SELECTED,
                '_MYFILES_FOLDER_EMPTY'     => _MYFILES_FOLDER_EMPTY,
                '_MYFILES_FOLDER_INFO'      => _MYFILES_FOLDER_INFO,
                '_MYFILES_BROWSER'          => _MYFILES_BROWSER,
                '_MYFILES_HOME'             => _MYFILES_HOME,
                '_MYFILES_NEW_DIR'          => _MYFILES_NEW_DIR,
                '_ITEMS'                    => _ITEMS,
                '_FILE'                     => _FILE,
                '_FILES'                    => _FILES,
                '_SIZE'                     => _SIZE,
                '_DATE_MODIFIED'            => _DATE_MODIFIED,
                '_TOTAL'                    => _TOTAL,
                '_FOLDERS'                  => _FOLDERS));

            // Assign dynamic content and other vars
            $this->tpl->assign(array(
                'FILES'         => $this->__get("files"),
                'DIRECTORIES'   => $this->__get("dirs"),
                'TOTAL_ITEMS'   => $this->__get("total_files") + $this->__get("total_dirs"),
                'TOTAL_FILES'   => $this->__get("total_files"),
                'TOTAL_SIZE'    => $this->__get("total_size"),
                'TOTAL_DIRS'    => $this->__get("total_dirs"),
                'BOOKMARK_LIST' => $bookmarks,
                'ROOT'          => $path,
                'FILE_MODE'     => $mode,
                'MYWEBSITE'     => str_replace("{USERNAME}", import_var('username', 'S'), $this->cfg['website_base']),
                'QUOTA_USED'    => $quota_used,
                'QUOTA_TOTAL'   => $quota_total,
                'JS_INC'        => 'myfiles_main.tpl'));

            // Display page
            $this->tpl->display('myfiles_main.tpl');
        }
    }

    /**
    * Displays the page containing the upload feature
    *
    * The upload functionality is contained in a popup window. This
    * method will display the contents of the popup window. The actual
    * code to cause the window to pop up is javascript that is contained
    * in the myfiles_main template.
    *
    * @access public
    * @param string $path The directory to which uploaded files will be placed
    * @param integer $box_count The number of file boxes to display for uploading files.
    */
    public function show_upload_files($path, $box_count) {
        // Assign language constants
        $this->tpl->assign(array(
            '_MYFILES_UPLOAD_WELCOME'   => _MYFILES_UPLOAD_WELCOME,
            '_MYFILES_NUM_UL_FIELDS'    => _MYFILES_NUM_UL_FIELDS,
            '_MYFILES_UPLOAD_FILES'     => _MYFILES_UPLOAD_FILES,
            '_MYFILES_RESET_FIELDS'     => _MYFILES_RESET_FIELDS,
            '_MYFILES_CHG_UL_CNT'       => _MYFILES_CHG_UL_CNT));

        // Assign dynamic content and other vars
        $this->tpl->assign('BOX_COUNT', $box_count);
        $this->tpl->assign('ROOT', $path);
        $this->tpl->assign("JS_INC", "myfiles_upload.tpl");

        // Display page
        $this->tpl->display('myfiles_upload.tpl');
    }

    /**
    * Uploads files to Flare
    *
    * This is a wrapper method that provides access to the Upload class
    * used to upload files to the server
    *
    * @access public
    * @param string $dest_dir
    */
    public function do_upload_files($dest_dir, $owner_id, $group_id) {
        require_once(ABSPATH.'/extensions/Filesystem/class.Uploads.php');
        require_once(ABSPATH.'/extensions/Groups/class.Groups.php');

        $grp = new Groups();
        $grp->__set("db", $this->__get("db"));

        /**
        * $_FILES['file_upload_array']['name'][$counter]
        * $_FILES['file_upload_array']['size'][$counter]
        * $_FILES['file_upload_array']['tmp_name'][$counter]
        * $_FILES['file_upload_array']['type'][$counter]
        * $_FILES['file_upload_array']['error'][$counter]
        *
        * Error values:
        * 0 - No error
        * 1 - Exceeded filesize allowed in php.ini
        * 2 - Exceeded filesize allowed by HTML MAX_FILE_SIZE
        * 3 - Only a partial upload
        * 4 - No upload occurred.
        */

        foreach ($_FILES['file_upload_array']['name'] as $key => $val) {
            if ($val != '')
                $file_upload_count++;
        }

        $ul_count   = 0;
        $name       = '';
        $size       = 0;
        $tmp_name   = '';
        $type       = '';
        $error      = 0;

        $ul = new Uploads;
        $ul->__set("cfg", $this->__get("cfg"));
        $ul->__set("log", $this->__get("log"));

        if ($file_upload_count > 0) {
                        // Check for error code support. Set the error code.
            if (count($_FILES['file_upload_array']['error']) == 0) {
                // This version of PHP does not support error
                // codes (PHP < 4.2.0).  Create our own error code.
                $error = 'default';
            } else {
                // We have error support.
                $error_support = 'TRUE';
            }

            /**
            * For each box that was on the show_upload page, we need to process the
            * uploaded data.
            */
            for ($counter = 0; $counter < $file_upload_count; $counter++) {
                $name       = $_FILES['file_upload_array']['name'][$counter];
                $size       = $_FILES['file_upload_array']['size'][$counter];
                $tmp_name   = $_FILES['file_upload_array']['tmp_name'][$counter];
                $type       = $_FILES['file_upload_array']['type'][$counter];
                $quotas     = array();

                /**
                * Quotas dont _have_ to be used and can be turned off if not needed
                * therefore check to see if we're even using them
                */
                if ($this->cfg['use_quotas']) {
                    /**
                    * The group_id field for user directories should be set to '-'
                    * therefore if we do this if statement we should be working in
                    * a group directory
                    */
                    if ($group_id != '-') {
                        /**
                        * Get the available quotas for the group
                        *
                        * The result returned is an array that is indexed by the
                        * field name. In this case, there should be 2 entries in
                        * the array.
                        * - quota_used
                        * - quota_total
                        */
                        $quotas = $grp->get_group_quotas($group_id);

                        /**
                        * If there are no results for some reason, the previous
                        * method will return an empty array. Check for it.
                        */
                        if (!empty($quotas)) {
                            /**
                            * Now we check to see if the uploaded file will
                            * put the group over quota
                            */
                            if (($size + $quotas['quota_used']) > $quotas['quota_total']) {
                                $this->tpl->assign("_MESSAGE", _MYFILES_UPLOAD_OVER_QUOTA_FAILURE);
                                $this->tpl->assign("_RETURN_LINK", _MYFILES_UPLOAD_RETURN_LINK);

                                $this->tpl->display('actions_done.tpl');
                                exit;
                            } else {
                                /**
                                * Check for error code support. Set the error code.
                                */
                                if ($error_support)
                                    $error  = $_FILES['file_upload_array']['error'][$counter];

                                /**
                                * Do the actual upload
                                */
                                $ul->upload_file($name, $size, $tmp_name, $type, $error, $dest_dir);

                                /**
                                * Check the status to see if the upload was successful
                                */
                                if($ul->__get("status")) {
                                    /**
                                    * If the upload succeeded, add file_permission entries for it
                                    */
                                    $this->add_file_perms_entries($ul->__get("uploaded_filename"), $owner_id, $group_id, $this->cfg['file_permissions']);

                                    /**
                                    * Also adjust quotas for it.
                                    * If the upload wont put the user over quota,
                                    * then we need to increase the total amount
                                    * of used quota by the size of the file being
                                    * uploaded
                                    */
                                    $grp->adjust_space_increase($group_id, $size);

                                    ++$ul_count;
                                } else {
                                    /**
                                    * The upload was not successful!
                                    */
                                    ++$ul_count;
                                }
                            }
                        } else
                            return false;
                    /**
                    * Otherwise, we're operating on a user directory. A users quotas
                    * are stored in a seperate location from the group quotas
                    */
                    } else {
                        /**
                        * Get the available quotas for the user
                        */
                        $quotas = $this->get_user_quotas($owner_id);

                        /**
                        * If there are no results for some reason, the previous
                        * method will return an empty array. Check for it.
                        */
                        if (!empty($quotas)) {
                            /**
                            * Now we check to see if the uploaded file will
                            * put the user over quota
                            */
                            if (($size + $quotas['quota_used']) > $quotas['quota_total']) {
                                $this->tpl->assign("_MESSAGE", _MYFILES_UPLOAD_OVER_QUOTA_FAILURE);
                                $this->tpl->assign("_RETURN_LINK", _MYFILES_UPLOAD_RETURN_LINK);

                                $this->tpl->display('actions_done.tpl');
                                exit;
                            } else {
                                /**
                                * Check for error code support. Set the error code.
                                */
                                if ($error_support)
                                    $error  = $_FILES['file_upload_array']['error'][$counter];

                                /**
                                * Do the actual upload
                                */
                                $ul->upload_file($name, $size, $tmp_name, $type, $error, $dest_dir);

                                /**
                                * Check the status to see if the upload was successful
                                */
                                if($ul->__get("status")) {
                                    /**
                                    * If the upload succeeded, add file_permission entries for it
                                    */
                                    $this->add_file_perms_entries($ul->__get("uploaded_filename"), $owner_id, $group_id, $this->cfg['file_permissions']);

                                    /**
                                    * Also adjust quotas for it.
                                    * If the upload wont put the user over quota,
                                    * then we need to increase the total amount
                                    * of used quota by the size of the file being
                                    * uploaded
                                    */
                                    $this->adjust_space_increase($owner_id, $size);

                                    ++$ul_count;
                                } else {
                                    /**
                                    * The upload was not successful!
                                    */
                                    ++$ul_count;
                                }
                            }
                        } else
                            return false;
                    }
                } else {
                    // Check for error code support. Set the error code.
                    if ($error_support)
                        $error  = $_FILES['file_upload_array']['error'][$counter];

                    $ul->upload_file($name, $size, $tmp_name, $type, $error, $dest_dir);

                    if($ul->__get("status")) {
                        $this->add_file_perms_entries($ul->__get("uploaded_filename"), $owner_id, $group_id, $this->cfg['file_permissions']);
                        ++$ul_count;
                    } else {
                        ++$ul_count;
                    }
                }
            }
        } else {
            $file_upload_count--;
        }

        if ($ul_count == $file_upload_count) {
            $this->tpl->assign("_MESSAGE", _MYFILES_UPLOAD_SUCCESS);
            $this->tpl->assign("_RETURN_LINK", _MYFILES_UPLOAD_RETURN_LINK);
        } else {
            $this->tpl->assign("_MESSAGE", _MYFILES_UPLOAD_FAILURE);
            $this->tpl->assign("_RETURN_LINK", _MYFILES_UPLOAD_RETURN_LINK);
        }

        $this->tpl->assign("JS_INC", "myfiles_upload.tpl");
        $this->tpl->display('actions_done.tpl');
    }

    /**
    * Shows page from where to manage bookmarks
    *
    * The bookmarks page where you can edit or delete bookmarks you
    * have accumulated can be displayed using this method. This
    * method will handle populating all the template variables and
    * calling the functions that actually display the page to the user
    *
    * @access public
    * @param integer $user_id The ID of the user who is attempting to manage their bookmarks.
    */
    public function show_manage_bookmarks($user_id) {
        // Multi-dimensional array containing link names, locations and descriptions for all bookmarks
        $bookmarks = array();

        // Assign SQL that will be used
        $sql = array (
            "mysql"	=> array (
                'bookmarks' => "SELECT `bookmark_id`,`link`,`name`,`description` FROM "._PREFIX."_bookmarks WHERE user_id = ':1'",
                )
        );

        // Prepare SQL for executing
        $stmt = $this->db->prepare($sql[_DBSYSTEM]['bookmarks']);

        // Execute, passing any needed args
        $stmt->execute($user_id);

        // Populate bookmarks array that will be sent to Smarty
        while ($row = $stmt->fetch_row()) {
            $bookmarks[] = array($row[0], $row[1], $row[2], $row[3]);
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_BKMKS_WELCOME'        => _BKMKS_WELCOME,
            '_BKMKS_VIEW'           => _BKMKS_VIEW,
            '_BKMKS_NAME'           => _BKMKS_NAME,
            '_BKMKS_NO_BKMKS'       => _BKMKS_NO_BKMKS,
            '_NO_ACTIONS'           => _NO_ACTIONS,
            '_LINK'                 => _LINK,
            '_DESCRIPTION'          => _DESCRIPTION,
            '_WITH_SELECTED'        => _WITH_SELECTED,
            '_DELETE'               => _DELETE,
            '_EDIT'                 => _EDIT,
            '_CHOOSE_ACTION'        => _CHOOSE_ACTION,
            '_MYFILES_SHOW_FILES'   => _MYFILES_SHOW_FILES));

        // Assign dynamic content and other vars
        $this->tpl->assign(array(
            "BOOKMARK_LIST" => $bookmarks,
            "JS_INC"        => "bookmarks_main.tpl"
        ));

        // Tell Smarty to make the main body of the bookmarks page
        $this->tpl->display('bookmarks_main.tpl');
    }

    /**
    * Adds a folder to the bookmark list
    *
    * Only folders can be bookmarked by users. This method
    * will create an entry in the bookmarks table so that
    * the user can jump directly to the folder they have
    * bookmarked without needing to click their way through
    * all the directories to get to that folder.
    *
    * @access public
    * @param string $root The root directory from where the folder is located
    * @param array $items List of paths to add as bookmarks
    * @param integer $user_id The user ID of the user doing the bookmarking
    */
    public function do_bookmark_page($root, $items, $user_id) {
        $sql = array(
            "mysql" => array (
                'bookmark' => "INSERT INTO "._PREFIX."_bookmarks (`user_id`, `link`, `name`) VALUES (':1',':2',':3');",
            )
        );
        $failed_bookmarks = array();

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['bookmark']);

        // For each bookmark that is sent to us...
        foreach ($items as $key => $val) {
            $path = $root . $val;
            // If the requested bookmark is not a directory, skip it.
            if (!is_dir($path)) {
                $failed_bookmarks[] = $path;
                $this->__set("error", TRUE);
                continue;
            }

            // Run it, passing the needed args
            $stmt->execute($user_id, $val, $val);

            $this->log->log('BOOKMARK_ADD', $path, import_var('username', 'S'));
        }
    }

    /**
    * Deletes a bookmark
    *
    * This method will delete a bookmark from the system.
    *
    * @access public
    * @param integer $bookmark The ID of the bookmark to delete
    * @param integer $user_id The user ID of the user who owns the bookmark
    */
    public function do_delete_bookmark($bookmark, $user_id) {
        $sql = array (
            "mysql" => array (
                'delete_bookmark' => "DELETE FROM "._PREFIX."_bookmarks WHERE bookmark_id=':1' AND user_id=':2'",
            )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['delete_bookmark']);

        $stmt->execute($bookmark, $user_id);
        $this->log->log('BOOKMARK_DELETE', $bookmark, $user_id);
    }

    /**
    * Deletes a file
    *
    * Deleting a file is slightly different from deleting a directory
    * in that the SQL to remove the file permissions executes given an
    * exact file to remove.
    *
    * @access public
    * @param string $path Full path to the file that is to be removed
    * @return bool True on failure, false on success
    */
    public function do_delete_files($path, $user_id, $group_id) {
        require_once(ABSPATH.'/extensions/Groups/class.Groups.php');

        $grp = new Groups();
        $grp->__set("db", $this->__get("db"));

        $path = $this->normalize_file($path);

        /**
        * Size is used later when we are adjusting the quotas
        * of either the user or group
        */
        $size = $this->dirsize($path);

        /**
        * Start by removing the file. Typical operation here
        */
        $this->rm_dir($path);

        if($this->__get("error")) {
            return true;
        } else {
            /**
            * If there werent any errors while removing the file,
            * then we next need to remove the file permissions
            */
            $this->remove_file_perms_entries($path);

            /**
            * Add a log entry saying that we've removed the file
            */
            $this->log->log('FILE_DELETE', $path, import_var('username', 'S'));

            /**
            * If we're using quotas then we need to do some further
            * code stuff; like adjusting the quotas
            */
            if ($this->cfg['use_quotas']) {
                /**
                * Start by checking to see if the file is in a group directory
                */
                if ($group_id != '-') {
                    /**
                    * If so, then we need to get the current group quotas
                    */
                    $quotas = $grp->get_group_quotas($group_id);

                    /**
                    * The above value will contain an empty array if there
                    * was no entry in the groups table for whatever reason
                    */
                    if (!empty($quotas)) {
                        /**
                        * Increase the amount of available quota for the group
                        */
                        $grp->adjust_space_decrease($group_id, $size);
                    } else
                        return true;
                } else {
                    /**
                    * If we are here, then we are operating in a user directory.
                    * Get the quotas for the user
                    */
                    $quotas = $this->get_user_quotas($user_id);

                    /**
                    * The above value will contain an empty array if there
                    * was no entry in the users table for whatever reason
                    */
                    if (!empty($quotas)) {
                        /**
                        * Increase the amount of available quota for the user
                        */
                        $this->adjust_space_decrease($user_id, $size);
                    } else
                        return true;
                }
            }

            return false;
        }
    }

    /**
    * Deletes an entire directory
    *
    * The code to remove a directory is the same as the code to remove
    * a file, except the SQL to take care of the file permissions is
    * changed slightly to match a wildcard instead of an exact folder.
    * This is needed or else only the folder name will be removed from
    * the permissions table and we will have inconsistancy in the table
    * because permissions still exist even though the file is gone.
    *
    * @access public
    * @param string $path Full path to the directory that is to be removed
    */
    public function do_delete_dirs($path, $user_id, $group_id) {
        require_once(ABSPATH.'/extensions/Groups/class.Groups.php');

        $grp = new Groups();
        $grp->__set("db", $this->__get("db"));

        $path = $this->normalize_dir($path);

        $size = $this->dirsize($path);

        $this->rm_dir($path);

        if($this->__get("error")) {
            $failed_delete[] = $full_path;
        } else {
            $this->remove_file_perms_entries($path, 'dir');
            $this->log->log('FILE_DELETE', $path, import_var('username', 'S'));

            /**
            * If we're using quotas then we need to do some further
            * code stuff; like adjusting the quotas
            */
            if ($this->cfg['use_quotas']) {
                /**
                * Start by checking to see if the file is in a group directory
                */
                if ($group_id != '-') {
                    /**
                    * If so, then we need to get the current group quotas
                    */
                    $quotas = $grp->get_group_quotas($group_id);

                    /**
                    * The above value will contain an empty array if there
                    * was no entry in the groups table for whatever reason
                    */
                    if (!empty($quotas)) {
                        /**
                        * Increase the amount of available quota for the group
                        */
                        $grp->adjust_space_decrease($group_id, $size);
                    }
                } else {
                    /**
                    * If we are here, then we are operating in a user directory.
                    * Get the quotas for the user
                    */
                    $quotas = $this->get_user_quotas($user_id);

                    /**
                    * The above value will contain an empty array if there
                    * was no entry in the users table for whatever reason
                    */
                    if (!empty($quotas)) {
                        /**
                        * Increase the amount of available quota for the user
                        */
                        $this->adjust_space_decrease($user_id, $size);
                    }
                }
            }
        }
    }

    /**
    * Creates a new directory
    *
    * This method will create a new directory in any folder specified
    *
    * @access public
    * @param string $root The root directory where the new folder will be made
    * @param string $path The remaining path, from root, to the directory where the folder will be made
    * @param integer $owner_id The user ID of the user creating the folder
    * @return bool Returns true on failure, false on success
    */
    public function do_new_dir($root, $path, $owner_id, $group_id) {
        $mode   = 0755;

        /**
        * Normalize the directory as a file first because
        * we need to find the parent folder and the easiest
        * way I can think of doing this is to just find the
        * last forward slash in the path name. However that
        * means we need to normalize as a file because
        * otherwise a normalized dir has a forward slash
        * at the end and this will screw up the results
        */
        $path   = $this->normalize_file($root . '/' . $path);

        /**
        * Get the full path to the parent folder
        */
        $parent = substr($path, 0, strrpos($path, "/") + 1);

        /**
        * Fix the path by sticking back on the forward slash
        * we left off when we normalized the directory as a file
        */
        $path   = $this->normalize_dir($path);

        if (is_dir($path)) {
            /**
            * We'll be here if the directory already exists
            */
            return false;
        } else {
            /**
            * We'll be here if the directory doesnt exist and
            * we need to make it
            */
            $this->mk_dir($path, $mode);

            if (!is_dir($path)) {
                /**
                * And we'll be here if the directory wasnt
                * created when we tried to create it
                */
                return true;
            }

            $this->add_file_perms_entries($path, $owner_id, $group_id, $this->cfg['directory_permissions']);

            return false;
        }
    }

    /**
    * Displays the edit bookmark page
    *
    * This is different from the manage bookmarks method. This
    * method is the actual editing functionality for the bookmarks
    * where the manage function only displayed all the bookmarks
    * that could be edited. This will let you actually edit them.
    *
    * @access public
    * @param array $bookmarks Bookmark ID of all the selected bookmarks that are to be edited
    * @param integer $user_id User ID of the user editing the bookmark
    * @return bool Returns false on success and true on failure
    */
    public function show_edit_bookmark($bookmarks, $user_id) {
        $bookmark_list  = array();
        $bookmark_id    = '';
        $link           = '';
        $name           = '';
        $description    = '';

        $sql = array (
            "mysql" => array (
                'bookmark' => "SELECT bookmark_id,link,name,description FROM "._PREFIX."_bookmarks WHERE bookmark_id=':1' AND user_id=':2'"
                )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['bookmark']);

        if (count($bookmarks) == 0) {
            $this->tpl->assign('_MESSAGE', _BKMKS_NONE_SELECTED);
            $this->tpl->assign('_RETURN_LINK', _BKMKS_NONE_SELECTED_RETURN_LINK);

            $this->tpl->display('actions_done.tpl');

            return true;
        } else {
            foreach ($bookmarks as $key => $val) {
                $stmt->execute($val,$user_id);
                $row            = $stmt->fetch_array();

                $bookmark_id    = $row['bookmark_id'];
                $link           = $row['link'];
                $name           = mysql_escape_string($row['name']);
                $description    = $row['description'];

                $bookmark_list[] = array ($bookmark_id,$link,$name,$description);
            }

            // Assign language constants
            $this->tpl->assign(array(
                '_NAME'             => _NAME,
                '_LOCATION'         => _LOCATION,
                '_DESCRIPTION'      => _DESCRIPTION,
                '_SAVE_CHGS'        => _SAVE_CHGS,
                '_UNDO_CHGS'        => _UNDO_CHGS,
                '_BKMKS_VIEW'       => _BKMKS_VIEW));

            // Assign dynamic content
            $this->tpl->assign('BOOKMARK_LIST', $bookmark_list);

            // Show the page
            $this->tpl->display('bookmarks_edit.tpl');

            return false;
        }
    }

    /**
    * Saves changes made to bookmarks
    *
    * After the user is done editing any selected bookmarks, they
    * must save them back before the changes take effect. This
    * will save changes back to the bookmarks table
    *
    * @access public
    * @param integer $user_id The user ID of the person saving the bookmark
    * @param integer $bookmark_id
    */
    public function do_save_edited_bookmark($user_id, $bookmark_id, $bookmark_link, $bookmark_name, $bookmark_desc) {
        $old_bookmark_link = '';

        // Create SQL array using format for multi databases
        $sql = array (
            "mysql" => array (
                'save_bkmk' => "UPDATE "._PREFIX."_bookmarks SET name=':1', description=':2' WHERE bookmark_id=':3' AND user_id=':4'",
                'select_bkmk' => "SELECT link FROM "._PREFIX."_bookmarks WHERE bookmark_id=':1'"
                )
        );

        /**
        * Prepare our queries
        */
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['save_bkmk']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['select_bkmk']);

        $stmt2->execute($bookmark_id);

        /**
        * The old bookmark link is used for logging purposes only
        */
        $old_bookmark_link = $stmt2->result(0);

        /**
        * Execute the SQL to update the bookmark
        */
        $stmt1->execute($bookmark_name,$bookmark_desc,$bookmark_id,$user_id);

        $this->log->log('BOOKMARK_EDIT', $old_bookmark_link, $bookmark_link, $user_id);
    }

    /**
    * Initiates copy action
    *
    * Copying is a 2 step process. First the files to be copied
    * must be selected and the copy button must be clicked. This
    * method stores a list of the files that were selected in a
    * session variable so that they can be pasted later.
    *
    * @access public
    * @param array $copy_list List of files to be copied
    * @param string $root Root directory from where the items were copied
    * @param integer $user_id User ID of the user copying files
    * @param string $path Path to the file from $root (but excluding the file name)
    */
    public function do_action_copy($copy_list, $root, $user_id, $path) {
        /**
        * To make sure that we dont keep making our array of files to copy larger, we
        * unset the current list of files before making a new list
        */
        unset($_SESSION['file_list']);

        // If the user didnt select anything to copy...
        if (count($copy_list) == 0) {
            // Present them with an error message saying they need to select at least something
            $this->tpl->assign("_MESSAGE", _MYFILES_COPY_FAILURE_NONE);
            $this->tpl->assign("_RETURN_LINK", _MYFILES_COPY_FAILURE_NONE_RETURN_LINK);

            $this->tpl->display('actions_done.tpl');
        } else {
            // Otherwise, add the list of items they wish to copy, to the copy array
            foreach ($copy_list as $key => $var) {
                $test_path = $this->normalize_dir($root . '/' . $path . '/' . $var . '/');

                if (is_dir($test_path)) {
                    $var = $this->normalize_dir('/' . $path . '/' . $var . '/');
                } else {
                    $var = $this->normalize_file('/' . $path . '/' . $var);
                }

                $_SESSION['file_list']['copy'][] = $var;
            }

            // And show them their folder again so they can navigate around to find a paste location
            $this->show_home_folder($root, $user_id, $path, "copy");
        }
    }

    /**
    * Initiates cut action
    *
    * Cutting is a 2 step process. First the files to be cut
    * must be selected and the cut button must be clicked. This
    * method stores a list of the files that were selected in a
    * session variable so that they can be pasted later.
    *
    * @access public
    * @param array $cut_list List of files to be cut
    * @param string $root Root directory from where the items were cut
    * @param integer $user_id User ID of the user copying files
    * @param string $path Path to the file from $root (but excluding the file name)
    */
    public function do_action_cut($cut_list, $root, $user_id, $path) {
        /**
        * To make sure that we dont keep making our array of files to cut larger, we
        * unset the current list of files before making a new list
        */
        unset($_SESSION['file_list']);

        // If the user didnt select anything to cut...
        if (count($cut_list) == 0) {
            // Present them with an error message saying they need to select at least something
            $this->tpl->assign("_MESSAGE", _MYFILES_CUT_FAILURE_NONE);
            $this->tpl->assign("_RETURN_LINK", _MYFILES_CUT_FAILURE_NONE_RETURN_LINK);

            $this->tpl->display('actions_done.tpl');
        } else {
            // Otherwise, add the list of items they with to cut, to the cut array
            foreach ($cut_list as $key => $var) {
                $test_path = $this->normalize_dir($root . '/' . $path . '/' . $var . '/');

                if (is_dir($test_path)) {
                    $var = $this->normalize_dir('/' . $path . '/' . $var . '/');
                } else {
                    $var = $this->normalize_file('/' . $path . '/' . $var);
                }

                $_SESSION['file_list']['cut'][] = $var;
            }

            // And show them their folder again so they can navigate around to find a paste location
            $this->show_home_folder($root, $user_id, $path, "cut");
        }
    }

    /**
    * Wrapper to paste files and folders
    *
    * Part two of either cutting or copying involves pasting
    * the files to a new location. The location that the files
    * will be pasted to is the folder that you are currently in!
    *
    * @access public
    * @param string $home The home folder of the user doing the pasting
    * @param array $paste_list List of items to be pasted. Is the full path minus the home directory
    * @param string $dest Full path to the destination folder to paste items into
    * @param integer $user_id User ID of the user doing the pasting
    */
    public function do_action_paste($home, $paste_list, $dest, $user_id) {
        $keys           = array_keys($paste_list);
        $dest           = $home . '/' . $dest;

        if (is_dir($dest))
            $dest       = $this->normalize_dir($dest);
        else
            $dest       = $this->normalize_file($dest);

        $action         = $keys[0];

        foreach ($paste_list[$action] as $key => $paste_item) {
            $source = $home . '/' . $paste_item;

            if ($action == "copy") {
                if (is_dir($source)) {
                    $source = $this->normalize_dir($source);
                    $this->do_action_copy_paste_dir($home,$paste_item,$source,$dest,$user_id);
                } else {
                    $source = $this->normalize_file($source);
                    $this->do_action_copy_paste_file($home,$paste_item,$source,$dest,$user_id);
                }
            } else {
                if (is_dir($source)) {
                    $source = $this->normalize_dir($source);
                    $this->do_action_cut_paste_dir($home,$paste_item,$source,$dest,$user_id);
                } else {
                    $source = $this->normalize_file($source);
                    $this->do_action_cut_paste_file($home,$paste_item,$source,$dest,$user_id);
                }
            }
            $source = '';
        }
    }

    /**
    * Pastes a file from a cut
    */
    private function do_action_cut_paste_file($home, $paste_item, $source, $dest, $user_id) {
        require_once(ABSPATH.'/extensions/Groups/class.Groups.php');

        $grp = new Groups();
        $grp->__set("db", $this->__get("db"));

        $failure_list           = array();
        $already_exists_list    = array();

        $item = explode("/", $paste_item);

        /**
        * Create a unique name for the uploaded file
        */
        $nr         = 0;
        $tmp_dir    = $item[count($item) - 1];

        while (file_exists($dest . $tmp_dir)) {
            $pieces     = explode("_-_", $tmp_dir);
            $tmp_dir    = $pieces[0] . '_-_' . $nr++;
        }

        $tmpdest    = $dest . '/' . $tmp_dir;

        $group_id   = @$this->get_group_id($source);
        $group_id   = ($group_id) ? $group_id : '-';

        $source     = $this->normalize_file($source);
        $tmpdest    = $this->normalize_file($tmpdest);

        /**
        * The destination can be in the source, but the source
        * cannot be in the destination because this would cause
        * infinite recursion! As such, we need to make a new
        * folder name. If the destination exists, or if the source
        * is in the destination!
        *
        * ex
        * 	from /tmp 	-> / 		= OK
        *	from /tmp/ok 	-> /tmp		= OK
        *	from /tmp	-> /bob		= OK
        *	from /tmp 	-> /tmp/ok 	= NO
        */

        /**
        * Get the permissions of the folder that we are putting the item into
        */
        $to_folder_perms = $this->get_file_perms_entries($dest);

        if (strpos($dest, $source) !== false)
            continue;

        if (false === strpos($dest, $source)) {
            /**
            * The source WAS NOT in the destination
            */

            /**
            * Quotas dont _have_ to be used and can be turned off if not needed
            * therefore check to see if we're even using them. Check to see if
            * there is available space before we would otherwise have to start
            * rolling stuff back
            */
            if ($this->cfg['use_quotas']) {
                /**
                * Get the new permissions of the item we just moved.
                */
                $dest_perms     = $this->get_file_perms_entries($tmpdest);
                $source_perms   = $this->get_file_perms_entries($source);

                $dest_size      = $this->dirsize($tmpdest);

                if ($source_perms['group_id'] == '-' && $dest_perms['group_id'] != '-') {
                    /**
                    * Cutting from user directory to group directory
                    */
                    $user_quotas    = $this->get_user_quotas($source_perms['owner_id']);
                    $group_quotas   = $grp->get_group_quotas($dest_perms['group_id']);

                    $this->adjust_space_decrease($source_perms['owner_id'], $dest_size);
                    $grp->adjust_space_increase($dest_perms['group_id'], $dest_size);
                } else if ($source_perms['group_id'] != '-' && $dest_perms['group_id'] == '-') {
                    /**
                    * Cutting from group directory to user directory
                    */
                    $user_quotas    = $this->get_user_quotas($dest_perms['owner_id']);
                    $group_quotas   = $grp->get_group_quotas($source_perms['group_id']);

                    $this->adjust_space_increase($source_perms['owner_id'], $dest_size);
                    $grp->adjust_space_decrease($dest_perms['group_id'], $dest_size);
                }
            }

            /**
            * To move, the user or group must have write access
            */
            if($this->permission_check($source, $user_id, $group_id, "write")) {
                /**
                * Move the item to the new destination
                */
                $this->mv_dir($source, $tmpdest);

                /**
                * The move method uses the copy method which keeps track
                * of the files it moved and stored them in the $dirs and
                * $files class variables. Those values can be grabbed from
                * the filesystem extension and are the returned (ret) values
                * below
                *
                * Change permissions for files
                */

                /**
                * Update the file_permissions entry of the item we moved.
                * This changes the `file` field to be the new destination
                */
                $this->update_file_perms_entries($source, $tmpdest);

                /**
                * Change the owner and groups of the newly moved file to
                * be that of the folder where we placed the new file
                */
                $this->update_owner_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);
            } else if (is_admin($user_id, $this->ext->__get('extension_id'))){
                // Move the item to the new destination
                $this->mv_dir($source, $tmpdest);

                /**
                * The move method uses the copy method which keeps track
                * of the files it moved and stored them in the $dirs and
                * $files class variables. Those values can be grabbed from
                * the filesystem extension and are the returned (ret) values
                * below
                *
                * Change permissions for files
                */

                /**
                * Update the file_permissions entry of the item we moved.
                * This changes the `file` field to be the new destination
                */
                $this->update_file_perms_entries($source, $tmpdest);

                /**
                * Get the new permissions of the item we just moved.
                * In particular, we want the file_id
                */
                $item_perms = $this->get_file_perms_entries($tmpdest);

                /**
                * Change the owner and groups of the newly moved file to
                * be that of the folder where we placed the new file
                */
                $this->update_owner_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);
            } else {
                continue;
            }
        } else {
            // The destination WAS in the source
            // To move, the user or group must have write access
            if($this->permission_check($source, $user_id, $group_id, "write")) {
                // Move the item to the new destination
                $this->mv_dir($source, $tmpdest);

                /**
                * The move method uses the copy method which keeps track
                * of the files it moved and stored them in the $dirs and
                * $files class variables. Those values can be grabbed from
                * the filesystem extension and are the returned (ret) values
                * below
                *
                * Change permissions for files
                */

                /**
                * Update the file_permissions entry of the item we moved.
                * This changes the `file` field to be the new destination
                */
                $this->update_file_perms_entries($source, $tmpdest);

                /**
                * Get the new permissions of the item we just moved.
                * In particular, we want the file_id
                */
                $item_perms = $this->get_file_perms_entries($tmpdest);

                /**
                * Change the owner and groups of the newly moved file to
                * be that of the folder where we placed the new file
                */
                $this->update_owner_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);
            } else if (is_admin($user_id, $this->ext->__get('extension_id'))){
                // Move the item to the new destination
                $this->mv_dir($source, $tmpdest);

                /**
                * The move method uses the copy method which keeps track
                * of the files it moved and stored them in the $dirs and
                * $files class variables. Those values can be grabbed from
                * the filesystem extension and are the returned (ret) values
                * below
                *
                * Change permissions for files
                */

                /**
                * Update the file_permissions entry of the item we moved.
                * This changes the `file` field to be the new destination
                */
                $this->update_file_perms_entries($source, $tmpdest);

                /**
                * Get the new permissions of the item we just moved.
                * In particular, we want the file_id
                */
                $item_perms = $this->get_file_perms_entries($tmpdest);

                /**
                * Change the owner and groups of the newly moved file to
                * be that of the folder where we placed the new file
                */
                $this->update_owner_entries($ret_dest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);
            } else {
                continue;
            }
        }
    }

    /**
    * Pastes a dir from a cut
    */
    private function do_action_cut_paste_dir($home, $paste_item, $source, $dest, $user_id) {
        require_once(ABSPATH.'/extensions/Groups/class.Groups.php');

        $grp = new Groups();
        $grp->__set("db", $this->__get("db"));

        $failure_list           = array();
        $already_exists_list    = array();

        $this->__set("files", array());
        $this->__set("dirs", array());

        $item = explode("/", $paste_item);

        /**
        * Create a unique name for the uploaded file
        */
        $nr         = 0;
        $tmp_file   = $item[count($item) - 1];

        $parts = explode(".", $tmp_file);
        while (file_exists($dest . $tmp_file)) {
            $pieces     = explode("_-_", $tmp_file);
            $tmp_file   = $pieces[0] . '_-_' . $nr++ . "." . $parts[1];
        }

        $tmpdest    = $dest . '/' . $tmp_file;

        $group_id   = @$this->get_group_id($source);
        $group_id   = ($group_id) ? $group_id : '-';

        $source     = $this->normalize_dir($source);
        $tmpdest    = $this->normalize_dir($tmpdest);

        /**
        * The destination can be in the source, but the source
        * cannot be in the destination because this would cause
        * infinite recursion! As such, we need to make a new
        * folder name. If the destination exists, or if the source
        * is in the destination!
        *
        * ex
        * 	from /tmp 	-> / 		= OK
        *	from /tmp/ok 	-> /tmp		= OK
        *	from /tmp	-> /bob		= OK
        *	from /tmp 	-> /tmp/ok 	= NO
        */

        /**
        * Get the permissions of the folder that we are putting the item into
        */
        $to_folder_perms = $this->get_file_perms_entries($dest);

        if (strpos($dest, $source) !== false)
            continue;

        if (false === strpos($dest, $source)) {
            /**
            * The source WAS NOT in the destination
            */

            /**
            * To move, the user or group must have write access
            */
            if($this->permission_check($source, $user_id, $group_id, "write")) {
                /**
                * Move the item to the new destination
                */
                $this->mv_dir($source, $tmpdest);

                /**
                * Quotas dont _have_ to be used and can be turned off if not needed
                * therefore check to see if we're even using them
                */
                if ($this->cfg['use_quotas']) {
                    /**
                    * Get the new permissions of the item we just moved.
                    */
                    $dest_perms     = $this->get_file_perms_entries($tmpdest);
                    $source_perms   = $this->get_file_perms_entries($source);

                    $dest_size      = $this->dirsize($tmpdest);

                    if ($source_perms['group_id'] == '-' && $dest_perms['group_id'] != '-') {
                        /**
                        * Cutting from user directory to group directory
                        */
                        $user_quotas    = $this->get_user_quotas($source_perms['owner_id']);
                        $group_quotas   = $grp->get_group_quotas($dest_perms['group_id']);
                        $owner_id       = $dest_perms['group_id'];

                        /**
                        * If we are here, then we are operating in a user directory.
                        * Get the quotas for the user
                        */
                        $quotas         = $this->get_user_quotas($owner_id);

                        /**
                        * The above value will contain an empty array if there
                        * was no entry in the users table for whatever reason
                        */
                        if (!empty($quotas)) {
                            /**
                            * Now we check to see if the file will
                            * put the group over quota
                            */
                            if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                                $this->rm_dir($tmpdest);
                            else {
                                /**
                                * Increase the amount of used quota for the group
                                */
                                $this->adjust_space_decrease($source_perms['owner_id'], $dest_size);
                                $grp->adjust_space_increase($dest_perms['group_id'], $dest_size);

                                /**
                                * Update the file_permissions entry of the item we moved.
                                * This changes the `file` field to be the new destination
                                */
                                $this->update_file_perms_entries($source, $tmpdest);

                                /**
                                * Change the owner and groups of the newly moved file to
                                * be that of the folder where we placed the new file
                                */
                                $this->update_owner_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);
                            }
                        } else
                            $this->rm_dir($tmpdest);
                    } else if ($source_perms['group_id'] != '-' && $dest_perms['group_id'] == '-') {
                        /**
                        * Cutting from user directory to group directory
                        */
                        $user_quotas    = $this->get_user_quotas($source_perms['owner_id']);
                        $group_quotas   = $grp->get_group_quotas($dest_perms['group_id']);
                        $owner_id       = $dest_perms['group_id'];

                        /**
                        * If we are here, then we are operating in a user directory.
                        * Get the quotas for the user
                        */
                        $quotas         = $this->get_user_quotas($owner_id);

                        /**
                        * The above value will contain an empty array if there
                        * was no entry in the users table for whatever reason
                        */
                        if (!empty($quotas)) {
                            /**
                            * Now we check to see if the file will
                            * put the group over quota
                            */
                            if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                                $this->rm_dir($tmpdest);
                            else {
                                /**
                                * Increase the amount of used quota for the group
                                */
                                $this->adjust_space_increase($source_perms['owner_id'], $dest_size);
                                $grp->adjust_space_decrease($dest_perms['group_id'], $dest_size);

                                /**
                                * Update the file_permissions entry of the item we moved.
                                * This changes the `file` field to be the new destination
                                */
                                $this->update_file_perms_entries($source, $tmpdest);

                                /**
                                * Change the owner and groups of the newly moved file to
                                * be that of the folder where we placed the new file
                                */
                                $this->update_owner_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);
                            }
                        } else
                            $this->rm_dir($tmpdest);
                    }
                }
            } else if (is_admin($user_id, $this->ext->__get('extension_id'))){
                // Move the item to the new destination
                $this->mv_dir($source, $tmpdest);

                /**
                * Change permissions for directories
                */

                /**
                * Update the file_permissions entry of the item we moved.
                * This changes the `file` field to be the new destination
                */
                $this->update_file_perms_entries($source, $tmpdest);

                /**
                * Get the new permissions of the item we just moved.
                * In particular, we want the file_id
                */
                $item_perms = $this->get_file_perms_entries($tmpdest);

                /**
                * Change the owner and groups of the newly moved file to
                * be that of the folder where we placed the new file
                */
                $this->update_owner_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);
            } else {
                continue;
            }
        } else {
            // The destination WAS in the source
            // To move, the user or group must have write access
            if($this->permission_check($source, $user_id, $group_id, "write")) {
                // Move the item to the new destination
                $this->mv_dir($source, $tmpdest);

                /**
                * Change permissions for directories
                */

                /**
                * Update the file_permissions entry of the item we moved.
                * This changes the `file` field to be the new destination
                */
                $this->update_file_perms_entries($source, $tmpdest);

                /**
                * Get the new permissions of the item we just moved.
                * In particular, we want the file_id
                */
                $item_perms = $this->get_file_perms_entries($tmpdest);

                /**
                * Change the owner and groups of the newly moved file to
                * be that of the folder where we placed the new file
                */
                $this->update_owner_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);
            } else if (is_admin($user_id, $this->ext->__get('extension_id'))){
                // Move the item to the new destination
                $this->mv_dir($source, $tmpdest);

                /**
                * The move method uses the copy method which keeps track
                * of the files it moved and stored them in the $dirs and
                * $files class variables. Those values can be grabbed from
                * the filesystem extension and are the returned (ret) values
                * below
                *
                * Change permissions for files
                */

                /**
                * Update the file_permissions entry of the item we moved.
                * This changes the `file` field to be the new destination
                */
                $this->update_file_perms_entries($source, $tmpdest);

                /**
                * Get the new permissions of the item we just moved.
                * In particular, we want the file_id
                */
                $item_perms = $this->get_file_perms_entries($tmpdest);

                /**
                * Change the owner and groups of the newly moved file to
                * be that of the folder where we placed the new file
                */
                $this->update_owner_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);

                /**
                * Change permissions for directories
                */

                /**
                * Update the file_permissions entry of the item we moved.
                * This changes the `file` field to be the new destination
                */
                $this->update_file_perms_entries($source, $tmpdest);

                /**
                * Get the new permissions of the item we just moved.
                * In particular, we want the file_id
                */
                $item_perms = $this->get_file_perms_entries($tmpdest);

                /**
                * Change the owner and groups of the newly moved file to
                * be that of the folder where we placed the new file
                */
                $this->update_owner_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id']);
            } else {
                continue;
            }
        }
    }

    /**
    * Pastes a file from a copy
    */
    private function do_action_copy_paste_file($home, $paste_item, $source, $dest, $user_id) {
        require_once(ABSPATH.'/extensions/Groups/class.Groups.php');

        $grp = new Groups();
        $grp->__set("db", $this->__get("db"));

        $failure_list           = array();
        $already_exists_list    = array();

        $this->__set("files", array());
        $this->__set("dirs", array());

        $item = explode("/", $paste_item);

        /**
        * Create a unique name for the uploaded file
        */
        $nr         = 0;
        $tmp_dir    = $item[count($item) - 1];

        while (file_exists($dest . $tmp_dir)) {
            $pieces     = explode("_-_", $tmp_dir);
            $tmp_dir    = $pieces[0] . '_-_' . $nr++;
        }

        $tmpdest    = $dest . '/' . $tmp_dir;

        $group_id   = @$this->get_group_id($source);
        $group_id   = ($group_id) ? $group_id : '-';

        $source     = $this->normalize_file($source);
        $tmpdest    = $this->normalize_file($tmpdest);

        /**
        * The destination can be in the source, but the source
        * cannot be in the destination because this would cause
        * infinite recursion! As such, we need to make a new
        * folder name. If the destination exists, or if the source
        * is in the destination!
        *
        * ex
        * 	from /tmp 	-> / 		= OK
        *	from /tmp/ok 	-> /tmp		= OK
        *	from /tmp	-> /bob		= OK
        *	from /tmp 	-> /tmp/ok 	= NO
        */

        /**
        * Get the permissions of the folder that we are putting the item into
        */
        $to_folder_perms = $this->get_file_perms_entries($dest);

        if (false === strpos($dest, $source)) {
            /**
            * The source WAS NOT in the destination
            */
            // To copy, the user or group must have read access
            if($this->permission_check($source, $user_id, $group_id, "read")) {
                // Copy the file to its new location
                $this->copyr($source, $tmpdest);

                /**
                * Get the new permissions of the item we just moved.
                * In particular, we want the file_id
                */
                $item_perms = $this->get_file_perms_entries($source);

                /**
                * Quotas dont _have_ to be used and can be turned off if not needed
                * therefore check to see if we're even using them
                */
                if ($this->cfg['use_quotas']) {
                    $source_perms   = $this->get_file_perms_entries($source);
                    $dest_perms     = $this->get_file_perms_entries($dest);

                    $dest_size      = $this->dirsize($tmpdest);

                    if ($source_perms['group_id'] == '-' && $dest_perms['group_id'] != '-') {
                        /**
                        * Copying from user directory to group directory
                        */
                        $group_quotas   = $grp->get_group_quotas($dest_perms['group_id']);
                        $owner_id       = $dest_perms['group_id'];

                        /**
                        * If we are here, then we are operating in a user directory.
                        * Get the quotas for the user
                        */
                        $quotas         = $this->get_user_quotas($owner_id);

                        /**
                        * The above value will contain an empty array if there
                        * was no entry in the users table for whatever reason
                        */
                        if (!empty($quotas)) {
                            /**
                            * Now we check to see if the file will
                            * put the group over quota
                            */
                            if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                                $this->rm_dir($tmpdest);
                            else {
                                /**
                                * Increase the amount of used quota for the group
                                */
                                $grp->adjust_space_increase($owner_id, $dest_size);
                                $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $item_perms['permissions']);
                            }
                        } else
                            $this->rm_dir($tmpdest);
                    } else if ($source_perms['group_id'] != '-' && $dest_perms['group_id'] == '-') {
                        /**
                        * Copying from group directory to user directory
                        */
                        $user_quotas    = $this->get_user_quotas($dest_perms['owner_id']);
                        $owner_id       = $dest_perms['group_id'];

                        /**
                        * If we are here, then we are operating in a user directory.
                        * Get the quotas for the user
                        */
                        $quotas         = $this->get_user_quotas($owner_id);

                        /**
                        * The above value will contain an empty array if there
                        * was no entry in the users table for whatever reason
                        */
                        if (!empty($quotas)) {
                            /**
                            * Now we check to see if the file will
                            * put the group over quota
                            */
                            if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                                $this->rm_dir($tmpdest);
                            else {
                                /**
                                * Increase the amount of used quota for the group
                                */
                                $this->adjust_space_increase($source_perms['owner_id'], $dest_size);
                                $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $item_perms['permissions']);
                            }
                        } else
                            $this->rm_dir($tmpdest);

                    } else if ($source_perms['group_id'] == '-' && $dest_perms['group_id'] == '-') {
                        /**
                        * Copying from user to user dir
                        */
                        $owner_id   = $dest_perms['owner_id'];

                        /**
                        * If we are here, then we are operating in a user directory.
                        * Get the quotas for the user
                        */
                        $quotas     = $this->get_user_quotas($owner_id);

                        /**
                        * The above value will contain an empty array if there
                        * was no entry in the users table for whatever reason
                        */
                        if (!empty($quotas)) {
                            /**
                            * Now we check to see if the file will
                            * put the group over quota
                            */
                            if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                                $this->rm_dir($tmpdest);
                            else {
                                /**
                                * Increase the amount of used quota for the user
                                */
                                $this->adjust_space_increase($source_perms['owner_id'], $dest_size);
                                $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $item_perms['permissions']);
                            }
                        } else
                            $this->rm_dir($tmpdest);
                    } else if ($source_perms['group_id'] != '-' && $dest_perms['group_id'] != '-') {
                        /**
                        * Copying from group to group dir
                        */
                        $group_quotas   = $grp->get_group_quotas($dest_perms['group_id']);
                        $owner_id       = $dest_perms['group_id'];

                        /**
                        * If we are here, then we are operating in a user directory.
                        * Get the quotas for the user
                        */
                        $quotas         = $this->get_user_quotas($owner_id);
                        /**
                        * The above value will contain an empty array if there
                        * was no entry in the users table for whatever reason
                        */
                        if (!empty($quotas)) {
                            /**
                            * Now we check to see if the file will
                            * put the group over quota
                            */
                            if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                                $this->rm_dir($tmpdest);
                            else {
                                /**
                                * Increase the amount of used quota for the group
                                */
                                $grp->adjust_space_increase($owner_id, $dest_size);
                                $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $item_perms['permissions']);
                            }
                        } else
                            $this->rm_dir($tmpdest);
                    }
                } else {
                    /**
                    * Add new permissions for the newly created file.
                    * We are adding inserting the owner and group id of the containing
                    * folder because otherwise we can be sure of who owns the items
                    * being placed.
                    */
                    $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $item_perms['permissions']);
                }
            } else if (is_admin($user_id, $this->ext->__get('extension_id'))){
                // Copy the file to its new location
                $this->copyr($source, $tmpdest);

                // Get the permissions of the file we were copying
                $item_perms = $this->get_file_perms_entries($source);

                /**
                * Add new permissions for the newly created file.
                * We are adding inserting the owner and group id of the containing
                * folder because otherwise we can be sure of who owns the items
                * being placed.
                */
                $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $item_perms['permissions']);
            } else {
                return;
            }
        } else {
            return;
        }
    }

    /**
    * Pastes a dir from a copy
    */
    private function do_action_copy_paste_dir($home, $paste_item, $source, $dest, $user_id) {
        require_once(ABSPATH.'/extensions/Groups/class.Groups.php');

        $grp = new Groups();
        $grp->__set("db", $this->__get("db"));

        $failure_list           = array();
        $already_exists_list    = array();

        $this->__set("files", array());
        $this->__set("dirs", array());

        $item = explode("/", $paste_item);

        /**
        * Create a unique name for the uploaded file
        */
        $nr         = 0;
        $tmp_file   = $item[count($item) - 1];

        $parts = explode(".", $tmp_file);
        while (file_exists($dest . $tmp_file)) {
            $pieces     = explode("_-_", $tmp_file);
            $tmp_file   = $pieces[0] . '_-_' . $nr++ . "." . $parts[1];
        }

        $tmpdest    = $dest . '/' . $tmp_file;

        $group_id   = @$this->get_group_id($source);
        $group_id   = ($group_id) ? $group_id : '-';

        $source     = $this->normalize_dir($source);
        $tmpdest    = $this->normalize_dir($tmpdest);

        /**
        * The destination can be in the source, but the source
        * cannot be in the destination because this would cause
        * infinite recursion! As such, we need to make a new
        * folder name. If the destination exists, or if the source
        * is in the destination!
        *
        * ex
        * 	from /tmp 	-> / 		= OK
        *	from /tmp/ok 	-> /tmp		= OK
        *	from /tmp	-> /bob		= OK
        *	from /tmp 	-> /tmp/ok 	= NO
        */

        /**
        * Get the permissions of the folder that we are putting the item into
        */
        $to_folder_perms = $this->get_file_perms_entries($dest);

        /**
        * The destination was NOT in the source
        */
        if (FALSE === strpos($source, $dest)) {
            if (FALSE === strpos($dest, $source)) {
                /**
                * The source WAS NOT in the destination
                */
                // To copy, the user or group must have read access
                if($this->permission_check($source, $user_id, $group_id, "read")) {
                    // Copy the file to its new location
                    $this->copyr($source, $tmpdest);

                    // Get the permissions of the file we were copying
                    $perm_data = $this->get_file_perms_entries($source);

                    /**
                    * Add new permissions for the newly created file.
                    * We are adding inserting the owner and group id of the containing
                    * folder because otherwise we can be sure of who owns the items
                    * being placed.
                    */
                    $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $perm_data['permissions']);
                } else if (is_admin($user_id, $this->ext->__get('extension_id'))){
                    // Copy the file to its new location
                    $this->copyr($source, $tmpdest);

                    // Get the permissions of the file we were copying
                    $perm_data = $this->get_file_perms_entries($source);

                    /**
                    * Add new permissions for the newly created file.
                    * We are adding inserting the owner and group id of the containing
                    * folder because otherwise we can be sure of who owns the items
                    * being placed.
                    */
                    $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $perm_data['permissions']);
                } else {
                    continue;
                }
            }
        } else {
            // To copy, the user or group must have read access
            if($this->permission_check($source, $user_id, $group_id, "read")) {
                // Get the permissions of the file we were copying
                $perm_data = $this->get_file_perms_entries($source);

                /**
                * Add new permissions for the newly created file.
                * We are adding inserting the owner and group id of the containing
                * folder because otherwise we can be sure of who owns the items
                * being placed.
                */
                $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $perm_data['permissions']);
            } else if (is_admin($user_id, $this->ext->__get('extension_id'))){
                // Copy the file to its new location
                $this->copyr($source, $tmpdest);

                // Get the permissions of the file we were copying
                $perm_data = $this->get_file_perms_entries($source);

                /**
                * Add new permissions for the newly created file.
                * We are adding inserting the owner and group id of the containing
                * folder because otherwise we can be sure of who owns the items
                * being placed.
                */
                $this->add_file_perms_entries($tmpdest, $to_folder_perms['owner_id'], $to_folder_perms['group_id'], $perm_data['permissions']);
            } else {
                continue;
            }
        }
    }

    /**
    * Create an archive
    *
    * Several different types of archives caan be created
    * by the user. These include zip, tar, gzip and bzip2.
    * The created archive will reside inside the folder
    * where you selected the items to be archived.
    *
    * @access public
    * @param string $archive_name The name of the archive to be created minus the file extension
    * @param string $archive_type The type of archive to make (file extension)
    * @param array $file List of all files to place in the archive. Each includes
    *			the full path to the file minus the root part of the path
    * @param string $root The root portion of the file to be included inside the archive
    */
    public function do_action_archive($archive_name, $archive_type, $file, $root, $paste_path) {
        //Generate a zip archive from a directory and an archive
        require_once ("File/Archive.php");

        $temp       = array();
        $files      = array();
        $tmp_path   = '';
        $paste_path = $this->normalize_dir($paste_path);

        foreach ($file as $key => $item) {
            $tmp_path = $root . '/' . $item;

            if(is_dir($tmp_path))
                $tmp_path = $this->normalize_dir($tmp_path);
            else
                $tmp_path = $this->normalize_file($tmp_path);

            $files[] = $tmp_path;
        }

        $archive = $this->normalize_file($paste_path . $archive_name . "." . $archive_type);

        File_Archive::extract(
            $files,
            File_Archive::toArchive(
                $archive,
                File_Archive::toFiles()
            )
        );

        $to_folder_perms = $this->get_file_perms_entries($paste_path);

        $size       = $this->dirsize($archive);
        $group_id   = @$this->get_group_id($paste_path);
        $group_id   = ($group_id) ? $group_id : '-';
        $owner_id   = $this->get_user_id($paste_path);

        /**
        * If we're using quotas then we need to do some further
        * code stuff; like adjusting the quotas
        */
        if ($this->cfg['use_quotas']) {
            /**
            * Start by checking to see if the file is in a group directory
            */
            if ($group_id != '-') {
                /**
                * If so, then we need to get the current group quotas
                */
                $quotas = $grp->get_group_quotas($group_id);

                /**
                * The above value will contain an empty array if there
                * was no entry in the groups table for whatever reason
                */
                if (!empty($quotas)) {
                    /**
                    * Now we check to see if the uploaded file will
                    * put the group over quota
                    */
                    if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                        $this->rm_dir($archive);
                    else {
                        /**
                        * Decrease the amount of available quota for the group
                        */
                        $grp->adjust_space_increase($group_id, $size);

                        $this->add_file_perms_entries($archive, $owner_id, $group_id, $perms = $this->cfg['file_permissions']);
                    }
                } else
                    $this->rm_dir($archive);
            } else {
                /**
                * If we are here, then we are operating in a user directory.
                * Get the quotas for the user
                */
                $quotas = $this->get_user_quotas($owner_id);

                /**
                * The above value will contain an empty array if there
                * was no entry in the users table for whatever reason
                */
                if (!empty($quotas)) {
                    /**
                    * Now we check to see if the uploaded file will
                    * put the group over quota
                    */
                    if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                        $this->rm_dir($archive);
                    else {
                        /**
                        * Decrease the amount of available quota for the group
                        */
                        $this->adjust_space_increase($owner_id, $size);

                        $this->add_file_perms_entries($archive, $owner_id, $group_id, $perms = $this->cfg['file_permissions']);
                    }
                } else
                    $this->rm_dir($archive);
            }
        } else {
            $this->add_file_perms_entries($archive, $owner_id, $group_id, $perms = $this->cfg['file_permissions']);
        }
    }

    /**
    * Extracts an archive
    *
    * After creating an archive on Flare or creating an archive
    * outside of Flare and uploading it to Flare, you'll probably
    * want to extract the contents. This method can extract all
    * the formats that Flare is able to create.
    * It should be noted that as of PHP 5.0.5 the PEAR package
    * File_Archive is BROKEN and will NOT work for tar.gz files
    * (as well as other archives that have not been tested yet)
    *
    * @access public
    * @param string $root Directory to extract all archived files to
    * @param string|array $archive Archives to be extracted
    * @return bool Returns true on success false on failure
    */
    public function do_extract_archive($root, $archive, $owner_id, $group_id) {
        require_once("File/Archive.php");
        require_once("File/Archive/Reader.php");
        require_once(ABSPATH.'/extensions/Groups/class.Groups.php');

        $grp = new Groups();
        $grp->__set("db", $this->__get("db"));

        /**
        * Normalizing as a file because we may need
        * to append stuff to the foldername in a sec.
        */
        $root   = $this->normalize_file($root);
        $src    = $this->normalize_file($root . '/' . $archive);

        /**
        * Get the extension of the archive. Determines
        * how File_Archive will extract it
        */
        $ext    = substr(strrchr($src, "."), 1);

        /**
        * File_Archive will die if the extension is different from
        * one of the supported ones below. Therefore rename the file
        * to an extension that can be extracted correctly
        */
        if ($ext != "zip" && $ext != "gzip" && $ext != "bzip2" && $ext != "tar")
            $this->do_action_rename($src, strtolower($src));

        /**
        * Get the filename of the archive minus the
        * extension. We are getting this because the
        * way this all is set up to work will be that
        * all items in the archive will be placed in
        * a new folder called the archives name
        */
        $archive_no_ext = substr($archive, 0, strrpos($archive, '.'));

        $test_root      = $this->normalize_dir($root . '/' . $archive_no_ext);

        $item = explode("/", $test_root);
        if (is_dir($test_root)) {
            $nr = 0;
            $tmp_dir = $item[count($item) - 2];

            while (is_dir($this->normalize_dir($root . '/' . $tmp_dir))) {
                $pieces     = explode("_-_", $tmp_dir);
                $tmp_dir    = $pieces[0] . '_-_' . $nr++;
            }

            $test_root = $root . '/' . $tmp_dir;
        }

        $root = $this->normalize_dir($test_root);

        /**
        * Setting the path to null because we already have the
        * full path to the directory we want to create in the
        * $root variable
        */
        if($this->do_new_dir($root, '', $owner_id, $group_id)) {
            /**
            * If true is returned, then the directory wasnt
            * created successfully. Return in this case
            */
            return false;
        }

        switch($ext) {
            case 'zip':
            case 'gzip':
            case 'bzip2':
            case 'tar':
                /**
                * Create reader for the archive sent to us
                */
                $archive            = File_Archive::readArchive($ext,$src);

                /**
                * Read the entire contents of the archive.
                * This is used in a little bit when the
                * archive is finished extracting. We use
                * the file names we've retrieved to add
                * appropriate permissions to the database
                */
                $archive_content    = $archive->getFileList();

                $group_id       = $this->get_group_id($this->normalize_dir($root));
                $owner_id       = $this->get_user_id($this->normalize_dir($root));

                /**
                * Append a forward slash so File_Archive reads
                * our zip as a directory
                */
                $src .= '/';

                /**
                * Extract the archive.
                *
                * File_Archive is such a pile of crap, I have no idea
                * if this line will break in the future or not. Better
                * keep an eye on it.
                */
                File_Archive::extract($src, $root);

                /**
                * We need to do this because File_Archive doesnt get directories
                * with the getFileList method
                */
                $this->ls_dir($root);

                foreach ($archive_content as $key => $val ) {
                    if (is_dir($root . '/' . $val))
                        $item = $this->normalize_dir($root . '/' . $val);
                    else
                        $item = $this->normalize_file($root . '/' . $val);

                    $size = $this->dirsize($item);

                    /**
                    * If we're using quotas then we need to do some further
                    * code stuff; like adjusting the quotas
                    */
                    if ($this->cfg['use_quotas']) {
                        /**
                        * Start by checking to see if the file is in a group directory
                        */
                        if ($group_id != '-') {
                            /**
                            * If so, then we need to get the current group quotas
                            */
                            $quotas = $grp->get_group_quotas($group_id);

                            /**
                            * The above value will contain an empty array if there
                            * was no entry in the groups table for whatever reason
                            */
                            if (!empty($quotas)) {
                                /**
                                * Now we check to see if the uploaded file will
                                * put the group over quota
                                */
                                if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                                    $this->rm_dir($item);
                                else {
                                    /**
                                    * Increase the amount of available quota for the group
                                    */
                                    $grp->adjust_space_decrease($group_id, $size);

                                    if (is_dir($item))
                                        $this->add_file_perms_entries($item, $owner_id, $group_id, $perms = $this->cfg['directory_permissions']);
                                    else {
                                        $this->add_file_perms_entries($item, $owner_id, $group_id, $perms = $this->cfg['file_permissions']);
                                    }
                                }
                            } else {
                                $this->rm_dir($item);
                            }
                        } else {
                            /**
                            * If we are here, then we are operating in a user directory.
                            * Get the quotas for the user
                            */
                            $quotas = $this->get_user_quotas($owner_id);

                            /**
                            * The above value will contain an empty array if there
                            * was no entry in the users table for whatever reason
                            */
                            if (!empty($quotas)) {
                                /**
                                * Now we check to see if the uploaded file will
                                * put the group over quota
                                */
                                if (($size + $quotas['quota_used']) > $quotas['quota_total'])
                                    $this->rm_dir($item);
                                else {
                                    /**
                                    * Increase the amount of available quota for the user
                                    */
                                    $this->adjust_space_decrease($owner_id, $size);

                                    if (is_dir($item))
                                        $this->add_file_perms_entries($item, $owner_id, $group_id, $perms = $this->cfg['directory_permissions']);
                                    else {
                                        $this->add_file_perms_entries($item, $owner_id, $group_id, $perms = $this->cfg['file_permissions']);
                                    }
                                }
                            } else {
                                $this->rm_dir($item);
                            }
                        }
                    } else {
                        if ($group_id != '-') {
                            if (is_dir($item))
                                $this->add_file_perms_entries($item, $owner_id, $group_id, $perms = $this->cfg['directory_permissions']);
                            else {
                                $this->add_file_perms_entries($item, $owner_id, $group_id, $perms = $this->cfg['file_permissions']);
                            }
                        } else {
                            if (is_dir($item))
                                $this->add_file_perms_entries($item, $owner_id, $group_id, $perms = $this->cfg['directory_permissions']);
                            else {
                                $this->add_file_perms_entries($item, $owner_id, $group_id, $perms = $this->cfg['file_permissions']);
                            }

                        }
                    }
                }

                /**
                * File_Archive doesnt report back directories,
                * so add permissions for directories
                */
                foreach ($this->__get("dirs") as $key => $val) {
                    $item = $this->normalize_dir($root . '/' . $val['file']);

                    $this->add_file_perms_entries($item, $owner_id, $group_id, $perms = $this->cfg['directory_permissions']);
                }
                break;
            default:
                return false;
                break;
        }
        return true;
    }

    /**
    * Renames a file or folder
    *
    * This is a typical rename function that can be
    * used to rename files or folders. It operates in
    * basically the same way that cut does, however ONLY
    * THE FIRST item that is selected (if more than one
    * are selected) will be renamed. So dont expect to
    * be able to select a bunch of items and rename all
    * of them.
    *
    * @access public
    * @param string $source The full path to the old file name
    * @param string $dest The full path to the new file name
    */
    public function do_action_rename($source, $dest) {
        $this->mv_dir($source, $dest);
        $this->update_file_perms_entries($source, $dest);
    }

    /**
    * Filters out possible paths to parent directories
    *
    * This is used primarily by the archive creation method
    * to make sure it is not creating an archive that would
    * include parent directories of any kind. This would be
    * a security risk because users may be able to obtain the
    * contents of the master home directory. Also, this could
    * cause a DDoS attack because zipping up the entire master
    * home directory is likely to be several gig in size and
    * this would kill the server trying to do that operation.
    *
    * @access public
    * @param string $file The path (not full) of the selected item being checked
    * @return bool Returns true if parent paths not found, false if they are found
    */
    public function filter_root_paths($file) {
        if ($file == "/")
            return false;
        if ($file == "../")
            return false;
        if ($file == "..")
            return false;
        else
            return true;
    }

    /**
    * Add entries to the file permissions table
    *
    * This will add entries to the file permissions table
    * so that new files and folders can be accessed. If
    * no permissions exist for an item, that item can have
    * no actions taken on it and is basically permanently
    * fixed in placed.
    *
    * @access public
    * @param string $path Full path to the file or folder
    * @param integer $owner_id The user ID of the person who owners the file
    * @param string $group_id The group ID of the group that owns the file, or '-' if the file is a user file
    * @param string $perms The full permission string to be applied to the file or folder.
    */
    public function add_file_perms_entries($path, $owner_id, $group_id, $perms = false) {
        if (!$perms)
            $perms = $this->cfg['directory_permissions'];

        $sql = array(
            "mysql" => array(
                "permissions" => "INSERT INTO "._PREFIX."_file_permissions (`file`,`permissions`,`owner_id`,`group_id`) VALUES (':1',':2',':3',':4')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["permissions"]);
        $stmt1->execute($path,$perms,$owner_id,$group_id);
    }

    /**
    * Removes file permission entries from the file_permissions table
    *
    * Used primarily for the delete methods, this method will remove all
    * traces of a file_permission from the file_permissions table. This
    * action is necessary to maintain a level of order in the system.
    * Dead permissions may foul things up if they are kept around.
    *
    * @access public
    * @param string $path Is the path that you want to search and delete from the table
    */
    public function remove_file_perms_entries($path, $type = 'file') {
        $sql = array(
            "mysql" => array(
                "permissions_file" => "DELETE FROM "._PREFIX."_file_permissions WHERE file=':1'",
                "permissions_dir" => "DELETE FROM "._PREFIX."_file_permissions WHERE file LIKE ':1%'"
            )
        );

        if ($type == 'file') {
            $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["permissions_file"]);
            $stmt1->execute($path);
        } else {
            $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["permissions_dir"]);
            $stmt1->execute($path);
        }
    }


    /**
    * Changes the path that is recorded in the database, to be the new path
    *
    * This method is used to update the file_permissions table to reflect the
    * new location of a file or folder. This method retains any current
    * ownership and permissions on the files.
    *
    * @access public
    * @param string $prev_path The previous path that we will search for to update
    * @param string $new_path The new path that we will update the permissions entry with
    */
    public function update_file_perms_entries($prev_path, $new_path) {
        $sql = array(
            "mysql" => array(
                "permissions" => "UPDATE "._PREFIX."_file_permissions SET `file`=':1' WHERE file=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["permissions"]);
        $stmt1->execute($new_path, $prev_path);
    }

    /**
    * Changes the owner and group id of a given file
    *
    * This method will allow you to change the owner id or the group
    * id of any file
    *
    * @access public
    * @param string $path Fullpath of the file to change owner and group of
    * @param int $owner_id The new owner id that you want to set
    * @param int $group_id The new group id that you want to set
    */
    public function update_owner_entries($path, $owner_id = null, $group_id = null) {
        $sql = array (
            "mysql" => array(
                "owner" => "UPDATE "._PREFIX."_file_permissions SET owner_id=':1' WHERE `file`=':2'",
                "group" => "UPDATE "._PREFIX."_file_permissions SET group_id=':1' WHERE `file`=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['owner']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['group']);

        if(!is_null($owner_id))
            $stmt1->execute($owner_id, $path);

        if(!is_null($group_id))
            $stmt2->execute($group_id, $path);
    }

    /**
    * Returns permission information about the specified item
    *
    * Because some methods may need to retrieve this info to make
    * decisions, or to use it to insert new info, this method is
    * provided. It will retrieve back an array that contains
    * the permissions, owner_id and group_id, indexed by field name
    *
    * @access public
    * @param string $path Fullpath of the item whose info you want to retrieve
    * @return array Return all fields from the file_permissions table for the specified item
    */
    public function get_file_perms_entries($path) {
        $sql = array(
            "mysql" => array(
                "permissions" => "SELECT `file_id`,`permissions`,`owner_id`,`group_id` FROM "._PREFIX."_file_permissions WHERE file=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["permissions"]);
        $stmt1->execute($path);

        return $stmt1->fetch_array();
    }

    /**
    * Decreases the amount of used quota in the users table
    *
    * @access public
    * @param integer $id User ID of the user who is having their quota decreased
    * @param integer $size Size to be decreased by
    */
    public function adjust_space_decrease($id, $size) {
        $sql = array(
            "mysql" => array(
                "select" => "SELECT quota_used FROM "._PREFIX."_users WHERE user_id=':1'",
                "adjust" => "UPDATE "._PREFIX."_users SET quota_used=':1' WHERE user_id=':2'",
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["adjust"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["select"]);

        $stmt2->execute($id);

        $row = $stmt2->fetch_assoc();

        if ($row['quota_used'] < 0)
            $size = 0;
        else
            $size = $row['quota_used'] - $size;

        $stmt1->execute($size, $id);
    }

    /**
    * Increases the amount of used quota in the users table
    *
    * @access public
    * @param integer $id User ID of the user who is having their quota increased
    * @param integer $size Size to be increased by
    */
    public function adjust_space_increase($id, $size) {
        $sql = array(
            "mysql" => array(
                "adjust" => "UPDATE "._PREFIX."_users SET quota_used=(quota_used + :1) WHERE user_id=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["adjust"]);
        $stmt1->execute($size, $id);
    }

    /**
    * Check the permissions on a file or folder
    *
    * This is an important method because it determines whether a
    * file or folder has the necessary permissions that you are
    * seeking. This method accepts an action to check against.
    * This action determines which permissions will be checked on
    * the file or folder.
    *
    * @access public
    * @param string $path The full path to the file or folder
    * @param integer $owner_id The user_id of the person who is checking the permissions or of the item
    * @param string $group_id The group_id of the directory where the file resides
    * @param string action The type of permission to check. {read|write|exec}
    * @return bool True on permission granted, false on permission denied
    */
    public function permission_check($path, $owner_id, $group_id, $action = "read") {
        $sql = array(
            "mysql" => array(
                "permissions"   => "SELECT permissions,owner_id,group_id FROM "._PREFIX."_file_permissions WHERE `file`=':1' LIMIT 1",
                "in_group"      => "SELECT user_id FROM "._PREFIX."_groups WHERE user_id=':1' AND group_id=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["permissions"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["in_group"]);
        $stmt1->execute($path);
        $stmt2->execute($owner_id, $group_id);

        $result = $stmt1->fetch_array();

        if(!function_exists('str_split')){
            function str_split($str,$split_lenght=1){
                $cnt = strlen($str);

                for ($i = 0;$i < $cnt;$i += $split_lenght)
                    $rslt[]= substr($str,$i,$split_lenght);
                return $rslt;
            }
        }

        $permissions = str_split($result['permissions']);

        /**
        * The first check on permissions is the owner permissions.
        * This would be if the owner_id being passed in is the same
        * as the owner_id of the file or folder in the file_permissions
        * table.
        */
        if ($owner_id == $result['owner_id']) {
            /**
            * Switch based on the action, and if no action is specified
            * or if the action is not one of the known actions, deny
            * the user by default
            */
            switch ($action) {
                case "read":
                    return ($permissions[1] == 'r') ? true : false;
                case "write":
                    return ($permissions[2] == 'w') ? true : false;
                case "execute":
                    return ($permissions[3] == 'x') ? true : false;
                default:
                    return false;
            }
        /**
        * Next check would be for the group ownership of the file.
        * So basically since all group folders dont have an integer
        * owner_id, and no user files have a group_id, this check only
        * really applies to group files and folders.
        */
        } else if ($stmt2->num_rows() > 0) {
            switch ($action) {
                case "read":
                    return ($permissions[4] == 'r') ? true : false;
                case "write":
                    return ($permissions[5] == 'w') ? true : false;
                case "execute":
                    return ($permissions[6] == 'x') ? true : false;
                default:
                    return false;
            }
        /**
        * Finally comes the 'everybody else' check. So if the user_id doesnt
        * match, and this isnt a group folder, check permissions to see
        * is the person has access.
        */
        } else {
            switch ($action) {
                case "read":
                    return ($permissions[7] == 'r') ? true : false;
                case "write":
                    return ($permissions[8] == 'w') ? true : false;
                case "execute":
                    return ($permissions[8] == 'x') ? true : false;
                default:
                    return false;
            }
        }
    }

    /**
    * Retrieves the group_id of a file or folder
    *
    * Returns the group_id of a file or folder based on the path that
    * is given and the item's entry in the file_permissions table
    *
    * @access public
    * @param string $path Full path to the file or folder
    * @return string The group_id of the given file or folder
    */
    public function get_group_id($path) {
        $sql = array(
            "mysql" => array(
                "group_id" => "SELECT group_id FROM "._PREFIX."_file_permissions WHERE file=':1' LIMIT 1"
            )
        );

        if (is_dir($path))
            $path = $this->normalize_dir($path);
        else
            $path = $this->normalize_file($path);

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["group_id"]);
        $stmt1->execute($path);

        return $stmt1->result(0);
    }

    /**
    * Retrieves owner_id of a file or folder
    *
    * Returns the owner_id of a file or folder based on the path that
    * is given and the item's entry in the file_permissions table
    *
    * @access public
    * @param string $path Full path to the file or folder
    * @return integer The owner_id of the given file or folder
    */
    public function get_user_id($path) {
        $sql = array(
            "mysql" => array(
                "group_id" => "SELECT owner_id FROM "._PREFIX."_file_permissions WHERE file=':1' LIMIT 1"
            )
        );

        if (is_dir($path))
            $path = $this->normalize_dir($path);
        else
            $path = $this->normalize_file($path);

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["group_id"]);
        $stmt1->execute($path);

        return $stmt1->result(0);
    }

    /**
    * Returns the current quota_total and quota_used
    *
    * The users total quota and how much has been used is needed when
    * determining if a write operation should be allowed on a user
    * directory. This method will return an array containing those two
    * value.
    *
    * @param string $user_id User ID of the user you want the quota info for
    * @return array Array containing requested information. Array will be empty if user doesnt exist
    */
    public function get_user_quotas($user_id) {
        $sql = array (
            "mysql" => array(
                "quotas" => "SELECT quota_total, quota_used FROM "._PREFIX."_users WHERE user_id = ':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['quotas']);

        $stmt1->execute($user_id);

        if ($stmt1->num_rows() > 0) {
            $data = $stmt1->fetch_array();

            $result = array(
                'quota_total'   => $data['quota_total'],
                'quota_used'    => $data['quota_used']
            );

            return $result;
        } else {
            return array();
        }
    }
}

?>
