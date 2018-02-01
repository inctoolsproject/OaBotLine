<?php
$var = file_get_contents('quote.txt');
$result = explode(',',$var);
echo $result[array_rand($result)];
?>