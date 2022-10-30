<?php
for ($i = 1; $i <= 5; $i++) {
  for ($j = 1; $j <= $i; $j++) {
    echo "*&nbsp;";
  }
  echo "<br>";
}
for ($i = 5; $i >= 1; $i--) {
  for ($j = 1; $j <= (5 - $i); $j++) {
    echo "&nbsp;&nbsp;&nbsp;";
  }
  for ($j = 1; $j <= $i; $j++) {
    echo "*&nbsp;";
  }
  echo "<br>";
}
