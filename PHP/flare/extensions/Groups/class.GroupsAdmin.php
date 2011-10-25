<?php
/**
* @package Groups
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

require_once (ABSPATH.'/extensions/Groups/class.Groups.php');

/**
* Group Administration tools
*
* Contains all the tools necessary to configure the Groups
* extension from the administrative interface of Flare.
* This class extends the methods of the Group class used
* by the normal user.
*
* @package Groups
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class GroupsAdmin extends Groups {
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
    * Object used for working with the filesystem
    *
    * @access public
    * @var object
    */
    public $fs;

    /**
    * Creates an instance of GroupsAdmin class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic, blank one is provided.
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
    * Displays a list of all created groups
    *
    * All known groups are listed using this method. This is the
    * default behavior of the groups admin page for Flare. From
    * here, the admin can edit or delete any number of groups. They
    * can also add new groups and specify the new admin of that group
    *
    * @access public
    */
    public function show_all_groups() {
        require_once(ABSPATH.'/extensions/Filesystem/class.Filesystem.php');

        // Used to hold the list of all groups
        $group_list = array();

        // Used to hold a count of the number of members in the group
        $user_count = 0;

        // Contains all the SQL that will be executed by this method
        $sql = array (
            'mysql' => array (
                "all_groups" => "SELECT g.group_id,g.group_name,g.group_type,g.admin_id,u.username,g.creation_date,g.home_dir,g.quota_total,g.quota_used FROM "._PREFIX."_group_info AS g LEFT JOIN "._PREFIX."_users AS u ON g.admin_id=u.user_id ORDER BY g.group_name ASC",
                "member_count" => "SELECT COUNT(user_id) FROM "._PREFIX."_groups WHERE group_id=':1'",
                "group_path" => "SELECT username, group_name FROM "._PREFIX."_group_info LEFT JOIN "._PREFIX."_users ON admin_id = user_id WHERE group_id=':1'"
            )
        );

        // Prepare all SQL for running
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["all_groups"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["member_count"]);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]["group_path"]);

        // Run SQL to pull all group information
        $stmt1->execute();

        // For each row (distinct group) we get back
        while ($row = $stmt1->fetch_array()) {
            // Store the group_id in a local var
            $group_id = $row['group_id'];

            // Run the SQL that will retrieve a count of the number of members in the group
            $stmt2->execute($group_id);

            // Store the member count in a local var
            $user_count = $stmt2->result(0);

            /**
            * Run SQL that will get us enough info to build a clickable path straight to
            * the group directory, assuming that the admin's home_dir is in the path of
            * the users dir we will be hopping into
            */
            $stmt3->execute($group_id);
            $group_path = $stmt3->fetch_array();

            // Push all the info about the group to the group_list
            $group_list[] = array (
                'group_id' 	=> $row['group_id'],
                'group_name' 	=> $row['group_name'],
                'group_type'	=> $row['group_type'],
                'admin_id' 	=> $row['admin_id'],
                'username' 	=> $row['username'],
                'creation_date' => $row['creation_date'],
                'home_dir' 	=> $row['home_dir'],
                'truncated_dir'	=> '/' . $group_path['username'] . '/' . "groups" . '/' . $group_path['group_name'] . '/',
                'quota_total' 	=> Filesystem::format_space($row['quota_total']),
                'quota_free'	=> Filesystem::format_space($row['quota_total'] - $row['quota_used']),
                'count' 	=> $user_count
            );

            // Reset all temp vars
            $group_id 	= 0;
            $user_count 	= 0;
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_WITH_SELECTED'		=> _WITH_SELECTED,
            '_GROUPS_CREATE'		=> _GROUPS_CREATE,
            '_GROUPS_ADM_SHOW_ALL_GROUPS'	=> _GROUPS_ADM_SHOW_ALL_GROUPS,
            '_GROUPS_GROUP_NAME'		=> _GROUPS_GROUP_NAME,
            '_GROUPS_ADMIN'			=> _GROUPS_ADMIN,
            '_GROUPS_CREATION_DATE'		=> _GROUPS_CREATION_DATE,
            '_GROUPS_GROUPDIR'		=> _GROUPS_GROUPDIR,
            '_GROUPS_QUOTA_TOTAL'		=> _GROUPS_QUOTA_TOTAL,
            '_GROUPS_QUOTA_FREE'		=> _GROUPS_QUOTA_FREE,
            '_GROUPS_NUM_MEMBERS'		=> _GROUPS_NUM_MEMBERS,
            '_GROUPS_NO_GROUPS_EXIST'	=> _GROUPS_NO_GROUPS_EXIST,
            '_GROUPS_CHANGE_GROUPS'		=> _GROUPS_CHANGE_GROUPS,
            '_GROUPS_DELETE_GROUPS'		=> _GROUPS_DELETE_GROUPS,
            '_NO_ACTIONS'			=> _NO_ACTIONS));

        // Assign dynamic content
        $this->tpl->assign('GROUP_LIST', $group_list);
        $this->tpl->assign('JS_INC', "groups_main.tpl");

        // Display the page
        $this->tpl->display('groups_main.tpl');
    }

    /**
    * Display form allowing creation of new group
    *
    * Used to create any group for any user of the Flare system
    * the design of this form is different from the userland
    * function of the same name. This method allows the admin
    * to specify more options for the group than the normal user
    * is allowed to specify
    *
    * @access public
    */
    public function show_add_group() {
        $member_list = array();
        $admin_name = $_SESSION['username'];

        // SQL code that we'll be executing in this function
        $sql = array (
            "mysql"	=> array(
                "member_list" => "SELECT user_id, username FROM "._PREFIX."_users ORDER BY username ASC"
                ),
        );

        // Prepare the SQL
        $stmt = $this->db->prepare($sql[_DBSYSTEM]['member_list']);

        // Execute SQL
        $stmt->execute();

        // Create the list of members for inclusion in multi-select box
        while ($row = $stmt->fetch_assoc()) {
            $member_list[] = array($row['user_id'], $row['username']);
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_GROUPS_CREATE_WELCOME'	=> _GROUPS_CREATE_WELCOME,
            '_GROUPS_LIST'			=> _GROUPS_LIST,
            '_GROUPS_CREATE'		=> _GROUPS_CREATE,
            '_GROUPS_INVITE_USER'		=> _GROUPS_INVITE_USER,
            '_GROUPS_GROUP_NAME'		=> _GROUPS_GROUP_NAME,
            '_GROUPS_INITIAL_MEMBERS'	=> _GROUPS_INITIAL_MEMBERS,
            '_GROUPS_INITIAL_MEMBERS_FYI'	=> _GROUPS_INITIAL_MEMBERS_FYI,
            '_GROUPS_CHECK_AVAIL'		=> _GROUPS_CHECK_AVAIL,
            '_GROUPS_NEW_GROUP'		=> _GROUPS_NEW_GROUP,
            '_GROUPS_ADMIN'			=> _GROUPS_ADMIN,
            '_RESET_FORM'			=> _RESET_FORM));

        // Assign dynamic content and other vars
        $this->tpl->assign('MEMBER_LIST', $member_list);

        // Assign external javascript code
        $this->tpl->assign('JS_INC', 'groups_add.tpl');

        // Display page
        $this->tpl->display('groups_add.tpl');
    }

    /**
    * Delete a list of groups from the system
    *
    * This method is not restricted by the user's admin_id.
    * Instead, it can be used to delete any group, given a
    * list of group ids and admin ids
    *
    * @access public
    */
    public function admin_delete_groups($groups) {
        $sql = array(
            "mysql" => array(
                "admin" => "SELECT admin_id FROM "._PREFIX."_group_info WHERE group_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["admin"]);

        foreach ($groups as $key => $group_id) {
            $stmt1->execute($group_id);

            $result = $stmt1->fetch_array();
            $admin_id = $result['admin_id'];

            $this->do_delete_groups($admin_id, $group_id);
        }
    }

    /**
    * Displays a list of all group members for specified group
    *
    * For the purposes of knowing who's where in Flare, this
    * method is provided for the Groups admin area so that
    * admins can know who is in a specified group. This
    * feature is accessed by clicking the hyperlinked # of
    * group members item on the Groups admin main page
    *
    * @var string group_id The group ID you wish to find members for
    * @returns array List of group member names. empty array if no members exist
    * @access public
    */
    public function show_group_members($group_id) {
        $members = array();
        $sql = array(
            "mysql" => array(
                "members" => "SELECT usr.username FROM "._PREFIX."_groups grps LEFT JOIN "._PREFIX."_users usr ON usr.user_id = grps.user_id WHERE grps.group_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["members"]);

        $stmt1->execute($group_id);

        if ($stmt1->num_rows() > 0) {
            while ($row = $stmt1->fetch_array()) {
                $members[] = array(
                    'username' => $row['username']
                );
            }

            return $members;
        } else
            return $members;
    }

    /**
    * Displays the group edit page
    *
    * The admin is allowed to edit a couple more fields
    * than the normal user. Also, they must get the
    * current admin_id for the group because it is not
    * the user_id stored in the session as it would be
    * for normal users.
    *
    * @access public
    * @param string $group_id Group ID of the group being edited
    */
    public function show_edit_group($group_id) {
        $sql = array(
            "mysql" => array(
                "select_admin" => "SELECT admin_id FROM "._PREFIX."_group_info WHERE group_id = ':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["select_admin"]);

        $stmt1->execute($group_id);
        $result = $stmt1->fetch_array();
        $admin_id = $result[0];

        parent::show_edit_group($group_id, $admin_id);
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
            '_GROUPS_ADM_SHOW_ALL_GROUPS'	=> _GROUPS_ADM_SHOW_ALL_GROUPS,
            '_SETTINGS'			=> _SETTINGS));

        // Assign dynamic content
        $this->tpl->assign('CONFIG', $config);

        // Display the page
        $this->tpl->display('groups_config.tpl');
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
}

?>
