<?php
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

/* Global texts */
define ("_PLEASE_FILL_OUT", "Please fill out the information below");

/* setup index page texts */
define ("_WELCOME_INDEX", "<h3>Welcome to Flare</h3>");
define ("_INDEX_BODY", "<div style='text-align: center; width: 70%;'>The next couple of pages will "
	. "guide you through the setup and configuration of Flare.</div>");

/* Step 1 texts */
define ("_WELCOME_DATABASE", "<h3>Database Configuration</h3>");
define ("_DB_CONNECT_FAIL", "Connecting to the database failed! Check that the database is running and that the "
	. "previous fields were filled out correctly.");
define ("_DB_CONNECT_FAIL_RETURN_LINK", "<a href='setup/step_1.php'>Return to Database Configuration</a>");
define ("_DB_SERVER", "Hostname");
define ("_DB_NAME", "Database Name");
define ("_USERNAME_CONNECT_WITH", "Username for Flare");
define ("_PWRD_FOR_ACCOUNT", "Password for Flare");
define ("_TABLE_PREFIX", "Table prefix to use");
define ("_STEP_DIR_SETUP", "On to Directory Setup >>");

/* Step 2 texts */
define ("_WELCOME_DIRECTORY", "<h3>Directory Setup</h3>");
define ("_DIRECTORY_BODY", "Flare stores all user files and groups in two main directories. Please enter "
	. "the locations of the directories where you would like Flare to read all data from.<p>"
	. "These settings can be changed on a per user basis later. The locations you are choosing now will "
	. "be used as defaults when new accounts are created, and they will also be used for the home and "
	. "group directories of the admin account you will create in the next section.<p>"
	. "If the directories do not exist, Flare will try to create them. However the directories containing "
	. "the folders that need to be created MUST be writable by the webserver.");
define ("_FAILED_CREATING", "Failed to create the following directories, please create them manually.");
define ("_FAILED_CREATING_RETURN_LINK", "<a href='setup/step_3.php'>On to Create an Admin Account</a>");
define ("_HOME_ROOT", "Default Home Root");
define ("_HOME_DIR", "Default Home Directory");
define ("_GROUP_ROOT", "Default Group Root");
define ("_GROUP_DIR", "Default Group Directory");
define ("_STEP_ADMIN_ACCOUNT", "On to Create Admin Account >>");

/* Step 3 texts */
define ("_WELCOME_ADMIN", "<h3>Create an Admin Account</h3>");
define ("_ADMIN_BODY", "You'll now create a super account from which you can perform any and all needed Flare "
	. "administration. This admin account's home directory will be the root directories you specified in the "
	. "previous step. You will want to log in and create a normal user once you are finished here.");
define ("_FNAME", "First Name");
define ("_LNAME", "Last Name");
define ("_EMAIL", "Email");
define ("_ADM_USER", "Admin username");
define ("_ADM_PWRD", "Admin Password");
define ("_AUTH_TYPE", "Authentication Type");
define ("_DATABASE", "Database");
define ("_KERB", "Kerberos");
define ("_STEP_FINISH", "Finish Setup >>");

/* Setup finished texts */
define ("_FINISH_WROTE_CONFIG", "<div style='text-align:left'><ul><li>Successfully wrote configuration file.<p>");
define ("_FINISH_NO_WROTE_CONFIG", "<div style='text-align:left'><ul><li>Couldn't write configuration file! "
	. "Please copy the following text and place it in a file called <b>config-inc.php</b> in the main "
	. "Flare directory.<p>");
define ("_FINISH_WROTE_INDEX", "<p /><li>Successfully wrote a redirect index file to the admin's home root. This "
	. "prevents others from viewing the whole home folder from the net.<p />");
define ("_FINISH_NO_WROTE_INDEX", "<p /><li>Couldn't write a default index file to the admin's home root.");
define ("_FINISH_WILL_REMOVE_SETUP_DIR", "</p><li>The setup directory will be removed when you surf to the "
	. "main page, or, you can remove it manually right now.<p>");
define ("_FINISH_REMOVED_BASE_CONFIG", "<li>Successfully removed the default configuration file<p>");
define ("_FINISH_NO_REMOVED_BASE_CONFIG", "<li>Couldn't remove the default configuration file "
	. "<b>config-inc.php.dist</b>. Don't worry, it will be "
	. "removed when you surf to the main page, or, you can remove it manually right now.<p>");
define ("_FINISH_BODY", "</ul></div>Flare setup complete! Use the link below to log in to your new Flare installation.");
define ("_FINISH_RETURN_LINK", "<a href='index.php'>Log In Now!</a>");

?>
