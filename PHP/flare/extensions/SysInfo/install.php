<?php

$ext_sql = array (
	"mysql" => array (
		'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
		'update' => "UPDATE "._PREFIX."_extensions SET image=':1',user_min_level=':2',display_order=':3',enabled=':4',visible=':5' WHERE name=':6'",
		)
);

$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['update']);

$stmt1->execute('sensor_program', '', '', $extension_id);
$stmt1->execute('show_mount_point', 'TRUE', '', $extension_id);
$stmt1->execute('sysinfo_version', '2.3', 'SysInfo extension version', $extension_id);

$stmt2->execute('extensions/SysInfo/sysinfo.png',100,1,1,0,$extension);

?>
