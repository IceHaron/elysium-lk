<?php
/* Конфиги */
$item_id = 264;				      // ID итема который получит игрок (по умолчанию выдается камень)
$item_count = 3;			    // Колличество итемов (по умолчанию выдается 1)

$hostname = "localhost";	// IP базы данных MySQL
$username = "root";	      // Логин к базе данных MySQL
$password = "ololena";		    // Пароль к базе данных MySQL
$dbName = "bonus";		  // Имя базы данных MySQL
/* /Конфиги */




if ($_GET['nickname']!='') {
  // подключаемся к базе данных MySQL
  $connect_to_mysql = mysql_connect($hostname,$username,$password) OR DIE("Не могу соединиться с базой"); 
  mysql_select_db($dbName) or die(mysql_error()); 

  $nickname=mysql_escape_string($_GET['nickname']); 
  $nickname=mysql_real_escape_string($nickname);
  // добавляем в базу данных
  $query = "INSERT INTO `w2v_bonus` (`nickname`, `item_id`, `item_amount`) VALUES ('".$nickname."', '".$item_id."', '".$item_count."')"; 
  mysql_query($query) or die(mysql_error());
  mysql_close($connect_to_mysql);
}
?>