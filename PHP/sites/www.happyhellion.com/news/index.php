<?php

$strPageTitle = "News";
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
<!--END DO NOT EDIT SECTION-->

<!--Begin Content-->
<!--change this to news once implemented-->
<div class="home">
<h1>News</h1>
This section contains the news that can be found in the news.xml file.
A soon to be added feature, before the site should go live, is the ability
to modify the xml data on the fly from a web submit form instead of by hand.
</div>


<!--End Content-->

<!--BEGIN DO NOT EDIT SECTION-->
<?php

  require($DR . "inc/foot.php");

?>
