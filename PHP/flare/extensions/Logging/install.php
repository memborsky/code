<?php

$ext_sql = array (
	"mysql" => array (
		'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
		'update' => "UPDATE "._PREFIX."_extensions SET image=':1',user_min_level=':2',display_order=':3',enabled=':4',visible=':5' WHERE name=':6'",
		'table_log_entries' => "
			CREATE TABLE IF NOT EXISTS `"._PREFIX."_log_entries` (
			`type_id` int(11) NOT NULL auto_increment,
			`type` varchar(64) NOT NULL default '',
			`content` varchar(255) NOT NULL default '',
			`description` text NOT NULL,
			PRIMARY KEY (`type_id`),
			UNIQUE KEY `type` (`type`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1",
		'table_logging' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_logging` (
			`log_id` bigint(20) NOT NULL auto_increment,
			`type` varchar(64) NOT NULL default '',
			`timestamp` int(11) NOT NULL default '0',
			`ip` varchar(15) NOT NULL default '',
			`contents` text NOT NULL,
			PRIMARY KEY  (`log_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1",
		'log_entry' => "INSERT INTO `flare_log_entries` (`type`,`content`) VALUES (':1', ':2')"
		)
);

$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['table_log_entries']);
$stmt3 = $db->prepare($ext_sql[_DBSYSTEM]['table_logging']);
$stmt4 = $db->prepare($ext_sql[_DBSYSTEM]['log_entry']);
$stmt5 = $db->prepare($ext_sql[_DBSYSTEM]['update']);

$stmt1->execute('use_logging', '1', 'System Logging', $extension_id);
$stmt1->execute('mode', 'sliding', 'The type of paging, either \'Sliding\' or \'Jumping\'', $extension_id);
$stmt1->execute('perPage', '30', 'Number of log entries per page', $extension_id);
$stmt1->execute('delta', '3', 'Number of pages to show before and after the current page', $extension_id);

$stmt2->execute();
$stmt3->execute();

// Run SQL to update extensions properties
$stmt5->execute('extensions/Logging/logging.png',100,1,1,0,$extension);

$stmt4->execute('DEFAULT', 'Activity logged - :1');
$stmt4->execute('PAGE_SURF', ':1;:2');
$stmt4->execute('LOGIN_SUCCESS', ':1;:2');
$stmt4->execute('LOGOUT', ':1;:2');
$stmt4->execute('FILE_UPLOAD_SUCCESS', ':1');
$stmt4->execute('FILE_UPLOAD_NEW', ':1');
$stmt4->execute('FILE_UPLOAD_FAILURE', ':1');
$stmt4->execute('FILE_DOWNLOAD', ':1;:2');
$stmt4->execute('FILE_CUT', ':1;:2');
$stmt4->execute('FILE_COPY', ':1;:2');
$stmt4->execute('FILE_DELETE', ':1;:2');
$stmt4->execute('FILE_ZIP', '');
$stmt4->execute('FILE_ZIP_EXTRACT', '');
$stmt4->execute('MAKE_NEW_DIR', ':1');
$stmt4->execute('ACCT_INFO_CHANGE', '');
$stmt4->execute('PASSWORD_CHANGE', '');
$stmt4->execute('BOOKMARK_ADD', ':1;:2');
$stmt4->execute('BOOKMARK_EDIT', '');
$stmt4->execute('GROUP_ADD_NEW', ':1;:2');
$stmt4->execute('GROUP_EDIT', '');
$stmt4->execute('USER_INVITE', '');
$stmt4->execute('CHANGE_DIR', 'Account with ID :1 changed directories to :2 from :3');
$stmt4->execute('CREATE_ACCOUNT', 'Account with ID :1 was deleted by %user%');
$stmt4->execute('DELETE_ACCOUNT', 'Account with ID :1 was deleted by %user%');
$stmt4->execute('ACTIVATE_ACCOUNT', 'Account with ID :1 was activated by %user%');
$stmt4->execute('DEACTIVATE_ACCOUNT', 'Account with ID :1 was deactivated by %user%');
$stmt4->execute('LOG_CLEAR_LOG', ':1;:2');
$stmt4->execute('NEW_EXT_NO_INSTALL_SCRIPT', ':1');
$stmt4->execute('GROUP_DELETE', ':1');
$stmt4->execute('LOGIN_FAILURE', ':1;:2');

?>
