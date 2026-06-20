<?php // HiveHub: logout.php
require_once 'header.php';

if (isset($_SESSION['user']))
{
  $formerUser = htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8');
  destroySession();

  echo <<<_END
    <section class='logout-card center'>
      <h3>You have logged out of HiveHub</h3>
      <p>Thank you, $formerUser. Your employee session has ended securely.</p>
      <a data-role='button' data-transition='slide'
         href='index.php?r=$randstr'>Return to HiveHub Home</a>
      <a data-role='button' data-transition='slide'
         href='login.php?r=$randstr'>Log In Again</a>
    </section>
_END;
}
else
{
  echo <<<_END
    <section class='logout-card center'>
      <h3>No Active Session</h3>
      <p>You are not currently logged in to HiveHub.</p>
      <a data-role='button' data-transition='slide'
         href='login.php?r=$randstr'>Go to Login</a>
    </section>
_END;
}
?>
    </div>
  </body>
</html>
