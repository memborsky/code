<?php
/**
* @package ClamAV
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

// Require the PEAR XML Parser
if(!@include_once ('XML/Parser.php')) {
    assign_header();
    assign_footer();
    $tpl->assign('_MESSAGE', "PEAR::XML_Parser could not be loaded!");
    $tpl->display('actions_done.tpl');
    exit;
}

require_once(ABSPATH.'/extensions/ClamAV/class.ClamAVAdmin.php');
require_once(ABSPATH.'/extensions/ClamAV/lang/lang-'.$cfg['language'].'.php');

$page = new ClamAVAdmin();

$page->__set("db",$db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);
$page->__set("ext",$ext);

switch ($flare_action) {
    case "do_schedule_clamscan":
        $items = import_var('item', 'P');

        if (count($items) == 0) {
            $page->tpl->assign("_MESSAGE", "You must select at least one item to scan.");
            $page->tpl->assign("_RETURN_LINK", "<a href='admin.php?extension=ClamAV&action=show_files'>Back to ClamAV Administration</a>");
            $page->tpl->display('actions_done.tpl');
        } else {
            $page->do_schedule_clamscan($items);

            $page->tpl->assign("_MESSAGE", "Antivirus scan scheduled.");
            $page->tpl->assign("_RETURN_LINK", "<a href='admin.php?extension=ClamAV&action=show_files'>Back to ClamAV Administration</a>");
            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "show_unscanned_files":
        $page->tpl->assign("_MESSAGE", _NOT_IMPLEMENTED);
        $page->tpl->assign("_RETURN_LINK", "<a href='admin.php?extension=ClamAV&action=show_files'>Back to ClamAV Administration</a>");
        $page->tpl->display('actions_done.tpl');
        break;
    case "show_scan_results":
        $ready 		= false;
        $scan_id	= import_var('scan_id', 'G');

        $ready = $page->check_results_ready($scan_id);

        if ($ready) {
            $page->show_clamscan_results($scan_id);
        } else {
            $page->tpl->assign('_MESSAGE', "No results found for this scan.");
            $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=ClamAV&amp;action=show_scans'>Back to ClamAV Administration</a>");

            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "show_scan_details":
        $scan_id	= import_var('scan_id', 'G');

        $page->show_clamscan_details($scan_id);
        break;
    case "show_settings":
        $page->show_settings();
        break;
    case "do_save_settings":
        break;
    case "show_schedule_scan":
        $tree = $page->treeview();

        $page->tpl->assign(array(
            '_SETTINGS'		=> _SETTINGS,
            '_CLAM_SCANS'		=> _CLAM_SCANS,
            '_CLAM_SCHEDULE_SCAN'	=> _CLAM_SCHEDULE_SCAN,
            'CSS_INC'		=> 'extensions/ClamAV/styles.css',
            'JS_INC'		=> 'clamav_main.tpl',
            'BODY_ON_LOAD'		=> "loadTree('$tree')"
        ));

        $page->tpl->display('clamav_schedule_scan.tpl');
        break;
    case "do_delete_scan":
        $scan_ids = import_var('scan_id', 'P');

        if (count($scan_ids) == 0) {
            $page->tpl->assign('_MESSAGE', "You must select at least one scan to remove.");
            $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=ClamAV&amp;action=show_scans'>Back to ClamAV Administration</a>");
            $page->tpl->display('actions_done.tpl');
        } else {
            foreach ($scan_ids as $key => $val) {
                $page->do_delete_scan($val);
            }
            $page->tpl->assign('_MESSAGE', "Scans and their results have been removed.");
            $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=ClamAV&amp;action=show_scans'>Back to ClamAV Administration</a>");
            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "do_reschedule_scan":
        $scan_ids = import_var('scan_id', 'P');

        if (count($scan_ids) == 0) {
            $page->tpl->assign('_MESSAGE', "You must select at least one scan to remove.");
            $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=ClamAV&amp;action=show_scans'>Back to ClamAV Administration</a>");
            $page->tpl->display('actions_done.tpl');
        } else {
            foreach ($scan_ids as $key => $val) {
                $page->do_reschedule_scan($val);
            }
            $page->tpl->assign('_MESSAGE', "Selected scans have been rescheduled.");
            $page->tpl->assign('_RETURN_LINK', "<a href='admin.php?extension=ClamAV&amp;action=show_scans'>Back to ClamAV Administration</a>");
            $page->tpl->display('actions_done.tpl');
        }
        break;
    case "show_scans":
    default:
        $page->show_summary();
        break;
}

?>
