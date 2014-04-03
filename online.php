<?
if(!$_GET['q']) die('bieber');
$q = base64_decode($_GET['q']);
function generate($length = 8){
  $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
  $numChars = strlen($chars);
  $string = '';
  for ($i = 0; $i < $length; $i++) {
    $string .= substr($chars, rand(1, $numChars) - 1, 1);
  }
  return $string;
}
$vag = generate(12);
$shit = file('online.txt');
$ip = $_SERVER['REMOTE_ADDR'];
$f = fopen('online.txt', 'a+');
fputs($f, $q.'|||'.$ip."\n");
fclose($f);
echo sizeof($shit);
?>
