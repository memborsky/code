<?php

function FirstReverse($str) {
    return strrev($str);

}

// keep this function call here
echo FirstReverse(fgets(fopen('php://stdin', 'r')));

?>
