<script type="text/javascript">function subsub(){document.form1.submit();}window.onload = subsub;</script>
<?
$cost = intval($_GET['cost']);
if($cost<=0) $cost = 50;
$data = $_GET['n'];
$mrh_login = "Scorpi";
$mrh_pass1 = "analzoo1337";
$inv_desc = 'Зачисление  '.$cost.' рублей на счёт #'.$data.' Elysium Game';
$crc  = md5("$mrh_login:$cost:$inv_id:$mrh_pass1:shpnick=$data");
print
   "<html>".
   "<form action='https://merchant.roboxchange.com/Index.aspx' method=POST name=form1>".
   "<input type=hidden name=MrchLogin value=$mrh_login>".
   "<input type=hidden name=OutSum value=$cost>".
   "<input type=hidden name=Desc value='$inv_desc'>".
   "<input type=hidden name=SignatureValue value=$crc>".
   "<input type=hidden name=shpnick value='$data'>".
   "<input type=hidden name=Culture value=ru>".
   "</form></html>";
?>