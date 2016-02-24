<?php

/**
 * 	Sometimes part numbers and other identification numbers have an extra digit called a check digit, to help verify that the number is valid and has not been altered.  A valid number is one having digits, including this check digit, that satisfy some internal algebraic relation, such as: the sum of the digits of the original part number, when taken mod 10, must be equal to the check digit.  For example, if the rightmost digit is the check digit, the number 2653 is valid because the sum of 2, 6, and 5 or 13, taken mod 10 is 3.  When people make typing errors or when numbers are accidentally changed, the alterations usually are confined to a single digit.  If one digit has been changed, the expected relation will not hold, and the number can be recognized as invalid. Write a program to test the validity of a four to eight digit part number, using the consistency check described, and refuse to accept the part number until it passes the consistency check.
 *
 * from:   Pascal from Begin to End 	by: Wilson and Shortt
 *
 * Input a digit and loop until 0 is entered. Check each number for validity according to the method described above, and print the input value, the sum of the digits, the check digit by itself, the result of the mod, and a message indicating whether or not the number is valid.
 *
 * You output must look like this:
 *
 * Data		Check   Sum	Mod	Validity
 * 1234  	 4		 6	 6	Not Valid
 * 2372	 	 2	    	12	 2	Valid
 * 5678 	 8	    	18	 8	Valid
 * 96754326	 6	    	36	 6	Valid
 */

/**
 * Continously loop to get all the user input until 0 has been entered.
 *
 * @return array()
 */
function GetInput ()
{
    $return = array();

    while (true)
    {
        $input = readline("Number to be checked (Input 0 to exit): ");

        if ($input != 0)
        {
            array_push($return, $input);
        }
        else
        {
            break;
        }
    }

    print "\n";
    return $return;
}

/**
 * Send all the items of output in a single function call.
 *
 * @int $data - The data string the user input.
 * @int $check - The check digit
 * @int $sum - The sum of the data input.
 * @int $mod - $sum % 10 result
 * @bool $valid - Is the data valid
 */
function SendOutput($data, $check, $sum, $mod, $valid)
{
    $output = str_pad($data, 12, " ", STR_PAD_RIGHT);
    $output .= str_pad($check, 8, " ", STR_PAD_RIGHT);
    $output .= str_pad($sum, 8, " ", STR_PAD_RIGHT);
    $output .= str_pad($mod, 8, " ", STR_PAD_RIGHT);

    if ($valid)
    {
        $output .= "Valid";
    }
    else
    {
        $output .= "Not Valid";
    }

    print $output . "\n";
}




// Get the users input.
$input = GetInput();


// Output the output header.
SendOutput("Data", "Check", "Sum", "Mod", "Validity");


// Go through all the data.
foreach ($input as $data)
{
    $check = substr($data, -1);

    $data_to_add = substr($data, 0, -1);
    $sum = 0;
    foreach (str_split($data_to_add) as $value)
    {
        $sum += $value;
    }

    $mod = $sum % 10;

    $valid = $mod == $check;

    SendOutput($data, $check, $sum, $mod, $valid);
}

?>
