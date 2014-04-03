<?php
function xenlogin($user, $pass)
{
$db_table       = 'xf_user';
$db_columnId  = 'user_id';
$db_columnUser  = 'username';
$db_columnPass  = 'data';
$db_tableOther = 'xf_user_authenticate';
$link = @mysql_connect('db01.hostline.ru:3306','vh55836_web','krotkarapuz') or die('Ќевозможно установить соединение с базой данных!');
mysql_select_db('vh55836_xen',$link);
mysql_query("SET names UTF8");
$row = mysql_fetch_assoc(mysql_query("SELECT $db_table.$db_columnId,$db_table.$db_columnUser,$db_tableOther.$db_columnId,$db_tableOther.$db_columnPass FROM $db_table, $db_tableOther WHERE $db_table.$db_columnId = $db_tableOther.$db_columnId AND $db_table.$db_columnUser LIKE BINARY '{$user}'"));
$realPass = substr($row[$db_columnPass],22,64);
$salt = substr($row[$db_columnPass],105,64);

if ($realPass) 
{
	$checkPass = hash_xenforo($pass, $salt);	
	
	if(strcmp($realPass,$checkPass) == 0)
							return true;
	else
		return false;
}
else return false;
}

function hash_xenforo($pass, $salt)
{
	
	$cryptPass = false;
	$cryptPass = hash('sha256', hash('sha256', $pass) . $salt);
	
	return $cryptPass;
}

if(xenlogin($_GET['user'],$_GET['pass'])) echo 'okay';
?>