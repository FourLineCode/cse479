<style>
  table,
  th,
  td {
    border: 1px solid black;
    border-collapse: collapse;
  }

  td {
    padding: 0.5rem
  }
</style>

<?php
if (!isset($_COOKIE['auth'])) {
  return header("Location: /www/login.php");
}

$user = explode("|", $_COOKIE["auth"]);
echo "<table>";
echo "<tr><td colspan='2'>Profile</td></tr>";
echo "<tr><td>ID</td><td>" . $user[0] . "</td></tr>";
echo "<tr><td>Name</td><td>" . $user[1] . "</td></tr>";
echo "<tr><td>Email</td><td>" . $user[2] . "</td></tr>";
echo "<tr><td>User Type</td><td>" . $user[4] . "</td></tr>";
echo "<tr><td colspan='2'><a href='/www/'>Go Home</a><br></td></tr>";
echo "</table>";
