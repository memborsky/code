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

/* Accounts page specific texts */
define ("_ACCTS_WELCOME", "My Account on F.U.E.L");
define ("_ACCTS_PHOTO_DEFAULT_MSG", "Only real photos of yourself! Fake pictures will be deleted. Photo must be "
    . "120px to 175px wide");
define ("_ACCTS_NO_PHOTO_MSG", "No photo yet");
define ("_ACCTS_SETTINGS_SAVED_THANKS", "Your account settings have been saved!");
define ("_ACCTS_RETURN_MAIN", "<a href='index.php?extension=Accounts'>Return to the Account Settings page.</a>");
define ("_ACCTS_PERSONAL_INFO", "Personal Information");
define ("_ACCTS_GENDER", "Gender");
define ("_ACCTS_AGE", "Age");
define ("_ACCTS_COUNTRY", "Country/State");
define ("_ACCTS_NO_COUNTRY", "No Country");
define ("_ACCTS_OCCUPATION", "Occupation");
define ("_ACCTS_CHG_PASSWD", "Change Password");
define ("_ACCTS_UNDO_SETTINGS", "Undo Settings");
define ("_ACCTS_SHOW_SETTINGS", "Show Settings");
define ("_ACCTS_CNCT_SETTINGS", "Contact Settings");
define ("_ACCTS_ORG_EMAIL", "IndianaTech Email");
define ("_ACCTS_ALT_EMAIL", "Alternative Email");
define ("_ACCTS_WEBSITE", "Website");
define ("_ACCTS_AVATAR_HDDR", "Avatar and Photo");
define ("_ACCTS_AVATAR", "Avatar");
define ("_ACCTS_CUR_PASSWD", "Current Password");
define ("_ACCTS_NEW_PASSWD", "New Password");
define ("_ACCTS_CHG_PASSWD_WELCOME", "My Password on F.U.E.L");
define ("_ACCTS_FNAME", "First Name");
define ("_ACCTS_LNAME", "Last Name");
define ("_ACCTS_PASSWD_SAVED", "Your password has been changed");
define ("_ACCTS_PASSWD_FAILURE_MISMATCH", "You must type your new password twice so that we can verify you typed it correctly!<p>We could not verify your new password!");
define ("_ACCTS_PASSWD_FAILURE_WRONG_CURRENT", "The old password that you entered was incorrect!");
define ("_ACCTS_THEME", "Theme");
define ("_ACCTS_ADM_USERNAME", "Username");
define ("_ACCTS_ADM_REALNAME", "Realname");
define ("_ACCTS_ADM_HOMEDIR", "Home Directory");
define ("_ACCTS_ADM_GROUPDIR", "Group Directory");
define ("_ACCTS_ADM_CREATION_DATE", "Account Creation Date");
define ("_ACCTS_ADM_LAST_LOGIN", "Last Login Date");
define ("_ACCTS_ADM_ADD_ACCT", "Add New Account");
define ("_ACCTS_ADM_ACCT_INFO", "Account Information");
define ("_ACCTS_ADM_HOMEROOT", "Home Root");
define ("_ACCTS_ADM_GROUPROOT", "Group Root");
define ("_ACCTS_ADM_EDIT_ACCT_SUCCESS", "Successfully saved changes to account(s)");
define ("_ACCTS_ADM_EDIT_ACCT_FAILURE", "You must select at least one account to edit!");
define ("_ACCTS_ADM_EDIT_ACCT_RETURN_LINK", "<a href='admin.php?extension=Accounts&amp;action=show_user_list'>Return to Accounts Admin</a>");
define ("_ACCTS_ADM_SHOW_ALL_ACCOUNTS", "Show All Accounts");
define ("_ACCTS_ADM_USER_ROOTS", "User Directories");
define ("_ACCTS_ADM_PASSWD_RESET", "Password Change/Reset");
define ("_ACCTS_ADM_AUTH_TYPE", "Authentication Type");
define ("_ACCTS_ADM_OPTIONAL_MEDIA", "Optional Media Features");
define ("_ACCTS_ADM_USER_LEVEL", "User Level");
define ("_ACCTS_ADM_STATUS", "Status");
define ("_ACCTS_ADM_PUBLIC_VIEW", "Publicly Viewable?");
define ("_ACCTS_ADM_ACCOUNT", "Account");
define ("_ACCTS_ADM_REGISTERED", "Registered");
define ("_ACCTS_ADM_SETTINGS_SAVED", "Settings for the Accounts Extension have been saved.");
define ("_ACCTS_ADM_SETTINGS_SAVED_RETURN_LINK", "<a href='admin.php?extension=Accounts&amp;action=show_user_list'>Return to Accounts Admin</a>");
define ("_ACCTS_ADM_CREATE_ACCOUNT", "Create Account");
define ("_ACCTS_ADM_PRIVILEGES", "Admin Privileges");
define ("_ACCTS_ADM_PRIVILEGES_MESG", "Select the extensions to which the user will have admin privileges");
define ("_ACCTS_ADM_SERVICES", "Available Services");
define ("_ACCTS_ADM_SERVICES_MESG", "Changing the bullets below will affect the services in the following ways."
    . "<p />"
    . "<ul>"
    . "<li><span style='font-style: italic;'>Not Activated</span> means the user has not attempted to activate the account yet."
    . "<li><span style='font-style: italic;'>Activated</span> means the user has successfully activated the account."
    . "<li><span style='font-style: italic;'>Disabled</span> means the user's account has been disabled. They cannot log in and they "
    . "cannot try to reactivate the account."
    . "</ul>");
