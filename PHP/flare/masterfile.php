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
* Prevent direct access to the file
*/
defined( '_FLARE_INC' ) or die( "You can't access this file directly." );

/**
* Returns a value from a superglobal
*
* Used to return cleaned variables from the PHP superglobals. This method
* receives an index and also the global that you want to access. Any of
* the PHP superglobals can have their values retrieved this way.
*
* @param string $varname The index in the Superglobal where your value is stored
* @param string $scope Superglobal to pull data from
* @return mixed|NULL The value at the specified index in the specified superglobal or NULL
*/
function import_var($varname, $scope = 'G') {
    $scope = strtoupper($scope);

    $superglobals = array(
        'G'     => $_GET,
        'P'     => $_POST,
        'C'     => $_COOKIE,
        'R'     => $_REQUEST,
        'S'     => $_SESSION,
        'SE'    => $_SERVER);

        if (count($superglobals[$scope]) > 0) {
            if (isset($superglobals[$scope][$varname]))
                return cleanse_input($superglobals[$scope][$varname]);
            else
                return NULL;
        } else
            return NULL;
}

/**
* Retrieves the default extension
*
* @param integer $admin The user_id of the person accessing the admin page
* @return string The name of the extension
*/
function default_extension($admin_id = false) {
    global $db;

    $sql = array (
        "mysql" => array (
            'default_ext' => "SELECT `value` FROM `"._PREFIX."_config` WHERE `name`='default_extension'",
            'allowed_admin' => "SELECT name FROM "._PREFIX."_admin_privs AS fap LEFT JOIN "._PREFIX."_extensions AS fe ON fap.extension_id = fe.extension_id WHERE user_id=':1' ORDER BY name ASC LIMIT 1",
            )
    );

    $stmt1 = $db->prepare($sql[_DBSYSTEM]['default_ext']);
    $stmt2 = $db->prepare($sql[_DBSYSTEM]['allowed_admin']);

    if ($admin_id) {
        $stmt2->execute($admin_id);
        return $stmt2->result();
    } else {
        $stmt1->execute();
        return $stmt1->result();
    }
}

/**
*  Assigns header code to template
*
* This method can parse both the empty headers (those without the navigation
* links at the top left and right) or the normal headers (opposite of empty).
* The variables that are assigned here are populated into the appropriate
* header.tpl or header_empty.tpl files located in the normal template folders.
*
* @param string $type The type of header to display. Can be many values, but EMPTY and '' are the typical values.
* @return bool Always returns true;
*/
function assign_header($type = '') {
    global $tpl, $db, $auth, $cfg;

    if (@$_SESSION['theme']) {
        $theme = import_var('theme', 'S');
        $tpl->assign("_TEMPLATE", $theme);
    } else
        $tpl->assign("_TEMPLATE", $cfg['template']);

    $user_id = import_var('user_id', 'S');

    switch ($type) {
        case 'EMPTY':
            return true;
        default:
            $link_count         = 0;
            $extension_links    = "";
            $is_admin           = 0;

            $sql = array (
                "mysql" => array (
                    'nav_items' => "SELECT `name`, `displayed_name` FROM "._PREFIX."_extensions WHERE `enabled`='1' AND `visible`='1' ORDER BY `display_order` ASC",
                    'allowed_admin' => "SELECT extension_id FROM "._PREFIX."_admin_privs WHERE user_id=':1'",
                    )
            );

            /**
            * Prepare and execute built in queries
            */
            $stmt1 = $db->prepare($sql[_DBSYSTEM]['nav_items']);
            $stmt2 = $db->prepare($sql[_DBSYSTEM]['allowed_admin']);
            $result = $stmt1->execute();
            $stmt2->execute($user_id);

            if ($stmt2->num_rows() > 0)
                $is_admin = 1;

            /**
            * Loop through all results and create the Extension links area
            */
            while ($row = $stmt1->fetch_assoc()) {
                $extension_links .= "<a href='index.php?extension=$row[name]'>"
                    . "$row[displayed_name]</a> :: ";

                ++$link_count;

                /**
                * For formattings sake, we insert a paragraph break after every 5 extensions
                */
                if ($link_count == 5) {
                    $extension_links = substr($extension_links, 0, -4);
                    $extension_links .= "<p>::";
                    $link_count = 0;
                }
            }

            /**
            * First line gets the length of the extensions_links var and subtracts the position
            * of the last :: from it so that it can find out the offset to start returning
            * chars from. Then, the second line removes the chars, starting at the determined offset
            */
            $last_chars = "-" . (strlen($extension_links) - strrpos($extension_links, "::"));
            $extension_links = substr($extension_links, 0, $last_chars);

            /**
            * Begin populating the template data, first assign language constants
            */
            $tpl->assign('_NAVIGATION', _NAVIGATION);
            $tpl->assign('_NEWS', _NEWS);
            $tpl->assign('_NO_NEWS', _NO_NEWS);
            $tpl->assign('_INVITES', _INVITES);
            $tpl->assign('_NO_INVITES', _NO_INVITES);
            $tpl->assign('_USERNAME', $auth->__get("username"));
            $tpl->assign('_IS_ADMIN', $is_admin);

            /**
            * Second, assign dynamic content and other vars
            */
            $tpl->assign('EXTENSION_NAVIGATION', $extension_links);

            return true;
    }
}

