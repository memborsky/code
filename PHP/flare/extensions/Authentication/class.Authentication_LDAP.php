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
* LDAP authentication tools
*
* Acts as an abstraction layer for authentication
* to an LDAP server
*
* @package Authentication
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Authentication_LDAP extends Authentication {
    /**
    * Creates an instance of Authenication_LDAP class
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
        global $tpl;

        $tpl->assign("_AUTH_TYPE", _AUTH_TYPE);
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

    }
}

?>
