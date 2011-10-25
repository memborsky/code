<?php
/**
* @package Groups
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

require_once (ABSPATH.'/extensions/Groups/class.GroupsAdmin.php');
require_once(ABSPATH.'/extensions/Groups/lang/lang-'.$cfg['language'].'.php');

$page = new GroupsAdmin();

$page->__set("db",$db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);
$page->__set("ext",$ext);

switch ($flare_action) {
    case "show_settings":
        $page->show_settings();
        break;
    case "do_save_settings":
        $visible	= import_var('visible', 'P');
        $visible 	= ($visible == "on") ? 1 : 0;

        $page->do_change_visibility($visible);
        break;
    case "show_add_group":
        $page->show_add_group();
        break;
    case "do_add_group":
        $group_name 	= str_replace(" ", "_", import_var('group_name', 'P'));
        $group_members 	= import_var('group_members', 'P');
        $admin_id	= import_var('user_id', 'P');
        $username	= import_var('username', 'P');
        $home_dir	= import_var('home_dir', 'P');
        $group_dir	= import_var('group_dir', 'P');

        $page->do_create_group(	$group_name,
                    $group_members,
                    $admin_id,
                    $username,
                    $home_dir,
                    $group_dir);
        break;
    case "do_delete_groups":
        $groups = import_var('group_id', 'P');

        $page->admin_delete_groups($groups);

        // Check for errors to see which message we should send back to the user
        if ($this->fs->__get("error")) {
            $this->tpl->assign('_MESSAGE', 	_GROUPS_DELETE_FAILURE);
            $this->tpl->assign('_RETURN_LINK',	_GROUPS_ADM_DELETE_RETURN_LINK);
        } else {
            $this->tpl->assign('_MESSAGE', 	_GROUPS_DELETE_SUCCESS);
            $this->tpl->assign('_RETURN_LINK',	_GROUPS_ADM_DELETE_RETURN_LINK);
        }

        // Display the page with above messages
        $this->tpl->display('actions_done.tpl');
        break;
    case "do_check_for_existing_group":
        $group_name	= import_var('group_name', 'G');
        $group_dir 	= import_var('group_dir', 'S');

        $page->do_check_for_existing_group($group_dir, $group_name);
        break;
    case "show_edit_group":
        $group_id = import_var('group_id', 'P');

        if (count($group_id) == 0) {
            $page->tpl->assign("_MESSAGE", _GROUPS_SELECT_GROUP_EDIT);
            $page->tpl->assign("_RETURN_LINK", _GROUPS_SELECT_GROUP_EDIT_RETURN_LINK);

            $page->tpl->display("actions_done.tpl");
        } else {
            // atm I dont know how we can edit multiple groups, so just edit the first if they try to do multiple
            $group_id = $group_id[0];

            $page->show_edit_group($group_id);
        }
        break;
    case "show_group_members":
        $group_id = import_var('group_id', 'G');
        $members = array();

        $members = $page->show_group_members($group_id);

        if(!empty($members)) {
            $page->tpl->assign('MEMBERS', $members);
            $page->tpl->display('groups_member_list.tpl');
        } else {
            $page->tpl->assign('_MESSAGE', "No members found");
            $page->display('actions_done.tpl');
        }
        break;
    default:
    case "show_all_groups":
        $page->show_all_groups();
        break;
}

?>