/**
*  Assigns header code to template
*
* This method can parse both the empty headers (those without the navigation
* links at the top left and right) or the normal headers (opposite of empty).
* The variables that are assigned here are populated into the appropriate
* header.tpl or header_empty.tpl files located in the admin template folders.
* The admin_header function will also display the admin navigation box displayed
* on all admin pages.
*
* @return bool Always returns true;
*/
function admin_header() {
    global $db, $tpl, $auth, $cfg;

    if ($_SESSION['theme']) {
        $template = import_var('theme', 'S');
        $theme = $template;
    } else
        $theme = $cfg['template'];

    $sql = array (
        "mysql" => array (
            'allowed_access' => "SELECT name,admin_displayed_name,image,fe.extension_id FROM "._PREFIX."_extensions as fe LEFT JOIN "._PREFIX."_admin_privs as fap ON fap.extension_id = fe.extension_id WHERE user_id=':1' ORDER BY admin_displayed_name ASC",
            'nav_items' => "SELECT `name`, `displayed_name` FROM "._PREFIX."_extensions WHERE `enabled`='1' AND `visible`='1' ORDER BY `display_order` ASC",
        )
    );

    $where_clause 		= '';
    $extension_links	= '';
    $admin_controls 	= array();
    $counter 		= 0;
    $link_count 		= 0;

    $stmt1	= $db->prepare($sql[_DBSYSTEM]['nav_items']);
    $stmt2	= $db->prepare($sql[_DBSYSTEM]['allowed_access']);

    $stmt1->execute();
    $stmt2->execute(import_var('user_id', 'S'));

    /**
    * Loop through all results and create the Extension links area
    */
    while ($row = $stmt1->fetch_assoc()) {
        $extension_links .= "<a href='index.php?extension=$row[name]'>"
            . "$row[displayed_name]</a> :: ";

        ++$link_count;

        /**
        * For formattings sake, we insert a paragraph break after every 5 extensions
        */
        if ($link_count == 5) {
            $extension_links = substr($extension_links, 0, -4);
            $extension_links .= "<p>::";
            $link_count = 0;
        }
    }
    /**
    * First line gets the length of the extensions_links var and subtracts the position
    * of the last :: from it so that it can find out the offset to start returning
    * chars from. Then, the second line removes the chars, starting at the determined offset
    */
    $last_chars = "-" . (strlen($extension_links) - strrpos($extension_links, "::"));
    $extension_links = substr($extension_links, 0, $last_chars);

    // hack to fix some extension icons not displaying in some installations
    $admin_controls[$counter] = array('dummy');

    // We'll be pulling an extension's information for each admin flag set in the users session
    while ($row = $stmt2->fetch_array()) {
        // Push the info into our string of admin controls. These are the buttons at the
        // top of each admin page
        $admin_controls[++$counter] = array (
            "name"                  => $row['name'],
            "image"                 => $row['image'],
            "admin_displayed_name"  => $row['admin_displayed_name']
        );
    }

    $tpl->assign("_TEMPLATE", $theme);
    $tpl->assign('_USERNAME', $auth->__get("username"));
    $tpl->assign('EXTENSION_NAVIGATION', $extension_links);
    $tpl->assign('ADMIN_CONTROLS', $admin_controls);

    return true;
}

