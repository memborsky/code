<?php
/**
* Copyright (C) 2004-2005 Indiana Tech Open Source Committee
* Please direct all questions and comments to TARupp01@indianatech.net
*
* This program is free software; you can redistribute it and/or modify it under the terms of
* the GNU General Public License as published by the Free Software Foundation; either version
* 2 of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along with this program;
* if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston,
* MA 02111-1307, USA.
*/

function change_sidebar($section = "welcome") {
	switch($section) {
		case "welcome":
			echo "  <p />"
			. "<span style='font-weight: bold'>Welcome</span><p />&nbsp;<p />"
			. "Step 1: Database Setup<p />&nbsp;<p />"
			. "Step 2: Install Extensions<p />&nbsp;<p />"
			. "Step 3: Setup Directories<p />&nbsp;<p />"
			. "Step 4: Create Admin User<p />&nbsp;<p />"
			. "Finish Installation";
			break;
		case "database":
			echo "  <p />"
			. "Welcome<p />&nbsp;<p />"
			. "<span style='font-weight: bold'>Step 1: Database Setup</span><p />&nbsp;<p />"
			. "Step 2: Install Extensions<p />&nbsp;<p />"
			. "Step 3: Setup Directories<p />&nbsp;<p />"
			. "Step 4: Create Admin User<p />&nbsp;<p />"
			. "Finish Installation";
			break;
		case "extensions":
			echo "  <p />"
			. "Welcome<p />&nbsp;<p />"
			. "Step 1: Database Setup<p />&nbsp;<p />"
			. "<span style='font-weight: bold'>Step 2: Install Extensions</span><p />&nbsp;<p />"
			. "Step 3: Setup Directories<p />&nbsp;<p />"
			. "Step 4: Create Admin User<p />&nbsp;<p />"
			. "Finish Installation";
			break;
		case "directory":
			echo "  <p />"
			. "Welcome<p />&nbsp;<p />"
			. "Step 1: Database Setup<p />&nbsp;<p />"
			. "Step 2: Install Extensions<p />&nbsp;<p />"
			. "<span style='font-weight: bold'>Step 3: Setup Directories</span><p />&nbsp;<p />"
			. "Step 4: Create Admin User<p />&nbsp;<p />"
			. "Finish Installation";
			break;
		case "admin":
			echo "  <p />"
			. "Welcome<p />&nbsp;<p />"
			. "Step 1: Database Setup<p />&nbsp;<p />"
			. "Step 2: Install Extensions<p />&nbsp;<p />"
			. "Step 3: Setup Directories<p />&nbsp;<p />"
			. "<span style='font-weight: bold'>Step 4: Create Admin User</span><p />&nbsp;<p />"
			. "Finish Installation";
			break;
		case "finished":
			echo "  <p />"
			. "Welcome<p />&nbsp;<p />"
			. "Step 1: Database Setup<p />&nbsp;<p />"
			. "Step 2: Install Extensions<p />&nbsp;<p />"
			. "Step 3: Setup Directories<p />&nbsp;<p />"
			. "Step 4: Create Admin User<p />&nbsp;<p />"
			. "<span style='font-weight: bold'>Finish Installation</span>";
			break;
	}
}

function welcome_database() {
	global $tpl;

	$tpl->assign(array(
		"_WELCOME_DATABASE"		=> _WELCOME_DATABASE,
		"_PLEASE_FILL_OUT"		=> _PLEASE_FILL_OUT,
		"_DB_SERVER"			=> _DB_SERVER,
		"_DB_NAME"			=> _DB_NAME,
		"_USERNAME_CONNECT_WITH"	=> _USERNAME_CONNECT_WITH,
		"_PWRD_FOR_ACCOUNT"		=> _PWRD_FOR_ACCOUNT,
		"_TABLE_PREFIX"			=> _TABLE_PREFIX));

	$tpl->display('database.tpl');
}

