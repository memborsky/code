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

require_once(ABSPATH.'/extensions/Help/class.Help.php');
require('Mail.php');

/**
* Help Administration tools
*
* Contains all the tools necessary to configure the Help
* extension from the administrative interface of Flare.
* This class extends the methods of the Help class used
* by the normal user.
*
* @package Help
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class HelpAdmin extends Help {
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
    * Creates an instance of HelpAdmin class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic, blank one is provided.
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
            '_HELP_ADM_SHOW_TOPICS' => _HELP_ADM_SHOW_TOPICS,
            '_HELP_ADM_ADD_TOPIC'	=> _HELP_ADM_ADD_TOPIC,
            '_HELP_ADM_FEEDBACK'	=> _HELP_ADM_FEEDBACK,
            '_SETTINGS'		=> _SETTINGS
        ));

        // Assign dynamic content
        $this->tpl->assign('CONFIG', $config);
        $this->tpl->assign('VISIBLE', $visible);

        // Display the page
        $this->tpl->display('help_config.tpl');
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
        $this->tpl->assign('_MESSAGE', _HELP_ADM_SETTINGS_SAVED);
        $this->tpl->assign('_RETURN_LINK', _HELP_ADM_SETTINGS_SAVED_RETURN_LINK);

        // Display the page
        $this->tpl->display('actions_done.tpl');
    }

    /**
    * Displays a list of all help topics and categories
    *
    * All the help topics will be displayed in a
    * format where they can be selected and edited
    * by the admin
    *
    * @access public
    */
    public function show_all_topics() {
        $topic_list = array();

        $sql = array (
            'mysql' => array (
                'topic_list' => "SELECT * FROM "._PREFIX."_help_index LEFT JOIN "._PREFIX."_help_content ON help_id=topic_id ORDER BY name ASC",
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["topic_list"]);

        $stmt1->execute();

        while ($row = $stmt1->fetch_array()) {
            $topic_list[] = array(
                "help_id" 	=> $row['help_id'],
                "name"		=> $row['name'],
                "content"	=> $row['content'],
                "level"		=> $row['user_min_level']
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_HELP_ADM_SHOW_TOPICS'	=> _HELP_ADM_SHOW_TOPICS,
            '_HELP_ADM_ADD_TOPIC'	=> _HELP_ADM_ADD_TOPIC,
            '_WITH_SELECTED'	=> _WITH_SELECTED,
            '_NO_ACTIONS'		=> _NO_ACTIONS,
            '_SETTINGS'		=> _SETTINGS));

        // Assign dynamic content
        $this->tpl->assign('TOPIC_LIST', $topic_list);
        $this->tpl->assign('JS_INC', 'help_main.tpl');

        // Display page
        $this->tpl->display('help_main.tpl');
    }

    /**
    * Displays page for adding a help topic
    *
    * This will display the page that allows an admin
    * to add new help topics to the system.
    *
    * @access public
    */
    public function show_add_topic() {
        $topic_list = array();
        $user_level = array();

        // Set up SQL array of all SQL we use
        $sql = array (
            'mysql' => array (
                'topic_list' => "SELECT * FROM "._PREFIX."_help_index LEFT JOIN "._PREFIX."_help_content ON help_id=topic_id WHERE type='category' ORDER BY name ASC",
                'user_level' => "SELECT DISTINCT(user_level) FROM "._PREFIX."_users ORDER BY user_level ASC"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["topic_list"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["user_level"]);
        $stmt1->execute();
        $stmt2->execute();

        while ($row = $stmt1->fetch_array()) {
            $topic_list[] = array(
                "help_id" 	=> $row['help_id'],
                "name"		=> $row['name']
            );
        }

        while ($row = $stmt2->fetch_array()) {
            $user_level[] = array(
                "user_level"	=> $row['user_level']
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_HELP_ADM_SHOW_TOPICS'		=> _HELP_ADM_SHOW_TOPICS,
            '_HELP_ADM_ADD_TOPIC'		=> _HELP_ADM_ADD_TOPIC,
            '_HELP_ADM_PARENT_CATEGORY'	=> _HELP_ADM_PARENT_CATEGORY,
            '_NAME'				=> _NAME,
            '_HELP_CONTENT'			=> _HELP_CONTENT,
            '_HELP_ADM_REQUIRED_LEVEL'	=> _HELP_ADM_REQUIRED_LEVEL,
            '_SETTINGS'			=> _SETTINGS,
            '_NONE'				=> _NONE,
            '_RESET_FORM'			=> _RESET_FORM));

        // Assign dynamic content
        $this->tpl->assign('TOPIC_LIST', $topic_list);
        $this->tpl->assign('USER_LEVEL', $user_level);

        // Display page
        $this->tpl->display('help_add_topic.tpl');
    }

    /**
    * Add a help topic
    *
    * This method will save a help topic
    * that you have written to the database
    *
    * @access public
    * @param integer $parent_id Help ID of the parent of this topic
    * @param integer $user_level User level that is allowed to see this topic
    * @param string $topic_name Name of the help topic
    * @param string $topic_content Content for the help topic
    * @param string $type Type of help item. Whether its a topic or category
    */
    public function do_add_topic($parent_id, $user_level, $topic_name, $topic_content, $type) {
        $sql = array (
            'mysql' => array (
                "add_topic_index" => "INSERT INTO "._PREFIX."_help_index (`parent_id`,`name`,`user_min_level`,`type`) VALUES (':1',':2',':3',':4')",
                "add_topic_content" => "INSERT INTO "._PREFIX."_help_content (`topic_id`,`content`) VALUES ((SELECT help_id FROM "._PREFIX."_help_index WHERE parent_id=':1' AND name=':2'), ':3')"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["add_topic_index"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["add_topic_content"]);

        $stmt1->execute($parent_id, $topic_name, $user_level, $type);
        $stmt2->execute($parent_id, $topic_name, $topic_content);

        $this->tpl->assign('_MESSAGE', _HELP_ADM_ADD_TOPIC_SUCCESS);
        $this->tpl->assign('_RETURN_LINK', _HELP_ADM_ADD_TOPIC_SUCCESS_RETURN_LINK);

        $this->tpl->display('actions_done.tpl');
    }

    /**
    * Delete a help item
    *
    * Deletes a help item from the database
    *
    * @access public
    * @param integer $topic_id ID of the topic to be deleted
    */
    public function do_delete_topics($topic_id) {
        $sql = array (
            'mysql' => array(
                "delete_topic" => "DELETE FROM "._PREFIX."_help_index WHERE help_id=':1' OR parent_id=':2'",
                "delete_content" => "DELETE FROM "._PREFIX."_help_content WHERE topic_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["delete_topic"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["delete_content"]);

        $stmt1->execute($topic_id, $topic_id);
        $stmt2->execute($topic_id);
    }

    /**
    * Show page to edit a help item
    *
    * This will display a page allowing the admin
    * to edit the help items content and change
    * it as neccessary.
    *
    * @access public
    * @param integer $topic_id ID of the topic to be edited
    */
    public function show_edit_topic($topic_id) {
        $sql = array(
            "mysql" => array(
                'topic_info' => "SELECT * FROM "._PREFIX."_help_index LEFT JOIN "._PREFIX."_help_content ON help_id=topic_id WHERE topic_id = ':1'",
                'topic_list' => "SELECT help_id,name FROM "._PREFIX."_help_index LEFT JOIN "._PREFIX."_help_content ON help_id=topic_id WHERE type='category' ORDER BY name ASC",
                'user_level' => "SELECT DISTINCT(user_level) FROM "._PREFIX."_users ORDER BY user_level ASC"
            )
        );

        $topic = array();
        $topic_list = array();
        $user_level = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["topic_list"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["user_level"]);
        $stmt3 = $this->db->prepare($sql[_DBSYSTEM]["topic_info"]);

        $stmt1->execute();
        $stmt2->execute();
        $stmt3->execute($topic_id);

        $topic_info = $stmt3->fetch_array();

        $topic = array(
            "help_id"	=> $topic_info['help_id'],
            "parent_id"	=> $topic_info['parent_id'],
            "name"		=> htmlentities($topic_info['name'], ENT_QUOTES),
            "user_min_level"=> $topic_info['user_min_level'],
            "type"		=> $topic_info['type'],
            "content"	=> $topic_info['content']
        );

        while ($row = $stmt1->fetch_array()) {
            $topic_list[] = array(
                "help_id" 	=> $row['help_id'],
                "name"		=> $row['name']
            );
        }

        while ($row = $stmt2->fetch_array()) {
            $user_level[] = array(
                "user_level"	=> $row['user_level']
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            '_HELP_ADM_SHOW_TOPICS'		=> _HELP_ADM_SHOW_TOPICS,
            '_HELP_ADM_ADD_TOPIC'		=> _HELP_ADM_ADD_TOPIC,
            '_HELP_ADM_PARENT_CATEGORY'	=> _HELP_ADM_PARENT_CATEGORY,
            '_HELP_ADM_REQUIRED_LEVEL'	=> _HELP_ADM_REQUIRED_LEVEL,
            '_SETTINGS'			=> _SETTINGS,
            '_NONE'				=> _NONE,
            '_NAME'				=> _NAME,
            '_HELP_CONTENT'			=> _HELP_CONTENT,
            '_RESET_FORM'			=> _RESET_FORM,
            '_SAVE_CHGS'			=> _SAVE_CHGS
        ));

        $this->tpl->assign("TOPIC", $topic);
        $this->tpl->assign("TOPIC_LIST", $topic_list);
        $this->tpl->assign("USER_LEVEL", $user_level);

        // Display the page
        $this->tpl->display("help_edit_topic.tpl");
    }

    /**
    * Saves changes made to help item
    *
    * After changes have been made to help items
    * they must be saved back to the database or
    * they will not be displayed. This method
    * saves changes made to the item, back to the
    * database
    *
    * @access public
    * @param integer $parent_id Help ID of the parent of this topic
    * @param integer $user_level User level that is allowed to see this topic
    * @param string $topic_name Name of the help topic
    * @param string $topic_content Content for the help topic
    * @param string $type Type of help item. Whether its a topic or category
    */
    public function do_edit_topic($help_id, $parent_id, $user_level, $topic_name, $topic_content, $type) {
        $sql = array (
            'mysql' => array (
                "edit_topic_index" => "UPDATE "._PREFIX."_help_index SET parent_id=':1',name=':2',user_min_level=':3',type=':4' WHERE help_id=':5'",
                "edit_topic_content" => "UPDATE "._PREFIX."_help_content SET content=':1' WHERE topic_id=':2'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["edit_topic_index"]);
        $stmt2 = $this->db->prepare($sql[_DBSYSTEM]["edit_topic_content"]);

        $stmt1->execute($parent_id, $topic_name, $user_level, $type, $help_id);
        $stmt2->execute($topic_content, $help_id);

        $this->tpl->assign('_MESSAGE', _HELP_ADM_ADD_TOPIC_SUCCESS);
        $this->tpl->assign('_RETURN_LINK', _HELP_ADM_ADD_TOPIC_SUCCESS_RETURN_LINK);

        $this->tpl->display('actions_done.tpl');

    }

    /**
    * Display the feedback page
    *
    * Users can leave feedback for the admins
    * This will access the dropbox that contains
    * all feedback that has been left.
    *
    * @access public
    */
    public function show_feedback() {
        $sql = array (
            "mysql" => array(
                "feedback" => "SELECT feedback_id, date, email, short_desc,status FROM "._PREFIX."_feedback ORDER BY date DESC",
            )
        );
        $feedback = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["feedback"]);

        $stmt1->execute();

        while ($row = $stmt1->fetch_array()) {
            $feedback[] = array (
                'feedback_id' 	=> $row['feedback_id'],
                'date'		=> strftime("%m-%d-%Y at %I:%M %p", $row['date']),
                'email'		=> $row['email'],
                'desc'		=> $row['short_desc'],
                'status'	=> $row['status']
            );
        }

        // Assign language constants
        $this->tpl->assign(array(
            "_HELP_ADM_SHOW_TOPICS"	=> _HELP_ADM_SHOW_TOPICS,
            "_HELP_ADM_ADD_TOPIC"	=> _HELP_ADM_ADD_TOPIC,
            "_SETTINGS"		=> _SETTINGS,
            "_WITH_SELECTED"	=> _WITH_SELECTED,
            "_NO_ACTIONS"		=> _NO_ACTIONS
        ));

        // Assign dynamic content
        $this->tpl->assign("FEEDBACK", $feedback);
        $this->tpl->assign("JS_INC", "help_feedback.tpl");

        $this->tpl->display('help_feedback.tpl');
    }

    /**
    * Deletes a feedback item
    *
    * Feedback items may begin to build up, so
    * this method provides the ability to delete
    * them from the system.
    *
    * @access public
    * @param integer $feedback_id ID of the feedback item that is to be deleted
    */
    public function do_delete_feedback($feedback_id) {
        $sql = array (
            'mysql' => array(
                "delete_feedback" => "DELETE FROM "._PREFIX."_feedback WHERE feedback_id=':1'",
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["delete_feedback"]);

        $stmt1->execute($feedback_id);
    }

    /**
    * Read a feedback item
    *
    * Because the feedback page only displays a
    * short excerpt from the feedback item, to
    * read the whole feedback item, you must
    * click on it. This will fetch back all the
    * feedback info so it can be displayed
    *
    * @access public
    * @param integer $feedback_id ID of the feedback item to be read
    */
    public function do_read_feedback($feedback_id) {
        $sql = array(
            "mysql" => array(
                "feedback" => "SELECT `date`,`email`,`ip`,`short_desc`,`content` FROM "._PREFIX."_feedback WHERE `feedback_id`=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["feedback"]);

        $stmt1->execute($feedback_id);

        $result = $stmt1->fetch_array();

        $this->tpl->assign(array(
            'DATE'		=> strftime("%m-%d-%Y at %I:%M %p", $result['date']),
            'EMAIL'		=> $result['email'],
            'SHORT_DESC'	=> htmlentities($result['short_desc'], ENT_QUOTES),
            'CONTENT'	=> htmlentities($result['content'], ENT_QUOTES)
        ));

        $this->tpl->display('help_feedback_read.tpl');
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

    /**
    * Sends an email
    *
    * Send an email to a specified SMTP server.
    *
    * @access public
    * @param string $recipient Receipient to send mail to
    * @param string $subject Subject of the email
    * @param string $body Body of the email
    */
    public function do_write_email($from, $recipient, $subject, $body) {
        $headers["From"]    	= $from;
        $headers["To"]      	= $recipient;
        $headers["Subject"] 	= $subject;
        $params["host"] 	= $this->cfg['help_mail_server'];
        $params["port"] 	= $this->cfg['help_mail_port'];

        // Create the mail object using the Mail::factory method
        $mail_object =& Mail::factory("smtp", $params);
        $mail_object->send($recipient, $headers, $body);
    }

    /**
    * Displays the reply page for selected feedback
    *
    * This method will display the reply page for
    * each feedback item that was selected. Multiple
    * items can be selected and multiple reply forms
    * will thus be generated.
    *
    * @access public
    * @param array $feedback_id Array of feedback IDs to pull info of
    */
    public function show_reply_feedback($feedback_id) {
        $sql = array(
            "mysql" => array(
                "feedback" => "SELECT * FROM "._PREFIX."_feedback WHERE feedback_id=':1'"
            )
        );
        $feedback = array();

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]["feedback"]);

        foreach ($feedback_id as $key => $val) {
            $stmt1->execute($val);

            $row = $stmt1->fetch_assoc();

            $data = array(
                'id'	=> $row['feedback_id'],
                'to'	=> $row['email'],
                'subj'	=> "Re: ".$row['short_desc'],
                'orig'	=> $row['content'],
            );

            array_push($feedback, $data);
        }

        $this->tpl->assign(array(
            'FEEDBACK'		=> $feedback,
            'FROM'			=> import_var('org_email', 'S'),
            '_RESET_FORM'		=> _RESET_FORM,
            '_SETTINGS'		=> _SETTINGS,
            '_HELP_ADM_SHOW_TOPICS'	=> _HELP_ADM_SHOW_TOPICS,
            '_HELP_ADM_ADD_TOPIC'	=> _HELP_ADM_ADD_TOPIC
        ));

        $this->tpl->display('help_feedback_reply.tpl');
    }

    /**
    * Marks a feedback message as 'read'
    *
    * To differentiate between messages, feedback is marked as
    * unread when it is received and then can be marked read
    * after it is read. This method will mark the feedback
    * message as 'read'
    *
    * @access public
    * @param integer $feedback_id ID of the feedback message to mark as read
    */
    public function do_mark_read($feedback_id) {
        $sql = array(
            "mysql" => array(
                "update" => "UPDATE "._PREFIX."_feedback SET status='R' WHERE feedback_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['update']);

        $stmt1->execute($feedback_id);
    }

    /**
    * Marks a feedback message as 'unread'
    *
    * To differentiate between messages, feedback is marked as
    * unread when it is received and then can be marked read
    * after it is read. This method will mark the feedback
    * message as 'unread'
    *
    * @access public
    * @param integer $feedback_id ID of the feedback message to mark as unread
    */
    public function do_mark_unread($feedback_id) {
        $sql = array(
            "mysql" => array(
                "update" => "UPDATE "._PREFIX."_feedback SET status='U' WHERE feedback_id=':1'"
            )
        );

        $stmt1 = $this->db->prepare($sql[_DBSYSTEM]['update']);

        $stmt1->execute($feedback_id);
    }
}

?>
