<?php

/**
* Count the number of occurances in example string and
* output the word with the most occurances.
**/


$example1 = "This is a string we are using as an example to show number of occurances. For every word in the string, lets count the number of times it is present in the string. After we do that, return the result with the most occurances. This result should be the same result every time we call the function, no matter the compiler.";
$example2 = "oF The of the of the of. th.e the o!f .The! of*";

function strip_punctuation ($input)
{
    return preg_replace("/\p{P}/u", "", $input);
}

function count_occurance ($input)
{
    // Strip the punctuation and lower the string.
    $result = strip_punctuation(strtolower($input));

    // Count the values of the string exploding on the spaces.
    $result = array_count_values(explode(" ", $result));

    // Sort the array of words from least to most common.
    asort($result);

    // Return the most common occurance.
    $max = max($result);
    return array_search(max($result), $result) . " - " . $max;
}

print $example1 . "\n";
print count_occurance($example1) . "\n\n";

print $example2 . "\n";
print count_occurance($example2) . "\n";

?>
