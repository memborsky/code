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

/**
* Base Authentication class
*
* This is a base class for authentication it should
* be extended by other classes because by itself
* it only performs very general tasks and isnt very
* useful
*
* @package Authentication
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Authentication {
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
    * Type of authentication to use to authenticate the user
    *
    * @access private
    * @var string
    */
    private $auth_type;

    /**
    * Creates an instance of Authentication class
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
    * Determines the type of authentication
    *
    * Because Flare supports authentication type on a per user
    * basis, we need to find out the type of authentication to
    * use when the user is trying to log in. This will set the
    * current type that is assigned to the user to the class
    * varaible 'auth_type' upon completion. If no authentication
    * type is set, database authentication will be the default.
    *
    * @access public
    * @param string $username The username of the account trying to authenticate
    */
    public function determine_auth_type($username) {
        $sql = array (
            "mysql" => array (
                'auth_type' => "SELECT auth_type FROM "._PREFIX."_users WHERE username='$username'"
                )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['auth_type']);
        $stmt->execute($username);

        if ($stmt->num_rows() == 0){
            $this->__set("auth_type", "db");
        } else {
            $row = $stmt->fetch_array();

            $auth_type = $row['auth_type'];

            $this->__set("auth_type", $auth_type);
        }
    }

    /**
    * Authentication factory
    *
    * This is a loader for the different types of
    * authentication that flare supports. Based on
    * the given type, it will load the appropriate
    * files for running authentication on the using
    * using the given type.
    *
    * @access public
    * @param string $type Type of authentication to load
    * @return object Object containing methods to authenticate user using authentication type
    */
    public function auth_factory($type) {
        switch($type) {
            case "db":
                if (!file_exists(ABSPATH.'/extensions/Authentication/class.Authentication_DB.php'))
                    die (_AUTH_SYS_NO_EXIST);

                require_once(ABSPATH.'/extensions/Authentication/class.Authentication_DB.php');
                return new Authentication_DB();
                break;
            case "krb5":
                if (!file_exists(ABSPATH.'/extensions/Authentication/class.Authentication_Kerberos.php'))
                    die (_AUTH_SYS_NO_EXIST);

                // Extension is called libphpkrb5 but is registered as just 'krb5' in php
                if (!extension_loaded('krb5'))
                    die (_AUTH_SYS_NO_EXT);

                require_once(ABSPATH.'/extensions/Authentication/class.Authentication_Kerberos.php');
                return new Authentication_Kerberos();
                break;
            case "ldap":
                if (!file_exists(ABSPATH.'/extensions/Authentication/class.Authentication_LDAP.php'))
                    die (_AUTH_SYS_NO_EXIST);

                require_once(ABSPATH.'/extensions/Authentication/class.Authentication_LDAP.php');
                return new Authentication_LDAP();
                break;
            case "adldap":
                if (!file_exists(ABSPATH.'/extensions/Authentication/class.Authentication_adLDAP.php'))
                    die (_AUTH_SYS_NO_EXIST);

                require_once(ABSPATH.'/extensions/Authentication/class.Authentication_adLDAP.php');
                return new Authentication_adLDAP();
                break;
            default:
                if (!file_exists(ABSPATH.'/extensions/Authentication/class.Authentication_DB.php'))
                    die (_AUTH_SYS_NO_EXIST);

                require_once(ABSPATH.'/extensions/Authentication/class.Authentication_DB.php');
                return new Authentication_DB();
                break;
        }
    }

    /**
    * Displays contents of a system announcement
    *
    * At the login screen, only snippets of system announcements
    * are displayed to the users. By clicking on a particular
    * snippet, they can view the full announcement. This method
    * displays to the user the full announcement.
    *
    * @access public
    * @param integer $id ID of announcement which is to be displayed
    * @return bool Always returns true
    */
    public function show_announcement($id = '') {
        // Check to see if the user clicked on a system announcement to view its full contents
        if ($id == '')
            $this->tpl->assign('LOGIN_PAGE_MESG', _LOGIN_NEW_USER_MSG);
        else
            $this->announcement_details($id);

        $this->show_announcements();

        $this->tpl->assign(array(
            '_LOGIN_LOGO'		=> _LOGIN_LOGO,
            '_WELCOME_BANNER'	=> _WELCOME_BANNER,
            '_AUTH_USERNAME'	=> _AUTH_USERNAME,
            '_USR_USERNAME'		=> _USR_USERNAME,
            '_USR_PASSWORD'		=> _USR_PASSWORD,
            '_LOGIN_BUTTON'		=> _LOGIN_BUTTON,
            '_NO_NEWS'		=> _NO_NEWS,
            '_LOGIN_SYS_ANNOUNCE'	=> _LOGIN_SYS_ANNOUNCE));

        $this->tpl->display('login.tpl');

        return true;
    }

    /**
    * Displays login page
    *
    * This will display the login page to the user so that
    * they can authenticate and use the system.
    *
    * @access public
    * @return bool Always returns true
    */
    public function show_login() {
        $this->show_announcements();

        $this->tpl->assign(array(
            '_LOGIN_LOGO'		=> _LOGIN_LOGO,
            '_WELCOME_BANNER'	=> _WELCOME_BANNER,
            '_AUTH_USERNAME'	=> _AUTH_USERNAME,
            '_USR_USERNAME'		=> _USR_USERNAME,
            '_USR_PASSWORD'		=> _USR_PASSWORD,
            '_LOGIN_BUTTON'		=> _LOGIN_BUTTON,
            '_NO_NEWS'		=> _NO_NEWS,
            '_LOGIN_SYS_ANNOUNCE'	=> _LOGIN_SYS_ANNOUNCE,
            'LOGIN_PAGE_MESG'	=> _LOGIN_NEW_USER_MSG));

        $this->tpl->display('login.tpl');

        return true;
    }

    /**
    * Displays a list of announcements
    *
    * This will assign to template variables a list of
    * the 5 most recent system announcements.
    *
    * @access public
    * @return bool Always returns true
    */
    public function show_announcements() {
        $mesg 		= array();
        $mesg_date	= "";
        $mesg_content	= array();

        $sql = array (
            "mysql" => array (
                'login_messages' => "SELECT * FROM `"._PREFIX."_messages` ORDER BY mesg_id DESC LIMIT 5",
            )
        );

        if ($this->cfg['use_reporting']) {
            $stmt = $this->db->prepare($sql[_DBSYSTEM]['login_messages']);
            $stmt->execute();

            /**
            * Populate system announcement arrays before they are assigned to Smarty
            */
            while ($row = $stmt->fetch_array()) {
                $temp_content 	= "";
                $mesg_date[]	= date("m-d-Y", $row['date']);

                if (strlen($row['content']) > 50) {
                    $temp_content = array(
                        'id'		=> $row['mesg_id'],
                        'subject'	=> $row['subject'],
                        'content' 	=> $row['content'],
                        'date'		=> strftime("%m-%d-%Y", $row['date'])
                    );

                    array_push($mesg_content, $temp_content);
                } else {
                    array_push($mesg_content, $row['content']);
                }
            }
        }

        $this->tpl->assign('MESG_DATE', $mesg_date);
        $this->tpl->assign('MESG_CONTENT', $mesg_content);

        return true;
    }

    /**
    * Retrieves the details of an announcement
    *
    * Because only a short list with announcement snippets
    * is shown to the user on the main page, this method
    * will fetch the full contents of a particular
    * announcement so that the user can read the details
    *
    * @access public
    * @param integer $announcement_id ID of the announcement whose details are being viewed
    * @return bool Always returns true
    */
    public function announcement_details($announcement_id) {
        $sql = array (
            "mysql" => array (
                "message" => "SELECT username, date, content FROM "._PREFIX."_messages LEFT JOIN "._PREFIX."_users ON author_id=user_id WHERE mesg_id = ':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['message']);
        $stmt1->execute($announcement_id);

        $row = $stmt1->fetch_assoc();

        $this->show_announcements();

        $this->tpl->assign('AUTHOR', $row['username']);
        $this->tpl->assign('DATE', strftime("%m-%d-%Y", $row['date']));
        $this->tpl->assign('LOGIN_PAGE_MESG', $row['content']);

        return true;
    }
}

?>
