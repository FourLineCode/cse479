<?php
$nums = [1, 2, 3, 4, 5, 6, 7, 8, 9];

echo "Before: ";
foreach ($nums as $key => $val) {
  echo "$val, ";
}
echo "<br>";

rsort($nums);

echo "After: ";
foreach ($nums as $key => $val) {
  echo "$val, ";
}
