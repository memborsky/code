<?php

/**
* Write a program that prints the numbers from 1 to 100. But for multiples
* of three print “Fizz” instead of the number and for the multiples of five
* print “Buzz”. For numbers which are multiples of both three and five print
* “FizzBuzz”.
**/

function FizzBuzz ($check)
{

    $fizz = $check % 3 == 0;
    $buzz = $check % 5 == 0;

    if ($fizz && $buzz)
    {
        return "FizzBuzz";
    }
    elseif ($fizz)
    {
        return "Fizz";
    }
    elseif ($buzz)
    {
        return "Buzz";
    }

    return $check;
}

for ($index = 1; $index <= 100; $index++)
{
    echo FizzBuzz($index) . "\n";
}

?>
