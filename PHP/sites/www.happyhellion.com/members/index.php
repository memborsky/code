<?php

if (file_exists("config-inc.php")) {
  require ("config-inc.php");
  $DR = "./";
} else {
  $DR = "";
  while(!file_exists($DR . "config-inc.php")) {
    $DR .= "../";
  }
  require ($DR . "config-inc.php");
}

$method = "";
$guild  = (!empty($_GET["guild"])) ? $_GET["guild"] : "";
$member = (!empty($_GET["member"])) ? $_GET["member"] : "";
switch ($guild) {
  case "nation":
  case "network":
  case "Girl Power":
    if (file_exists("guilds/" . $guild . "/" . $member . ".xml")) {
      $strPageTitle = ucfirst($member);
      $method = "members";
    } else {
      $strPageTitle = ucfirst($guild) . " Guild";
      $method = "guilds";
    }
    break;
  default:
    $strPageTitle = "List of Alliance Guilds";
    $method = "default";
    break;
}
  require($DR . "inc/head.php");
  include($method . ".php");
  require($DR . "inc/foot.php");

?>
