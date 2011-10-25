<?php
/**
* @package Flare
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/

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

/**
* Begin the session that will follow the user through the
* rest of their time while using the app
*/
session_name("Flare");
session_start();

// Register all variables that will tag along with the user
session_register('debug');

// Load other files that are needed by the application
if (file_exists('config-inc.php'))
    require ('config-inc.php');
else
    header('Location: setup.php');

require_once (ABSPATH.'/extensions/Error_Handler/class.General_Exception.php');     // Error Handler
require_once (ABSPATH.'/extensions/Authentication/class.Authentication.php');       // Authentication Layer
require_once (ABSPATH.'/db/'._DBSYSTEM.'.php');                                     // Database Abstraction Layer
require_once (ABSPATH.'/extensions.php');                                           // Extension discovery,cleanup and addition
require_once (ABSPATH.'/smarty/Smarty.class.php');                                  // Smarty
require (ABSPATH.'/masterfile.php');                                                // General functions

// Assign the username if one has been sent to us. Otherwise use the one stored in $_SESSION['username']
$username = import_var('username', 'S');
$user_id = import_var('user_id', 'S');

// Create a database object to be used during the rest of program execution
$db = new DB(_DBUSER, _DBPWRD, _DBUSE, _DBSERVER, _DBPORT);

/**
* Moved by: mlemborsky01
* Reason:
* For setting up our enviroment, we want to make sure that all the configuration items are handled properly.
* If they aren't, then we need to kill our current state and output an error message about how we got to
* where we are now. This may help in debugging certain 'odd' bugs that might arise in the future.
*/
try {
    if ( !$cfg = get_config() ) {
        /**
        * Throw exception if we can not load our configuration from the database.
        */
        throw new Exception("Error occured in database configuration.<br>\n<br>\n"
                            . "<b>Error Notes:</b> Configuration does not exist.<br>\n"
                            . "<b>Time:</b> " . date('l dS \of F Y h:i:s A')
                            );
    }

    $check_config   = 0;
    $safe_page      = 0;
    $safe_actions   = array(
        'show_credits',
        'show_privacy_policy',
        'show_feedback',
        'do_leave_feedback',
        'show_usage',
        'show_tos'
    );


    /**
    * Create the extensions object which is used to cleanup and insert new
    * extensions into the database automagically as they are added
    *
    * Moved by: mlemborsky01
    * Reason: This is to allow us to simplify how many if checks we do to eliminate overhead.
    */
    $ext = new Extensions();
    $ext->__set("db",$db);

    // Check to see if logging is enabled and the logging file exists before we try to load it.
    if ($cfg['use_logging'] && !file_exists(ABSPATH.'/extensions/Logging/class.Logging.php')) {
        // If both are not true, then we need to throw an error to make sure that we can get the file to load first.
        throw new Exception ("Error occured in Logging system.<br>\n<br>\n"
                             . "<b>Error Notes:</b> File does not exist.<br>"
                             . "<b>File:</b> class.Logging.php<br>\n"
                             . "<b>Path:</b> " . ABSPATH . "extensions/Logging/class.Logging.php<br>\n"
                             . "<b>Time:</b> " . date('l dS \of F Y h:i:s A')
                             );
    }

    // If we have made it into this far then we should be able to load the logging system.
    require_once (ABSPATH.'/extensions/Logging/class.Logging.php');

    // Create a new Logging object that will be used for the rest of script execution
    $log = new Logging($db);

    $ext->__set("log",$log);

} catch (Exception $e) {

    die ($e->getMessage() . "<br>\n<b>Line:</b> " . $e->getLine() . "<br>\n<b>Backtrace:</b> " . $e->getTraceAsString());

}

/**
* Load other files that are needed by the application.
* These requirements ARE dependant on the database.
*/
require_once(ABSPATH.'/lang/lang-'.$cfg['language'].'.php');        // Localization
require_once(ABSPATH.'/extensions/Help/lang/lang-'.$cfg['language'].'.php');

// Create our Smarty object which will be used for creating all output
$tpl = new Smarty;

/**
* Set up template directories
* First, the directory that houses all the templates we can use
*/
if ($_SESSION['theme']) {
    $theme = import_var('theme', 'S');

    $tpl->template_dir  = ABSPATH.'/templates/'.$theme.'/templates/admin';      // The actual templates directory.
    $tpl->compile_dir   = ABSPATH.'/templates/'.$theme.'/templates_c/admin';    // The cache directory for our templates.
} else {
    $tpl->template_dir  = ABSPATH.'/templates/'.$cfg['template'].'/templates/admin';    // The actual templates directory.
    $tpl->compile_dir   = ABSPATH.'/templates/'.$cfg['template'].'/templates_c/admin';  // The cache directory for our templates.
}


