<?php
define('INCLUDE_CHECK',true);

require 'functions.php';
require 'config.php'; 
require 'pex.php';

function xenlogin($user, $pass)
{
$db_table       = 'xf_user';
$db_columnId  = 'user_id';
$db_columnUser  = 'username';
$db_columnPass  = 'data';
$db_tableOther = 'xf_user_authenticate';
$link = @mysql_connect('db01.hostline.ru:3306','vh55836_web','krotkarapuz') or die('Нет коннекта к БД!');
mysql_select_db('vh55836_xen',$link);
mysql_query("SET names UTF8");
$row = mysql_fetch_assoc(mysql_query("SELECT $db_table.$db_columnId,$db_table.$db_columnUser,$db_tableOther.$db_columnId,$db_tableOther.$db_columnPass FROM $db_table, $db_tableOther WHERE $db_table.$db_columnId = $db_tableOther.$db_columnId AND $db_table.$db_columnUser LIKE BINARY '{$user}'"));
$realPass = substr($row[$db_columnPass],22,64);
$salt = substr($row[$db_columnPass],105,64);

if ($realPass) 
{
	$checkPass = hash_xenforo($pass, $salt);	
	
	if(strcmp($realPass,$checkPass) == 0)
							return array($row['user_id'], $row['username']);
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

function getCoins($user)
{
$db_table       = 'xf_user';
$db_columnId  = 'user_id';
$db_columnUser  = 'username';
$link = @mysql_connect('db01.hostline.ru:3306','vh55836_web','krotkarapuz') or die('Нет коннекта к БД!');
mysql_select_db('vh55836_xen',$link);
mysql_query("SET names UTF8");
$sql = "SELECT * FROM `xf_user` WHERE `username` LIKE '$user'";
$row = mysql_fetch_assoc(mysql_query($sql));
return $row['coins'];
}

function withdrawCoins($user, $howmuch)
{
$db_table       = 'xf_user';
$db_columnId  = 'user_id';
$db_columnUser  = 'username';
$link = @mysql_connect('db01.hostline.ru:3306','vh55836_web','krotkarapuz') or die('Нет коннекта к БД!');
mysql_select_db('vh55836_xen',$link);
mysql_query("SET names UTF8");
$sql = "SELECT * FROM `xf_user` WHERE `username` LIKE '$user'";
$row = mysql_fetch_assoc(mysql_query($sql));
$coins = $row['coins']; 
$new = $coins-$howmuch;
if($new<0) return false; else
{
$sql = "UPDATE `xf_user` SET `coins` = $new WHERE username LIKE BINARY '$user'";
mysql_query($sql);
return true;
}
}
	
	session_name('Login');
	session_set_cookie_params(2*7*24*60*60);
	session_start();
	
	if($_SESSION['id'] && !isset($_COOKIE['Remember']) && !$_SESSION['rememberMe'])
	{
		$_SESSION = array();
		session_destroy();
	}
 
	if(isset($_GET['logoff']))
	{
		$_SESSION = array();
		session_destroy();
		header("Location: index.php");
		exit;
	}
	
	if($_POST['submit']=='Войти')
	{
		$err = array();
	
		if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'Все поля должны быть заполнены!';
		

			//$_POST['username'] = mysql_real_escape_string($_POST['username']);
			//$_POST['password'] = mysql_real_escape_string($_POST['password']);
			$_POST['rememberMe'] = (int)$_POST['rememberMe'];
			$login = xenlogin($_POST['username'], $_POST['password']);
				
			if($login)
			{
				$_SESSION['playername']=$login[1];
				$_SESSION['id'] = $login[0];
				$_SESSION['rememberMe'] = $_POST['rememberMe'];
				setcookie('Remember',$_POST['rememberMe']);
				writeLog($_SESSION['playername'].' logged in',1);
			}
			else
			{
				$err[]='Неправильный пароль или нет такого пользователя.';
				writeLog($_POST['username'].' attempted to log in',1);
			}


		if($err)
		$_SESSION['msg']['login-err'] = implode('<br />',$err);
		header("Location: index.php");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Личный кабинет</title>
	<link href="cabinet.css" rel="stylesheet" type="text/css" />
	<script>
	function refresh_money()
	{
	if(document.getElementById('1').checked)
	  document.getElementById('lolcost').value = parseFloat(document.getElementById('baxcost').value) / 1000;
	  else if(document.getElementById('2').checked)
	  document.getElementById('lolcost').value = parseFloat(document.getElementById('baxcost').value) / 500;
	  if(isNaN((document.getElementById('baxcost').value))) document.getElementById('lolcost').value = '0';
	  if(document.getElementById('lolcost').value < 10) { document.getElementById('exchanger').disabled = true; document.getElementById('from10').style.display = '';}
	  else {document.getElementById('exchanger').disabled = false; document.getElementById('from10').style.display = 'none';}
	}
	</script>
	</head>
	<body>
 	<div id="container">
   		<div id="header"><a href="./" style="border: 0; color:black;">Личный кабинет</a></div>
  		<div style="width: 206px; margin-top: 0px; height: 430px; text-align: center;" id="sidebar">
		<?if($_SESSION['id']) {
		$john1 = getGroup($_SESSION['playername'],'kernel');
		$john2 = getGroup($_SESSION['playername'],'backtrack');
		if(sizeof($john1)>1) $prava[] = 'Kernel: '.$john1[0].' до <span style="color:#00BFBF">'.date('d.m.y', $john1[1]).'</span><br/>';
		if(sizeof($john2)>1) $prava[] = 'BackTrack: '.$john2[0].' до <span style="color:#00BFBF">'.date('d.m.y', $john2[1]).'</span><br/>';
						foreach($_POST as $param => $value)
				{
				$param_exploded = explode('-', $param);
				if($param_exploded[0] == 'buy') {
				switch($param_exploded[1]){
					case 1:
					if($john1) break;
					$g = getCoins($_SESSION['playername']);
					if($g<200) { $error = 'Недостаточно денег'; break; }
					$s = addGroup($_SESSION['playername'],1,'vip');
					if($s)  { withdrawCoins($_SESSION['playername'], 200); $inf = 'Покупка совершена успешно!';writeLog($_SESSION['playername'].' bought VIP on Kernel (-200 RUR)'); }else $error = 'Попробуйте приобрести позже, сервер временно недоступен';
					
					break;
					
					case 2:
					if($john1) break;
					$g = getCoins($_SESSION['playername']);
					if($g<400) { $error = 'Недостаточно денег'; break; }
					$s = addGroup($_SESSION['playername'],1,'gvip'); 
					if($s) { withdrawCoins($_SESSION['playername'], 400); $inf = 'Покупка совершена успешно!';writeLog($_SESSION['playername'].' bought GoldVIP on Kernel (-400 RUR)');} else $error = 'Попробуйте приобрести позже, сервер временно недоступен';
					
					break;
					
					case 3:
					if($john2) break;
					$g = getCoins($_SESSION['playername']);
					if($g<200) { $error = 'Недостаточно денег'; break; }
					$s = addGroup($_SESSION['playername'],2,'vip');
					if($s) { withdrawCoins($_SESSION['playername'], 200); $inf = 'Покупка совершена успешно!';writeLog($_SESSION['playername'].' bought VIP on BackTrack (-200 RUR)');} else $error = 'Попробуйте приобрести позже, сервер временно недоступен';
					
					break;
					
					case 4:
					if($john2) break;
					$g = getCoins($_SESSION['playername']);
					if($g<400) { $error = 'Недостаточно денег'; break; }
					$s = addGroup($_SESSION['playername'],2,'gvip');
					if($s) { withdrawCoins($_SESSION['playername'], 400); $inf = 'Покупка совершена успешно!';writeLog($_SESSION['playername'].' bought GoldVIP on BackTrack (-400 RUR)'); }else $error = 'Попробуйте приобрести позже, сервер временно недоступен';
					break;
					
					case 5:
					$g = getCoins($_SESSION['playername']);
					$coins = intval($_POST['wannacoins']);
					if($_POST['valuta_server'] == 1) {
					$wd = $coins/1000;
					if($wd<10) {$error = 'Заказ валюты только от 10 р.'; break; }
					if($g<$wd) { $error = 'Недостаточно денег'; break; }
					$s = giveMoney($_SESSION['playername'], 1, $coins);
					if($s) { withdrawCoins($_SESSION['playername'], intval($wd)); $inf = 'Покупка совершена успешно!'; writeLog($_SESSION['playername'].' bought '.$coins.'$ on Kernel (-'.intval($wd).' RUR)');} else $error = 'Попробуйте приобрести позже, сервер временно недоступен';
					}else {
					$wd = $coins/500;
					if($wd<10) {$error = 'Заказ валюты только от 10 р.'; break; }
					if($g<$wd) { $error = 'Недостаточно денег'; break; }
					$s = giveMoney($_SESSION['playername'], 2, $coins);
					if($s) { withdrawCoins($_SESSION['playername'], intval($wd)); $inf = 'Покупка совершена успешно!'; writeLog($_SESSION['playername'].' bought '.$coins.'$ on BackTrack (-'.intval($wd).' RUR)');} else $error = 'Попробуйте приобрести позже, сервер временно недоступен';
					}
					break;
					
					case 6:
					$g = getCoins($_SESSION['playername']);
					$prefix = $_POST['prefix'];
					if($g<50) { $error = 'Недостаточно денег'; break; }
					$s = setPrefix($_SESSION['playername'], $_POST['prefix_server'], substr($prefix, 0, 15));
					if($s) { withdrawCoins($_SESSION['playername'], 50); $inf = 'Покупка совершена успешно!'; writeLog($_SESSION['playername'].' bought prefix "'.substr($prefix,0,15).'" on BackTrack (-50 RUR)'); } else $error = 'Попробуйте приобрести позже, сервер временно недоступен';
					break;
				}
				}
				}
		?>
			<h2>Привет, <?=$_SESSION['playername'];?><br/><a style="font-size: 90%; float:center;" href="?logoff=yep">(выйти)</a>
			<hr>Ваш баланс:</h2>
			<? echo getCoins($_SESSION['playername']); ?> рублей <a href="?p=2" style="font-size: 90%">(пополнить)</a><br/>
			<span style="color: gray">Игровая валюта: скоро...</span>
			<hr/>
			<h2>Ваши права</h2>
			<?php if(!$john1) echo 'Kernel: <span style="color: #00BFBF;">игрок</span></br>';
				  if($prava[0]) echo $prava[0];
				  if((sizeof($john1) == 1) && $john1) echo 'Kernel: '.$john1.'<br/>';
				  
				  if($prava[0] && $prava[1]) echo '<br/>';
				  
				  if(!$john2) echo 'BackTrack: <span style="color: #00BFBF;">игрок</span>';
				  if($prava[1]) echo $prava[1];
				  if((sizeof($john2) == 1) && $john2) echo 'BackTrack: '.$john2;
				  ?>
			<hr/>
			<h2>Голосуй за нас!</h2>
<a href="http://www.want2vote.com/info.php?id=1104" target="_blank" style="border: 0"><img src="http://w2v.biz/_status/pictures/status_votebanner/1104.jpg"></a>

			<?} else {?>
			<h2>Авторизуйтесь</h2>
			<form action="" method="post" enctype="multipart/form-data">
			Логин:<br /><input type="text" name="username"/><br />
			Пароль:<br /><input type="password" name="password"/><br />
			<input name="rememberMe" type="checkbox"  value="1" /> &nbsp;Запомнить меня<br />
			<input type="submit" name="submit" value="Войти" class="button" /><br />
			</form><hr/>
			<div style="text-align:center;">
				<img src="http://www.webmoney.ru/img/icons/88x31_wm_blue_on_white_ru.png"/>
				<!-- begin WebMoney Transfer : attestation label --> 
				<a href="https://passport.webmoney.ru/asp/certview.asp?wmid=131200683789" style="border:0" target=_blank><IMG SRC="http://www.webmoney.ru/img/icons/88x31_wm_v_blue_on_white_ru.png" title="Здесь находится аттестат нашего WM идентификатора 131200683789" border="0"><br><font size=1>Проверить аттестат</font></a>
				<!-- end WebMoney Transfer : attestation label -->
			</div>
			<?if($_SESSION['msg']['login-err'])
	{
		echo '<p style="color:red">'.$_SESSION['msg']['login-err'].'<br /></p>';
		unset($_SESSION['msg']['login-err']);
	}
			}?>
   		</div>
		<div style="margin-left: 228px; height: 350px; width: 750px;" id="content">
			<?if($_SESSION['id']) {
				if($_FILES['skin']['tmp_name']) $info[] = handleSkin($_SESSION['playername']);
				if($_FILES['cloak']['tmp_name']) $info[] = handleCloak($_SESSION['playername']);
				if($_POST['dskin']) unlink('upload/skins/'.$_SESSION['playername'].'.png');
				if($_POST['dcloak']) unlink('upload/cloaks/'.$_SESSION['playername'].'.png');
				$bad1 = '<span style="color:red">'.$info[0][error].'</span>';
				$bad2 = '<span style="color:red">'.$info[1][error].'</span>';
				
			if($_GET['p'] == 2) {?>
    		<h2 style="text-align: center;"><a href="./">Скины и плащи</a> | Донат</h2>
<?
$er = '<span style="color:red">'.$error.'</span>';
$in = '<span style="color:green">'.$inf.'</span>';
if($error) echo $er;
if($inf) echo $in;
?>
<form action="" method="POST" style="margin-top: -30px;">
<div style="margin-top: 50px;border-right: 1px dashed gray; width: 280px; padding-bottom: 11px;" >
<h3 align=center>Доп. возможности</h3>
			<table style="font-size:125%;" width=280px border=0 cellpadding=5 cellspacing=0>

<tr>
<td></td><td>VIP <small>(200 р.)<small></td><td><small>GoldVIP (400 р.)</small></td>
</tr>
<tr>
<td>Kernel</td><td><input <?if($john1) echo 'disabled ';?> type="submit" name="buy-1" value="Купить"/></td><td><input <?if($john1) echo 'disabled ';?> type="submit" name="buy-2" value="Купить"/></td>
</tr>
<tr>
<td>BackTrack</td><td><input <?if($john2) echo 'disabled ';?>type="submit" name="buy-3" value="Купить"/></td><td><input <?if($john2) echo 'disabled ';?>type="submit" name="buy-4" value="Купить"/></td>
</tr>
</table><?php if($john1 || $john2) {?>
<span style="color:red; font-size:100%;">Заблокированные кнопки означают, что нельзя купить группу, если еще не истёк срок предыдущей покупки</span>
<?}?>
<br/><br/>
</div>
<div style="text-align:center; float: right; margin-top:-220px; margin-right: 260px;border-right: 1px dashed gray; padding-right: 25px;">
<h3>Игровая валюта</h3>
<div style="padding-bottom: 75px; margin: 0px;">Покупаю <input id="baxcost" value="10000" size="3" maxlength="7" onKeyUp="refresh_money()" name="wannacoins"/>$</br>
<span style="padding-left: 55px;">за <input id="lolcost" size="3" maxlength="7" value="10" style="background: lightgray; text-align:center;" readonly/>руб.</span></br>
на <input type="radio" id="1" name="valuta_server" value="1" onchange="refresh_money()"  checked>Kernel | <input type="radio" id="2" onchange="refresh_money()" name="valuta_server" value="2">BackTrack</br>
<span id="from10" style="font-size: 70%;color: red;display: none;">от 10 р.</span>
<input type="submit" id="exchanger" name="buy-5" value="Купить"/></div>
</div>
<div style="text-align:center; float: right; margin-top:-220px; width: 180px; margin-right: 30px;border-right: 0px dashed gray; padding-right: 10px; padding-bottom: 57px;">
<h3>Префикс</h3>
Установить префикс (50 р. за каждую смену) <br/><input value="&3Игрок" size="10" maxlength="15" name="prefix"/><br/><a href="" onclick="window.open('colors.php', 'colors', 'width=400,height=200,scrollbars=yes');"><font size=1>(о цветах)</font></a><br/>
<div style="margin-top: 8px;">на <input type="radio" id="1" name="prefix_server" value="1" checked>Kernel | <input type="radio" id="2" name="prefix_server" value="2">BackTrack</div>
<input type="submit" name="buy-6" value="Купить"/>
</div>
</form>
<hr/>
<div style="text-align:center;  padding-right: 2px;">
<h3>Пополнение счёта</h3>
<input id="cost" value="50" size="2" maxlength="5"/>руб.</br>
<input type="button" onclick="location.href = 'merchant.php?cost='+document.getElementById('cost').value+'&n=<?echo $_SESSION['playername'];?>'" value="Пополнить"/><br/>
</div>
			<?}else{?>
			<h2 style="text-align: center;">Скины и плащи | <a href="?p=2">Донат</a></h2><?echo $bad1;
				if($info[1][error]) echo '<br/>'.$bad2;echo '<br/>';?>
			<form action="" method="post" enctype="multipart/form-data">
			
			<div id="skin"><div id="skimg"><?
				if ( !file_exists($dir_skins.$_SESSION['playername'].'.png'))
					{
						$skinpath = 'default';
					}
				else
					{
						$skinpath = $_SESSION['playername'];
					}
					
				echo '<img src="skin2d.php?skinpath='.$skinpath.'&scorpi='.time().'" /><br/>';
			?></div>
				<input type="file" name="skin" /><br/>
				<input type="submit" name="dskin" value="Удалить скин"/>
				
			</div>
			<div id="middle"><input type="submit" value="Закачать" name="upload_submit"/></div>
			<div id="cloak"><div id="skimg"><?
				if (file_exists($dir_cloaks.$_SESSION['playername'].'.png'))
					echo '<img src="skin2d.php?skinpath='.$_SESSION['playername'].'&scorpi='.time().'&c=1" /><br/>';
					else echo '<br/><br/><br/><h3>Плащ не установлен</h3>';
			?></div><input type="file" name="cloak" /><br/><input type="submit" name="dcloak" value="Удалить плащ"/></div>
			</form>
			<?}}else{?><h2 style="text-align: center;">Добро пожаловать!</h2><h4 style="text-align: center;">Уважаемый игрок, введите логин и пароль от форума чтобы авторизоваться в личном кабинете.<br/>После авторизации вы сможете сменить скин или плащ, моментально получить VIP/GoldVIP на любом сервере или купить игровой валюты.</h3><hr/>
			<h2 style="text-align: center;">Информация об услугах</h2>Все покупки в панели управления совершаются по внутренней валюте, которая носит название "рубли".<br/>Пополнить свой счёт вы можете после авторизации в кабинете.<br/>Список того, что можно купить за внутреннюю валюту: <ul><li>VIP/GoldVIP статус на любой сервер</li><li>Обменять рубли на игровую валюту серверов</li><li>Поставить себе уникальный префикс перед ником на любой сервер</li><li>Сменить цвет вашего ника</li><li>Купить варп в игре</li></ul><?}?>
    		</div>
		<div id="footer">&copy; <a href="http://elysium-game.ru" style="color:white">Elysium-Game.ru</a> 2012</div>
   		</div> 
 	</body>
</html>
