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

/* General texts */
define ("_NAME", "Name");
define ("_STUDENT_ID", "Student ID");
define ("_TECH_EMAIL", "Indiana Tech Email Address");
define ("_ORG_EXT", "@IndianaTech.net");
define ("_FLARE_INFO", "Flare Information");
define ("_USR_USERNAME", "Username");
define ("_USR_PASSWORD", "Password");
define ("_VER_USR_PASSWORD", "Verify Password");
define ("_ERROR_NO_AUTH", "You do not have permission to use this system. Please try logging in again");
define ("_DEBUG_MODE", "<center>Flare is running in debugging mode. All available symbols will be dumped to "
        . "the debugging console while this mode is enabled.</center>");
define ("_COPYLEFT", "Copyleft FUEL");
define ("_PROJECT_OF", "<a href='index.php'>Flare</a> is a project of FUEL and the ACM Open Source Committee");
define ("_LICENSE", "Licensed under GPL -- version ");
define ("_NAVIGATION", "Navigation");
define ("_NEWS", "News");
define ("_NO_NEWS", "There are no current system announcements");
define ("_INVITES", "Invitations");
define ("_NO_INVITES", "You have no invitations pending");
define ("_AUTH_SYS_NO_EXIST", "The Authentication System is not installed or cannot be found. Please reinstall it.");
define ("_WITH_SELECTED", "With Selected");
define ("_NO_ACTIONS", "No Actions Available");
define ("_FILE", "File");
define ("_SIZE", "Size");
define ("_DATE_MODIFIED", "Date Modified");
define ("_ITEMS", "Item(s)");
define ("_FILES", "Files");
define ("_TOTAL", "Total");
define ("_FOLDERS", "Folder(s)");
define ("_LINK", "Link");
define ("_DESCRIPTION", "Description");
define ("_DELETE", "Delete");
define ("_EDIT", "Edit");
define ("_CHOOSE_ACTION", "Choose Actions");
define ("_NONE", "None");
define ("_NOT_IMPLEMENTED", "This feature of Flare has not been implemented yet. Sorry");
define ("_NO_ADMIN_PAGE", "This feature of Flare does not include an admin page.");
define ("_MALE", "Male");
define ("_FEMALE", "Female");
define ("_YES", "Yes");
define ("_NO", "No");
define ("_LOCATION", "Location");
define ("_MYSQL_NO_ROOT", "root cannot be used by Flare to access the database!<p>Please create a separate "
        . "user which only has SELECT,INSERT,UPDATE,DELETE and CREATE privileges!<p />"
        . "<a href='index.php'>Try again</a>");
define ("_BLANK_PASSWORD", "You did not specify a password to be used with your database username!<p> "
        . "Flare will NOT work unless you password protect the database user account!<p />"
        . "<a href='index.php'>Try again</a>");
define ("_REGISTER_GLOBALS", "For security reasons, Flare will not work while register_globals is on.<p>Please "
        . "change the register_globals setting in your php.ini to 'Off'<p />"
        . "<a href='index.php'>Try again</a>");
define ("_SETUP_STILL_EXISTS", "The Flare setup directory still exists! Your installation is massively insecure "
        . "while this directory still exists!<p>Please <b>remove</b> this directory after you are finished "
        . "setting up Flare, and before using Flare in a production environment!<p />"
        . "<a href='index.php'>Try again</a>");
define ("_SETUP_FILE_STILL_EXISTS", "The setup file used to install Flare still exists! Remove it now to "
        . "keep your Flare install from being tampered with!<p />"
        . "<a href='index.php'>Try again</a>");
define ("_CONFIG_DIST_EXISTS", "The default configuration file distributed with Flare still exists!<p>While this "
        . "is not a major problem, it is considered a useless file and <b>must</b> be removed!<p />"
        . "<a href='index.php'>Try again</a>");
define ("_RESET_FORM", "Reset Form");
define ("_SETTINGS", "Settings");
define ("_SAVE_CHGS", "Save Changes");
define ("_UNDO_CHGS", "Undo Changes");
define ("_DATABASE", "Database");
define ("_KERBEROS", "Kerberos");
define ("_OWNER", "Owner");
define ("_GROUP", "Group");
define ("_EMAIL", "Email");
define ("_CREDITS", "Credits");
define ("_PRIV_POLICY", "Privacy Policy");
define ("_USAGE_POLICY", "Usage Policy");
define ("_TOS", "Terms of Service");
define ("_SERVICES", "Services");
define ("_PRIVILEGES", "Privileges");

/* SQL specific text localizations */
define ("_CONNECT_ERROR", "Can't Connect to the Database: ");
define ("_INVALID_CONNECTION", "Not a valid database connection");
define ("_DB_SELECT_ERROR", "An error occured while trying to select the database");
define ("_SQL_ERROR", "An error occured while running the SQL query");
define ("_INVALID_QUERY", "The query you chose was not a valid query");
define ("_EMPTY_QUERY", "Empty query passed to function");
define ("_EMPTY_RESULT", "Empty result set passed to function");
define ("_SQL_GET_ARRAY_ERROR", "get_array() in dbclass ");
define ("_EXECUTED_QUERY", "The SQL code attempting to be executed is: <p>");
define ("_QUERY_NOT_EXEC", "The query you are trying to retrieve results from was never executed.");

?>
