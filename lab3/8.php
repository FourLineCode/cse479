<form action="/www/8.php">
  <label for="row">Row:</label>
  <input type="number" name="row" value="8">
  <label for="col">Column:</label>
  <input type="number" name="col" value="8">
  <input type="submit" value="Submit">
</form>

<?php
$row = $_GET["row"] ?? null;
$col = $_GET["col"] ?? null;

if ($row && $col) {
  for ($i = 0; $i < $row; $i++) {
    for ($j = 0; $j < $col; $j++) {
      if ($i % 2 == 0)
        echo $j % 2 == 0 ? "⬜️" : "⬛️";
      else
        echo $j % 2 == 0 ? "⬛️" : "⬜️";
    }
    echo "<br>";
  }
}
