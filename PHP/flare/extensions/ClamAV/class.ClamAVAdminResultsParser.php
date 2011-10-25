<?php
/**
* @package ClamAV
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
* Required for parsing the results later in the script
*/
require_once 'XML/Parser.php';

class ClamAVAdminResultsParser extends XML_Parser {
    /**
    * Template object used to display pages
    *
    * @access public
    * @var object
    */
    public $tpl;

    /**
    * Holds the name of the current start tag
    *
    * @access public
    * @var string
    */
    public $start_tag;

    /**
    * Holds the name of the current end tag
    *
    * @access public
    * @var string
    */
    public $end_tag;

    /**
    * Holds the tags attributes
    *
    * @access public
    * @var array
    */
    public $attributes;

    /**
    * Holds array of infected files temporarily so they can be assigned later
    *
    * @access public
    * @var array
    */
    public $infected_list;

    /**
    *
    */
    public function __construct() {
        parent::XML_Parser();

        $this->__set("infected_list", array());
        $this->__set("start_tag", '');
        $this->__set("attributes", array());
        $this->__set("end_tag", '');
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
    * handle start element
    *
    * @access private
    * @param  resource  xml parser resource
    * @param  string    name of the element
    * @param  array     attributes
    */
    public function startHandler($xp, $name, $attribs) {
        $this->__set('start_tag', $name);

        if (count($attribs) > 0) {
            $tmp = $this->__get("infected_list");

            array_push($tmp, $attribs['PATH']);

            $this->__set("infected_list", $tmp);
        }
    }

    /**
    * handle start element
    *
    * @access private
    * @param  resource  xml parser resource
    * @param  string    name of the element
    */
    public function endHandler($xp, $name) {
        $this->__set('end_tag', $name);
    }

    /**
    * handle character data
    *
    * @access private
    * @param  resource  xml parser resource
    * @param  string    character data
    */
    public function cdataHandler($xp, $cdata) {
        $tagname = $this->__get("start_tag");

        $this->tpl->assign($tagname, $cdata);
    }
}

?>
