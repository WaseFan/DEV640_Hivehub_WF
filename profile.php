<?php // HiveHub: profile.php
  require_once 'header.php';

  if (!$loggedin) die("</div></body></html>");

  echo "<h3 class='center'>Your HiveHub Profile</h3>";

  $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

  $about      = "";
  $department = "";
  $role       = "";
  $interests  = "";
  $linkedin   = "";
  $mood       = "";

  if (isset($_POST['about']))
  {
    $about      = sanitizeString($_POST['about']);
    $department = sanitizeString($_POST['department']);
    $role       = sanitizeString($_POST['role']);
    $interests  = sanitizeString($_POST['interests']);
    $linkedin   = sanitizeString($_POST['linkedin']);
    $mood       = sanitizeString($_POST['mood']);

    $about      = preg_replace('/\s\s+/', ' ', $about);
    $department = preg_replace('/\s\s+/', ' ', $department);
    $role       = preg_replace('/\s\s+/', ' ', $role);
    $interests  = preg_replace('/\s\s+/', ' ', $interests);
    $linkedin   = preg_replace('/\s\s+/', ' ', $linkedin);
    $mood       = preg_replace('/\s\s+/', ' ', $mood);

    // Store the HiveHub profile fields in one text field to stay compatible
    // with the original profiles table structure: user + text.
    $profileText = "Department: $department\n" .
                   "Role: $role\n" .
                   "About Me: $about\n" .
                   "Interests/Skills: $interests\n" .
                   "LinkedIn: $linkedin\n" .
                   "Mood Check-In: $mood";

    if ($result->rowCount())
         queryMysql("UPDATE profiles SET text='$profileText' WHERE user='$user'");
    else queryMysql("INSERT INTO profiles VALUES('$user', '$profileText')");
  }
  else
  {
    if ($result->rowCount())
    {
      $row = $result->fetch();
      $text = stripslashes($row['text']);

      // Simple parsing for previously saved HiveHub profile text.
      $lines = explode("\n", $text);
      foreach ($lines as $line)
      {
        if (strpos($line, 'Department:') === 0)       $department = trim(substr($line, 11));
        elseif (strpos($line, 'Role:') === 0)         $role       = trim(substr($line, 5));
        elseif (strpos($line, 'About Me:') === 0)     $about      = trim(substr($line, 9));
        elseif (strpos($line, 'Interests/Skills:') === 0) $interests = trim(substr($line, 17));
        elseif (strpos($line, 'LinkedIn:') === 0)     $linkedin   = trim(substr($line, 9));
        elseif (strpos($line, 'Mood Check-In:') === 0) $mood      = trim(substr($line, 14));
      }

      // If this is an older profile, place the old text into About Me.
      if ($about == "" && strpos($text, 'Department:') === FALSE)
        $about = $text;
    }
  }

  $about      = stripslashes($about);
  $department = stripslashes($department);
  $role       = stripslashes($role);
  $interests  = stripslashes($interests);
  $linkedin   = stripslashes($linkedin);
  $mood       = stripslashes($mood);

  if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '')
  {
    $saveto = "$user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;

    switch($_FILES['image']['type'])
    {
      case "image/gif":   $src = imagecreatefromgif($saveto); break;
      case "image/jpeg":
      case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
      case "image/png":   $src = imagecreatefrompng($saveto); break;
      default:            $typeok = FALSE; break;
    }

    if ($typeok)
    {
      list($w, $h) = getimagesize($saveto);

      $max = 100;
      $tw  = $w;
      $th  = $h;

      if ($w > $h && $max < $w)
      {
        $th = $max / $w * $h;
        $tw = $max;
      }
      elseif ($h > $w && $max < $h)
      {
        $tw = $max / $h * $w;
        $th = $max;
      }
      elseif ($max < $w)
      {
        $tw = $th = $max;
      }

      $tmp = imagecreatetruecolor($tw, $th);
      imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
      imageconvolution($tmp, array(array(-1, -1, -1),
        array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
      imagejpeg($tmp, $saveto);
      imagedestroy($tmp);
      imagedestroy($src);
    }
  }

  showProfile($user);

echo <<<_END
      <form data-ajax='false' method='post'
        action='profile.php?r=$randstr' enctype='multipart/form-data'>

        <h3>Build Your Employee Profile</h3>
        <p class='info'>Help coworkers learn about your role, interests, skills, and workplace community involvement.</p>

        <div data-role='fieldcontain'>
          <label>Department</label>
          <input type='text' maxlength='64' name='department' value='$department' placeholder='Engineering, Operations, Marketing'>
        </div>

        <div data-role='fieldcontain'>
          <label>Role</label>
          <input type='text' maxlength='64' name='role' value='$role' placeholder='Program Manager, Software Engineer, Designer'>
        </div>

        <div data-role='fieldcontain'>
          <label>About Me</label>
          <textarea name='about' placeholder='Share a short introduction about yourself'>$about</textarea>
        </div>

        <div data-role='fieldcontain'>
          <label>Interests or Skills</label>
          <textarea name='interests' placeholder='Fitness, food, pets, gaming, wellness, coding, design'>$interests</textarea>
        </div>

        <div data-role='fieldcontain'>
          <label>LinkedIn Profile</label>
          <input type='text' maxlength='255' name='linkedin' value='$linkedin' placeholder='https://www.linkedin.com/in/yourname'>
        </div>

        <div data-role='fieldcontain'>
          <label>Mood Check-In</label>
          <select name='mood'>
            <option value='$mood'>$mood</option>
            <option value='Energized'>Energized</option>
            <option value='Focused'>Focused</option>
            <option value='Neutral'>Neutral</option>
            <option value='Stressed'>Stressed</option>
            <option value='Needs Support'>Needs Support</option>
          </select>
        </div>

        <div data-role='fieldcontain'>
          <label>Profile Thumbnail</label>
          <input type='file' name='image' size='14'>
        </div>

        <div data-role='fieldcontain'>
          <label></label>
          <input data-transition='slide' type='submit' value='Save HiveHub Profile'>
        </div>
      </form>
    </div><br>
  </body>
</html>
_END;
?>
