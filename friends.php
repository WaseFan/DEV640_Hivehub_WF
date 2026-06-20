<?php // HiveHub: friends.php
  require_once 'header.php';

  if (!$loggedin) die("</div></body></html>");

  if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
  else                      $view = $user;

  if ($view == $user)
  {
    $displayName = "Your";
    $intro       = "These are your HiveHub workplace connections.";
    $emptyMsg    = "You have not added any colleagues yet. Visit the employee directory to start building your HiveHub network.";
  }
  else
  {
    $displayName = "$view's";
    $intro       = "These are $view's HiveHub workplace connections.";
    $emptyMsg    = "$view has not added any workplace connections yet.";
  }

  echo <<<_END
      <section class='page-card'>
        <h3>$displayName Connections</h3>
        <p class='info'>$intro</p>
      </section>
_END;

  // Optional profile preview for the selected employee
  echo "<section class='profile-preview'>";
  showProfile($view);
  echo "</section>";

  $followers = array();
  $following = array();

  // In the original project structure:
  // user = the member being followed/connected with
  // friend = the member who created the connection
  $result = queryMysql("SELECT * FROM friends WHERE user='$view'");
  while ($row = $result->fetch())
  {
    $followers[] = $row['friend'];
  }

  $result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
  while ($row = $result->fetch())
  {
    $following[] = $row['user'];
  }

  $mutual    = array_intersect($followers, $following);
  $followers = array_diff($followers, $mutual);
  $following = array_diff($following, $mutual);
  $hasConnections = FALSE;

  function hivehubConnectionList($title, $people, $randstr)
  {
    if (!sizeof($people)) return;

    echo "<section class='connection-card'>";
    echo "<h4>$title</h4>";
    echo "<ul data-role='listview' data-inset='true'>";

    foreach($people as $person)
    {
      echo "<li><a data-transition='slide' href='member.php?view=$person&r=$randstr'>$person</a></li>";
    }

    echo "</ul>";
    echo "</section>";
  }

  if (sizeof($mutual))
  {
    hivehubConnectionList("Mutual Workplace Connections", $mutual, $randstr);
    $hasConnections = TRUE;
  }

  if (sizeof($followers))
  {
    hivehubConnectionList("Colleagues Connected With $view", $followers, $randstr);
    $hasConnections = TRUE;
  }

  if (sizeof($following))
  {
    if ($view == $user)
      hivehubConnectionList("Colleagues You Added", $following, $randstr);
    else
      hivehubConnectionList("Colleagues $view Added", $following, $randstr);

    $hasConnections = TRUE;
  }

  if (!$hasConnections)
  {
    echo <<<_END
      <section class='empty-state'>
        <h4>No connections yet</h4>
        <p>$emptyMsg</p>
        <a data-role='button' data-transition='slide' href='member.php?r=$randstr'>
          Browse Employee Directory
        </a>
      </section>
_END;
  }
?>
    </div><br>
  </body>
</html>
