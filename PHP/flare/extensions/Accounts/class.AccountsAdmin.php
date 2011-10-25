<?php
/**
* @package Accounts
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
* Require the tools stored in the base accounts class. This is necessary
* for the extending code below.
*/
require_once(ABSPATH.'/extensions/Accounts/class.Accounts.php');

/**
* Accounts administration extension
*
* Contains the methods and variables needed to maintain user accounts
* in Flare from the administrations point of view. The tools provided
* here operate at a different level and allow full customization and
* modification of any user account in the system
*
* @package Accounts
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
* @uses Accounts
*/
class AccountsAdmin extends Accounts {
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
    * List of all the services offered by Flare
    *
    * @access public
    * @var array
    */
    public $service_list;

    /**
    * Barebones constructor
    *
    * This constructor will override the default one supplied by PHP.
    * In this context the constructor does not do anything, so it is
    * left up to future developers to decide if it will even be used.
    *
    * @access public
    */
    public function __construct() {
        $services = array(
            'svn'	=> "Subversion",
            'smb'	=> "Samba",
            'mysql'	=> "MySQL",
            'ftp'	=> "FTP",
            'web'	=> "Flare Account"
        );

        $this->__set("service_list", $services);
    }

    /**
    * Shows list of all users
    *
    * Displays a list of all known users in the Flare system. This list
    * includes information such as the users login name, their real name,
    * where their home and group directories are, their authentication
    * type and the last time they logged in.
    *
    * @access public
    * @return bool Always returns true
    */
    public function show_user_list() {
        // Array that will hold list of all Active users
        $account_list_a = array();

        // Array that will hold list of all Pending users
        $account_list_p = array();

        // Array that will hold list of all Deactivated users
        $account_list_d = array();

        // Make the SQL structure to hold all the SQL we're going to run
        $sql = array (
            "mysql" => array (
                'user_list_activated' => "SELECT user_id,username,fname,lname,home_dir,group_dir,auth_type,last_login FROM "._PREFIX."_users WHERE status='A' ORDER BY username ASC",
                'user_list_deactivated' => "SELECT user_id,username,fname,lname,home_dir,group_dir,auth_type,last_login FROM "._PREFIX."_users WHERE status='D' ORDER BY username ASC",
                'user_list_pending' => "SELECT user_id,username,fname,lname,home_dir,group_dir,auth_type,last_login FROM "._PREFIX."_users WHERE status='P' ORDER BY username ASC",
                )
        );

        // Prepare all the SQL for executing
        $stmt_a = $this->db->prepare($sql[_DBSYSTEM]['user_list_activated']);
        $stmt_d = $this->db->prepare($sql[_DBSYSTEM]['user_list_deactivated']);
        $stmt_p = $this->db->prepare($sql[_DBSYSTEM]['user_list_pending']);

        // Run it all
        $stmt_a->execute();
        $stmt_d->execute();
        $stmt_p->execute();

        // Build the list of active users
        while ($row = $stmt_a->fetch_array()) {
            $user_id	= $row['user_id'];
            $username 	= $row['username'];
            $realname 	= $row['fname'] . " " . $row['lname'];
            $home		= $row['home_dir'];
            $groups		= $row['group_dir'];
            $last_login	= ($row['last_login'] != 0) ? strftime("%m-%d-%Y at %I:%M %p",$row['last_login']) : '-';
            $auth_type	= $row['auth_type'];

            $account_list_a[] = array(
                'user_id'	=> $user_id,
                'username'	=> $username,
                'realname'	=> $realname,
                'home_dir'	=> $home,
                'group_dir'	=> $groups,
                'last_login'	=> $last_login,
                'auth_type'	=> $auth_type);
        }

        // Build the list of deactivated users
        while ($row = $stmt_d->fetch_array()) {
            $user_id	= $row['user_id'];
            $username 	= $row['username'];
            $realname 	= $row['fname'] . " " . $row['lname'];
            $home		= $row['home_dir'];
            $groups		= $row['group_dir'];
            $last_login	= ($row['last_login'] != 0) ? strftime("%m-%d-%Y",$row['last_login']) : '-';
            $auth_type	= $row['auth_type'];

            $account_list_d[] = array(
                'user_id'	=> $user_id,
                'username'	=> $username,
                'realname'	=> $realname,
                'home_dir'	=> $home,
                'group_dir'	=> $groups,
                'last_login'	=> $last_login,
                'auth_type'	=> $auth_type);
        }

        // Build the list of pending users
        while ($row = $stmt_p->fetch_array()) {
            $user_id	= $row['user_id'];
            $username 	= $row['username'];
            $realname 	= $row['fname'] . " " . $row['lname'];
            $home		= $row['home_dir'];
            $groups		= $row['group_dir'];
            $last_login	= ($row['last_login'] != 0) ? strftime("%m-%d-%Y",$row['last_login']) : '-';
            $auth_type	= $row['auth_type'];

            $account_list_p[] = array(
                'user_id'	=> $user_id,
                'username'	=> $username,
                'realname'	=> $realname,
                'home_dir'	=> $home,
                'group_dir'	=> $groups,
                'last_login'	=> $last_login,
                'auth_type'	=> $auth_type);
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_ACCTS_ADM_USERNAME' 		=> _ACCTS_ADM_USERNAME,
            '_ACCTS_ADM_REALNAME' 		=> _ACCTS_ADM_REALNAME,
            '_ACCTS_ADM_HOMEDIR' 		=> _ACCTS_ADM_HOMEDIR,
            '_ACCTS_ADM_GROUPDIR' 		=> _ACCTS_ADM_GROUPDIR,
            '_ACCTS_ADM_AUTH_TYPE'		=> _ACCTS_ADM_AUTH_TYPE,
            '_ACCTS_ADM_LAST_LOGIN' 	=> _ACCTS_ADM_LAST_LOGIN,
            '_ACCTS_ADM_ADD_ACCT' 		=> _ACCTS_ADM_ADD_ACCT,
            '_ACCTS_ADM_SHOW_ALL_ACCOUNTS'	=> _ACCTS_ADM_SHOW_ALL_ACCOUNTS,
            '_ACCTS_DEACTIVATE'		=> _ACCTS_DEACTIVATE,
            '_ACCTS_ACTIVATE'		=> _ACCTS_ACTIVATE,
            '_ACCTS_DELETE'			=> _ACCTS_DELETE,
            '_ACCTS_PURGE'			=> _ACCTS_PURGE,
            '_WITH_SELECTED' 		=> _WITH_SELECTED,
            '_ACCTS_CHG_ACCTS'		=> _ACCTS_CHG_ACCTS,
            '_SETTINGS'			=> _SETTINGS,
            '_NO_ACTIONS' 			=> _NO_ACTIONS,
            'JS_INC'			=> 'accounts_main.tpl'));