// Check to see if we are in maintence mode.
if ($cfg['maintenance_mode'] > time()) {
    $cfg['idle_timeout'] = 0;
}

// Check the running configuration for common errors, and forbidden settings
check_config();

// Get the extension that is requested to be loaded
if (@$_GET['extension']) {
    @$flare_extension = import_var('extension', 'G');
} else if (@$_POST['extension']) {
    @$flare_extension = import_var('extension', 'P');
} else {
    /**
    * Setting the variable passed to default_ext to the users' id
    * will check to see if the user is an
    * admin of the default ext and if he isnt, it will get the first
    * extension that he is an admin of.
    */
    $flare_extension = default_extension($user_id);
}

// Get the action if any are specified. Otherwise assign a default action
if (@$_GET['action']) {
    @$flare_action = import_var('action', 'G');
} else if (@$_POST['action']) {
    @$flare_action = import_var('action', 'P');
} else {
    @$flare_action = "";
}

// Get the configuration information for the extension
$ext->extension_config_info($flare_extension);

// Check user level and get the default if user level isnt set
if (empty($_SESSION['user_level'])) {
    session_register('user_level');
    session_register('username');

    $sql = array (
        "mysql" => array (
            'guest_user' => "SELECT `user_level`,`username` FROM `"._PREFIX."_users` WHERE `username`='guest' LIMIT 1",
        )
    );

    $stmt = $db->prepare($sql[_DBSYSTEM]['guest_user']);
    $stmt->execute();
    $result = $stmt->fetch_array();

    $_SESSION['username']	= $result['username'];
    $_SESSION['user_level'] = $result['user_level'];
}

// Check to see if debugging is enabled. If it is, act accordingly
if ($cfg['use_debug']) {
    // Require the debugger's code
    require_once('debug.php');

    // Create a new debugging object
    $debug		= new Debug();

    // As the debugger objects first entry, record the filename that is executing
    $debug->record(__FILE__);

    // Notify the users that we're in debug mode
    echo _DEBUG_MODE;
} else {
    //error_reporting('E_ALL & ~E_WARNING');
}

// Create new authentication instance
$auth_temp = new Authentication;
$auth_temp->__set("db",$db);
$auth_temp->__set("tpl",$tpl);

if ($cfg['use_logging'])
    $auth_temp->__set("log",$log);

/**
* Determine the type of authentication to use based on the field value for
* the users account in the database
*/
$auth_temp->determine_auth_type($username);

// Retrieve the determined authentication type
$auth_type 	= $auth_temp->__get("auth_type");

// Assign new authentication object based on the type retrieved from the users field in the database
$auth           = $auth_temp->auth_factory($auth_type);

// Setup objects in the Authentication object
$auth->__set("db",$db);
$auth->__set("tpl",$tpl);

if ($cfg['use_logging'])
    $auth->__set("log",$log);

$auth->__set("cfg",$cfg);
$auth->__set("ext",$ext);
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

// If the user is authenticated...
if ($auth->__get("authenticated")) {
    // Assign our headers and footers to template variables...
    admin_header();
    assign_footer();

    // ..and load the extension they asked for...
    if (!is_file('extensions/' . $flare_extension . '/admin.php')) {
        $tpl->assign('_MESSAGE', "Could not load the extension you requested.");
        $tpl->assign('_RETURN_LINK', "<a href='admin.php'>Back to Admin Home</a>");

        $tpl->display('actions_done.tpl');
    } else {
        // Get current times and saved times to check if user was idle for too long
        $curr_time = time();
        $last_access = import_var('last_access', 'S');

        /**
        * If the settings dictate no idle timeout, make sure we set the timeout to a
        * value that will literally never timeout
        */
        if ($cfg['idle_timeout'] == 0)
            $cfg['idle_timeout'] = time() + 999999;

        // If they have been idle too long, log them out
        if (($last_access + $cfg['idle_timeout']) < $curr_time && $flare_extension != "Authentication") {
            $auth->tpl->assign('_MESSAGE', _LOGIN_TIMEOUT);
            $auth->logout();
        } else {
            require(ABSPATH.'/extensions/' . $flare_extension . '/admin.php');
            $_SESSION['last_access']	= time();
        }
    }
} else {
    /**
    * ...else, throw them to the main page. They'll be rejected again and sent
    * to the login page if they cant auth there too
    */
    header("Location: index.php");
}

// The last step for debugging is to open the debug console. This does that.
if ($cfg['use_debug']) {
    echo "<script language='javascript'>"
    . "window.open('debug.php');"
    . "</script>";
}

if ($cfg['use_logging'])
    $log->log('PAGE_SURF', @$_SERVER['HTTP_REFERER'], import_var('username', 'S'));

?>
