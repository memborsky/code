#!/usr/bin/php
<?php

// Include our configuraiton file so we can provide easy setup for users.
include_once "config.php";


// Stop if the user hasn't setup the data correctly.
if ($password == "-") { die("Invalid User Data:\n\nPlease refer to the documentation to setup the bot with your configuration.\n"); }


// Require the classes of our objects only if we have been setup properly.
require_once "Snoopy.class.php";
require_once "lastRSS.php";


// Setup some basic server environment stuff so that php won't complain in the logs about this being a script.
$_SERVER["SERVER_SOFTWARE"] = "linux";
$_SERVER["DOCUMENT_ROOT"] = ".";
$_SERVER["HTTP_HOST"] = "localhost";


// No point in continuing if we don't have the feed name we are updating.
if ($_SERVER['argc'] < 2)
{
  die("Usage: " . $_SERVER['argv'][0] . " <news|updates>\n");
}
else
{
  // Setup some short forms of the arguments.
  $script_name  = $_SERVER['argv'][0];
  $feed         = strtolower(trim($_SERVER['argv'][1]));
}



// Create all of our objects we use below.
$snoop = new Snoopy();
$rss = new lastRSS();


// Rewrite our host url if it has a trailing /
$host = substr($host, -1) == '/' ? substr($host, 0, -1) : $host;


// Setup our objects
$rss->cache_dir   = '/tmp/';
$rss->cache_time  = 3600;
$snoop->host = substr($host, 0, 7) == 'http://' ? substr($host, 7) : $host;
$snoop->port = $port;



// Setup our global array configurations. This will allow us to better centralize our data for later updating.
$feeds = array (
  "news" => array (
    "short_url"   => "http://www.guildwars.net/newsarchive/rss/news-current.xml", // Used for easier script calling.
    // What we are wanting to replace.
    "pattern"     => array('/&lt;/', '/&gt;/', '/&amp;/', '/&quot;/', '/&apos;/', '/&#146;/', '/\[a href=\"/', '/\"\]/', '/\[\/a\]/', '/\[br\]/', '/\[b[^\]]*\]/'),
    // What we are replacing it with.
    "replace"     => array('[', ']', '&', '"', "'", "'", '[url=', ']', '[/url]', "\n", '[b]'),
    "file"        => "last_news" // Used to store when we last ran the script so we don't over post on the board.
  ),
  "updates" => array (
    "short_url"   => "http://www.guildwars.com/support/gameupdates/rss/updates-current.xml", // Used for easier script calling.
    // What we are wanting to replace.
    "pattern"     => array('/&lt;/', '/&gt;/', '/&apos;/', '/&quot;/', '/&amp;/', '/&#146;/', '/\[a href=\"/', '/\"\]/', '/\[\/a\]/', '/\[ul[^\]]*\]/', '/\[\/ul\]/', '/\[li\]/', '/\[\/li\]/',
                     '/\[b[^\]]*\]/', '/\[br\]/', '/ class=\"[^\"]*\"/'),
    // What we are replacing it with.
    "replace"     => array('[', ']', "'", '"', '&', "'", '[url=', ']', '[/url]', '[list]', '[/list]', '[*]', '', '[b]', "\n", ''),
    "file"        => "last_updates" // Used to store when we last ran the script so we don't over post on the board.
  )
);



// Setup our login information.
$login = array(
  'username' => $username,
  'password' => $password,
  'redirect' => '',
  'autologin' => 'off',
  'login' => 'Log in'
);


// Create the null message array. This will change later when we get back the data from the feed and are sure we need to post a new message to the forums.
$message = array(
  'subject' => '',
  'message' => '',
  'mode' => 'newtopic',
  'f' => !empty($forum) ? $forum : '1',
  'sid' => '',
  'post' => 'submit',
  'confirm' => 'true',
  'username' => $username
);


// Login to the forums.
if (!$snoop->submit($host . "/login.php", $login))
{
  die("Failed to login.");
}


// Grab our sid from our redirect so we can post to the boards.
$sid = trim(substr($snoop->lastredirectaddr, -32));


// Rewrite our url to be able to pull the feed from guildwars.com.
$url = array_key_exists($feed, $feeds) ? $feeds[$feed]["short_url"] : die("Please check usage: " . $script_name . " <news|updates>\n");


// Grab the data from the feed for parsing.
$data = $rss->get($url);


// Array to hold our new posts so we can post them in proper posting order by date rather than the reverse of time.
$new_posts = array();


// Grab the date in UTC form else we will just chuck in the current UTC value.
$last_date = is_file($feeds[$feed]['file']) ? strtotime(file_get_contents($feeds[$feed]['file'])) : strtotime(date('r'));


// Loop for each item that is newer than the last post we made to the forums.
foreach ($data['items'] as $item)
{

  if ($last_date < strtotime($item['pubDate']))
  {
    // If we are updating from the updates thread, then we don't need to appened the feed name to the post subject and can remove the date from the post message.
    switch ($feed)
    {
      case "news":
        $message['subject'] = ucfirst(strtolower($feed)) . " ~ " . preg_replace($feeds[$feed]['pattern'], $feeds[$feed]['replace'], $item['title']);
        $message['message'] = "Posted on " . date('l, F j, Y', strtotime($item['pubDate'])) . " at " . date('g:i:s T', strtotime($item['pubDate'])) . "\n\n" .
                              preg_replace($feeds[$feed]['pattern'], $feeds[$feed]['replace'], $item['description']);
        break;
      case "updates":
        $message['subject'] = preg_replace($feeds[$feed]['pattern'], $feeds[$feed]['replace'], $item['title']);
        $message['message'] = preg_replace($feeds[$feed]['pattern'], $feeds[$feed]['replace'], $item['description']);
        break;
    }

    $message['sid'] = $sid;

    $lastPub = !isset($lastPub) ? $item['pubDate'] : $lastPub;

    // Add the post to our post array so we can reverse the order just in case if we have more than one update since last run.
    array_push($new_posts, $message);
  }
}


// Reverse the order of our new posts so that we can get them up in the proper order of posts.
foreach (array_reverse($new_posts) as $post_message)
{
  // Reset our cookies for our PHP Web Client so phpBB doesn't complain to us about not having correct data for posting.
  foreach (array_keys($snoop->cookies) as $key)
  {
    $snoop->cookies = substr($key, -3) == "sid" ? array($key => $snoop->cookies[$key]) : $snoop->cookies;
  }

  // Post our data to the forums.
  $snoop->submit($host . "/posting.php?sid=" . $post_message['sid'] . "&f=" . $post_message['f'] . "&mode=" . $post_message['mode'], $post_message);

  // Fix for making sure we don't post to quickly to the host.
  sleep(15);
}


// Write the current date to the file so we know when we ran it this session for next time.
if (is_writeable($feeds[$feed]['file']) && isset($lastPub))
{
  if (!$hFile = fopen($feeds[$feed]['file'], 'w'))
  {
    die("Failed to open file.\n");
    exit;
  }

  // echo "Wrote " . $lastPub . " to " . $feeds[$feed]['file'];
  if (fwrite($hFile, $lastPub) === False)
  {
    die("Failed to write to file.\n");
    exit;
  }

  fclose($hFile);
}


/*
  echo strtotime($item['pubDate']) . " " . $last_date . "\n";
  echo $last_date <= strtotime($item['pubDate']) ? 1 : 0;
  die("\n");
*/
?>
