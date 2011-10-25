<?php

// Get list of guilds based upon who has an xml file located inside of guilds/.
$exclude = array("default.xml", "xmlGuildLayout.xml", "membertags.xml");
$guilds_temp = str_replace("guilds/", "", glob("guilds/*.xml"));
$guilds_temp = array_diff($guilds_temp, $exclude);

$guilds = array();
foreach ($guilds_temp as $guild) {
  list($guild, $null) = explode(".", $guild);
  array_push($guilds, $guild);
}

foreach ($guilds as $g) {
  echo "<center><a href=\"?guild=$g\" name=\"" . ucfirst($g) . " Guild\">" . ucfirst($g) . "</a></center>";
}

$nDebug = (!empty($_GET["debug"])) ? $_GET["debug"] : false;
if ($nDebug) {
  echo "<pre>";
  print_r($guilds_temp);
  print_r($exclude);
  print_r(array_diff($guilds_temp, $exclude));
  print_r($guilds);
  echo "</pre>";
}

?>
