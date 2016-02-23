<?php

/**
 * Take a simple string and replace parts of it with input from the user.
 * Pad fill the age to 4 digits with leading 0s.
**/

$example = "Hello {name}, you are {age} years old.";

$name = readline("Your name: ");
$age = readline("Your age: ");

$search = array("{name}", "{age}");
$replace = array($name, str_pad($age, 4, "0", STR_PAD_LEFT));

print str_replace($search, $replace, $example) . "\n";

?>
