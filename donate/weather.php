<?php
error_reporting('E_ALL ^ E_NOTICE');

require 'rcon.class.php';

$Rcon = new MinecraftRcon;
$Rcon->Connect('localhost', 9992, 'iloveBTyeah', 2 );
$Data = $Rcon->Command( 'weather sunny -1 world' );
if( $Data === false )$error = true;else if( StrLen( $Data ) == 0 )$error = true; //ошибки

$Rcon->Disconnect( );
?>