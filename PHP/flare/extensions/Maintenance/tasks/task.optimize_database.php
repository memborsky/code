<?php
/**
* @package Maintenance
* @author Tim Rupp <tarupp01@indianatech.net>
* @author Matt Emborsky <mlemborsky01@indianatech.net>
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
* @author Matt Emborsky <mlemborsky01@indianatech.net>
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class optimize_database {
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
    * Holds the actual data from our query.
    *
    * @access private
    * @var array
    */
    private $data;

    /**
    * Holds the size of the database in terms of number of tables.
    *
    * @access private
    * @var integer
    */
    private $size;

    /**
    * Holds the table which we make in create_table.
    *
    * @access private
    * @var array
    */
    private $table;

    /**
    * Total size of the database
    *
    * @access private
    * @var integer
    */
    private $total_size;

    /**
    * Total overhead in the database
    *
    * @access private
    * @var integer
    */
    private $total_overhead;

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
        // Set the name of the report according to filename
        $this->__set("task_id", $task_id);

        // Set the name of the report according to filename
        $this->__set("task_name", "optimize_database");

        // Set up description
        $this->__set("task_desc", "Optimizes all tables in Flare database");
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
        $sql =	Array (
            "mysql" => Array (
                "status" => "SHOW TABLE STATUS",
            )
        );

        /**
        * Run the optimize code if the submit button was pressed
        */
        if ($_POST) {
            $this->run();
        }

        /*
        * Setup our query for execution and actually execute it.
        */
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['status']);
        $stmt1->execute();

        /*
        * Rearrange our results to be an array of an array with the following items in
        * the inner array.
        *
        * table_name = Name of the table.
        * size = Size of the data in the table.
        * overhead = Size of overhead on the table.
        * index_size = Size of the fields of the table.
        */
        $i = 0;
        while ($result = $stmt1->fetch_assoc()) {
            $data[$i]['table_name']	= $result['Name'];
            $data[$i]['size'] 	= $result['Data_length'];
            $data[$i]['overhead'] 	= $result['Data_free'];
            $data[$i]['index_size'] = $result['Index_length'];
            $i++;
        }

        $this->create_table($data, sizeof($data));

        /*
        * Prep the template object for display by assigning our compiled array
        * of information to the object.
        */
        $this->tpl->assign(array(
            'TASK_ID'		=> $this->cfg["task_id"],
            'OPTIMIZE_TABLE'	=> $this->__get("table"),
            'TOTAL_SIZE'		=> $this->__get("total_size"),
            'TOTAL_OVERHEAD'	=> $this->__get("total_overhead")
        ));

        $this->tpl->display('tasks/task_optimize_table.tpl');
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
        $sql = array (
            "mysql" => array (
                "status" => "SHOW TABLE STATUS",
                "table_list" => "SHOW TABLES",
                "optimize_table" => "OPTIMIZE TABLE :1"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['table_list']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['optimize_table']);

        $stmt1->execute();

        while ($result = $stmt1->fetch_assoc()) {
            $table_names[] = $result['Tables_in_' . _DBUSE];
        }

        foreach ($table_names as $table_name) {
            $stmt2->execute($table_name);
        }

        return true;
    }

    /*
    * Create an array of our table so that we can send it through to our template
    * object. This should be easily adaptable to almost any size of array we send
    * in.
    *
    * @access public
    * @param array $data Two dimensional array that holds the results we already obtained.
    * @param integer $size_db Number of tables that we are going to be parsing.
    */
    public function create_table($data, $size_db) {
        require_once ('extensions/Filesystem/class.Filesystem.php');

        $table 	= array();
        $i	= $total_size = $total_overhead = 0;

        /*
        * As long as we are going to be building our table, we might as well,
        * calculate the running total of both the size of the tables and the
        * space we will be saving from the overhead on each table. We also
        * will be using a previously written method called format_space which
        * will convert our size and overhead values to readable form. That is
        * from bytes to kilobytes, megabytes, and gigabytes, where possible,
        * otherwise will just be getting bytes returned. We store this
        * information into our already built array containing all template
        * information for output.
        */
        for ($i; $i < $size_db; $i++) {
            $total_size	+= ($data[$i]['size'] + $data[$i]['index_size']);
            $total_overhead += $data[$i]['overhead'];

            $table[] = Array (
                'table_name'	=> $data[$i]['table_name'],
                'size'		=> Filesystem::format_space(($data[$i]['size'] + $data[$i]['index_size'])),
                'overhead'	=> Filesystem::format_space($data[$i]['overhead'])
            );
        }

        /*
        * We will now add the total values just totaled to our template array
        * so we can have it appended to the end of the table. The totals also
        * use the previously discussed method, format_space, which will return
        * our total size in a more readable format.
        */
        $this->__set('total_size', Filesystem::format_space($total_size));
        $this->__set('total_overhead', Filesystem::format_space($total_overhead));

        $this->__set("table", $table);
    }
}

?>
