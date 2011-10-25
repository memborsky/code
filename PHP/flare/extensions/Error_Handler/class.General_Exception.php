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

/**
* General Exception handler
*
* Provides tools to access general features of
* errors that are thrown
*
* @package Error_Handler
* @access public
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/
class General_Exception extends Exception {
    /**
    * The filename where the error was encountered
    *
    * @access protected
    * @var string
    */
    protected $file;

    /**
    * The line number where the error occured
    *
    * @access protected
    * @var integer
    */
    protected $line;

    /**
    * The message provided for why the error was thrown
    *
    * @access protected
    * @var string
    */
    protected $message;

    /**
    * The error code for the above message
    *
    * @access protected
    * @var integer
    */
    protected $code;

    /**
    * Creates an instance of General_Exception class
    *
    * This is a default constructor to override the one otherwise
    * created by PHP. This constructor need not do anything complex
    * so a basic one is provided. This constructor begins by setting
    * several of the available class variables to information that
    * is provided by PHP and the developer of the extension.
    *
    * @access public
    */
    public function __construct($message = FALSE, $code = FALSE) {
        $this->file	=	__FILE__;
        $this->line	=	__LINE__;
        $this->message	=	$message;
        $this->code	=	$code;
    }

    /**
    * Returns the file name where the error occured
    *
    * @access public
    * @return string Filename where the error occured
    */
    public function get_file() {
        return $this->file;
    }

    /**
    * Returns the line number where the error occured
    *
    * @access public
    * @return integer The line number where the error was thrown
    */
    public function get_line() {
        return $this->line;
    }

    /**
    * Returns developer assigned error message
    *
    * @access public
    * @return string Message created by developer to describe the error encountered
    */
    public function get_message() {
        return $this->message;
    }

    /**
    * Return error code for specific error message
    *
    * If the developer has created a code to match the
    * error message, then this will return that code number
    *
    * @access public
    * @return integer Developer assigned error code
    */
    public function get_code() {
        return $this->code;
    }
}

?>
