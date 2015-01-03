<?php

// Init class
include('curl_phpbb.php');

// The ending backslash is required.
$phpbb = new curl_phpbb('http://forum.ysfhq.com/');

// Log in
$phpbb->login('testuser', 'tapdancing+turtles');

// Send random_user a pm
// $r = $phpbb->new_pm('Eric', 'test message!', 'Hello user...');
// echo $r;

// Post a new topic
$r = $phpbb->new_topic('274', 'This is just a test post!', 'Topic subject');

// // Reply to a topic
// $r = $phpbb->topic_reply('6713', 'This is just a test post!', 'Post subject');
// echo $r;

// Read index
//echo $phpbb->read('index.php');

// Log out
$phpbb->logout();

?>
