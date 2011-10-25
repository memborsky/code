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

require(ABSPATH.'/extensions/ClamAV/class.ClamAVAdminResultsParser.php');

/**
* ClamAV administration tools
*
* This class provides methods for administering
* the ClamAV extension so that you can scan files
* and change available settings
*
* @package ClamAV
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class ClamAVAdmin {
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
    * Option string for the search
    *
    * @access private
    * @var string
    */
    private $clam_options;

    /**
    * Creates an instance of ClamAV class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided.
    *
    * @access public
    */
    public function __construct() {
        $this->__set("clam_options", " -i -r --no-mail --block-encrypted --block-max --max-recursion=5 --tempdir=/tmp");
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
    * Recursively lists a directory
    *
    * This method was taken from the link below and
    * modified for Flare
    * http://fundisom.com/phparadise/php/directories/recursive_directory_listing
    *
    * This method is different from the similar method
    * in the Filesystem extension because this will return
    * a full listing of all files in all directories.
    * Plus, this method has less fluff.
    * Finally, this method stores all its output in XML
    * format for use by dhtmlxTree later
    *
    * @access public
    * @param string $dir Current directory to list contents of
    * @param string $data Reference to the main data string. All XML is stored in this
    */
    public function recur_dir($dir, &$data) {
        $dirlist = opendir($dir);
        while ($file = readdir ($dirlist)) {
            if ($file != '.' && $file != '..') {
                $newpath = $dir.'/'.$file;
                $level = explode('/',$newpath);
                if (is_dir($newpath)) {
                    $data .= "<item id='".$newpath."' text='". end($level)."'>";
                    $this->recur_dir($newpath, $data);
                    $data .= "</item>";
                } else {
                    $data .= "<item id='".$newpath."' text='". end($level)."'></item>";
                }
            }
        }

        closedir($dirlist);
    }

    /**
    * Wrapper for recur_dir
    *
    * This method wraps the above method and adds
    * special XML that is needed to start and end
    * the root XML tags. It also escapes all the
    * slashes before it returns its data
    *
    * @access public
    * @return string Full XML string ready to be used by dhtmlxTree
    */
    public function treeview() {
        $header = "<?xml version='1.0'?><tree id='0'>";

        $data = '';
        $this->recur_dir($this->cfg['scan_root'], $data);

        $footer = "</tree>";

        return addslashes($header . $data . $footer);
    }

    /**
    * Schedules an antivirus scan to run
    *
    * This method will schedule a Clam Antivirus job to
    * run, using Flare's built in job scheduler. The output
    * from the scan will be parsed and added back to a
    * report that the admin can read and take action on
    * whenever they need to.
    *
    * @access public
    * @param array $items List of all items to scan
    */
    public function do_schedule_clamscan($items) {
        $sql = array(
            "mysql" => array(
                "job" => "INSERT INTO "._PREFIX."_clamscans(`name`,`scan_cmd`,`status`) VALUES (':1',':2','P');"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['job']);

        // Strip list of duplicate items
        $items = array_unique($items);

        $cmd = $this->cfg['clamscan_bin'] . $this->__get("clam_options");

        foreach ($items as $key => $val) {
            $val = str_replace(" ", "\\\ ", $val);

            $cmd .= " ".$val;
        }

        $name = strftime("%d-%m-%Y at %r", time());

        $stmt1->execute($name,$cmd);
    }

    /**
    * Displays the listed configuration for this extension
    *
    * Will display any configuration settings stored in the
    * _config table that specifically relate to this extension
    *
    * @access public
    * @return bool Always returns true
    */
    public function show_settings() {
        $config = array();
        $sql = array (
            'mysql' => array (
                'config' => "SELECT * FROM "._PREFIX."_config WHERE extension_id=':1'"
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);

        $stmt1->execute($this->ext->__get('extension_id'));

        while ($row = $stmt1->fetch_array()) {
            $config[$row['name']] = array (
                'name' 	=> $row['name'],
                'desc' 	=> $row['description'],
                'value'	=> $row['value']
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_YES'			=> _YES,
            '_NO'			=> _NO,
            '_UNDO_CHGS'		=> _UNDO_CHGS,
            '_SAVE_CHGS'		=> _SAVE_CHGS,
            '_SETTINGS'		=> _SETTINGS,
            '_CLAM_SCANS'		=> _CLAM_SCANS,
            '_CLAM_SCHEDULE_SCAN'	=> _CLAM_SCHEDULE_SCAN
        ));

        // Assign dynamic content
        $this->tpl->assign('CONFIG', $config);

        // Display the page
        $this->tpl->display('clamav_config.tpl');

        return true;
    }

    /**
    * Show a scan page summary
    *
    * Displays the main page for the ClamAV
    * extension. On this page are listed all the
    * scheduled and run scans and links to view
    * their results.
    *
    * @access public
    */
    public function show_summary() {
        $sql = array (
            "mysql" => array (
                "scans" => "SELECT scan_id, name, status FROM "._PREFIX."_clamscans ORDER BY scan_id DESC"
            )
        );
        $scans = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['scans']);

        $stmt1->execute();

        while ($row = $stmt1->fetch_array()) {
            $scans[] = array(
                "scan_id"	=> $row['scan_id'],
                "name" 		=> $row['name'],
                "status"	=> $row['status']
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_SETTINGS'			=> _SETTINGS,
            '_CLAM_SCANS'			=> _CLAM_SCANS,
            '_CLAM_SCHEDULE_SCAN'		=> _CLAM_SCHEDULE_SCAN,
            '_CLAM_DELETE_RESULTS'		=> _CLAM_DELETE_RESULTS,
            '_CLAM_RESCHEDULE_SCAN'		=> _CLAM_RESCHEDULE_SCAN,
            '_CLAM_DATE_SCHEDULED'		=> _CLAM_DATE_SCHEDULED,
            '_CLAM_SCAN_STATUS'		=> _CLAM_SCAN_STATUS,
            '_CLAM_SCAN_RESULTS'		=> _CLAM_SCAN_RESULTS,
            '_CLAM_PENDING'			=> _CLAM_PENDING,
            '_CLAM_RUNNING'			=> _CLAM_RUNNING,
            '_CLAM_FINISHED'		=> _CLAM_FINISHED,
            '_CLAM_VIEW_RESULTS'		=> _CLAM_VIEW_RESULTS,
            '_CLAM_NO_SCANS_SCHEDULED'	=> _CLAM_NO_SCANS_SCHEDULED,
            '_WITH_SELECTED'		=> _WITH_SELECTED,
            '_NO_ACTIONS'			=> _NO_ACTIONS,
            '_SETTINGS'			=> _SETTINGS));

        // Assign dynamic content
        $this->tpl->assign(array(
            "SCAN_LIST"	=> $scans,
            "JS_INC"	=> "clamav_main.tpl"
        ));

        // display the page
        $this->tpl->display("clamav_main.tpl");
    }

    /**
    * Check to see if result are available
    *
    * This method will specify whether the antivirus
    * scan has returned a result set or not yet
    *
    * @access public
    * @param integer $scan_id ID of the scan to check results for
    */
    public function check_results_ready($scan_id) {
        $sql = array(
            "mysql" => array(
                "finished" => "SELECT scan_id FROM "._PREFIX."_clamscans WHERE scan_id=':1' AND status='F'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['finished']);

        $stmt1->execute($scan_id);

        if ($stmt1->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
    * Displays results of a scan
    *
    * This will parse the resulting XML generated by
    * the flareclam script and will display the results
    * in an easy to read layout.
    *
    * @access public
    * @param integer $scan_id ID of the scan to get results for
    */
    public function show_clamscan_results($scan_id) {
        $sql = array(
            "mysql" => array(
                'get_results' => "SELECT results FROM "._PREFIX."_clamresults WHERE scan_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['get_results']);
        $stmt1->execute($scan_id);

        $xml = $stmt1->result(0);

        $p = &new ClamAVAdminResultsParser();
        $p->__set("tpl", $this->__get("tpl"));

        $result = $p->setInputString($xml);
        $result = $p->parse();

        $tpl = $p->__get("tpl");

        $tpl->assign(array(
            'INFECTED_LIST'		=> $p->__get("infected_list"),
            '_SETTINGS'		=> _SETTINGS,
            '_CLAM_SCANS'		=> _CLAM_SCANS,
            '_CLAM_SCAN_SUMMARY'	=> _CLAM_SCAN_SUMMARY,
            '_CLAM_KNOWN_VIRUSES'	=> _CLAM_KNOWN_VIRUSES,
            '_CLAM_ENGINE_VERSION'	=> _CLAM_ENGINE_VERSION,
            '_CLAM_SCAN_DIRS'	=> _CLAM_SCAN_DIRS,
            '_CLAM_NONE_INFECTED'	=> _CLAM_NONE_INFECTED,
            '_CLAM_SCANNED_FILES'	=> _CLAM_SCANNED_FILES,
            '_CLAM_INFECTED_FILES'	=> _CLAM_INFECTED_FILES,
            '_CLAM_DATA_SCANNED'	=> _CLAM_DATA_SCANNED,
            '_CLAM_TIME'		=> _CLAM_TIME,
            '_CLAM_INFECTED_LIST'	=> _CLAM_INFECTED_LIST,
            '_CLAM_SCHEDULE_SCAN'	=> _CLAM_SCHEDULE_SCAN
        ));

        $tpl->display('clamav_scan_results.tpl');
    }

    /**
    * Displays details of a scan
    *
    * This will show the admin the full details of the
    * scan that is scheduled to be run.
    *
    * @access public
    * @param integer $scan_id ID of the scan to view details of
    */
    public function show_clamscan_details($scan_id) {
        $sql = array(
            "mysql" => array(
                'details' => "SELECT * FROM "._PREFIX."_clamscans WHERE scan_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['details']);

        $stmt1->execute($scan_id);

        $row = $stmt1->fetch_assoc();

        // Assign language constants
        $this->tpl->assign(array(
            '_SETTINGS'		=> _SETTINGS,
            '_CLAM_SCANS'		=> _CLAM_SCANS,
            '_CLAM_SCAN_DETAILS'	=> _CLAM_SCAN_DETAILS,
            '_CLAM_SCAN_NAME'	=> _CLAM_SCAN_NAME,
            '_CLAM_PENDING'		=> _CLAM_PENDING,
            '_CLAM_RUNNING'		=> _CLAM_RUNNING,
            '_CLAM_FINISHED'	=> _CLAM_FINISHED,
            '_CLAM_SCAN_STATUS'	=> _CLAM_SCAN_STATUS,
            '_CLAM_COMMAND_USED'	=> _CLAM_COMMAND_USED,
            '_CLAM_SCHEDULE_SCAN'	=> _CLAM_SCHEDULE_SCAN
        ));

        // Assign dynamic content
        $this->tpl->assign(array(
            'SCAN_NAME'	=> $row['name'],
            'SCAN_CMD'	=> $row['scan_cmd'],
            'SCAN_STATUS'	=> $row['status']
        ));

        $this->tpl->display('clamav_scan_details.tpl');
    }

    /**
    * Deletes a clamscan and results
    *
    * If a schedule has yet to be run, or even in the schedule has
    * already ran, this method will remove the schedule from the
    * clamscan table, and also remove any result contents that are
    * associated with the scan
    *
    * @access public
    * @param integer $scan_id ID of the scheduled scan to remove
    */
    public function do_delete_scan($scan_id) {
        $sql = array(
            "mysql" => array(
                "delete_scan" => "DELETE FROM "._PREFIX."_clamscans WHERE scan_id=':1'",
                "select" => "SELECT scan_id FROM "._PREFIX."_clamresults WHERE scan_id=':1'",
                "delete_result" => "DELETE FROM "._PREFIX."_clamresults WHERE scan_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["delete_scan"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["delete_results"]);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]["select"]);

        $stmt1->execute($scan_id);
        $stmt3->execute($scan_id);

        if ($stmt3->num_rows() > 0)
            $stmt2->execute($scan_id);
    }

    /**
    * Reschedules a scan
    *
    * Rescheduling a scan will update its name in the clamscans
    * table as well as remove any existing report that is associated
    * with a previous scan
    *
    * @access public
    * @param integer $scan_id ID of the scan to reschedule
    */
    public function do_reschedule_scan($scan_id) {
        $time = time();

        $sql = array(
            "mysql" => array(
                "reschedule" => "UPDATE "._PREFIX."_clamscans SET name=':1', status='P' WHERE scan_id=':2'",
                "select" => "SELECT scan_id FROM "._PREFIX."_clamresults WHERE scan_id=':1'",
                "delete_result" => "DELETE FROM "._PREFIX."_clamresults WHERE scan_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['reschedule']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['delete_result']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]["select"]);

        while(true) {
            if (time() > $time+1)
                break;
        }

        $name = strftime("%d-%m-%Y at %r", time());

        $stmt1->execute($name,$scan_id);
        $stmt3->execute($scan_id);

        if ($stmt3->num_rows() > 0)
            $stmt2->execute($scan_id);
    }

    /**
    * Saves extension configuration back to database
    *
    * After making changes to the running configuration of the
    * extension, the settings must be saved back to the database.
    * This method takes care of that process.
    *
    * @access public
    * @param array $settings Settings to be saved to configuration table
    */
    public function do_save_settings($settings) {
        $sql = array (
            'mysql' => array (
                "config" => "UPDATE "._PREFIX."_config SET value=':1' WHERE name=':2' AND extension_id=':3'"
            )
        );

        // Prepare SQL for running
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);

        // For each setting name->value pair we received...
        foreach($settings as $key => $val) {
            // Execute the SQL to update that value
            $stmt1->execute($val,$key,$this->ext->__get('extension_id'));
        }

        // Assign language constants
        $this->tpl->assign('_MESSAGE', _AUTH_SETTINGS_SAVED);
        $this->tpl->assign('_RETURN_LINK', _AUTH_SETTINGS_SAVED_RETURN_LINK);

        // Display the page
        $this->tpl->display('actions_done.tpl');
    }

    /**
    * Changes an extensions visibility
    *
    * This method will switch the visibility of the
    * extensions link when viewed by normal users
    *
    * @access public
    * @param integer $visible Value to change visibility to
    */
    public function do_change_visibility($visible) {
        $sql = array(
            "mysql" => array(
                "visible" => "UPDATE "._PREFIX."_extensions SET visible=':1' WHERE extension_id=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["visible"]);
        $stmt1->execute($visible, $this->ext->__get("extension_id"));
    }

}

?>
