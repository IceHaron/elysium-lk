<?php
error_reporting(0);
class Skin2d {
    private $image = NULL;
 
    function __destructor () {
        if ($this->image != NULL) {
            imagedestroy($this->image);
        }
    }

    function AssignSkinFromFile ($file) {
        if ($this->image != NULL) {
            imagedestroy($this->image);
        }
        if(($this->image = imagecreatefrompng($file)) == False) {
            // Error occured
            throw new Exception("Could not open PNG file.");
        }
        //if(!$this->Valid()) {
        //    throw new Exception("Invalid skin image.");
        //}
    }

    function AssignSkinFromString ($data) {
        if ($this->image != NULL) {
            imagedestroy($this->image);
        }
        if(($this->image = imagecreatefromstring($data)) == False) {

            throw new Exception("Could not load image data from string.");
        }
        if(!$this->Valid()) {
            throw new Exception("Invalid skin image.");
        }
    }

    function Width () {
        if($this->image != NULL) {
            return imagesx($this->image);
        } else {
            throw new Exception("No skin loaded.");
        }
    }

    function Height () {
        if($this->image != NULL) {
            return imagesy($this->image);
        } else {
            throw new Exception("No skin loaded.");
        }
    }
 
    function Valid () {
        return ($this->Width() != 64 || $this->Height() != 32) ? False : True;
    }
 
    function FrontImage ($scale = 1, $r = 255, $g = 255, $b = 255) {
        $newWidth = 16 * $scale;
        $newHeight = 32 * $scale;
 
        $newImage = imagecreatetruecolor(16, 32);
        $background = imagecolorallocate($newImage, $r, $g, $b);
        imagefilledrectangle($newImage, 0, 0, 16, 32, $background);
 
        imagecopy($newImage, $this->image, 4, 0, 8, 8, 8, 8);
         $this->imagecopyalpha($newImage, $this->image, 4, 0, 40, 8, 8, 8, imagecolorat($this->image, 63, 0));
        imagecopy($newImage, $this->image, 4, 8, 20, 20, 8, 12);
        imagecopy($newImage, $this->image, 8, 20, 4, 20, 4, 12);
        imagecopy($newImage, $this->image, 4, 20, 4, 20, 4, 12);
        imagecopy($newImage, $this->image, 12, 8, 44, 20, 4, 12);
        imagecopy($newImage, $this->image, 0, 8, 44, 20, 4, 12);
 
        if($scale != 1) {
            $resize = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($resize, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, 16, 32);
            imagedestroy($newImage);
            return $resize;
        }
 
        return $newImage;
    }
 
    function BackImage ($scale = 1, $r = 238, $g = 238, $b = 238) {
        $newWidth = 16 * $scale;
        $newHeight = 32 * $scale;
 
        $newImage = imagecreatetruecolor(16, 32);
        $background = imagecolorallocate($newImage, $r, $g, $b);
        imagefilledrectangle($newImage, 0, 0, 16, 32, $background);
 
        imagecopy($newImage, $this->image, 4, 0, 24, 8, 8, 8);
        $this->imagecopyalpha($newImage, $this->image, 4, 0, 56, 8, 8, 8, imagecolorat($this->image, 63, 0));
        imagecopy($newImage, $this->image, 4, 8, 32, 20, 8, 12);
        imagecopy($newImage, $this->image, 8, 20, 12, 20, 4, 12);
        imagecopy($newImage, $this->image, 4, 20, 12, 20, 4, 12);
        imagecopy($newImage, $this->image, 12, 8, 52, 20, 4, 12);
        imagecopy($newImage, $this->image, 0, 8, 52, 20, 4, 12);
 
        if($scale != 1) {
            $resize = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($resize, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, 16, 32);
            imagedestroy($newImage);
            return $resize;
        }
 
        return $newImage;
    }
	
	function IAmCloak($scale = 1, $r = 238, $g = 238, $b = 238)
	{
		$newWidth = 25 * $scale;
		$newHeight = 17 * $scale;
		
		$newImage = imagecreatetruecolor(25, 17);
        $background = imagecolorallocate($newImage, $r, $g, $b);
        imagefilledrectangle($newImage, 0, 0, 28, 17, $background);
		$col=imagecolorallocatealpha($newImage,255,255,255,127);
        imagefilledrectangle($newImage,0,0,37,36,$col);
		imagealphablending($newImage,true);
		
		imagecopy($newImage, $this->image, 0,0,1,1,10,16);
		imagecopy($newImage, $this->image, 15,0,12,1,10,16);
		
        if($scale != 1) {
            $resize = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($resize, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, 25, 17);
            imagedestroy($newImage);
            return $resize;
        }
 
        return $newImage;
	}
	
