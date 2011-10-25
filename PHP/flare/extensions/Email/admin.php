<?php
/**
* @package Email
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

require_once(ABSPATH."/extensions/Email/class.EmailAdmin.php");
require_once(ABSPATH."/extensions/Email/class.MailingListAdmin.php");
require_once(ABSPATH.'/extensions/Email/lang/lang-'.$cfg['language'].'.php');

$page = new EmailAdmin();
$page_ml = new MailingListAdmin();

$page->__set("db",$db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);
$page->__set("ext",$ext);

$page_ml->__set("db",$db);
$page_ml->__set("tpl",$tpl);
$page_ml->__set("log",$log);
$page_ml->__set("cfg",$cfg);
$page_ml->__set("ext",$ext);

switch ($flare_action) {
    case "do_write_email":
        $recipients 	= import_var('recipients', 'P');
        $lists		= import_var('mailing_lists', 'P');
        $subject	= import_var('email_subject', 'P');
        $body		= import_var('email_body', 'P');

        if (count($recipients) == 0 && count($lists) == 0) {
            $page->tpl->assign(array(
                '_MESSAGE'	=> "You must select at least one recipient to send to.",
                '_RETURN_LINK'	=> "<a href='admin.php?extension=Email&action=show_write_email'>Return to Write Email</a>"
            ));

            $page->tpl->display('actions_done.tpl');
        } else {
            $sql = array(
                "mysql" => array(
                    "recipients" => "SELECT org_email FROM "._PREFIX."_mailing_lists AS fml LEFT JOIN "._PREFIX."_users AS fu ON fml.user_id=fu.user_id WHERE fml.list_id=':1'"
                )
            );

            if (count($lists) > 0) {
                $stmt1 = $page->db->prepare($sql[_DBSYSTEM]['recipients']);

                foreach($lists as $key => $val) {
                    $stmt1->execute($val);

                    while($row = $stmt1->fetch_assoc()) {
                        $list_recipients[] = $row['org_email'];
                    }
                }
            }

            $tmp_recipients = array_merge($recipients, $list_recipients);
            $recipients = $tmp_recipients;

            foreach ($recipients as $key => $recipient) {
                $retval = $page->do_write_email($recipient, $subject, $body);

                if (PEAR::isError($retval)) {
                    $page->tpl->assign(array(
                        '_MESSAGE'	=> "Sending to $recipient failed.",
                        '_RETURN_LINK'	=> "<a href='admin.php?extension=Email&action=show_write_email'>Return to Write Email</a>"
                    ));

                    $page->tpl->display('actions_done.tpl');
                    exit;
                }
            }

            $page->tpl->assign(array(
                '_MESSAGE'	=> "Email sent successfully",
                '_RETURN_LINK'	=> "<a href='admin.php?extension=Email&action=show_write_email'>Return to Email Admin</a>"
            ));

            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "show_settings":
        $page->show_settings();
        break;
    case "do_save_settings":
        $visible	= import_var('visible', 'P');
        $visible 	= ($visible == "on") ? 1 : 0;
        $settings = array(
            'mail_from'	=> import_var('mail_from', 'P'),
            'mail_server'	=> import_var('mail_server', 'P'),
            'mail_port'	=> import_var('mail_port', 'P'),
        );

        $page->do_save_settings($settings);
        $page->do_change_visibility($visible);
        break;
    case "show_create_mailing_list":
        $users = array();
        $data = array();
        $sql = array(
            "mysql" => array(
                "users" => "SELECT username,user_id FROM "._PREFIX."_users ORDER BY username ASC"
            )
        );

        $stmt1 = $db->prepare($sql[_DBSYSTEM]['users']);

        $stmt1->execute();

        while ($row = $stmt1->fetch_assoc()) {
            $data = array(
                'username' => $row['username'],
                'id' => $row['user_id']
            );

            array_push($users, $data);
        }

        $tpl->assign(array(
            '_SETTINGS'	=> _SETTINGS,
            'USERS'		=> $users,
        ));

        $tpl->display('email_mailing_list_add.tpl');
        break;
    case "do_create_mailing_list":
        $list_name 	= import_var('list_name', 'P');
        $list_members	= import_var('list_members', 'P');

        $page_ml->do_create_mailing_list($list_name);

        $list_id = $page_ml->get_mailing_list_id($list_name);

        foreach ($list_members as $key => $user_id) {
            $page_ml->add_member_to_mailing_list($list_id, $user_id);
        }

        $page_ml->tpl->assign('_MESSAGE', "Mailing list successfully created");
        $page_ml->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Email&action=show_mailing_lists'>Return to Mailing List Admin</a>");

        $page_ml->tpl->display('actions_done.tpl');
        break;
    case "show_mailing_lists":
        $page_ml->show_mailing_lists();
        break;
    case "show_change_mailing_list":
        $mailing_lists = import_var('mailing_list', 'P');

        $page_ml->show_change_mailing_list($mailing_lists[0]);
        break;
    case "do_change_mailing_list":
        $list_id 	= import_var('list_id', 'P');
        $list_name	= import_var('list_name', 'P');
        $rm_recipients	= import_var('rm_recipients', 'P');
        $add_recipients	= import_var('add_recipients', 'P');

        $page_ml->do_change_mailing_list($list_id,$list_name,$rm_recipients,$add_recipients);

        $page_ml->tpl->assign('_MESSAGE', "Mailing list successfully updated");
        $page_ml->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Email&action=show_mailing_lists'>Return to Mailing List Admin</a>");

        $page_ml->tpl->display('actions_done.tpl');
        break;
    case "do_delete_mailing_list":
        $mailing_lists = import_var('mailing_list', 'P');

        foreach($mailing_lists as $key => $list_id) {
            $page_ml->do_delete_mailing_list($list_id);
        }

        $page_ml->tpl->assign('_MESSAGE', "Mailing list successfully removed");
        $page_ml->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Email&action=show_mailing_lists'>Return to Mailing List Admin</a>");

        $page_ml->tpl->display('actions_done.tpl');
        break;
    default:
    case "show_write_email":
        $sql = array(
            "mysql" => array(
                "select" => "SELECT username,org_email FROM "._PREFIX."_users ORDER BY username ASC",
                "mlists" => "SELECT list_id, list_name FROM "._PREFIX."_mailing_list_info ORDER BY list_name ASC"
            )
        );

        $stmt1 = $page->db->prepare($sql[_DBSYSTEM]['select']);
        $stmt2 = $page->db->prepare($sql[_DBSYSTEM]['mlists']);
        $stmt1->execute();
        $stmt2->execute();

        while ($row = $stmt1->fetch_assoc()) {
            $users[] = array(
                'username'	=> $row['username'],
                'email'		=> $row['org_email']
            );
        }

        while ($row = $stmt2->fetch_assoc()) {
            $lists[] = array(
                'id'	=> $row['list_id'],
                'name'	=> $row['list_name']
            );
        }

        $page->tpl->assign(array(
            "USERS"		=> $users,
            "LISTS"		=> $lists,
            "_NONE"		=> _NONE,
            "_SETTINGS"	=> _SETTINGS,
            "_RESET_FORM"	=> _RESET_FORM,
        ));

        $page->tpl->display("email_main.tpl");
        break;
}

?>
