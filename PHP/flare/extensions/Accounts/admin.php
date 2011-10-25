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

require_once(ABSPATH.'/extensions/Accounts/class.AccountsAdmin.php');
require_once(ABSPATH.'/extensions/Accounts/lang/lang-'.$cfg['language'].'.php');

$page = new AccountsAdmin();

$page->__set("db",$db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);
$page->__set("ext",$ext);

switch($flare_action) {
    case "show_add_account":
        $page->show_add_account();
        break;
    case "do_add_account":
        $user_info = array (
            'username'	=> str_replace(" ", "_", import_var('user_username', 'P')),
            'password'	=> md5(import_var('user_password', 'P')),
            'fname'		=> import_var('user_fname', 'P'),
            'lname'		=> import_var('user_lname', 'P'),
            'gender'	=> import_var('user_gender', 'P'),
            'email'		=> import_var('user_email', 'P') . _ORG_EXT,
            'auth_type'	=> import_var('auth_type', 'P'),
            'home_dir'	=> str_replace(" ", "_", import_var('user_homedir', 'P')),
            'group_dir'	=> str_replace(" ", "_", import_var('user_groupdir', 'P')));
        $result = $page->do_add_account($user_info);

        if ($result == 1) {
            // Assign template variables saying the account exists
            $page->tpl->assign('_MESSAGE', 		_ACCTS_CHECK_EXISTS);
            $page->tpl->assign('_RETURN_LINK',	_ACCTS_CHECK_EXISTS_RETURN_LINK);
        } else if ($result == 2) {
            // Let the user know it failed
            $page->tpl->assign('_MESSAGE', 		_ACCTS_CREATE_ACCT_FAILURE);
            $page->tpl->assign('_RETURN_LINK', 	_ACCTS_CREATE_ACCT_RETURN_LINK);
        } else if ($result == 3) {
            // If it doesnt exist, then we encountered a problem and need to exit
            $page->tpl->assign('_MESSAGE', 		_ACCTS_CREATE_ACCT_FAILURE);
            $page->tpl->assign('_RETURN_LINK', 	_ACCTS_CREATE_ACCT_RETURN_LINK);
        } else {
            // Otherwise, assign a success message
            $page->tpl->assign('_MESSAGE', 		_ACCTS_CREATE_ACCT_SUCCESS);
            $page->tpl->assign('_RETURN_LINK', 	_ACCTS_CREATE_ACCT_RETURN_LINK);
        }

        // Print the page
        $page->tpl->display('actions_done.tpl');

        break;
    case "do_mass_add_account":
        $tmp = $_FILES['mass_account_file']['tmp_name'];
        $sql = array(
            "mysql" => array(
                "tmp" => "INSERT INTO "._PREFIX."_tmp (`data`) VALUES (':1')"
            )
        );

        $stmt1 = $page->db->prepare($sql[_DBSYSTEM]['tmp']);

        $fh = fopen($tmp, 'r');

        if (!is_resource($fh)) {
            $page->tpl->assign("_MESSAGE", _ACCTS_CANNOT_READ);
            $page->tpl->assign("_RETURN_LINK", _ACCTS_ADD_CANNOT_READ_RETURN_LINK);
            $page->tpl->display('actions_done.tpl');
            exit();
        }

        while(!feof($fh)) {
            $line = fgets($fh, 4096);

            $line = trim($line);

            // There's no need to process further if there's nothing on the line
            if ($line == '')
                continue;

            $tmp = explode('@', $line);

            $username = strtolower($tmp[0]);

            // Passwords are temporary and not used with Tech's adldap implementation
            $password = rand(0,1000000);

            $user_info = array (
                'username'	=> str_replace(" ", "_", $username),
                'password'	=> $password,
                'fname'		=> '',
                'lname'		=> '',
                'gender'	=> 'm',
                'email'		=> $username . _ORG_EXT,
                'auth_type'	=> 'adldap',
                'home_dir'	=> str_replace(" ", "_", $cfg['user_dir'] . '/' . $username . '/public_html/'),
                'group_dir'	=> str_replace(" ", "_", $cfg['group_dir']));
            $page->do_add_account($user_info);
            $stmt1->execute("$username;$password");
        }

        $page->tpl->assign("_MESSAGE", _ACCTS_ADD_TEMP_FINISHED);
        $page->tpl->assign("_RETURN_LINK", "<a href='admin.php?extension=Accounts'>Return to Accounts Admin</a>");
        $page->tpl->display('actions_done.tpl');

        break;
    case "show_change_accounts":
        if ($_POST)
            $account 	= import_var('account', 'P');
        else
            $account 	= import_var('account', 'G');

        $auth_type	= import_var('auth_type', 'S');
        $account_list	= array();
        $status_list 	= array();
        $levels_list 	= array();
        $auth_types 	= array();
        $templates	= array();

        if (count($account) == 0 ) {
            // If no accounts were asked for, then exit gracefully
            $page->tpl->assign("_MESSAGE", 		_ACCTS_ADM_EDIT_ACCT_FAILURE);
            $page->tpl->assign("_RETURN_LINK",	_ACCTS_ADM_EDIT_ACCT_RETURN_LINK);

            $page->tpl->display('actions_done.tpl');
        } else {
            $account_list	= $page->show_change_accounts($account);
            $status_list 	= $page->get_status_list();
            $levels_list 	= $page->get_levels_list();
            $auth_types 	= $page->get_auth_types();

            if (count($account_list) == 0) {
                // If no accounts were retrieved, then exit gracefully
                $page->tpl->assign("_MESSAGE", 		_ACCTS_ADM_EDIT_ACCT_FAILURE);
                $page->tpl->assign("_RETURN_LINK",	_ACCTS_ADM_EDIT_ACCT_RETURN_LINK);

                $page->tpl->display('actions_done.tpl');
            } else {
                /**
                * Make list of templates
                */
                if (is_dir($page->cfg['template_dir'])) {
                    if ($handle = opendir($page->cfg['template_dir'])) {
                        while (false !== ($file = readdir($handle))) {
                            if ($file != "." && $file != "..") {
                                array_push($templates, $file);
                            }
                        }
                        closedir($handle);
                    }
                } else {
                    // Otherwise just assign the default
                    $templates = array("default");
                }

                // Assign language constants
                $page->tpl->assign(array(
                    '_ACCTS_WELCOME' 		=> _ACCTS_WELCOME,
                    '_ACCTS_PERSONAL_INFO' 		=> _ACCTS_PERSONAL_INFO,
                    '_ACCTS_FNAME'			=> _ACCTS_FNAME,
                    '_ACCTS_LNAME'			=> _ACCTS_LNAME,
                    '_ACCTS_GENDER' 		=> _ACCTS_GENDER,
                    '_ACCTS_AGE' 			=> _ACCTS_AGE,
                    '_ACCTS_COUNTRY' 		=> _ACCTS_COUNTRY,
                    '_ACCTS_OCCUPATION' 		=> _ACCTS_OCCUPATION,
                    '_ACCTS_CHG_PASSWD' 		=> _ACCTS_CHG_PASSWD,
                    '_ACCTS_CNCT_SETTINGS'		=> _ACCTS_CNCT_SETTINGS,
                    '_ACCTS_ORG_EMAIL' 		=> _ACCTS_ORG_EMAIL,
                    '_ACCTS_ALT_EMAIL' 		=> _ACCTS_ALT_EMAIL,
                    '_ACCTS_ADM_ADD_ACCT'		=> _ACCTS_ADM_ADD_ACCT,
                    '_ACCTS_ADM_SHOW_ALL_ACCOUNTS'	=> _ACCTS_ADM_SHOW_ALL_ACCOUNTS,
                    '_SETTINGS'			=> _SETTINGS,
                    '_ACCTS_ADM_ACCOUNT'		=> _ACCTS_ADM_ACCOUNT,
                    '_ACCTS_ADM_REGISTERED'		=> _ACCTS_ADM_REGISTERED,
                    '_ACCTS_PERSONAL_INFO'		=> _ACCTS_PERSONAL_INFO,
                    '_ACCTS_ADM_USER_ROOTS'		=> _ACCTS_ADM_USER_ROOTS,
                    '_ACCTS_ADM_HOMEROOT'		=> _ACCTS_ADM_HOMEROOT,
                    '_ACCTS_ADM_HOMEDIR'		=> _ACCTS_ADM_HOMEDIR,
                    '_ACCTS_ADM_GROUPROOT'		=> _ACCTS_ADM_GROUPROOT,
                    '_ACCTS_ADM_GROUPDIR'		=> _ACCTS_ADM_GROUPDIR,
                    '_ACCTS_ADM_PASSWD_RESET'	=> _ACCTS_ADM_PASSWD_RESET,
                    '_ACCTS_ADM_AUTH_TYPE'		=> _ACCTS_ADM_AUTH_TYPE,
                    '_ACCTS_NEW_PASSWD'		=> _ACCTS_NEW_PASSWD,
                    '_VER_USR_PASSWORD'		=> _VER_USR_PASSWORD,
                    '_ACCTS_ADM_OPTIONAL_MEDIA'	=> _ACCTS_ADM_OPTIONAL_MEDIA,
                    '_ACCTS_ADM_USER_LEVEL'		=> _ACCTS_ADM_USER_LEVEL,
                    '_ACCTS_ADM_STATUS'		=> _ACCTS_ADM_STATUS,
                    '_ACCTS_ADM_PUBLIC_VIEW'	=> _ACCTS_ADM_PUBLIC_VIEW,
                    '_ACCTS_ADM_PRIVILEGES'		=> _ACCTS_ADM_PRIVILEGES,
                    '_ACCTS_ADM_PRIVILEGES_MESG'	=> _ACCTS_ADM_PRIVILEGES_MESG,
                    '_ACCTS_ADM_SERVICES'		=> _ACCTS_ADM_SERVICES,
                    '_ACCTS_ADM_SERVICES_MESG'	=> _ACCTS_ADM_SERVICES_MESG,
                    '_PRIVILEGES'			=> _PRIVILEGES,
                    '_SERVICES'			=> _SERVICES,
                    '_YES'				=> _YES,
                    '_NO'				=> _NO,
                    '_MALE'				=> _MALE,
                    '_FEMALE'			=> _FEMALE,
                    '_SAVE_CHGS'			=> _SAVE_CHGS,
                    '_UNDO_CHGS'			=> _UNDO_CHGS,
                    '_AUTH_TYPE'			=> $auth_type,
                    '_ACCTS_THEME' 			=> _ACCTS_THEME));

                // Assign dynamic content
                $page->tpl->assign(array(
                    'ACCOUNT_LIST'	=> $account_list,
                    'STATUS_LIST'	=> $status_list,
                    'LEVELS_LIST'	=> $levels_list,
                    'TEMPLATES'	=> $templates,
                    'AUTH_TYPES'	=> $auth_types));

                // Print the page
                $page->tpl->display('accounts_edit.tpl');
            }
        }
        break;
    case "do_change_accounts":
        $account_info = array();

        $user_id 		= import_var('user_id', 'P');
        $fname			= import_var('user_fname', 'P');
        $lname			= import_var('user_lname', 'P');
        $gender			= import_var('user_gender', 'P');
        $age			= import_var('user_age', 'P');
        $country		= import_var('user_country', 'P');
        $occupation		= import_var('user_occupation', 'P');
        $org_email		= import_var('user_org_email', 'P');
        $alternate_email	= import_var('user_email', 'P');
        $home_dir		= import_var('user_home_dir', 'P');
        $group_dir		= import_var('user_group_dir', 'P');
        $theme			= import_var('user_theme', 'P');
        $auth_type		= import_var('auth_type', 'P');
        $user_level		= import_var('user_level', 'P');
        $status			= import_var('user_status', 'P');
        $public			= import_var('user_public', 'P');
        $new_password		= import_var('user_new_password', 'P');
        $new_password_verify	= import_var('user_new_password_verify', 'P');
        $auth_perm		= import_var('auth_perm', 'P');
        $services		= import_var('service', 'P');
        $mesg			= '';

        foreach ($user_id as $key => $val) {
            $account_info = array (
                'user_id' 		=> $user_id[$val],
                'fname'			=> $fname[$val],
                'lname'			=> $lname[$val],
                'gender'		=> $gender[$val],
                'age'			=> $age[$val],
                'country'		=> $country[$val],
                'occupation'		=> $occupation[$val],
                'org_email'		=> $org_email[$val],
                'alternate_email'	=> $alternate_email[$val],
                'theme'			=> $theme[$val],
                'auth_type'		=> $auth_type[$val],
                'user_level'		=> $user_level[$val],
                'status'		=> $status[$val],
                'public'		=> $public[$val]
            );

            $current_user_id 	= $user_id[$val];
            $current_home_dir	= $home_dir[$val];
            $current_group_dir 	= $group_dir[$val];

            // Change settings that only alter the users info in database
            $page->do_change_accounts($account_info);

            if (!$page->__get("error"))
                $mesg = _ACCTS_SETTINGS_SAVED_THANKS;

            // Change the admin privileges
            $page->do_change_admin_permissions($user_id[$val], $auth_perm[$val]);

            // Change the available services
            $page->do_change_services($user_id[$val], $services[$val]);

            if (!$page->__get("error"))
                $mesg .= "<p />Services updated, jobs scheduled.";

            // Change the home directory separately. This is important
            // because we also need to move the users actual home
            // folder too
            $page->do_change_home_dir($current_user_id, $current_home_dir);

            if (!$page->__get("error"))
                $mesg .= "<p />Home Directory settings saved";

            // Change the group directory separately . This is important
            // because we also need to move the users actual group
            // folder too
            $page->do_change_group_dir($current_user_id, $current_group_dir);

            if (!$page->__get("error"))
                $mesg .= "<p />Group Directory settings saved";

            if ($new_password[$val] != "") {
                $page->do_change_password($user_id[$val], $new_password[$val], $new_password_verify[$val]);

                if (!$page->__get("error"))
                    $mesg .= "<p />" . _ACCTS_PASSWD_SAVED;
                else
                    $mesg .= "<p />Failed to change password";
            }

        }

        $page->tpl->assign("_MESSAGE", $mesg);
        $page->tpl->assign("_RETURN_LINK", "<a href='admin.php?extension=Accounts'>Return to Accounts Admin</a>");
        $page->tpl->display('actions_done.tpl');
        break;
    case "do_delete_accounts":
        $accounts = import_var('account', 'P');

        $page->do_delete_accounts($accounts);
        break;
    case "do_activate_accounts":
        $accounts = import_var('account', 'P');

        if (count($accounts) == 0) {
            $page->tpl->assign('_MESSAGE',		_ACCTS_ACTIVATE_FAILURE);
            $page->tpl->assign('_RETURN_LINK', 	_ACCTS_ACTIVATE_RETURN_LINK);
        } else {
            foreach ($accounts as $key => $user_id) {
                $page->change_status('A', $user_id);
                $page->log->log("ACTIVATE_ACCOUNT", $user_id);
            }

            $page->tpl->assign('_MESSAGE',		_ACCTS_ACTIVATE_SUCCESS);
            $page->tpl->assign('_RETURN_LINK', 	_ACCTS_ACTIVATE_RETURN_LINK);
        }

        // Print the page
        $page->tpl->display('actions_done.tpl');
        break;
    case "do_deactivate_accounts":
        $accounts = import_var('account', 'P');

        if (count($accounts) == 0) {
            $page->tpl->assign('_MESSAGE', 		_ACCTS_DEACTIVATE_FAILURE);
            $page->tpl->assign('_RETURN_LINK', 	_ACCTS_DEACTIVATE_RETURN_LINK);
        } else {
            foreach ($accounts as $key => $user_id) {
                $page->change_status('D', $user_id);
                $page->log->log("DEACTIVATE_ACCOUNT", $user_id);
            }

            $page->tpl->assign('_MESSAGE', 		_ACCTS_DEACTIVATE_SUCCESS);
            $page->tpl->assign('_RETURN_LINK', 	_ACCTS_DEACTIVATE_RETURN_LINK);
        }

        // Print the page
        $page->tpl->display('actions_done.tpl');
        break;
    case "show_settings":
        $page->show_settings();
        break;
    case "do_save_settings":
        $visible	= import_var('visible', 'P');

        $settings = array(
            'template'	=> import_var('template', 'P'),
            'user_dir'	=> import_var('user_dir', 'P'),
            'group_dir'	=> import_var('group_dir', 'P'),
            'idle_timeout'	=> import_var('idle_timeout', 'P'),
        );

        $page->do_save_settings($settings);
        $page->do_change_visibility($visible);
        break;
    case "show_user_list":
    default:
        $page->show_user_list();
        break;
}

?>
