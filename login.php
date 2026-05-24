<?php // HiveHub: login.php
  require_once 'header.php';

  $error = $user = $pass = "";

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);

    if ($user == "" || $pass == "")
      $error = 'Please enter both your username and password.';
    else
    {
      $result = queryMysql("SELECT user, pass FROM members
        WHERE user='$user' AND pass='$pass'");

      if ($result->rowCount() == 0)
      {
        $error = "Invalid HiveHub login. Please check your username and password.";
      }
      else
      {
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $pass;

        die("<div class='center'>
               <h3>Welcome back to HiveHub, $user!</h3>
               <p>You are now logged in. Continue to your HiveHub member profile and start connecting with your coworkers.</p>
               <a data-role='button' data-transition='slide'
                 href='members.php?view=$user&r=$randstr'>Go to My Profile</a>
             </div></div></body></html>");
      }
    }
  }

echo <<<_END
      <div class='center'>
        <h2>HiveHub Employee Login</h2>
        <p class='info'>Log in to connect with colleagues, send messages, join groups, and stay engaged with your startup community.</p>
      </div>

      <form method='post' action='login.php?r=$randstr'>
        <div data-role='fieldcontain'>
          <label></label>
          <span class='error'>$error</span>
        </div>

        <div data-role='fieldcontain'>
          <label>Username</label>
          <input type='text' maxlength='16' name='user' value='$user' placeholder='Enter your username'>
        </div>

        <div data-role='fieldcontain'>
          <label>Password</label>
          <input type='password' maxlength='16' name='pass' value='$pass' placeholder='Enter your password'>
        </div>

        <div data-role='fieldcontain'>
          <label></label>
          <input data-transition='slide' type='submit' value='Log In to HiveHub'>
        </div>
      </form>

      <div class='center'>
        <p>New to HiveHub? <a data-transition='slide' href='signup.php?r=$randstr'>Create an employee account</a>.</p>
      </div>
    </div>
  </body>
</html>
_END;
?>
