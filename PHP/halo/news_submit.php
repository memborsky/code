<?php

require_once("config.php");

$connection = mysql_connect($db['hostname'], $db['username'], $db['password'])
      or die ("\nFailed to connect to MySQL server on " . $db['hostname'] . ".\n");

$db = mysql_select_db($db['database']) 
      or die ("\nFailed to select database " . $db['database'] 
      . " at host " . $db['hostname'] . ".\n");

$sql_queries = array (
  "INSERT INTO halo_news (`topic`, `name`, `date`, `data`)
   VALUES ('$topic', '$name', '$date', '$data')
  "
);


if (!$_POST['submit'])
  {
    $date = date("Y-m-d");

    echo "
      <form method='post' name='news_submit.php'>
        <input type='hidden' name='date' value='$date'>
        Name: <input name='name'><br>
        Topic: <input name='topic'><br>
        Data: <br><textarea name='data' cols='50' rows='30'></textarea>
      <input type='submit' name='submit' value='Submit News'>
      </form>
    ";
  }
else
  {
    $date = $_POST['date'];
    $name = $_POST['name'];
    $topic = $_POST['topic'];
    $data = $_POST['data'];

    mysql_escape_string($data);
    stripslashes($data);

    $result = mysql_query($sql_queries[0], $connection) or die ("FAILED TO QUERY");

    echo "Date: " . $date . "<br>";
    echo "Name: " . $name . "<br>";
    echo "Topic: " . $topic . "<br>";
    echo "Data: " . $data . "<br>";

    // execute above sql
  }

?>
