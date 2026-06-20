<?php // HiveHub: messages.php
require_once 'header.php';

if (!$loggedin) die("</div></body></html>");

$view = isset($_GET['view']) ? sanitizeString($_GET['view']) : $user;

/*
  The existing messages table is used as follows:
  pm = '0' for a public profile message
  pm = '1' for a private direct message
*/
if (isset($_POST['text']))
{
  $text = sanitizeString($_POST['text']);
  $pm   = isset($_POST['pm']) ? substr(sanitizeString($_POST['pm']), 0, 1) : '0';

  if ($text != "")
  {
    $time = time();
    queryMysql("INSERT INTO messages VALUES(NULL, '$user', '$view', '$pm', $time, '$text')");
  }
}

if (isset($_GET['erase']))
{
  $erase = sanitizeString($_GET['erase']);

  // A message may only be removed by its author or the profile owner.
  queryMysql("DELETE FROM messages
              WHERE id='$erase' AND (auth='$user' OR recip='$user')");
}

if ($view == $user)
{
  $title = "Your HiveHub Messages";
  $intro = "Share a public update with colleagues or send a private direct message.";
}
else
{
  $safeView = htmlspecialchars($view, ENT_QUOTES, 'UTF-8');
  $title = "$safeView's Messages";
  $intro = "Connect with this colleague through a public message or a private direct message.";
}

echo "<h3>$title</h3>";
showProfile($view);

echo <<<_END
<div class='hivehub-message-panel'>
  <p class='info'>$intro</p>

  <form method='post' action='messages.php?view=$view&r=$randstr'>
    <fieldset data-role='controlgroup' data-type='horizontal'>
      <legend>Choose a message type</legend>

      <input type='radio' name='pm' id='public' value='0' checked='checked'>
      <label for='public'>Public Post</label>

      <input type='radio' name='pm' id='private' value='1'>
      <label for='private'>Private Message</label>
    </fieldset>

    <label for='text'>Your message</label>
    <textarea name='text' id='text' maxlength='4096'
      placeholder='Write a positive, professional message...'></textarea>

    <input data-transition='slide' type='submit' value='Send Message'>
  </form>
</div>
_END;

date_default_timezone_set('America/Los_Angeles');

$query  = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC";
$result = queryMysql($query);
$num    = $result->rowCount();

echo "<div class='hivehub-message-list'>";

while ($row = $result->fetch())
{
  $isPrivate = ($row['pm'] == '1');

  // Public messages are visible to everyone. Private messages are visible
  // only to their author and recipient.
  if ($isPrivate && $row['auth'] != $user && $row['recip'] != $user) continue;

  $author = htmlspecialchars($row['auth'], ENT_QUOTES, 'UTF-8');
  $message = nl2br(htmlspecialchars(stripslashes($row['message']),
    ENT_QUOTES, 'UTF-8'));
  $timestamp = date('M j, Y g:i A', $row['time']);

  $typeLabel = $isPrivate ? "Private Message" : "Public Post";
  $typeClass = $isPrivate ? "private-message" : "public-message";

  echo "<article class='message-card $typeClass'>";
  echo "<div class='message-card-header'>";
  echo "<strong><a data-transition='slide' href='member.php?view=" .
       rawurlencode($row['auth']) . "&r=$randstr'>$author</a></strong>";
  echo "<span class='message-type'>$typeLabel</span>";
  echo "</div>";

  echo "<p class='message-time'>$timestamp</p>";
  echo "<div class='message-body'>$message</div>";

  if ($row['auth'] == $user || $row['recip'] == $user)
  {
    echo "<p class='message-actions'><a data-transition='slide' " .
         "href='messages.php?view=" . rawurlencode($view) .
         "&erase=" . $row['id'] . "&r=$randstr'>Delete</a></p>";
  }

  echo "</article>";
}

if (!$num)
  echo "<p class='info'>No messages have been posted yet. Start a conversation with a colleague.</p>";

echo "</div>";

echo "<a data-role='button' data-icon='refresh' href='messages.php?view=" .
     rawurlencode($view) . "&r=$randstr'>Refresh Messages</a>";
?>

    </div><br>
  </body>
</html>
