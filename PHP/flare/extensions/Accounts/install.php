<?php

global $lang;

$ext_sql = array (
	"mysql" => array (
		'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
		'guest' => "INSERT INTO `"._PREFIX."_users` VALUES (2, 'guest', 'guest', '', '', 'm', 0, 0, '', 0, '', NULL, '', '', '', 0, 0, 'db', '', '1000', 'D', '0', '0', '0')",
		'country' => "INSERT INTO `"._PREFIX."_countries` (`major`, `minor`) VALUES (':1', ':2')",
		'update' => "UPDATE "._PREFIX."_extensions SET displayed_name=':1',admin_displayed_name=':2',image=':3',user_min_level=':4',display_order=':5',enabled=':6',visible=':7' WHERE name=':8'",
		'table_users' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_users` (
				`user_id` int(11) NOT NULL auto_increment,
				`username` varchar(32) NOT NULL default '',
				`password` varchar(32) NOT NULL default '',
				`fname` varchar(32) NOT NULL default '',
				`lname` varchar(32) NOT NULL default '',
				`gender` enum('m','f') NOT NULL default 'm',
				`age` tinyint(4) NOT NULL default '0',
				`country` int(11) NOT NULL default '0',
				`occupation` varchar(64) NOT NULL default '',
				`student_id` int(11) NOT NULL default '0',
				`org_email` varchar(64) NOT NULL default '',
				`alternate_email` varchar(64) default NULL,
				`home_dir` varchar(255) NOT NULL default '',
				`group_dir` varchar(255) NOT NULL default '',
				`theme` varchar(32) NOT NULL default '',
				`register_date` int(11) NOT NULL default '0',
				`last_login` int(11) NOT NULL default '0',
				`auth_type` varchar(8) NOT NULL default 'db',
				`admin_flags` text,
				`user_level` varchar(5) NOT NULL default '100',
				`status` enum('A','P','D') NOT NULL default 'D',
				`public` char(1) NOT NULL default '1',
				`quota_total` int(11) NOT NULL default '1073741824',
				`quota_used` int(11) NOT NULL default '0',
				PRIMARY KEY  (`user_id`),
				UNIQUE KEY `email` (`org_email`),
				UNIQUE KEY `username` (`username`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;",
		'table_countries' => "CREATE TABLE IF NOT EXISTS `"._PREFIX."_countries` (
				`country_id` int(10) unsigned NOT NULL auto_increment,
				`major` varchar(64) NOT NULL default '',
				`minor` varchar(255) NOT NULL default '',
				PRIMARY KEY  (`country_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1"
		)
);

// Prepare all SQL that will run
$stmt1 = $db->prepare($ext_sql[_DBSYSTEM]['config']);
$stmt2 = $db->prepare($ext_sql[_DBSYSTEM]['guest']);
$stmt3 = $db->prepare($ext_sql[_DBSYSTEM]['country']);
$stmt4 = $db->prepare($ext_sql[_DBSYSTEM]['table_users']);
$stmt5 = $db->prepare($ext_sql[_DBSYSTEM]['table_countries']);
$stmt6 = $db->prepare($ext_sql[_DBSYSTEM]['update']);

// Make user table
$stmt4->execute();

// Make countries table
$stmt5->execute();

// Run SQL to update extensions properties
$stmt6->execute('My Account','Accounts','extensions/Accounts/accounts.png',100,1,1,1,$extension);

// Set up config options for extension
$stmt1->execute('language', $lang, 'Default language', $extension_id);
$stmt1->execute('template', 'default', 'Default template', $extension_id);
$stmt1->execute('idle_timeout', '1800', 'Amount of time in seconds before user is automatically logged out for being idle', $extension_id);
$stmt1->execute('default_quota_size', '1073741824', 'The default quota size that should be assigned to a user', $extension_id);

// Create the guest account
$stmt2->execute();

