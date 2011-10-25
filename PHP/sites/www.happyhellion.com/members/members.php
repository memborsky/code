<div id="member">
<?php

global $guild;
global $member;
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

function object_to_array($obj) {
  $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
  foreach ($_arr as $key => $val) {
    $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
    $arr[$key] = $val;
  }
  return $arr;
}

function create_cell($method, $tags, $data) {
  global $member; global $guild;
  switch ($method) {
    case "since":
      return $tags[$method] . " " . $data . "<br>\n";
      break;
    default:
      return $tags[$method] . " " . $data . "<br>\n";
      break;
  }
}

$xml = simplexml_load_file("guilds/" . $guild . "/" . $member . ".xml");
$person = object_to_array($xml);
$xml2 = simplexml_load_file("guilds/membertags.xml");
$tags = object_to_array($xml2);
$avatar = array(
  0 => "First",
  1 => "Second",
  2 => "Third",
  3 => "Fourth",
  4 => "Fifth",
  5 => "Sixth",
  6 => "Seventh",
  7 => "Eigth",
  null => ""
);

foreach ($person as $key => $val) {
  if (!is_array($val)) {
    if (!empty($val)) 
      echo create_cell($key, str_replace("%w", $avatar[null], $tags), $val);
  } else {
    for ($i = 0; $i < sizeof($val); $i++) {
      echo create_cell($key, str_replace("%w", $avatar[$i], $tags), $val[$i]);
    }
  }
}

if ($_GET["debug"] == true) {
  echo "<pre>";
  print_r($person);
  print_r($tags);
  echo "</pre>";
}

?>
</div>
