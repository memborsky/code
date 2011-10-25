<?php
/**
* @package Error_Handler
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

require_once(ABSPATH.'/extensions/Error_Handler/class.General_Exception.php');

/**
* Postgre Exception handler
*
* Provides tools to access general parts of
* Postgre specific errors that are thrown
*
* @package Error_Handler
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class Postgre_Exception extends General_Exception {
    /**
    * Maintains a backtrace of all calls up to and including the error
    *
    * @access public
    * @var array
    */
    public $backtrace;

    /**
    * Creates an instance of MSSQL_Exception class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided. This constructor begins by setting
    * several of the available class variables to information that
    * is provided by PHP and the developer of the extension.
    *
    * @access public
    */
    public function __construct() {
        $this->backtrace	=	debug_backtrace();
    }
}

?>
