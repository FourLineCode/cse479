<h1>Login</h1>
<form action="/www/login.php" method="POST">
  <label for="id">User ID</label>
  <input type="text" name="id"><br>
  <label for="password">Password</label>
  <input type="password" name="password"><br>
  <hr>
  <input type="submit" value="Login">
  <a href="/www/register.php">Register</a>
</form>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST["id"];
  $password = $_POST["password"];

  if ($id == "" || $password == "") {
    echo "<h3 style='color: red;'>Field cannot be empty!</h3>";
    return;
  }

  $conn = mysqli_connect("localhost", "root", "", "lab8", 8081);
  $sql = $conn->prepare("SELECT * FROM users WHERE id = ?;");
  $sql->bind_param("s", $id);
  $sql->execute();
  $result = $sql->get_result();

  while ($row = $result->fetch_array(MYSQLI_NUM)) {
    if ($password != $row[3]) {
      echo "<h3 style='color: red;'>Incorrect password!</h3>";
      return;
    }

    $cookie = join("|", $row);
    setcookie("auth", $cookie);

    return header("Location: /www/");
  }
  echo "<h3 style='color: red;'>User not found!</h3>";
}
