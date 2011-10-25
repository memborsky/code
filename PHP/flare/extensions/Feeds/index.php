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

class Feeds {

}

header("Content-Type: text/xml");

echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n\n";
echo "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\"\n";
echo "'http://my.netscape.com/publish/formats/rss-0.91.dtd'>\n\n";
echo "<rss version='0.91'>\n\n";
echo "<channel>\n";
echo "<title>".htmlspecialchars($sitename)."</title>\n";
echo "<link>$nukeurl</link>\n";
echo "<description>".htmlspecialchars($backend_title)."</description>\n";
echo "<language>$backend_language</language>\n\n";

while ($row = $db->sql_fetchrow($result)) {
    $rsid = intval($row['sid']);
    $rtitle = $row['title'];
    echo "<item>\n";
    echo "<title>".htmlspecialchars($rtitle)."</title>\n";
    echo "<link>$nukeurl/modules.php?name=News&amp;file=article&amp;sid=$rsid</link>\n";
    echo "</item>\n\n";
}
echo "</channel>\n";
echo "</rss>";

?>
