<?php // HiveHub: checkuser.php
  require_once 'functions.php';

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);

    if ($user == "")
    {
      echo "<span class='taken'>&nbsp;&#x2718; Please enter a username</span>";
      return;
    }

    if (strlen($user) < 4)
    {
      echo "<span class='taken'>&nbsp;&#x2718; Username must be at least 4 characters</span>";
      return;
    }

    if (!preg_match('/^[A-Za-z0-9_]+$/', $user))
    {
      echo "<span class='taken'>&nbsp;&#x2718; Use only letters, numbers, or underscores</span>";
      return;
    }

    $result = queryMysql("SELECT * FROM members WHERE user='$user'");

    if ($result->rowCount())
      echo "<span class='taken'>&nbsp;&#x2718; The HiveHub username '$user' is already taken</span>";
    else
      echo "<span class='available'>&nbsp;&#x2714; The HiveHub username '$user' is available</span>";
  }
?>
