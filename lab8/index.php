<?php
if (!isset($_COOKIE['auth'])) {
  return header("Location: /www/login.php");
}

$user_html = '<div>
<a href="/www/profile.php">Profile</a><br>
<a href="/www/changepass.php">Change Password</a><br>
<a href="/www/logout.php">Logout</a><br>
</div>';

$admin_html = '<div>
<a href="/www/profile.php">Profile</a><br>
<a href="/www/changepass.php">Change Password</a><br>
<a href="/www/users.php">View Users</a><br>
<a href="/www/logout.php">Logout</a><br>
</div>';

$user = explode("|", $_COOKIE["auth"]);
echo "<h1>Welcome " . $user[1] . "!</h1>";

if ($user[4] == "user") echo $user_html;
else echo $admin_html;
