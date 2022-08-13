<?php

function gradient($w, $h , $c, $hex) {

    /*
    Generates a gradient image

    Parameters:
    w: width in px
    h: height in px
    c: color-array with 4 elements:
        $c[0]:   top left color
        $c[1]:   top right color
        $c[2]:   bottom left color
        $c[3]:   bottom right color

    if $hex is true (default), colors are hex-strings like '#FFFFFF' (NOT '#FFF')
    if $hex is false, a color is an array of 3 elements which are the rgb-values, e.g.:
    $c[0]=array(0,255,255);

    */

    $im=imagecreatetruecolor($w,$h);

    if($hex) {  // convert hex-values to rgb
        for($i=0;$i<=3;$i++) {
            $c[$i]=hex2rgb($c[$i]);
        }
    }

    $rgb=$c[0]; // start with top left color
    for($x=0;$x<=$w;$x++) { // loop columns
        for($y=0;$y<=$h;$y++) { // loop rows
            // set pixel color 
            $col=imagecolorallocate($im,$rgb[0],$rgb[1],$rgb[2]);
            imagesetpixel($im,$x-1,$y-1,$col);
            // calculate new color  
            for($i=0;$i<=2;$i++) {
                $rgb[$i]=
                    $c[0][$i]*(($w-$x)*($h-$y)/($w*$h)) +
                    $c[1][$i]*($x     *($h-$y)/($w*$h)) +
                    $c[2][$i]*(($w-$x)*$y     /($w*$h)) +
                    $c[3][$i]*($x     *$y     /($w*$h));
            }
        }
    }
    return $im;    
}

function hex2rgb($hex)
{
    $rgb[0]=hexdec(substr($hex,1,2));
    $rgb[1]=hexdec(substr($hex,3,2));
    $rgb[2]=hexdec(substr($hex,5,2));
    return($rgb);
}

// usage example
$image = gradient(1200, 630, array('#667eea', '#764ba2', '#667eea', '#764ba2'), true);

$textcolor = imagecolorallocate($image, 255, 255, 255);  

if (!empty($_GET["text"])){
    $text = $_GET["text"];
}
else{
    $text = "Welcome";
}

if (!empty($_GET["site"])){
    $site = $_GET["site"];
}
else{
    $site = "Free Og Generator";
}

$text = $text." "."|" ." ".$site;

$white = imagecolorallocate($image, 255, 255, 255);
$grey = imagecolorallocate($image, 128, 128, 128);
$black = imagecolorallocate($image, 0, 0, 0);

$tb = imagettfbbox(30, 0, 'arial.ttf', $text);

imagettftext($image, 30, 0, 20, 302, $grey, 'arial.ttf', $text);

imagettftext($image, 30 , 0, 20, 300, $textcolor, 'arial.ttf', $text);

$filename = $text.".png";

header('Content-type: image/png');

//serve as png file
//header('Content-Disposition: attachment; filename="'.$filename.'"');

//serve as png image
imagepng($image);
imagedestroy($image);

?>