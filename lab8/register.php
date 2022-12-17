<h1>Registration</h1>
<form action="/www/register.php" method="POST">
  <label for="id">ID</label>
  <input type="text" name="id"><br>
  <label for="password">Password</label>
  <input type="password" name="password"><br>
  <label for="cpassword">Confirm Password</label>
  <input type="password" name="cpassword"><br>
  <label for="name">Name</label>
  <input type="text" name="name"><br>
  <label for="email">Email</label>
  <input type="email" name="email"><br>
  <label for="user_type">User Type [User/Admin]</label>
  <select type="text" name="user_type">
    <option value="user">User</option>
    <option value="admin">Admin</option>
  </select><br>
  <hr>
  <input type="submit" value="Register">
  <a href="/www/login.php">Login</a>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST["id"];
  $password = $_POST["password"];
  $cpassword = $_POST["cpassword"];
  $name = $_POST["name"];
  $email = $_POST["email"];
  $role = $_POST["user_type"];

  if ($password != $cpassword) {
    echo "<h3 style='color: red;'>Password doesn't match!</h3>";
    return;
  }

  if ($id == "" || $password == "" || $name == "" || $email == "") {
    echo "<h3 style='color: red;'>Field cannot be empty!</h3>";
    return;
  }

  $conn = mysqli_connect("localhost", "root", "", "lab8", 8081);

  $sql = $conn->prepare("SELECT * FROM users WHERE id = ?;");
  $sql->bind_param("s", $id);
  $sql->execute();
  $result = $sql->get_result();

  while ($row = $result->fetch_array(MYSQLI_NUM)) {
    if ($row[0] == $id) {
      echo "<h3 style='color: red;'>User ID already exists!</h3>";
      return;
    }
  }

  $sql = $conn->prepare("INSERT INTO users (id, username, email, password, role) VALUES (?,?,?,?,?);");
  $sql->bind_param("sssss", $id, $name, $email, $password, $role);
  $sql->execute();

  return header("Location: /www/login.php");
}