/**
* Fetches common footer code
*
* Fetches and parses all footer code that is common across all pages. This normally
* includes the copyleft notice, the version, and the few links that link to credits
* feedback and other areas of Flare where no authentication is required.
*
* @return bool Always returns true;
*/
function assign_footer() {
    global $tpl, $cfg;

    $tpl->assign(array(
        "_COPYLEFT"         => _COPYLEFT,
        "_PROJECT_OF"       => _PROJECT_OF,
        "_LICENSE"          => _LICENSE,
        "_CREDITS"          => _CREDITS,
        "_PRIV_POLICY"      => _PRIV_POLICY,
        "_HELP_FEEDBACK"    => _HELP_FEEDBACK,
        "_USAGE_POLICY"     => _USAGE_POLICY,
        "_TOS"              => _TOS,
        "_VERSION"          => $cfg['version']
    ));

    $footer = $tpl->fetch('footer.tpl');
    $tpl->assign('_FOOTER', $footer);
    return true;
}

/**
* Checks current Flare configuration
*
* Checks to make sure the current Flare installation does not have any remnants
* left over from the install. Also checks for common pitfalls that may have tried
* to have been passed off to Flare during installation.
*
* @return bool true|false 0 (False) on success or Error encountered on failure.
*/
function check_config() {
    /**
    * Check if setup directory exists
    *
    * This has the potential to be a major flaw if not taken care of. By
    * still existing, the setup directory allows anyone with a browser
    * to essentially overwrite the entire Flare configuration. This is
    * obviously a security problem. That is why we should fail if the
    * check finds this problems
    */
    if (is_dir('setup')) {
        /**
        * The Filesystem extension is used to remove the offending folder
        */
        if (is_file(ABSPATH.'/extensions/Filesystem/class.Filesystem.php')) {
            require_once(ABSPATH.'/extensions/Filesystem/class.Filesystem.php');
            $fs = new Filesystem;

            // Remove the setup folder
            $fs->rm_dir('setup');

            // Check to make sure it doesnt exist. If it does, we need to error.
            if (is_dir('setup'))
                return _SETUP_STILL_EXISTS;
        } else
            return _SETUP_STILL_EXISTS;
    }

    /**
    * The next check is for the setup php file
    *
    * This file can also be used to overwrite an existing Flare configuration if it
    * falls into the wrong hands. Therefore, it must be removed.
    */
    if(file_exists(ABSPATH.'/setup.php')) {
        // Delete the file
        unlink(ABSPATH."/setup.php");

        // Make sure it doesnt exist. If it does, then throw an error.
        if(file_exists(ABSPATH.'/setup.php'))
            return _SETUP_FILE_STILL_EXISTS;
    }

    /**
    * Remove the default config file
    *
    * Flare is shipped with a default configuration file. Inside of this file are
    * default usernames and passwords for accessing a database. Should you actually
    * have usernames and passwords that match, this could potentially be a problem.
    * Therefore, its deemed unnecessary and must be removed.
    */
    if(file_exists(ABSPATH.'/config-inc.php.dist')) {
        unlink(ABSPATH."/config-inc.php.dist");

        if(file_exists(ABSPATH.'/config-inc.php.dist'))
            return _CONFIG_DIST_EXISTS;
    }

    /**
    * Check default database user is not root
    *
    * This should have been checked for during the install, but in case it wasnt, we
    * check again. If the Flare user account is root, all sorts of security problems
    * can arise. The best bet is to just create an underprivileged user account for
    * Use with Flare. During install, the account must have the typical INSERT, SELECT,
    * UPDATE and DELETE permissions, but also the CREATE permission. After installation
    * CREATE permission can be removed.
    */
    if (_DBUSER == "root")
        return _MYSQL_NO_ROOT;

    /**
    * Check to make sure no blank database password
    *
    * for the same reason above. Blank passwords are security risks rightout. Therefore
    * we dont possibly want to be associated with them. Therefore we die if the password
    * for the database user is blank.
    */
    if (_DBPWRD == "")
        return _BLANK_PASSWORD;

    /**
    * Check for register_globals
    *
    * register_globals has been a security threat since it's inception.
    * We strictly deny running if it has been turned on. While this wont catch globals
    * that may have been registered before this method, it should hopefully catch using
    * it after this method has run.
    */
    $register_globals = ini_get('register_globals');
    if ($register_globals == "On" || $register_globals == "on")
        return _REGISTER_GLOBALS;
}

