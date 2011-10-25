<?php

$g_strPageTitle = (empty($strPageTitle) ? "" : "| $strPageTitle");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://wwww.w3.org/TR/html4/strict.dtd">
<html>

<head>

    <title><?php echo "Hellion Alliance $g_strPageTitle"; ?></title>

    <meta name="Author" content="Matt Emborsky">
    <meta name="copyright" content="&copy; 2006 Hellion Alliance">
    <link type="text/css" href="<?php echo $DR . "inc/style.css"; ?>" rel="stylesheet" title="Default">
    <link type="text/javascript" href="<?php echo $DR . "inc/earworm.js"; ?>">

</head>

<body>

    <div id="wrapper">
        <div id="container">

            <div id="header"></div>

            <div id="navmenu">
                <ul>
                    <li><a href="<?php echo $DR . 'about/' ?>">About</a></li>
                    <li><a href="<?php echo $DR . 'news/' ?>">News</a></li>
                    <li><a href="http://happyhellion.proboards55.com">Forums</a></li>
                    <li><a href="<?php echo $DR . 'members/' ?>">Members</a></li>
                    <li><a href="<?php echo $DR . 'links/' ?>">Links</a></li>
                    <li><a href="<?php echo $DR . 'media/' ?>">Media</a></li>
                    <li><a href="<?php echo $DR . 'help/' ?>">Help</a></li>
                </ul>
            </div>

