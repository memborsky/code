<?php

session_start();

$_SESSION["name"] = "test";

echo "<pre>";
print_r($_SESSION);
echo "</pre>";

?>
