<?php
/**
* @package Help
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
* Help tools
*
* Contains all the tools necessary to access the
* help features provided by Flare
*
* @package Help
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Help {
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
    * Creates an instance of Help class
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
    * Displays a help item
    *
    * Lets the user see the content for a
    * particular help item when they choose
    * it at the help page.
    *
    * @access public
    * @param integer $topic_id ID of the help topic the user is requesting to see
    * @para integer $user_level User level of the users account
    */
    public function show_help($topic_id = 0, $user_level = '1000') {
        $topics_list = array();
        $sub_sections = array();

        // Set up SQL array of all SQL we use
        $sql = array (
            'mysql' => array (
                'topic_list' => "SELECT * FROM "._PREFIX."_help_index LEFT JOIN "._PREFIX."_help_content ON help_id=topic_id AND user_min_level >= ':2' WHERE parent_id=':1' AND type='topic' ORDER BY name ASC",
                'subsections' => "SELECT * FROM "._PREFIX."_help_index WHERE parent_id=':1' AND user_min_level >= ':2' AND type='category' ORDER BY name ASC",
            )
        );

        // Prepare any SQL for use
        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['topic_list']);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]['subsections']);

        // Execute first SQL call to get list of topics
        $stmt1->execute($topic_id, $user_level);

        // For each topic retrieved...
        while ($row = $stmt1->fetch_array()) {
            // Store it in the topics array...
            $topics_list[] = array(
                "topic_id" 	=> $row['help_id'],
                "name" 		=> $row['name'],
                "content"	=> $row['content']
            );
        }

        // Query for all subsections that have a parent id of the current section.
        $stmt2->execute($topic_id, $user_level);
        while ($row2 = $stmt2->fetch_array()) {
            // Then store all those in the sub_sections array
            $sub_sections[] = array(
                "topic_id"	=> $row2['help_id'],
                "name" 		=> $row2['name']
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_HELP_WELCOME'		=> _HELP_WELCOME,
            '_HELP_TOPICS'		=> _HELP_TOPICS,
            '_HELP_NO_TOPICS'	=> _HELP_NO_TOPICS,
            '_HELP_SUB_SECTIONS'	=> _HELP_SUB_SECTIONS,
            '_HELP_BOTTOM'		=> _HELP_BOTTOM,
            '_HELP_CONTENT'		=> _HELP_CONTENT));

        // Assign dynamic content
        $this->tpl->assign('TOPICS_LIST', $topics_list);
        $this->tpl->assign('SUB_SECTIONS', $sub_sections);

        // Display the page
        $this->tpl->display('help_main.tpl');
    }

    /**
    * Displays the credits page
    *
    * Displays the credits HTML page that is
    * shipped with Flare
    *
    * @access public
    */
    public function show_credits() {
        $this->tpl->display('credits.tpl');
    }

    /**
    * Displays the privacy policy page
    *
    * Displays the privacy policy HTML page that
    * is shipped with Flare
    *
    * @access public
    */
    public function show_privacy_policy() {
        $this->tpl->display('privacy_policy.tpl');
    }

    /**
    * Displays feedback page
    *
    * Displays the page that allows a user to
    * send the admins feedback about the system
    *
    * @access public
    */
    public function show_feedback() {
        $this->tpl->assign(array(
            '_HELP_FEEDBACK'	=> _HELP_FEEDBACK,
            '_HELP_FEEDBACK_MESG'	=> _HELP_FEEDBACK_MESG,
            '_HELP_FEEDBACK_SHORT'	=> _HELP_FEEDBACK_SHORT,
            '_HELP_FEEDBACK_SEND'	=> _HELP_FEEDBACK_SEND,
            '_EMAIL'		=> _EMAIL,
            'DATE'			=> time()
        ));

        $this->tpl->display('feedback.tpl');
    }

    /**
    * Displays the usage policy page
    *
    * Displays the usage policy HTML page that
    * is shipped with Flare
    *
    * @access public
    */
    public function show_usage_policy() {
        $this->tpl->display('usage_policy.tpl');
    }

    /**
    * Displays terms of service page
    *
    * Displays the terms of service HTML page
    * that is shipped with Flare
    *
    * @access public
    */
    public function show_tos() {
        $this->tpl->display('tos.tpl');
    }

    /**
    * Leaves a feedback message
    *
    * Users are given the ability to leave feedback
    * messages for the administrators. This will
    * save their message to the database
    *
    * @access public
    * @param integer $date Unix timestamp of the date the feedback was submitted
    * @param string $email Email address given by the user
    * @param string $short_desc Short description (title) of the feedback item
    * @param string $content Full content that the user typed
    */
    public function do_leave_feedback($date, $email, $short_desc, $content) {
        $sql = array (
            "mysql" => array (
                "feedback" => "INSERT INTO "._PREFIX."_feedback (`date`,`email`,`ip`,`short_desc`,`content`) VALUES (':1',':2',':3',':4',':5')"
            )
        );

        $ip = import_var('REMOTE_ADDR', 'SE');

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["feedback"]);

        $stmt1->execute($date, $email, $ip, $short_desc, $content);
    }
}

?>