function welcome_admin() {
	global $tpl;

	// Assign language constants for page
	$tpl->assign(array(
		"_WELCOME_ADMIN"	=> _WELCOME_ADMIN,
		"_PLEASE_FILL_OUT"	=> _PLEASE_FILL_OUT,
		"_FNAME"		=> _FNAME,
		"_LNAME"		=> _LNAME,
		"_EMAIL"		=> _EMAIL,
		"_ADM_USER"		=> _ADM_USER,
		"_ADM_PWRD"		=> _ADM_PWRD,
		"_AUTH_TYPE"		=> _AUTH_TYPE,
		"_DATABASE"		=> _DATABASE,
		"_KERB"			=> _KERB,
		"_STEP_FINISH"		=> _STEP_FINISH,
		"_ADMIN_BODY"		=> _ADMIN_BODY));

	// Display the page
	$tpl->display('admin_account.tpl');

}

function welcome_finished() {
	global $tpl;

	$tpl->display('finished.tpl');
}

function create_admin($fname, $lname, $email, $username, $password, $auth_type) {
	$password	= md5($password);
	$admin_flags	= '';

	// Require the database class
	require_once('db/mysql.php');
	require_once(ABSPATH.'/config-inc.php');

	// Create a new database object and filesystem object
	$db	= new DB(_DBUSER, _DBPWRD, _DBUSE, _DBSERVER, _DBPORT);

	// Create array of SQL that we will be executing
	$sql = array (
		"mysql" => array (
			'dirs' => "SELECT `value` FROM "._PREFIX."_config WHERE name=':1'",
			'extension_id' => "SELECT `extension_id` FROM "._PREFIX."_extensions",
			'admin_id' => "SELECT `user_id` FROM "._PREFIX."_users WHERE username=':1'",
			'create_admin' => "INSERT INTO `"._PREFIX."_users` (`username`,`password`, `fname`, `lname`, `org_email`, `home_dir`,`group_dir`,`theme`,`register_date`,`auth_type`,`user_level`,`status`,`public`) VALUES (':1',':2',':3',':4',':5',':6',':7',':8',':9',':10',':11',':12','0')",
			'admin_privs' => "INSERT INTO "._PREFIX."_admin_privs(`user_id`,`extension_id`) VALUES (':1',':2')"
			)
	);

	// Prepare all our SQL
	$stmt1 = $db->prepare($sql[_DBSYSTEM]['extension_id']);
	$stmt2 = $db->prepare($sql[_DBSYSTEM]['create_admin']);
	$stmt3 = $db->prepare($sql[_DBSYSTEM]['dirs']);
	$stmt4 = $db->prepare($sql[_DBSYSTEM]['admin_id']);
	$stmt5 = $db->prepare($sql[_DBSYSTEM]['admin_privs']);

	$stmt3->execute('user_dir');
	$home_dir = $stmt3->result(0);

	$stmt3->execute('group_dir');
	$group_dir = $stmt3->result(0);

	// Execute SQL to create admin account
	$stmt2->execute($username,
			$password,
			$fname,
			$lname,
			$email,
			$home_dir,
			$group_dir,
			'default',
			time(),
			$auth_type,
			100,
			'A');

	$stmt4->execute($username);

	$admin_id = $stmt4->result(0);

	$stmt1->execute();

	while ($row = $stmt1->fetch_assoc()) {
		$extension_id = $row['extension_id'];

		$stmt5->execute($admin_id, $extension_id);
	}

	echo "  1";
}

function welcome_extensions() {
	global $tpl;

	$required = array(
		'Accounts',
		'Authentication',
		'Groups',
		'Settings',
		'Filesystem',
		'Error_Handler'
	);
	$req = 0;
	
	if ($handle = opendir('extensions/')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "index.php") {
				foreach ($required as $key => $val) {
					if ($file == $val) {
						$req = 1;
						break;
					}
				}

				$extensions[] = array(
					'name' => $file,
					'req' => $req
				);

				$req = 0;
			}
		}
		closedir($handle);
	}

	$tpl->assign(array(
		'extensions' 		=> $extensions,
		"_STEP_DIR_SETUP"	=> _STEP_DIR_SETUP,
	));

	$tpl->display('extensions.tpl');
}

