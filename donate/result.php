<?
include('goods.php');
include('pex.php');
if($_POST['LMI_PREREQUEST']==1) {
if(trim($_POST['LMI_PAYEE_PURSE']) != 'R415962984729') die('Неизвестная постигла ошибка тебя, падаван');
if($good[$_POST['goodid']]['cost'] != $_POST['LMI_PAYMENT_AMOUNT']) die('Обмана способ этот как мир стар');
if(!trim($_POST['nick'])) die('Игрока ник указать забыл ты');
echo 'YES';
}
else
{
if(!$_POST['goodid']) die('IWANNASEXWITHBIEBER');
 $secret_key="analzoo13372";
  // Склеиваем строку параметров
  $common_string = $_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].
     $_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].
     $_POST['LMI_SYS_TRANS_DATE'].$secret_key.$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
  // Шифруем полученную строку в MD5 и переводим ее в верхний регистр
  $hash = strtoupper(md5($common_string));
  // Прерываем работу скрипта, если контрольные суммы не совпадают
  if($hash!=$_POST['LMI_HASH']) die('Hey u, stop hacking!');
$bought = $good[$_POST['goodid']];
$info = explode(':', $bought['type']);
if($info[0] == 'group') { if(addGroup($_POST['nick'], $bought['serverid'], $info[1])) echo 'OH SHI~ GOOD!'; else echo 'blyat'; } else echo 'blyat x2';

}
?>