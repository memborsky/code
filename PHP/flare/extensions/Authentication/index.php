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

require_once(ABSPATH.'/extensions/Authentication/lang/lang-'.$cfg['language'].'.php');

/**
* There is no need to create an authentication object
* on this index page because the authentication object
* is already created on the base index.php located two
* directories up. Therefore we simply reuse that one
*/

switch($flare_action) {
    case "login":
        $username = import_var("username", 'P');
        $password = import_var("password", 'P');

        $auth->login($username, $password);
        break;
    case "logout":
        $username = import_var('username', 'S');

        $auth->logout();
        $auth->log->log('LOGOUT', $username, time());
        break;
    case "announcement":
        $auth->show_announcement(import_var('id', 'G'));
        break;
    default:
        break;
}

?>
