<?php
/**
* @package Maintenance
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

require_once(ABSPATH.'/extensions/Maintenance/class.MaintenanceAdmin.php');
require_once(ABSPATH.'/extensions/Maintenance/lang/lang-'.$cfg['language'].'.php');

$page = new MaintenanceAdmin();

$page->__set("db",$db);
$page->__set("tpl",$tpl);
$page->__set("log",$log);
$page->__set("cfg",$cfg);
$page->__set("ext",$ext);
$page->__set("task_path", $cfg['task_path']);

$page->discover_tasks();

switch ($flare_action) {
    case "show_task":
        if ($_POST)
            $task_id	= import_var('task_id', 'P');
        else
            $task_id	= import_var('task_id', 'G');

        $tcfg 		= $page->task_info($task_id);

        $task_name 	= $page->get_task_name($task_id);

        require_once($page->__get('task_path') . 'task.' . $task_name . '.php');

        $task = new $task_name($task_id);
        $task->__set("db", $page->__get("db"));
        $task->__set("tpl", $page->__get("tpl"));
        $task->__set("cfg", $tcfg);

        $task->show();
        break;
    case "run_task":
        if ($_POST)
            $task_id	= import_var('task_id', 'P');
        else
            $task_id	= import_var('task_id', 'G');

        $tcfg 		= $page->task_info($task_id);

        $task_name 	= $page->get_task_name($task_id);

        require_once($page->__get('task_path') . 'task.' . $task_name . '.php');

        $task = new $task_name($task_id);
        $task->__set("db", $page->__get("db"));
        $task->__set("tpl", $page->__get("tpl"));
        $task->__set("cfg", $tcfg);

        if ($task->run()) {
            $page->tpl->assign("_MESSAGE", "Successfully finished running the task");
        } else {
            $page->tpl->assign("_MESSAGE", "The task failed to run successfully");
        }

        $page->tpl->assign("_RETURN_LINK", "<a href='admin.php?extension=Maintenance'>Return to Maintenance Admin</a>");
        $page->tpl->display('actions_done.tpl');
        break;
    case "do_maintenance_mode_on":
        $page->do_maintenance_mode("on");
        break;
    case "do_maintenance_mode_off":
        $page->do_maintenance_mode("off");
        break;
    default:
        $page->show_summary();
        break;
}

?>
