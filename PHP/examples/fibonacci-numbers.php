<?php

/**
 * Calculates the (n)th fibonacci number recursively until 0 is input.
**/

function fibonacci ($find)
{
    if ($find == 1)
    {
        return 1;
    }
    elseif ($find == 2)
    {
        return 1;
    }
    else
    {
        return (fibonacci($find - 1) + fibonacci($find - 2));
    }
}

while (true)
{
    $input = readline("Input fibonacci # do you want to find: (0 to exit): ");

    if ($input == 0)
    {
        break;
    }

    $start_time = microtime(true);
    $fib_number = fibonacci($input);
    $end_time = microtime(true);

    print "The " . $input . " Fibonacci number is: " . fibonacci($input) . ". It took " . ($end_time - $start_time) . " seconds to find this number recursively.\n";
}

?>
