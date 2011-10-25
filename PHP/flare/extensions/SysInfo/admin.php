<?php 
/**
* @package SysInfo
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/

/**
* phpSysInfo - A PHP System Information Script
* http://phpsysinfo.sourceforge.net/
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
* $Id: admin.php 922 2005-11-06 02:58:02Z tim $
* phpsysinfo release version number
*/

$VERSION = "2.3";

define('APP_ROOT', "");

set_magic_quotes_runtime(0);

if (!extension_loaded('xml')) {
  echo '<center><b>Error: phpsysinfo requires xml module.</b></center>';
  exit;

}

// reassign HTTP variables (incase register_globals is off)
if (!empty($HTTP_GET_VARS)) while (list($name, $value) = each($HTTP_GET_VARS)) $$name = $value;
if (!empty($HTTP_POST_VARS)) while (list($name, $value) = each($HTTP_POST_VARS)) $$name = $value;

require('includes/lang/' . $cfg['language'] . '.php'); // get our language include

// Figure out which OS where running on, and detect support
	require('includes/os/class.' . PHP_OS . '.inc.php');
	$sysinfo = new sysinfo;

if (!empty($sensor_program)) {
		require('includes/mb/class.' . $sensor_program . '.inc.php');
		$mbinfo = new mbinfo;
}

require('includes/common_functions.php'); // Set of common functions used through out the app
require('includes/xml/vitals.php');
require('includes/xml/network.php');
require('includes/xml/hardware.php');
require('includes/xml/memory.php');
require('includes/xml/filesystems.php');
require('includes/xml/mbinfo.php');

$xml = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
$xml .= "<!DOCTYPE phpsysinfo SYSTEM \"phpsysinfo.dtd\">\n\n";
$xml .= created_by();
$xml .= "<phpsysinfo>\n";
$xml .= "  <Generation version=\"$VERSION\" timestamp=\"" . time() . "\"/>\n";
$xml .= xml_vitals();
$xml .= xml_network();
$xml .= xml_hardware();
$xml .= xml_memory();
$xml .= xml_filesystems();
if (!empty($sensor_program)) {
	$xml .= xml_mbtemp();
	$xml .= xml_mbfans();
	$xml .= xml_mbvoltage();
} ;
$xml .= "</phpsysinfo>";

function makebox ($title, $content, $percent) {
	global $tpl;
	$tpl->assign('TITLE', $title);
	$tpl->assign('CONTENT', $content);
	if (empty($content)) 
		return '';
	else
		return $tpl->fetch('sysinfo_box.tpl'); 
}
 
// Fire off the XPath class
require('includes/XPath.class.php');

$XPath = new XPath();
$XPath->importFromString($xml); 

$tpl->assign('TITLE', $text['title'] . ': ' . $XPath->getData('/phpsysinfo/Vitals/Hostname') . ' (' . $XPath->getData('/phpsysinfo/Vitals/IPAddr') . ')');

$tpl->assign('VITALS', makebox($text['vitals'], html_vitals(), '100%'));
$tpl->assign('NETWORK', makebox($text['netusage'], html_network(), '100%'));
$tpl->assign('HARDWARE', makebox($text['hardware'], html_hardware(), '100%'));
$tpl->assign('MEMORY', makebox($text['memusage'], html_memory(), '100%'));
$tpl->assign('FILESYSTEMS', makebox($text['fs'], html_filesystems(), '100%'));

if (!empty($sensor_program)) {
	$tpl->assign('MBTEMP', makebox($text['temperature'], html_mbtemp(), '100%'));
	$tpl->assign('MBFANS', makebox($text['fans'], html_mbfans(), '100%'));
	$tpl->assign('MBVOLTAGE', makebox($text['voltage'], html_mbvoltage(), '100%'));
} else {
	$tpl->assign('MBTEMP', '');
	$tpl->assign('MBFANS', '');
	$tpl->assign('MBVOLTAGE', '');
}

// parse our the template
$tpl->display('sysinfo_form.tpl');

?>
