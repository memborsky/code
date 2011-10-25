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

/* Logging admin page specific texts */
define ("_LOG_CLEARLOG_SUCCESS", "Successfully cleared Flare's system log");
define ("_LOG_CLEARLOG_FAILURE", "Failed to clear Flare's system log");
define ("_LOG_RETURN_LINK", "<a href='admin.php?extension=Logging&amp;action=show_log'>Return to Logging</a>");
define ("_LOG_CONFIG_TYPE_RETURN_LINK", "<a href='admin.php?extension=Logging&amp;action=show_log_type_actions'>Return to Configuring Log Types</a>");
define ("_LOG_LEAST_ONE_TYPE", "You must select at least one log type to remove!");
define ("_LOG_DELETE_LOG_TYPE_SUCCESS", "Successfully removed log type and all log entries with selected types!");
define ("_LOG_SHOW_LOG", "Show Log");
define ("_LOG_CLEAR_LOG", "Clear Log");
define ("_LOG_CONFIG_LOG_TYPES", "Configure Log Types");
define ("_LOG_EDIT_LOG_TYPE_SUCCESS", "Successfully updated Log Type Entries");
define ("_LOG_SHOW_TYPES", "Show All Log Types");
define ("_LOG_ADD_NEW_TYPE", "Add New Log Type");
define ("_LOG_TYPE", "Log Type");
define ("_LOG_ADD_TYPE", "Add Type");
define ("_LOG_CONTENT", "Content");

?>