/**
* Gets the whole Flare config database
*
* Flare allows extensions to store their configuration entries in a config table.
* Because an extension may require these config entries at any time, Flare makes
* it a priority to get all the available config entries from the config table.
*
* @return array $cfg The entire configuration table.
*/
function get_config() {
    global $db;

    $cfg = array();
    $sql = array (
        "mysql" => array (
            'config' => "SELECT name,value FROM "._PREFIX."_config ORDER BY name ASC"
            )
    );

    $stmt1 = $db->prepare($sql[_DBSYSTEM]['config']);

    try {
        $stmt1->execute();
    } catch ( MySQL_Exception $e) {
        return FALSE;
    }

    while ($row = $stmt1->fetch_array()) {
        $cfg[$row['name']] = $row['value'];
    }

    $home_root  = import_var('home_root', 'S');
    $home_dir   = import_var('home_dir', 'S');
    $group_root = import_var('group_root', 'S');
    $group_dir  = import_var('group_dir', 'S');

    if ($home_root != "")
        $cfg['home_root']   = $home_root;
    if ($home_dir != "")
        $cfg['home_dir']    = $home_dir;
    if ($group_root != "")
        $cfg['group_root']  = $group_root;
    if ($group_dir != "")
        $cfg['group_dir']   = $group_dir;

    return $cfg;
}

/**
* Checks to see if the user is an admin
*
* We define admin as anyone who has an entry in the admin_privs table. Therefore
* as long as you admin an extension, you're considered an admin and should be
* let through to the admin page. Note that you'll only see extensions for which
* you are an admin of.
*
* @param integer $user_id The user_id for which we are checking admin status
* @param integer $extension_id The extension_id for which we are checking admin status of the user
* @return bool true on success, false on failure.
*/
function is_admin($user_id, $extension_id) {
    global $db;

    $sql = array(
        "mysql" => array(
            "admin" => "SELECT * FROM "._PREFIX."_admin_privs WHERE user_id=':1' AND extension_id=':2'",
            "any" => "SELECT * FROM "._PREFIX."_admin_privs WHERE user_id=':1'"
        )
    );

    $stmt1 = $db->prepare($sql[_DBSYSTEM]["admin"]);
    $stmt2 = $db->prepare($sql[_DBSYSTEM]["any"]);

    if ($extension_id === false) {
        $stmt2->execute($user_id);
        return ($stmt2->num_rows() > 0) ? true : false;
    } else {
        $stmt1->execute($user_id, $extension_id);
        return ($stmt1->num_rows() > 0) ? true : false;
    }
}

/**
* Cleans form input
*
* This is currently just a skeleton function. It is (in the future) intended to be a gateway
* for sanitizing possible data that may be submitted via forms.
*
* @param mixed $input Data which you wish to have cleaned
* @return mixed Return cleaned input
*/
function cleanse_input($input) {
    return $input;
}

?>
