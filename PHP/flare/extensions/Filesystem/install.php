<?php

// Set up the SQL arrays for all the SQL we'll run
$ext_sql = array (
	"mysql" => array (
		'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
		'update' => "UPDATE "._PREFIX."_extensions SET displayed_name=':1',admin_displayed_name=':2',image=':3',user_min_level=':4',display_order=':5',enabled=':6',visible=':7' WHERE name=':8'",
		'table_bookmarks' => "CREATE TABLE IF NOT EXISTS `flare_bookmarks` (
			`bookmark_id` int(11) NOT NULL auto_increment,
			`user_id` int(11) NOT NULL default '0',
			`link` text NOT NULL,
			`name` varchar(255) NOT NULL default '',
			`description` varchar(255) NOT NULL default '',
			PRIMARY KEY  (`bookmark_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1",
		'table_permissions' => "CREATE TABLE IF NOT EXISTS `flare_file_permissions` (
			`file_id` bigint(20) NOT NULL auto_increment,
			`file` text NOT NULL,
			`permissions` varchar(10) NOT NULL default '-rw-rw-r--',
			`owner_id` varchar(32) NOT NULL default '0',
			`group_id` varchar(32) NOT NULL default '',
			PRIMARY KEY  (`file_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;"
		)
);

// Prepare all the SQL we will run
$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['table_bookmarks']);
$stmt3 = $db->prepare($ext_sql[_DBSYSTEM]['update']);
$stmt4 = $db->prepare($ext_sql[_DBSYSTEM]['table_permissions']);

// Create the bookmarks table
$stmt2->execute();
$stmt4->execute();

// Setup all config options
$stmt1->execute('max_ul_size', '100', 'Maximum file upload size (MB)', $extension_id);
$stmt1->execute('use_quotas', '1', 'Use Flare Filesystem Quotas', $extension_id);
$stmt1->execute('tmp_dir', '/tmp/', 'Temporary directory to use for file operations', $extension_id);
$stmt1->execute('website_base', "http://" . $_SERVER['HTTP_HOST'] . "/~{USERNAME}", 'Default webroot for user. {USERNAME} is replaced with the users name during directory listing', $extension_id);
$stmt1->execute('file_permissions', '-rw-rw----', 'Default permissions applied to newly created files', $extension_id);
$stmt1->execute('directory_permissions', 'drwxr-xr-x', 'Default permissions applied to newly created directories', $extension_id);

// Run SQL to update extensions properties
$stmt3->execute('My Files','Filesystem','extensions/Filesystem/filesystem.png',100,2,1,1,$extension);

?>
