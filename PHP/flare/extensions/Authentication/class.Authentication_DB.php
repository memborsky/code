<?php
/**
* @package Authentication
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

require_once(ABSPATH.'/extensions/Authentication/class.Authentication.php');

/**
* Database authentication tools
*
* Acts as an abstraction layer for authentication
* to a backend database. Currently only MySQL is
* supported, however Postgre is planned for the future
*
* @package Authentication
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Authentication_DB extends Authentication {
    /**
    * Determines if the user is authenticated or not
    *
    * @access private
    * @var bool
    */
    private $authenticated;

    /**
    * Privilege level of the user
    *
    * @access private
    * @var integer
    * @deprecated Deprecated because it is not really used anywhere
    */
    private $user_level;

    /**
    * Username of the user authenticating
    *
    * @access private
    * @var string
    */
    private $username;

    /**
    * Creates an instance of Authenication_DB class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided. This constructor begins by setting
    * several variables for use in authenticating a user using later
    * methods.
    *
    * @access public
    */
    public function __construct() {
        $this->__set("username", ((@import_var('username', 'S') == "") ? 'guest' : import_var('username', 'S')));
        $this->__set("user_level", (@import_var('user_level', 'S')) ? import_var('user_level', 'S') : '1000');
        $this->__set("authenticated", FALSE);
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
    * Determines if a user is authenticated
    *
    * Responsible for determining if a user has
    * the proper access rights to an extension.
    * This is different from the admin authentication
    * which can also be used to access the admin area
    *
    * @access public
    * @param string $extension Extension name user wishes to have access to
    * @return bool Sets 'authenticated' class variable to true on success and false on failure
    */
    public function authenticate($extension) {
        // Set up array of SQL statements we will use
        $sql = array (
            "mysql" => array (
                'user_account' => "SELECT user_id FROM "._PREFIX."_users WHERE username=':1' AND status='A' LIMIT 1",
                'ext_level' => "SELECT `displayed_name` FROM "._PREFIX."_extensions WHERE `name`=':1' AND `user_min_level` >= ':2'"
                )
        );

        // Prepare SQL for execution
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['user_account']);

        $username = (@import_var('action', 'P') == "login") ? import_var('username', 'P') : import_var('username', 'S');

        // Run SQL to select user_id given a username
        $stmt1->execute($username, $this->__get("user_level"));

        //  If one row was returned...
        if ($stmt1->num_rows() > 0) {
            // ...prepare any SQL we can at this point in time
            $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['ext_level']);

            // Execute all prepared SQL and pass vars to SQL that is executed
            $stmt2->execute($extension, $this->__get("user_level"));

            /**
            * If the user's level is less than or equal to the required minimum user level
            * for the extension being requested...
            */
            if ($stmt2->num_rows() > 0) {
                $this->__set("authenticated", true);
            } else {
                $this->__set("authenticated", false);
            }
        } else {
            if ($username != "guest") {
                // If no results were returned, then we need to output an error message
                $this->tpl->assign('_MESSAGE', _LOGIN_FAILURE_MESG);
            }

            if ($extension == "Authentication") {
                $this->__set("authenticated", true);
                return true;
            } else {
                // and make sure the user isnt authenticated
                $this->__set("authenticated", false);
            }
        }
    }

    /**
    * Determines if a user can access an admin area
    *
    * Responsible for determining if a user has the
    * proper admin access rights to an extension. This
    * will allow them to access administrative tools
    * that they otherwise would not have access to.
    *
    * @access public
    * @param integer $user_id The user ID of the authenticating user
    * @return bool Returns true on success and false on failure
    */
    public function authenticate_admin($user_id) {
        // Set up the array of SQL that we will execute in this function
        $sql = array (
            "mysql" => array (
                'user_account' => "SELECT user_id FROM "._PREFIX."_admin_privs WHERE user_id=':1' AND extension_id=':2'",
                )
        );

        // Prepare the first SQL query. Selecting the user_id
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['user_account']);

        $stmt1->execute($user_id, $this->ext->__get("extension_id"));

        // If a result was returned, work with it
        if ($stmt1->num_rows() > 0) {
            $this->__set("authenticated", true);
        } else {
            // Otherwise, no rows were returned (no users found) and we need to print an error
            $this->tpl->assign('_MESSAGE', _LOGIN_FAILURE_MESG);

            // And then make sure the user is not authenticated
            $this->__set("authenticated", false);
        }
    }

    /**
    * Logs a user into the system
    *
    * Logging in involves setting a host of session
    * variables that are later used all over the
    * system. While authentication is supposed to be
    * a configurable option, much more works needs to
    * go into actually making this a reality.
    *
    * @access public
    * @param string $username The username of the user logging in
    * @param string $password Password of the user logging in
    * @return Sends user to main page on success. Sends user back to login on failure
    */
    public function login($username = 'guest', $password = '') {
        // Set up the array of SQL that we will use
        $sql = array (
            "mysql" => array (
                'login' => "SELECT * FROM `"._PREFIX."_users` WHERE `username`=':1' AND `password`=':2' AND status='A' LIMIT 1",
                'admin_login' => "SELECT * FROM `"._PREFIX."_users` WHERE `username`=':1' AND `password`=':2' AND status='A' AND admin_flags IS NOT NULL LIMIT 1",
                'update_last_logon' => "UPDATE `"._PREFIX."_users` SET last_login=:1 WHERE `username`=':2'",
                )
        );

        // Finally, destroy the session.
        session_destroy();

        // Start a brand new session for the (potentially) brand new user
        session_start();

        // Start over with new session, establish users session data.
        session_register('user_id');
        session_register('username');
        session_register('real_name');
        session_register('student_id');
        session_register('org_email');
        session_register('alternate_email');
        session_register('home_dir');
        session_register('group_dir');
        session_register('theme');
        session_register('admin_flags');
        session_register('user_level');
        session_register('file_list');
        session_register('website_url');
        session_register('quota_total');
        session_register('quota_used');
        session_register('last_access');

        // Replace any blank spaces with underscores
        $username 	= str_replace(" ", "_", strtolower($username));

        // MD5 hash the users password so it will be in correct format for querying database with
        $password 	= md5($password);

        // Store the current time in a local var
        // This is used to update the last_login_date on the user
        $time		= time();

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['login']);

        // Also we need to prepare the SQL to update the users last login date if
        // they do sucessfully log in
        $stmt2	= $this->db->prepare($sql[_DBSYSTEM]['update_last_logon']);

        // Execute the first SQL query. This is the user SQL
        $stmt->execute($username, $password);

        // If no results were returned, then login failed
        if ($stmt->num_rows() == 0) {
            // Assign a failure message
            $this->tpl->assign('_MESSAGE', _LOGIN_FAILURE_MESG);

            // Assign an empty header...
            assign_header('EMPTY');

            // ...and our default footer...
            assign_footer();

            // and send the user packing back to the login page.
            $this->show_login();

            $this->log->log('LOGIN_FAILURE', $username, time());
        } else {
            // Otherwise, we received a row

            // Pull out the one row we received
            $results = $stmt->fetch_array();

            // Assign all the row data to the particular session variables
            $_SESSION['user_id']		= $results['user_id'];
            $_SESSION['username'] 		= $results['username'];
            $_SESSION['real_name']		= $results['fname'] . " " . $results['lname'];
            $_SESSION['student_id']		= $results['student_id'];
            $_SESSION['org_email']		= $results['org_email'];
            $_SESSION['alternate_email']	= $results['alternate_email'];
            $_SESSION['home_dir']		= ($results['home_dir'] == "") ? $this->cfg['user_dir'] . $results['username'] : $results['home_dir'];
            $_SESSION['group_dir']		= ($results['group_dir'] == "") ? $this->cfg['group_dir'] . $results['username'] : $results['group_dir'];
            $_SESSION['theme']		= $results['theme'];
            $_SESSION['admin_flags']	= $results['admin_flags'];
            $_SESSION['user_level']		= $results['user_level'];
            $_SESSION['file_list']		= array();
            $_SESSION['website_url']	= $results['website_url'];
            $_SESSION['quota_total']	= $results['quota_total'];
            $_SESSION['quota_used']		= $results['quota_used'];
            $_SESSION['last_access']	= time();

            // Execute the update for user last login
            $stmt2->execute($time, $username);

            // Tell the system the user is authenticated
            $this->__set("authenticated", TRUE);

            // Set the username of the user in our authentication object
            $this->__set("username", $username);

            // Set their user_level also
            $this->__set("user_level", $results['user_level']);

            // If they were trying to authenticate as admin, and have gotten this far
            if ($admin_login) {
                // Then they can head on through to the admin page
                header("Location: admin.php?extension=Accounts");
            } else {
                // Otherwise, they were only asking for regular login. So take
                // them to the regular login page
                header("Location: index.php?extension=Accounts");
            }

            $this->log->log('LOGIN_SUCCESS', $username, time());
        }
    }

    /**
    * Logs a user out of the system
    *
    * Because we use sessions for maintaining authentication
    * across pages, by destroying the session and setting the
    * authentication status to false, we basically log the
    * user out.
    *
    * @access public
    * @return bool Always returns true
    */
    public function logout() {
        // Nuke their session
        session_destroy();

        // Tell the system they cant come back in
        $this->__set("authenticated", FALSE);

        // And show them the door
        $this->show_login();

        return true;
    }
}

?>
