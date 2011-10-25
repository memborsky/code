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

require_once(ABSPATH.'/extensions/Help/class.Help.php');
require_once(ABSPATH.'/extensions/Help/lang/lang-'.$cfg['language'].'.php');

$page = new Help();
$page->__set("db", $db);
$page->__set("tpl", $tpl);
$page->__set("log", $log);
$page->__set("cfg", $cfg);
$page->__set("ext", $ext);

switch ($flare_action) {
    case "show_credits":
        $page->show_credits();
        break;
    case "show_privacy_policy":
        $page->show_privacy_policy();
        break;
    case "show_feedback":
        $page->show_feedback();
        break;
    case "do_leave_feedback":
        $date		= import_var('date', 'P');
        $email		= import_var('email', 'P');
        $short_desc	= import_var('short_desc', 'P');
        $content	= import_var('content', 'P');

        $page->do_leave_feedback($date, $email, $short_desc, $content);

        $page->tpl->assign('_MESSAGE', "Thanks for your feedback!");
        $page->tpl->assign('_RETURN_LINK', "<a href='#' onClick='javascript:window.close()'>Close Window</a>");
        $page->tpl->display('actions_done_empty.tpl');
        break;
    case "show_usage":
        $page->show_usage_policy();
        break;
    case "show_tos":
        $page->show_tos();
        break;
    case "show_help_topic":
        $topic_id = import_var('topic_id', 'G');
        $user_level = import_var('user_level', 'S');

        $page->show_help($topic_id, $user_level);
        break;
    default:
    case "general_help":
        $user_level = import_var('user_level', 'S');
        $page->show_help(0, $user_level);
        break;
}

?>
