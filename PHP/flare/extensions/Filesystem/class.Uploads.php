<?php
/**
* @package Filesystem
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
*
* -------------------------------------------------------------------------
* Coppermine Photo Gallery 1.3.2
* -------------------------------------------------------------------------
* Copyright (C) 2002-2004 Gregory DEMAR
* http://www.chezgreg.net/coppermine/
* -------------------------------------------------------------------------
* Updated by the Coppermine Dev Team
* (http://coppermine.sf.net/team/)
* see /docs/credits.html for details
* -------------------------------------------------------------------------
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
* -------------------------------------------------------------------------
*/

/**
* Prevent direct access to the file
*/
defined( '_FLARE_INC' ) or die( "You can't access this file directly." );

require_once (ABSPATH.'/extensions/Filesystem/class.Filesystem.php');

/**
* Upload tools
*
* This class provides tools that perform file
* upload operations; one of the more crucial
* parts of the Flare system
*
* @package Filesystem
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Uploads extends Filesystem {
    /**
    * Contains all settings stored in the database that relate to this extension
    *
    * @access public
    * @var object
    */
    public $cfg;

    /**
    * Logging object used to log actions to the database
    *
    * @access public
    * @var object
    */
    public $log;

    /**
    * Success status of an operation
    *
    * @access private
    * @var bool
    */
    private $status;

    /**
    * Fullpath to the uploaded file
    *
    * @access private
    * @var string
    */
    private $uploaded_filename;

    /**
    * Creates an instance of Uploads class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided that simply sets the current status
    * to false (no errors).
    *
    * @access public
    */
    public function __construct() {
        $this->__set("status", false);
    }

    /**
    * Returns the value of a class variable
    *
    * Given a class variable name, this method will return the
    * value associated with the name.
    *
    * @access public
    * @param string $key class variable name
    * @return misc $key value stored in variable
    */
    public function __get( $key ) {
        return isset( $this->$key ) ? $this->$key : NULL;
    }

    /**
    * Sets the value of a class variable
    *
    * Given a class variable name and the value that you wish to
    * store in that variable, this method will store the supplied
    * value in the named variable
    *
    * @access public
    */
    public function __set( $key, $value ) {
        $this->$key = $value;
    }

    /**
    * Uploads a file
    *
    * File uploads are handled here. This method only
    * handles moving one file over at a time, so it
    * is left up to wrapper functions to provide multi
    * file upload functionality.
    *
    * @access public
    * @param string $name Filename of the uploaded file
    * @param integer $size Filesize in bytes of the uploaded file
    * @param string $tmp_name Temporary name, assigned by PHP, to the uploaded file
    * @param integer $error Error code assigned by PHP to the uploaded file
    * @param string $dest_dir Fullpath to the destination directory to place file in
    * @return bool Returns false on failure, true on success
    */
    public function upload_file($name, $size, $tmp_name, $type, $error, $dest_dir) {
        // Assign maximum file size for browser controls.
        $max_file_size = Filesystem::convert_size($this->cfg['max_ul_size'], 'mb','b');

        // Initialize the $matches array.
        $matches = array();

        // Used as a uniqueness marker for making filenames
        $nr = 0;

        // If there is no file name, make a dummy name for the error reporting system.
        $name = (($name == '')) ? 'filename_unavailable' : $name;

        // If magic quotes is on, remove the slashes it added to the file name.
        if (get_magic_quotes_gpc())
            $name = stripslashes($name);

        /**
        * Create the holder $file_name by translating the file name.
        * Translate any forbidden character into an underscore.
        */
        $file_name = $this->strip_forbidden_chars($name,"file");

        // Analyze the file extension using regular expressions.
        if (!preg_match("/(.+)\.(.*?)\Z/", $file_name, $matches)) {
            // The file name is invalid.
            $matches[1] = $file_name;

            // Make a bogus file extension to trigger Coppermine's defenses.
            $matches[2] = '';
        }

        // Create a unique name for the uploaded file
        while (file_exists($dest_dir . $file_name)) {
            $file_name = $matches[1] . '_-_' . $nr++ . '.' . $matches[2];
        }

        // Test for a blank file upload box.
        if (empty($tmp_name)) {
            /**
            * There is no need for further tests or action as there was no
            * uploaded file, so skip the remainder of the iteration.
            */
            $this->__set('status', false);
            return;
        }

        // Check to make sure the file was uploaded via POST.
        if (!is_uploaded_file($tmp_name)) {
            // We reject the file, and make a note of the error.
            $file_failure_array[] = array( 	'failure_ordinal'=>$failure_ordinal,
                            'file_name'=> $file_name,
                            'error_code'=>$lang_upload_php['no_post']);

            /**
            * There is no need for further tests or action,
            * so skip the remainder of the iteration.
            */
            $this->__set('status', false);
            return;
        }

        // Check for upload errors.
        if (!($error == '0') and !($error == 'default')) {
            // PHP has detected a file upload error.
            if ($error == '1')
                $error_message = $lang_upload_php['exc_php_ini'];
            elseif ($error == '2')
                $error_message = $lang_upload_php['exc_file_size'];
            elseif ($error == '3')
                $error_message = $lang_upload_php['partial_upload'];
            elseif ($error == '4')
                $error_message = $lang_upload_php['no_upload'];
            else
                $error_message = $lang_upload_php['unknown_code'];

            //Make a note in the error array.
            $this->log->log('FILE_UPLOAD_FAILURE',$dest_dir . $file_name);

            /**
            * There is no need for further tests or action,
            * so skip the remainder of the iteration.
            */
            $this->__set('status', false);
            return;
        } elseif ($size <= 0) {
            /**
            * The file contains no data or was corrupted.
            * Make a note of it in the error array.
            */
            $file_failure_array[] = array( 'failure_ordinal'=>$failure_ordinal,
                'file_name'=> $file_name, 'error_code'=>$lang_upload_php['no_file_size']);

            /**
            * There is no need for further tests or action,
            * so skip the remainder of the iteration.
            */
            $this->__set('status', false);
            return;
        } elseif ($size > $max_file_size) {
            /**
            * The file exceeds the amount specified by the max upload directive.
            * Either the browser is stupid, or somebody isn't playing nice.
            * (Ancient browser - MAX_UPLOAD forgery)
            */
            $file_failure_array[] = array( 'failure_ordinal'=>$failure_ordinal,
                'file_name'=> $file_name, 'error_code'=>$lang_upload_php['exc_file_size']);

            /**
            * There is no need for further tests or action,
            * so skip the remainder of the iteration.
            */
            $this->__set('status', false);
            return;
        }

        //Now we upload the file.
        if (!(move_uploaded_file($tmp_name, $dest_dir . $file_name))) {
            // The file upload has failed.
            $file_failure_array[] = array( 'failure_ordinal'=>$failure_ordinal,
                'file_name'=> $file_name, 'error_code'=>$lang_upload_php['impossible']);

            /**
            * There is no need for further tests or action,
            * so skip the remainder of the iteration.
            */
            $this->__set('status', false);
            return;
        }

        if (is_file($dest_dir . $file_name)) {
            // The file was placed successfully

            // Change the permissions of the newly uploaded file
            chmod($dest_dir . $file_name, 0644);

            // Log the successful upload
            $this->log->log('FILE_UPLOAD_NEW',$dest_dir . $file_name);

            // Set the file's full path for permissioning by Flare
            $this->__set('uploaded_filename', $dest_dir . $file_name);

            // Set status to success
            $this->__set("status", TRUE);
        } else {
            // The file was not placed successfully.

            // Log the failed upload
            $this->log->log('FILE_UPLOAD_FAILURE',$dest_dir . $file_name);

            // Set status to failure
            $this->__set("status", FALSE);
        }
    }
}

?>
