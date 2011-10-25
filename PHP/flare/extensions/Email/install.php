<?php

$ext_sql = array (
	"mysql" => array (
		'update' => "UPDATE "._PREFIX."_extensions SET image=':1',user_min_level=':2',display_order=':3',enabled=':4',visible=':5' WHERE name=':6'",
		'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
		'table_list_info' => "
			CREATE TABLE IF NOT EXISTS `"._PREFIX."_mailing_list_info` (
			`list_id` int(11) NOT NULL auto_increment,
			`list_name` varchar(255) NOT NULL,
			`creation_date` int(11) NOT NULL,
			PRIMARY KEY  (`list_id`),
			UNIQUE KEY `list_name` (`list_name`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;",
		'table_mailing_lists' => "CREATE TABLE `"._PREFIX."_mailing_lists` (
			`list_id` int(11) NOT NULL,
			`user_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;"
	)
);

$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['update']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt3 = $db->prepare($ext_sql[_DBSYSTEM]['table_list_info']);
$stmt4 = $db->prepare($ext_sql[_DBSYSTEM]['table_mailing_lists']);

$stmt3->execute();

$stmt1->execute('extensions/Email/email.png',100,4,1,1,$extension);

$stmt2->execute('mail_from', "noreply@localhost.localdomain", 'Default address that the email will be sent from', $extension_id);
$stmt2->execute('mail_server', "localhost.localdomain", 'Default SMTP server to send mail to', $extension_id);
$stmt2->execute('mail_port', "25", 'Port that the SMTP is listening for mail on.', $extension_id);

?>
