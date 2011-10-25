#!/usr/bin/php
<?php

// Include our configuraiton file so we can provide easy setup for users.
include_once "config.php";

// Include class files for our objects that we create below.
require_once "Snoopy.class.php";
require_once "lastRSS.php";

// Setup some basic server environment stuff so that php won't complain in the logs about this being a script.
$_SERVER["SERVER_SOFTWARE"] = "linux";
$_SERVER["DOCUMENT_ROOT"] = ".";
$_SERVER["HTTP_HOST"] = "localhost";

// No point in continuing if we don't have enough arguments for parsing the feeds.
if ($_SERVER['argc'] < 2)
{
  die("Usage: " . $_SERVER['argv'][0] . " <news|updates>\n");
}
else
{
  $feed = $_SERVER['argv'][1];
}

// Create all of our objects we use below.
$snoop = new Snoopy();
$rss = new lastRSS();

// Setup our rss object.
$rss->cache_dir   = '/tmp/';
$rss->cache_time  = 3600;

// Setup our login information.
$login = array(
  'username' => $username,
  'password' => $password,
  'redirect' => '',
  'autologin' => 'off',
  'login' => 'Log in'
);

// Setup our basic message array. We will use this array over the top of each new post from the last
// run that would have new topics posted to the feed.
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

// Rewrite our host url if it has a trailing /.
$host = substr($host, -1) == '/' ? substr($host, 0, -1) : $host;

// Set our basic setup data to the PHP Client object.
$snoop->host = substr($host, 0, 7) == 'http://' ? substr($host, 7) : $host;
$snoop->port = $port;

// Login to the forums.
$snoop->submit($host . "/login.php", $login);

// Grab our sid from our redirect so we can post to the boards.
$sid = trim(substr($snoop->lastredirectaddr, -32));

// Setup our global array configurations. This will allow us to better centralize our data for later updating.
$feeds = array (
  "news" => array (
    // Used for easier script calling.
    "short_url"   => "http://www.guildwars.net/newsarchive/rss/news-current.xml",

    // What we are wanting to replace.
    "pattern"     => array('/&lt;/', '/&gt;/', '/&amp;/', '/&quot;/', '/&apos;/', '/&#146;/', '/a href=\"/', '/\[\/a\]/', '/\"\]/', '<br>'),

    // What we are replacing it with.
    "replace"     => array('[', ']', '&', '"', "'", "'", 'url=', '[/url]', ']', "\n"),

    // Used to store when we last ran the script so we don't over post on the board.
    "file"        => "last_news_run"
  ),

  "updates" => array (
    // Used for easier script calling.
    "short_url"   => "http://www.guildwars.com/support/gameupdates/rss/updates-current.xml",

    // What we are wanting to replace.
    "pattern"     => array('/&lt;/', '/&gt;/', '/&apos;/', '/&quot;/', '/&amp;/', '/&#146;/', '/\[a[^\]]*\]/', '/\[\/a\]/', '/\[ul[^\]]*\]/', '/\[\/ul\]/', '/\[li\]/', '/\[\/li\]/', '/\[br\]/'),

    // What we are replacing it with.
    "replace"     => array('[', ']', "'", '"', '&', "'", '', '', '[list]', '[/list]', '[*]', '', "\n"),

    // Used to store when we last ran the script so we don't over post on the board.
    "file"        => "last_updates_run"
  )
);

// Rewrite our url to be able to pull the feed from guildwars.com.
$url = array_key_exists($feed, $feeds) ? $feeds[$feed]["short_url"] : die("Please check usage: " . $_SERVER['argv'][0] . " <news|updates>\n");

// Grab the data from our url.
$data = $rss->get($url);

// Array to hold our new posts so we can post them in proper posting order by date rather than the reverse of time.
$new_posts = array();

// Grab the date in UTC form else we will just chuck in the current UTC value.
$last_pubDate = is_file($feeds[$feed]['file']) ? strtotime(file_get_contents($feeds[$feed]['file'])) : strtotime(date('r'));

// Loop for each item that is newer than the last post we made to the forums.
// Each new message is then stored into our message array for later retrieval.
foreach ($data['items'] as $item)
{
  if ($last_pubDate < strtotime($item['pubDate']))
  {

    if ($feed != "updates")
    {
      $message['subject'] = ucfirst(strtolower($feed)) . " ~ " . preg_replace($feeds[$feed]['pattern'], $feeds[$feed]['replace'], $item['title']);
      $message['message'] = "Posted on " . date('l, F j, Y', strtotime($item['pubDate'])) . "\n\n" . preg_replace($feeds[$feed]['pattern'], $feeds[$feed]['replace'], $item['description']);
    }
    else
    {
      $message['subject'] = preg_replace($feeds[$feed]['pattern'], $feeds[$feed]['replace'], $item['title']);
      $message['message'] = preg_replace($feeds[$feed]['pattern'], $feeds[$feed]['replace'], $item['description']);
    }

    $message['sid'] = $sid;
    array_push($new_posts, $message);
  }
}

// Reverse post each new thread so that we don't post items in the reverse order of when they were
// updated to the feed.
foreach (array_reverse($new_posts) as $post_message)
{
  // Reset our cookies for our PHP Web Client so phpBB doesn't complain to us about not having
  // correct data for posting.
  $snoop->cookies = array(
    "girlpowergwphpbb_sid" => $post_message["sid"]
  );

  // Post our data to the forums.
  $snoop->submit($host . "/posting.php?sid=" . $post_message['sid'] . "&f=" . $post_message['f'] . "&mode=" . $post_message['mode'], $post_message);

  // Get around the slowness of our own connection to be able to multi-post.
  sleep(5);
}

// Write last pub date to file_cache for updated usage.
if (is_writeable($feeds[$feed]['file']))
{
  if (!$hFile = fopen($feeds[$feed]['file'], 'w'))
  {
    exit;
  }

  // Used for debugging purposes.
  // echo "Writing " . date('r') . " to " . $feeds[$feed]['file'] . "\n";

  if (fwrite($hFile, date('r')) === False)
  {
    exit;
  }

  fclose($hFile);
}

?>
