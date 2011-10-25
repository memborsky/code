<?php
/**
* @package Logging
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

require_once(ABSPATH.'/extensions/Logging/class.Logging.php');
require_once(ABSPATH.'/extensions/Logging/class.LoggingAdmin.php');
require_once(ABSPATH.'/extensions/Logging/lang/lang-'.$cfg['language'].'.php');

$page = new LoggingAdmin($db);

$page->__set("tpl", $tpl);
$page->__set("cfg", $cfg);
$page->__set("ext", $ext);

switch($flare_action) {
    case "show_clear_log":
        $page->show_clear_log();
        break;
    case "do_clear_log":
        $log_types = import_var('log_type', 'P');

        foreach ($log_types as $key => $type) {
            $page->do_clear_log($type);

            // Log that clear_log was done
            $page->log('LOG_CLEAR_LOG', import_var('username', 'S'), $type);
        }

        // Assign success messages and link back
        $page->tpl->assign('_MESSAGE', _LOG_CLEARLOG_SUCCESS);
        $page->tpl->assign('_RETURN_LINK', _LOG_RETURN_LINK);

        // Display the page showing the actions are now done
        $page->tpl->display('actions_done.tpl');

        break;
    case "sort_log_type":
        if (!empty($_POST)) {
            $type = import_var('type', 'P');
            $pageID = import_var('pageID', 'P');
        } else {
            $type = import_var('type', 'G');
            $pageID = import_var('pageID', 'G');
        }

        if ($type == "")
            $type = "all";

        if ($pageID == "")
            $pageID = 1;

        $page->sort_log_type($type, $pageID);
        break;
    case "show_log_type_actions":
        $page->show_log_type_actions();
        break;
    case "show_add_log_type":
        $page->tpl->assign(array(
            '_LOG_SHOW_TYPES'	=> _LOG_SHOW_TYPES,
            '_LOG_ADD_NEW_TYPE'	=> _LOG_ADD_NEW_TYPE,
            '_LOG_TYPE'		=> _LOG_TYPE,
            '_LOG_CONTENT'		=> _LOG_CONTENT,
            '_LOG_ADD_TYPE'		=> _LOG_ADD_TYPE,
            '_RESET_FORM'		=> _RESET_FORM
        ));

        $page->tpl->display('logging_add.tpl');
        break;
    case "do_add_log_type":
        $type 		= import_var('type', 'P');
        $content 	= import_var('content', 'P');

        $page->do_add_log_type($type, $content);

        if ($page->__get("error")) {
            $page->tpl->assign("_MESSAGE", "Failed to add new log type");
            $page->tpl->assign("_RETURN_LINK", _LOG_RETURN_LINK);
        } else {
            $page->tpl->assign("_MESSAGE", "Added new log type successfully");
            $page->tpl->assign("_RETURN_LINK", _LOG_RETURN_LINK);
        }

        $page->tpl->display("actions_done.tpl");
        break;
    case "show_edit_log_type":
        $type_list = import_var('log_type', 'P');
        $page->show_edit_log_type($type_list);
        break;
    case "do_edit_log_type":
        $type		= import_var('type', 'P');
        $content	= import_var('content', 'P');

        $page->do_edit_log_type($type,$content);
        break;
    case "do_delete_log_type":
        $log_type = import_var('log_type', 'P');

        if (count($log_type) == 0) {
            $page->tpl->assign("_MESSAGE", _LOG_LEAST_ONE_TYPE);
            $page->tpl->assign("_RETURN_LINK", _LOG_CONFIG_TYPE_RETURN_LINK);
        } else {
            $page->do_delete_log_type($log_type);

            $page->tpl->assign("_MESSAGE", _LOG_DELETE_LOG_TYPE_SUCCESS);
            $page->tpl->assign("_RETURN_LINK", _LOG_CONFIG_TYPE_RETURN_LINK);
        }
        $page->tpl->display('actions_done.tpl');
        break;
    case "show_log":
    default:
        $type = import_var('type', 'G');
        $pageID = import_var('pageID', 'G');

        if ($type == "");
            $type = "all";

        if ($pageID == "");
            $pageID = 1;

        $page->show_log($type, $pageID);
        break;
}

?>
