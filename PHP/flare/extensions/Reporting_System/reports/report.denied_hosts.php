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
* Lists Denied Hosts
*
* DenyHosts is a script that is run on Flare to
* pick up people who are trying to brute force
* their way into our server via SSH. This report
* will simply list the offenders
*
* @package Reporting_System
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class denied_hosts {
    /**
    * Name of the report as it will appear in the database.
    * This is limited to 64 characters in length
    *
    * @access private
    * @var string
    */
    private $report_name;

    /**
    * Description of the report. Keep this limited
    * to no more than 255 characters
    *
    * @access private
    * @var string
    */
    private $report_desc;

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
    * Path relative to the master admin.php file, to where the reports directory is located
    *
    * @access private
    * @var string
    */
    private $report_path;

    /**
    * Full path to the systems hosts.deny file
    *
    * @access private
    * @var string
    */
    private $hosts_deny_file;

    /**
    * Full path to the systems hosts.allow file
    *
    * @access private
    * @var string
    */
    private $hosts_allow_file;

    /**
    * Creates an instance of Report
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided.
    *
    * @access public
    */
    public function __construct() {
        /**
        * Set the name of the report according to filename
        */
        $this->__set("report_name", "denied_hosts");

        /**
        * Set up description
        */
        $this->__set("report_desc", "Lists all the hosts that have been denined by DenyHosts");

        $this->__set("hosts_deny_file", "/etc/hosts.deny");
        $this->__set("hosts_allow_file", "/etc/hosts.allow");
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
    * Installs report into Flare system
    *
    * log_entry_counts requires no extra tables be added. A simple registration with
    * the reporting table is all that is needed to run and use this report
    *
    * @access public
    */
    public function install() {
        $sql_report = array(
            "mysql" => array(
                "install" => "INSERT INTO "._PREFIX."_reporting (`name`, `description`) VALUES (':1', ':2')"
            )
        );

        $stmt_report = $this->db->prepare($sql_report[_DBSYSTEM]['install']);

        $stmt_report->execute($this->__get("report_name"), $this->__get("report_desc"));
    }

    /**
    *
    */
    public function uninstall() {
        $sql = array(
            "mysql" => array(
                "uninstall" => "DELETE FROM "._PREFIX."_reporting WHERE name=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['uninstall']);

        $stmt1->execute($this->__get("report_name"));
    }

    /**
    * Main display method of Report
    *
    * This method should be used in the same way
    * that the index and admin PHP files as used
    * in the main class folder. Switching between
    * cases is an example.
    *
    * @access public
    */
    public function show() {
        $this->run();
    }

    /**
    * Runs the particular report
    *
    * The second part of a report is the run functionality.
    * All reports should provide their own run function,
    * and use it as a means of displaying their report
    * using data sent by the user in whatever form the
    * report installs.
    *
    * @access public
    */
    public function run() {
        $fhd = fopen($this->__get("hosts_deny_file"), 'r');
        $fha = fopen($this->__get("hosts_allow_file"), 'r');
        $denied = array();
        $allowed = array();

        while (!feof($fha)) {
            $line = fgets($fha, 4096);

            if(substr($line,0,1) == '#')
                continue;
            else {
                $line = trim($line);
                if ($line == '')
                    continue;

                $tmp 		= explode(':', $line);
                $ip 		= trim($tmp[1]);

                $allowed[] = $ip;
            }
        }

        while (!feof($fhd)) {
            $line = fgets($fhd, 4096);

            if(substr($line,0,1) == '#')
                continue;
            else {
                $line = trim($line);
                if ($line == '')
                    continue;

                $data		= array();
                $tmp 		= explode(':', $line);
                $services 	= trim($tmp[0]);
                $ip 		= trim($tmp[1]);

                if (in_array($ip, $allowed))
                    continue;

                $hostname 	= @gethostbyaddr($ip);

                $data = array(
                    'ip'		=> $ip,
                    'host'		=> $hostname,
                    'services'	=> $services
                );

                array_push($denied, $data);
            }
        }

        fclose($fhd);
        fclose($fha);

        $this->tpl->assign('DENIED', $denied);
        $this->tpl->display('reports/denied_hosts.tpl');
    }
}

?>
