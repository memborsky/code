<?php

$strPageTitle = "About";
$strJavaInclude = "inc/about.js";

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


require($DR . "inc/head.php");

?>

<script type="text/javascript">
<!--

function displayToggle(strId)
{
    document.getElementById(strId).style.display = (document.getElementById(strId).style.display == 'block') ? 'none' : 'block';
}

function init()
{
    var aBlockList = new Array("about-block", "mission-block");
    for (i = 0; i < aBlockList.length; i++)
    {
        document.getElementById(aBlockList[i]).style.display = 'none';
    }
}

window.onload = init;
//-->
</script>

<div id="about-wrap">

<h1>About</h1>

<h2><a href="javascript:void(0);" onclick="javascript:displayToggle('about-block')">History <span class="sub">- History of the Hellion Alliance</span></a></h2><hr>

<p id="about-block">
Fill in with information about the guild and what it does.
</p>

<h2><a href="javascript:void(0);" onclick="displayToggle('mission-block')">Mission <span class="sub">- Mission of the Alliance</span></a></h2><hr><p id="mission-block">
Input the mission of the guild here.
</p>

</div>

<?php

  require($DR . "inc/foot.php");

?>
