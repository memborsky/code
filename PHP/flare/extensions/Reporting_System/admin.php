<?php
/**
* @package Reporting_System
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

require_once(ABSPATH.'/extensions/Reporting_System/class.ReportingAdmin.php');
require_once(ABSPATH.'/extensions/Reporting_System/lang/lang-'.$cfg['language'].'.php');

// Require the PEAR Image_Color
if(!include_once('Image/Color.php')) {
    assign_header();
    assign_footer();
    $tpl->assign('_MESSAGE', "PEAR::Image_Color could not be loaded!");
    $tpl->display('actions_done.tpl');
    exit;
}

// Require the PEAR Image_Canvas
if(!include_once('Image/Canvas.php')) {
    assign_header();
    assign_footer();
    $tpl->assign('_MESSAGE', "PEAR::Image_Canvas could not be loaded!");
    $tpl->display('actions_done.tpl');
    exit;
}
// Require the PEAR Image_Graph
if(!include_once('Image/Graph.php')) {
    assign_header();
    assign_footer();
    $tpl->assign('_MESSAGE', "PEAR::Image_Graph could not be loaded!");
    $tpl->display('actions_done.tpl');
    exit;
}

require_once ('Image/Graph.php');

$page = new ReportingAdmin();

$page->__set("db",$db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);
$page->__set("ext",$ext);
$page->__set("report_path", $cfg['report_path']);

$page->discover_reports();

switch ($flare_action) {
    case "show_add_system_message":
        $page->tpl->assign(array(
            "_SETTINGS"			=> _SETTINGS,
            "_REPORT_ANNOUNCE_NEW_MESG"	=> _REPORT_ANNOUNCE_NEW_MESG,
            "_REPORT_MESG_TO_ANNOUNCE"	=> _REPORT_MESG_TO_ANNOUNCE,
            "_REPORT_CLEAR_MESG"		=> _REPORT_CLEAR_MESG,
            "_REPORT_ANNOUNCE_MESG"		=> _REPORT_ANNOUNCE_MESG,
            "_REPORT_SUMMARY"		=> _REPORT_SUMMARY
        ));

        $page->tpl->display("reporting_messages_add.tpl");
        break;
    case "do_add_system_message":
        $message 	= import_var('system_message', 'P');
        $subject	= import_var('subject', 'P');
        $author_id	= import_var('user_id', 'S');

        $page->do_add_system_message($subject,$message,$author_id);
        break;
    case "show_settings":
        $page->show_settings();
        break;
    case "do_save_settings":
        $settings = array(
            'template'	=> import_var('template', 'P'),
            'user_root'	=> import_var('user_root', 'P'),
            'user_dir'	=> import_var('user_dir', 'P'),
            'group_dir'	=> import_var('group_dir', 'P')
        );

        $page->do_save_settings($settings);
        break;
    case "show_change_messages":
        $mesg_id = import_var('mesg_id', 'P');

        $page->show_change_messages($mesg_id);
        break;
    case "do_change_messages":
        $mesg_id	= import_var('mesg_id', 'P');
        $subject 	= import_var('subject', 'P');
        $system_message	= import_var('system_message', 'P');

        foreach ($mesg_id as $key => $id) {
            do_change_message($id, $subject[$key], $system_message[$key]);
        }
        break;
    case "do_delete_messages":
        $messages = import_var('mesg_id', 'P');

        $page->do_delete_messages($messages);

        $page->show_summary();
        break;
    case "show_report":
        if ($_POST)
            $report_id	= import_var('report_id', 'P');
        else
            $report_id	= import_var('report_id', 'G');

        $rcfg 		= $page->report_info($report_id);

        $report_name 	= $page->get_report_name($report_id);

        require_once($page->__get('report_path') . 'report.' . $report_name . '.php');

        $report = new $report_name;
        $report->__set("db", $page->__get("db"));
        $report->__set("tpl", $page->__get("tpl"));
        $report->__set("cfg", $rcfg);
        $report->__set("report_path", $cfg['report_path']);

        $report->show();
        break;
    case "show_summary":
    default:
        $page->show_summary();
}

?>
