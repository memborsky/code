<?php

$ext_sql = array (
	"mysql" => array (
		'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
		'update' => "UPDATE "._PREFIX."_extensions SET displayed_name=':1',admin_displayed_name=':2',image=':3',user_min_level=':4',display_order=':5',enabled=':6',visible=':7' WHERE name=':8'",
		)
);

$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['update']);

$stmt1->execute('use_auth', '1', 'Use authentication in Flare', $extension_id);
$stmt1->execute('auth_type', 'db', 'Default authentication type', $extension_id);

$stmt2->execute('Authentication','Authentication','extensions/Authentication/authentication.png',1000,1,1,0,$extension);

?>
