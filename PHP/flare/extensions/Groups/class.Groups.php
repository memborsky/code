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

/**
* Group userland tools
*
* Contains all the tools available for use by the end user
* of the Flare system. This class contains the base methods
* used by the normal user and the accompanying admin class.
*
* @package Groups
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Groups {
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
    * Holds filesystem object for when operations need to be done on the filesystem
    *
    * @access public
    * @var object
    */
    public $fs;

    /**
    * Used to notify any listening code if an error has occured
    *
    * @access public
    * @var bool
    */
    public $error;

    /**
    * Creates an instance of Groups class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided. This constructor begins by setting
    * the error var to FALSE.
    *
    * @access public
    */
    public function __construct() {
        $this->__set("error", FALSE);
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
    * Displays list of groups that a user is associated with
    *
    * This method will display both a list of groups that the user
    * is an admin of and a list of groups that the user is a member
    * of
    *
    * @access public
    * @param integer $admin_id user_id of the person viewing the group list
    */
    public function show_groups($admin_id) {
        $groups_not_admin 	= array();
        $groups_admin 		= array();

        $sql = array (
            "mysql"	=> array(	"groups_admin" => 	"SELECT `group_id`,`group_name`,`creation_date`
                                    FROM "._PREFIX."_group_info WHERE admin_id=':1'",
                        "groups_not_admin" =>	"SELECT fgi.group_id,fgi.group_name,fgi.home_dir
                                    FROM `flare_groups` AS fg LEFT JOIN flare_group_info AS fgi
                                    ON fg.group_id=fgi.group_id WHERE fg.user_id=':1' AND fgi.admin_id!=':2'"
                ),
        );

        $stmt 	= $this->db->prepare($sql[_DBSYSTEM]['groups_admin']);
        $stmt2 	= $this->db->prepare($sql[_DBSYSTEM]['groups_not_admin']);

        $stmt->execute($admin_id);
        $stmt2->execute($admin_id,$admin_id);

        while ($row = $stmt->fetch_array()) {
            $groups_admin[] = array (
                "group_id" 	=> $row['group_id'],
                "group_name" 	=> $row['group_name'],
                "creation_date"	=> $row['creation_date']);
        }

        while ($row = $stmt2->fetch_array()) {
            $groups_not_admin[] = array (
                "group_id"	=> $row['group_id'],
                "group_name"	=> $row['group_name'],
                "home_dir"	=> $row['home_dir']);
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_GROUPS_WELCOME'		=> _GROUPS_WELCOME,
            '_GROUPS_MINE'			=> _GROUPS_MINE,
            '_GROUPS_IN'			=> _GROUPS_IN,
            '_GROUPS_DELETE_GROUPS'		=> _GROUPS_DELETE_GROUPS,
            '_GROUPS_EDIT_GROUPS'		=> _GROUPS_EDIT_GROUPS,
            '_GROUPS_LEAVE_GROUPS'		=> _GROUPS_LEAVE_GROUPS,
            '_GROUPS_CREATE'		=> _GROUPS_CREATE,
            '_GROUPS_LIST'			=> _GROUPS_LIST,
            '_GROUPS_INVITE_USER'		=> _GROUPS_INVITE_USER,
            '_GROUPS_ACCEPT_INVITE'		=> _GROUPS_ACCEPT_INVITE,
            '_GROUPS_DECLINE_INVITE'	=> _GROUPS_DECLINE_INVITE,
            '_GROUPS_RECEIVED_INVITE_FROM'	=> _GROUPS_RECEIVED_INVITE_FROM,
            '_GROUPS_TO_JOIN'		=> _GROUPS_TO_JOIN,
            '_GROUPS_REQUEST_JOIN'		=> _GROUPS_REQUEST_JOIN,
            '_GROUPS_RETRACT_REQUEST'	=> _GROUPS_RETRACT_REQUEST,
            '_WITH_SELECTED'		=> _WITH_SELECTED,
            '_NO_ACTIONS'			=> _NO_ACTIONS,
            '_NONE'				=> _NONE));

        // Assign dynamic content
        $this->tpl->assign(array(
            'GROUPS_ADMIN'		=> $groups_admin,
            'GROUPS_NOT_ADMIN'	=> $groups_not_admin,
            'GROUP_DIR'		=> $this->cfg['group_dir'],
            'JS_INC'		=> "groups_main.tpl"));

        $this->show_invites_pending($admin_id);
        $this->show_requests_pending($admin_id);

        // Display page
        $this->tpl->display('groups_main.tpl');

    }

    /**
    * Display page allowing group creation
    *
    * This method differs from the similarly named admin
    * method in that this method does not display the
    * same quantity of information. The user is restricted
    * to only being able to set properties that will
    * effect their account and not the accounts of others
    *
    * @access public
    */
    public function show_create_group($admin_id) {
        require_once(ABSPATH.'/extensions/Filesystem/class.Filesystem.php');
        $member_list = array();

        // SQL code that we'll be executing in this function
        $sql = array (
            "mysql"	=> array(
                "member_list" => "SELECT user_id, username FROM "._PREFIX."_users WHERE public='1' AND user_id != ':1' ORDER BY username ASC",
                "user_quotas" => "SELECT quota_used, quota_total FROM "._PREFIX."_users WHERE user_id = ':1'"
                ),
        );

        // Prepare the SQL
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['member_list']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['user_quotas']);

        // Execute SQL
        $stmt1->execute($admin_id);
        $stmt2->execute($admin_id);

        $quotas = $stmt2->fetch_array();
        $total_quota = Filesystem::format_space($quotas['quota_total'] - $quotas['quota_used']);

        // Create the list of members for inclusion in multi-select box
        while ($row = $stmt1->fetch_assoc()) {
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
            '_GROUPS_SHARE_AMNT'		=> _GROUPS_SHARE_AMNT,
            '_GROUPS_SHARE_AMNT_SUB'	=> _GROUPS_SHARE_AMNT_SUB,
            '_GROUPS_TYPE'			=> _GROUPS_TYPE,
            '_GROUPS_TRUSTED'		=> _GROUPS_TRUSTED,
            '_GROUPS_DISTRO'		=> _GROUPS_DISTRO,
            '_GROUPS_TRUSTED_NFO'		=> _GROUPS_TRUSTED_NFO,
            '_GROUPS_DISTRO_NFO'		=> _GROUPS_DISTRO_NFO,
            '_GROUPS_CHARS_LEFT'		=> _GROUPS_CHARS_LEFT,
            '_RESET_FORM'			=> _RESET_FORM));

        // Assign dynamic content and other vars
        $this->tpl->assign('MEMBER_LIST', $member_list);
        $this->tpl->assign('TOTAL_QUOTA_REMAINING', $total_quota);

        // Assign external javascript code
        $this->tpl->assign('JS_INC', 'groups_create.tpl');

        // Display page
        $this->tpl->display('groups_create.tpl');
    }

    /**
    * Creates a new collaborative group
    *
    * Will perform all steps necessary to create a group
    * and establish its pressence in both the database
    * and on the filesystem
    *
    * @access public
    * @param string $group_name Name of group being created
    * @param array $group_members List of initial members being invited to the group
    * @param integer $admin_id user_id of the user creating the group
    * @param string $username username of the user creating the group
    * @param string $home_dir home_dir of the user creating the group
    * @param string $group_dir group_dir of the user creating the group
    */
    public function do_create_group($group_name,$group_type,$group_members,$admin_id,$username,$home_dir,$group_dir,$group_quota) {
        /**
        * I changed requiring the Filesystem class to instead requiring the MyFiles class because
        * MyFiles includes a database holder
        */
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        $fs = new MyFiles();
        $fs->__set("db", $this->__get("db"));

        /**
        * $values will be used to store the multi-insert if
        * more than 1 user is selected at time of group creation
        */
        $values = '';
        $status = '';
        $group_name = $fs->strip_forbidden_chars($group_name, 'group');

        // Group creation date
        $date = date("Y-m-d");

        // Create SQL array that holds all SQL we are going to execute
        $sql = array (
            "mysql"	=> array (
                'make_group' => "INSERT INTO "._PREFIX."_group_info (`group_id`,`admin_id`,`group_name`,`group_type`,`creation_date`,`home_dir`,`quota_total`) VALUES (':1',':2',':3',':4',':5',':6',':7')",
                'add_admin' => "INSERT INTO "._PREFIX."_groups (`user_id`, `group_id`) VALUES (':1',':2')",
                'check_for_group' => "SELECT group_id FROM "._PREFIX."_group_info WHERE home_dir=':1'",
                'update_user_quota' => "UPDATE "._PREFIX."_users SET quota_used = (quota_used + :1) WHERE user_id = ':2'",
                ),
        );

        // Store sent values
        $link_tgt	= $fs->normalize_dir($group_dir) . $group_name;
        $link_dir	= $fs->normalize_file($home_dir . '/' . "groups" . '/' . $group_name);
        $new_group	= $fs->normalize_dir($group_dir . $group_name . "/");
        $mode		= 0755;
        $group_quota	= $fs->convert_size(round($group_quota), 'mb', 'b');

        // Pseudo-randomly creates a group_id
        $group_id = md5(rand(0,1000000) . time() . $group_name);

        // Prepare all SQL statements
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['make_group']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['add_admin']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['check_for_group']);
        $stmt4 = $this->db->prepare($sql[_DBSYSTEM]['update_user_quota']);

        // Run the SQL to check to see if the group already exists
        $stmt3->execute($new_group);

        // If the group exists in the database...
        // TODO: Also check for group folder existence
        if ($stmt3->num_rows() > 0) {
            // Throw user to page telling them the group already exists
            $this->tpl->assign("_MESSAGE", _GROUPS_CHECK_EXISTS);
            $this->tpl->assign("_RETURN_LINK", _GROUPS_CREATE_RETURN_LINK);
        } else {
            $stmt1->execute($group_id,$admin_id,$group_name,$group_type,date("Y-m-d"),$new_group,$group_quota);

            /**
            * If this is the first time that a user has made a group, they probably havent
            * created the actual 'groups' folder. This does that.
            */
            if (!is_dir($home_dir . '/groups')) {
                $fs->add_file_perms_entries($fs->normalize_dir($home_dir . '/groups'), $admin_id, '-', "dr-x------");
                mkdir ($home_dir . '/groups');
            }

            /**
            * If this is the first time a group has been made (at all, not just for this user)
            * then it may be possible that the master group directory doesnt exist. We want
            * to make sure it does exist if it doesnt already
            */
            if (!is_dir($group_dir)) {
                $fs->mk_dir($group_dir);

                /**
                * Also need to update file permissions after we make the group dir
                * otherwise the user wont be able to step into the directory we dont
                * want them to be able to remove this main groups directory though
                */
                $fs->add_file_perms_entries($fs->normalize_dir($group_dir), $admin_id, '-', "dr-xr-x---");
            }

            /**
            * Make the new folder in the global groups folder
            * $new_group is the fullpath to the _real_ group folder
            */
            $fs->mk_dir($new_group, $mode);

            /**
            * Make a symbolic link to the new group folder inside of the admins
            * 'groups' folder.
            * $link_tgt is the fullpath to the _real_ group folder
            * $link_dir is the fullpath to the symbolic link pointing to the _real_ folder
            */
            $fs->mk_link($link_tgt, $link_dir);

            if ($group_type == 1) {
                /**
                * If the group being created is a 'trusted' group, that means that all the
                * group members can write to the group folder. Therefore we need
                * to set the permissions to be rwx for the group as well as the owner.
                *
                * We're normalize the symbolic link because it will be seen as a directory
                * when it is clicked. We cannot normalize it as a directory before this
                * step however because otherwise the link creation code would fail!
                * It works just like Linux!
                */
                $fs->add_file_perms_entries($fs->normalize_dir($link_dir), $admin_id, $group_id, "drwxrwxr-x");
            } else {
                /**
                * If the group being created is a 'distribution' group, that means that all
                * the group members ONLY have read access to the group folder. aka, they cant
                * upload new files, move files from or to, or delete files from. Therefore, the
                * folder permissions need to reflect that.
                *
                * We're normalize the symbolic link because it will be seen as a directory
                * when it is clicked. We cannot normalize it as a directory before this
                * step however because otherwise the link creation code would fail!
                * It works just like Linux!
                */
                $fs->add_file_perms_entries($fs->normalize_dir($link_dir), $admin_id, $group_id, "drwxr-xr-x");
            }

            // The admin is by default, added to their group
            $stmt2->execute($admin_id, $group_id);

            // Update the admins quota_used to reflect the share amount they chose
            $stmt4->execute($group_quota, $admin_id);

            // If a list of users was selected (or even if 1 user was selected)...
            if (is_array($group_members)) {
                foreach ($group_members as $key => $val) {
                    $this->do_invites_create($admin_id,$group_id,$val);
                }
            }

            $this->tpl->assign("_MESSAGE", _GROUPS_CREATE_SUCCESS);
            $this->tpl->assign("_RETURN_LINK", _GROUPS_CREATE_RETURN_LINK);

            $this->log->log('GROUP_ADD_NEW', $group_name, $new_group);
        }

        $this->tpl->display("actions_done.tpl");
    }

    /**
    * Display form to allow inviting new members to group
    *
    * @access public
    * @param string $admin_name username of the person doing the inviting
    * @param integer $admin_id user_id of the person doing the inviting
    */
    public function show_invites_create($admin_name, $admin_id) {
        $group_list	= array();
        $member_list	= array();

        $sql = array (
            "mysql"	=>	array(	"my_groups" => 		"SELECT `group_id`,`group_name`, `creation_date`
                                    FROM "._PREFIX."_group_info WHERE admin_id=':1'",
                        "all_members" =>	"SELECT `user_id`,`username` FROM "._PREFIX."_users
                                    WHERE public='1' AND username != ':1' ORDER BY username ASC",
                    ),
        );

        $stmt 	= $this->db->prepare($sql[_DBSYSTEM]["my_groups"]);
        $stmt1 	= $this->db->prepare($sql[_DBSYSTEM]["all_members"]);

        $stmt->execute($admin_id);
        $stmt1->execute($admin_name);

        while ($row = $stmt->fetch_array()) {
            $group_list[] = array ( $row['group_id'], $row['group_name'], $row['creation_date'] );
        }

        while ($row = $stmt1->fetch_array()) {
            $member_list[] = array ( $row['user_id'], $row['username'] );
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_GROUPS_INVITE_WELCOME'	=> _GROUPS_INVITE_WELCOME,
            '_GROUPS_LIST'			=> _GROUPS_LIST,
            '_GROUPS_CREATE'		=> _GROUPS_CREATE,
            '_GROUPS_INVITE_USER'		=> _GROUPS_INVITE_USER,
            '_GROUPS_NO_GROUPS_ADMIN'	=> _GROUPS_NO_GROUPS_ADMIN,
            '_GROUPS_MEMBERS_ADD'		=> _GROUPS_MEMBERS_ADD,
            '_GROUPS_MEMBERS_ADD_FYI'	=> _GROUPS_MEMBERS_ADD_FYI,
            '_GROUPS_SEND_INVITE'		=> _GROUPS_SEND_INVITE,
            '_RESET_FORM'			=> _RESET_FORM));

        // Assign dynamic data
        $this->tpl->assign('GROUP_LIST', $group_list);
        $this->tpl->assign('MEMBER_LIST', $member_list);

        // Display page
        $this->tpl->display('groups_invite_user.tpl');
    }

    /**
    * Show list of invites pending
    *
    * Will assign to Smarty a list of both invites
    * pending that the user has sent to other users
    * and invites pending that have been sent to the
    * user on behalf of other users
    *
    * @access public
    * @param integer $user_id User ID of the user requesting invites pending list
    */
    public function show_invites_pending($user_id) {
        $invites_pending_mine = array();
        $invites_pending_sent = array();

        $sql = array (
            "mysql" => array (
                'pending_invites_mine' => "SELECT DISTINCT(inv.group_id), inv.to_user_id, rslt.username,rslt.group_name FROM flare_invites AS inv LEFT JOIN (SELECT usr.user_id,usr.username,grp.group_name,grp.group_id FROM  flare_users AS usr LEFT JOIN flare_group_info AS grp ON usr.user_id=grp.admin_id) AS rslt ON rslt.group_id=inv.group_id WHERE inv.to_user_id=':1'",

                'pending_invites_sent' => "SELECT DISTINCT(inv.group_id), inv.to_user_id, rslt.username,rslt.group_name FROM flare_invites AS inv LEFT JOIN (SELECT usr.user_id,usr.username,grp.group_name,grp.group_id FROM  flare_users AS usr LEFT JOIN flare_group_info AS grp ON usr.user_id=grp.admin_id) AS rslt ON rslt.group_id=inv.group_id WHERE inv.from_user_id=':1'",
                'sent_to_user' => "SELECT username FROM "._PREFIX."_users WHERE user_id = ':1'"
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['pending_invites_mine']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['pending_invites_sent']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['sent_to_user']);
        $stmt1->execute($user_id);
        $stmt2->execute($user_id);

        while ($row = $stmt1->fetch_array()) {
            $invites_pending_mine[] = array(
                    'group_id' 	=> $row['group_id'],
                    'username' 	=> $row['username'],
                    'group_name' 	=> $row['group_name']);
        }

        while ($row = $stmt2->fetch_array()) {
            $stmt3->execute($row['to_user_id']);

            $result = $stmt3->result(0);

            $invites_pending_sent[] = array(
                    'group_id' 	=> $row['group_id'],
                    'to_user_id'	=> $row['to_user_id'],
                    'to_user'	=> $result,
                    'group_name' 	=> $row['group_name']);
        }

        // Begin populating the template data, first assign language constants
        $this->tpl->assign(array(
            '_GROUPS_INVITES'		=> _GROUPS_INVITES,
            '_GROUPS_INVITES_MINE'		=> _GROUPS_INVITES_MINE,
            '_GROUPS_INVITES_SENT'		=> _GROUPS_INVITES_SENT,
            '_GROUPS_RETRACT_INVITE'	=> _GROUPS_RETRACT_INVITE,
            '_NONE'				=> _NONE));

        // Assign dynamic content and other vars
        $this->tpl->assign('INVITES_PENDING_MINE', $invites_pending_mine);
        $this->tpl->assign('INVITES_PENDING_SENT', $invites_pending_sent);
    }

    /**
    * Show list of pending requests
    *
    * Requests allow a user to make a request to a group admin
    * to join a group. The group admin can then verify whether
    * or not they want the user to have access to the group.
    *
    * @access public
    * @param integer $user_id ID of the user to get requests for
    */
    public function show_requests_pending($user_id) {
        $requests_pending_received = array();
        $requests_pending_sent = array();
        $data = array();

        $sql = array(
            "mysql" => array(
                "requests_sent" => "SELECT request_id,username,group_name,fgi.group_id FROM "._PREFIX."_group_requests AS fgr LEFT JOIN "._PREFIX."_group_info AS fgi ON fgi.group_id=fgr.group_id LEFT JOIN "._PREFIX."_users AS fu ON fu.user_id=fgr.to WHERE fgr.from=':1' ORDER BY group_name ASC",
                "requests_verify" => "SELECT request_id,fu.user_id,username,group_name,fgi.group_id FROM "._PREFIX."_group_requests AS fgr LEFT JOIN "._PREFIX."_group_info AS fgi ON fgi.group_id=fgr.group_id LEFT JOIN "._PREFIX."_users AS fu ON fu.user_id=fgr.from WHERE fgr.to=':1' ORDER BY group_name ASC"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['requests_sent']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['requests_verify']);

        $stmt1->execute($user_id);
        $stmt2->execute($user_id);

        while ($row = $stmt1->fetch_assoc()) {
            $data = array(
                'request_id'	=> $row['request_id'],
                'group_id'	=> $row['group_id'],
                'username'	=> $row['username'],
                'group_name'	=> $row['group_name']
            );

            array_push($requests_pending_sent, $data);
        }

        while ($row = $stmt2->fetch_assoc()) {
            $data = array(
                'user_id'	=> $row['user_id'],
                'request_id'	=> $row['request_id'],
                'group_id'	=> $row['group_id'],
                'username'	=> $row['username'],
                'group_name'	=> $row['group_name']
            );

            array_push($requests_pending_received, $data);
        }

        // Assign content
        $this->tpl->assign(array(
            '_GROUPS_DENY_REQUEST'		=> _GROUPS_DENY_REQUEST,
            '_GROUPS_ALLOW_REQUEST'		=> _GROUPS_ALLOW_REQUEST,
            'REQUESTS_PENDING_RECEIVED'	=> $requests_pending_received,
            'REQUESTS_PENDING_SENT'		=> $requests_pending_sent));

    }

    /**
    * Send invites to users
    *
    * Used to send invites to other users so that
    * they can join groups you create
    *
    * @access public
    * @param integer $admin_id User ID of the person who administers the group
    * @param string $group_id ID of the group that the user is being invited to
    * @param array  $group_members List of user_ids to send invites to
    * @return bool True on failure, false on success
    */
    public function do_invites_create($admin_id, $group_id, $group_members = '') {
        $sql = array (
            "mysql" => array (
                'insert_invite' => "INSERT INTO "._PREFIX."_invites (`from_user_id`,`to_user_id`,`group_id`) VALUES (':1',':2',':3')"
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['insert_invite']);

        if(count($group_members) == 0 && $group_members == "") {
            $this->__set("error", TRUE);
            return;
        } else {
            if (!is_array($group_members)) {
                $stmt1->execute($admin_id, $group_members, $group_id);
                $this->log->log('USER_INVITE', $admin_id, $group_id, $group_members);
            } else {
                foreach ($group_members as $key => $val) {
                    $stmt1->execute($admin_id, $val, $group_id);
                    $this->log->log('USER_INVITE', $admin_id, $group_id, $val);
                }
            }
            $this->__set("error", FALSE);
            return;
        }
    }

    /**
    * Deletes a group
    *
    * This method takes care of all the steps that
    * need to be performed when removing a group
    * from the system. This includes deleting the
    * group folder. Adjusting quotas, adjusting
    * file permissions, removing links to the group,
    * removing any pending invites, etc.
    *
    * @access public
    * @param integer $admin_id User ID of the group admin
    * @param string $group_id Group ID of the group to be removed
    */
    public function do_delete_groups($admin_id, $group_id) {
        // Require the filesystem operations file that we will use to remove the group dirs and symlinks
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        // Create a filesystem object
        $fs = new MyFiles();

        $fs->__set("db", $this->__get('db'));
        $this->__set("fs", $fs);

        $failed_groupmember_unlinking 	= array();
        $success_groupmember_unlinking 	= array();
        $failed_group_dir_deleting	= array();

        // Make our array of SQL using the format established
        $sql = array (
            "mysql" => array (
                'retrieve_group_info' => "SELECT `home_dir`,`quota_total` FROM "._PREFIX."_group_info WHERE admin_id=':1' AND group_id=':2'",
                'delete_groups' => "DELETE FROM "._PREFIX."_group_info WHERE admin_id=':1' AND group_id=':2'",
                'force_member_leave' => "DELETE FROM "._PREFIX."_groups WHERE group_id=':1'",
                'delete_outstanding_invites' => "DELETE FROM "._PREFIX."_invites WHERE from_user_id=':1' AND group_id=':2'",
                'group_links' => "SELECT usr.username, usr.home_dir, rslt.group_name FROM "._PREFIX."_users AS usr LEFT JOIN (SELECT fgi.admin_id,fgi.group_id,fgi.group_name FROM "._PREFIX."_group_info AS fgi LEFT JOIN "._PREFIX."_groups AS fg ON fg.group_id=fgi.group_id) AS rslt ON rslt.admin_id=usr.user_id WHERE rslt.group_id=':1' AND rslt.group_id IS NOT NULL",
                'update_quota' => "UPDATE "._PREFIX."_users SET quota_used = (quota_used - :1) WHERE user_id = ':2'"
                )
        );

        // Prepare all our SQL for execution
        $stmt1	= $this->db->prepare($sql[_DBSYSTEM]['retrieve_group_info']);
        $stmt2 	= $this->db->prepare($sql[_DBSYSTEM]['delete_groups']);
        $stmt3 	= $this->db->prepare($sql[_DBSYSTEM]['force_member_leave']);
        $stmt4 	= $this->db->prepare($sql[_DBSYSTEM]['delete_outstanding_invites']);
        $stmt5 	= $this->db->prepare($sql[_DBSYSTEM]['group_links']);
        $stmt6 	= $this->db->prepare($sql[_DBSYSTEM]['update_quota']);

        // First, run SQL to get the home directory of the group.
        $stmt1->execute($admin_id, $group_id);

        $group_info = $stmt1->fetch_array();

        // Pull the home_dir from the result. This will be used when removing the groups folder
        $group_dir = $fs->normalize_dir($group_info['home_dir']);

        // Get the group total quota size
        $quota_total = $group_info['quota_total'];

        // Check to make sure the folder we're about to delete exists, and delete it if it does
        if (is_dir($group_dir)) {
            $fs->rm_dir($group_dir);
            $fs->remove_file_perms_entries($group_dir);
        }

        // Check for errors
        if ($fs->__get("error")) {
            $failed_group_dir_deleting[] = $group_dir;
            // break out of processing if we hit an error
            break;
        } else {
            // Otherwise, clean up the rest of the group associations. These include...
            $stmt5->execute($group_id);

            while ($row = $stmt5->fetch_array()) {
                $user_link_to_group = $fs->normalize_file($row['home_dir'] . '/' . "groups" . '/' . $row['group_name']);

                if(is_link($user_link_to_group)) {
                    $fs->rm_dir($user_link_to_group);

                    if (!is_link($user_link_to_group)) {
                        $fs->remove_file_perms_entries($fs->normalize_dir($user_link_to_group));
                        $success_groupmember_unlinking[] = $row['username'] . " from " . $row['group_name'];
                    } else
                        $failed_groupmember_unlinking[] = $row['username'] . " from " . $row['group_name'];
                } else {
                    $failed_groupmember_unlinking[] = $row['username'] . " from " . $row['group_name'];
                }
            }

            // Nuking the group from the group_info table
            $stmt2->execute($admin_id, $group_id);

            // and removing all the users from the groups table that are members of this group
            $stmt3->execute($group_id);

            // and finally, removing all the users from the invites table that havent accepted
            // the invitation to the group yet
            $stmt4->execute($admin_id, $group_id);

            // Then if we are using quotas, update the user's quota to add the space back
            if ($this->cfg['use_quotas'])	{
                $stmt6->execute($quota_total, $admin_id);
            }

            $this->log->log('GROUP_DELETE', $group_dir);
        }
    }

    /**
    * Displays group editing page
    *
    * Displays the page that allows the admin of a
    * group change various features of the group such
    * as altering quotas, kicking people out and
    * inviting new people
    *
    * @access public
    * @param string $group_id ID of the group being edited
    * @param integer $admin User ID of the group administrator
    */
    public function show_edit_group($group_id, $admin) {
        require_once(ABSPATH.'/extensions/Filesystem/class.Filesystem.php');

        $member_list = array();
        $config = array();
        $sql = array(
            "mysql" => array(
                "config" => "SELECT * FROM "._PREFIX."_group_info WHERE group_id = ':1'",
                "members" => "SELECT grps.user_id, usrs.username FROM "._PREFIX."_groups AS grps LEFT JOIN "._PREFIX."_users AS usrs ON grps.user_id = usrs.user_id WHERE group_id = ':1' ORDER BY usrs.username ASC",
                "user_quotas" => "SELECT quota_used, quota_total FROM "._PREFIX."_users WHERE user_id = ':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['members']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['user_quotas']);

        $stmt1->execute($group_id);
        $stmt2->execute($group_id);
        $stmt3->execute($admin);

        $config = $stmt1->fetch_array();

        $quotas = $stmt3->fetch_array();
        $total_quota = Filesystem::format_space($quotas['quota_total'] - $quotas['quota_used']);

        while ($row = $stmt2->fetch_array()) {
            // Make sure the admin cant shoot themselves in the foot
            if ($row[1] == $admin)
                continue;

            // Make the member list that people can choose to remove from
            $member_list[] = array(
                "user_id" => $row[0],
                "username" => $row[1]
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            "_GROUPS_LIST"			=> _GROUPS_LIST,
            "_GROUPS_CREATE"		=> _GROUPS_CREATE,
            "_GROUPS_INVITE_USER"		=> _GROUPS_INVITE_USER,
            "_GROUPS_EDIT_WELCOME"		=> _GROUPS_EDIT_WELCOME,
            "_GROUPS_GROUP_NAME"		=> _GROUPS_GROUP_NAME,
            "_GROUPS_CURRENT_MEMBERS"	=> _GROUPS_CURRENT_MEMBERS,
            "_GROUPS_CURRENT_MEMBERS_FYI"	=> _GROUPS_CURRENT_MEMBERS_FYI,
            "_GROUPS_CHECK_AVAIL"		=> _GROUPS_CHECK_AVAIL,
            "_SAVE_CHGS"			=> _SAVE_CHGS,
            "_RESET_FORM"			=> _RESET_FORM,
            "_GROUPS_CUR_SHARE_AMNT"	=> _GROUPS_CUR_SHARE_AMNT,
            "_GROUPS_SHARE_AMNT_MESG"	=> _GROUPS_SHARE_AMNT_MESG
        ));

        // Assign dynamic content
        $this->tpl->assign(array(
            "GROUP_ID"		=> $config['group_id'],
            "GROUP_TYPE"		=> $config['group_type'],
            "MEMBER_LIST"		=> $member_list,
            "GROUP_NAME"		=> $config['group_name'],
            "SHARE_AMOUNT"		=> Filesystem::convert_size($config['quota_total'], 'b', 'mb'),
            "TOTAL_QUOTA_REMAINING"	=> $total_quota,
            "JS_INC"		=> "groups_edit.tpl"
        ));

        // Display the page
        $this->tpl->display("groups_edit.tpl");
    }

    /**
    * Saves changes made to a group
    *
    * After an administrator is finished making
    * any changes, they must be saved back to the
    * database to take effect. This method
    * accomplishes that.
    *
    * @access public
    * @param string $group_id ID of the group being edited
    * @param integer $group_type Integer value, either 1 or 2 determining
    *				if the group is a distribution or trusted group
    * @param array $members Array of user_ids for members of the group
    * @param integer $old_share_amount Old amount of user quota to share in a group (in bytes)
    * @param integer $new_share_amount New amount of user quota to share in a group (in bytes)
    * @param integer $admin_id User ID of the administrator
    */
    public function do_edit_group($group_id, $group_type, $group_members, $old_share_amount, $new_share_amount, $admin_id) {
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        $fs = new MyFiles();
        $fs->__set("db", $this->__get("db"));

        $share_amount = 0;
        $sql = array(
            "mysql" => array(
                'update_group' => "UPDATE "._PREFIX."_group_info SET group_type=':1', quota_total=':2' WHERE group_id=':3'",
                'add_to_user_quota' => "UPDATE "._PREFIX."_users SET quota_used=(quota_used + :1)",
                'subtract_from_user_quota' => "UPDATE "._PREFIX."_users SET quota_used=(quota_used - :1)",
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['update_group']);

        $new_share_amount = $fs->convert_size(round($new_share_amount), 'mb', 'b');
        $old_share_amount = $fs->convert_size(round($old_share_amount), 'mb', 'b');

        $share_amount = $new_share_amount - $old_share_amount;

        if ($share_amount > 0)
            $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['add_to_user_quota']);
        else if ($share_amount < 0)
            $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['subtract_from_user_quota']);
        else
            $stmt3 = '';

        $stmt1->execute($group_type,$new_share_amount,$group_id);

        if (count($group_members) > 0) {
            foreach ($group_members as $key => $member_id) {
                $this->my_groups_in_withdraw($member_id, $group_id);
            }
        }

        // Use abs() because subtracting by a negative will add
        if ($stmt3 != '')
            $stmt3->execute(abs($share_amount));
    }

    /**
    * Leave a group
    *
    * This allows a user to leave a group whenever
    * they want to. It takes care of cleaning up
    * links too
    *
    * @access public
    * @param integer $user_id ID of the user leaving the group
    * @param string $group_id ID of the group that the user is leaving
    */
    public function my_groups_in_withdraw($user_id, $group_id) {
        // Require the MyFiles extension for working with the file permissions and filesystem
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        $fs = new MyFiles();
        $fs->__set("db", $this->__get("db"));

        $sql = array (
            "mysql" => array (
                'leave_group' => "DELETE FROM "._PREFIX."_groups WHERE user_id=':1' AND group_id=':2'",
                'user_info' => "SELECT home_dir FROM "._PREFIX."_users WHERE user_id=':1'",
                'group_name' => "SELECT group_name FROM "._PREFIX."_group_info WHERE group_id=':1'"
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['leave_group']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['user_info']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['group_name']);

        $stmt2->execute($user_id);
        $user_info = $stmt2->fetch_array();

        $stmt3->execute($group_id);
        $group_name = $stmt3->result(0);

        $group_link = $fs->normalize_file($user_info['home_dir'] . '/' . "groups" . '/' . $group_name);

        $fs->rm_dir($group_link);

        // If the link was removed successfully
        if (!file_exists($group_link)) {
            // Remove the users entry from the groups table
            $stmt1->execute($user_id, $group_id);

            // And remove the link entry from the permissions table
            $fs->remove_file_perms_entries($group_link);
        }
    }

    /**
    * Accept an invite to a group
    *
    * Before you can access group files, you must
    * be a member of the group. If you arent the
    * admin, then the only way to gain access is
    * through accepting invites sent from the
    * admin
    *
    * @access public
    * @param integer $user_id ID of the user accepting invites
    * @param string $group_dir Full path to the group directory minus the group name
    * @param string $home_dir Full path to the requesting user's home directory
    * @param array $invites_mine Array of group IDs that you are accepting invites for
    */
    public function do_invites_accept($user_id, $group_dir, $home_dir, $invites_mine) {
        // Require the MyFiles extension for working with the server
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        // Create a new instance of the Filesystem object
        $fs = new MyFiles();
        $fs->__set("db", $this->__get("db"));

        /**
        * Used to store a list of all groups that our user may already be a member of
        */
        $already_member_of = array();

        /**
        * Used to store a list of all the links that weren't created successfully
        */
        $failed_links = array();

        $already_member_error = "";
        $failed_links_error = "";
        $output = '';

        // This little bit of code just silences a warning that would otherwise
        // be displayed a little further down in the foreach loop
        if (!is_array($invites_mine))
            $invites_mine = array($invites_mine);

        // Create our array of SQL that we will use in this method
        $sql = array (
            "mysql" => array(
                'group' => "SELECT `group_id`,`group_name`,`group_type` FROM "._PREFIX."_group_info WHERE group_id=':1'",
                'mk_group' => "INSERT INTO "._PREFIX."_groups (`user_id`,`group_id`) VALUES (':1',':2')",
                'remove_pending' => "DELETE FROM "._PREFIX."_invites WHERE to_user_id=':1' AND group_id=':2'",
                )
        );

        // Prepare the SQL to be used
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['group']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['mk_group']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['remove_pending']);

        // If the user didnt choose an invite to accept...
        if (count($invites_mine) == 0 ) {
            // Assign error message and exit
            $this->tpl->assign('_MESSAGE', _GROUPS_ACCEPT_INVITE_FAILURE_NONE);
            $this->tpl->assign('_RETURN_LINK', _GROUPS_ACCEPT_INVITE_RETURN_LINK);
        } else {
            /**
            * Otherwise, we need to operate on each group_id that was given to us
            * Note we are working with the group_ids instead of the group_name because
            * even though its not possible to have 2 groups with the same name in the same
            * directory, Flare supports group_dirs on a per user basis, so just because
            * a group name exists twice in the database _doesnt_ mean thats _not_ possible
            */
            foreach ($invites_mine as $key => $val) {
                // We start with retrieving the name of the group that has the group_id stored in $val
                $stmt1->execute($val);

                $group_info = $stmt1->fetch_array();

                /**
                * We are basically assembling strings we can send to a function that
                * emulates 'ln' on linux.
                * The first argument to ln is the place you want to point to
                */
                $group_dir_tgt = $fs->normalize_dir($group_dir . '/' . $group_info['group_name']);;

                // The second argument is what you want to call the new link
                $group_dir_dir = $fs->normalize_file($home_dir . '/' . 'groups' . '/' . $group_info['group_name']);

                /**
                * If we determine that the user is already a member of this group by checking
                * to see if the link to the group exists...
                * TODO: Do database checking in addition to link existence checking
                */
                if(is_link($group_dir_dir)) {
                    // Remove their invite entry
                    $stmt3->execute($user_id,$val);

                    // and mark the group in a list of groups that the user is already a member of
                    $already_member_of[] = $group_info['group_name'];
                } else {
                    /**
                    * If the user hasnt created any groups yet, then their groups
                    * directory doesnt exist and therefore they'll die when they hit this
                    * block. So create the group dir if it doesnt exist.
                    */
                    $main_group_dir = $fs->normalize_dir($home_dir . '/' . 'groups');
                    if (!is_dir($main_group_dir)) {
                        $fs->mk_dir($main_group_dir);
                        $fs->add_file_perms_entries($main_group_dir, $user_id, '-', 'dr-x------');
                    }

                    /**
                    * If the link doesnt already exist, then we assume that the user
                    * isnt already a member of the group. In that case, make the link
                    */
                    $fs->mk_link($group_dir_tgt, $group_dir_dir);

                    // Check to see if the newly created link actually points to a valid location
                    if(!is_link($group_dir_dir)) {
                        // If not, mark it down in our list of broken items
                        $failed_links[] = $group_info['group_name'];
                    } else {
                        // Otherwise, add the user's new group entry into the groups table
                        $stmt2->execute($user_id,$val);

                        // and erase their entry in the invites table
                        $stmt3->execute($user_id,$val);

                        /**
                        * Re-normalize the symlink as a directory. We do this because otherwise
                        * permissions will be screwed up when the user surfs to the directory
                        */
                        $group_dir_dir = $fs->normalize_dir($group_dir_dir);

                        // Check the group type they are joining
                        switch($group_info['group_type']) {
                            case 1:
                                /**
                                * Trusted group
                                *
                                * A trusted group allows ALL GROUP MEMBERS to modify
                                * the contents of the group directory
                                */
                                $fs->add_file_perms_entries($group_dir_dir, '-', $group_info['group_id'], 'd---rwx---');
                                break;
                            case 2:
                                /**
                                * Distribution group
                                *
                                * A distribution group allows ONLY the admin of the
                                * group to modify the contents of the group directory
                                */
                                $fs->add_file_perms_entries($group_dir_dir, '-', $group_info['group_id'], 'd---r-x---');
                                break;
                        }

                        // Assign the working link name to the success list
                        $success_links[] = $group_info['group_name'];
                    } // end else
                } // end else
            } // end foreach

            /**
            * Once we've reached this point, we're all done with all the invites that
            * the user wanted to accept
            *
            * If the system picked out that the user was already a member of at least one
            * group, we should probably let the user know that they cant join a group
            * they're already a member of
            */
            if (count($already_member_of) > 0) {
                // We start with the message that they werent able to re-join the group
                $already_member_error = _GROUPS_ACCEPT_INVITE_FAILURE_ALREADY_MEMBER;

                // We use a bulleted list to organize the list of groups
                $already_member_error .= "<ul>";

                // With each bullet being the name of the group they couldnt join
                foreach($already_member_of as $key => $val) {
                    $already_member_error .= "<li>$val</li>";
                }

                // And after wrapping up the list
                $already_member_error .= "</ul><p>";
            } //end if

            if (count($failed_links) > 0) {
                $failed_links_error = _GROUPS_ACCEPT_INVITE_FAILURE_LINKS_BROKE;

                $failed_links_error .= "<ul>";

                foreach ($failed_links as $key => $val) {
                    $failed_links_error .= "<li>$val</li>";
                }

                $failed_links_error .= "</ul><p>";
            } //end if

            // Create the output that will be sent back to the user
            $output = trim($already_member_error . $failed_links_error);
        } // end else

        if ($output) {
            return $output;
        }
    }

    /**
    * Declines invitation to a group
    *
    * If an admin invites you to a group and you do
    * not want to join it, this will allow you to
    * decline the invitation
    *
    * @access public
    * @param integer $user_id ID of the user declining the invitation
    * @param array $invites_mine Array of group IDs for invitations you are declining
    */
    public function do_invites_decline($user_id, $invites_mine) {
        $sql = array (
            "mysql" => array (
                'decline' => "DELETE FROM "._PREFIX."_invites WHERE to_user_id=':1' AND group_id=':2'"
                )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['decline']);

        if (count($invites_mine) == 0) {
            $this->tpl->assign('_MESSAGE', _GROUPS_DECLINE_INVITE_FAILURE);
            $this->tpl->assign('_RETURN_LINK', _GROUPS_DECLINE_INVITE_RETURN_LINK);
        } else {
            foreach ($invites_mine as $key => $val) {
                $stmt->execute($user_id,$val);
            }
            $this->tpl->assign('_MESSAGE', _GROUPS_DECLINE_INVITE_SUCCESS);
            $this->tpl->assign('_RETURN_LINK', _GROUPS_DECLINE_INVITE_RETURN_LINK);
        }

        $this->tpl->display('actions_done.tpl');
    }

    /**
    * Checks for the existance of a group
    *
    * Checks to see if a group already exists so that
    * another group isnt created for one that already
    * exists
    *
    * @access public
    * @param string $group_dir Full path, minus the group name, to the group directory
    * @param string $group_name Name of the group being checked for
    */
    public function do_check_for_existing_group($group_dir,$group_name) {
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        $fs = new MyFiles();

        $sql = array (
            "mysql" => array (
                "select_group" => "SELECT group_name FROM "._PREFIX."_group_info WHERE home_dir=':1'"
                )
        );

        $group = $fs->normalize_dir($group_dir . '/' . $group_name);

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select_group']);
        $stmt1->execute($group);

        // Make sure we're not receiving an empty group name. This could hex the whole method
        if (empty($group_name)) {
            $this->tpl->assign('_MESSAGE', _GROUPS_CHECK_EMPTY);
            $this->tpl->assign('_RETURN_LINK', _GROUPS_CHECK_EMPTY_RETURN_LINK);
        } else {
            /**
            * If we're here, the group name was at least not empty
            * So see if the group entry exists in the database. Note that
            * we can disregard checking for the group name and only focus on the
            * group home, because Flare supports user_specific group dirs, and can
            * thus have multiple group names
            */
            if ($stmt1->num_rows() > 0) {
                $this->tpl->assign('_MESSAGE', _GROUPS_CHECK_EXISTS);
                $this->tpl->assign('_RETURN_LINK', _GROUPS_CHECK_EMPTY_RETURN_LINK);
            } else {
                $this->tpl->assign('_MESSAGE', _GROUPS_CHECK_NO_EXISTS);
                $this->tpl->assign('_RETURN_LINK', _GROUPS_CHECK_EMPTY_RETURN_LINK);
            }
        }
        $this->tpl->display('actions_done.tpl');
    }

    /**
    * Retract an invite sent to a user
    *
    * As the admin of a group, if you send an
    * invite and the determine you sent it to
    * the wrong person, or no longer want the
    * user to be able to accept the invite, this
    * method will allow you to retract the invite
    * so that the user it was sent to wont be able
    * to accept it. Note that if they already
    * sent it, then this function wont be able
    * to remove them from the group
    *
    * @access public
    * @param integer $user_id User ID of the user you want to retract the invite from
    * @param string $group_id Group ID of the group that the invite was sent for
    */
    public function do_retract_invite($user_id, $group_id) {
        $sql = array (
            "mysql" => array (
                "retract" => "DELETE FROM "._PREFIX."_invites WHERE to_user_id = ':1' AND group_id = ':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['retract']);

        $stmt1->execute($user_id, $group_id);
    }

    /**
    * Returns the current quota_total and quota_used
    *
    * The groups total quota and how much has been used is needed when
    * determining if a write operation should be allowed on a group
    * directory. This method will return an array containing those two
    * value.
    *
    * @param string $group_id Group ID of the group you want the quota info for
    * @return array Array containing requested information. Array will be empty if group doesnt exist
    */
    public function get_group_quotas($group_id) {
        $sql = array (
            "mysql" => array(
                "quotas" => "SELECT quota_total, quota_used FROM "._PREFIX."_group_info WHERE group_id = ':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['quotas']);

        $stmt1->execute($group_id);

        if ($stmt1->num_rows() > 0) {
            $data = $stmt1->fetch_array();

            $result = array(
                'quota_total' 	=> $data['quota_total'],
                'quota_used '	=> $data['quota_used']
            );

            return $result;
        } else {
            return array();
        }
    }

    /**
    * Decreases the amount of used quota in the groups table
    *
    * @access public
    * @param string $id group_id of the group to have their quota decreased
    * @param integer $size size to be decreased by
    */
    public function adjust_space_decrease($id, $size) {
        $sql = array(
            "mysql" => array(
                "adjust" => "UPDATE "._PREFIX."_group_info SET quota_used=(quota_used - :1) WHERE group_id=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["adjust"]);
        $stmt1->execute($size, $id);
    }

    /**
    * Increases the amount of used quota in the groups table
    *
    * @access public
    * @param integer $id group_id of the group who is having their quota increased
    * @param integer $size size to be increased by
    */
    public function adjust_space_increase($id, $size) {
        $sql = array(
            "mysql" => array(
                "adjust" => "UPDATE "._PREFIX."_group_info SET quota_used=(quota_used + :1) WHERE group_id=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["adjust"]);
        $stmt1->execute($size, $id);
    }

    /**
    * Request to Join a group
    *
    * Requests allow a user who is not a member of a group
    * to send a message to a group administrator asking them
    * to please allow the person into the group.
    *
    * @access public
    * @param string $group_id Group ID of the group the user wishes to join
    * @param integer $admin_id User ID of the group administrator
    * @param integer $user_id User ID of the user requesting to be added
    */
    public function do_request_join($group_id, $admin_id, $user_id) {
        $sql = array(
            "mysql" => array(
                "request" => "INSERT INTO "._PREFIX."_group_requests (`to`,`from`,`group_id`) VALUES (':1',':2',':3')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['request']);

        $stmt1->execute($admin_id,$user_id,$group_id);
    }

    /**
    * Retracts a group request
    *
    * In the event that a user makes a request to join a group
    * and then decides that they dont want to be in the group,
    * or for any other reason decides to retract the request,
    * this method will do just that.
    *
    * Requests can only be retracted until the group admin
    * approves the request. After the request has been approved,
    * there is no way to retract the request. The user will need
    * to leave the group if they want to.
    *
    * @access public
    * @param integer $request_id ID of the request to retract
    * @param integer $user_id ID of the user doing the retracting
    */
    public function do_retract_request($request_id) {
        $this->do_remove_request($request_id);
    }

    /**
    *
    */
    public function do_remove_request($request_id) {
        $sql = array(
            "mysql" => array(
                "retract" => "DELETE FROM "._PREFIX."_group_requests WHERE `request_id`=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['retract']);

        $stmt1->execute($request_id);
    }
}

?>
