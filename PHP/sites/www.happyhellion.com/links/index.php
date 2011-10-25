<!--BEGIN DO NOT EDIT SECTION-->
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


require($DR . "inc/head.php");

?>
<!--END DO NOT EDIT SECTION-->

<!--Begin Content-->



<!--End Content-->

<!--BEGIN DO NOT EDIT SECTION-->
<?php

  require($DR . "inc/foot.php");

?>
<!--END DO NOT EDIT SECTION-->
