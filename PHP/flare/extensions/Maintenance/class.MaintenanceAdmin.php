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

/**
* Maintenance Administration tools
*
* Contains maintenance administration tools
* for use by the admin to maintain a Flare
* installation
*
* @package Maintenance
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class MaintenanceAdmin {
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
    * Logging object used to log actions to the database
    *
    * @access public
    * @var object
    */
    public $log;

    /**
    * Contains all settings stored in the database that relate to this extension
    *
    * @access public
    * @var object
    */
    public $cfg;

    /**
    * Contains extension specific properties not available in the Configuration object
    *
    * @access public
    * @var object
    */
    public $ext;

    /**
    * Path relative to the master admin.php file, to where the tasks directory is located
    *
    * @access private
    * @var string
    */
    private $task_path;

    /**
    * List of all tasks that are currently installed
    *
    * @access private
    * @var array
    */
    private $installed_tasks;

    /**
    * List of all possible tasks in the tasks directory
    *
    * @access private
    * @var array
    */
    private $task_list;

    /**
    * Creates an instance of Maintenance class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * it sets several class path variables to default values.
    *
    * @access public
    */
    public function __construct() {
        $this->__set("task_path", "");
        $this->__set("installed_tasks", array());
        $this->__set("task_list", array());
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
    * Show a task page summary
    *
    * Displays the main page for the Maintenance
    * extension. On this page are listed all the
    * installed tasks and links to view them and
    * run them.
    *
    * @access public
    */
    public function show_summary() {
        $sql = array (
            "mysql" => array (
                "tasks" => "SELECT task_id, name, description FROM "._PREFIX."_maintenance_tasks ORDER BY name ASC",
            )
        );
        $tasks = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['tasks']);

        $stmt1->execute();

        while ($row = $stmt1->fetch_array()) {
            $tasks[] = array(
                "task_id" => $row['task_id'],
                "name" => $row['name'],
                "description" => $row['description']
            );
        }

        if ($this->cfg['maintenance_mode'] < time())
            $mmode = 0;
        else
            $mmode = 1;

        // Assign language constants
        $this->tpl->assign(array(
            "_WITH_SELECTED"		=> _WITH_SELECTED,
            "_NO_ACTIONS"			=> _NO_ACTIONS,
            "_SETTINGS"			=> _SETTINGS,
            "MAINTENANCE_MODE"		=> $mmode,
            "JS_INC"			=> "maintenance_main.tpl"));

        // Assign dynamic content
        $this->tpl->assign("TASK_LIST", $tasks);

        // display the page
        $this->tpl->display("maintenance_main.tpl");
    }

    /**
    * Discovers all tasks known and unknown
    *
    * Performs process of discovering all tasks and adding and removing
    * new and dead tasks.
    *
    * @access public
    */
    public function discover_tasks() {
        $this->get_installed_tasks();
        $this->get_all_known_tasks();

        /**
        * By this point we should have a complete list of all currently installed tasks
        * and a list of all known tasks ( task.*.php files in the extensions/Maintenance/tasks directory )
        *
        * We now need to run a comparison.
        * - Any tasks in the "all known" list that are not in the "current list" need to be added
        * - Any tasks in the "current list" that are not in the "all known list" need to be removed
        */
        $this->add_new_tasks();
        $this->remove_dead_tasks();
    }

    /**
    * Get all installed tasks
    *
    * Creates an array of all the currently installed tasks by fetching
    * the list of currently installed tasks from the database
    *
    * @access private
    */
    private function get_installed_tasks() {
        $sql = array (
            "mysql" => array (
                'tasks_installed'=> "SELECT `name` FROM `"._PREFIX."_maintenance_tasks`",
            )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['tasks_installed']);
        $stmt->execute();

        while ($row = $stmt->fetch_array()) {
            $this->installed_tasks[] = $row["name"];
        }

        sort($this->installed_tasks);
    }

    /**
    * Retrieves list of all known tasks
    *
    * By all known tasks I mean all tasks that exist in the tasks
    * directory. Since this is the only place where tasks can be put, there
    * will always potentially be more tasks here than in the database. The
    * database is updated via checking which tasks exist in the tasks
    * directory
    *
    * @access private
    */
        private function get_all_known_tasks() {
                /**
                * Variables $x and $y are only temp counting variables. As such, don't
                * spend time trying to understand what they are used for.
                */
                $handle = opendir($this->__get("task_path"));
                while ($x = readdir($handle)) {
            $fullpath = $this->__get("task_path") . "/$x";
                        if(!is_dir($fullpath) && $x != "." && $x != "..") {
                if (substr($x,0,1) != ".") {
                    $temp = explode(".", $x);
                                    $this->task_list[] = $temp[1];
                }
                        }
                }
                closedir($handle);
        sort($this->task_list);
        }

    /**
    * Adds new tasks
    *
    * Adds any new tasks found in the filesystem to the database. The
    * only place that will be search for tasks will be the location
    * stored in the class variable $task_path
    *
    * @access private
    */
    private function add_new_tasks() {
        /**
        * The task_list will always be >= the current_list.
        * This is why we loop for each task_list item
        * as opposed to looping for each installed_tasks item
        */
        $diff = array_diff($this->__get("task_list"), $this->__get("installed_tasks"));

        $sql = array(
            "mysql" => array(
                "installed" => "SELECT * FROM "._PREFIX."_maintenance_tasks WHERE name=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['installed']);

        // For each task we find, we'll need to do stuff
        foreach ($diff as $key => $task) {
            $task_file = "task." . $task . ".php";

            /**
            * We need to run the install method for the new task so that variables and
            * any other necessary stuff, as defined by the author, is run.
            */
            $install_script = $this->__get("task_path") . $task_file;

            $stmt1->execute($task);

            if (is_dir($install_script))
                continue;

            if (file_exists($install_script)) {
                if ($stmt1->num_rows() == 0) {
                    require ($install_script);

                    $tsk = new $task;

                    $tsk->__set("db",$this->__get("db"));
                    $tsk->__set("tpl",$this->__get("tpl"));
                    $tsk->__set("log",$this->__get("log"));
                    $tsk->__set("cfg",$this->__get("cfg"));
                    $tsk->__set("ext",$this->__get("ext"));

                    $tsk->install();
                    unset($tsk);
                }
            } else {
                //$log->log("NEW_EXT_NO_INSTALL_SCRIPT", $tsk);
            }
        }
    }

    /**
    * Removes dead tasks
    *
    * A dead task is basically a task that has been removed by the user
    * by having its task file deleted from the tasks folder. This method takes
    * care of clearing the database of these dead tasks.
    *
    * @access private
    */
    private function remove_dead_tasks() {
                /**
        * For this walk, we only care about the installed tasks.
                */
        $diff = array_diff($this->__get("installed_tasks"), $this->__get("task_list"));

        $sql = array (
            "mysql" => array (
                'delete_task' => "DELETE FROM `"._PREFIX."_maintenance_tasks` WHERE `name`=':1'",
            )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['delete_task']);

        if (count($diff) > 0) {
            foreach ($diff as $key => $task) {
                $stmt->execute($task);
                    }
        }
    }

    /**
    * Get all info about task
    *
    * All available information in the maintenance_tasks table will be retrieved
    * about the current task. This can be used later to query the database using
    * your tasks specific information
    *
    * @access public
    * @param string $task_id ID of the extension that you want to get
    * @return array The configuration information for this task
    */
    public function task_info($task_id) {
        $sql = array (
            "mysql" => array (
                'config'=> "SELECT * FROM `"._PREFIX."_maintenance_tasks` WHERE task_id=':1'",
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);
        $stmt1->execute($task_id);

        $row = $stmt1->fetch_array();

        $tcfg = array(
            "task_id" 	=> $row['task_id'],
            "task_name"	=> $row['name'],
            "task_desc"	=> $row['description'],
            "last_run"	=> $row['last_ran'],
            "last_status"	=> $row['last_status']
        );

        return $tcfg;
    }

    /**
    * Get name of task
    *
    * Given a provided task ID, this method will return
    * the name of the task, as stored in the database
    *
    * @access public
    * @param integer $task_id ID of the task whose name is to be returned
    * @return string The name of the task with the associated ID
    */
    public function get_task_name($task_id) {
        $sql = array(
            "mysql" => array(
                'name' => "SELECT name FROM "._PREFIX."_maintenance_tasks WHERE task_id=':1' LIMIT 1"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['name']);
        $stmt1->execute($task_id);

        return $stmt1->result(0);
    }

    /**
    *
    */
    public function update_last_run($task_id, $status) {
        $last_run = time();
        $sql = array (
            "mysql" => array (
                'update_run' => "UPDATE "._PREFIX."_maintenance_tasks SET last_run=':1', last_status=':2' WHERE task_id=':3'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['update_run']);

        $stmt1->execute($last_run, $status, $task_id);
    }

    /**
    * Activates Maintenance Mode
    *
    * Maintenance Mode prevents normal users from seeing
    * the Flare pages. Instead, all requests are redirected
    * to a special maintenance page until the time limit
    * expires or the admin turns maintenance mode off.
    *
    * @access public
    */
    public function do_maintenance_mode($status = "on") {
        $sql = array(
            "mysql" => array(
                "mode" => "UPDATE "._PREFIX."_config SET value=':1' WHERE name='maintenance_mode'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['mode']);

        if ($status == "on") {
            $time = time();
            $time += 7200;
            $stmt1->execute($time);

            $this->tpl->assign(array(
                "_MESSAGE" 	=> _MAINTENANCE_ACTIVATE,
                "_RETURN_LINK"	=> _MAINTENANCE_RETURN_LINK
            ));

            $this->tpl->display('actions_done.tpl');
        } else {
            $stmt1->execute(0);

            $this->tpl->assign(array(
                "_MESSAGE" 	=> _MAINTENANCE_DEACTIVATE,
                "_RETURN_LINK"	=> _MAINTENANCE_RETURN_LINK
            ));

            $this->tpl->display('actions_done.tpl');
        }
    }
}

?>
