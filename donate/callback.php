<?php
error_reporting(E_ALL ^ E_NOTICE);
require 'pex.php';
//кодовое слово
$code_word = "ilovepornBb11";

//  входные данные
$from=$_GET['from'];
$date=$_GET['date'];
$msg=$_GET['msg'];
$cost=$_GET['cost'];
$operator_id=$_GET['operator_id'];
$country=$_GET['country'];
$short_number=$_GET['short_number'];
$sms_id=$_GET['sms_id'];
$abonent_cost=$_GET['abonent_cost'];
$clear_msg=$_GET['clear_msg'];
$sign=$_GET['sign'];

$sms_id=$_GET['sms_id'];
$pay_status=$_GET['pay_status'];
/*
//обновление статуса мт-сообщений
if ($pay_status=="not_ok") {
    $file = file('baza.txt');
    $out_file = "";
    //обход файла по строкам
    foreach ($file as $str_id => $str_row) {
        //если в строке нашли идентификатор то для этой SMS нужно обнулить стоимость SMS
        if(preg_match('/'.$sms_id.'/i',$str_row)) {
            $str_row=preg_replace("/([^\|]+)\|\|([^\|]+)\|\|([^\|]+)\|\|([^\|]+)\|\|([^\|]+)\|\|([^\|]+)\|\|([^\|]+)\|\|([^\|]+)\|\|([^\|]+)/","\$1||\$2||\$3||0||\$5||\$6||\$7||\$8||\$9",$str_row);
        }
        $out_file .= $str_row;
    }
    //записать данные обратно в файл и завершить выполнение скрипта
    file_put_contents('baza.txt', $out_file);
    die("ok\nDengi zachisleni");
} elseif ($pay_status=="ok") {
    //при оплаченой услуге можно ничего не менять т.к. в строке SMS цена уже проставлена,
    //завершить выполнение скрипта
    die("ok\nDengi zachisleni");
}*/

//если скрипт был вызван с неправильным параметром безопасности, завершить выполнение.
if($sign!=md5($_GET['sms_id'].$code_word)) die('hacking attempt');

//
$fp=fopen("baza.txt","a");
fputs($fp,"$from||$date||$msg||$cost||$operator_id||$country||$short_number||$sms_id||$abonent_cost\n");
fclose($fp);
//

$fp=fopen("nomera.txt","a");
fputs($fp,"$from||");
fclose($fp);

if($short_number == '8510')
{
$info = explode(' ', $msg);
$vip = addVIP($info[1], 2);
if(!$vip) {$f = fopen('errors.txt','a+'); fputs($f, $info[1].':2'."\n"); fclose($f); echo "ok\nVIP will activate in 30 minutes";} else echo "ok\nVIP activated"; 
}
else
{
echo "ok\nEtot nomer ne ispolzuetsa!";
}
?>