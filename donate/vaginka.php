<?header('Content-Type: text/html; charset=utf-8');?>
<?php
include('goods.php');
$buying = $good[$_SERVER['QUERY_STRING']];
if($buying['cost']){
$info = explode(':', $buying['type']);
?>
<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp">
<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<? echo $buying['cost']; ?>">
<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="<?php echo base64_encode('Оплата '.$buying['name'].' на Elysium-'.$buying['serverid']);?>">
<input type="hidden" name="LMI_PAYEE_PURSE" value="R415962984729">
<input type="hidden" name="goodid" value="<?echo $_SERVER['QUERY_STRING'];?>">
<?if($info[0] == 'group'){?>
Услуга: установка группы <?echo $buying['name'];?><br/>
Cервер: #<?echo $buying['serverid'];?><br/>
Период действия: месяц<br/><br/>
Введите никнейм: <input type="text" name="nick" size="15"><br/>
<input type="submit" value="Перейти к оплате">
</form>
<?}?>
<?
}else {?>
LOL
<?}?>