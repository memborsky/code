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

require_once(ABSPATH.'/extensions/Groups/class.Groups.php');
require_once(ABSPATH.'/extensions/Groups/lang/lang-'.$cfg['language'].'.php');

$page = new Groups();

$page->__set("db", $db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);

switch($flare_action) {
    case "show_create_group":
        $admin_id = import_var('user_id', 'S');

        $page->show_create_group($admin_id);
        break;
    case "do_create_group":
        $group_name 	= str_replace(" ", "_", import_var('group_name', 'P'));
        $group_type	= import_var('group_type', 'P');
        $group_members 	= import_var('group_members', 'P');
        $group_quota	= import_var('share_amount', 'P');
        $admin_id	= import_var('user_id', 'S');
        $username	= import_var('username', 'S');
        $home_dir	= import_var('home_dir', 'S');
        $group_dir	= import_var('group_dir', 'S');

        if ($group_type == "trusted")
            $group_type = 1;
        else
            $group_type = 2;

        $page->do_create_group($group_name,$group_type,$group_members,$admin_id,$username,$home_dir,$group_dir,$group_quota);
        break;
    case "show_groups":
        $admin_id 	= import_var('user_id', 'S');

        $page->show_groups($admin_id);
        break;
    case "show_invites_create":
        $admin_name 	= import_var('username', 'S');
        $admin_id	= import_var('user_id', 'S');

        $page->show_invites_create($admin_name, $admin_id);
        break;
    case "do_invites_create":
        $admin_id 	= import_var('user_id', 'S');
        $group_id 	= import_var('group_id', 'P');
        $group_members	= import_var('group_members', 'P');

        $page->do_invites_create($admin_id, $group_id, $group_members);

        if($page->__get("error")) {
            $page->tpl->assign('_MESSAGE', _GROUPS_INVITES_CREATE_FAILURE);
            $page->tpl->assign('_RETURN_LINK', _GROUPS_INVITES_CREATE_RETURN_LINK);
        } else {
            $page->tpl->assign('_MESSAGE', _GROUPS_INVITES_CREATE_SUCCESS);
            $page->tpl->assign('_RETURN_LINK', _GROUPS_INVITES_CREATE_RETURN_LINK);
        }
        $page->tpl->display('actions_done.tpl');
        break;
    case "my_admin_delete":
        $admin_id 	= import_var('user_id', 'S');
        $groups 	= import_var('group_id', 'P');

        foreach ($groups as $key => $group_id) {
            $page->do_delete_groups($admin_id, $group_id);
        }

        // Check for errors to see which message we should send back to the user
        if ($page->fs->__get("error")) {
            $page->tpl->assign('_MESSAGE', _GROUPS_DELETE_FAILURE);
            $page->tpl->assign('_RETURN_LINK', _GROUPS_DELETE_RETURN_LINK);
        } else {
            $page->tpl->assign('_MESSAGE', _GROUPS_DELETE_SUCCESS);
            $page->tpl->assign('_RETURN_LINK', _GROUPS_DELETE_RETURN_LINK);
        }

        // Display the page with above messages
        $page->tpl->display('actions_done.tpl');
        break;
    case "my_admin_edit":
        $group_id 	= import_var('group_id', 'P');
        $admin		= import_var('user_id', 'S');

        if (count($group_id) == 0) {
            $page->tpl->assign("_MESSAGE", _GROUPS_SELECT_GROUP_EDIT);
            $page->tpl->assign("_RETURN_LINK", _GROUPS_SELECT_GROUP_EDIT_RETURN_LINK);

            $page->tpl->display("actions_done.tpl");
        } else {
            // atm I dont know how we can edit multiple groups, so just edit the first if they try to do multiple
            $group_id = $group_id[0];

            $page->show_edit_group($group_id, $admin);
        }
        break;
    case "do_edit_group":
        $group_id	= import_var('group_id', 'P');
        $group_type	= import_var('group_type', 'P');
        $group_members 	= import_var('group_members', 'P');
        $admin_id	= import_var('user_id', 'S');
        $old_share_amount	= import_var('old_share_amount', 'P');
        $new_share_amount	= import_var('new_share_amount', 'P');

        if ($group_type == "trusted")
            $group_type = 1;
        else
            $group_type = 2;

        $page->do_edit_group($group_id, $group_type, $group_members, $old_share_amount, $new_share_amount, $admin_id);

        if (!$page->__get("error")) {
            $page->tpl->assign('_MESSAGE',	_GROUPS_EDIT_SUCCESS);
            $page->tpl->assign('_RETURN_LINK', _GROUPS_EDIT_RETURN_LINK);

            $page->tpl->display('actions_done.tpl');
        } else {
            $page->tpl->assign('_MESSAGE',	_GROUPS_EDIT_FAILURE);
            $page->tpl->assign('_RETURN_LINK', _GROUPS_EDIT_RETURN_LINK);

            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "my_groups_in_withdraw":
        $user_id 	= import_var('user_id', 'S');
        $group_id	= import_var('group_id', 'P');

        if (is_array($group_id)) {
            foreach ($group_id as $key => $val) {
                $page->my_groups_in_withdraw($user_id, $val);
            }
        } else {
            $page->my_groups_in_withdraw($user_id, $group_id);
        }

        if (!$page->__get("error")) {
            $page->tpl->assign('_MESSAGE', _GROUPS_LEAVE_SUCCESS);
            $page->tpl->assign('_RETURN_LINK', _GROUPS_LEAVE_RETURN_LINK);

            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "accept_invite":
        // The id of the user who is doing the accepting
        $user_id = import_var('user_id', 'S');

        // The group_dir of the user who is doing the accepting
        $group_dir = import_var('group_dir', 'S');

        // The home_dir of the user who is doing the accepting
        $home_dir = import_var('home_dir', 'S');

        $invites_mine = import_var('invites_mine', 'P');

        $output = $page->do_invites_accept($user_id, $group_dir, $home_dir, $invites_mine);

        // Assign the output to the template variables
        $page->tpl->assign('_MESSAGE', $output);
        $page->tpl->assign('_RETURN_LINK', _GROUPS_ACCEPT_INVITE_RETURN_LINK);

        // Print the messages back to the browser
        $this->tpl->display('actions_done.tpl');
        break;
    case "decline_invite":
        $to_user_id = import_var('user_id', 'S');

        $invites_mine = import_var('invites_mine', 'P');

        $page->do_invites_decline($to_user_id, $invites_mine);
        break;
    case "retract_invite":
        $invites = import_var('invites_sent', 'P');
        $admin_id = import_var('user_id', 'S');

        if (count($invites) == 0) {
            $page->tpl->assign("_MESSAGE", _GROUPS_INVITE_MUST_SELECT_ONE);
            $page->tpl->assign("_RETURN_LINK", _GROUPS_INVITE_RETURN_LINK);

            $page->tpl->display("actions_done.tpl");
        } else {
            foreach ($invites as $user_id => $group_id) {
                $page->do_retract_invite($user_id, $group_id);
            }

            $page->show_groups($admin_id);
        }
        break;
    case "do_check_for_existing_group":
        $group_name	= import_var('group_name', 'G');
        $group_dir 	= import_var('group_dir', 'S');

        $page->do_check_for_existing_group($group_dir, $group_name);
        break;
    case "show_request_join":
        $user_id 	= import_var('user_id', 'S');
        $data		= array();
        $groups		= array();

        $sql = array(
            "mysql" => array(
                "groups" => "SELECT fgi.group_id,fgi.admin_id,fgi.group_name,fu.username,fu.fname,fu.lname FROM `"._PREFIX."_group_info` AS fgi LEFT JOIN "._PREFIX."_users AS fu ON fgi.admin_id = fu.user_id WHERE fgi.group_id NOT IN ( SELECT group_id FROM "._PREFIX."_group_info WHERE admin_id = ':1' ) AND fgi.group_id NOT IN ( SELECT group_id FROM "._PREFIX."_group_requests WHERE `from`=':2' ) AND fgi.group_id NOT IN ( SELECT group_id FROM "._PREFIX."_invites WHERE to_user_id=':3' )"
            )
        );

        $stmt1 = $page->db->prepare($sql[_DBSYSTEM]['groups']);
        $stmt1->execute($user_id, $user_id, $user_id);

        while($row = $stmt1->fetch_assoc()) {
            $name = trim($row['fname'] . ' ' . $row['lname']);
            $data = array(
                'group_id'	=> $row['group_id'],
                'admin_id'	=> $row['admin_id'],
                'group_name'	=> $row['group_name'],
                'username'	=> ($name == '') ? $row['username'] : $name
            );

            array_push($groups, $data);
        }

        $page->tpl->assign(array(
            'GROUPS'			=> $groups,
            '_GROUPS_CREATE'		=> _GROUPS_CREATE,
            '_GROUPS_LIST'			=> _GROUPS_LIST,
            '_GROUPS_INVITE_USER'		=> _GROUPS_INVITE_USER,
            'JS_INC'			=> 'groups_request_join.tpl',
            '_GROUPS_REQUEST_JOIN'		=> _GROUPS_REQUEST_JOIN));

        $page->tpl->display('groups_request_join.tpl');
        break;
    case "do_request_join":
        $groups = import_var('groups', 'P');
        $user_id = import_var('user_id', 'S');

        if (count($groups) == 0) {
            $page->tpl->assign('_MESSAGE', _GROUPS_REQUEST_MUST_SELECT_ONE);
            $page->tpl->assign('_RETURN_LINK', _GROUPS_REQUEST_RETURN_LINK);

            $page->tpl->display('actions_done.tpl');
        } else {
            foreach ($groups as $group_id => $admin_id) {
                $page->do_request_join($group_id, $admin_id, $user_id);
            }
            $page->tpl->assign('_MESSAGE', _GROUPS_REQUEST_SUCCESS);
            $page->tpl->assign('_RETURN_LINK', _GROUPS_GENERAL_RETURN_LINK);

            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "deny_request":
        $requests = import_var('requests_received', 'P');

        $sql = array(
            "mysql" => array(
                "info" => "DELETE FROM "._PREFIX."_group_requests WHERE `from`=':1' AND `group_id`=':2'"
            )
        );

        $stmt1 = $page->db->prepare($sql[_DBSYSTEM]['info']);

        foreach ($requests as $user_id => $group_id) {
            $stmt1->execute($user_id, $group_id);
        }

        $page->tpl->assign('_MESSAGE', "Did not allow the selected users to join your group.");
        $page->tpl->assign('_RETURN_LINK', _GROUPS_GENERAL_RETURN_LINK);

        $page->tpl->display('actions_done.tpl');
        break;
    case "allow_request":
        $requests = import_var('requests_received', 'P');

        $sql = array(
            "mysql" => array(
                "info" => "SELECT home_dir,group_dir FROM "._PREFIX."_users WHERE user_id=':1'",
                "request_id" => "SELECT request_id FROM "._PREFIX."_group_requests WHERE `from`=':1' AND group_id=':2'"
            )
        );

        $stmt1 = $page->db->prepare($sql[_DBSYSTEM]['info']);
        $stmt2 = $page->db->prepare($sql[_DBSYSTEM]['request_id']);

        foreach ($requests as $user_id => $group_id) {
            $stmt1->execute($user_id);
            $stmt2->execute($user_id, $group_id);

            $row = $stmt1->fetch_assoc();
            $request_id = $stmt2->result(0);

            // The group_dir of the user who is doing the accepting
            $group_dir = $row['group_dir'];

            // The home_dir of the user who is doing the accepting
            $home_dir = $row['home_dir'];

            $page->do_invites_accept($user_id, $group_dir, $home_dir, $group_id);

            $page->do_remove_request($request_id);
        }

        $page->tpl->assign('_MESSAGE', "Allowed the selected users to join your group.");
        $page->tpl->assign('_RETURN_LINK', _GROUPS_GENERAL_RETURN_LINK);

        $page->tpl->display('actions_done.tpl');
        break;
    case "retract_request":
        $requests 	= import_var('requests_sent', 'P');
        $user_id	= import_var('user_id', 'S');

        foreach ($requests as $key => $request_id) {
            $page->do_retract_request($request_id, $user_id);
        }

        $page->tpl->assign('_MESSAGE', _GROUPS_RETRACT_REQUEST_SUCCESS);
        $page->tpl->assign('_RETURN_LINK', _GROUPS_RETRACT_REQUEST_RETURN_LINK);

        $page->tpl->display('actions_done.tpl');
        break;
    default:
        $admin_id 	= import_var('user_id', 'S');

        $page->show_groups($admin_id);
        $page->show_invites_pending($admin_id);
        break;
}

?>
