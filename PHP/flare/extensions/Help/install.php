<?php

$ext_sql = array (
	"mysql" => array (
		'update' => "UPDATE "._PREFIX."_extensions SET image=':1',user_min_level=':2',display_order=':3',enabled=':4',visible=':5' WHERE NAME=':6'",
		'table_help_index' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_help_index` (
					`help_id` int(11) NOT NULL auto_increment,
					`parent_id` int(11) NOT NULL default '0',
					`name` varchar(255) NOT NULL default '',
					`user_min_level` int(11) NOT NULL default '1000',
					`type` varchar(16) NOT NULL default '',
					PRIMARY KEY  (`help_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;",
		'table_help_content' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_help_content` (
					`content_id` int(11) NOT NULL auto_increment,
					`topic_id` int(11) NOT NULL default '0',
					`content` text NOT NULL,
					PRIMARY KEY  (`content_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;",
		'table_feedback' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_feedback` (
			`feedback_id` int(11) NOT NULL auto_increment,
			`date` int(11) NOT NULL default '0',
			`email` varchar(64) NOT NULL default '',
			`ip` varchar(16) NOT NULL default '',
			`short_desc` varchar(255) NOT NULL default '',
			`content` text NOT NULL,
			PRIMARY KEY  (`feedback_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1"
		)
);

$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['update']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['table_help_index']);
$stmt3 = $db->prepare($ext_sql[_DBSYSTEM]['table_help_content']);
$stmt4 = $db->prepare($ext_sql[_DBSYSTEM]['table_feedback']);

// Execute SQL to update extensions properties
$stmt1->execute('extensions/Help/help.png',1000,5,1,1,$extension);

// Create Help tables
$stmt2->execute();
$stmt3->execute();
$stmt4->execute();

?>
