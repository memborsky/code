<?php

// Create SQL array of all SQL we'll run
$ext_sql = array(
	"mysql" => array(
		'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
		'update' => "UPDATE "._PREFIX."_extensions SET displayed_name=':1',admin_displayed_name=':2',image=':3',user_min_level=':4',display_order=':5',enabled=':6',visible=':7' WHERE name=':8'",
	)
);

// Prepare all SQL that will run
$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['update']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['config']);

// Execute SQL to update the extension's properties
$stmt1->execute('Xinha', 'Xinha', 'extensions/Xinha/xinha.png', 100, 1,1,0,$extension);

$stmt2->execute('use_xinha', '1', "Specifies whether to use Xinha or use regular textareas", $extension_id);

?>
