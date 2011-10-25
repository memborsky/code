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
* Optimize the database we use for Flare.
*
* This will optimize all of the tables for us automatically.
*
* @package Maintenance
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class remove_old_reports {
    /**
    * Holds the unique ID of the task
    *
    * @access private
    * @var integer
    */
    private $task_id;

    /**
    * Name of the task as it will appear in the database.
    * This is limited to 64 characters in length
    *
    * @access private
    * @var string
    */
    private $task_name;

    /**
    * Description of the task. Keep this limited to no more than 255 characters
    *
    * @access private
    * @var string
    */
    private $task_desc;

    /**
    * Database object used to connect to and query database
    *
    * @access public
    * @var object
    */
    public $db;

    /**
    * Template object used to display pages
    *
    * @access public
    * @var object
    */
    public $tpl;

    /**
    * Contains all settings stored in the maintenance_tasks
    * table that relate to this task
    *
    * @access public
    * @var object
    */
    public $cfg;

    /**
    * The directory to find reports in
    *
    * @access private
    * @var string
    */
    private $report_dir;

    /**
    * Report dir as used by the img link on the show page
    *
    * @access private
    * @var string
    */
    private $report_dir_web;

    /**
    * Creates an instance of Task
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided.
    *
    * @access public
    */
    public function __construct($task_id = 0) {
        $report_path = "extensions/Reporting_System/reports/";

        // Set the name of the report according to filename
        $this->__set("task_id", $task_id);

        // Set the name of the report according to filename
        $this->__set("task_name", "remove_old_reports");

        // Set up description
        $this->__set("task_desc", "Removes old Reporting System graphs");

        $this->__set("report_dir_web", $report_path);
        $this->__set("report_dir", ABSPATH.'/'.$report_path);
    }

    /**
    * Returns the value of a class variable
    *
    * Given a class variable name, this method will return the
    * value associated with the name.
    *
    * @access public
    * @param string $key class variable name
    * @return misc $key value stored in variable
    */
    public function __get( $key ) {
        return isset( $this->$key ) ? $this->$key : NULL;
    }

    /**
    * Sets the value of a class variable
    *
    * Given a class variable name and the value that you wish to
    * store in that variable, this method will store the supplied
    * value in the named variable
    *
    * @access public
    */
    public function __set( $key, $value ) {
        $this->$key = $value;
    }

    /**
    * Installs task into Flare system
    *
    * log_entry_counts requires no extra tables be added. A simple registration with
    * the tasks table is all that is needed to run and use this task
    *
    * @access public
    */
    public function install() {
        $sql_report = array(
            "mysql" => array(
                "install" => "INSERT INTO "._PREFIX."_maintenance_tasks (`name`, `description`) VALUES (':1', ':2')"
            )
        );

        $stmt_report = $this->db->prepare($sql_report[_DBSYSTEM]['install']);

        $stmt_report->execute($this->__get("task_name"), $this->__get("task_desc"));
    }

    /**
    *
    */
    public function uninstall() {
        $sql = array(
            "mysql" => array(
                "uninstall" => "DELETE FROM "._PREFIX."_maintenance_tasks WHERE task_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['uninstall']);

        $stmt1->execute($this->__get("task_id"));
    }

    /**
    * Main display method of Task
    *
    * This method should be used in the same way
    * that the index and admin PHP files as used
    * in the main class folder. Switching between
    * cases is an example.
    *
    * @access public
    */
    public function show() {
        $graphs = array();
        if ($handle = opendir($this->__get("report_dir"))) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $tmp = explode('.', $file);

                    if ($tmp[1] == "png") {
                        $info = stat($this->__get("report_dir")."/".$file);
                        $date = strftime("%d-%m-%Y at %r", $info[9]);

                        $graphs[] = array(
                            'name'	=> $file,
                            'date'	=> $date,
                            'link'	=> $this->__get("report_dir_web")."/".$file
                        );
                    }
                }
            }
            closedir($handle);
        }

        $this->tpl->assign(array(
            'GRAPHS'	=> $graphs,
            'TASK_ID'	=> $this->__get("task_id"),
            'JS_INC'	=> "task_remove_old_reports.tpl"
        ));

        $this->tpl->display('tasks/task_remove_old_reports.tpl');
    }

    /**
    * Runs the particular maintenance task
    *
    * The second part of a maintenance task is the run
    * functionality. All tasks should provide their own
    * run function to be called by the MaintenanceAdmin
    * or themselves. Do not include code in your run
    * method that is declared in other functions because
    * only the task constructor will be called before the
    * run method by the MaintenanceAdmin
    *
    * @access public
    * @return bool true on success, false on failure
    */
    public function run() {
        $reports = null;

        if ($_POST)
            $reports = import_var('report', 'P');
        else {
            if ($handle = opendir($this->__get("report_dir"))) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        $tmp = explode('.', $file);

                        if ($tmp[1] == "png")
                            $reports[] = $file;
                    }
                }
                closedir($handle);
            }
        }

        if (!is_null($reports)) {
          foreach ($reports as $key => $file) {
              unlink($this->__get("report_dir").$file);
          }
        }

        return 1;
    }
}

?>
