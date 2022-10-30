<form action="/6.php">
  <label for="unit">String:</label>
  <input type="text" name="str">
  <input type="submit" value="Submit">
</form>

<?php

function is_palindrome($s)
{
  $s_split = str_split($s);
  $s_rev = array_reverse($s_split);
  for ($i = 0; $i < count($s_split); $i++) {
    if ($s_split[$i] != $s_rev[$i]) {
      return false;
    }
    return true;
  }
}

$str = $_GET["str"] ?? "";
if ($str) {
  echo "String: ", $str, "<br>";
  echo "String is palindrome: ", is_palindrome($str) ? "true" : "false";
}
