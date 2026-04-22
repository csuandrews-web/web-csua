<?php
$file = "loginstats.txt";
$count = file_exists($file) ? (int)file_get_contents($file) : 0;
file_put_contents($file, ++$count);
?>