    function CombinedImage ($scale = 1, $r = 238, $g = 238, $b = 238) {
        $newWidth = 37 * $scale;
        $newHeight = 36 * $scale;
 $f = file('ears.txt');
 foreach($f as $pl) if($_GET['skinpath'] == trim($pl)) $ears = true;
        $newImage = imagecreatetruecolor(37, 36);
        $background = imagecolorallocate($newImage, $r, $g, $b);
        imagefilledrectangle($newImage, 0, 0, 36, 37, $background);
  $col=imagecolorallocatealpha($newImage,255,255,255,127);

 imagefilledrectangle($newImage,0,0,37,36,$col);
 imagealphablending($newImage,true);
        imagecopy($newImage, $this->image, 4, 4, 8, 8, 8, 8);
		if($ears){
		imagecopy($newImage, $this->image, 0, 0, 25, 1, 6, 6); //ear_front_l
		imagecopy($newImage, $this->image, 10, 0, 25, 1, 6, 6); //ear_front_r
		}
        $this->imagecopyalpha($newImage, $this->image, 4, 4, 40, 8, 8, 8, imagecolorat($this->image, 63, 0));
        imagecopy($newImage, $this->image, 4, 12, 20, 20, 8, 12);
        imagecopy($newImage, $this->image, 8, 24, 4, 20, 4, 12);
        imagecopy($newImage, $this->image, 4, 24, 4, 20, 4, 12);
        imagecopy($newImage, $this->image, 12, 12, 44, 20, 4, 12);
        imagecopy($newImage, $this->image, 0, 12, 44, 20, 4, 12);
 
        imagecopy($newImage, $this->image, 25, 4, 24, 8, 8, 8);
		if($ears){
		imagecopy($newImage, $this->image, 21, 0, 32, 1, 6, 6); //ear_back_l
		imagecopy($newImage, $this->image, 31, 0, 32, 1, 6, 6); //ear_back_r
		}
        $this->imagecopyalpha($newImage, $this->image, 25, 5, 56, 8, 8, 8, imagecolorat($this->image, 63, 0));
        imagecopy($newImage, $this->image, 25, 12, 32, 20, 8, 12);
        imagecopy($newImage, $this->image, 29, 24, 12, 20, 4, 12);
        imagecopy($newImage, $this->image, 25, 24, 12, 20, 4, 12);
        imagecopy($newImage, $this->image, 33, 12, 52, 20, 4, 12);
        imagecopy($newImage, $this->image, 21, 12, 52, 20, 4, 12);
 
        if($scale != 1) {
            $resize = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($resize, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, 37, 36);
            imagedestroy($newImage);
            return $resize;
        }
 
        return $newImage;
    }

    function imagecopyalpha($dst, $src, $dst_x, $dst_y, $src_x, $src_y, $w, $h, $bg) {
        for($i = 0; $i < $w; $i++) {
            for($j = 0; $j < $h; $j++) {
 
                $rgb = imagecolorat($src, $src_x + $i, $src_y + $j);
 
                if(($rgb & 0xFFFFFF) == ($bg & 0xFFFFFF)) {
                    $alpha = 127;
                } else {
                    $colors = imagecolorsforindex($src, $rgb);
                    $alpha = $colors["alpha"];
                }
                imagecopymerge($dst, $src, $dst_x + $i, $dst_y + $j, $src_x + $i, $src_y + $j, 1, 1, 100 - (($alpha / 127) * 100));
            }
        }
    }
}
?><?php
$path = $_GET[skinpath];
$cloak = $_GET['c'];
$test = new Skin2d();
if($cloak)
$test->AssignSkinFromFile('upload/cloaks/'.$path.'.png');
else
$test->AssignSkinFromFile('upload/skins/'.$path.'.png');
 
Header("Content-Type: image/png");
if($cloak)
{
$img = $test->IAmCloak(10);
}
else
$img = $test->CombinedImage(5);

imagepng($img);
imagedestroy($img);
?>