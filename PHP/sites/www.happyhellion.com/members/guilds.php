<div class="guilds">
<?php

global $guild;
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
    $arr = array();
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {
        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

function multiarray($mutliarray, $level = 2) {
  if (is_array($mutliarray)) {
    if ($level == 1) {
      return true;
    }
    foreach ($mutliarray as $array) {
      if (multiarray($array, $level - 1)) {
        return true;
      }
    }
  } else {
    return false;
  }
}

function table_header($rank) {
  switch ($rank) {
    case "Leader":
      return "\n<div id=\"leader\">\n<table>  \n    <tr>\n      <td><h1>" . $rank . "</h1></td>\n    </tr>\n    <tr>\n";
      break;
    case "Officer":
      return "\n<div id=\"officer\">\n<table>  \n    <tr>\n      <td colspan=\"3\"><h1>" . $rank . "s</h1></td>\n    </tr>\n    <tr>\n";
      break;
    case "Member":
      return "\n<div id=\"member\">\n<table>  \n    <tr>\n      <td colspan=\"4\"><h1>" . $rank . "s</h1></td>\n    </tr>\n    <tr>\n";
      break;
  }
}

function table_footer() {
  return "    </tr>\n  </table>\n</div>";
}

function check($pShort, $method) {
  global $guild;
  switch ($method) {
    case "avatar":
      if (!file_exists("guilds/" . $guild . "/" . $pShort . ".jpg")) {
        return "guilds/" . $guild . ".jpg";
      } else {
        return "guilds/" . $guild . "/" . $pShort . ".jpg";
      }
      break;
    case "link":
      if (file_exists("guilds/" . $guild . "/" . $pShort . ".xml")) {
        return true;
      } else {
        return false;
      }
      break;
  }
}

function array_to_cell($person) {
  global $guild;
  switch ($person['link']) {
    case true:
      return "<td colspan=\"1\"><div id=\"nlinked\"><a href=\"?guild="
      . $guild . "&amp;member=" . $person['short'] . "\"><img src=\""
      . $person['avatar'] . "\" alt=\"" . $person['name'] . "\"><br>"
      . $person['name'] . "</a><br><h2>HP - " 
      . (!empty($person['hp']) ? $person['hp'] : "?")
      . "</h2></div></td>";
      break;
    case false:
      return "<td colspan=\"1\"><div id=\"nolink\"><img src=\""
      . $person['avatar'] . "\" alt=\"" . $person['name'] . "\"><br>"
      . $person['name'] . "</a><br><h2>HP - "
      . (!empty($person['hp']) ? $person['hp'] : "?")
      . "<h2></div></td>";
      break;
    default:
      break;
  }
}

function new_table_row() {
  echo "    </tr>\n    <tr>\n";
}

$xml = simplexml_load_file("guilds/" . $guild . ".xml");
$data = object_to_array($xml);


// Create leader table and output it.
$data['leader']['link'] = check($data['leader']['short'], "link");
$data['leader']['avatar'] = check($data['leader']['short'], "avatar");
echo table_header("Leader");
  echo array_to_cell($data['leader']);
echo table_footer();
echo "\n\n<br><br>\n\n";

// Create officer table and output it.
if (!empty($data['officer'])) {
  if (multiarray($data['officer'])) {
    asort($data['officer']);
    $count = 0;
    echo table_header("Officer");
      foreach ($data['officer'] as &$officer) {
        $officer['link'] = check($officer['short'], "link");
        $officer['avatar'] = check($officer['short'], "avatar");
        if ($count === 3) {
          echo new_table_row();
          $count = 0;
        }
        echo array_to_cell($officer);
        $count++;
      }
    echo table_footer();
    echo "\n\n<br><br>\n\n";
  } else {
    $data['officer']['link'] = check($data['officer']['short'], "link");
    $data['officer']['avatar'] = check($data['officer']['short'], "avatar");
    echo table_header("Officer");
    echo array_to_cell($data['officer']);
    echo table_footer();
  }
}


// Create Member table and output it.
if (!empty($data['member'])) {
  if (multiarray($data['member'])) {
    asort($data['member']);
    $count = 0;
    echo table_header("Member");
      foreach ($data['member'] as &$member) {
        $member['link'] = check($member['short'], "link");
        $member['avatar'] = check($member['short'], "avatar");
        if ($count == 4) {
          echo new_table_row();
          $count = 0;
        }
        echo array_to_cell($member);
        $count++;
      }
    echo table_footer();
  } else {
    $data['member']['link'] = check($data['member']['short'], "link");
    $data['member']['avatar'] = check($data['member']['short'], "avatar");
    echo table_header("Member");
    echo array_to_cell($data['member']);
    echo table_footer();
  }
}

$nDebug = (!empty($_GET["debug"]) ? $_GET["debug"] : false);
if ($nDebug) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

?>
</div>
