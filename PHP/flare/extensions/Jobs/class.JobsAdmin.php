<?php
/**
* @package Jobs
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
* Jobs Administration tools
*
* The Jobs extension contains tools for
* controlling the flarecmd cron job that
* runs every couple of minutes. This
* class provides an interface for controlling
* jobs that are run.
*
* @package Jobs
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class JobsAdmin {
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
    * Path to where the jobs directory is located
    *
    * @access private
    * @var string
    */
    private $job_path;

    /**
    * List of all jobs that are currently installed
    *
    * @access private
    * @var array
    */
    private $installed_jobs;

    /**
    * List of all possible jobs in the jobs directory
    *
    * @access private
    * @var array
    */
    private $job_list;

    /**
    * Creates an instance of Jobs class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided.
    *
    * @access public
    */
    public function __construct() {
        $this->__set('job_path', ABSPATH."/extensions/Jobs/jobs/");
        $this->__set("installed_jobs", array());
        $this->__set("job_list", array());

        if ($this->cfg['job_path'] != '')
            $this->__set('job_path', $this->cfg['job_path']);
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
                "cmds" => "SELECT cmd_id, cmd FROM "._PREFIX."_cmd ORDER BY cmd ASC",
                "jobs" => "SELECT * FROM "._PREFIX."_jobs ORDER BY job ASC",
            )
        );
        $cmds = array();
        $jobs = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['cmds']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['jobs']);

        $stmt1->execute();
        $stmt2->execute();

        while ($row = $stmt1->fetch_array()) {
            $cmds[] = array(
                "cmd_id" => $row['cmd_id'],
                "cmd" => $row['cmd'],
            );
        }

        while ($row = $stmt2->fetch_array()) {
            $jobs[] = array(
                "job_id" => $row['job_id'],
                "job_name" => $row['job'],
                "last_run" => strftime("%m-%d-%Y", $row['last_run']),
                "interval" => strftime("%H:%M:%S", $row['interval'])
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            "_WITH_SELECTED"		=> _WITH_SELECTED,
            "_NO_ACTIONS"			=> _NO_ACTIONS,
            "_SETTINGS"			=> _SETTINGS));

        // Assign dynamic content
        $this->tpl->assign("JOB_LIST", $jobs);
        $this->tpl->assign("CMD_LIST", $cmds);

        // display the page
        $this->tpl->display("jobs_main.tpl");
    }

    /**
    * Discovers all jobs known and unknown
    *
    * Performs process of discovering all jobss and adding and removing
    * new and dead jobs.
    *
    * @access public
    */
    public function discover_jobs() {
        $this->get_installed_jobs();
        $this->get_all_known_jobs();

        /**
        * By this point we should have a complete list of all currently installed jobs
        * and a list of all known jobs ( files in the extensions/Jobs/jobs directory by default )
        *
        * We now need to run a comparison.
        * - Any jobs in the "all known" list that are not in the "current list" need to be added
        * - Any jobs in the "current list" that are not in the "all known list" need to be removed
        */
        $this->add_new_jobs();
        $this->remove_dead_jobs();
    }

    /**
    * Get all installed jobs
    *
    * Creates an array of all the currently installed jobs by fetching
    * the list of currently installed jobs from the database
    *
    * @access private
    */
    private function get_installed_jobs() {
        $sql = array (
            "mysql" => array (
                'jobs_installed'=> "SELECT `cmd` FROM `"._PREFIX."_cmd`",
            )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['jobs_installed']);
        $stmt->execute();

        while ($row = $stmt->fetch_array()) {
            $this->installed_jobs[] = $row["cmd"];
        }

        if (count($this->installed_jobs) > 0)
            sort($this->installed_jobs);
    }

    /**
    * Retrieves list of all known jobs
    *
    * By all known jobs I mean all jobs that exist in the jobs
    * directory. The database is updated via checking which jobs
    * exist in the jobs directory
    *
    * @access private
    */
        private function get_all_known_jobs() {
                /**
                * Variables $x and $y are only temp counting variables. As such, don't
                * spend time trying to understand what they are used for.
                */
                $handle = opendir($this->__get("job_path"));
                while ($x = readdir($handle)) {
            $fullpath = $this->__get("job_path") . "/$x";
                        if(!is_dir($fullpath) && $x != "." && $x != "..") {
                if (substr($x,0,1) != ".") {
                                    $this->job_list[] = $x;
                }
                        }
                }
                closedir($handle);
        sort($this->job_list);
        }

    /**
    * Adds new jobs
    *
    * Adds any new jobs found in the filesystem to the database. The
    * only place that will be search for jobs will be the location
    * stored in the class variable $job_path
    *
    * @access private
    */
    private function add_new_jobs() {
        /**
        * The job_list will always be >= the current_list.
        * This is why we loop for each job_list item
        * as opposed to looping for each installed_jobs item
        */
        $diff = array_diff($this->__get("job_list"), $this->__get("installed_jobs"));

        $sql = array(
            "mysql" => array(
                "installed" => "SELECT * FROM "._PREFIX."_cmd WHERE cmd=':1'",
                "install" => "INSERT INTO "._PREFIX."_cmd (`cmd`) VALUES (':1')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['installed']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['install']);

        // For each job we find, we'll need to do stuff
        foreach ($diff as $key => $job) {
            $script = $this->__get("job_path") . '/' . $job;

            $stmt1->execute($job);

            /**
            * We need to run the install method for the new job so that variables and
            * any other necessary stuff, as defined by the author, is run.
            */
            if (file_exists($script)) {
                if ($stmt1->num_rows() == 0) {
                    $stmt2->execute($job);
                }
            } else {
                //$log->log("NEW_EXT_NO_INSTALL_SCRIPT", $tsk);
            }
        }
    }

    /**
    * Removes dead jobs
    *
    * A dead job is basically a job that has been removed by the user
    * by having its job file deleted from the jobs folder. This method takes
    * care of clearing the database of these dead jobs.
    *
    * @access private
    */
    private function remove_dead_jobs() {
                /**
        * For this walk, we only care about the installed jobs.
                */
        $diff = array_diff($this->__get("installed_jobs"), $this->__get("job_list"));

        $sql = array (
            "mysql" => array (
                'delete_job' => "DELETE FROM `"._PREFIX."_cmd` WHERE `cmd`=':1'",
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['delete_job']);

        if (count($diff) > 0) {
            foreach ($diff as $key => $job) {
                $stmt1->execute($job);
                    }
        }
    }
}

?>
