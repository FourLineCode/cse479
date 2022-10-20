<?php
$random = rand(1, 9);

echo "Number: ", $random, "<br>";
echo "Day of the week: ";
switch ($random) {
  case 1:
    echo "Saturday";
    break;
  case 2:
    echo "Sunday";
    break;
  case 3:
    echo "Monday";
    break;
  case 4:
    echo "Tuesday";
    break;
  case 5:
    echo "Wednesday";
    break;
  case 6:
    echo "Thursday";
    break;
  case 7:
    echo "Friday";
    break;
  default:
    echo "Invalid";
    break;
}
