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

/**
*
*/
class LoggingAdmin extends Logging {
    public $db;
    public $tpl;
    public $cfg;
    public $ext;

    /**
    *
    */
    public function __construct(&$db) {
        parent::__construct($db);
    }

    /**
    *
    */
    public function __get( $key ) {
        return isset( $this->$key ) ? $this->$key : NULL;
    }

    /**
    *
    */
    public function __set( $key, $value ) {
        $this->$key = $value;
    }

    /**
    *
    */
    public function show_log($type = "all", $id = 1) {
        // Require the PEAR Pager
        if(!@include_once ('Pager/Pager.php')) {
            assign_header();
            assign_footer();
            $this->tpl->assign('_MESSAGE', "PEAR::Pager could not be loaded!");
            $this->tpl->display('actions_done.tpl');
            exit;
        }

        if ($type == "all") {
            $clause = '';
        } else {
            $clause = "WHERE type=':1'";

        }

        $sql = array (
            "mysql" => array (
                'log' => "SELECT * FROM "._PREFIX."_logging $clause ORDER BY log_id ASC",
                'log_entries' => "SELECT type FROM "._PREFIX."_log_entries ORDER BY type ASC"
                )
        );

        $logs = array();
        $log_entries = array();
        $pages = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['log']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['log_entries']);

        $stmt1->execute($type);
        $stmt2->execute();

        // Return all the logs
        while ($row = $stmt1->fetch_array()) {
            $logs[] = array($row['log_id'],
                    $row['type'],
                    strftime("%Y-%m-%d",$row['timestamp']),
                    strftime("%I:%M:%S %p",$row['timestamp']),
                    $row['ip'],
                    $row['contents']
                );
        }

        // Build the data list of all the available log_types to sort by
        while ($row = $stmt2->fetch_array()) {
            $log_entries[] = $row['type'];
        }

        // Assign settings for pager
        $params = array(
            'mode'       		=> $this->cfg['mode'],
            'perPage'    		=> $this->cfg['perPage'],
            'delta'      		=> $this->cfg['delta'],
            'itemData'   		=> $logs,
            'append'		=> FALSE,
            'fileName'		=> "admin.php?extension=Logging&action=sort_log_type&type=$type&pageID=%d",
            'currentPage'		=> $id,
            '_curPageSpanPre'	=> '<span style="font-weight: bold; text-decoration: underline">',
            '_curPageSpanPost'	=> '</span>'
        );

        // Create new pager object and send it the settings above
        $pager = & Pager::factory($params);

        // Return the paged data according to the perPage setting above
        $data  = $pager->getPageData();

        // Return the links that can be used to navigate to different pages of the log
        $links = $pager->getLinks();

        // Resort the indexes of the paged data, otherwise Smarty will choke
        // Because the starting index will be higher than 0 on every page except the first
        if (is_array($data))
            $data = array_values($data);

        // Assign language constants
        $this->tpl->assign(array(
            '_LOG_SHOW_LOG'		=> _LOG_SHOW_LOG,
            '_LOG_CONFIG_LOG_TYPES'	=> _LOG_CONFIG_LOG_TYPES,
            '_LOG_CLEAR_LOG'	=> _LOG_CLEAR_LOG));

        // Assign dynamic content
        $this->tpl->assign(array(
            'LOGS'		=> $data,
            'FIRST'		=> $links['first'],
            'BACK'		=> " " . $links['back'],
            'LINKS'		=> $links['pages'],
            'NEXT'		=> $links['next'],
            'LAST'		=> $links['last'],
            'LOG_ENTRIES'	=> $log_entries));

