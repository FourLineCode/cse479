<?php
$chars = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
for ($i = 65; $i < 91; $i++) array_push($chars, chr($i));
for ($i = 97; $i < 123; $i++) array_push($chars, chr($i));

shuffle($chars);

echo "Random password: ", join(array_slice($chars, 0, 12));
