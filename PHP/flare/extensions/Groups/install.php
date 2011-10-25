<?php
/**
* @package Groups
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
		'table_group_info' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_group_info` (
			`group_id` varchar(32) NOT NULL default '0',
			`admin_id` int(11) NOT NULL default '0',
			`group_name` varchar(64) NOT NULL default '0',
			`group_type` int(11) NOT NULL default '1',
			`creation_date` date NOT NULL default '0000-00-00',
			`home_dir` varchar(255) NOT NULL default '',
			`quota_total` int(11) NOT NULL default '0',
			`quota_used` int(11) NOT NULL default '0',
			PRIMARY KEY  (`group_id`),
			UNIQUE KEY `group_id` (`group_name`),
			UNIQUE KEY `home_dir` (`home_dir`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1",
		'table_groups' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_groups` (
			`user_id` int(11) NOT NULL default '0',
			`group_id` char(32) NOT NULL default '0'
			) ENGINE=MyISAM DEFAULT CHARSET=latin1",
		'table_invites' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_invites` (
			`invite_id` int(11) NOT NULL auto_increment,
			`from_user_id` int(11) NOT NULL default '0',
			`to_user_id` int(11) NOT NULL default '0',
			`group_id` char(32) NOT NULL default '0',
			PRIMARY KEY  (`invite_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1"
		)
);

$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['table_group_info']);
$stmt3 = $db->prepare($ext_sql[_DBSYSTEM]['table_groups']);
$stmt4 = $db->prepare($ext_sql[_DBSYSTEM]['table_invites']);
$stmt5 = $db->prepare($ext_sql[_DBSYSTEM]['update']);

$stmt2->execute();
$stmt3->execute();
$stmt4->execute();

// Execute SQL to update extensions properties
$stmt5->execute('My Groups','Groups','extensions/Groups/groups.png',100,3,1,1,$extension);

?>
