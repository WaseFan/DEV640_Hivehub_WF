<?php // HiveHub: signup.php
  require_once 'header.php';

echo <<<_END
  <script>
    function checkUser(user)
    {
      if (user.value == '')
      {
        $('#used').html('&nbsp;')
        return
      }

      $.post
      (
        'checkuser.php',
        { user : user.value },
        function(data)
        {
          $('#used').html(data)
        }
      )
    }
  </script>
_END;

  $error = $user = $pass = $name = $department = $role = $about = $interests = "";

  if (isset($_SESSION['user'])) destroySession();

  if (isset($_POST['user']))
  {
    $user       = sanitizeString($_POST['user']);
    $pass       = sanitizeString($_POST['pass']);
    $name       = sanitizeString($_POST['name']);
    $department = sanitizeString($_POST['department']);
    $role       = sanitizeString($_POST['role']);
    $about      = sanitizeString($_POST['about']);
    $interests  = sanitizeString($_POST['interests']);

    if ($user == "" || $pass == "" || $name == "" || $department == "" || $role == "")
      $error = "<div class='error'>Please complete all required fields.</div><br>";
    else
    {
      $result = queryMysql("SELECT * FROM members WHERE user='$user'");

      if ($result->rowCount())
        $error = "<div class='error'>That username already exists. Please choose another one.</div><br>";
      else
      {
        queryMysql("INSERT INTO members(user, pass, name, department, role)
                    VALUES('$user', '$pass', '$name', '$department', '$role')");

        queryMysql("INSERT INTO profiles(user, text, interests)
                    VALUES('$user', '$about', '$interests')");

        die("<div class='center'>
               <h4>Welcome to HiveHub, $name!</h4>
               <p>Your employee community account has been created.</p>
               <p>Please <a data-transition='slide' href='login.php'>log in</a> to complete your profile, upload a thumbnail, find colleagues, and start connecting.</p>
             </div></div></body></html>");
      }
    }
  }

echo <<<_END
      <div class='signup-intro'>
        <h3>Join HiveHub</h3>
        <p>
          Create your employee account to connect with colleagues, build workplace relationships,
          join interest groups, share recognition, and stay engaged with company updates.
        </p>
      </div>

      <form method='post' action='signup.php?r=$randstr'>$error

        <div data-role='fieldcontain'>
          <label>Username *</label>
          <input type='text' maxlength='16' name='user' value='$user'
            onBlur='checkUser(this)' placeholder='Choose a username'>
          <label></label><div id='used'>&nbsp;</div>
        </div>

        <div data-role='fieldcontain'>
          <label>Password *</label>
          <input type='password' maxlength='16' name='pass' value='$pass'
            placeholder='Create a password'>
        </div>

        <div data-role='fieldcontain'>
          <label>Name *</label>
          <input type='text' maxlength='64' name='name' value='$name'
            placeholder='Your full name'>
        </div>

        <div data-role='fieldcontain'>
          <label>Department *</label>
          <input type='text' maxlength='64' name='department' value='$department'
            placeholder='Example: Engineering, Marketing, Operations'>
        </div>

        <div data-role='fieldcontain'>
          <label>Role *</label>
          <input type='text' maxlength='64' name='role' value='$role'
            placeholder='Example: Product Manager, Software Engineer'>
        </div>

        <div data-role='fieldcontain'>
          <label>About Me</label>
          <textarea name='about' maxlength='4096'
            placeholder='Share a brief introduction about yourself'>$about</textarea>
        </div>

        <div data-role='fieldcontain'>
          <label>Interests or Skills</label>
          <input type='text' maxlength='255' name='interests' value='$interests'
            placeholder='Example: fitness, food, pets, gaming, wellness'>
        </div>

        <div data-role='fieldcontain'>
          <label></label>
          <input data-transition='slide' type='submit' value='Create HiveHub Account'>
        </div>

        <p class='small-note'>
          Required fields are marked with *. After signing up, employees can upload profile thumbnails,
          locate colleagues in the member directory, add friends, and send public or private messages.
        </p>
      </form>
    </div>
  </body>
</html>
_END;
?>
