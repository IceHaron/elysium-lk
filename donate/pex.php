<?php
error_reporting('E_ALL ^ E_NOTICE');

require 'rcon.class.php';

function addGroup($user, $server, $group)
{
if($server == 1) { $port = 'x'; $pass='x'; }
if($server == 2) { $port = 9992; $pass = 'iloveBTyeah'; }
$Rcon = new MinecraftRcon;
$Rcon->Connect('localhost', $port, $pass, 2 );
$Data = $Rcon->Command( 'pex user '.$user.' group add '.$group.' "" 2592000' );
if( $Data === false )$error = true;else if( StrLen( $Data ) == 0 )$error = true; //ошибки

$Rcon->Disconnect( );

if(!$error){
if(substr_count($Data, 'User added to')) return true; else return false;
}
else return false;
}
?>