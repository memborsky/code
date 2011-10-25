<?php

require_once("config.php");

$connection = mysql_connect($db['hostname'], $db['username'], $db['password'])
      or die("\nFailed to connect to Mysql Server at " . $db['hostname'] . ".\n");

$db = mysql_select_db($db['database'], $connection)
      or die("\nFailed to select database " . $db['database'] . ".\n");

$sql_queries = array (
  "SELECT *
   FROM halo_news
   ORDER BY news_id DESC
   LIMIT 10
  "
);

$user_pic = Array (
  "http://maumee.indianatech.net/halo/images/news/chris.jpg",
  "http://maumee.indianatech.net/halo/images/news/clinton.gif",
  "http://maumee.indianatech.net/halo/images/news/matt.gif",
  "http://maumee.indianatech.net/halo/images/news/ryan.jpg",
  "http://maumee.indianatech.net/halo/images/news/halo.jpg"
);
  
$user_name = Array (
  "Chris Gee",
  "Clinton Gulley",
  "Matt Emborsky",
  "Ryan Kraszyk",
  "halo"
);

$user_email = Array (
  "crgee01@.com",
  "ccgulley01@.com",
  "mlemborsky01@.com",
  "rbkraszyk01@.com",
  "halo@.com"
);

$result = mysql_query($sql_queries[0], $connection) or die ("FAILED TO RUN QUERY<br>\n" . mysql_error() . "<br>\n<br>\nPlease contact <a href='mailto:halo@.com'>Webmaster</a> with the error.");

echo "
  <html>
   <head>
      <title>
         Navigation and Title
      </title>
<style type='text/css'>
p.tableheader {font-family: arial; color: white; font-size: 12pt}
</style> 

</head>
<body>
";

if (mysql_num_rows($result) == 0)
{

echo "<h3>There currently isn't any news.</h3>
<br>
<br>
Please check back later, or contact the <a href='mailto:halo@.com'>Webmaster</a> if you think this is an error.";

} else {

  while ($row = mysql_fetch_row($result))
  {

  if (($row[2] == "chris") || ($row[2] == "Chris")) {
    $user_index = 0;
  } elseif (($row[2] == "clinton") || ($row[2] == "Clinton")) {
    $user_index = 1;
  } elseif (($row[2] == "matt") || ($row[2] == "Matt")) {
    $user_index = 2;
  } elseif (($row[2] == "ryan") || ($row[2] == "Ryan")) {
    $user_index = 3;
  } else {
    $user_index = 4;
  }
  
      echo "<table width='555' align='CENTER' bordercolorlight='silver' bordercolordark='silver' cellpadding='3' cellspacing='0' border='3' rules='none'>
        <tr>
          <td bgcolor='silver'><p class='tableheader'>$row[1]</p class='tableheader'></td>
          <td bgcolor='silver' align=right>
            <font face='arial' color='white' size='2'>$row[3] | <a href='mailto:$user_email[$user_index]'>$user_name[$user_index]</a></td>
        <tr>
          
          <td colspan='2'><font face='Arial,Helvetica,Geneva,Swiss,SunSans-Regular'>$row[4]</font>
            <img src='$user_pic[$user_index]' align='right'>
          </td>
        </tr>
      </table><br>
      ";
  }

}
echo "</body></html>";
