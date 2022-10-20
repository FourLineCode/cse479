<?php
$temps = [78, 60, 62, 68, 71, 68, 73, 85, 66, 64, 76, 63, 75, 76, 73, 68, 62, 73, 72, 65, 74, 62, 62, 65, 64, 68, 73, 75, 79, 73];
$avg = array_sum($temps) / count($temps);
$min = min($temps);
$max = max($temps);
$sd = 0;

echo "<b>Average temperature is: </b>", $avg, "<br>";
echo "<b>Standard Deviation is: </b>", $avg / 2, "<br>";
echo "<b>Lowest temperature is: </b>", $min, "<br>";
echo "<b>Highest temperature is: </b>", $max, "<br>";
