<?php
if (!isset($_COOKIE['auth'])) {
  return header("Location: /www/login.php");
}
?>

<h1>Change Password</h1>
<form action="/www/changepass.php" method="POST">
  <label for="curr">Current Password</label>
  <input type="password" name="curr"><br>
  <label for="new">New Password</label>
  <input type="password" name="new"><br>
  <label for="renew">Retype New Password</label>
  <input type="password" name="renew"><br>
  <hr>
  <input type="submit" value="Change">
  <a href="/www/">Home</a>
</form>



<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = explode("|", $_COOKIE["auth"]);

  $curr = $_POST["curr"];
  $new = $_POST["new"];
  $renew = $_POST["renew"];

  if ($new != $renew) {
    echo "<h3 style='color: red;'>Password doesn't match!</h3>";
    return;
  }

  $conn = mysqli_connect("localhost", "root", "", "lab8", 8081);
  $sql = $conn->prepare("SELECT * FROM users WHERE id = ?;");
  $sql->bind_param("s", $user[0]);
  $sql->execute();
  $result = $sql->get_result();

  while ($row = $result->fetch_array(MYSQLI_NUM)) {
    if ($curr != $row[3]) {
      echo "<h3 style='color: red;'>Incorrect password!</h3>";
      return;
    }

    $sql = $conn->prepare("UPDATE users SET password = ? WHERE id = ?;");
    $sql->bind_param("ss", $new, $user[0]);
    $sql->execute();

    return header("Location: /www/");
  }
}
