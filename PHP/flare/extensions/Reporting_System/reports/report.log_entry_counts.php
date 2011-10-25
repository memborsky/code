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
* Creates graphs based on log entry counters
*
* This particular report requires that the Logging
* extension be installed and running. If no entries
* have been logged then this report will not be able
* to display any data
*
* @package Reporting_System
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class log_entry_counts {
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
        $this->__set("report_name", "log_entry_counts");

        /**
        * Set up description
        */
        $this->__set("report_desc", "Graphs number of hits for log type over range of times");
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
        $sql = array (
            "mysql" => array (
                'log_types' => "SELECT type FROM "._PREFIX."_log_entries ORDER BY type ASC"
            )
        );

        $log_type 	= import_var('log_type', 'P');
        $period 	= import_var('period', 'P');

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['log_types']);
        $stmt1->execute();

        while ($row = $stmt1->fetch_assoc()) {
            $types[] = $row['type'];
        }

        $this->tpl->assign('TYPES', $types);

        switch($period) {
            default:
                $this->tpl->assign('REPORT_ID', $this->cfg['report_id']);
                $this->tpl->display('reports/log_entry_counts.tpl');
                break;
            case 'day':
                $time_till = time();
                $time_from = $time_till - 86400;
                $report = $this->run($log_type, $time_from, $time_till);

                $this->tpl->assign(array(
                    'REPORT_GRAPH'	=> $report,
                    'REPORT_ID'	=> $this->cfg['report_id'],
                ));

                $this->tpl->display('reports/log_entry_counts.tpl');
                break;
            case 'week':
                $time_till = time();
                $time_from = $time_till - 604800;
                $report = $this->run($log_type, $time_from, $time_till);

                $this->tpl->assign(array(
                    'REPORT_GRAPH'	=> $report,
                    'REPORT_ID'	=> $this->cfg['report_id'],
                ));

                $this->tpl->display('reports/log_entry_counts.tpl');
                break;
            case 'month':
                $time_till = time();
                $time_from = $time_till - 2629744;
                $report = $this->run($log_type, $time_from, $time_till);

                $this->tpl->assign(array(
                    'REPORT_GRAPH'	=> $report,
                    'REPORT_ID'	=> $this->cfg['report_id'],
                ));

                $this->tpl->display('reports/log_entry_counts.tpl');
                break;
            case 'all':
                $time_till = time();
                $time_from = 0;
                $report = $this->run($log_type, $time_from, $time_till);

                $this->tpl->assign(array(
                    'REPORT_GRAPH'	=> $report,
                    'REPORT_ID'	=> $this->cfg['report_id']
                ));

                $this->tpl->display('reports/log_entry_counts.tpl');
                break;
        }

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
    public function run($log_type, $time_from, $time_till) {
        $sql = array (
            "mysql" => array(
                "count" => "SELECT count(log_id) FROM "._PREFIX."_logging WHERE type=':1' AND timestamp >= ':2' AND timestamp <= ':3'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['count']);

        /**
        * Create the graph
        */
        $Graph =& Image_Graph::factory('graph', array(700, 500));
        $Graph->add(Image_Graph::factory('title', array('Log Entry Counter', 11)));

        $Font =& $Graph->addNew('ttf_font', 'verdana.ttf');

        /**
        * Set the font size to 11 pixels
        */
        $Font->setSize(11);

        $Plotarea =& $Graph->addNew('plotarea');

        foreach ($log_type as $key => $type) {
            $stmt1->execute($type, $time_from, $time_till);

            $count = $stmt1->result(0);

            /**
            * Create a grid and assign it to the secondary Y axis
            */
            $Dataset =& Image_Graph::factory('dataset');
            $Dataset->addPoint("$type", $count);

            /**
            * Create a new bar to plot
            */
            $Plot =& $Plotarea->addNew('bar', $Dataset);

            /**
            * Get 3 random integers for color conversion
            */
            $red 	= rand(0,255);
            $green	= rand(0,255);
            $blue 	= rand(0,255);

            /**
            * Get a proper hex color for the 3 integers above
            */
            $color 	= $this->DecToHex($red, $green, $blue);

            /**
            * Change the fill color of our bar to be the random color
            */
            $Plot->setFillColor($color);
        }

        $AxisX =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);

        if (count($log_type) > 5)
            $AxisX->setFontAngle('vertical');

        $AxisX->setTitle('Log Type');
        $AxisY =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
        $AxisY->setTitle('Number of Entries', 'vertical');

        $filename = $this->__get('report_path') . md5(rand(0,10000000));

        // output the Graph
        $Graph->done(array(
            'filename' => $filename.'.png'
        ));

        return $filename . '.png';
    }

    /**
    * Returns hex value for a given integer
    *
    * Code taken from http://www.321webmaster.com/colorconverter.php
    * and converted to PHP by Tim Rupp
    *
    * @access private
    * @param integer $int Integer to get hex value of
    * @return char Hex character for given integer
    */
    private function GiveHex($int) {
        if($int == 10)
            return 'a';
        else if($int == 11)
            return 'b';
        else if($int == 12)
            return 'c';
        else if($int == 13)
            return 'd';
        else if($int == 14)
            return 'e';
        else if($int == 15)
            return 'f';
        else
            return '' . $int;
    }

    /**
    * Returns a proper color string
    *
    * Code taken from http://www.321webmaster.com/colorconverter.php
    * and converted to PHP by Tim Rupp
    *
    * @access private
    * @param integer $red Integer (between 0 and 255) to assign to red color
    * @param integer $green Integer (between 0 and 255) to assign to green color
    * @param integer $blue Integer (between 0 and 255) to assign to blue color
    * @return string Proper color string
    */
    private function DecToHex($red, $green, $blue) {
        $a = $this->GiveHex(floor($red / 16));
        $b = $this->GiveHex($red % 16);
        $c = $this->GiveHex(floor($green / 16));
        $d = $this->GiveHex($green % 16);
        $e = $this->GiveHex(floor($blue / 16));
        $f = $this->GiveHex($blue % 16);

        $z = '#' . $a . $b . $c . $d . $e . $f;

        return $z;
    }
}

?>
