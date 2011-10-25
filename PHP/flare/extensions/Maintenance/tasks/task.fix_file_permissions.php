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
* Fix the Flare file permissions
*
* This will fix file permissions. This means removing
* permissions for files that dont exist. Also, it means
* adding new permissions for files that somehow dont
* have any associated with them.
*
* @package Maintenance
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class fix_file_permissions {
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
    * Contains a list of all dead permissions
    *
    * @access private
    * @var array
    */
    private $dead_permissions;

    /**
    * Contains a list of all missing file permissions
    *
    * @access private
    * @var array
    */
    private $missing_file_permissions;

    /**
    * Contains a list of all missing directory permissions
    *
    * @access private
    * @var array
    */
    private $missing_dir_permissions;

    /**
    * Holds the base user home directory to scan
    *
    * @access private
    * @var string
    */
    private $user_dir;

    /**
    * Holds the base group directory to scan
    *
    * @access private
    * @var string
    */
    private $group_dir;

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
        $this->__set("task_name", "fix_file_permissions");

        // Set up description
        $this->__set("task_desc", "Fixes broken, missing, or outdated file permissions");

        $this->__set("user_dir", "/mnt/fuel/burning-edge/home/");
        $this->__set("group_dir", "/mnt/fuel/burning-edge/group/");

        $this->__set("dead_permissions", array());
        $this->__set("missing_file_permissions", array());
        $this->__set("missing_dir_permissions", array());
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
        clearstatcache();

        $this->find_dead_permissions();
        $this->find_missing_permissions("file");
        $this->find_missing_permissions("dir");
        $this->find_no_permissions();

        $this->tpl->assign(array(
            'dead'		=> $this->__get("dead_permissions"),
            'missing_file'	=> $this->__get("missing_file_permissions"),
            'missing_dir'	=> $this->__get("missing_dir_permissions"),
            'cdead'		=> count($this->__get("dead_permissions")),
            'cmissing_file'	=> count($this->__get("missing_file_permissions")),
            'cmissing_dir'	=> count($this->__get("missing_dir_permissions")),
            'JS_INC'	=> "task_fix_file_permissions.tpl",
            'TASK_ID'	=> $this->cfg['task_id']
        ));

        $this->tpl->display("tasks/task_fix_file_permissions.tpl");
    }

    /**
    * Finds all dead permissions
    *
    * Dead permissions are those where the permission
    * exists in the database, but the file or folder
    * does not exist on disk.
    */
    public function find_dead_permissions() {
        $dead = array();
        $sql =	Array (
            "mysql" => Array (
                "select_user" => "SELECT file_id,file FROM "._PREFIX."_file_permissions WHERE owner_id != '-' ORDER BY owner_id ASC, file ASC",
                "select_group" => "SELECT file_id,file FROM "._PREFIX."_file_permissions WHERE group_id != '-' ORDER BY group_id ASC, file ASC",
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select_user']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['select_group']);

        $stmt1->execute();
        $stmt2->execute();

        while ($row = $stmt1->fetch_assoc()) {
            if (file_exists($row['file']))
                continue;
            else
                array_push($dead, array('id' => $row['file_id'], 'file' => $row['file']));
        }

        while ($row = $stmt2->fetch_assoc()) {
            if (file_exists($row['file']))
                continue;
            else
                array_push($dead, array('id' => $row['file_id'], 'file' => $row['file']));
        }

        $this->__set("dead_permissions", $dead);
    }

    /**
    * Finds all missing permissions
    *
    * Missing permissions are those where the file
    * exists on disk, but there is no permission
    * and associated owner or group for that file
    * or folder in the file_permissions table
    */
    public function find_missing_permissions($type = "file") {
        $items 		= array();
        $missing	= array();
        $sql = array(
            "mysql" => array(
                "select" => "SELECT file FROM "._PREFIX."_file_permissions WHERE file=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select']);

        if ($type == "file") {
            $user 	= $this->ls_dir($this->user_dir, "file");
            $group	= $this->ls_dir($this->group_dir, "file");
            $items 	= array_merge($user,$group);

            foreach ($items as $key => $val) {
                $file = $this->normalize_file($val);

                $stmt1->execute($file);

                if ($stmt1->num_rows() == 0)
                    array_push($missing, array('file' => $file));
            }

            $this->__set("missing_file_permissions", $missing);
        } else {
            $user 	= $this->ls_dir($this->user_dir, "dir");
            $group	= $this->ls_dir($this->group_dir, "dir");
            $items	= array_merge($user,$group);

            foreach ($items as $key => $val) {
                $dir = $this->normalize_dir($val);

                $stmt1->execute($dir);

                if ($stmt1->num_rows() == 0)
                    array_push($missing, array('dir' => $dir));
            }

            $this->__set("missing_dir_permissions", $missing);
        }
    }

    /**
    * Finds directories that dont have the execute bit set
    *
    * If a directory has the execute bit not set
    * then users arent able to step into the folder.
    * While this may be something the admin intended
    * to do, it may also be caused by a bug in Flare
    * or something else. Therefore its considered
    * incorrect to have in the database
    */
    public function find_non_exec_directories() {

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
                "rm_dead" => "DELETE FROM "._PREFIX."_file_permissions WHERE file_id=':1'",
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['rm_dead']);

        $type = import_var('type', 'P');

        switch($type) {
            case "dead":
                $dead = import_var('dead', 'P');

                foreach ($dead as $key => $val) {
                    $stmt1->execute($val);
                }

                break;
            case "missing_file":
                $mf = import_var('missing_file', 'P');
                break;
            case "missing_dir":
                $md = import_var('missing_dir', 'P');
                break;
        }

        return true;
    }

    /**
    *
    */
    public function ls_dir($fullpath, $type = "file") {
        $temp		= array();
        $files		= array();
        $dirs		= array();

        // Clear any info about the files or directories that PHP may have cached
        clearstatcache();
        // If the path sent is a directory...
        if (is_dir($fullpath)) {
            // ...check to see if we can open it. If yes, store its resource in a variable
            if ($handle = opendir($fullpath)) {
                // If we're capable of opening the directory, reading files in one at a time until no more
                while (false !== ($file = readdir($handle))) {
                    // Check to see if the filename is either the current dir, or the parent dir.
                    // and skip it if it is.
                    if ($file != "." && $file != "..") {
                        // If the read-in filename is a directory...
                        if ($type == "dir") {
                            if (is_dir($fullpath . "/" . $file)) {
                                $temp[] = $fullpath . '/' . $file . '/';
                                    $temp = array_merge($temp, $this->ls_dir($fullpath . '/' . $file . '/', $type));
                            }
                            } else {
                            if (is_dir($fullpath . "/" . $file))
                                    $temp = array_merge($temp, $this->ls_dir($fullpath . '/' . $file . '/', $type));
                            else
                                    $temp[] = $fullpath . '/' . $file;
                        }
                    }
                }
            }
            // Close the directory we were working with
            closedir($handle);
        }

        // Sort the arrays
        sort($temp);

        return $temp;
    }

    /**
    * Normalizes a directory
    *
    * We define a normalized directory as being a path
    * with only a single forward slash seperating directories
    * and having a forward slash at the end of the string
    *
    * @access public
    * @param string $dir Path to be normalized
    * @example /this/is/a/path/ Example result of a normalized path
    * @return string Normalized path
    */
    private function normalize_dir($dir) {
        if (substr($dir, 0, 1) != '/')
            $dir = '/' . $dir;

        if (substr($dir, -1, 1) != '/')
            $dir .= '/';

        return preg_replace("/\/+/", "/", $dir);
    }

    /**
    * Normalizes a file
    *
    * We define a normalized file as being a path
    * with only a single forward slash seperating
    * directories and having no forward slash at
    * the end of the string
    *
    * @access public
    * @param string $file File to be normalized
    * @example /this/is/a/file Example result of a normalized file
    * @return string Normalized path
    */
    private function normalize_file($file) {
        if (substr($file, 0, 1) != '/')
            $file = '/' . $file;

        if (substr($file, -1, 1) == '/')
            $file = substr($file, 0, -1);

        return preg_replace("/\/+/", "/", $file);
    }
}

?>
