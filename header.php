<?php // header.php for HiveHub
  session_start();

  require_once 'functions.php';

  $appname = "HiveHub";
  $tagline = "Grow community, build morale, and strengthen workplace relationships";
  $userstr = 'Welcome Guest';
  $randstr = substr(md5(rand()), 0, 7);

  if (isset($_SESSION['user']))
  {
    $user     = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr  = "Logged in as: $user";
  }
  else $loggedin = FALSE;

  echo <<<_INIT
<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='jquery.mobile-1.4.5.min.css'>
    <link rel='stylesheet' href='styles.css' type='text/css'>
    <script src='javascript.js'></script>
    <script src='jquery-2.2.4.min.js'></script>
    <script src='jquery.mobile-1.4.5.min.js'></script>
    <title>$appname: $userstr</title>
  </head>
  <body>
    <div data-role='page'>
      <div data-role='header' data-theme='b'>
        <div id='logo' class='center hivehub-logo'>$appname</div>
        <div class='center hivehub-tagline'>$tagline</div>
        <div class='username center'>$userstr</div>
      </div>
      <div data-role='content'>
_INIT;

  if ($loggedin)
  {
    echo <<<_LOGGEDIN
        <div class='center hivehub-nav'>
          <a data-role='button' data-inline='true' data-icon='home'
            data-transition='slide' href='members.php?view=$user&r=$randstr'>My Profile</a>
          <a data-role='button' data-inline='true' data-icon='user'
            data-transition='slide' href='members.php?r=$randstr'>Member Directory</a>
          <a data-role='button' data-inline='true' data-icon='heart'
            data-transition='slide' href='friends.php?r=$randstr'>Friends</a><br>
          <a data-role='button' data-inline='true' data-icon='mail'
            data-transition='slide' href='messages.php?r=$randstr'>Messages</a>
          <a data-role='button' data-inline='true' data-icon='star'
            data-transition='slide' href='recognition.php?r=$randstr'>Shoutouts</a>
          <a data-role='button' data-inline='true' data-icon='calendar'
            data-transition='slide' href='events.php?r=$randstr'>Events</a><br>
          <a data-role='button' data-inline='true' data-icon='grid'
            data-transition='slide' href='groups.php?r=$randstr'>Interest Groups</a>
          <a data-role='button' data-inline='true' data-icon='comment'
            data-transition='slide' href='mood.php?r=$randstr'>Mood Check-In</a>
          <a data-role='button' data-inline='true' data-icon='info'
            data-transition='slide' href='analytics.php?r=$randstr'>Morale Analytics</a><br>
          <a data-role='button' data-inline='true' data-icon='edit'
            data-transition='slide' href='profile.php?r=$randstr'>Edit Profile</a>
          <a data-role='button' data-inline='true' data-icon='action'
            data-transition='slide' href='linkedin.php?r=$randstr'>Connect LinkedIn</a>
          <a data-role='button' data-inline='true' data-icon='delete'
            data-transition='slide' href='logout.php?r=$randstr'>Log Out</a>
        </div>
        <p class='info center'>HiveHub helps employees connect, recognize teammates, join communities, and stay engaged in a growing workplace.</p>
_LOGGEDIN;
  }
  else
  {
    echo <<<_GUEST
        <div class='center hivehub-nav'>
          <a data-role='button' data-inline='true' data-icon='home'
            data-transition='slide' href='index.php?r=$randstr'>Home</a>
          <a data-role='button' data-inline='true' data-icon='plus'
            data-transition='slide' href='signup.php?r=$randstr'>Sign Up</a>
          <a data-role='button' data-inline='true' data-icon='check'
            data-transition='slide' href='login.php?r=$randstr'>Log In</a>
        </div>
        <p class='info center'>Create a HiveHub account to build your employee profile, upload a thumbnail, find colleagues, add friends, send messages, and participate in company communities.</p>
        <p class='info center'>(You must be logged in to access profiles, messages, friends, events, recognition, and morale features.)</p>
_GUEST;
  }
?>
