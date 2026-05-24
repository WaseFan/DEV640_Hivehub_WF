<?php // index.php for HiveHub
  session_start();
  require_once 'header.php';

  echo "<div class='center hive-hero'>";

  if ($loggedin)
  {
    echo <<<_LOGGEDIN
      <h2>Welcome back to HiveHub, $user!</h2>
      <p>
        HiveHub helps startup employees stay connected, recognized, and engaged.
        Use the member directory to find coworkers, update your profile, connect
        with colleagues, send messages, and participate in company community spaces.
      </p>

      <div class='feature-grid'>
        <div class='feature-card'>
          <h3>Build Relationships</h3>
          <p>Find colleagues, add friends, and strengthen workplace connections.</p>
        </div>

        <div class='feature-card'>
          <h3>Share Recognition</h3>
          <p>Celebrate employee shoutouts, badges, birthdays, and work anniversaries.</p>
        </div>

        <div class='feature-card'>
          <h3>Stay Engaged</h3>
          <p>Follow company events, join interest-based groups, and check morale trends.</p>
        </div>
      </div>
_LOGGEDIN;
  }
  else
  {
    echo <<<_GUEST
      <h2>Welcome to HiveHub</h2>
      <p>
        HiveHub is a corporate social networking platform designed for fast-growing
        startup teams. Employees can create accounts, securely log in, build personal
        profiles, upload profile thumbnails, find coworkers, add friends, and send
        public or private messages.
      </p>

      <p>
        The goal of HiveHub is to help employees feel connected, recognized, and
        engaged, especially in hybrid or rapidly scaling workplaces.
      </p>

      <div class='feature-grid'>
        <div class='feature-card'>
          <h3>Community</h3>
          <p>Connect with coworkers through profiles, friends, messages, and groups.</p>
        </div>

        <div class='feature-card'>
          <h3>Recognition</h3>
          <p>Support morale with shoutouts, badges, birthdays, and anniversaries.</p>
        </div>

        <div class='feature-card'>
          <h3>Wellness</h3>
          <p>Use optional mood check-ins to understand team morale and engagement.</p>
        </div>
      </div>

      <p class='info'>Please sign up or log in to access HiveHub.</p>
_GUEST;
  }

  echo <<<_END
      </div><br>
    </div>
    <div data-role="footer">
      <h4>HiveHub &mdash; Building stronger startup communities</h4>
    </div>
  </body>
</html>
_END;
?>
