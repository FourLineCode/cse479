<?php
if (isset($_COOKIE['auth'])) {
  setcookie("auth", "", time() - 3600);
  return header("Location: /www/login.php");
}
