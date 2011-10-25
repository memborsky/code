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

$ext_sql = array (
	"mysql" => array (
		'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
		'update' => "UPDATE "._PREFIX."_extensions SET displayed_name=':1',admin_displayed_name=':2',image=':3',user_min_level=':4',display_order=':5',enabled=':6',visible=':7' WHERE name=':8'",
		'table_reporting' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_reporting` (
			`report_id` int(11) NOT NULL auto_increment,
			`name` varchar(64) NOT NULL default '',
			`description` varchar(255) NOT NULL default '',
			PRIMARY KEY (`report_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1",
		'table_messages' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_messages` (
			`mesg_id` int(11) NOT NULL auto_increment,
			`subject` varchar(255) NOT NULL,
			`content` text NOT NULL,
			`date` int(11) NOT NULL default '0',
			`author_id` int(11) NOT NULL default '0',
			PRIMARY KEY  (`mesg_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1"
		)
);

$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['table_reporting']);
$stmt4 = $db->prepare($ext_sql[_DBSYSTEM]['table_messages']);
$stmt5 = $db->prepare($ext_sql[_DBSYSTEM]['update']);

$stmt1->execute('use_reporting', '1', 'Statistics reporting', $extension_id);
$stmt1->execute('report_path', 'extensions/Reporting_System/reports/', 'Path to reports directory (relative to Flare index.php file)', $extension_id);

$stmt2->execute();
$stmt4->execute();

// Execute SQL to update extensions properties
$stmt5->execute('Reporting System','Reporting System','extensions/Reporting_System/reporting.png',100,1,1,0,$extension);

?>
