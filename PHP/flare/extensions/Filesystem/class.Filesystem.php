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
*/

/**
* Prevent direct access to the file
*/
defined( '_FLARE_INC' ) or die( "You can't access this file directly." );

/**
* Filesystem access tools
*
* This class provides low level access to filesystem
* operations. It *should* be extended by other classes
* before it is used, but this isnt enforced and it is
* safe to use this class if you only need to do simple
* operations on files
*
* @package Filesystem
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Filesystem {
    /**
    * Contains list of all directories in current working directory
    *
    * @access private
    * @var array
    */
    private $dirs;

    /**
    * Contains list of all files in current working directory
    *
    * @access private
    * @var array
    */
    private $files;

    /**
    * Total size of the all the files added up in the current working directory
    *
    * @access private
    * @var integer
    */
    private $total_size;

    /**
    * Total number of files in the current working directory
    *
    * @access private
    * @var integer
    */
    private $total_files;

    /**
    * Total number of directories in the current working directory
    *
    * @access private
    * @var integer
    */
    private $total_dirs;

    /**
    * Used to notify any listening code if an error has occured
    *
    * @access public
    * @var bool
    */
    private $error;

    /**
    * Creates an instance of Filesystem class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided. This constructor begins by setting
    * several variables to empty or null.
    *
    * @access public
    */
    public function __construct() {
        $empty = array();

        $this->__set("dirs", $empty);
        $this->__set("files", $empty);
        $this->__set("error",false);
        $this->set_total_size(0);
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
    * Get the contents of a directory
    *
    * This method is used to basically list the
    * entire contents of a directory so the user
    * can see what is in the directory. The information
    * that is gets is stored in the class variables
    * dirs, files, total_size, total_files and
    * total_dirs
    *
    * @access public
    * @param string $root The root path to the directory being listed
    * @param string $path The remaining path, from root, to the directory being listed
    */
    public function ls_dir($root, $path = '/') {
        require_once(ABSPATH.'/extensions/Filesystem/constants.mimetypes.php');

        $fullpath 	= $root . $path;
        $d 		= $f = $total_size = 0;
        $parent_dir	= 'first';
        $dirs_temp	= array();
        $files_temp	= array();
        $files		= array();
        $dirs		= array();

        /**
        * Directory listing code courtesy of Ash Young (http://evoluted.net/)
        * Modified by Tim Rupp for inclusion with Flare
        */
        // Clear any info about the files or directories that PHP may have cached
        clearstatcache();
        // If the path sent is a directory...
        if (is_dir($fullpath)) {
            // ...check to see if we can open it. If yes, store its resource in a variable
            if ($handle = opendir($fullpath)) {
                // If we're capable of opening the directory, reading files in one at a time until no more
                while (false !== ($file = readdir($handle))) {
                    if ($parent_dir == "first") {
                        // Get the parent directory if we're allowed to
                        if ($path != "" && $path != "/") {
                            $expdirs = explode("/", $path);

                            $parent_dir = '';

                            for($x = 0; $x < count($expdirs) - 2; ++$x) {
                                $parent_dir .= $expdirs[$x] . "/";
                            }

                            $parent_dir = substr($parent_dir, 0, -1);

                            // Get and format the date last modified for the directory
                            $date = date("M d Y h:i:s A", @filemtime($root . $parent_dir . "/"));

                            $dirs_temp[$parent_dir] = array("","$parent_dir","..",$date);
                        }
                    }
                    // Check to see if the filename is either the current dir, or the parent dir.
                    // and skip it if it is.
                    if ($file != "." && $file != "..") {
                        // If the read-in filename is a directory...
                        if (is_dir($fullpath . "/" . $file)) {
                            $d++;

                            // Get and format the date last modified for the directory
                            $date = date("M d Y h:i:s A", @filemtime($fullpath . "/" . $file));

                            // Pump all the data into an array for Smarty to deal with
                                $dirs_temp[$file] = array($path,$file,$file,$date);
                            } else {
                            // else, we read in a file, not a directory.
                            $f++;

                            // Check to see if the file has an extension
                            if (false !== strpos($file, "."))
                                $ext = substr(strrchr($file, "."),1);
                            else
                                $ext = 'xxx';

                            // Counter for total size of current working directory
                            $total_size += @filesize($fullpath . "/" . $file);

                            // Get and format the file size
                            $size = $this->format_space(@filesize($fullpath . "/" . $file));

                            // Get and format the date last modified for the file
                            $date = date("M d Y h:i:s A", @filemtime($fullpath . "/" . $file));

                            // Get appropriate file icon for guessed file type
                            $icon = $this->get_file_icon($ext);

                            // Pump all the data into an array for Smarty to deal with
                                $files_temp[$file] = array($path,$file,$size,$date,$icon);
                            }
                    }
                }
            }
            // Close the directory we were working with
            closedir($handle);
        } else {
            // If the path sent to us isnt a directory, return false
            $this->__set("error", TRUE);
        }

        // Make all the keys lowercase for sorting
        array_change_key_case($files_temp, CASE_LOWER);
        array_change_key_case($dirs_temp, CASE_LOWER);

        // Sort the arrays
        sort($files_temp);
        sort($dirs_temp);

        foreach ($dirs_temp as $key => $val) {
            $dirs[] = array (
                'root' => $val[0],
                'file' => $this->normalize_dir($val[1]),
                'disp' => $val[2] . "/",
                'date' => $val[3]);
        }

        foreach ($files_temp as $key => $val) {
            $files[] = array (
                'root' => $val[0],
                'file' => $val[1],
                'size' => $val[2],
                'date' => $val[3],
                'icon' => $val[4]);
        }

        // Set both arrays into their associated class variables for use
        // in any classes that may extend this class (ex. class.MyFiles.php)
        $this->__set("dirs", $dirs);
        $this->__set("files", $files);
        $this->set_total_size($total_size);
        $this->set_total_files($files);
        $this->set_total_dirs($dirs);
    }

    /**
    * Move a file or recursively move a directory
    *
    * Performs filesystem operations to safely move
    * a directory. This function should be wrapped
    * by other functions as necessary
    *
    * @access public
    * @param string $source Full path to the original item location
    * @param string $dest Full path to the new location
    */
    public function mv_dir($source, $dest) {
        /**
        * Create a unique name for the uploaded file
        */
        $nr = 0;

        if (is_dir($source)) {
            $dest = substr($dest, 0, -1);
            $tmpdest = $this->normalize_dir($dest . rand(0,100000));
        } else {
            $parts = explode(".", dest);
            $tmpdest = $this->normalize_file($dest . rand(0,100000));
        }

        $this->copyr($source, $tmpdest);
        $this->rm_dir($source);
        $this->copyr($tmpdest, $dest);
        $this->rm_dir($tmpdest);
    }

    /**
    * Copy a file, or recursively copy a folder and its contents.
    *
    * Source code for copyr lifted from Aidan's PHP Repository
    * http://aidan.dotgeek.org/lib/?file=function.copyr.php
    *
    * @author      Aidan Lister <aidan@php.net>, Tim Rupp <caphrim007@gmail.com>
    * @version     1.0.1
    * @param       string   $source    Source path
    * @param       string   $dest      Destination path
    * @return      bool     Returns TRUE on success, FALSE on failure
    */
    public function copyr($source, $dest) {
        if (!file_exists($source))
            return false;

        // Simple copy for a file
        if (is_file($source)) {
            $files = $this->__get("files");
            array_push($files, $source . ':' . $dest);
            $this->__set("files", $files);

            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);

            $dirs = $this->__get("dirs");
            array_push($dirs, $source . ':' . $dest);
            $this->__set("dirs", $dirs);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            if ($dest !== "$source/$entry") {
                $this->copyr("$source/$entry", "$dest/$entry");
            }
        }

        // Clean up
        $dir->close();

        return true;
    }

    /**
    * Remove a file or folder
    *
    * Source code for rm_dir lifted from Aidan's PHP Repository
    * http://aidan.dotgeek.org/lib/?file=function.rmdirr.php
    *
    * @author      Aidan Lister <aidan@php.net>, Tim Rupp <caphrim007@gmail.com>
    * @version     1.0.1
    * @access public
    * @param string $dirname Full path to the item that is to be removed
    */
    public function rm_dir($dirname) {
        // Kludge to take care of phantom links
        if (!file_exists($dirname) && is_link($dirname)) {
            unlink($dirname);
            $this->__set("error", false);
            return;
        }

        /**
        * Sanity check. If the file somehow doesnt exist between the time
        * the button was clicked and now, return error.
        */
        if (!file_exists($dirname)) {
            $this->__set("error", true);
            return;
        }

        /**
        * Simple delete for a file
        * Added symlink checking because otherwise the recursive
        * delete will follow symlinks on linux...bad news (Tim Rupp)
        */
        if (is_file($dirname) || is_link($dirname)) {
            unlink($dirname);
            $this->__set("error", false);
            return;
        }

        // Loop through the folder
        $dir = dir($dirname);

        // While there is an entry to read out of the directory listing...
        while (false !== ($entry = $dir->read())) {
            // Skip current and parent directories
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Recurse
            $this->rm_dir("$dirname/$entry");
        }

        // Clean up. Close the directory
        $dir->close();

        // Remove the last directory (parent of all we just recursed into)
        @rmdir($dirname);

        if (is_link($dirname)) {
            /**
            * If we're here then something funky is happening. Perhaps a
            * symlink with a trailing slash?
            */

            /**
            * Remove the slash
            */
            $dirname = substr($dirname,0,-1);

            /**
            * Try deleting again
            */
            $this->rm_dir($dirname);
        } else {
            /**
            * Otherwise the file was probably deleted ok
            */
            $this->__set("error", false);
        }
    }

    /**
    * Create a directory
    *
    * Source code for mk_dir lifted from Aidan's PHP Repository
    * http://aidan.dotgeek.org/lib/?file=function.mkdirr.php.
    * Recursive functionality removed by Tim Rupp.
    *
    * @author Aidan Lister <aidan@php.net>, Tim Rupp <caphrim007@gmail.com>
    * @version 1.0.0
    * @param string $pathname The directory structure to create
    * @return bool Returns TRUE on success, FALSE on failure
    */
    public function mk_dir($dir, $mode = null) {
        // Seperator for directories
        $dirSeperator 	= "/";

        // We need to also set a default mode on all directories that are made
        // rwxr-xr-x is the default here
        if (is_null($mode))
            $mode	= 0755;

        // Check if directory already exists
        if (is_dir($dir) || empty($dir)) {
            // If it does, then return FALSE, no problems, we succesfully made the
            // directory I guess :-p
            $this->__set("error",FALSE);
            return TRUE;
        } else {
            // Ensure a file does not already exist with the same name
            if (is_file($dir)) {
                // If it does, return an error
                $this->__set("error",TRUE);
                return FALSE;
            } else {
                // Otherwise, crawl up the directory tree
                if (!file_exists($dir)) {
                    mkdir($dir, $mode);
                }
            }
        }
        return FALSE;
    }


    /**
    * Get the size of a directory.
    *
    * Source code for dirsize lifted from Aidan's PHP Repository
    * http://aidan.dotgeek.org/repos/?file=function.dirsize.php
    *
    * @author      Aidan Lister <aidan@php.net>
    * @version     1.0.0
    * @param       string   $directory    Path to directory
    */
    public function dirsize($directory) {
        // Init
        $size = 0;

        if (is_file($directory))
            return filesize($directory);

        // Trailing slash
        if (substr($directory, -1, 1) !== DIRECTORY_SEPARATOR) {
            $directory .= DIRECTORY_SEPARATOR;
        }

        // Creating the stack array
        $stack = array($directory);

        // Iterate stack
        for ($i = 0, $j = count($stack); $i < $j; ++$i) {
            // Add to total size
            if (is_file($stack[$i])) {
                $size += filesize($stack[$i]);
            } elseif (is_dir($stack[$i])) {
                // Add to stack

                // Read directory
                $dir = dir($stack[$i]);

                while (false !== ($entry = $dir->read())) {
                    // No pointers
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }

                    // Add to stack
                    $add = $stack[$i] . $entry;
                    if (is_dir($stack[$i] . $entry)) {
                        $add .= DIRECTORY_SEPARATOR;
                    }
                    $stack[] = $add;
                }

                // Clean up
                $dir->close();
            }

            // Recount stack
            $j = count($stack);
        }

        return $size;
    }

    /**
    * Makes a symbolic link
    *
    * Will make a symbolic link to a directory
    *
    * @access public
    * @param string $tgt Full path to destination where the link will point to
    * @param string $dir Full path to what the symbolic link will be called. Includes link name
    * @return bool True on failure, false on success
    */
    public function mk_link($tgt, $dir) {
        // Converted to using PHP built in function for Linux
        symlink($tgt, $dir);

        // Check to make sure the link was actually created
        if(!is_link($dir))
            $this->__set("error",TRUE);
        else
            $this->__set("error",FALSE);
    }

    /**
    * Sets the total size class variable
    *
    * The total size contains the total size of
    * all files in the current working directory
    * but it is a private variable. This will set
    * it.
    *
    * @access private
    * @param integer $size Total size of the current working directory
    */
    private function set_total_size($size) {
        $this->total_size = $this->format_space($size);
    }

    /**
    * Sets the total number of directories
    *
    * Sets the total number of directories in the
    * current working directory. Since the class
    * variable is private, a setter function is
    * needed to change it.
    *
    * @access private
    * @param integer $dirs Total number of directories in current working directory
    */
    private function set_total_dirs($dirs) {
        if (is_array($dirs))
            $this->total_dirs = count($dirs);
        else if (is_numeric($dirs))
            $this->total_dirs = $dirs;
        else
            $this->total_dirs = 0;

    }

    /**
    * Sets the total number of files
    *
    * Sets the total number of files in the
    * current working directory. Since the class
    * variable is private, a setter function is
    * needed to change it.
    *
    * @access private
    * @param integer $files Total number of files in current working directory
    */
    private function set_total_files($files) {
        if (is_array($files))
            $this->total_files = count($files);
        else if (is_numeric($files))
            $this->total_files = $files;
        else
            $this->total_files = 0;
    }

    /**
    * Formats a size in bytes
    *
    * Formats a file size in bytes so that it is
    * in a human readable format. The maximum size
    * that it will convert is to gigabytes, so
    * do not expect a readable value if you are
    * passing in thousands of gigabytes worth of
    * filesize in bytes.
    *
    * @access public
    * @param integer $size Size in bytes of the number to format
    * @param bool $prefix Determines if only the number should be returned,
    *			or if the GB,MB, etc suffix should be returned also
    */
    public function format_space($size, $suffix = TRUE) {
        if ($size >= 1073741824) {
            $size = round(($size / 1073741824), 2);
            $result = "$size GB";
        } else if ($size >= 1048576) {
            $size = round(($size / 1048576), 2);
            $result = "$size MB";
        } else if ($size >= 1024) {
            $size = round(($size / 1024), 2);
            $result = "$size KB";
        } else {
            $result = "$size B";
        }

        if ($suffix)
            return $result;
        else {
            $temp = explode(" ", $result);
            return $temp[0];
        }
    }

    /**
    * Convert a filesize
    *
    * Provides the ability to convert between
    * different size amounts.
    *
    * @access public
    * @param integer $size Size to be converted
    * @param string $from Format to convert from
    * @param string $to Format to convert to
    * @param integer $precision Decimal precision to return result in if needed
    * @return integer|double New size after being converted
    */
    public function convert_size($size, $from, $to, $precision = 0) {
        $from	= strtolower($from);
        $to 	= strtolower($to);

        switch ($from) {
            case 'b':
                if ($to == 'kb')
                    return round(($size / 1024), $precision);
                else if ($to == 'mb')
                    return round(($size / 1048576), $precision);
                else if ($to == 'gb')
                    return round(($size / 1073741824), $precision);
                else
                    return $size;
            case 'kb':
                if ($to == 'b')
                    return round(($size * 1024), $precision);
                else if ($to == 'mb')
                    return round(($size / 1024), $precision);
                else if ($to == 'gb')
                    return round(($size / 1048576), $precision);
                else
                    return $size;
            case 'mb':
                if ($to == 'b')
                    return round(($size * 1048576), $precision);
                else if ($to == 'kb')
                    return round(($size * 1024), $precision);
                else if ($to == 'gb')
                    return round(($size / 1024), $precision);
                else
                    return $size;
            case 'gb':
                if ($to == 'b')
                    return round(($size * 1073741824), $precision);
                if ($to == 'kb')
                    return round(($size * 1048576), $precision);
                else if ($to == 'mb')
                    return round(($size * 1024), $precision);
                else
                    return $size;
        }
    }

    /**
    * Gets the icon for a file extension
    *
    * Flare includes icons for various file extensions.
    * This will return the current icon that is associated
    * with a given file extension
    *
    * @access private
    * @param string $extension File extension to get icon for
    * @return string Link to file icon as defined in constants.mimetypes.php
    */
    private function get_file_icon($extension) {
        /**
        * All constants that are returned below are defined in the file
        * extensions/Filesystem/constants.mimetypes.php
        */

        // Check to see if developer sent us an extension without removing the preceding decimal
        if (false === strpos($extension, "."))
            $extension = str_replace(".", "", $extension);

        // Make the extension all lowercase for ease of switching
        $extension = strtolower($extension);

        // Switch extension and return filetype as necessary
        switch ($extension) {
            case "avi":
                return _MIME_AVI;
                break;
            case "bin":
                return _MIME_BIN;
                break;
            case "c":
                return _MIME_C;
                break;
            case "cpp":
                return _MIME_CPP;
                break;
            case "deb":
                return _MIME_DEB;
                break;
            case "doc":
                return _MIME_DOC;
                break;
            case "dvi":
                return _MIME_DVI;
                break;
            case "exe":
                return _MIME_EXE;
                break;
            case "fla":
                return _MIME_FLA;
                break;
            case "gif":
                return _MIME_GIF;
                break;
            case "h":
                return _MIME_H;
                break;
            case "htm":
            case "html":
                return _MIME_HTML;
                break;
            case "info":
                return _MIME_INFO;
                break;
            case "iso":
                return _MIME_ISO;
                break;
            case "java":
                return _MIME_JAVA;
                break;
            case "jpg":
            case "jpeg":
                return _MIME_JPG;
                break;
            case "log":
                return _MIME_LOG;
                break;
            case "make":
                return _MIME_MAKE;
                break;
            case "man":
                return _MIME_MAN;
                break;
            case "midi":
                return _MIME_MIDI;
                break;
            case "moc":
                return _MIME_MOC;
                break;
            case "mpg":
                return _MIME_MPG;
                break;
            case "o":
                return _MIME_O;
                break;
            # OpenOffice.org Base
            case "odb":
                return _MIME_ODB;
                break;
            # OpenOffice.org Math
            case "odf":
            case "sxm":
                return _MIME_ODF;
                break;
            # OpenOffice.org Draw
            case "odg":
            case "otg":
            case "sxd":
            case "std":
                return _MIME_ODG;
                break;
            # OpenOffice.org Presentation
            case "odp":
            case "otp":
            case "sxi":
            case "sti":
                return _MIME_ODP;
                break;
            # OpenOffice.org Spreadsheet
            case "ods":
            case "ots":
            case "sxc":
            case "stc":
                return _MIME_ODS;
                break;
            # OpenOffice.org Writer
            case "odt":
            case "ott":
            case "sxw":
            case "stw":
                return _MIME_ODT;
                break;
            case "pdf":
                return _MIME_PDF;
                break;
            case "php":
                return _MIME_PHP;
                break;
            case "pk":
                return _MIME_PK;
                break;
            case "pl":
                return _MIME_PL;
                break;
            case "ps":
                return _MIME_PS;
                break;
            case "psp":
                return _MIME_PSP;
                break;
            case "py":
                return _MIME_PY;
                break;
            case "qt":
                return _MIME_QT;
                break;
            case "readme":
                return _MIME_README;
                break;
            case "real":
            case "rm":
                return _MIME_REAL;
                break;
            case "rpm":
                return _MIME_RPM;
                break;
            case "sh":
                return _MIME_SH;
                break;
            case "swf":
                return _MIME_SWF;
                break;
            case "tar":
                return _MIME_TAR;
                break;
            case "tex":
                return _MIME_TEX;
                break;
            case "gz":
            case "tgz":
                return _MIME_TGZ;
                break;
            case "tmp":
                return _MIME_TMP;
                break;
            case "tty": // TrueType file. Not tty as in teletype console device
                return _MIME_TTY;
                break;
            case "txt":
                return _MIME_TXT;
                break;
            case "xls":
                return _MIME_XLS;
                break;
            case "zip":
                return _MIME_ZIP;
                break;
            default:
                return _MIME_UNKNOWN;
                break;
        }
    }

    /**
    * Downloads a file
    *
    * Allows a user to download a chosen file to their
    * computer.
    *
    * @access public
    * @param string $path Full path to the file to be sent to the user
    * @param string $file Filename to be shown in the file download box.
    */
    public function do_download_file($path, $file) {
        require_once('HTTP/Download.php');

        $path .= $file;
        $size = filesize($path);

        $params = array(
            'file'	=> $path,
            'cache'	=> false,
            'contenttype'	=> 'application/octet-stream',
            'contentdisposition'	=> array(HTTP_DOWNLOAD_ATTACHMENT, $file),
        );

        $error = HTTP_DOWNLOAD::staticSend($params, false);

        $this->log->log('FILE_DOWNLOAD', $path, time());
    }

    /**
    * Remove bad pathnames
    *
    * The user should not be requesting access to certain
    * paths. This removes those paths from variables that
    * might be sent to us.
    *
    * @access public
    * @param string $dir Path to be checked for bad path names
    * @return string Path with any possible bad path names removed
    */
    public function strip_bad_navigation($dir) {
        /**
        * Very early version of striping bad directory navigation
        * from directories. This should be improved upon as necessary
        */
        if (false !== strpos($dir, "..")) {
            return str_replace("..", "/", $dir);
        } else {
            return $dir;
        }
    }

    /**
    * Normalizes a directory
    *
    * We define a normalized directory as being a path
    * with only a single forward slash seperating directories
    * and having a forward slash at the end of the string
    *
    * @access public
    * @param string $dir Path to be normalized
    * @example /this/is/a/path/ Example result of a normalized path
    * @return string Normalized path
    */
    public function normalize_dir($dir) {
        if (substr($dir, 0, 1) != '/')
            $dir = '/' . $dir;

        if (substr($dir, -1, 1) != '/')
            $dir .= '/';

        return preg_replace("/\/+/", "/", $dir);
    }

    /**
    * Normalizes a file
    *
    * We define a normalized file as being a path
    * with only a single forward slash seperating
    * directories and having no forward slash at
    * the end of the string
    *
    * @access public
    * @param string $file File to be normalized
    * @example /this/is/a/file Example result of a normalized file
    * @return string Normalized path
    */
    public function normalize_file($file) {
        if (substr($file, 0, 1) != '/')
            $file = '/' . $file;

        if (substr($file, -1, 1) == '/')
            $file = substr($file, 0, -1);

        return preg_replace("/\/+/", "/", $file);
    }

    /**
    * Removes forbidden characters
    *
    * When uploading files or creating new archives
    * or directories, there are many characters that
    * we do not allow to be included in the filename.
    * This method strips those characters from the
    * item and replaces them with allowed characters.
    *
    * @access public
    * @param string $item Name or path to have forbidden characters stripped from
    * @param string $type Type of item being worked on. This is included because
    *			we may want to allow certain characters for certain
    *			types of items
    * @return string Path or filename with forbidden characters removed
    */
    public function strip_forbidden_chars($item, $type) {
        switch ($type) {
            case "dir":
                $item	= strtr($item, array(	"&" => "_",
                                "\\" => "_",
                                "/" => "_",
                                "=" => "_",
                                "<" => "_",
                                ">" => "_",
                                "." => "_",
                                "," => "_",
                                "^" => "_",
                                "#" => "_",
                                ";" => "_",
                                ":" => "_",
                                "*" => "_",
                                "(" => "_",
                                ")" => "_",
                                "@" => "_",
                                "!" => "_",
                                " " => "_",
                                "'" => "_",
                                "\"" => "_",
                                "|" => "_",
                                "?" => "_",
                                "%" => "_",
                                "+" => "_",
                                "=" => "_",
                                "*" => "_",
                                "`" => "_",
                                "~" => "_",
                                "$" => "_", ));
                return $item;
            default:
            case "group":
            case "file":
                $item = strtr($item, array("&" => "and",
                            " " => "_",
                            "\\" => "_",
                            "/" => "_",
                            "=" => "_",
                            "<" => "_",
                            ">" => "_",
                            "," => "_",
                            "^" => "_",
                            "#" => "_",
                            ";" => "_",
                            ":" => "_",
                            "*" => "_",
                            "(" => "_",
                            ")" => "_",
                            "@" => "_",
                            "!" => "_",
                            " " => "_",
                            "'" => "",
                            "\"" => "_",
                            "|" => "_",
                            "?" => "_",
                            "%" => "_",
                            "+" => "_",
                            "=" => "_",
                            "*" => "_",
                            "`" => "_",
                            "~" => "_",
                            "$" => "_" ));
                return $item;
        }
    }
}

?>