        // Display the page
        $this->tpl->display('logging_main.tpl');
    }

    /**
    *
    */
    public function show_clear_log() {
        $log_types = array();
        $sql = array(
            "mysql" => array(
                "log_type" => "SELECT type FROM "._PREFIX."_log_entries ORDER BY type ASC"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['log_type']);

        $stmt1->execute();

        $log_types[] = array (
            "type" => "All Log Entries"
        );

        while ($row = $stmt1->fetch_array()) {
            $log_types[] = array(
                "type" => $row['type']
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_LOG_SHOW_LOG'		=> _LOG_SHOW_LOG,
            '_LOG_CLEAR_LOG'	=> _LOG_CLEAR_LOG
        ));

        // Assign dynamic content
        $this->tpl->assign("LOG_TYPES", $log_types);

        // Display the page
        $this->tpl->display("logging_clear.tpl");
    }

    /**
    *
    */
    public function do_clear_log($type) {
        // Set up our structure of SQL we will execute
        $sql = array (
            "mysql" => array (
                'clear_log_all' => "DELETE FROM "._PREFIX."_logging",
                'clear_log_select' => "DELETE FROM "._PREFIX."_logging WHERE type = ':1'"
                )
        );

        // Prepare the SQL
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['clear_log_all']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['clear_log_select']);

        if ($type != "All Log Entries")
            // Run it
            $stmt2->execute($type);
        else
            $stmt1->execute();
    }

    /**
    *
    */
    public function sort_log_type($type, $pageID) {
        $this->show_log($type, $pageID);
    }

    /**
    *
    */
    public function do_add_log_type($type, $content) {
        $sql = array(
            'mysql' => array (
                'select_type' => "SELECT type_id FROM "._PREFIX."_log_entries WHERE type=':1'",
                'log_type' => "INSERT INTO "._PREFIX."_log_entries (`type`,`content`) VALUES (':1',':2')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select_type']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['log_type']);

        $stmt1->execute($type);

        if ($stmt1->num_rows() > 0) {
            $this->__set("error", TRUE);
        } else {
            $stmt2->execute($type, $content);
            $this->__set("error", FALSE);
        }
    }

    /**
    *
    */
    public function show_log_type_actions() {
        $sql = array (
            'mysql' => array (
                'log_types' => "SELECT * FROM "._PREFIX."_log_entries ORDER BY type ASC"
                )
        );
        $log_types = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['log_types']);
        $stmt1->execute();

        while ($row = $stmt1->fetch_array()) {
            $log_types[] = array(
                'type_id'	=> $row['type_id'],
                'type'		=> $row['type'],
                'content'	=> $row['content'],
                'description'	=> $row['description']);
        }

        $this->tpl->assign('LOG_TYPES', $log_types);

        $this->tpl->display('logging_config.tpl');
    }

    /**
    *
    */
    public function show_edit_log_type($type_list) {
        $sql = array (
            "mysql" => array (
                'log_type_info' => "SELECT * FROM "._PREFIX."_log_entries WHERE type=':1'"
                )
        );
        $log_info = array ();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['log_type_info']);

        if (!is_array($type_list)) {
            $stmt1->execute($type_list);
        } else {
            foreach ($type_list as $key => $val) {
                $stmt1->execute($val);
                while ($row = $stmt1->fetch_array()) {
                    $log_info[] = array (
                        'type_id'	=> $row['type_id'],
                        'type' 		=> $row['type'],
                        'content'	=> $row['content']
                    );
                }
            }
        }

        $this->tpl->assign('LOG_INFO', $log_info);
        $this->tpl->display('logging_edit.tpl');
    }

    /**
    *
    */
    public function do_edit_log_type($type,$content) {
        $sql = array (
            "mysql" => array (
                'type_update' => "UPDATE "._PREFIX."_log_entries SET type=':1', content=':2' WHERE type_id=':3'"
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['type_update']);

        foreach ($type as $type_id => $type) {
            $stmt1->execute($type,$content[$type_id],$type_id);
        }

        $this->tpl->assign('_MESSAGE', _LOG_EDIT_LOG_TYPE_SUCCESS);
        $this->tpl->assign('_RETURN_LINK', _LOG_RETURN_LINK);
        $this->tpl->display('actions_done.tpl');
    }

    /**
    *
    */
    public function do_delete_log_type($log_type) {
        $sql = array (
            "mysql" => array (
                "delete_logs" => "DELETE FROM "._PREFIX."_logging WHERE type=':1'",
                "delete_log_type" => "DELETE FROM "._PREFIX."_log_entries WHERE type=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["delete_logs"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["delete_log_type"]);

        foreach ($log_type as $key => $type) {
            if ($type == "DEFAULT")
                continue;
            else {
                $stmt1->execute($type);
                $stmt2->execute($type);

                $this->log("DELETE_LOG_TYPE", $type, import_var('username', 'S'));
            }
        }
    }
}

?>
