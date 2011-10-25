<?php
/**
* @package Language
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/

/**
* Copyright (C) 2004-2005 Tim Rupp
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

/* Help page specific texts */
define ("_HELP_WELCOME", "Flare Help Center");
define ("_HELP_TOPICS", "Topics In This Section");
define ("_HELP_NO_TOPICS", "No Topics Available");
define ("_HELP_SUB_SECTIONS", "Sub Sections");
define ("_HELP_INDEX", "Help Index");
define ("_HELP_CONTENT", "Content");
define ("_HELP_BOTTOM", "At bottom of section");
define ("_HELP_ADM_SHOW_TOPICS", "Show All Topics");
define ("_HELP_ADM_ADD_TOPIC", "Add Topic");
define ("_HELP_ADM_PARENT_CATEGORY", "Parent Category");
define ("_HELP_ADM_TOPIC_NAME", "Topic Name");
define ("_HELP_ADM_TOPIC_CONTENT", "Topic Content");
define ("_HELP_ADM_REQUIRED_LEVEL", "Minimum Required User Level");
define ("_HELP_ADM_DELETE_TOPIC_SUCCESS", "Successfully removed the select topics and their related content");
define ("_HELP_ADM_DELETE_TOPIC_SUCCESS_RETURN_LINK", "<a href='admin.php?extension=Help&amp;action=show_all_topics'>Return to Help Main</a>");
define ("_HELP_ADM_ADD_TOPIC_SUCCESS", "Successfully added Help topic");
define ("_HELP_ADM_ADD_TOPIC_SUCCESS_RETURN_LINK", "<a href='admin.php?extension=Help&amp;action=show_all_topics'>Return to Help Main</a>");
define ("_HELP_ADM_FEEDBACK", "Show Feedback");
define ("_HELP_FEEDBACK", "Feedback");
define ("_HELP_FEEDBACK_MESG", "We welcome feedback from our users. If you wish to let us know how we're doing, "
    . "or if you need to contact us, use the form below.<p />Note that if you are expecting us to reply, you'll "
    . "need to leave a valid email address!<p/>"
    . "Thanks!<br>"
    . "ACM Open Source Committee");
define ("_HELP_FEEDBACK_SHORT", "Short title");
define ("_HELP_FEEDBACK_SEND", "Send Feedback");
define ("_HELP_ADM_SETTINGS_SAVED", "Settings for the Help Extension have been saved.");
define ("_HELP_ADM_SETTINGS_SAVED_RETURN_LINK", "<a href='admin.php?extension=Help'>Return to Help Admin</a>");

?>