// Populate the countries table
$stmt3->execute('Common Countries', 'Australia');
$stmt3->execute('Common Countries', 'Canada');
$stmt3->execute('Common Countries', 'France');
$stmt3->execute('Common Countries', 'Japan');
$stmt3->execute('USA', 'Alabama');
$stmt3->execute('USA', 'Alaska');
$stmt3->execute('USA', 'Arizona');
$stmt3->execute('USA', 'Arkansas');
$stmt3->execute('USA', 'California');
$stmt3->execute('USA', 'Colorado');
$stmt3->execute('USA', 'Connecticut');
$stmt3->execute('USA', 'Delaware');
$stmt3->execute('USA', 'Florida');
$stmt3->execute('USA', 'Georgia');
$stmt3->execute('USA', 'Hawaii');
$stmt3->execute('USA', 'Idaho');
$stmt3->execute('USA', 'Illinois');
$stmt3->execute('USA', 'Indiana');
$stmt3->execute('USA', 'Iowa');
$stmt3->execute('USA', 'Kansas');
$stmt3->execute('USA', 'Kentucky');
$stmt3->execute('USA', 'Louisiana');
$stmt3->execute('USA', 'Maine');
$stmt3->execute('USA', 'Maryland');
$stmt3->execute('USA', 'Massachusets');
$stmt3->execute('USA', 'Michigan');
$stmt3->execute('USA', 'Minnesota');
$stmt3->execute('USA', 'Mississippi');
$stmt3->execute('USA', 'Missouri');
$stmt3->execute('USA', 'Montana');
$stmt3->execute('USA', 'Nebraska');
$stmt3->execute('USA', 'Nevada');
$stmt3->execute('USA', 'New Hampshire');
$stmt3->execute('USA', 'New Jersey');
$stmt3->execute('USA', 'New Mexico');
$stmt3->execute('USA', 'New York');
$stmt3->execute('USA', 'North Carolina');
$stmt3->execute('USA', 'North Dakota');
$stmt3->execute('USA', 'Ohio');
$stmt3->execute('USA', 'Oklahoma');
$stmt3->execute('USA', 'Oregon');
$stmt3->execute('USA', 'Pennsylvania');
$stmt3->execute('USA', 'Rhode Island');
$stmt3->execute('USA', 'Pennsylvania');
$stmt3->execute('USA', 'Rhode Island');
$stmt3->execute('USA', 'South Carolina');
$stmt3->execute('USA', 'South Dakota');
$stmt3->execute('USA', 'Tennessee');
$stmt3->execute('USA', 'Texas');
$stmt3->execute('USA', 'Utah');
$stmt3->execute('USA', 'Vermont');
$stmt3->execute('USA', 'Virginia');
$stmt3->execute('USA', 'Washington');
$stmt3->execute('USA', 'Washington, D.C.');
$stmt3->execute('USA', 'West Virginia');
$stmt3->execute('USA', 'Wisconsin');
$stmt3->execute('USA', 'Wyoming');
$stmt3->execute('All Countries', 'Afghanistan');
$stmt3->execute('All Countries', 'Albania');
$stmt3->execute('All Countries', 'Algeria');
$stmt3->execute('All Countries', 'American Samoa');
$stmt3->execute('All Countries', 'Andorra');
$stmt3->execute('All Countries', 'Angola');
$stmt3->execute('All Countries', 'Anguilla');
$stmt3->execute('All Countries', 'Antigua');
$stmt3->execute('All Countries', 'Argentina');
$stmt3->execute('All Countries', 'Armenia');
$stmt3->execute('All Countries', 'Aruba');
$stmt3->execute('All Countries', 'Ascension Is');
$stmt3->execute('All Countries', 'Austria');
$stmt3->execute('All Countries', 'Azurbaijan');
$stmt3->execute('All Countries', 'Bahamas');
$stmt3->execute('All Countries', 'Bahrain');
$stmt3->execute('All Countries', 'Bangladesh');
$stmt3->execute('All Countries', 'Barbados');
$stmt3->execute('All Countries', 'Barbuda');
$stmt3->execute('All Countries', 'Belarus');
$stmt3->execute('All Countries', 'Belgium');
$stmt3->execute('All Countries', 'Belize');
$stmt3->execute('All Countries', 'Benin');
$stmt3->execute('All Countries', 'Bermuda');
$stmt3->execute('All Countries', 'Bhutan');
$stmt3->execute('All Countries', 'Bolivia');
$stmt3->execute('All Countries', 'Bosnia');
$stmt3->execute('All Countries', 'Botswana');
$stmt3->execute('All Countries', 'Brazil');
$stmt3->execute('All Countries', 'British Indian Ocean Territory');
$stmt3->execute('All Countries', 'British VI');
$stmt3->execute('All Countries', 'Brunei Darussalam');
$stmt3->execute('All Countries', 'Bulgaria');
$stmt3->execute('All Countries', 'Burkina Faso');
$stmt3->execute('All Countries', 'Burundi');
$stmt3->execute('All Countries', 'Cambodia');
$stmt3->execute('All Countries', 'Cameroon');
$stmt3->execute('All Countries', 'Cape Verde Is');
$stmt3->execute('All Countries', 'Cayman Is');
$stmt3->execute('All Countries', 'Central African Rep');
$stmt3->execute('All Countries', 'Chad');
$stmt3->execute('All Countries', 'Chile');
$stmt3->execute('All Countries', 'China');
$stmt3->execute('All Countries', 'Christmas Is');
$stmt3->execute('All Countries', 'Cocos Is');
$stmt3->execute('All Countries', 'Colombia');
$stmt3->execute('All Countries', 'Comoro Is');
$stmt3->execute('All Countries', 'Congo');
$stmt3->execute('All Countries', 'Cook Is');
$stmt3->execute('All Countries', 'Costa Rica');
$stmt3->execute('All Countries', 'Croatia');
$stmt3->execute('All Countries', 'Cuba');
$stmt3->execute('All Countries', 'Cyprus');
$stmt3->execute('All Countries', 'Czech Rep');
$stmt3->execute('All Countries', 'Denmark');
$stmt3->execute('All Countries', 'Diego Garcia');
$stmt3->execute('All Countries', 'Djibouti');
$stmt3->execute('All Countries', 'Dominica');
$stmt3->execute('All Countries', 'Dominican Rep');
$stmt3->execute('All Countries', 'East Timor');
$stmt3->execute('All Countries', 'Ecuador');
$stmt3->execute('All Countries', 'Egypt');
$stmt3->execute('All Countries', 'El Salvador');
$stmt3->execute('All Countries', 'Equatorial Guinea');
$stmt3->execute('All Countries', 'Eritrea');
$stmt3->execute('All Countries', 'Estonia');
$stmt3->execute('All Countries', 'Ethiopia');
$stmt3->execute('All Countries', 'Faeroe Is');
$stmt3->execute('All Countries', 'Falkland Is');
$stmt3->execute('All Countries', 'Fiji');
$stmt3->execute('All Countries', 'Finland');
$stmt3->execute('All Countries', 'French Guiana');
$stmt3->execute('All Countries', 'French Polynesia');
$stmt3->execute('All Countries', 'Gabon');
$stmt3->execute('All Countries', 'Gambia');
$stmt3->execute('All Countries', 'Georgia');
$stmt3->execute('All Countries', 'Germany');
$stmt3->execute('All Countries', 'Ghana');
$stmt3->execute('All Countries', 'Gibraltar');
$stmt3->execute('All Countries', 'Greece');
$stmt3->execute('All Countries', 'Greenland');
$stmt3->execute('All Countries', 'Grenada');
$stmt3->execute('All Countries', 'Guadeloupe');
$stmt3->execute('All Countries', 'Guam');
$stmt3->execute('All Countries', 'Guatemala');
$stmt3->execute('All Countries', 'Guinea');
$stmt3->execute('All Countries', 'Guinea Bissau');
$stmt3->execute('All Countries', 'Guyana');
$stmt3->execute('All Countries', 'Haiti');
$stmt3->execute('All Countries', 'Honduras');
$stmt3->execute('All Countries', 'Hong Kong');
$stmt3->execute('All Countries', 'Hungary');
$stmt3->execute('All Countries', 'Iceland');
$stmt3->execute('All Countries', 'India');
$stmt3->execute('All Countries', 'Indonesia');
$stmt3->execute('All Countries', 'Iran');
$stmt3->execute('All Countries', 'Iraq');
$stmt3->execute('All Countries', 'Ireland');
$stmt3->execute('All Countries', 'Israel');
$stmt3->execute('All Countries', 'Italy');
$stmt3->execute('All Countries', 'Ivory Coast');
$stmt3->execute('All Countries', 'Jamaica');
$stmt3->execute('All Countries', 'Jordan');
$stmt3->execute('All Countries', 'Kazakhstan');
$stmt3->execute('All Countries', 'Kenya');
$stmt3->execute('All Countries', 'Kiribati');
$stmt3->execute('All Countries', 'Korea North');
$stmt3->execute('All Countries', 'Korea South');
$stmt3->execute('All Countries', 'Kuwait');
$stmt3->execute('All Countries', 'Kyrgyzstan');
$stmt3->execute('All Countries', 'Laos');
$stmt3->execute('All Countries', 'Latvia');
$stmt3->execute('All Countries', 'Lebanon');
$stmt3->execute('All Countries', 'Lesotho');
$stmt3->execute('All Countries', 'Liberia');
$stmt3->execute('All Countries', 'Libya');
$stmt3->execute('All Countries', 'Liechtenstein');
$stmt3->execute('All Countries', 'Lithuania');
$stmt3->execute('All Countries', 'Luxembourg');
$stmt3->execute('All Countries', 'Macau');
$stmt3->execute('All Countries', 'Macedonia');
$stmt3->execute('All Countries', 'Madagascar');
$stmt3->execute('All Countries', 'Malawi');
$stmt3->execute('All Countries', 'Malaysia');
$stmt3->execute('All Countries', 'Maldives');
$stmt3->execute('All Countries', 'Mali');
$stmt3->execute('All Countries', 'Malta');
$stmt3->execute('All Countries', 'Mariana Is');
$stmt3->execute('All Countries', 'Marshall Is');
$stmt3->execute('All Countries', 'Martinique');
$stmt3->execute('All Countries', 'Mauritania');
$stmt3->execute('All Countries', 'Mauritius');
$stmt3->execute('All Countries', 'Mayotte Is');
$stmt3->execute('All Countries', 'Mexico');
$stmt3->execute('All Countries', 'Micronesia');
$stmt3->execute('All Countries', 'Moldova');
$stmt3->execute('All Countries', 'Monaco');
$stmt3->execute('All Countries', 'Mongolia');
$stmt3->execute('All Countries', 'Montserrat');
$stmt3->execute('All Countries', 'Morocco');
$stmt3->execute('All Countries', 'Mozambique');
$stmt3->execute('All Countries', 'Myanmar');
$stmt3->execute('All Countries', 'Namibia');
$stmt3->execute('All Countries', 'Nauru');
$stmt3->execute('All Countries', 'Nepal');
$stmt3->execute('All Countries', 'Netherlands');
$stmt3->execute('All Countries', 'Netherlands Antilles');
$stmt3->execute('All Countries', 'Nevis');
$stmt3->execute('All Countries', 'New Caledonia');
$stmt3->execute('All Countries', 'New Zealand');
$stmt3->execute('All Countries', 'Nicaragua');
$stmt3->execute('All Countries', 'Niger');
$stmt3->execute('All Countries', 'Nigeria');
$stmt3->execute('All Countries', 'Niue Is');
$stmt3->execute('All Countries', 'Norfolk Is');
$stmt3->execute('All Countries', 'Norway');
$stmt3->execute('All Countries', 'Oman');
$stmt3->execute('All Countries', 'Pakistan');
$stmt3->execute('All Countries', 'Palau');
$stmt3->execute('All Countries', 'Panama');
$stmt3->execute('All Countries', 'Papua New Guinea');
$stmt3->execute('All Countries', 'Paraguay');
$stmt3->execute('All Countries', 'Peru');
$stmt3->execute('All Countries', 'Peurto Rico');
$stmt3->execute('All Countries', 'Philippines');
$stmt3->execute('All Countries', 'Poland');
$stmt3->execute('All Countries', 'Portugal');
$stmt3->execute('All Countries', 'Qatar');
$stmt3->execute('All Countries', 'Reunion Is');
$stmt3->execute('All Countries', 'Romania');
$stmt3->execute('All Countries', 'Russia');
$stmt3->execute('All Countries', 'Rwanda');
$stmt3->execute('All Countries', 'S. Georgia &amp; S. Sandwich Isls.');
$stmt3->execute('All Countries', 'Saint Kitts &amp; Nevis Anguilla');
$stmt3->execute('All Countries', 'Saint Lucia');
$stmt3->execute('All Countries', 'Saint Pierre and Miquelon');
$stmt3->execute('All Countries', 'Saint Tome and Principe');
$stmt3->execute('All Countries', 'Saint Vincent &amp; Grenadines');
$stmt3->execute('All Countries', 'San Marino');
$stmt3->execute('All Countries', 'Sao Tome');
$stmt3->execute('All Countries', 'Saudi Arabia');
$stmt3->execute('All Countries', 'Senegal');
$stmt3->execute('All Countries', 'Seychelles');
$stmt3->execute('All Countries', 'Sierra Leone');
$stmt3->execute('All Countries', 'Singapore');
$stmt3->execute('All Countries', 'Slovakia');
$stmt3->execute('All Countries', 'Slovenia');
$stmt3->execute('All Countries', 'Solomon Is');
$stmt3->execute('All Countries', 'Somalia');
$stmt3->execute('All Countries', 'South Africa');
$stmt3->execute('All Countries', 'Spain');
$stmt3->execute('All Countries', 'Sri Lanka');
$stmt3->execute('All Countries', 'St. Helena');
$stmt3->execute('All Countries', 'St. Kitts');
$stmt3->execute('All Countries', 'St. Lucia');
$stmt3->execute('All Countries', 'St. Pierre &amp; Miquelon');
$stmt3->execute('All Countries', 'St. Vincent');
$stmt3->execute('All Countries', 'Sudan');
$stmt3->execute('All Countries', 'Suriname');
$stmt3->execute('All Countries', 'Swaziland');
$stmt3->execute('All Countries', 'Sweden');
$stmt3->execute('All Countries', 'Switzerland');
$stmt3->execute('All Countries', 'Syria');
$stmt3->execute('All Countries', 'Taiwan');
$stmt3->execute('All Countries', 'Tajikistan');
$stmt3->execute('All Countries', 'Tanzania');
$stmt3->execute('All Countries', 'Thailand');
$stmt3->execute('All Countries', 'Togo');
$stmt3->execute('All Countries', 'Tonga');
$stmt3->execute('All Countries', 'Trinidad &amp; Tobago');
$stmt3->execute('All Countries', 'Tunisia');
$stmt3->execute('All Countries', 'Turkey');
$stmt3->execute('All Countries', 'Turkmenistan');
$stmt3->execute('All Countries', 'Turks &amp; Caicos Is');
$stmt3->execute('All Countries', 'Tuvalu');
$stmt3->execute('All Countries', 'Uganda');
$stmt3->execute('All Countries', 'UK');
$stmt3->execute('All Countries', 'Ukraine');
$stmt3->execute('All Countries', 'United Arab Emirates');
$stmt3->execute('All Countries', 'Uruguay');
$stmt3->execute('All Countries', 'USA Minor Outlying Islands');
$stmt3->execute('All Countries', 'US Virgin Islands');
$stmt3->execute('All Countries', 'Uzbekistan');
$stmt3->execute('All Countries', 'Vanuatu');
$stmt3->execute('All Countries', 'Vatican City');
$stmt3->execute('All Countries', 'Venezuela');
$stmt3->execute('All Countries', 'Vietnam');
$stmt3->execute('All Countries', 'Wallis &amp; Futuna');
$stmt3->execute('All Countries', 'Western Samoa');
$stmt3->execute('All Countries', 'Yemen');
$stmt3->execute('All Countries', 'Yugoslavia');
$stmt3->execute('All Countries', 'Zaire');
$stmt3->execute('All Countries', 'Zambia');
$stmt3->execute('All Countries', 'Zimbabwe');

?>
