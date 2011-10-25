<?php
/**
* @package Filesystem
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

require_once(ABSPATH.'/extensions/Filesystem/class.FilesystemAdmin.php');
require_once(ABSPATH.'/extensions/Filesystem/lang/lang-'.$cfg['language'].'.php');

$page = new FilesystemAdmin();

$page->__set("db",$db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);
$page->__set("ext",$ext);

switch ($flare_action) {
    case "do_save_settings":
        $visible	= import_var('visible', 'P');
        $visible 	= ($visible == "on") ? 1 : 0;

        $settings = array(
            'max_ul_size'		=> import_var('max_ul_size', 'P'),
            'use_quotas'		=> import_var('use_quotas', 'P'),
            'directory_permissions'	=> import_var('directory_permissions', 'P'),
            'file_permissions'	=> import_var('file_permissions', 'P')
        );

        $page->do_save_settings($settings);
        $page->do_change_visibility($visible);

        // Assign language constants
        $page->tpl->assign('_MESSAGE', _MYFILES_ADM_SETTINGS_SAVED);
        $page->tpl->assign('_RETURN_LINK', _MYFILES_ADM_SETTINGS_SAVED_RETURN_LINK);

        // Display the page
        $page->tpl->display('actions_done.tpl');
        break;
    case "show_permissions":
        if ($_POST) {
            $path = $page->strip_bad_navigation(import_var('base_dir', 'P'));
        } else {
            $path = $page->strip_bad_navigation(import_var('path', 'G'));
            $file = $page->strip_bad_navigation(import_var('file', 'G'));
            $path = $page->normalize_dir($path . '/' . $file);
        }

        $root 		= $page->cfg['user_dir'];

        if ($path == "") {
            $path .= "/";
        } else {
            if(substr($path, -1, 1) != "/")
                $path .= "/";
        }

        $page->show_permissions($root, $path);
        break;
    case "do_update_permissions":
        $items 		= import_var('item_id', 'P');
        $files		= import_var('file', 'P');
        $base_dir 	= import_var('base_dir', 'P');
        $root 		= $page->cfg['user_dir'];
        $owner_id	= import_var('user_id', 'S');

        $type		= import_var('type', 'P');

        $o_read		= import_var('o_read', 'P');
        $o_write	= import_var('o_write', 'P');
        $o_exec		= import_var('o_exec', 'P');

        $g_read		= import_var('g_read', 'P');
        $g_write	= import_var('g_write', 'P');
        $g_exec		= import_var('g_exec', 'P');

        $e_read		= import_var('e_read', 'P');
        $e_write	= import_var('e_write', 'P');
        $e_exec		= import_var('e_exec', 'P');
        $access		= '';

        foreach ($items as $key => $item_id) {
            $permissions = array(
                'o_read'	=> ($o_read[$item_id]) ? 'r' : '-',
                'o_write'	=> ($o_write[$item_id]) ? 'w' : '-',
                'o_exec'	=> ($o_exec[$item_id]) ? 'x' : '-',
                'g_read'	=> ($g_read[$item_id]) ? 'r' : '-',
                'g_write'	=> ($g_write[$item_id]) ? 'w' : '-',
                'g_exec'	=> ($g_exec[$item_id]) ? 'x' : '-',
                'e_read'	=> ($e_read[$item_id]) ? 'r' : '-',
                'e_write'	=> ($e_write[$item_id]) ? 'w' : '-',
                'e_exec'	=> ($e_exec[$item_id]) ? 'x' : '-'
            );

            foreach ($permissions as $key => $val) {
                $access .= $val;
            }

            if (is_numeric($item_id)) {
                $access = $type[$item_id] . $access;
                $page->do_update_permissions($item_id, $access);
            } else {
                $path = $page->normalize_dir($root . '/' . $base_dir . $files[$item_id]);

                if (is_dir($path)) {
                    $access = 'd' . $access;
                    $page->do_add_permissions($path, $access, $owner_id, '-');
                } else {
                    $path = $page->normalize_file($path);
                    $access = '-' . $access;
                    $page->do_add_permissions($path, $access, $owner_id, '-');
                }
            }

            $access = '';
        }

        $path = $base_dir;

        if ($path == "") {
            $path .= "/";
        } else {
            if(substr($path, -1, 1) != "/")
                $path .= "/";
        }

        $page->show_permissions($root, $path);

        break;
    case "show_change_owner":
        $items 	= import_var('item', 'P');
        $data 	= array();

        foreach ($items as $key => $item_id) {
            $tmp = $page->get_owner_info($item_id);
            if (empty($tmp))
                continue;
            else
                $data[] = $tmp;
        }

        $all_users = $page->get_all_users();
        $all_groups = $page->get_all_groups();

        // Assign language constants
        $page->tpl->assign('_SETTINGS', _SETTINGS);

        // Assign dynamic content
        $page->tpl->assign('OWNERS', $data);
        $page->tpl->assign('ALL_USERS', $all_users);
        $page->tpl->assign('ALL_GROUPS', $all_groups);

        // Display the page
        $page->tpl->display('filesystem_owners.tpl');
        break;
    case "do_change_owner":
        $items	= import_var('item', 'P');
        $owner	= import_var('owner', 'P');
        $group	= import_var('group', 'P');

        foreach ($items as $key => $item_id) {
            $page->do_change_owner($item_id, $owner[$item_id], $group[$item_id]);
        }

        $page->tpl->assign('_MESSAGE', 'Permissions Changed');
        $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Filesystem&amp;action=show_permissions'>Return to Filesystem Admin</a>");

        $page->tpl->display('actions_done.tpl');
        break;
    default:
    case "show_settings":
        $page->show_settings();
        break;
}

?>
