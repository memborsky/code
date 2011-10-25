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

// Register all variables that will tag along with any user
session_register('debug');

/**
* Load files that are needed by the application.
* These requirements ARE NOT dependant on the database.
*/
if (file_exists('config-inc.php'))
    require ('config-inc.php');
else
    header('Location: setup.php');

require_once (ABSPATH.'/extensions/Error_Handler/class.General_Exception.php'); // Error Handler
require_once (ABSPATH.'/db/'._DBSYSTEM.'.php');                                 // Database Abstraction Layer
require_once (ABSPATH.'/extensions.php');                                       // Extension discovery,cleanup and addition
require_once (ABSPATH.'/smarty/Smarty.class.php');                              // Smarty
require (ABSPATH.'/masterfile.php');                                            // General functions

// Create a database object to be used during the rest of program execution
$db     = new DB(_DBUSER, _DBPWRD, _DBUSE, _DBSERVER, _DBPORT);

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
* Now that the basic enviroment is setup, we can continue on with the stuff we know should work.
* Create our Smarty object which will be used for creating all output
*/
$tpl    = new Smarty;

/**
* Set up template directories
* First, the directory that houses all the templates we can use
*/
if (@$_SESSION['theme']) {
    $theme = import_var('theme', 'S');
    $tpl->template_dir  = ABSPATH.'/templates/'.$theme.'/templates';
    $tpl->compile_dir   = ABSPATH.'/templates/'.$theme.'/templates_c';
} else {
    $tpl->template_dir  = ABSPATH.'/templates/'.$cfg['template'].'/templates';

    // Next, the directory that Smarty will cache to
    $tpl->compile_dir   = ABSPATH.'/templates/'.$cfg['template'].'/templates_c';
}

if (!is_dir($tpl->template_dir)) {
    $tpl->template_dir = ABSPATH.'/templates/default/templates';
    $_SESSION['theme'] = "default";
} if (!is_dir($tpl->compile_dir)) {
    $tpl->compile_dir = ABSPATH.'/templates/default/templates_c';
    $_SESSION['theme'] = "default";
}

if ($cfg['maintenance_mode'] > time()) {
    if (is_admin($_SESSION['user_id'], false))
        $cfg['idle_timeout'] = 0;

    $tpl->assign('EXPIRES', strftime("%B %d, %Y %H:%M:%S", $cfg['maintenance_mode']));
    $tpl->display('maintenance_mode.tpl');
    exit;
}

/**
* Load other files that are needed by the application.
* These requirements ARE dependant on the database.
*/
require_once(ABSPATH.'/lang/lang-'.$cfg['language'].'.php');
require_once(ABSPATH.'/extensions/Help/lang/lang-'.$cfg['language'].'.php');

// PEAR::File_Archive
if(!@include_once ('File/Archive.php')) {
    assign_header('EMPTY');
    assign_footer();
    $tpl->assign('_MESSAGE', "PEAR::File_Archive could not be loaded!");
    $tpl->display('actions_done.tpl');
    exit;
}

// Check to see if we should be checking the running config
if ($cfg['use_strict']) {
    // We are, so check the running configuration for common errors, and forbidden settings
    $check_config = check_config();

    if ($check_config) {
        $tpl->assign("_MESSAGE", $check_config);
        assign_header('EMPTY');
        assign_footer();
        $tpl->display("actions_done.tpl");
        exit;
    }
}

// Get the extension that is requested to be loaded
if (@$_GET['extension']) {
    @$flare_extension = import_var('extension', 'G');
} else if (@$_POST['extension']) {
    @$flare_extension = import_var('extension', 'P');
} else {
    @$flare_extension = default_extension();
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

    // Create the SQL structure that holds any SQL code we'll run
    $sql = array (
        "mysql" => array ( 'guest_user' => "SELECT `username`,`user_level` FROM `"._PREFIX."_users` WHERE `username`='guest' LIMIT 1",
        )
    );

    // Prepare SQL code for executing
    $stmt = $db->prepare($sql[_DBSYSTEM]['guest_user']);

    // Query database for guest user information
    $stmt->execute();

    // Retrieve the single row we pulled
    $result = $stmt->fetch_array();

    /**
    * Store the guest data in session vars. These will be overwritten (even for actual users
    * who are truely authenticating) when the user gets to the login methods of the Authentication
    * extension. We dont need to worry about these session vars being set.
    */
    $_SESSION['username']   = $result['username'];
    $_SESSION['user_level'] = $result['user_level'];
}

