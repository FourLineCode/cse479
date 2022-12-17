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
if ($user[4] != "admin") {
  return header("Location: /www/");
}

$conn = mysqli_connect("localhost", "root", "", "lab8", 8081);
$sql = $conn->prepare("SELECT * FROM users;");
$sql->execute();
$result = $sql->get_result();

echo "<table>";
echo "<tr><td colspan='4'>Users</td></tr>";
echo "<tr><td>ID</td><td>Name</td><td>Email</td><td>User Type</td></tr>";
while ($row = $result->fetch_array(MYSQLI_NUM)) {
  echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[4] . "</td></tr>";
}
echo "<tr><td colspan='4'><a href='/www/'>Go Home</a><br></td></tr>";
echo "</table>";
