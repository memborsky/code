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

<div class="home">
<img src="img/greetingfreedom.jpg">
<h1>Welcome to the Hellion Guilds web site.</h1>
This page would contain information about the alliance in general and give a brief introduction to the comings
of the hellion guild and other such information. If you request to have coded inputed in this section, you may
do so at the cost of knowing what it does, or consult the creator of this site. Have fun!
</div>

<!--End Content-->

<!--BEGIN DO NOT EDIT SECTION-->
<?php

  require($DR . "inc/foot.php");

?>