// Check to see if debugging is enabled. If it is, act accordingly
if ($cfg['use_debug']) {
    // Require the debugger's code
    require_once('debug.php');

    // Create a new debugging object
    $dbg        = new Debug();

    // As the debugger objects first entry, record the filename that is executing
    $dbg->record(__FILE__);

    // Notify the users that we're in debug mode
    echo _DEBUG_MODE;
} else {
    //error_reporting('E_ALL & ~E_WARNING');
}

// Check to see if authentication is enabled. If it is, act accordingly
if ($cfg['use_auth']) {
    require_once(ABSPATH.'/extensions/Authentication/class.Authentication.php');
    require_once(ABSPATH.'/extensions/Authentication/lang/lang-'.$cfg['language'].'.php');

    // Assign the username if one has been sent to us...
    if (@$_POST['action'] == "login") {
        // Username will always be sent via POST
        if ($_POST['username'] == "")
            $username = "guest";
        else {
            $username = import_var('username', 'P');
            if(!strpos($username, "@") === FALSE) {
                $pos = strpos($username, "@");

                $username = substr($username, 0, $pos);
            }
        }
    } else {
        // Otherwise, use the one stored in the session
        $username = import_var('username', 'S');
    }

    // Create new authentication instance
    $auth_temp  = new Authentication;
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
    $auth_type  = $auth_temp->__get("auth_type");

    // Assign new authentication object based on the type retrieved from the users field in the database
    $auth       = $auth_temp->auth_factory($auth_type);

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
    $auth->authenticate($flare_extension);

    foreach ($safe_actions as $key => $val) {
        if ($flare_action == $val) {
            $auth->__set("authenticated", TRUE);
            $last_access = time();
            $safe_page = 1;
            break;
        }
    }

    // If the user is authenticated...
    if ($auth->__get("authenticated")) {
        // Assign our headers and footers to template variables...
        assign_header();
        assign_footer();

        // ..and load the extension they asked for...
        if (!is_file(ABSPATH.'/extensions/' . $flare_extension . '/index.php')) {
            $tpl->assign('_MESSAGE', "Could not load the extension you requested.");
            $tpl->assign('_RETURN_LINK', "<a href='index.php'>Back to Home</a>");

            $tpl->display('actions_done.tpl');
        } else {
            // Get current times and saved times to check if user was idle for too long
            $curr_time = time();
            $last_access = ($safe_page) ? $last_access : import_var('last_access', 'S');

            if ($cfg['idle_timeout'] == 0)
                $cfg['idle_timeout'] = time() + 999999;

            // If they have been idle too long, log them out
            if (($last_access + $cfg['idle_timeout']) < $curr_time && $flare_extension != "Authentication") {

                $auth->tpl->assign('_MESSAGE', _LOGIN_TIMEOUT);
                $auth->logout();
            } else {
                require(ABSPATH.'/extensions/' . $flare_extension . '/index.php');
                $_SESSION['last_access']	= time();
            }
        }
    } else {
        // ...else, throw them to the login page
        assign_header('EMPTY');
        assign_footer();
        $auth->show_login();
    }
} else {
    /**
    * If we're not using the authentication system, then forget about
    * authenticating stuff and just load the requested extension.
    */
    if (!is_file(ABSPATH.'/extensions/' . $flare_extension . '/index.php')) {
        $tpl->assign('_MESSAGE', "Could not load the extension you requested.");
        $tpl->assign('_RETURN_LINK', "<a href='index.php'>Back to Home</a>");

        $tpl->display('actions_done.tpl');
    } else {
        require(ABSPATH.'/extensions/' . $flare_extension . '/index.php');
    }
}

// The last step for debugging is to open the debug console. This does that.
if ($cfg['use_debug']) {
    echo "<script language='javascript'>"
    . "window.open('debug.php');"
    . "</script>";
}

// Log the URL string that was used to surf to this page
if ($cfg['use_logging'])
    $log->log('PAGE_SURF', @$_SERVER['HTTP_REFERER'], import_var('username', 'S'));

?>
