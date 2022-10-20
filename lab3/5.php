<?php
for ($i = 1; $i <= 10; $i++) {
  echo $i;
  if ($i != 10) {
    if ($i % 2 == 0) echo "_";
    else echo "-";
  }
}