        // Assign dynamic content
        $this->tpl->assign(array(
            'USE_JOBS'		=> $this->cfg['use_jobs'],
            'ACCOUNT_LIST_A'	=> $account_list_a,
            'ACCOUNT_LIST_D'	=> $account_list_d,
            'ACCOUNT_LIST_P'	=> $account_list_p));

        // Display the page
        $this->tpl->display('accounts_main.tpl');

        return true;
    }

    /**
    * Shows the add account form
    *
    * This form is only available to the admin. It will allow them
    * to add a new user account to the Flare system. Several items
    * must be specified in the form to properly make the user account.
    * These include the username, and home and group directories. The
    * other fields can be changed at any time.
    *
    * @access public
    * @return bool Always returns true
    */
    public function show_add_account() {
        $this->tpl->assign(array(
            '_ACCTS_PERSONAL_INFO' 		=> _ACCTS_PERSONAL_INFO,
            '_ACCTS_FNAME'	 		=> _ACCTS_FNAME,
            '_ACCTS_LNAME' 			=> _ACCTS_LNAME,
            '_ACCTS_GENDER' 		=> _ACCTS_GENDER,
            '_ACCTS_ORG_EMAIL' 		=> _ACCTS_ORG_EMAIL,
            '_ACCTS_ADM_ADD_ACCT' 		=> _ACCTS_ADM_ADD_ACCT,
            '_ACCTS_ADM_USERNAME'		=> _ACCTS_ADM_USERNAME,
            '_ACCTS_ADM_ACCT_INFO'		=> _ACCTS_ADM_ACCT_INFO,
            '_ACCTS_ADM_AUTH_TYPE'		=> _ACCTS_ADM_AUTH_TYPE,
            '_ACCTS_ADM_SHOW_ALL_ACCOUNTS'	=> _ACCTS_ADM_SHOW_ALL_ACCOUNTS,
            '_SETTINGS'			=> _SETTINGS,
            '_USR_PASSWORD' 		=> _USR_PASSWORD,
            '_ORG_EXT'			=> _ORG_EXT,
            '_ACCTS_ADM_HOMEDIR' 		=> _ACCTS_ADM_HOMEDIR,
            '_ACCTS_ADM_GROUPDIR' 		=> _ACCTS_ADM_GROUPDIR,
            '_ACCTS_ADM_CREATE_ACCOUNT'	=> _ACCTS_ADM_CREATE_ACCOUNT,
            '_MALE'				=> _MALE,
            '_FEMALE'			=> _FEMALE,
            '_DATABASE'			=> _DATABASE,
            '_KERBEROS'			=> _KERBEROS,
            '_RESET_FORM'			=> _RESET_FORM,
            '_USER_DIR' 			=> $this->cfg['user_dir'],
            '_GROUP_DIR' 			=> $this->cfg['group_dir']));

        $this->tpl->assign('AVATAR_LINK', 'images/default.png');
        $this->tpl->assign('AVATAR_DEMO', 'images/default.png');

        $this->tpl->display('accounts_add.tpl');

        return true;
    }

    /**
    * Adds an account to Flare
    *
    * This method will add a new user account to Flare and will leave it in
    * a 'pending' state by default so that even after the account is created,
    * it will still need to be 'activated' by the administrator before the
    * user can log in.
    *
    * @access public
    * @param array $user_info The information for the user account
    */
    public function do_add_account($user_info) {
        // Require filesystem operations because we'll use them to make the users home directory
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        // Create a new filesystem object
        $fs = new MyFiles();
        $fs->__set("db", $this->__get("db"));

        // Retrieve the default theme stored in configuration
        $theme		= $this->cfg['template'];

        // Get the current time to use as a registration date
        $register_date	= time();

        // Create SQL structure for all SQL we'll use
        $sql = array (
            "mysql" => array (
                'create_account' => "INSERT INTO "._PREFIX."_users (`username`,`password`,`fname`,`lname`,`gender`,`org_email`,`home_dir`,`group_dir`,`theme`,`auth_type`,`register_date`,`status`) VALUES (':1',':2',':3',':4',':5',':6',':7',':8',':9',':10',':11',':12')",
                'select_email' => "SELECT username FROM "._PREFIX."_users WHERE org_email=':1'",
                'select_id' => "SELECT user_id FROM "._PREFIX."_users WHERE username=':1' AND home_dir=':2'"
                )
        );

        // Prepare all the SQL for execution
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['create_account']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['select_email']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['select_id']);

        // Execute SQL to check to see if the user account already exists
        $stmt2->execute($user_info['email']);

        // If the account already exists...
        if ($stmt2->num_rows() == 1) {
            return 1;
        } else {
            $user_home = $fs->normalize_dir($user_info['home_dir']);
            $group_dir = $fs->normalize_dir($user_info['group_dir']);

            // Otherwise, execute SQL to create the actual account
            $stmt1->execute(strtolower($user_info['username']),
                    $user_info['password'],
                    $user_info['fname'],
                    $user_info['lname'],
                    $user_info['gender'],
                    strtolower($user_info['email']),
                    $user_home,
                    $group_dir,
                    $theme,
                    $user_info['auth_type'],
                    $register_date,
                    'P');

            // Execute SQL to check to see if the user account we just created actually exists
            $stmt2->execute($user_info['email']);

            // If it does exist, then we can move on to the next step
            if ($stmt2->num_rows() == 1) {
                $stmt3->execute($user_info['username'], $user_home);

                $result = $stmt3->fetch_assoc();

                // Attempt to make the new home
                @$fs->mk_dir($user_home);

                $fs->add_file_perms_entries($user_home, $result['user_id'], '-', $perms = 'drwxr-xr-x');

                // If the directory creation failed...
                if ($fs->__get("error")) {
                    return 2;
                } else {
                    return 0;
                }
            } else {
                return 3;
            }
        }
    }

    /**
    * Changes the status of an account
    *
    * All newly created accounts have their status set to pending. However that
    * will not allow them to log in. This method will allow one to change the
    * status of a user account to either Activated or Deactivated. Once changed,
    * an accounts status cannot be changed back to pending.
    *
    * @access public
    */
    public function change_status($status, $user_id) {
        $sql = array (
            "mysql" => array (
                'change_status' => "UPDATE "._PREFIX."_users SET status=':1' WHERE user_id=':2'"
                )
        );

        $status = strtoupper($status);

        if ($status != 'A')
            if ($status != 'D')
                $status = 'D';

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['change_status']);

        $stmt->execute($status, $user_id);
    }

    /**
    * Get list of possible status'
    *
    * Accounts can be in one of three states. Either Activated,
    * Deactivated, or Pending. When an account is first created
    * it is put in Pending state. After that, it must be activated.
    * From then on, it can only ever be activated or deactivated.
    * This will return a list of possible states.
    *
    * @access public
    * @return array List of possible status' that can be assigned
    */
    public function get_status_list() {
        $status_list = array(
            0	=> array(
                    'name' => 'Activated',
                    'status' => 'A'
                ),
            1	=> array(
                    'name' => 'Deactivated',
                    'status' => 'D'
                ),
            2	=> array(
                    'name' => 'Pending',
                    'status' => 'P'
                )
        );

        return $status_list;
    }

    /**
    * Get list of account levels
    *
    * Account levels are used for such things as checking
    * whether an account can access a specific area without
    * being logged in. This will retrieve a list of all
    * available levels
    *
    * @access public
    * @return array List of all account levels
    */
    public function get_levels_list() {
        $levels_list = array();
        $sql = array(
            "mysql" => array(
                'levels' => "SELECT DISTINCT(user_level) FROM "._PREFIX."_users ORDER BY user_level ASC",
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['levels']);
        $stmt1->execute();

        while ($row = $stmt1->fetch_array()) {
            $levels_list[] = $row['user_level'];
        }

        return $levels_list;
    }

    /**
    * Returns available authentication types
    *
    * Returns a list of the available type of authentication
    * that can be used in Flare
    *
    * @access public
    * @return array Array of possible means of authentication
    */
    public function get_auth_types() {
        return array('db', 'krb5', 'ldap', 'adldap');
    }

    /**
    * Get account data for changing accounts
    *
    * This method will return all the account information
    * for the requested user accounts
    *
    * @access public
    * @param array $account List of user IDs for accounts to change
    * @return array Array containing all user data indexed by integer
    */
    public function show_change_accounts($account) {
        $account_list = array();
        $count = 0;
        $sql = array (
            "mysql" => array (
                'account_info' => "SELECT * FROM "._PREFIX."_users WHERE user_id=':1' LIMIT 1",
                'countries' => "SELECT * FROM "._PREFIX."_countries ORDER BY country_id ASC",
                'admin_privs' => "SELECT extension_id FROM "._PREFIX."_admin_privs WHERE user_id=':1'",
                'services' => "SELECT * FROM "._PREFIX."_services WHERE user_id=':1' ORDER BY service_name ASC",
                'extensions' => "SELECT extension_id, name FROM "._PREFIX."_extensions ORDER BY name ASC"
                )
        );

        $stmt1 	= $this->db->prepare($sql[_DBSYSTEM]['account_info']);
        $stmt2	= $this->db->prepare($sql[_DBSYSTEM]['countries']);
        $stmt3	= $this->db->prepare($sql[_DBSYSTEM]['admin_privs']);
        $stmt4	= $this->db->prepare($sql[_DBSYSTEM]['extensions']);
        $stmt5	= $this->db->prepare($sql[_DBSYSTEM]['services']);

        foreach ($account as $key => $val) {
            $result 	= '';
            $approved	= 0;
            $activated	= 0;
            $tmp		= array();
            $extension	= array();
            $services	= array();
            $usr_service	= array();

            $stmt1->execute($val);
            $stmt2->execute();
            $stmt3->execute($val);
            $stmt4->execute();
            $stmt5->execute($val);

            // Build the admin privileges
            if ($stmt3->num_rows() != 0) {
                while ($row = $stmt3->fetch_array()) {
                    $tmp[] = $row['extension_id'];
                }
            }

            // Build the list of extensions
            while ($row = $stmt4->fetch_array()) {
                if (empty($tmp)) {
                    $approved = 0;
                } else {
                    foreach ($tmp as $key2 => $val2) {
                        if ($val2 == $row['extension_id']) {
                            $approved = 1;
                            break;
                        }
                    }
                }

                $extension[] = array(
                    'extension_id'	=> $row['extension_id'],
                    'name'		=> $row['name'],
                    'allowed'	=> $approved
                );
                $approved = 0;
            }

            // Make temp array that holds all user service info
            while ($row = $stmt5->fetch_assoc()) {
                $usr_service[] = array(
                    'service_name' 		=> $row['service_name'],
                    'service_status'	=> $row['service_status']
                );
            }

            // Build the list of services
            foreach ($this->__get("service_list") as $key3 => $val3) {
                if (count($usr_service) <= 0)
                    break;

                foreach ($usr_service as $key4 => $val4) {
                    if (($key3 == $val4['service_name']) && ($val4['service_status'] == '1'))  {
                        $activated = 1;
                        break;
                    }
                }

                $services[] = array(
                    'name'		=> $key3,
                    'display'	=> $val3,
                    'activated'	=> $activated
                );
                $activated = 0;
            }

            $result = $stmt1->fetch_array();

            // Create the list of countries.
            $temp = $this->country_list($stmt2, $result);

            $account_list[$count] = array (
                'user_id' 		=> $result['user_id'],
                'username' 		=> $result['username'],
                'fname'			=> $result['fname'],
                'lname'			=> $result['lname'],
                'gender'		=> $result['gender'],
                'age'			=> $result['age'],
                'country'		=> $temp,
                'occupation'		=> $result['occupation'],
                'student_id'		=> $result['student_id'],
                'org_email'		=> $result['org_email'],
                'alternate_email'	=> $result['alternate_email'],
                'home_dir'		=> $result['home_dir'],
                'group_dir'		=> $result['group_dir'],
                'theme'			=> $result['theme'],
                'register_date'		=> strftime("%D %r", $result['register_date']),
                'auth_type'		=> $result['auth_type'],
                'admin_privs'		=> $extension,
                'services'		=> $services,
                'user_level'		=> $result['user_level'],
                'status'		=> $result['status'],
                'public'		=> $result['public']
            );

            ++$count;
        }

        return $account_list;
    }

    /**
    * Deletes an account from Flare
    *
    * This method will delete any number of accounts
    * from Flare. It will take care of removing all
    * user folders, deleting groups, and cleaning up
    * the system.
    *
    * @access public
    * @param array $accounts List of user_id for the accounts to be deleted
    */
    public function do_delete_accounts($accounts) {
        require_once(ABSPATH.'/extensions/Groups/class.Groups.php');
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        $grp = new Groups();
        $grp->__set("db", $this->__get("db"));
        $grp->__set("fs", $this->__get("fs"));

        $fs = new MyFiles();
        $fs->__set("db", $this->__get("db"));

        $group_id_list = array();

        // Create the array structure of all SQL we'll use
        $sql = array (
            "mysql" => array (
                'select_acct_info' => "SELECT * FROM "._PREFIX."_users WHERE user_id=':1' LIMIT 1",
                'select_groups' => "SELECT group_id FROM "._PREFIX."_group_info WHERE admin_id=':1'",
                'delete_account' => "DELETE FROM "._PREFIX."_users WHERE user_id=':1'",
                )
        );

        /**
        * Prepare the SQL queries for executing
        */
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select_acct_info']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['select_groups']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['delete_account']);

        /**
        * If we were passed an array that had no items...
        */
        if (count($accounts) == 0) {
            // Return an error message
            $this->tpl->assign('_MESSAGE',	_ACCTS_DELETE_FAILURE);
            $this->tpl->assign('_RETURN_LINK', 	_ACCTS_DELETE_RETURN_LINK);
        } else {
            /**
            * Otherwise, we need to operate on each account ID we were given
            */
            foreach ($accounts as $key => $val) {
                /**
                * Run the query to pull all the user's group ids for the groups the user admins
                */
                $stmt2->execute($val);

                while ($row = $stmt2->fetch_array()) {
                    $grp->do_delete_groups($val, $row['group_id']);
                }

                /**
                * Run the query to pull the user's info
                */
                $stmt1->execute($val);

                /**
                * Get the actual information from the query
                */
                $result = $stmt1->fetch_array();

                if ($result['home_dir'] != '') {
                    /**
                    * In particular we need their home directory so we can delete it in a second
                    */
                    $user_home	= $fs->normalize_dir($result['home_dir']);

                    /**
                    * Remove the users home directory
                    */
                    $fs->rm_dir($user_home);
                    $fs->remove_file_perms_entries($user_home, 'dir');
                }

                /**
                * Remove the user from the database
                */
                $stmt3->execute($val);
            }

            /**
            * We're done so we can set a success message
            */
            $this->tpl->assign('_MESSAGE', 	_ACCTS_DELETE_SUCCESS);
            $this->tpl->assign('_RETURN_LINK', 	_ACCTS_DELETE_RETURN_LINK);
        }

        /**
        * And print out the page
        */
        $this->tpl->display('actions_done.tpl');
    }

    /**
    * Displays the listed configuration for this extension
    *
    * Will display any configuration settings stored in the
    * _config table that specifically relate to this extension
    *
    * @access public
    * @return bool Always returns true
    */
    public function show_settings() {
        $config = array();
        $sql = array (
            'mysql' => array (
                'config' => "SELECT * FROM "._PREFIX."_config WHERE extension_id=':1'",
                'visible' => "SELECT visible FROM "._PREFIX."_extensions WHERE extension_id=':1'"
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['visible']);

        $stmt1->execute($this->ext->__get('extension_id'));
        $stmt2->execute($this->ext->__get('extension_id'));

        $visible = $stmt2->result(0);

        while ($row = $stmt1->fetch_array()) {
            $config[$row['name']] = array (
                'name' 	=> $row['name'],
                'desc' 	=> $row['description'],
                'value'	=> $row['value']
            );
        }

        // Assign language content
        $this->tpl->assign(array(
            '_UNDO_CHGS'		=> _UNDO_CHGS,
            '_ACCTS_ADM_ADD_ACCT'	=> _ACCTS_ADM_ADD_ACCT
        ));

        // Assign dynamic content
        $this->tpl->assign('CONFIG', $config);
        $this->tpl->assign('VISIBLE', $visible);

        // Display the page
        $this->tpl->display('accounts_config.tpl');

        return true;
    }

    /**
    * Saves extension configuration back to database
    *
    * After making changes to the running configuration of the
    * extension, the settings must be saved back to the database.
    * This method takes care of that process.
    *
    * @access public
    * @param array $settings Settings to be saved to configuration table
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
            $stmt1->execute($val,$key,$this->ext->__get('extension_id'));
        }

        // Assign language constants
        $this->tpl->assign('_MESSAGE', _ACCTS_ADM_SETTINGS_SAVED);
        $this->tpl->assign('_RETURN_LINK', _ACCTS_ADM_SETTINGS_SAVED_RETURN_LINK);

        // Display the page
        $this->tpl->display('actions_done.tpl');
    }

    /**
    * Saves account changes
    *
    * After an admin makes changes to an account, this
    * method will need to be called to save those changes
    * back to the database
    *
    * @access public
    * @param array $acct_info All account info (changed or not) for an account
    */
    public function do_change_accounts($acct_info) {
        $sql = array (
            "mysql" => array (
                "update" => "UPDATE "._PREFIX."_users SET fname=':1',lname=':2',gender=':3',age=':4',country=':5',occupation=':6',org_email=':7',alternate_email=':8', theme=':9',auth_type=':10', user_level=':11', status=':12',public=':13' WHERE user_id=':14'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["update"]);

        $stmt1->execute($acct_info['fname'],
                $acct_info['lname'],
                $acct_info['gender'],
                $acct_info['age'],
                $acct_info['country'],
                $acct_info['occupation'],
                $acct_info['org_email'],
                $acct_info['alternate_email'],
                $acct_info['theme'],
                $acct_info['auth_type'],
                $acct_info['user_level'],
                $acct_info['status'],
                $acct_info['public'],
                $acct_info['user_id']);

    }

    /**
    * Change a users password
    *
    * This will allow the admin to change
    * any accounts password.
    *
    * @access public
    * @param integer $user_id User ID of the account whose password is being changed
    * @param string $new_password New password to set for account
    * @param string $verify_password Verify that the password was typed correctly
    */
    public function do_change_password($user_id,$new_password,$verify_password) {
        $sql = array (
            "mysql" => array (
                'select_password' => "SELECT password FROM "._PREFIX."_users WHERE user_id=':1'",
                'chg_password' => "UPDATE "._PREFIX."_users SET password=':1' WHERE user_id=':2'"
                )
        );

        if ($new_password == $verify_password) {
            $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select_password']);
            $stmt1->execute($user_id);

            $current_db_password = $stmt1->result(0);

            $new_password = md5($new_password);

            $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['chg_password']);
            $stmt2->execute($new_password,$user_id);

            // Assign language constants
            $this->tpl->assign("_MESSAGE", _ACCTS_PASSWD_SAVED);
            $this->tpl->assign("_RETURN_LINK", _ACCTS_RETURN_MAIN);
        } else {
            $this->tpl->assign('_MESSAGE', _ACCTS_PASSWD_FAILURE_MISMATCH);
            $this->tpl->assign("_RETURN_LINK", _ACCTS_RETURN_MAIN);
        }
    }

    /**
    * Changes a users home directory
    *
    * This will change a users home directory and
    * update all areas where this is used. It will
    * also adjust the file permissions.
    *
    * @access public
    * @param integer $user_id ID of the user who is having their home directory moved
    * @param string $home_dir Full path to new home directory
    * @return bool False on failure, true on success
    * @todo Add permission migration
    */
    public function do_change_home_dir($user_id, $home_dir) {
        // Require filesystem operations because we'll use them to make the users home directory
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        // Create a new filesystem object
        $fs = new MyFiles();
        $fs->__set("db", $this->__get("db"));

        $home_dir = $fs->normalize_dir($home_dir);

        $sql = array(
            "mysql" => array(
                "home_dir" => "SELECT home_dir FROM "._PREFIX."_users WHERE user_id = ':1'",
                "update_dir" => "UPDATE "._PREFIX."_users SET home_dir = ':1' WHERE user_id = ':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["home_dir"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["update_dir"]);

        $stmt1->execute($user_id);
        $result = $stmt1->fetch_array();

        if ($result['home_dir'] == $home_dir) {
            $this->__set("error", false);
            return;
        } else {
      if (substr($home_dir,-1,1) == '/')
                $tmp_dest = substr($home_dir,0,-1);
            else
                $tmp_dest = $home_dir;

        $tmp_dest = $fs->normalize_dir($tmp_dest . rand(0,1000000));

        $source = $fs->normalize_dir($result['home_dir']);

            if (is_dir($source) && is_dir($home_dir)) {

                $stmt2->execute($home_dir, $user_id);
                $this->__set("error", false);
                return;

            } else {

                $fs->mv_dir($source, $tmp_dest);

                if (is_dir($tmp_dest)) {
                    $fs->mv_dir($tmp_dest, $home_dir);
                if (is_dir($home_dir)) {
                        $stmt2->execute($home_dir, $user_id);
                        $this->__set("error", false);
                        return;
                    } else {
                        $this->__set("error", true);
                        return;
                    }
                } else {
                    $this->__set("error", true);
                    return;
                }
            }
        }
    }


    /**
    * Change a users base group directory
    *
    * This will migrate a users group directory
    * to a new specified group directory and
    * maintain all permissions for the new folder
    * for the user.
    *
    * @access public
    * @param integer $user_id ID of the user who is having their group directory moved
    * @param string $group_dir Full path to new group directory
    * @return bool False on failure, true on success
    * @todo Add permission migration
    */
    public function do_change_group_dir($user_id, $group_dir) {
        // Require filesystem operations because we'll use them to make the users home directory
        require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');

        // Create a new filesystem object
        $fs = new MyFiles();
        $fs->__set("db", $this->__get("db"));

        $group_dir = $fs->normalize_dir($group_dir);

        $sql = array(
            "mysql" => array(
                "group_dir" => "SELECT group_dir FROM "._PREFIX."_users WHERE user_id = ':1'",
                "update_dir" => "UPDATE "._PREFIX."_users SET group_dir = ':1' WHERE user_id = ':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["group_dir"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["update_dir"]);

        $stmt1->execute($user_id);
        $result = $stmt1->fetch_array();

        if ($result['group_dir'] == $group_dir) {
            $this->__set("error", false);
            return;
        } else {
            if (substr($group_dir,-1,1) == '/')
                $tmp_dest = substr($group_dir,0,-1);
            else
                $tmp_dest = $group_dir;

            $tmp_dest = $fs->normalize_dir($tmp_dest . rand(0,1000000));

            $source = $fs->normalize_dir($result['group_dir']);

            if (is_dir($source) && is_dir($group_dir)) {

                $stmt2->execute($source, $user_id);
                $this->__set("error", false);
                return;

            } else {

                $fs->mv_dir($source, $tmp_dest);

                if (is_dir($tmp_dest)) {
                    $fs->mv_dir($tmp_dest, $group_dir);
                    if (is_dir($group_dir)) {
                        $this->__set("error", false);

                        $stmt2->execute($group_dir, $user_id);
                        $this->__set("error", false);
                        return;
                    } else {
                        $this->__set("error", true);
                        return;
                    }
                } else {
                    $this->__set("error", true);
                    return;
                }
            }
        }
    }

    /**
    * Updates admin privileges for a user
    *
    * Users cannot access an extensions admin area
    * until they have been given the privilege to
    * do so. Only admins of the Account extension
    * can grant this privilege, and when new
    * extensions are installed, they will need to
    * have their privileges granted to all admin
    * accounts that will use them. This method
    * can grant privileges to users.
    *
    * @access public
    * @param integer $user_id User ID of the user who will have admin privs updated
    * @param array $extension_id Array of extension IDs for which to grant privs on, indexed by the extension ID
    */
    public function do_change_admin_permissions($user_id, $extension_id) {
        $sql = array(
            "mysql" => array(
                "delete_perms" => "DELETE FROM "._PREFIX."_admin_privs WHERE user_id=':1'",
                "insert_perms" => "INSERT INTO "._PREFIX."_admin_privs (`user_id`, `extension_id`) VALUES (':1', ':2')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["delete_perms"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["insert_perms"]);

        $stmt1->execute($user_id);

        if (empty($extension_id))
            return;

        foreach ($extension_id as $key => $val) {
            $stmt2->execute($user_id, $key);
        }
    }

    /**
    * Updates services for a user
    *
    * Flare offers several services for the users of the
    * system. Since all the services require running a
    * shell script more or less, I'm trying to use the
    * jobs system to admin the services from the web
    * frontend. This method should take care of inserting
    * jobs in the table for later running.
    *
    * @access public
    * @param integer $user_id User ID of the user who will have services updated
    * @param array $service_name Array of service names for which to change, indexed by the short service name
    */
    public function do_change_services($user_id, $sent_services) {
        $existing_services = array();
        $sql = array(
            "mysql" => array(
                "select_services" => "SELECT service_name,service_status FROM "._PREFIX."_services WHERE user_id=':1'",
                "schedule_job" => "INSERT INTO "._PREFIX."_jobs (`job`) VALUES (':1')",
                "user_nfo" => "SELECT username FROM "._PREFIX."_users WHERE user_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["select_services"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["user_nfo"]);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]["schedule_job"]);

        $stmt1->execute($user_id);
        $stmt2->execute($user_id);

        $username = $stmt2->result(0);

        while ($row = $stmt1->fetch_assoc()) {
            $existing_services[$row['service_name']] = $row['service_status'];
        }

        /**
        * Any missing services from the existing array should
        * just be considered inactive still (0) as opposed to
        * disabled (2) because of course the user may not
        * have activated the service yet
        */
        foreach ($this->__get("service_list") as $key => $val) {
            if (@!is_numeric($existing_services[$key]))
                $existing_services[$key] = 0;
        }

        @ksort($sent_services);
        @ksort($existing_services);

        foreach ($this->__get("service_list") as $key => $val) {
            if ($sent_services[$key] == $existing_services[$key])
                continue;
            else {
                switch($sent_services[$key]) {
                    /**
                    * If the admin is only making a change that effects
                    * the Flare service, then we already have methods
                    * set up to handle this. No job is necessary.
                    * So specifically handle that "job" here. Otherwise,
                    * schedule a job to be run.
                    */
                    case 0:
                        if ($key == 'web') {
                            $page->change_status('P', $user_id);
                            $page->log->log("PENDING_ACCOUNT", $user_id);
                        } else {
                            $job = "set_pending_service_".$key."::".$username;

                            $stmt3->execute($job);
                        }
                        break;
                    case 1:
                        if ($key == 'web') {
                            $page->change_status('A', $user_id);
                            $page->log->log("ACTIVATE_ACCOUNT", $user_id);
                        } else {
                            $job = "add_service_".$key."::".$username."::".rand(0,1000000);

                            $stmt3->execute($job);
                        }
                        break;
                    case 2:
                        if ($key == 'web') {
                            $page->change_status('D', $user_id);
                            $page->log->log("DEACTIVATE_ACCOUNT", $user_id);
                        } else {
                            $job = "disable_service_".$key."::".$username;

                            $stmt3->execute($job);
                        }
                        break;
                }
            }
        }
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
