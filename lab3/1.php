<?php
for ($i = 2000; $i <= 5000; $i++) {
  if ($i % 11 == 0 || $i % 13 == 0) {
    echo $i, "<br>";
  }
}
