<?php
/**
* @package Jobs
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

// Create SQL array of all SQL we'll run
$ext_sql = array (
	"mysql" => array (
		'update' => "UPDATE "._PREFIX."_extensions SET displayed_name=':1',admin_displayed_name=':2',image=':3',user_min_level=':4',display_order=':5',enabled=':6',visible=':7' WHERE name=':8'",
		'config' => "INSERT INTO "._PREFIX."_config (`name`, `value`, `description`, `extension_id`) VALUES (':1',':2',':3',':4')",
		'table_jobs' => "CREATE TABLE IF NOT EXISTS`"._PREFIX."_jobs` (
			`job_id` int(11) NOT NULL auto_increment,
			`job` varchar(255) NOT NULL default '',
			`last_run` int(11) default NULL,
			`interval` int(11) default NULL,
			PRIMARY KEY  (`job_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;",
		'table_cmd' => "CREATE TABLE IF NOT EXISTS`"._PREFIX."_cmd` (
			`cmd_id` int(11) NOT NULL auto_increment,
			`cmd` varchar(255) NOT NULL default '',
			PRIMARY KEY  (`cmd_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;"
		)
);

$job_dir = dirname(__FILE__) . "/jobs/";

// Prepare all SQL that will run
$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['update']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt3 = $db->prepare($ext_sql[_DBSYSTEM]['table_jobs']);
$stmt4 = $db->prepare($ext_sql[_DBSYSTEM]['table_cmd']);

// Execute SQL to update the extensions properties
$stmt1->execute('Jobs', 'Jobs', 'extensions/Jobs/jobs.png',100,1,1,0,$extension);

$stmt2->execute('use_jobs', '1', 'Determines whether to use the flarecmd cron tool and job tools', $extension_id);
$stmt2->execute('jobs_dir', "$job_dir", 'Default directory to find job files in', $extension_id);

$stmt3->execute();
$stmt4->execute();

?>
