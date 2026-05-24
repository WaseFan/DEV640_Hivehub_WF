<!DOCTYPE html> <!-- HiveHub: setup.php -->
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>HiveHub Database Setup</title>
  </head>
  <body>
    <h3>Setting up HiveHub database...</h3>

<?php
  require_once 'functions.php';

  // Core employee account table
  createTable('members',
              'user VARCHAR(32) NOT NULL,
              pass VARCHAR(255) NOT NULL,
              email VARCHAR(100),
              department VARCHAR(64),
              role VARCHAR(64),
              linkedin VARCHAR(255),
              created_at INT UNSIGNED,
              INDEX(user(12))');

  // Employee profile details and thumbnail/profile picture path
  createTable('profiles',
              'user VARCHAR(32) NOT NULL,
              full_name VARCHAR(80),
              about_me VARCHAR(4096),
              interests VARCHAR(1024),
              skills VARCHAR(1024),
              thumbnail VARCHAR(255),
              INDEX(user(12))');

  // Internal workplace connections
  createTable('friends',
              'user VARCHAR(32) NOT NULL,
              friend VARCHAR(32) NOT NULL,
              status VARCHAR(16) DEFAULT "pending",
              created_at INT UNSIGNED,
              INDEX(user(12)),
              INDEX(friend(12))');

  // Public profile posts and private messages
  createTable('messages',
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              auth VARCHAR(32) NOT NULL,
              recip VARCHAR(32),
              pm CHAR(1),
              time INT UNSIGNED,
              message VARCHAR(4096),
              sentiment VARCHAR(32),
              INDEX(auth(12)),
              INDEX(recip(12))');

  // Employee shoutouts and recognition badges
  createTable('recognition',
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              sender VARCHAR(32) NOT NULL,
              receiver VARCHAR(32) NOT NULL,
              badge VARCHAR(64),
              note VARCHAR(1024),
              time INT UNSIGNED,
              INDEX(sender(12)),
              INDEX(receiver(12))');

  // Company-wide announcements and event updates
  createTable('events',
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              title VARCHAR(120) NOT NULL,
              description VARCHAR(2048),
              event_date DATE,
              created_by VARCHAR(32),
              created_at INT UNSIGNED,
              INDEX(created_by(12))');

  // Interest-based groups such as wellness, gaming, pets, food, and fitness
  createTable('groups',
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              name VARCHAR(80) NOT NULL,
              description VARCHAR(1024),
              created_by VARCHAR(32),
              created_at INT UNSIGNED,
              INDEX(name(20)),
              INDEX(created_by(12))');

  createTable('group_members',
              'group_id INT UNSIGNED NOT NULL,
              user VARCHAR(32) NOT NULL,
              joined_at INT UNSIGNED,
              INDEX(group_id),
              INDEX(user(12))');

  // Optional mood check-ins for morale and burnout/sentiment analytics
  createTable('mood_checkins',
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              user VARCHAR(32) NOT NULL,
              mood VARCHAR(32),
              burnout_level TINYINT UNSIGNED,
              note VARCHAR(1024),
              time INT UNSIGNED,
              INDEX(user(12))');

  // Birthday and work anniversary highlights
  createTable('employee_milestones',
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              user VARCHAR(32) NOT NULL,
              milestone_type VARCHAR(32),
              milestone_date DATE,
              INDEX(user(12)),
              INDEX(milestone_type(12))');
?>

    <br>...HiveHub database setup complete.
  </body>
</html>
