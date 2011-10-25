<?php

// Create SQL array of all SQL we'll run
$ext_sql = array (
	"mysql" => array (
		'update' => "UPDATE "._PREFIX."_extensions SET image=':1',user_min_level=':2',display_order=':3',enabled=':4',visible=':5' WHERE name=':6'"
		)
);

// Prepare all SQL that will run
$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['update']);

// Execute SQL to update the extensions properties
$stmt1->execute('extensions/Feeds/feeds.png',100,1,1,0,$extension);

?>
