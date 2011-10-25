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
* Accounts extension
*
* Contains the methods and variables needed to maintain user accounts
* in Flare. The tools provided are userland tools as opposed to admin
* tools. Therefore these function can be used by the end user to modify
* their own account
*
* @package Accounts
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Accounts {
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
    * Barebones constructor
    *
    * This constructor will override the default one supplied by PHP.
    * In this context the constructor does not do anything, so it is
    * left up to future developers to decide if it will even be used.
    *
    * @access public
    */
    public function __construct() {
    }

    /**
    * Generic getter method
    *
    * Gets the value of any class variable using the supplied variable name
    *
    * @access public
    * @param string $key Name of the variable whose value you wish to get
    * @return mixed|NULL The value of the named variable
    */
    public function __get( $key ) {
        return isset( $this->$key ) ? $this->$key : NULL;
    }

    /**
    * Generic setter method
    *
    * Sets the value of any given variable name to any given value
    *
    * @access public
    * @param string $key Name of the variable whose value you wish to set
    * @param mixed $value Value which you wish to set to variable
    */
    public function __set( $key, $value ) {
        $this->$key = $value;
    }

    /**
    * Displays the accounts setting page
    *
    * This will display to the end user the account settings page where
    * they can make various changes to their personal account on Flare
    *
    * @access public
    * @param integer $user_id The user ID of the account whose settings should be displayed
    * @return bool Always returns true
    */
    public function show_user_default_page($user_id) {
        $current_country = '';

        $sql = array (
            "mysql" => array (
                'user_data'	=> "SELECT * FROM "._PREFIX."_users WHERE `user_id`=':1' LIMIT 1",
                'countries'	=> "SELECT * FROM "._PREFIX."_countries ORDER BY country_id ASC",
                )
        );

        $stmt 	= $this->db->prepare($sql[_DBSYSTEM]['user_data']);
        $stmt2	= $this->db->prepare($sql[_DBSYSTEM]['countries']);

        $stmt->execute($user_id);
        $stmt2->execute();

        $result = $stmt->fetch_assoc();

        // Create the list of countries.
        $temp = $this->country_list($stmt2, $result);

        // Assign language constants
        $this->tpl->assign(array(
            '_ACCTS_WELCOME' 	=> _ACCTS_WELCOME,
            '_ACCTS_PERSONAL_INFO' 	=> _ACCTS_PERSONAL_INFO,
            '_ACCTS_FNAME'		=> _ACCTS_FNAME,
            '_ACCTS_LNAME'		=> _ACCTS_LNAME,
            '_ACCTS_GENDER' 	=> _ACCTS_GENDER,
            '_ACCTS_AGE' 		=> _ACCTS_AGE,
            '_ACCTS_COUNTRY' 	=> _ACCTS_COUNTRY,
            '_ACCTS_OCCUPATION' 	=> _ACCTS_OCCUPATION,
            '_ACCTS_CHG_PASSWD' 	=> _ACCTS_CHG_PASSWD,
            '_ACCTS_CNCT_SETTINGS' 	=> _ACCTS_CNCT_SETTINGS,
            '_ACCTS_ORG_EMAIL' 	=> _ACCTS_ORG_EMAIL,
            '_ACCTS_ALT_EMAIL' 	=> _ACCTS_ALT_EMAIL,
            '_ACCTS_WEBSITE' 	=> _ACCTS_WEBSITE,
            '_ACCTS_SHOW_SETTINGS'	=> _ACCTS_SHOW_SETTINGS,
            '_ACCTS_NO_COUNTRY'	=> _ACCTS_NO_COUNTRY,
            '_ACCTS_MISC'		=> _ACCTS_MISC,
            '_SAVE_CHGS'		=> _SAVE_CHGS,
            '_UNDO_CHGS'		=> _UNDO_CHGS,
            '_ACCTS_VIEW_PUBLIC'	=> _ACCTS_VIEW_PUBLIC,
            '_MALE'			=> _MALE,
            '_FEMALE'		=> _FEMALE,
            '_YES'			=> _YES,
            '_NO'			=> _NO));

        // Assign dynamic content and other vars
        $this->tpl->assign(array(
            'FNAME' 	=> $result['fname'],
            'LNAME' 	=> $result['lname'],
            'GENDER' 	=> $result['gender'],
            'AGE' 		=> $result['age'],
            'COUNTRIES' 	=> $temp,
            'OCCUPATION'	=> $result['occupation'],
            'ORG_EMAIL' 	=> $result['org_email'],
            'ALT_EMAIL'	=> $result['alternate_email'],
            'PUBLIC'	=> $result['public'],
            'AVATAR_DEMO' 	=> 'images/default.png'));

        // The template is ready to be displayed. Show it
        $this->tpl->display('accounts_main.tpl');
        return true;
    }

    /**
    * Create country list
    *
    * Using the countries database table, this method will parse and format
    * all the known countries and return a value that includes this parsed
    * content. The content will be a select box that has major countries
    * sectioned off using <optgroup> HTML tags
    *
    * @access public
    * @param object $stmt2 The database object containing the query to the countries table
    * @param array $result The current user data whose account is being queried
    * @return string $temp The full select list of all the countries. The users currect country will be selected.
    */
    public function country_list(&$stmt2,&$result) {
        if (is_null($result))
            $result = array();

        $current_country 	= "";
        $temp 			= '';

        while ($row = $stmt2->fetch_array()) {
            if ($current_country == $row['major']) {
                /**
                * Checks to see if we should set the current country we're working on
                * as the user's previously set default country
                */
                if ($row['country_id'] == $result['country'])
                    $temp .= "<option value='$row[country_id]' SELECTED>$row[minor]</option>";
                else
                    $temp .= "<option value='$row[country_id]'>$row[minor]</option>";
            } else {
                // Simple logic used to create the different country groups
                if ($current_country == "") {
                    $temp = "<optgroup label='$row[major]'>";
                    $current_country = $row['major'];
                } else {
                    $temp .= "</optgroup>";
                    $temp .= "<optgroup label='$row[major]'>";
                    $current_country = $row['major'];
                }
            }
        }
        $temp .= "</optgroup>";
        return $temp;
    }

    /**
    * Shows the account creation form
    *
    * This is used to register new accounts without much admin intervention.
    * It will display a form from which new users can register with Flare.
    * Currently this code is not implemented anywhere because account registration
    * is an admin privilege because of the way Flare is made to be used at Tech.
    *
    * @access public
    * @deprecated Deprecated since 1.0 It may be reused in a general context in the future however
    * @return bool Always returns true
    */
    public function show_create() {
        // Assign language constants
        $this->tpl->assign(array(
            '_CREATE_ACCT_MSG_1'		=> _CREATE_ACCT_MSG_1,
            '_CREATE_ACCT_MSG_2' 		=> _CREATE_ACCT_MSG_2,
            '_CREATE_ACCT_MSG_3' 		=> _CREATE_ACCT_MSG_3,
            '_CREATE_ACCT_MSG_4' 		=> _CREATE_ACCT_MSG_4,
            '_CREATE_ACCT_MSG_5' 		=> _CREATE_ACCT_MSG_5,
            '_CREATE_ACCT_MSG_6' 		=> _CREATE_ACCT_MSG_6,
            '_CREATE_ACCT_MSG_7' 		=> _CREATE_ACCT_MSG_7,
            '_CREATE_ACCT_MSG_8' 		=> _CREATE_ACCT_MSG_8,
            '_CREATE_ACCT_MSG_9' 		=> _CREATE_ACCT_MSG_9,
            '_CREATE_ACCT_MSG_10'		=> _CREATE_ACCT_MSG_10,
            '_CREATE_ACCT_MSG_11' 		=> _CREATE_ACCT_MSG_11,
            '_CREATE_ACCT_MSG_12' 		=> _CREATE_ACCT_MSG_12,
            '_CREATE_ACCT_SCHOOL_1' 	=> _CREATE_ACCT_SCHOOL_1,
            '_CREATE_ACCT_SCHOOL_2' 	=> _CREATE_ACCT_SCHOOL_2,
            '_NAME' 			=> _NAME,
            '_STUDENT_ID' 			=> _STUDENT_ID,
            '_TECH_EMAIL'			=> _TECH_EMAIL,
            '_TECH_EXT' 			=> _TECH_EXT,
            '_FLARE_INFO' 			=> _FLARE_INFO,
            '_CREATE_ACCT_REQ_USERNAME'	=> _CREATE_ACCT_REQ_USERNAME,
            '_CREATE_ACCT_CHK_AVAIL' 	=> _CREATE_ACCT_CHK_AVAIL,
            '_CREATE_ACCT_SEC_EMAIL'	=> _CREATE_ACCT_SEC_EMAIL,
            '_CREATE_ACCT_WHY' 		=> _CREATE_ACCT_WHY,
            '_CREATE_ACCT_WHY_RECORD' 	=> _CREATE_ACCT_WHY_RECORD,
            '_USR_PASSWORD' 		=> _USR_PASSWORD,
            '_VER_USR_PASSWORD' 		=> _VER_USR_PASSWORD));

        // Second, assign dynamic content and other vars
        $this->tpl->assign(array(
            '_CURRENT_IP' 		=> $_SERVER['REMOTE_ADDR'],
            '_CURRENT_BROWSER'	=> $_SERVER['HTTP_USER_AGENT'],
            '_CURRENT_TIME'		=> time()));

        // Display page
        $this->tpl->display('create_account.tpl');

        return true;
    }

    /**
    * Updates a users account info
    *
    * Will update all editable fields for a users account given the field
    * information.
    *
    * @access public
    * @param array $acct_info The account info to be saved back to the database
    * @todo Move template code to index page.
    * @todo Provide database checking to make sure the info was sucessfully updated
    */
    public function do_update_acct_info($acct_info) {
        $sql = array (
            "mysql" => array (
                'update' => "UPDATE "._PREFIX."_users SET fname=':1',lname=':2',gender=':3',age=':4',country=':5',occupation=':6',alternate_email=':7',public=':8' WHERE username=':9'"
                )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['update']);
        $stmt->execute(	$acct_info['user_fname'],
                $acct_info['user_lname'],
                $acct_info['user_gender'],
                $acct_info['user_age'],
                $acct_info['user_country'],
                $acct_info['user_occupation'],
                $acct_info['user_email'],
                $acct_info['public'],
                $acct_info['username']
        );

        // Assign language constants
        $this->tpl->assign('_MESSAGE',		_ACCTS_SETTINGS_SAVED_THANKS);
        $this->tpl->assign('_RETURN_LINK', 	_ACCTS_RETURN_MAIN);

        // Display page
        $this->tpl->display('actions_done.tpl');

    }

    /**
    * Changes a users password
    *
    * This method will change a users password if that user is using
    * database authentication to access their Flare account. This will
    * not affect passwords that are authenticated using LDAP or Kerberos
    *
    * @access public
    * @param integer $user_id The user ID of the account whose password is to be changed
    * @param string $current_password The users current password as submitted via form
    * @param string $new_password The users new password as submitted via form
    * @param string $verify_password The users new password again to verify they typed it correctly
    * @todo Check to make sure the update to the password field finished correctly
    */
    public function do_change_password($user_id,$current_password,$new_password,$verify_password) {
        $sql = array (
            "mysql" => array (
                'select_password' => "SELECT password FROM "._PREFIX."_users WHERE user_id=':1'",
                'chg_password' => "UPDATE "._PREFIX."_users SET password=':1' WHERE user_id=':2'"
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select_password']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['chg_password']);

        if ($new_password == $verify_password) {
            $stmt1->execute($user_id);

            $current_db_password = $stmt1->result(0);
            if (md5($current_password) == $current_db_password) {
                $new_password = md5($new_password);

                $stmt2->execute($new_password,$user_id);

                $this->log->log('PASSWORD_CHANGE', $user_id, time());

                // Assign language constants
                $this->tpl->assign("_MESSAGE", _ACCTS_PASSWD_SAVED);
                $this->tpl->assign("_RETURN_LINK", _ACCTS_RETURN_MAIN);
            } else {
                $this->tpl->assign("_MESSAGE", _ACCTS_PASSWD_FAILURE_WRONG_CURRENT);
                $this->tpl->assign("_RETURN_LINK", _ACCTS_RETURN_MAIN);
            }
        } else {
            $this->tpl->assign('_MESSAGE', _ACCTS_PASSWD_FAILURE_MISMATCH);
            $this->tpl->assign("_RETURN_LINK", _ACCTS_RETURN_MAIN);
        }

        // Display page
        $this->tpl->display("actions_done.tpl");
    }
}

?>
