<?php

$ext_sql = array (
	"mysql" => array (
		'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
		'update' => "UPDATE "._PREFIX."_extensions SET image=':1',user_min_level=':2',display_order=':3',enabled=':4',visible=':5' WHERE name=':6'",
		'table_incentives' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_incentives` (
			`incentive_id` int(11) NOT NULL auto_increment,
			`name` varchar(255) NOT NULL default '',
			PRIMARY KEY  (`incentive_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1"
		)
);

$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['table_incentives']);
$stmt3 = $db->prepare($ext_sql[_DBSYSTEM]['update']);

$stmt2->execute();

$stmt3->execute('extensions/Incentives/incentives.png',100,4,1,1,$extension);

?>
