<?php

// Create SQL array of all SQL we'll run
$ext_sql = array (
	"mysql" => array (
		'update' => "UPDATE "._PREFIX."_extensions SET displayed_name=':1',admin_displayed_name=':2',image=':3',user_min_level=':4',display_order=':5',enabled=':6',visible=':7' WHERE name=':8'",
		)
);

// Prepare all SQL that will run
$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['update']);

// Execute SQL to update the extensions properties
$stmt1->execute('Settings', 'Settings', 'extensions/Settings/settings.png',100,1,1,0,$extension);

?>
