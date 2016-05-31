<?
session_start();

$im=imagecreatefrompng ("/web/britannixru/site/www/modules/captcha/imagekey.png");
$color=imagecolorallocate($im, 0x52, 0x02, 0x01);
for ($i=0;$i<4;$i++) {
	$rand[$i]=mt_rand(0,9);
}
$x=5;
for ($i=0;$i<count($rand);$i++) {
	$y=mt_rand(15,18);
	$arc=mt_rand(-10,10);
	imageTTFText($im, 11, $arc, $x, $y, $color, "/web/britannixru/site/www/modules/captcha/betinas1.ttf", $rand[$i]);
	$x=$x+17;
	$str=$str.$rand[$i];
}
	
Header("Content-type: image/png");
imagePng($im);
imageDestroy($im);
	
$_SESSION['captcha'] = md5(md5($str));
?>