function check_database() {
	define ("_PREFIX", $prefix);
	define ("_DBSYSTEM", $dbtype);
	define ("_CONNECT_TYPE", "connect");

	require_once('db/mysql.php');

	try {
		$db	= new DB($dbuser, $dbpwrd, $dbname, $dbsrvr, $dbport);
	} catch (Exception $e) {
		$tpl->assign("_MESSAGE", _DB_CONNECT_FAIL);
		$tpl->assign("_RETURN_LINK", _DB_CONNECT_FAIL_RETURN_LINK);

		$tpl->display("actions_done.tpl");
		exit;
	}
}

function install_extension($extension_name) {
	global $db;

	require_once(ABSPATH.'/config-inc.php');
	require('extensions.php');

	// Create a new database object and filesystem object
	$db	= new DB(_DBUSER, _DBPWRD, _DBUSE, _DBSERVER, _DBPORT);

	$ext = new Extensions('extensions/');
	$ext->__set("db", $db);
	$ext->get_installed_extensions();

	$tmp = $ext->__get("installed_extensions");
	array_push($tmp, $extension_name);
	sort($tmp);
	$ext->__set("extension_list", $tmp);

	$ext->add_new_extensions();

	if ($ext->__get("install_result")) {
		echo "  1;$extension_name";
	} else {
		echo "  0;$extension_name";
	}

	unset($ext);
	unset($db);
}

function welcome_main() {
	$tpl                    = new Smarty;
	$tpl->template_dir      = 'setup/templates';
	$tpl->compile_dir       = 'setup/templates_c';

	$tpl->assign("_WELCOME_INDEX", _WELCOME_INDEX);
	$tpl->assign("_INDEX_BODY", _INDEX_BODY);

	if(!check_version(PHP_VERSION, "5.0.4") ) {
		$tpl->assign('PHP_VERSION_OK', 0);
		$tpl->assign('SETUP_NOT_OK', 0);
	} else {
		$tpl->assign('PHP_VERSION_OK', 1);
		$tpl->assign('SETUP_NOT_OK', 1);
	}

	if (is_writable('.')) {
		$tpl->assign('DIR_WRITABLE', 1);
		$tpl->assign('SETUP_NOT_OK', 0);
	} else {
		$tpl->assign('DIR_WRITABLE', 0);
		$tpl->assign('SETUP_NOT_OK', 1);
	}

	if (is_dir('extensions')) {
		$tpl->assign('EXTENSIONS_DIR', 1);
		$tpl->assign('SETUP_NOT_OK', 0);
	} else {
		$tpl->assign('EXTENSIONS_DIR', 0);
		$tpl->assign('SETUP_NOT_OK', 1);
	}

	$tpl->display('welcome.tpl');
}

function check_version($currentversion, $requiredversion) {
	list($majorC, $minorC, $editC) = split('[/.-]', $currentversion);
	list($majorR, $minorR, $editR) = split('[/.-]', $requiredversion);
  
	if ($majorC > $majorR) return true;
	if ($majorC < $majorR) return false;

	// same major - check ninor
	if ($minorC > $minorR) return true;
	if ($minorC < $minorR) return false;

	// and same minor
	if ($editC  >= $editR)  return true;
	return true;
}

function welcome_directory() {
	global $tpl;

	// Assign language constants for page
	$tpl->assign(array(
		"_WELCOME_DIRECTORY"	=> _WELCOME_DIRECTORY,
		"_DIRECTORY_BODY"	=> _DIRECTORY_BODY,
		"_HOME_ROOT"		=> _HOME_ROOT,
		"_HOME_DIR"		=> _HOME_DIR,
		"_GROUP_ROOT"		=> _GROUP_ROOT,
		"_GROUP_DIR"		=> _GROUP_DIR,
		"_STEP_ADMIN_ACCOUNT"	=> _STEP_ADMIN_ACCOUNT,
		"_PLEASE_FILL_OUT"	=> _PLEASE_FILL_OUT));

	// Display the page
	$tpl->display('directories.tpl');
}

