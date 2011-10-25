<?php
/**
* @package ClamAV
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
		'table_clamscans' => "CREATE TABLE `"._PREFIX."_clamscans` (
			`scan_id` int(11) NOT NULL auto_increment,
			`name` varchar(255) NOT NULL default '',
			`scan_cmd` text NOT NULL,
			`status` enum('P','R','F') NOT NULL default 'P',
			PRIMARY KEY  (`scan_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;",
		'table_clamresults' => "CREATE TABLE `"._PREFIX."_clamresults` (
			`scan_id` int(11) NOT NULL default '0',
			`results` blob NOT NULL,
			PRIMARY KEY  (`scan_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;"
	)
);

// Prepare all SQL that will run
$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['update']);
$stmt3 = $db->prepare($ext_sql[_DBSYSTEM]['table_clamscans']);
$stmt4 = $db->prepare($ext_sql[_DBSYSTEM]['table_clamresults']);

// Set up config options for extension
$stmt1->execute('scan_root', '.', 'Root from which the ClamAV scanner will begin to look for files', $extension_id);
$stmt1->execute('quarentine_dir', '/tmp/clamav_infected', 'Directory to move infected files to. This must be writable if you want to have Clam automatically move infected files', $extension_id);
$stmt1->execute('auto_move_infected', 0, 'Automatically move infected files to quarentine directory', $extension_id);
$stmt1->execute('clamscan_bin', '/usr/local/bin/clamscan', 'Fullpath to clamscan executable', $extension_id);

// Run SQL to update extensions properties
$stmt2->execute('ClamAV','ClamAV','extensions/ClamAV/clamav.png',100,1,0,0,$extension);

$stmt3->execute();
$stmt4->execute();

mkdir('/tmp/clamav_infected');

?>
