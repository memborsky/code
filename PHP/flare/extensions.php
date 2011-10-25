<?php
/**
* @package Extensions
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
* Extension maintenance tools
*
* Contains the methods and variables needed to maintain the extensions
* that Flare can use. This class is able to add, remove and find new
* extensions that may be installed. It allows users to simply add the
* extension to the extensions/ folder, and it will automatically be
* installed
*
* @package Extensions
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Extensions {
    /**
    * Database object used to connect to and query database
    *
    * @access public
    * @var object
    */
    public $db;

    /**
    * Unique extension ID used to identify extension
    *
    * @access public
    * @var integer
    */
    public $extension_id;

    /**
    * Name of the extension. Must not contain spaces
    *
    * @access public
    * @var string
    */
    public $extension_name;

    /**
    * Extension name when displayed on all normal index pages
    *
    * @access public
    * @var string
    */
    public $extension_displayed_name;

    /**
    * Extension name when displayed in the admin block
    *
    * @access public
    * @var string
    */
    public $extension_admin_displayed_name;

    /**
    * An image to associate with the extension in the admin block
    *
    * @access public
    * @var string
    */
    public $extension_image;

    /**
    * Minimum user level required to access extension features
    *
    * @access public
    * @var integer
    */
    public $extension_user_min_level;

    /**
    * Order in which (from left to right) extension names are displayed to normal users
    *
    * @access public
    * @var integer
    */
    public $extension_display_order;

    /**
    * Whether the extension is enabled or disabled
    *
    * @access public
    * @var integer
    */
    public $extension_enabled;

    /**
    * Whether the extension is visible to normal users
    *
    * @access public
    * @var integer
    */
    public $extension_visible;

    /**
    * Path to the extensions folder
    *
    * @access private
    * @var string
    */
    private $extension_path;

    /**
    * Contains all known currently installed extensions (from database)
    *
    * @access private
    * @var array
    */
    private $installed_extensions;

    /**
    * Contains list of all currently available extensions (from extensions folder)
    *
    * @access private
    * @var array
    */
    private $extension_list;

    /**
    * Specifies whether the extension was installed successfully or not
    *
    * @access private
    * @var boolean
    */
    private $install_result;

    /**
    * Performs all necessary extension operations at instantiation
    *
    * The extension upon intiation, will automatically discover all new extensions
    * and remove all old extensions. There should be no need to manually call any
    * of the methods provided.
    *
    * @access public
    * @param string $extension_path Path to the extensions directory
    */
    public function __construct($extension_path = "./extensions/") {
        $this->__set("extension_path", $extension_path);
        $this->__set("installed_extensions", array());
        $this->__set("extensions_list", array());
        $this->__set("install_result", 0);
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
    * Discovers all extensions known and unknown
    *
    * Performs process of discovering all extensions and adding and removing
    * new and dead extensions.
    *
    * @access private
    */
    public function discover_extensions () {
        $this->get_installed_extensions();
        $this->get_all_known_extensions();

        /**
        * By this point we should have a complete list of all currently installed extensions
        * and a list of all known extensions ( directories in the extensions directory )
        *
        * We now need to run a comparison.
        * - Any extensions in the "all known list" that are not in the "current list" need to be added
        * - Any extensions in the "current list" that are not in the "all known list" need to be removed
        */

        $this->add_new_extensions();
        $this->remove_dead_extensions();
    }

    /**
    * Get all installed extensions
    *
    * Creates an array of all the currently installed extensions by fetching
    * the list of currently installed extensions from the database
    *
    * @access private
    */
    public function get_installed_extensions() {
        $sql = array (
            "mysql" => array (
                'exts_installed'=> "SELECT `name` FROM `"._PREFIX."_extensions`",
            )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['exts_installed']);
        $stmt->execute();

        while ($row = $stmt->fetch_array()) {
            $this->installed_extensions[] = $row["name"];
        }

        sort($this->installed_extensions);
    }


    /**
    * Adds new extensions
    *
    * Adds any new extensions found in the filesystem to the database. The
    * only place that will be search for extensions will be the location
    * stored in the class variable $extension_path
    *
    * @access private
    */
    public function add_new_extensions() {
        /**
        * The extension_list will always be >= the current_list.
        * This is why we loop for each extension_list item
        * as opposed to looping for each installed_extensions item
        */
        $diff = array_diff($this->extension_list, $this->installed_extensions);

        $sql = array (
            "mysql" => array (
                'get_ext' => "SELECT extension_id FROM "._PREFIX."_extensions WHERE name=':1'",
                'add_ext' => "INSERT INTO `"._PREFIX."_extensions` (`name`,`displayed_name`,`admin_displayed_name`,`enabled`) VALUES (':1',':2',':3','0')",
            )
        );

        // Prepare all the SQL we'll be using
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['get_ext']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['add_ext']);

        // For each extension we find, we'll need to do stuff
        foreach ($diff as $key => $extension) {
            // Because the extension may have had spaces in its name, remove them
            $safe_extension_name = str_replace(' ', '_', $extension);

            // Run the SQL to check to see if the extension name already exists
            $stmt1->execute($safe_extension_name);

            // Get the result if any
            $result = @$stmt1->result(0);

            // If the extension_id already exists, we can assume the extension already exists!
            if (is_integer($result)) {
                // Make a log entry and just move on
                if ($log != '')
                    $log->log("ADD_EXT_FAILURE_DUPE", $extension);

                $this->__set("install_result", false);
                continue;
            } else {
                // Otherwise, store the extension
                $stmt2->execute($safe_extension_name,$extension,$extension);

                // Get the extension_id we just stored
                $stmt1->execute($safe_extension_name);

                $extension_id = $stmt1->result(0);
            }

            /**
            * Now we need to run the install script for the new extension so that variables and
            * any other necessary stuff, as defined by the author, is run.
            */
            $install_script = $this->__get("extension_path") . $extension . "/install.php";

            /**
            * An extension can still be installed even if there is no install script. There's
            * no harm in doing this. So incase the install.php script doesnt exist, dont
            * choke and die.
            */
            if (file_exists($install_script)) {
                global $db,$extension;

                $db = $this->__get("db");

                require_once($install_script);

                // Since the contents of the install file have been parsed, remove the file
                unlink($install_script);
            } else {
                if (@$log != '')
                    $log->log("NEW_EXT_NO_INSTALL_SCRIPT", $safe_extension_name);
            }

            // Log that the new extension has been installed
            if (@$log != '')
                $log->log("NEW_EXT_INSTALLED", $safe_extension_name);

            $this->__set("install_result", true);
        }
    }

    /**
    * Removes dead extensions
    *
    * A dead extension is basically an extension that has been removed by the user
    * by having its folder deleted from the extensions folder. This method takes
    * care of clearing the database of these dead extensions.
    *
    * @access private
    */
    public function remove_dead_extensions() {
        /**
        * For this walk, we only care about the installed extensions.
        */
        $diff = array_diff($this->installed_extensions, $this->extension_list);

        $sql = array (
            "mysql" => array ( 'del_ext' => "DELETE FROM `"._PREFIX."_extensions` WHERE `name`=':1'",
            )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['del_ext']);

        foreach ($diff as $key => $extension) {
            $stmt->execute($extension);
        }
    }

    /**
    * Retrieves list of all known extensions
    *
    * By all known extensions I mean all extensions that exist in the extensions
    * directory. Since this is the only place where extensions can be put, there
    * will always potentially be more extensions here than in the database. The
    * database is updated via checking which extensions exist in the extensions
    * directory
    *
    * @access private
    */
    public function get_all_known_extensions() {
        /**
        * Variables $x and $y are only temp counting variables. As such, don't
        * spend time trying to understand what they are used for.
        */
        $handle = opendir($this->extension_path);
        while ($x = readdir($handle)) {
            $fullpath = $this->extension_path . "/$x";
            if(is_dir($fullpath) && $x != "." && $x != "..") {
                $this->extension_list[] = $x;
            }
        }
        closedir($handle);
        sort($this->extension_list);
    }

    /**
    * Get all info about extension
    *
    * All available information in the extensions table will be retrieved about
    * the current extension. This can be used later to query the database using
    * your extenions specific information
    *
    * @access public
    * @param string $extension Name of the extension that you want to get
    */
    public function extension_config_info($extension) {
        $sql = array (
            "mysql" => array (
                'config'=> "SELECT * FROM `"._PREFIX."_extensions` WHERE name=':1'",
            )
        );

        $stmt = $this->db->prepare($sql[_DBSYSTEM]['config']);
        $stmt->execute(str_replace(' ', '_', $extension));

        $row = $stmt->fetch_array();

        $this->__set("extension_id",                    $row['extension_id']);
        $this->__set("extension_name",                  $row['name']);
        $this->__set("extension_displayed_name",        $row['displayed_name']);
        $this->__set("extension_admin_displayed_name",  $row['admin_displayed_name']);
        $this->__set("extension_image",                 $row['image']);
        $this->__set("extension_user_min_level",        $row['user_min_level']);
        $this->__set("extension_display_order",         $row['display_order']);
        $this->__set("extension_enabled",               $row['enabled']);
        $this->__set("extension_visible",               $row['visible']);
    }
}

?>
