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

require_once(ABSPATH.'/extensions/Accounts/class.Accounts.php');
require_once(ABSPATH.'/extensions/Accounts/lang/lang-'.$cfg['language'].'.php');

$page = new Accounts();

$page->__set("db",$db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);
$page->__set("ext",$ext);

switch($flare_action) {
    case "show_change_password":
        $user_id = import_var('user_id', 'S');

        // Assign language constants
        $page->tpl->assign(array(
            '_ACCTS_CHG_PASSWD'		=> _ACCTS_CHG_PASSWD,
            '_ACCTS_UNDO_SETTINGS' 		=> _ACCTS_UNDO_SETTINGS,
            '_ACCTS_SHOW_SETTINGS' 		=> _ACCTS_SHOW_SETTINGS,
            '_ACCTS_CHG_PASSWD_WELCOME' 	=> _ACCTS_CHG_PASSWD_WELCOME,
            '_ACCTS_CUR_PASSWD' 		=> _ACCTS_CUR_PASSWD,
            '_ACCTS_VER_PASSWD' 		=> _VER_USR_PASSWORD,
            '_ACCTS_NEW_PASSWD' 		=> _ACCTS_NEW_PASSWD));

        // Assign dynamic content and other vars
        $page->tpl->assign('USER_ID', $user_id);

        // Display page
        $page->tpl->display('accounts_change_passwd.tpl');
        break;
    case "do_change_password":
        $user_id 		= import_var('user_id', 'P');
        $current_password 	= import_var('current_password', 'P');
        $new_password 		= import_var('new_password', 'P');
        $verify_password 	= import_var('verify_password', 'P');

        $page->do_change_password($user_id,$current_password,$new_password,$verify_password);
        break;
    case "update_acct_info":
        $acct_info = array(
            'user_fname'		=> import_var('user_fname', 'P'),
            'user_lname'		=> import_var('user_lname', 'P'),
            'user_gender'		=> import_var('user_gender', 'P'),
            'user_age' 		=> import_var('user_age', 'P'),
            'user_country' 		=> import_var('user_country', 'P'),
            'user_occupation' 	=> import_var('user_occupation', 'P'),
            'user_org_email' 	=> import_var('user_org_email', 'P'),
            'user_email'		=> import_var('user_email', 'P'),
            'public'		=> import_var('user_public', 'P'),
            'username'		=> import_var('username', 'S')
        );

        $page->do_update_acct_info($acct_info);
        break;
    case "show_create_account":
        $page->show_create();
        break;
    case "check_availability":
        $page->do_check_availability($flare_username);
        break;
    default:
        $user_id = import_var('user_id', 'S');

        if (@$_GET['user_id']) {
            if (is_admin($user_id, $page->ext->__get("extension_id"))) {
                $user_id = import_var('user_id', 'G');
            }
        }

        $page->show_user_default_page($user_id);
        break;
}

?>
