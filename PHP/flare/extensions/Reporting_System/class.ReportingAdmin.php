<?php
/**
* @package Reporting_System
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
* Reporting administration tools
*
* Contains tools to managing and viewing reports
* as well as discovering new reports and adding
* them to the system so that they can be viewed
*
* @package Reporting_System
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class ReportingAdmin {
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
    * Path relative to the master admin.php file, to where the reports directory is located
    *
    * @access private
    * @var string
    */
    private $report_path;

    /**
    * List of all reports that are currently installed
    *
    * @access private
    * @var array
    */
    private $installed_reports;

    /**
    * List of all possible reports in the reports directory
    *
    * @access private
    * @var array
    */
    private $report_list;

    /**
    * Creates an instance of ReportingAdmin class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided. This constructor begins by setting
    * the error var to FALSE.
    *
    * @access public
    */
    public function __construct() {
        $this->__set("report_path", "");
        $this->__set("installed_reports", array());
        $this->__set("report_list", array());
    }

    /**
    * Generic getter method
    *
    * Gets the value of any class variable using the supplied variable name
    *
    * @access public
    * @param string $key Name of the variable whose value you wish to get
    * @return mixed|NULL The value of the named variable
    */
    public function __get( $key ) {
        return isset( $this->$key ) ? $this->$key : NULL;
    }

    /**
    * Generic setter method
    *
    * Sets the value of any given variable name to any given value
    *
    * @access public
    * @param string $key Name of the variable whose value you wish to set
    * @param mixed $value Value which you wish to set to variable
    */
    public function __set( $key, $value ) {
        $this->$key = $value;
    }

    /**
    * Show a reporting page summary
    *
    * Displays the main page for the Reporting_System
    * extension. On this page are listed all the system
    * messages that the admin has created, as well as
    * all the installed reports and links to view them.
    *
    * @access public
    */
    public function show_summary() {
        // Require the PEAR Image Graphing class
        if(!@include_once ('Image/Graph.php')) {
            assign_header();
            assign_footer();
            $this->tpl->assign('_MESSAGE', "PEAR::Image_Graph could not be loaded!");
            $this->tpl->display('actions_done.tpl');
            exit;
        } else {
            $image_graph = 1;
        }

        $sql = array (
            "mysql" => array (
                "messages" => "SELECT fu.username,fm.subject,fm.mesg_id,fm.date FROM "._PREFIX."_messages AS fm LEFT JOIN "._PREFIX."_users AS fu ON fu.user_id=fm.author_id ORDER BY fm.mesg_id ASC",
                "reports" => "SELECT report_id, name, description FROM "._PREFIX."_reporting ORDER BY name ASC"
            )
        );
        $messages = array();
        $reports = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['messages']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['reports']);

        $stmt1->execute();

        if ($image_graph)
            $stmt2->execute();

        while ($row = $stmt1->fetch_array()) {
            $messages[] = array (
                "author"	=> $row['username'],
                "subject" 	=> $row['subject'],
                "mesg_id" 	=> $row['mesg_id'],
                "date"		=> strftime("%m-%d-%Y", $row['date'])
            );
        }

        if ($image_graph) {
            while ($row = $stmt2->fetch_array()) {
                $reports[] = array(
                    "report_id" => $row['report_id'],
                    "name" => $row['name'],
                    "description" => $row['description']
                );
            }
        } else {
            $reports[] = array(
                "report_id" => 0,
                "name" => "PEAR Image_Graph is not installed",
                "description" => ""
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            "_WITH_SELECTED"	=> _WITH_SELECTED,
            "_NO_ACTIONS"		=> _NO_ACTIONS,
            "_SETTINGS"		=> _SETTINGS,
            "_REPORT_SUMMARY"	=> _REPORT_SUMMARY
        ));

        // Assign dynamic content
        $this->tpl->assign("MESSAGE_LIST", $messages);
        $this->tpl->assign("REPORT_LIST", $reports);
        $this->tpl->assign("JS_INC", "reporting_main.tpl");

        // display the page
        $this->tpl->display("reporting_main.tpl");
    }

    /***
    * Save a system message
    *
    * After writing a system message, it must be
    * saved to the database. This method saves
    * the message so that it can be displayed
    * on the login page
    *
    * @access public
    * @param string $subject Subject of the message
    * @param string $message Full message to save
    * @param integer $author_id User ID of the author of the message
    */
    public function do_add_system_message($subject,$message,$author_id) {
        $sql = array(
            "mysql" => array(
                "announce" => "INSERT INTO "._PREFIX."_messages (`subject`,`content`,`date`,`author_id`) VALUES (':1',':2',':3',':4')"
            )
        );

        $stmt1 	= $this->db->prepare($sql[_DBSYSTEM]['announce']);
        $time	= time();

        $stmt1->execute($subject,$message,$time,$author_id);

        $this->tpl->assign("_MESSAGE", _REPORT_ADD_MESG_ANNOUNCE);
        $this->tpl->assign("_RETURN_LINK", "<a href='admin.php?extension=Reporting_System'>Return to Reporting System</a>");

        $this->tpl->display("actions_done.tpl");
    }

    /**
    * Allows you to edit a system message
    *
    * Administrators may need to change a message that
    * they posted after they actually posted it. This
    * method displays the message so that it can be
    * altered and saved
    *
    * @access public
    * @param array $message_id IDs of the messages to be changed
    */
    public function show_change_messages($message_id) {
        $sql = array(
            "mysql" => array(
                "mesg" => "SELECT * FROM "._PREFIX."_messages WHERE mesg_id=':1'"
            )
        );
        $messages = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["mesg"]);

        foreach ($message_id as $key => $id) {
            $stmt1->execute($id);

            $row = $stmt1->fetch_assoc();

            $data = array(
                'id'		=> $row['mesg_id'],
                'subj'		=> $row['subject'],
                'content'	=> $row['content']
            );

            array_push($messages, $data);
        }

        $this->tpl->assign(array(
            "MESSAGES"			=> $messages,
            "_REPORT_SUMMARY"		=> _REPORT_SUMMARY,
            "_SETTINGS"			=> _SETTINGS,
            "_REPORT_ANNOUNCE_NEW_MESG"	=> _REPORT_ANNOUNCE_NEW_MESG,
            "_REPORT_MESG_TO_ANNOUNCE"	=> _REPORT_MESG_TO_ANNOUNCE,
            "_REPORT_CLEAR_MESG"		=> _REPORT_CLEAR_MESG,
            "_REPORT_ANNOUNCE_MESG"		=> _REPORT_ANNOUNCE_MESG,
            "_REPORT_SUMMARY"		=> _REPORT_SUMMARY,
            "_REPORT_SUBJECT"		=> _REPORT_SUBJECT
        ));

        $this->tpl->display('reporting_messages_edit.tpl');
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
        $this->tpl->assign("_SETTINGS", _SETTINGS);

        $this->tpl->display("reporting_config.tpl");
    }

    /**
    * Discovers all reports known and unknown
    *
    * Performs process of discovering all reports and adding and removing
    * new and dead reports.
    *
    * @access public
    */
    public function discover_reports() {
        $this->get_installed_reports();
        $this->get_all_known_reports();

        /**
        * By this point we should have a complete list of all currently installed reports
        * and a list of all known reports ( report.*.php files in the extensions/Reporting_System/reports directory )
        *
        * We now need to run a comparison.
        * - Any reports in the "all known" list that are not in the "current list" need to be added
        * - Any reports in the "current list" that are not in the "all known list" need to be removed
        */
        $this->add_new_reports();
        $this->remove_dead_reports();
    }

    /**
    * Get all installed reports
    *
    * Creates an array of all the currently installed reports by fetching
    * the list of currently installed reports from the database
    *
    * @access private
    */
    private function get_installed_reports() {
        $sql = array (
            "mysql" => array (
                'reports_installed'=> "SELECT `name` FROM `"._PREFIX."_reporting`",
            )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['reports_installed']);
        $stmt->execute();

        while ($row = $stmt->fetch_array()) {
            $this->installed_reports[] = $row["name"];
        }

        sort($this->installed_reports);
    }

    /**
    * Retrieves list of all known reports
    *
    * By all known reports I mean all reports that exist in the reports
    * directory. Since this is the only place where reports can be put, there
    * will always potentially be more reports here than in the database. The
    * database is updated via checking which reports exist in the reports
    * directory
    *
    * @access private
    */
        private function get_all_known_reports() {
                /**
                * Variables $x and $y are only temp counting variables. As such, don't
                * spend time trying to understand what they are used for.
                */
                $handle = opendir($this->__get("report_path"));
                while ($x = readdir($handle)) {
            $fullpath = $this->__get("report_path") . "/$x";
                        if(!is_dir($fullpath) && $x != "." && $x != "..") {
                if (substr($x,0,1) != ".") {
                    $temp = explode(".", $x);
                                    $this->report_list[] = $temp[1];
                }
                        }
                }
                closedir($handle);
        sort($this->report_list);
        }

    /**
    * Adds new reports
    *
    * Adds any new reports found in the filesystem to the database. The
    * only place that will be search for reports will be the location
    * stored in the class variable $report_path
    *
    * @access private
    */
    private function add_new_reports() {
        /**
        * The report_list will always be >= the current_list.
        * This is why we loop for each report_list item
        * as opposed to looping for each installed_reports item
        */
        $diff = array_diff($this->__get("report_list"), $this->__get("installed_reports"));

        $sql = array(
            "mysql" => array(
                "installed" => "SELECT * FROM "._PREFIX."_reporting WHERE name=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['installed']);

        // For each report we find, we'll need to do stuff
        foreach ($diff as $key => $report) {
            $report_file = "report." . $report . ".php";

            // We need to run the install method for the new report so that variables and
            // any other necessary stuff, as defined by the author, is run.
            $install_script = $this->__get("report_path") . $report_file;

            $stmt1->execute($report);

            if (is_dir($install_script))
                continue;

            if (file_exists($install_script)) {
                if ($stmt1->num_rows() == 0) {
                    require ($install_script);

                    $rpt = new $report;

                    $rpt->__set("db",$this->__get("db"));
                    $rpt->__set("tpl",$this->__get("tpl"));
                    $rpt->__set("log",$this->__get("log"));
                    $rpt->__set("cfg",$this->__get("cfg"));
                    $rpt->__set("ext",$this->__get("ext"));

                    $rpt->install();
                    unset($rpt);
                }
            } else {
                //$log->log("NEW_EXT_NO_INSTALL_SCRIPT", $rpt);
            }
        }
    }

    /**
    * Removes dead reports
    *
    * A dead report is basically a report that has been removed by the user
    * by having its report file deleted from the reports folder. This method takes
    * care of clearing the database of these dead reports.
    *
    * @access private
    */
    private function remove_dead_reports() {
                /**
        * For this walk, we only care about the installed reports.
                */
        $diff = array_diff($this->__get("installed_reports"), $this->__get("report_list"));

        $sql = array (
            "mysql" => array (
                'delete_report' => "DELETE FROM `"._PREFIX."_reporting` WHERE `name`=':1'",
            )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['delete_report']);

        if (count($diff) > 0) {
            foreach ($diff as $key => $report) {
                $stmt->execute($report);
                    }
        }
    }

    /**
    *
    */
    public function do_delete_messages($message_id) {
        $sql = array (
            "mysql" => array(
                "delete" => "DELETE FROM "._PREFIX."_messages WHERE mesg_id = ':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['delete']);

        if (is_array($message_id)) {
            foreach ($message_id as $key => $val) {
                $this->do_delete_messages($val);
            }
        } else {
            $stmt1->execute($message_id);
        }
    }

    /**
    * Get all info about report
    *
    * All available information in the reporting table will be retrieved
    * about the current report. This can be used later to query the database using
    * your reports specific information
    *
    * @access public
    * @param string $report_id ID of the extension that you want to get
    * @return array The configuration information for this report
    */
    public function report_info($report_id) {
        $sql = array (
            "mysql" => array (
                'config'=> "SELECT * FROM `"._PREFIX."_reporting` WHERE report_id=':1'",
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);
        $stmt1->execute($report_id);

        $row = $stmt1->fetch_assoc();

        $rcfg = array(
            "report_id" 	=> $row['report_id'],
            "report_name"	=> $row['name'],
            "report_desc"	=> $row['description'],
        );

        return $rcfg;
    }

    /**
    * Get name of report
    *
    * Given a provided report ID, this method will return
    * the name of the report, as stored in the database
    *
    * @access public
    * @param integer $report_id ID of the report whose name is to be returned
    * @return string The name of the report with the associated ID
    */
    public function get_report_name($report_id) {
        $sql = array(
            "mysql" => array(
                'name' => "SELECT name FROM "._PREFIX."_reporting WHERE report_id=':1' LIMIT 1"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['name']);
        $stmt1->execute($report_id);

        return $stmt1->result(0);
    }
}

?>
