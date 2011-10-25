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

require_once (ABSPATH.'/extensions/Filesystem/class.Filesystem.php');

/**
* Filesystem administration tools
*
* This class provides tools that allow administration
* of the filesystem and various aspects of it such as
* file permissions.
*
* @package Filesystem
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class FilesystemAdmin extends Filesystem {
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
    * Creates an instance of FilesystemAdmin class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided.
    *
    * @access public
    */
    public function __construct() {

    }

    /**
    * Returns the value of a class variable
    *
    * Given a class variable name, this method will return the
    * value associated with the name.
    *
    * @access public
    * @param string $key class variable name
    * @return misc $key value stored in variable
    */
    public function __get( $key ) {
        return isset( $this->$key ) ? $this->$key : NULL;
    }

    /**
    * Sets the value of a class variable
    *
    * Given a class variable name and the value that you wish to
    * store in that variable, this method will store the supplied
    * value in the named variable
    *
    * @access public
    */
    public function __set( $key, $value ) {
        $this->$key = $value;
    }

    /**
    * Shows settings configuration page
    *
    * From this page, the admin will be able to
    * change the various settings for the extension.
    *
    * @access public
    * @return bool Always returns true
    */
    public function show_settings() {
        $config = array();
        $sql = array (
            'mysql' => array (
                'config' => "SELECT * FROM "._PREFIX."_config WHERE extension_id=':1'"
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);

        $stmt1->execute($this->ext->__get('extension_id'));

        while ($row = $stmt1->fetch_array()) {
            $config[$row['name']] = array (
                'name' 	=> $row['name'],
                'desc' 	=> $row['description'],
                'value'	=> urldecode($row['value'])
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
//            '_ACCTS_ADM_ADD_ACCT' => _ACCTS_ADM_ADD_ACCT,
            '_SETTINGS'           => _SETTINGS));

        // Assign dynamic content
        $this->tpl->assign('CONFIG', $config);

        // Display the page
        $this->tpl->display('filesystem_config.tpl');
    }

    /**
    * Save extension settings
    *
    * After making any changes, the settings must
    * be saved back to the database before they will
    * take effect.
    *
    * @access public
    * @return bool Always returns true
    */
    public function do_save_settings($settings) {
        $sql = array (
            'mysql' => array (
                "config" => "UPDATE "._PREFIX."_config SET value=':1' WHERE name=':2' AND extension_id=':3'"
            )
        );

        // Prepare SQL for running
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);

        // For each setting name->value pair we received...
        foreach($settings as $key => $val) {
            // Execute the SQL to update that value
            $stmt1->execute(urlencode($val),$key,$this->ext->__get('extension_id'));
        }
    }

    /**
    * Displays file permission editing page
    *
    * File permissions are a feature of Flare that helps
    * us deal with the fact that the webserver is incapable
    * of impersonating different users. These permissions
    * exist entirely in Flare and are not related at all to
    * the actual filesystem permissions of the system.
    *
    * @access public
    * @param string $root Root directory of the folder we are going to show
    * @param string $path Path (minus root) to the folder the admin is currently sitting in
    * @return bool Always returns true
    */
    public function show_permissions($root, $path = "") {
        // Variable declaration
        $dir 		= $this->strip_bad_navigation($path);
        $dir_temp	= array();
        $files_temp	= array();
        $sql 		= array (
                    "mysql"	=> array (
                        'permissions' => "SELECT `file_id`,`file`,`username`,`group_name`,`permissions` FROM flare_file_permissions AS ffp LEFT JOIN flare_users AS fu ON ffp.owner_id = fu.user_id LEFT JOIN flare_group_info AS fgi ON ffp.group_id = fgi.group_id WHERE `file` = ':1'"
                    )
                );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['permissions']);

        // Set the users path to whatever they specified, and
        // to their home root if they didnt specify anything
        $path = ($path == "/") ? '' : $path;

        // Prepare bookmark SQL
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['permissions']);

        // Get the contents of the directory
        $this->ls_dir($root, $dir);

        $files_temp = $this->__get("files");
        $dirs_temp = $this->__get("dirs");

        /**
        * Need to get the permissions information in addition
        * to the normal information returned by ls_dir
        */
        foreach ($files_temp as $key => $file) {
            $item = $this->normalize_file($root . '/' . $path . '/' . $file['file']);
            $stmt1->execute($item);

            if ($stmt1->num_rows() == 0) {
                $id = md5(rand(0,500000));
                $info = array(
                    'id'		=> $id,
                    'owner'		=> '-',
                    'group'		=> '-',
                    'type'		=> '-',
                    'o_read'	=> '-',
                    'o_write'	=> '-',
                    'o_exec'	=> '-',
                    'g_read'	=> '-',
                    'g_write'	=> '-',
                    'g_exec'	=> '-',
                    'e_read'	=> '-',
                    'e_write'	=> '-',
                    'e_exec'	=> '-'
                );
            } else {
                $result = $stmt1->fetch_array();
                $perm = str_split($result['permissions']);
                $info = array (
                    'id'		=> $result['file_id'],
                    'owner'		=> $result['username'],
                    'group'		=> ($result['group_name']) ? $result['group_name'] : '-',
                    'type'		=> $perm[0],
                    'o_read'	=> $perm[1],
                    'o_write'	=> $perm[2],
                    'o_exec'	=> $perm[3],
                    'g_read'	=> $perm[4],
                    'g_write'	=> $perm[5],
                    'g_exec'	=> $perm[6],
                    'e_read'	=> $perm[7],
                    'e_write'	=> $perm[8],
                    'e_exec'	=> $perm[9]
                );
            }

            $files[] = array (
                'root'		=> $file['root'],
                'file'		=> $file['file'],
                'size'		=> $file['size'],
                'date'		=> $file['date'],
                'icon'		=> $file['icon'],
                'id'		=> $info['id'],
                'owner'		=> $info['owner'],
                'group'		=> $info['group'],
                'type'		=> $info['type'],
                'o_read'	=> $info['o_read'],
                'o_write'	=> $info['o_write'],
                'o_exec'	=> $info['o_exec'],
                'g_read'	=> $info['g_read'],
                'g_write'	=> $info['g_write'],
                'g_exec'	=> $info['g_exec'],
                'e_read'	=> $info['e_read'],
                'e_write'	=> $info['e_write'],
                'e_exec'	=> $info['e_exec']
            );
        }

        foreach ($dirs_temp as $key => $dir) {
            if ($dir['disp'] == '../')
                $item = $this->normalize_dir($root . '/' . $path . '/');
            else
                $item = $this->normalize_dir($root . '/' . $path . '/' . $dir['file']);

            $stmt1->execute($item);

            if ($stmt1->num_rows() == 0) {
                $id = md5(rand(0,500000));
                $info = array(
                    'id'		=> $id,
                    'owner'		=> '-',
                    'group'		=> '-',
                    'type'		=> 'd',
                    'o_read'	=> '-',
                    'o_write'	=> '-',
                    'o_exec'	=> '-',
                    'g_read'	=> '-',
                    'g_write'	=> '-',
                    'g_exec'	=> '-',
                    'e_read'	=> '-',
                    'e_write'	=> '-',
                    'e_exec'	=> '-'
                );
            } else {
                $result = $stmt1->fetch_array();
                $perm = str_split($result['permissions']);
                $info = array(
                    'id'		=> $result['file_id'],
                    'owner'		=> $result['username'],
                    'group'		=> ($result['group_name']) ? $result['group_name'] : '-',
                    'type'		=> $perm[0],
                    'o_read'	=> $perm[1],
                    'o_write'	=> $perm[2],
                    'o_exec'	=> $perm[3],
                    'g_read'	=> $perm[4],
                    'g_write'	=> $perm[5],
                    'g_exec'	=> $perm[6],
                    'e_read'	=> $perm[7],
                    'e_write'	=> $perm[8],
                    'e_exec'	=> $perm[9],
                );
            }

            $dirs[] = array (
                'root' 		=> $this->normalize_dir($dir['root']),
                'file' 		=> $this->normalize_dir($dir['file']),
                'disp' 		=> $dir['disp'],
                'date'		=> $dir['date'],
                'id'		=> $info['id'],
                'owner'		=> $info['owner'],
                'group'		=> $info['group'],
                'type'		=> $info['type'],
                'o_read'	=> $info['o_read'],
                'o_write'	=> $info['o_write'],
                'o_exec'	=> $info['o_exec'],
                'g_read'	=> $info['g_read'],
                'g_write'	=> $info['g_write'],
                'g_exec'	=> $info['g_exec'],
                'e_read'	=> $info['e_read'],
                'e_write'	=> $info['e_write'],
                'e_exec'	=> $info['e_exec']
            );
        }

        if ($this->__get("error")) {
            // If we got an error while listing the directory, then the dir doesnt exist
            $this->tpl->assign('_MESSAGE', _MYFILES_DIR_NO_EXIST);
            $this->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Filesystem&amp;action=show_settings'>Return to Filesystem Admin</a>");

            $this->tpl->display('actions_done.tpl');
        } else {
            // Assign language constants
            $this->tpl->assign(array(
                '_FILE'			=> _FILE,
                '_SIZE'			=> _SIZE,
                '_DATE_MODIFIED'	=> _DATE_MODIFIED,
                '_OWNER'		=> _OWNER,
                '_GROUP'		=> _GROUP,
                '_SETTINGS'		=> _SETTINGS,
                '_PERMISSIONS_WELCOME' 	=> _PERMISSIONS_WELCOME,
                '_MYFILES_FOLDER_INFO'	=> _MYFILES_FOLDER_INFO,
                '_MYFILES_HOME'		=> _MYFILES_HOME,
                'JS_INC'		=> 'filesystem_permissions.tpl'));

            // Assign dynamic content and other vars
            $this->tpl->assign(array(
                'FILES' 	=> $files,
                'DIRECTORIES' 	=> $dirs,
                'ROOT' 		=> $path,
            ));

            // Display page
            $this->tpl->display('filesystem_permissions.tpl');
        }

        return true;
    }

    /**
    * Saves changed permissions back to database
    *
    * The admin can change the file permissions on a file or
    * directory, but these changes need to be saved back to the
    * database before they take effect. This method does this.
    *
    * @access public
    * @param integer $item_id ID of the file or folder as registered in the database
    * @param string $permissions Full permission string to update the file to
    * @return bool Always returns true
    */
    public function do_update_permissions($item_id, $permissions) {
        $sql = array (
            "mysql" => array(
                'update_perms' => "UPDATE "._PREFIX."_file_permissions SET permissions=':1' WHERE file_id=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['update_perms']);

        $stmt1->execute($permissions, $item_id);

        return true;
    }

    /**
    * Adds new file permissions to the database
    *
    * If a file has no permissions then there is noway
    * that the user can do anything with it and it
    * basically just sits there, unable to be used in
    * any way. Therefore this method is used to add a
    * permission set to the item so that the user can
    * fiddle with it as they see fit.
    *
    * @access public
    * @param string $path Full path to the item to be added
    * @param string $permissions Full permission string to apply to the new item
    * @param integer $owner_id ID of the owner being assigned to the new item
    * @param string $group_id Group ID of the group being assigned to the new item
    * @return bool Always returns true
    */
    public function do_add_permissions($path, $permissions, $owner_id, $group_id) {
        $sql = array(
            "mysql" => array(
                'insert_perms' => "INSERT INTO "._PREFIX."_file_permissions (`file`,`permissions`,`owner_id`,`group_id`) VALUES (':1',':2',':3',':4')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['insert_perms']);

        $stmt1->execute($path, $permissions, $owner_id, $group_id);

        return true;
    }

    /**
    * Get user and group info about an item
    *
    * This method will return the known user and group
    * for a particular file. It will also return their ids
    *
    * @access public
    * @param integer $item_id The ID of the item as known by the database
    * @return array Array containing at least the user and group ids and their names
    */
    public function get_owner_info($item_id) {
        $sql = array(
            "mysql" => array(
                "owner_info" => "SELECT `file`,`owner_id`,fgi.group_id,`username`,`group_name` FROM "._PREFIX."_file_permissions AS ffp LEFT JOIN "._PREFIX."_users AS fu ON fu.user_id = ffp.owner_id LEFT JOIN "._PREFIX."_group_info AS fgi ON fgi.group_id = ffp.group_id WHERE ffp.file_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["owner_info"]);
        $stmt1->execute($item_id);

        if ($stmt1->num_rows() == 0)
            return array();

        $result = $stmt1->fetch_array();

        $to_return = array(
            'file_id'	=> $item_id,
            'file'		=> $result['file'],
            'user_id'	=> $result['owner_id'],
            'username'	=> $result['username'],
            'group_id'	=> $result['group_id'],
            'group_name'	=> $result['group_name']
        );

        return $to_return;
    }

    /**
    * Get list of all known users
    *
    * Retrieves a list of all known users from the users table
    *
    * @access public
    * @return array Array containing user_id and username of all known users
    */
    public function get_all_users() {
        $sql = array(
            "mysql" => array(
                "all_users" => "SELECT user_id, username FROM "._PREFIX."_users ORDER BY username ASC"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["all_users"]);

        $stmt1->execute();

        while ($row = $stmt1->fetch_array()) {
            $data[] = array(
                'user_id' 	=> $row['user_id'],
                'username'	=> $row['username']
            );
        }

        return $data;
    }

    /**
    * Get list of all known groups
    *
    * Retrieves a list of all known groups from the group_info table
    *
    * @access public
    * @return array Array containing group_id and group_name of all known groups
    */
    public function get_all_groups() {
        $sql = array(
            "mysql" => array(
                "all_groups" => "SELECT group_id, group_name FROM "._PREFIX."_group_info ORDER BY group_name ASC"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["all_groups"]);

        $stmt1->execute();

        while ($row = $stmt1->fetch_array()) {
            $data[] = array(
                'group_id'	=> $row['group_id'],
                'group_name'	=> $row['group_name']
            );
        }

        return $data;
    }

    /**
    * Change a file or folders owner
    *
    * When adding completely new permissions (from the admin's
    * point of view), the new file will be owned by the admin
    * The admin will therefore need to change the ownership to
    * be that of the user or group who should actually own the
    * item. This allows for that to happen
    *
    * @access public
    * @param integer $item_id ID of the item as stored in the file_permissions table
    * @param integer $owner_id User ID to change the owner of the file to
    * @param string $group_id Group ID to change the group associated with the file to
    */
    public function do_change_owner($item_id, $owner_id, $group_id) {
        $sql = array(
            "mysql" => array(
                "update_owners" => "UPDATE "._PREFIX."_file_permissions SET owner_id=':1', group_id=':2' WHERE file_id=':3'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["update_owners"]);

        $stmt1->execute($owner_id, $group_id, $item_id);
    }

    /**
    * Changes an extensions visibility
    *
    * This method will switch the visibility of the
    * extensions link when viewed by normal users
    *
    * @access public
    * @param integer $visible Value to change visibility to
    */
    public function do_change_visibility($visible) {
        $sql = array(
            "mysql" => array(
                "visible" => "UPDATE "._PREFIX."_extensions SET visible=':1' WHERE extension_id=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["visible"]);
        $stmt1->execute($visible, $this->ext->__get("extension_id"));
    }

}

?>