define ("_ACCTS_CREATE_ACCT_SUCCESS", "Account Created Successfully");
define ("_ACCTS_CREATE_ACCT_FAILURE", "Failed to Create Account");
define ("_ACCTS_CREATE_ACCT_RETURN_LINK", "<a href='admin.php?extension=Accounts&amp;action=show_user_list'>Return to Accounts Admin</a>");
define ("_ACCTS_DELETE_SUCCESS", "Sucessfully deleted selected account(s) and all the files stored for the account(s)");
define ("_ACCTS_DELETE_FAILURE", "Failed to delete selected account(s)");
define ("_ACCTS_DELETE_RETURN_LINK", "<a href='admin.php?extension=Accounts&amp;action=show_user_list'>Return to Accounts Admin</a>");
define ("_ACCTS_DEACTIVATE_SUCCESS", "Successfully deactivated selected account(s)");
define ("_ACCTS_DEACTIVATE_FAILURE", "You must select at least <b>one</b> account to deactivate");
define ("_ACCTS_DEACTIVATE_RETURN_LINK", "<a href='admin.php?extension=Accounts&amp;action=show_user_list'>Return to Accounts Admin</a>");
define ("_ACCTS_ACTIVATE_SUCCESS", "Successfully activated selected account(s)");
define ("_ACCTS_ACTIVATE_FAILURE", "You must select at least <b>one</b> account to activate");
define ("_ACCTS_ACTIVATE_RETURN_LINK", "<a href='admin.php?extension=Accounts&amp;action=show_user_list'>Return to Accounts Admin</a>");
define ("_ACCTS_CHECK_EXISTS", "The account being created already exists.");
define ("_ACCTS_CHECK_EXISTS_RETURN_LINK", "<a href='admin.php?extension=Accounts&amp;action=show_add_account'>Return to Add User Account</a>");
define ("_ACCTS_ADD_CANNOT_READ_RETURN_LINK", "<a href='admin.php?extension=Accounts&amp;action=show_add_account'>Return to Add User Accounts</a>");
define ("_ACCTS_MISC", "Miscellaneous");
define ("_ACCTS_VIEW_PUBLIC", "Viewable by the Public");
define ("_ACCTS_CHG_ACCTS", "Change Accounts");
define ("_ACCTS_DEACTIVATE", "Deactivate Accounts");
define ("_ACCTS_ACTIVATE", "Activate Accounts");
define ("_ACCTS_DELETE", "Delete Accounts");
define ("_ACCTS_PURGE", "Purge Accounts");
define ("_ACCTS_CANNOT_READ", "Cannot read uploaded file.");
define ("_ACCTS_ADD_TEMP_FINISHED", "Finished adding accounts to temporary table. They will be activated once users complete their manual registration.");

?>