function make_home_root($home_dir) {
	require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');
	require_once(ABSPATH.'/config-inc.php');

	$fs 	= new MyFiles();

	// Create a new database object and filesystem object
	$db	= new DB(_DBUSER, _DBPWRD, _DBUSE, _DBSERVER, _DBPORT);

	$fs->__set("db", $db);

	// If the home_root directory doesnt exist...
	if(!is_dir($home_dir)) {
		// try to make it.
		$fs->do_new_dir($home_dir, '', '','');

		// And if it still doesnt exist after we try to make it...
		if(!is_dir($home_dir)) {
			// then we've hit a snag and need to tell the user
			return 0;
		}
	}

	return 1;
}

function make_group_root($group_dir) {
	require_once(ABSPATH.'/extensions/Filesystem/class.MyFiles.php');
	require_once(ABSPATH.'/config-inc.php');

	$fs 	= new MyFiles();

	// Create a new database object and filesystem object
	$db	= new DB(_DBUSER, _DBPWRD, _DBUSE, _DBSERVER, _DBPORT);

	$fs->__set("db", $db);

	// If the group_root directory doesnt exist...
	if (!is_dir($group_dir)) {
		// try to make it.
		$fs->do_new_dir($group_dir, '', '','');

		// And if it still doesnt exist after we try to make it...
		if (!is_dir($group_dir)) {
			return 0;
		}
	}

	return 1;
}

function save_default_roots($home_dir, $group_dir) {
	require_once(ABSPATH.'/config-inc.php');

	// Create a new database object and filesystem object
	$db	= new DB(_DBUSER, _DBPWRD, _DBUSE, _DBSERVER, _DBPORT);

	// Otherwise, we can work on updating the root dirs. Make the SQL array first
	$sql = array (
		"mysql" => array (
			'ext_id' => "SELECT extension_id FROM "._PREFIX."_extensions WHERE name='Accounts'",
			'config' => "INSERT INTO "._PREFIX."_config (`name`,`value`,`description`,`extension_id`) VALUES (':1',':2',':3',':4')",
			)
	);

	// Prepare all the SQL we're going to run
	$stmt1 = $db->prepare($sql[_DBSYSTEM]['ext_id']);
	$stmt2 = $db->prepare($sql[_DBSYSTEM]['config']);

	$stmt1->execute();
	$extension_id = $stmt1->result(0);

	$stmt2->execute('user_dir', $home_dir, 'Default home directory that user is placed in', $extension_id);
	$stmt2->execute('group_dir', $group_dir, 'Default group directory that users groups will be placed in', $extension_id);

	return 1;
}

function remove_base_conf() {
	$base_conf 	= "config-inc.php.dist";

	if (file_exists($base_conf)) {
		@unlink($base_conf);

		if (file_exists($base_conf))
			return 0;
		else
			return 1;
	}

	return 1;
}

function write_conf($dbsrvr,$dbuser,$dbpwrd,$dbname) {
	$real_conf 	= "config-inc.php";

	$config_data = '<?php
	/**
	* Database Information. Fill this out as per your server configuration
	*/
		define ("_DBSERVER",	"'.$dbsrvr.'");
		define ("_DBPORT", 	3306);
		define ("_DBUSER", 	"'.$dbuser.'");
		define ("_DBPWRD", 	"'.$dbpwrd.'");
		define ("_DBUSE", 	"'.$dbname.'");
		define ("_PREFIX", 	"flare");

	/**
	* Database system that you are using. See supported databases below.
	*
	*	Supported databases
	*	-------------------
	*	mysql		-	MySQL Database
	*/
		define ("_DBSYSTEM", "mysql");

	/**
	* The type of connection to establish with the database
	*/
		define ("_CONNECT_TYPE", "connect");

	/**
	* Used to keep people from directly accessing files
	*/
		define ("_FLARE_INC", 1);

	define("ABSPATH", dirname(__FILE__)."/");

	?>';

	if (is_writable('.')) {
		if (!$fh = fopen($real_conf, "w+")) {
			return 0;
			break;
		}

		if (fwrite($fh, $config_data) === FALSE) {
			return 0;
			break;
		}

		fclose($fh);
	
		if (file_exists($real_conf)) {
			return 1;
		} else {
			return 0;
		}
	} else
		return 0;
		
}

