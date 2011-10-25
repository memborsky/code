<?php
/**
* @package Language
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/

/**
* Copyright (C) 2004-2005 Tim Rupp
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

/* Login template specific texts */
define ("_WELCOME_BANNER", "Welcome to Flare");
define ("_LOGIN_NEW_USER_MSG", "<h3>New Users</h3>"
    . "If you havent gotten started with FUEL and Flare, you're missing out!<p />"
    . "Each Flare user receives..."
    . "<ul>"
    . "<li>1 GB of remote, online file storage</li>"
    . "<li>Your own personal webspace for hosting a website at Indiana Tech.</li>"
    . "<li>Ability to easily share files with groups of friends</li>"
    . "<li>And so much more..."
    . "</ul><p />"
    . "Accounts are only available to students of the schools listed below."
    . "<ul>"
    . "<li>School of Engineering</li>"
    . "<li>School of Computer Studies</li>"
    . "</ul>"
    . "If you fall under this category, <a href='https://flare.indianatech.net/osc'>activate</a> your "
    . "Flare account using your INDIANATECHNET account now!<p />");
define ("_LOGIN_FAILURE_MESG", "The username or password you provided is incorrect. Please try logging in again.");
define ("_LOGIN_LOGO", "Flare Logo");
define ("_LOGIN_SYS_ANNOUNCE", "System Wide Announcements");
define ("_LOGIN_TIMEOUT", "You have been logged out due to inactivity. Please log in again.");
define ("_LOGIN_BUTTON", "SIGN IN");

/* Authentication System specific texts (Admin only) */
define ("_AUTH_SETTINGS_SAVED", "Settings for the Authentication Extension have been saved");
define ("_AUTH_SETTINGS_SAVED_RETURN_LINK", "<a href='admin.php?extension=Authentication&amp;action=show_settings'>Return to Authentication Admin</a>");
define ("_AUTH_USERNAME", "Username");

?>
