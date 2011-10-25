<?php
/**
* @package Email
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
* Mailing list administration extension
*
* Contains the methods and variables needed to send email
* in Flare from the administration site.
*
* @package Email
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class MailingListAdmin {
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
    * Error status of sending email
    *
    * @access private
    * @var bool
    */
    public $error;

    /**
    * Barebones constructor
    *
    * This constructor will override the default one supplied by PHP.
    * In this context the constructor does not do anything, so it is
    * left up to future developers to decide if it will even be used.
    *
    * @access public
    */
    public function __construct() {
        $this->__set("error", false);
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
    * Creates a mailing list
    *
    * @access public
    * @param string $list_name Name of the list to create
    */
    public function do_create_mailing_list($list_name) {
        $time = time();
        $sql = array(
            "mysql" => array(
                "insert" => "INSERT INTO "._PREFIX."_mailing_list_info (list_name, creation_date) VALUES (':1', ':2')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['insert']);

        $stmt1->execute($list_name, $time);
    }

    /**
    * Retrieves the mailing list id
    *
    * @access public
    * @param string $list_name Name of the mailing list whose ID you want
    * @return integer $id Mailing list ID of the given list name
    */
    public function get_mailing_list_id($list_name) {
        $sql = array(
            "mysql" => array(
                "select" => "SELECT list_id FROM "._PREFIX."_mailing_list_info WHERE list_name = ':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['select']);

        $stmt1->execute($list_name);

        $id = $stmt1->result(0);

        return $id;
    }

    /**
    * Adds a new member to a mailing list
    *
    * @access public
    * @param integer $list_id Mailing list ID of list to add member to
    * @param integer $user_id User ID of the user to add
    */
    public function add_member_to_mailing_list($list_id, $user_id) {
        $sql = array(
            "mysql" => array(
                "insert" => "INSERT INTO "._PREFIX."_mailing_lists(list_id,user_id) VALUES (':1',':2')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['insert']);

        $stmt1->execute($list_id, $user_id);
    }

    /**
    * Displays the mailing list main page
    *
    * This page shows all the mailing lists that are known
    * to the system and lets you edit them, delete them
    * and perform other operations on them.
    *
    * @access public
    */
    public function show_mailing_lists() {
        $sql = array(
            "mysql" => array(
                "lists" => "SELECT * FROM "._PREFIX."_mailing_list_info ORDER BY list_name ASC",
                "count" => "SELECT COUNT(user_id) FROM "._PREFIX."_mailing_lists WHERE list_id=':1'"
            )
        );
        $mailing_lists = array();
        $data = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['lists']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['count']);

        $stmt1->execute();

        while ($row = $stmt1->fetch_assoc()) {
            $stmt2->execute($row['list_id']);

            $data = array(
                'id'		=> $row['list_id'],
                'name'		=> $row['list_name'],
                'date'		=> strftime("%m-%d-%Y", $row['creation_date']),
                'members'	=> $stmt2->result(0)
            );

            array_push($mailing_lists, $data);
        }

        $this->tpl->assign(array(
            '_WITH_SELECTED'	=> _WITH_SELECTED,
            '_NO_ACTIONS'		=> _NO_ACTIONS,
            '_SETTINGS'		=> _SETTINGS,
            'MAILING_LISTS'		=> $mailing_lists,
            'JS_INC'		=> "email_mailing_lists.tpl"
        ));

        $this->tpl->display('email_mailing_lists.tpl');
    }

    /**
    * Delete a mailing list
    *
    * Deletes a mailing list and removes all the recipients
    * for the specified list from the mailing_lists table
    *
    * @access public
    * @param integer $list_id ID of the list being deleted
    */
    public function do_delete_mailing_list($list_id) {
        $sql = array(
            "mysql" => array(
                "rm_info" => "DELETE FROM "._PREFIX."_mailing_lists WHERE list_id=':1'",
                "rm_members" => "DELETE FROM "._PREFIX."_mailing_list_info WHERE list_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['rm_info']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['rm_members']);

        $stmt1->execute($list_id);
        $stmt2->execute($list_id);
    }

    /**
    * Displays page to change mailing list
    *
    * Flare allows the administrator to change the
    * mailing list and update it with new members
    * or remove old members. This method will display
    * the form allowing the changes to be made
    *
    * @access public
    * @param integer $list_id ID of the list to be changed
    */
    public function show_change_mailing_list($list_id) {
        $sql = array(
            "mysql" => array(
                "info" => "SELECT list_name FROM "._PREFIX."_mailing_list_info WHERE list_id=':1'",
                "current_members" => "SELECT fu.user_id, fu.username FROM "._PREFIX."_mailing_lists AS ml LEFT JOIN "._PREFIX."_users AS fu ON ml.user_id=fu.user_id WHERE ml.list_id=':1' ORDER BY fu.username ASC",
                "remaining_users" => "SELECT user_id,username FROM "._PREFIX."_users WHERE user_id NOT IN ( SELECT user_id FROM "._PREFIX."_mailing_lists WHERE list_id = ':1' )"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['info']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['current_members']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['remaining_users']);

        $stmt1->execute($list_id);
        $stmt2->execute($list_id);
        $stmt3->execute($list_id);

        $name		= $stmt1->result(0);
        $current	= array();
        $remaining 	= array();

        while ($row = $stmt2->fetch_assoc()) {
            $data = array(
                'id'	=> $row['user_id'],
                'name'	=> $row['username']
            );

            array_push($current, $data);
        }

        while ($row = $stmt3->fetch_assoc()) {
            $data = array(
                'id'	=> $row['user_id'],
                'name'	=> $row['username']
            );

            array_push($remaining, $data);
        }

        $this->tpl->assign(array(
            'NAME'		=> $name,
            'LIST_ID'	=> $list_id,
            'CURRENT'	=> $current,
            'REMAINING'	=> $remaining,
            '_SETTINGS'	=> _SETTINGS
        ));

        $this->tpl->display('email_mailing_list_edit.tpl');
    }

    /**
    * Updates a mailing list
    *
    * Mailing lists as used by Flare contain a list name
    * as well as a list of recipients. The form to change
    * a list gives you the ability to select which new
    * recipients to add and which old ones to remove.
    * Duplicate recipient names cannot happen.
    *
    * @access public
    * @param integer $list_id ID of the list to be modified
    * @param string $list_name New name (or unchanged name) of the list being modified
    * @param array $rm_recipients Array of recipients to remove from the mailing list
    * @param array $add_recipients Array of recipients to add to the mailing list
    */
    public function do_change_mailing_list($list_id, $list_name, $rm_recipients, $add_recipients) {
        $sql = array(
            "mysql" => array(
                "update" => "UPDATE "._PREFIX."_mailing_list_info SET list_name=':1' WHERE list_id=':2'",
                "add" => "INSERT INTO "._PREFIX."_mailing_lists (`list_id`,`user_id`) VALUES (':1',':2')",
                "del" => "DELETE FROM "._PREFIX."_mailing_lists WHERE list_id=':1' AND user_id=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['update']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['add']);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]['del']);

        $stmt1->execute($list_name,$list_id);

        if (count($rm_recipients) > 0) {
            foreach ($rm_recipients as $key => $user_id) {
                $stmt3->execute($list_id,$user_id);
            }
        }

        if (count($add_recipients) > 0) {
            foreach (@$add_recipients as $key => $user_id) {
                $stmt2->execute($list_id,$user_id);
            }
        }
    }
}

?>
