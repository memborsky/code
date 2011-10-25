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

// Start the session so that we have all the session vars available to us that we set in the previous pages
session_start();

/**
* Check to see if by chance the user has come from an old install where they might not
* have logged out before they went to install a new instance
*/
if (@$_SESSION['username']) {
  session_destroy();
  header("Location: setup.php");
}

// Define FLARE_INC so that any pages that we include will work
if (!defined('_FLARE_INC'))
  define('_FLARE_INC', 1);

if (!defined('ABSPATH'))
  define('ABSPATH', dirname(__FILE__).'/');

if (@$lang == '')
  $lang='english';

// Also require our templating stuff
require_once (ABSPATH.'/smarty/Smarty.class.php');
require_once (ABSPATH.'/setup/lang/lang-'.$lang.'.php');
require_once (ABSPATH.'/extensions/Error_Handler/class.General_Exception.php');
require_once (ABSPATH.'/setup/lib/sajax.php');
require (ABSPATH.'/setup/lib/worker.php');
require (ABSPATH.'/db/mysql.php');

if(!check_version(PHP_VERSION, "5.0.4") ) {
  $tpl = new Smarty;
  $tpl->template_dir 	= ABSPATH.'/setup/templates';
  $tpl->compile_dir	= ABSPATH.'/setup/templates_c';

  $tpl->assign('_MESSAGE', '<center>Current PHP version: ' . PHP_VERSION . ' is too low for this code!</center>');
  $tpl->display('actions_done.tpl');
  exit();
}

require_once ('masterfile.php');

// Make a new template object and assign it the template and template compile dirs
$tpl			= new Smarty;
$tpl->template_dir 	= ABSPATH.'/setup/templates';

if (is_writable(ABSPATH.'/setup/templates_c'))
  $tpl->compile_dir	= ABSPATH.'/setup/templates_c';
else {
  $tpl->compile_dir	= '/tmp';
  $tpl->assign("_MESSAGE", "Your Smarty compile directory (setup/templates_c) is not writable. Please change the "
  . "permissions to be writable by your web server");
  $tpl->display("actions_done.tpl");
  exit();
}

$rs     = @$_GET['rs'];

if (!$rs) {
  $sajax_request_type = "GET";
  sajax_init();
  sajax_export("welcome_main");
  sajax_export("welcome_database");
  sajax_export("welcome_directory");
  sajax_export("welcome_extensions");
  sajax_export("welcome_admin");
  sajax_export("welcome_finished");
  sajax_export("change_sidebar");
  sajax_export("create_database");
  sajax_export("create_user");
  sajax_export("check_writable");
  sajax_export("setup_database");
  sajax_export("install_extension");
  sajax_export("check_for_necessary_extensions");
  sajax_export("make_directories");
  sajax_export("create_admin");

  sajax_handle_client_request();

  $tpl->assign('SAJAX_JAVASCRIPT', sajax_get_javascript());
  $tpl->display("index.tpl");
} else if ($rs) {
  switch($rs) {
    case "welcome_main":
      welcome_main();
      break;
    case "welcome_database":
      welcome_database();
      break;
    case "welcome_directory":
      welcome_directory();
      break;
    case "welcome_extensions":
      welcome_extensions();
      break;
    case "welcome_admin":
      welcome_admin();
      break;
    case "welcome_finished":
      welcome_finished();
      break;
    case "change_sidebar":
      $section = $_GET['rsargs'][0];

      change_sidebar($section);
      break;
    case "setup_database":
      $root_username 	= $_GET['rsargs'][0];
      $root_password 	= $_GET['rsargs'][1];

      $dbuser		= $_GET['rsargs'][2];
      $dbpass		= $_GET['rsargs'][3];

      $database 	= $_GET['rsargs'][4];
      $hostname 	= $_GET['rsargs'][5];

      $cdb = $_GET['rsargs'][6];
      $cus = $_GET['rsargs'][7];

      if ($cdb == "true") {
        create_database($root_username, $root_password, $database, $hostname);
      }

      if ($cus == "true") {
        create_user($root_username, $root_password, $dbuser, $dbpass, $hostname);
      }

      create_tables($dbuser, $dbpass, $database, $hostname);

      write_conf($hostname, $dbuser,$dbpass,$database);
      break;
    case "check_writable":
      $dir	= $_GET['rsargs'][0];

      check_writable($dir);
      break;
    case "install_extension":
      $extension = $_GET['rsargs'][0];
      install_extension($extension);
      break;
    case "check_for_necessary_extensions":
      check_for_necessary_extensions();
      break;
    case "make_directories":
      $home_dir = $_GET['rsargs'][0];
      $group_dir = $_GET['rsargs'][1];

      $home = normalize_dir($home_dir);
      $group = normalize_dir($group_dir);

      if (make_home_root($home)) {
        if (make_group_root($group)) {
          if (save_default_roots($home,$group))
            echo "  1";
          else
            echo "  0";
        } else
          echo "  0";
      } else
        echo "  0";
      break;
    case "create_admin":
      $fname 		= $_GET['rsargs'][0];
      $lname 		= $_GET['rsargs'][1];
      $email 		= $_GET['rsargs'][2];
      $username 	= $_GET['rsargs'][3];
      $password 	= $_GET['rsargs'][4];
      $auth_type	= $_GET['rsargs'][5];

      create_admin($fname, $lname, $email, $username, $password, $auth_type);
      break;
  }
}

?>
