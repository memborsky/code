<?php
/**
* @package Settings
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
* General Settings tools
*
* The Settings extension contains tools for
* working with settings that dont have an
* associated extension
*
* @package Settings
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class SettingsAdmin {
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
    * Creates an instance of Settings class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided.
    *
    * @access public
    */
    public function __construct() {

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
    * Displays the listed configuration for settings
    * with no set extension
    *
    * Will display any configuration settings stored in the
    * _config table that have no associated extension_id
    *
    * @access public
    * @return bool Always returns true
    */
    public function show_settings() {
        $config = array();
        $sql = array (
            'mysql' => array (
                'config' => "SELECT * FROM "._PREFIX."_config WHERE extension_id='0'"
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
        $this->tpl->assign('_SETTINGS', _SETTINGS);

        // Assign dynamic content
        $this->tpl->assign('CONFIG', $config);

        // Display the page
        $this->tpl->display('settings_main.tpl');
    }

    /**
    * Saves extension configuration back to database
    *
    * After making changes to the general configuration for
    * settings in the config table, the settings must be
    * saved back to the database. This method takes care of
    * that process.
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

        /**
        * Prepare SQL for running
        */
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);

        /**
        * For each setting name->value pair we received...
        */
        foreach($settings as $key => $val) {
            /**
            * Execute the SQL to update that value
            * Note that we pass 0 as the extension_id because the
            * Settings extension updates settings that have no
            * associated extension
            */
            $stmt1->execute(urlencode($val),$key,0);
        }
    }

    /**
    * Displays Page allowing admin to install new extensions
    *
    * @access public
    */
    public function show_install_extensions() {
            $required = array(
            'Accounts',
            'Authentication',
            'Groups',
            'Settings',
            'Filesystem',
            'Error_Handler'
        );

        $req 		= 0;
        $is_installed	= 0;

        $this->ext->get_installed_extensions();
        $tmp = $this->ext->__get("installed_extensions");

        if ($handle = opendir(ABSPATH.'/extensions/')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != "index.php") {
                    foreach ($required as $key => $val) {
                        if ($file == $val) {
                            $req = 1;
                            break;
                        }
                    }

                    foreach ($tmp as $key => $val) {
                        if ($file == $val) {
                            $is_installed = 1;
                            break;
                        }
                    }

                    $extensions[] = array(
                        'name'		=> $file,
                        'req' 		=> $req,
                        'is_installed'	=> $is_installed
                    );

                    $req 		= 0;
                    $is_installed	= 0;
                }
            }

            closedir($handle);
        }
        $this->tpl->assign(array(
            '_SETTINGS'	=> _SETTINGS,
            'extensions'	=> $extensions,
            'JS_INC'	=> "settings_extensions.tpl"
        ));

        $this->tpl->display('settings_extensions.tpl');
    }

    /**
    * Installs an extension
    *
    * Because extensions are not automatically installed
    * once they are added, this method is included to install
    * an extension fully into the system.
    *
    * @access public
    */
    public function do_install_extension($extension_name, $admin_id) {
        $sql = array(
            "mysql" => array(
                "select" => "SELECT extension_id FROM "._PREFIX."_extensions WHERE name=':1'",
                "admin_privs" => "INSERT INTO "._PREFIX."_admin_privs(`user_id`,`extension_id`) VALUES (':1',':2')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['admin_privs']);

        $this->ext->get_installed_extensions();
        $tmp = $this->ext->__get("installed_extensions");
        array_push($tmp, $extension_name);
        sort($tmp);
        $this->ext->__set("extension_list", $tmp);
        $this->ext->add_new_extensions();

        $stmt1->execute($extension_name);

        $extension_id = $stmt1->result(0);

        $stmt2->execute($admin_id, $extension_id);
    }

    /**
    * Removes an extension
    *
    * Removes an extension and all associating privileges
    * from the database. Note that this currently does not
    * remove the extension files from disk
    *
    * @access public
    */
    public function do_remove_extension($extension_name) {
        $sql = array(
            "mysql" => array(
                "select" => "SELECT extension_id FROM "._PREFIX."_extensions WHERE name=':1'",
                "delete_ext" => "DELETE FROM "._PREFIX."_extensions WHERE extension_id=':1'",
                "delete_privs" => "DELETE FROM "._PREFIX."_admin_privs WHERE extension_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['delete_ext']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['delete_privs']);

        $stmt1->execute($extension_name);

        $extension_id = $stmt1->result(0);

        if ($extension_id <= 0)
            return false;

        $stmt2->execute($extension_id);
        $stmt3->execute($extension_id);

        return true;
    }
}

?>
