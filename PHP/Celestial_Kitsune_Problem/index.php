<?php

$input_file = "input.txt";

$line = array(
  array(
    'case'      => "",
    'time'      => "",
    'variable'  => array()
  )
);

$lines = file($input_file);

// Parse the lines array to format the data into each field of the line array.
Foreach ($lines as $line_num => $sLine)
{

  $lineSlice = explode(" ", $sLine);
  $line[$line_num]['case'] = (string)trim($lineSlice[0]);

  If (trim($lineSlice[1]) == "Point")
  {
    $line[$line_num]['time'] = trim($lineSlice[1]) . " " . trim($lineSlice[2]);
    $start = 3;
  }
  else
  {
    $line[$line_num]['time'] = trim($lineSlice[1]);
    $start = 2;
  }

  For ($index = $start; $index <= count($lineSlice); $index++)
  {

    If (trim($lineSlice[$index]) <> '')
      $line[$line_num]['variable'][] = (int)trim($lineSlice[$index]);

  }

}

// Find the baseline location in the array for each variable.
$baseline = array();
For ($index = 0; $index <= count($line); $index++)
{
   If (!is_null($line[$index]['variable']))
  {
    $variable[$index] = $line[$index]['variable'];
  }

  If ($line[$index]['time'] == "Baseline")
  {
    $baseline[$index] = $index;
  }
  else
  {
    $jndex = 0;
    While ($jndex <= count($line))
    {
      If (($line[$jndex]['time'] == "Baseline") && ($line[$jndex]['case'] == $line[$index]['case']))
      {
        $baseline[$index] = $jndex;
        break;
      }
      else
      {
        $jndex++;
      }
    }
  }
}

// Find the solution and print it.
$solution = $line;
$output   = "";
echo "Case\tTime\t\tVariables 1, Variable 2, ...\n";
For ($index = 0; $index <= count($line) - 2; $index++)
{
  If ((!is_null($line[$index]['time'])) && ($line[$index]['time'] == "Baseline"))
  {
    $tab = "\t";
  }
  else
  {
    $tab = "\t\t";
  }
  $output = $line[$index]['case'] . "\t" . $line[$index]['time'] . $tab;
  For ($jndex = 0; $jndex <= count($variable[$index]); $jndex++)
  {
    If (!is_null($line[$index]['variable'][$jndex]))
    {
      // echo $line[$index]['variable'][$jndex] . "\n";
      // echo $line[$baseline[$index]]['variable'][$jndex] . "\n";

      // x - baseline / baseline -> percentage
      $x = $line[$index]['variable'][$jndex];
      $bline = $line[$baseline[$index]]['variable'][$jndex];

      $answer = (($x - $bline) / $bline);

      If ($jndex != 0)
      {
        $output .= ", " . (float)(((int)($answer * 10000)) / 100) . "%";
      }
      else
      {
        $output .= (float)(((int)($answer * 10000)) / 100) . "%";
      }
    }
  }

  echo $output . "\n";
}

?>
