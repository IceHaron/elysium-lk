<?php
require 'charsets.php';
$l = explode(':', $_SERVER['QUERY_STRING']);
if($l[0]==1)
$string = file_get_contents('/root/minecraft/kernel/plugins/CCLogger/players/'.$l[1].'.log');
else
$string = file_get_contents('/root/minecraft/backtrack/plugins/CCLogger/players/'.$l[1].'.log');

$string = iconv('utf-8', 'cp1252', $string);

//$string = iconv('cp1251', 'utf-8', $string);

echo nl2br($string);
?>