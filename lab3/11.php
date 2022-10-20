<style>
  table,
  td {
    border: 1px solid gray;
    border-collapse: collapse;
    padding: 8px;
  }
</style>
<table>
  <?php
  $arr = [[5, 12, 17, 9, 3], [13, 4, 8, 14, 1], [9, 6, 3, 7, 21]];
  foreach ($arr as $row) {
    echo "<tr>";
    foreach ($row as $col) {
      echo "<td>$col</td>";
    }
    echo "</tr>";
  }
  ?>
</table>