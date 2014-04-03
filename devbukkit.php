<?php
include('WebIcqPro.class.php');
 
$plugins = array('worldedit', 'vault', 'vanish');
$arr = json_decode(file_get_contents('plugins.txt'));
foreach($plugins as $plugin)
{
$f = file_get_contents('http://dev.bukkit.org/server-mods/'.$plugin.'/files/');
$n = explode('data-epoch="', $f);
$n1 = explode('"', $n[1]);
$time = $n1[0];
if($time != $arr->$plugin)
{
$updated[] = $plugin;
}
$arr->$plugin = $time;
}
file_put_contents('plugins.txt', json_encode($arr));

$icq = new WebIcqPro();

if($icq->connect(642065268, 'budeki1')){
  if(!$icq->sendMessage('619542', 'hi'))
	echo $icq->error;
	$icq->disconnect();
}
	 else
echo $icq->error;
?>