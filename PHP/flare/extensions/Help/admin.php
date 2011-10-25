<?php
/**
* @package Help
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

require_once(ABSPATH.'/extensions/Help/class.HelpAdmin.php');
require_once(ABSPATH.'/extensions/Help/lang/lang-'.$cfg['language'].'.php');

$page = new HelpAdmin();
$page->__set("db", $db);
$page->__set("tpl", $tpl);
$page->__set("log", $log);
$page->__set("cfg", $cfg);
$page->__set("ext", $ext);

switch ($flare_action) {
    case "show_settings":
        $page->show_settings();
        break;
    case "do_save_settings":
        $visible	= import_var('visible', 'P');

        $settings = array();

        $page->do_save_settings($settings);
        $page->do_change_visibility($visible);
        break;
    case "show_add_topic":
        $page->show_add_topic();
        break;
    case "show_edit_topic":
        $topic_id = import_var('topic_id', 'P');

        if (count($topic_id) > 0)
            $page->show_edit_topic($topic_id[0]);
        else {
            $page->tpl->assign("_MESSAGE", "You must select at least one topic to edit");
            $page->tpl->assign("_RETURN_LINK", "<a href='admin.php?extension=Help&amp;action=show_all_topics'>Return to Help Administration</a>");
            $page->tpl->display("actions_done.tpl");
        }
        break;
    case "do_edit_topic":
        $help_id	= import_var('help_id', 'P');
        $parent_id	= import_var('parent_id', 'P');
        $user_level	= import_var('user_level', 'P');
        $topic_name	= import_var('topic_name', 'P');
        $topic_content	= import_var('topic_content', 'P');
        $type		= import_var('type', 'P');

        $page->do_edit_topic($help_id, $parent_id, $user_level, $topic_name, $topic_content, $type);
        break;
    case "do_delete_topics":
        $topic_id = import_var('topic_id', 'P');

        foreach ($topic_id as $key => $val) {
            $page->do_delete_topics($val);
        }

        $page->tpl->assign('_MESSAGE', _HELP_ADM_DELETE_TOPIC_SUCCESS);
        $page->tpl->assign('_RETURN_LINK', _HELP_ADM_DELETE_TOPIC_SUCCESS_RETURN_LINK);

        $page->tpl->display('actions_done.tpl');
        break;
    case "do_add_topic":
        $parent_topic	= import_var('parent_topic', 'P');
        $user_level	= import_var('user_level', 'P');
        $topic_name	= import_var('topic_name', 'P');
        $topic_content	= import_var('topic_content', 'P');
        $type		= import_var('type', 'P');

        $page->do_add_topic($parent_topic, $user_level, $topic_name, $topic_content, $type);
        break;
    case "show_feedback":
        $page->show_feedback();
        break;
    case "do_delete_feedback":
        $feedback_id = import_var('feedback_id', 'P');

        foreach ($feedback_id as $key => $val) {
            $page->do_delete_feedback($val);
        }

        $page->tpl->assign('_MESSAGE', "Successfully removed selected feedback items");
        $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Help&amp;action=show_all_topics'>Return to Help Administration</a>");

        $page->tpl->display('actions_done.tpl');
        break;
    case "do_read_feedback":
        $feedback_id = import_var('feedback_id', 'G');

        $page->do_mark_read($feedback_id);
        $page->do_read_feedback($feedback_id);
        break;
    case "show_reply_feedback":
        $feedback_id = import_var('feedback_id', 'P');

        $page->show_reply_feedback($feedback_id);
        break;
    case "do_reply_feedback":
        $recipient 	= import_var('recipient', 'P');
        $email_from	= import_var('email_from', 'P');
        $email_subject	= import_var('email_subject', 'P');
        $email_body 	= import_var('email_body', 'P');
        $id		= import_var('id', 'P');

        foreach($id as $key => $val) {
            $page->do_write_email($email_from[$key], $recipient[$key], $email_subject[$key], $email_body[$key]);
        }
        $page->tpl->assign('_MESSAGE', "Successfully sent feedback");
        $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Help&amp;action=show_feedback'>Return to Feedback Administration</a>");

        $page->tpl->display('actions_done.tpl');
        break;
    case "do_mark_read":
        $feedback = import_var('feedback_id', 'P');

        foreach ($feedback as $key => $id) {
            $page->do_mark_read($id);
        }
        $page->tpl->assign('_MESSAGE', "Successfully marked the selected items as 'read'");
        $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Help&amp;action=show_feedback'>Return to Feedback Administration</a>");

        $page->tpl->display('actions_done.tpl');
        break;
    case "do_mark_unread":
        $feedback = import_var('feedback_id', 'P');

        foreach ($feedback as $key => $id) {
            $page->do_mark_unread($id);
        }
        $page->tpl->assign('_MESSAGE', "Successfully marked the selected items as 'unread'");
        $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=Help&amp;action=show_feedback'>Return to Feedback Administration</a>");

        $page->tpl->display('actions_done.tpl');
        break;
    case "show_all_topics":
    default:
        $page->show_all_topics();
        break;
}

?>
