<?php
define('INCLUDE_CHECK',true);
require 'functions.php';

$cost = $_POST['OutSum'];
$nick = $_POST['shpnick'];
$vage = $_POST['InvId'];

$crc = strtoupper(md5("$cost:$vage:analzoo1488:shpnick=$nick"));
$lold = $_POST['SignatureValue'];

if($crc != $lold) die('Scorpi, go away.');

$db_table       = 'xf_user';
$db_columnId  = 'user_id';
$db_columnUser  = 'username';
$link = @mysql_connect('db01.hostline.ru:3306','vh55836_web','krotkarapuz') or die('Нет коннекта к БД!');
mysql_select_db('vh55836_xen',$link);
$sql = "UPDATE `xf_user` SET `coins` = `coins`+$cost WHERE username LIKE BINARY '$nick'";
mysql_query($sql);
echo 'OK'.$vage;
writeLog($nick.' deposit from ROBOKASSA (+'.round($cost).' RUR)');
?>