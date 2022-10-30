<form action="/10.php">
  <label for="unit">Unit:</label>
  <input type="number" name="unit" id="unit">
  <input type="submit" value="Submit">
</form>

<?php
$unit = $_GET["unit"] ?? 0;
$cost = 0;

$cost += min(50, $unit) * 3.5;
if ($unit > 50) $unit -= 50;
else $unit = 0;

$cost += min(100, $unit) * 4;
if ($unit > 100) $unit -= 100;
else $unit = 0;

$cost += min(100, $unit) * 5.20;
if ($unit > 100) $unit -= 100;
else $unit = 0;

$cost += $unit * 6.5;

echo "Total cost: ", $cost, "<br>";
