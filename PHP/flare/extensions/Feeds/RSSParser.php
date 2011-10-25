<?php
/**
* @package Feeds
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

class RSSParser {
    var $insideitem = false;
    var $tag = "";
    var $title = "";
    var $description = "";
    var $link = "";

    function startElement($parser, $tagName, $attrs) {
        if ($this->insideitem) {
            $this->tag = $tagName;
        } elseif ($tagName == "ITEM") {
            $this->insideitem = true;
        }
    }

    function endElement($parser, $tagName) {
        if ($tagName == "ITEM") {
            printf("<p><b><a href='%s'>%s</a></b></p>",
            trim($this->link), htmlspecialchars(trim($this->title)));
            printf("<p>%s</p>", htmlspecialchars(trim($this->description)));
            $this->title = "";
            $this->description = "";
            $this->link = "";
            $this->insideitem = false;
        }
    }

    function characterData($parser, $data) {
        if ($this->insideitem) {
            switch ($this->tag) {
                case "TITLE":
                    $this->title .= $data;
                    break;
                case "DESCRIPTION":
                    $this->description .= $data;
                    break;
                case "LINK":
                    $this->link .= $data;
                    break;
            }
        }
    }
}

?>
