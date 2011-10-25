<?php
/**
* @package Authentication
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
* Authentication administration class
*
* This class provides methods for administering features
* of the Authentication extension
*
* @package Authentication
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class AuthenticationAdmin {
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
    * Creates an instance of AuthenticationAdmin class
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
                'config' => "SELECT * FROM "._PREFIX."_config WHERE extension_id=':1'",
                'visible' => "SELECT visible FROM "._PREFIX."_extensions WHERE extension_id=':1'"
                )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['config']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['visible']);

        $stmt1->execute($this->ext->__get('extension_id'));
        $stmt2->execute($this->ext->__get('extension_id'));

        $visible = $stmt2->result(0);

        while ($row = $stmt1->fetch_array()) {
            $config[$row['name']] = array (
                'name' 	=> $row['name'],
                'desc' 	=> $row['description'],
                'value'	=> $row['value']
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_SETTINGS'	=> _SETTINGS,
            '_SAVE_CHGS'	=> _SAVE_CHGS,
            '_UNDO_CHGS'	=> _UNDO_CHGS
        ));

        // Assign dynamic content
        $this->tpl->assign('CONFIG', $config);
        $this->tpl->assign('VISIBLE', $visible);

        // Display the page
        $this->tpl->display('authentication_config.tpl');
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