function write_admin_index() {
	$index		= "index.php";

	$host = (stristr($_SERVER['HTTP_HOST'], "http://") === FALSE) ? "http://" . $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST'];

	$index_data = '<?php
		header("Location: '.$host.'");
	?>';

	if (is_writable($home_root . $home_dir)) {
		if (!$fh = fopen($home_root . $home_dir . "/" . $index, "w+")) {
			return 0;
			break;
		}

		if (fwrite($fh, $index_data) === FALSE) {
			return 0;
			break;
		}

		fclose($fh);
	
		if (file_exists($home_root . $home_dir . "/" . $index)) {
			return 1;
		} else {
			return 0;
		}
	} else
		return 0;
}

function create_user($root_username, $root_password, $flare_username, $flare_password) {
	$db	= new DB($root_username, $root_password, "", $hostname);

	$sql = array(
		"user_all" => "GRANT SELECT , INSERT , UPDATE , DELETE, CREATE ON * . * TO ':1'@'%' IDENTIFIED BY ':2'",
		"user_local" => "GRANT SELECT , INSERT , UPDATE , DELETE, CREATE ON * . * TO ':1'@'localhost' IDENTIFIED BY ':2'"
	);

	$stmt1 = $db->prepare($sql['user_all']);
	$stmt2 = $db->prepare($sql['user_local']);

	$stmt1->execute($flare_username, $flare_password);	
	$stmt2->execute($flare_username, $flare_password);	
}

function create_database($root_username, $root_password, $database, $hostname) {
	$db	= new DB($root_username, $root_password, "", $hostname);

	$sql = array(
		"database" => "CREATE DATABASE IF NOT EXISTS `:1`"
	);

	$stmt1 = $db->prepare($sql['database']);
	$stmt1->execute($database);
}

function create_tables($username, $password, $database, $hostname) {
	$db	= new DB($username, $password, $database, $hostname);
	$fullsql = '';

	$fh = fopen('setup/sql/mysql.sql', 'r') or die ("Cannot open SQL file");

	while (!feof($fh)) {
		$data = fgets($fh, 4096);

		if (strpos($data, '--') === false)
			$fullsql .= $data;
		else
			continue;
	}

	$sql = explode(';', $fullsql);

	foreach ($sql as $key => $val) {
		$val = trim($val);

		if ($val == '')
			continue;
		else {
			$stmt = $db->prepare($val);
			$stmt->execute();
		}
	}
}

function check_writable($dir) {
	$dir = normalize_dir($dir);

	if (is_writable(dirname($dir)))
		echo "  1";
	else {
		if (is_writable($dir))
			echo "  1";
		else
			echo "  0";
	}
}

function check_for_necessary_extensions() {
	require_once(ABSPATH.'/config-inc.php');

	// Create a new database object and filesystem object
	$db	= new DB(_DBUSER, _DBPWRD, _DBUSE, _DBSERVER, _DBPORT);

	$required = array(
		'Accounts',
		'Authentication',
		'Groups',
		'Settings',
		'Filesystem',
		'Error_Handler'
	);
	$count = 0;

	$sql = array(
		"mysql" => array(
			"extensions" => "SELECT extension_id FROM "._PREFIX."_extensions WHERE name=':1'"
		)
	);

	$stmt1 = $db->prepare($sql[_DBSYSTEM]['extensions']);

	foreach ($required as $key => $val) {
		$stmt1->execute($val);

		@$result = $stmt1->result(0);

		if ($result == '')
			continue;
		else
			$count++;
	}

	if ($count == count($required))
		echo "  1";
	else
		echo "  0";

	return;
}

function normalize_dir($dir) {
	if (substr($dir, 0, 1) != '/')
		$dir = '/' . $dir;

	if (substr($dir, -1, 1) != '/')
		$dir .= '/';

	return preg_replace("/\/+/", "/", $dir);
}

?>
