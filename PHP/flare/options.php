<?php

session_name("Flare");
session_start();

/**
* Load files that are needed by the application.
* These requirements ARE NOT dependant on the database.
*/
if (file_exists('config-inc.php'))
    require ('config-inc.php');
else 
    header('Location: setup.php');

require_once (ABSPATH.'/extensions/Error_Handler/class.General_Exception.php');     // Error Handler
require_once (ABSPATH.'/extensions/Authentication/class.Authentication.php');       // User Authentication Handler
require_once (ABSPATH.'/db/'._DBSYSTEM.'.php');                                     // Database Abstraction Layer
require_once (ABSPATH.'/extensions.php');                                           // Extension discovery,cleanup and addition
require_once (ABSPATH.'/smarty/Smarty.class.php');                                  // Smarty
require (ABSPATH.'/masterfile.php');                                                // General functions

// Create a database object to be used during the rest of program execution
$db = new DB(_DBUSER, _DBPWRD, _DBUSE, _DBSERVER, _DBPORT);

// Grab all the configuration data and file it into our array
$cfg    = get_config();
$ext    = new Extensions();
$ext->__set("db",$db);

// Create our Smarty object which will be used for creating all output
$tpl    = new Smarty;

/**
* Set up template directories
* First, the directory that houses all the templates we can use
*/
$tpl->template_dir  = ABSPATH.'/templates/'.$cfg['template'].'/templates/admin';

// Next, the directory that Smarty will cache to
$tpl->compile_dir   = ABSPATH.'/templates/'.$cfg['template'].'/templates_c/admin';

/**
* Load other files that are needed by the application.
* These requirements ARE dependant on the database.
*/
require_once(ABSPATH.'/lang/lang-'.$cfg['language'].'.php');
require_once(ABSPATH.'/extensions/Help/lang/lang-'.$cfg['language'].'.php');

$user_id = import_var('user_id', 'S');
$username = import_var('username', 'S');

// Create new authentication instance
$auth_temp = new Authentication;
$auth_temp->__set("db",$db);
$auth_temp->__set("tpl",$tpl);

/**
* Determine the type of authentication to use based on the field value for
* the users account in the database
*/
$auth_temp->determine_auth_type($username);

// Retrieve the determined authentication type
$auth_type  = $auth_temp->__get("auth_type");

// Assign new authentication object based on the type retrieved from the users field in the database
$auth       = $auth_temp->auth_factory($auth_type);

// Setup objects in the Authentication object
$auth->__set("db",$db);
$auth->__set("ext",$ext);
$auth->__set("tpl",$tpl);

$auth->__set("cfg",$cfg);
$auth->tpl->assign("_AUTH_TYPE", $auth_type);

// Destroy the Authentication object because it wont be needed anymore
unset($auth_temp);

/**
* Assign the authentication type to a template variable
* used to determine how some pages are show and if some features are shown.
* For instance, if the auth type is kerberos, we're unable to change the user's
* password so we dont give them the option of changing their password on the Accounts form
*/
$tpl->assign('_AUTH_TYPE', $auth_type);

// Attempt to authenticate the user
$auth->authenticate_admin($user_id);

if (!is_admin($user_id, false))
    header("Location: index.php");

assign_header();
assign_footer();

$action = import_var('action', 'P');

switch($action) { // Begin: switch($action) {
    case 'update':
        $options = import_var('option', 'P');

        $sql = array(
            "mysql" => array(
                "update" => "UPDATE "._PREFIX."_config SET value=':1' WHERE name=':2'"
            )
        );

        $stmt1 = $db->prepare($sql[_DBSYSTEM]['update']);

        foreach($options as $key => $val) {
            $stmt1->execute($val, $key);
        }

        $tpl->assign("_MESSAGE", "Successfully saved configuration.");
        $tpl->assign("_RETURN_LINK", "<a href='options.php'>Return to Options Page</a>");

        $tpl->display('actions_done.tpl');
        break;
    default:
        $sql = array(
            "mysql" => array(
                "options" => "SELECT * FROM "._PREFIX."_config ORDER BY name ASC"
            )
        );
        $options = array();
        $data = array();

        $stmt1 = $db->prepare($sql[_DBSYSTEM]['options']);
        $stmt1->execute();

        while ($row = $stmt1->fetch_assoc()) {
            $data = array(
                'name'  => $row['name'],
                'value' => $row['value'],
                'extid' => $row['extension_id'],
                'desc'  => $row['description']
            );

            array_push($options, $data);
        }

        $tpl->assign('OPTIONS', $options);
        $tpl->display('options.tpl');

        break;
} // End: switch($action) {

?>
