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

/**
*
*/
class Logging {
    public $log_entry;
    public $log_file;
    public $binds;
    public $db;

    /**
    *
    */
    public function __construct(&$db) {
        // Create SQL queries
        $sql = array (
            "mysql" => array (
                'log_entries' => "SELECT * FROM "._PREFIX."_log_entries"
                )
        );

        /**
        * All log entries that are available.
        *
        * The values that you want to send to the log entry should be numbered from 1 to
        * the highest known number of values you'll be sending to the entry.
        * All values should be entered in the log entry lines below as :number where number
        * is the argument position that you will be sending to the entry.
        *
        * The below list also supports several wildcards that you can use to
        * insert values without having to send those values to the log function.
        * The supported wildcards are listed below.
        *
        * %date% - date at time of logging. Expressed in YYYY-mm-dd
        * %time% - current time at time of logging. Expressed in hh:mm:ss
        * %user% - the username of the person filing the log entry, or 'guest' if username is empty
        */
        $log_entry = array ();

        // Assign db Flare var to the Logging class
        $this->__set("db", $db);

        // Prepare the above SQL to be executed
        $stmt = $this->db->prepare($sql[_DBSYSTEM]['log_entries']);

        // Now run the SQL
        $stmt->execute();

        // For each log_type that we return, we need to do stuff with it
        while($row = $stmt->fetch_array()) {
            // Assign the content of the log type to the log_entry array.
            // Use the type of the log as the index for the log_entry array
            $log_entry[$row['type']] = $row['content'];
        }

        // Assign the log_entries to the class variable log_entry
        $this->__set("log_entry", $log_entry);
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
    *
    */
    private function referer() {
        return import_var('HTTP_REFERER', 'SE');
    }

    /**
    *
    */
    private function page() {
        return __FILE__;
    }

    /**
    *
    */
    public function log() {
        $binds = func_get_args();

        $type = ($binds[0] != "") ? $binds[0] : 'DEFAULT';
        $time = time();
        $ip = import_var('REMOTE_ADDR', 'SE');

        $sql = array (
            "mysql" => array (
                'log_entry' => "INSERT INTO "._PREFIX."_logging (`type`,`timestamp`,`ip`,`contents`) VALUES ('$type','$time','$ip',':1');",
                )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['log_entry']);

        if (count($binds) > 0) {
            if (!array_key_exists($binds[0], $this->log_entry)) {
                $log_entry = $this->log_entry["DEFAULT"];
            } else {
                $log_entry = $this->log_entry[$binds[0]];
                array_shift($binds);
            }

            if (count($binds) > 0) {
                foreach ($binds as $key => $val) {
                    $this->binds[$key + 1] = $val;
                }

                foreach ($this->binds as $key => $val) {
                    $log_entry = preg_replace("/:$key/", $val, $log_entry, 1);
                }
            }

            $log_entry = str_replace("%date%", date("Y-m-d"), $log_entry);
            $log_entry = str_replace("%time%", date("h:m:s"), $log_entry);
            $log_entry = str_replace("%type%", $type, $log_entry);
            $log_entry = str_replace("%time_seconds%", time(), $log_entry);
            $log_entry = str_replace("%remote_ip%", $_SERVER['REMOTE_ADDR'], $log_entry);
            $log_entry = str_replace("%user%", (($_SESSION['username'] != "") ? $_SESSION['username'] : 'guest'), $log_entry);
        }
        $stmt->execute($log_entry);

        return TRUE;
    }

}

?>
