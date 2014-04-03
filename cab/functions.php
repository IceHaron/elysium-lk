<?php

if(!defined('INCLUDE_CHECK')) die('У вас не прав запускать файл на выполнение');

function handleSkin($username)  
    {  
	 if ($_FILES['skin']['error'] === UPLOAD_ERR_OK)  
        {  
            // проверяем расширение файла  
			if (($_FILES['skin']['type'] == "image/png")  || ($_FILES['skin']['type'] == "image/x-png"))
            {  
                // проверяем размер файла  
                if ($_FILES['skin']['size'] < 8*1024) 
                {  
					// проверяем разрешение файла
					$ImageSize = getimagesize($_FILES['skin']['tmp_name']); 
					if($ImageSize['0'] == 64 && $ImageSize['1'] == 32) {
						
						$destination = 'upload/skins/'.$username.'.png';  
      					if (move_uploaded_file($_FILES['skin']['tmp_name'], $destination))
							$info = 'Скин успешно загружен';
							 else  
							$error = 'Не удалось загрузить скин';
							
					}   
					else  
						$error = 'Разрешение скина неправильное';
                }   
                else  
                    $error = 'Размер скина больше допустимого';  
            }   
            else  
                $error = 'Разрешена загрузка скинов только в формате "png"';  
        }   
        else $error = 'Не удалось загрузить скин';
        return array('info' => $info, 'error' => $error);  
    }
	
function handleCloak($username)  
    {  
	 if ($_FILES['cloak']['error'] === UPLOAD_ERR_OK)  
        {  
            // проверяем расширение файла  
			if (($_FILES['cloak']['type'] == "image/png")  || ($_FILES['cloak']['type'] == "image/x-png"))
            {  
                // проверяем размер файла  
                if ($_FILES['cloak']['size'] < 7*1024) 
                {  
					// проверяем разрешение файла
					$ImageSize = getimagesize($_FILES['cloak']['tmp_name']); 
					if($ImageSize['0'] == 22 && $ImageSize['1'] == 17) {
						
						$destination = 'upload/cloaks/'.$username.'.png';  
      					if (move_uploaded_file($_FILES['cloak']['tmp_name'], $destination))
							$info = 'Плащ успешно загружен';
							 else  
							$error = 'Не удалось загрузить плащ';
							
					}   
					else  
						$error = 'Разрешение плаща неправильное';
                }   
                else  
                    $error = 'Размер плаща больше допустимого';  
            }   
            else  
                $error = 'Разрешена загрузка плащей только в формате "png"';  
        }   
        else $error = 'Не удалось загрузить плащ';
        return array('info' => $info, 'error' => $error);  
    }

function getGroup($user, $server)
{
$permissions = file_get_contents('/root/minecraft/'.$server.'/plugins/PermissionsEx/permissions.yml');
$yaml = syck_load($permissions);
$opts = $yaml["users"][$user]["options"];
if($opts["group-vip-until"]) return array('<span style="color:#BF00BF">VIP</span>', $opts["group-vip-until"]);
if($opts["group-gvip-until"]) return array('<span style="color:#BFBF00">GoldVIP</span>', $opts["group-gvip-until"]);
$groups = $yaml["users"][$user]["group"];
if($groups[0] == 'admin') return '<span style="color:#BF0000">Administartor</span> до <span style="color:#00BFBF; font-size: 200%">∞</span>';
if($groups[0] == 'moder') return '<span style="color:#00BF00">Moderator</span> до <span style="color:#00BFBF; font-size: 200%">∞</span>';
if($groups[0] == 'moderator') return '<span style="color:#00BF00">Moderator</span> до <span style="color:#00BFBF; font-size: 200%">∞</span>';
if($groups[0] == 'smoderator') return '<span style="color:#BF00BF">Major Moderator</span> до <span style="color:#00BFBF; font-size: 200%">∞</span>';
if($groups[0] == 'mmoder') return '<span style="color:#00BF00">Junior Moderator</span> до <span style="color:#00BFBF; font-size: 200%">∞</span>';
return false;
}

function writeLog($string, $wagin = 0)
{
if($wagin==1)
$f = fopen('log2.txt','a+');
else
$f = fopen('log.txt','a+');
fputs($f, date('[d.m.y H:i:s]')." $string\n");
fclose($f);
}
?>