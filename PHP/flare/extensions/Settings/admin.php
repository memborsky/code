<?php
/**
* @package Settings
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

require_once('class.SettingsAdmin.php');

$page = new SettingsAdmin();

$page->__set("db",$db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);
$page->__set("ext",$ext);

switch ($flare_action) {
    case "do_save_settings":
        $settings = array(
            'default_extension'	=> import_var('default_extension', 'P'),
            'use_debug'		=> import_var('use_debug', 'P'),
            'update'		=> import_var('update', 'P'),
            'version'		=> import_var('version', 'P'),
            'update_interval'	=> import_var('update_interval', 'P'),
            'last_update_check'	=> import_var('last_update_check', 'P'),
            'use_strict'		=> import_var('use_strict', 'P')
        );

        $page->do_save_settings($settings);

        echo "Hello";
        // Assign language constants
        $page->tpl->assign('_MESSAGE', "Settings Saved");
        $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Settings'>Back to Settings Admin</a>");

        // Display the page
        $page->tpl->display('actions_done.tpl');
        break;
    case "show_install_extensions":
        $page->show_install_extensions();
        break;
    case "do_install_extension":
        $extension = import_var('extension_name', 'P');
        $admin_id = import_var('user_id', 'S');

        $page->do_install_extension($extension, $admin_id);

        if ($page->ext->__get("install_result")) {
            $page->tpl->assign('_MESSAGE', "Successfully installed extension");
            $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Settings&action=show_settings'>Return to Settings Admin</a>");
            $page->tpl->display('actions_done.tpl');
        } else {
            $page->tpl->assign('_MESSAGE', "Failed to install extension");
            $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Settings&action=show_settings'>Return to Settings Admin</a>");
            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "do_remove_extension":
        $extension = import_var('extension_name', 'P');
        $admin_id = import_var('user_id', 'S');

        $result = $page->do_remove_extension($extension, $admin_id);

        if ($result) {
            $page->tpl->assign('_MESSAGE', "Successfully removed extension");
            $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Settings&action=show_settings'>Return to Settings Admin</a>");
            $page->tpl->display('actions_done.tpl');
        } else {
            $page->tpl->assign('_MESSAGE', "Failed to remove extension");
            $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Settings&action=show_settings'>Return to Settings Admin</a>");
            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "show_settings":
    default:
        $page->show_settings();
        break;
}

?>
