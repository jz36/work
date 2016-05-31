<?php
if (!defined("NX_PATH")) define ("NX_PATH", "../");
#require_once(NX_PATH.'lib/global.lib.php');
#require_once(NX_PATH.'config.inc.php');
#require_once(NX_PATH.'lib/mysql.lib.php');
@set_magic_quotes_runtime(0);
//validate_post_vars();



#db_open();


function resize_image_oneparam($filename, $new_filename, $maxwidth, $maxheight){
	//$new_filename = $filename;

	if ($imginfo = getimagesize($filename)){
		$loaded = true;
		switch ($imginfo[2]) {
			case 1:
			$im = imagecreatefromgif($filename);
			break;
			case 2:
			$im = imagecreatefromjpeg($filename);
			break;
			case 3:
			$im = imagecreatefrompng($filename);
			break;
			default:
			$loaded = false;
			break;
		}
		if ($loaded){
			$nwidth = $maxwidth;
			$nheight = $maxheight;
			$width = imagesx($im);
			$height = imagesy($im);
			$dx = 0;
			$dy = 0;
			$xw = $nwidth;
			$xh = $nheight;
			if ($maxheight && $maxwidth){
				if ($width > $height){
					// dx
					$xh = ($height / $width) * $nwidth;
					//$dy = ($nheight - $xh) / 2;
				}else{
					// dy
					$xw = ($width / $height) * $nheight;
					//$dx = ($nwidth - $xw) / 2;
				}
			}elseif($maxheight){
				$xw = ($width / $height) * $nheight;
			}elseif($maxwidth){
				$xh = ($height / $width) * $nwidth;
			}else{
				return ; // ^_^ 0px - COOL !! %)
			}
			if (($xw < $width) or ($xh < $height)){
				$nim = imagecreatetruecolor($xw, $xh);
				imagecopyresampled($nim, $im, 0, 0, 0, 0, $xw, $xh, $width, $height);
			}else{
				$nim = $im;
			}
			switch ($imginfo[2]) {
				case 1:
				$im = imagegif($nim, $new_filename);
				break;
				case 2:
				$im = imagejpeg($nim, $new_filename);
				break;
				case 3:
				$im = imagepng($nim, $new_filename);
				break;
				default:
				break;
			}
		}
	}
}



#if (isset($_SESSION["WA_USER"])) {



	$c_url = '/news/customimages/';
	$c_path = NX_PATH.'news/customimages/';



	$year = date("Y");
	$id = 0;
	//include(NX_PATH.'wedadmin/header.inc.php');
	echo '<H3>ИЗОБРАЖЕНИЯ</H3>';

	if (isset($_GET["id"])){
		$id = intval($_GET["id"]);
		$c_url .= $id.'/';
		$c_path .= $id.'/';
		if (!is_dir($c_path)) {
			mkdir($c_path);
		}
		//echo '<H3>'.$id.'</H3>';

		if (isset($_GET["upload"])){
			if (isset($_FILES["picture"])){
				$filename = $_FILES["picture"]["name"];
				$tmpname =  $_FILES["picture"]["tmp_name"];
				if (is_uploaded_file($tmpname)){
					if (copy($tmpname, $c_path.$filename)){

					}
				}
			}
		}
		if (isset($_GET["delete"])){
			$filename = base64_decode($_GET["delete"]);
			if (is_file($c_path.$filename)){
				unlink($c_path.$filename);
			}
		}


		$page = 0;
		if (isset($_GET["page"])) $page = intval($_GET["page"]);

		#db_open();

		$count = 25;
		$from = $page * $count;
		$dir = opendir($c_path);
		rewinddir($dir);
		$files_count = 0;

		$directories = array();
		$files = array();
		while ($filename = readdir($dir)) {
			if (($filename !== '.') AND ($filename !== '..')){
				if (($files_count >= $from) && ($files_count < $from + $count)){
					if (is_file($c_path.$filename)){
						$files[] = $filename;
					}
				}
				if (is_dir($c_path.$filename)){
					$directories[] = $filename;
				}else{
					$files_count++;
				}
			}
		}

		$news_total = $files_count;
		$pages_count = ceil($news_total / $count);

		echo '<DIV>Страницы: ';
		$i = 0;
		while ($i < ($pages_count)){
			echo ' <A href="/wedadmin/news_images.php?id='.$id.'&page='.$i.'">';
			if ($i == $page){

			}
			echo ($i+1).'</A> ';
			$i++;
		}
		echo '</DIV>';
		echo '<BR/><BR/>';
		echo '<TABLE>';
		$r=0;
		if (isset($_POST['filename'])){
			$filename = base64_decode($_POST['filename']);
			$imginfo = getimagesize($c_path.$filename);
			if ($imginfo){
				if (isset($_POST['w']) && isset($_POST['h'])){
					$w = intval($_POST['w']);
					$h = intval($_POST['h']);
					resize_image_oneparam($c_path.$filename, $c_path.$filename, $w, $h);
				}
			}
		}
		foreach ($files as $filename){
			$r++;
			if ($r>1) $r=0;
			echo '<TR class="r'.$r.'"><TD><IMG src="'.$c_url.$filename.'">';
			echo '<BR/>Ссылка:&nbsp;'.$c_url.$filename;
			$imginfo = getimagesize($c_path.$filename);
			if ($imginfo){
				echo '<form action="/wedadmin/news_images.php?id='.$id.'&resize" method="post">';
				echo '<input type="hidden" name="filename" value="'.base64_encode($filename).'" />';
				echo 'Новый размер: <input type="text" name="w" /> x <input type="text" name="h" />';
				echo '<br /><input type="submit" value="сохранить">';
				echo '</form>';
			}
			echo '</TD><TD><A href="/wedadmin/news_images.php?id='.$id.'&delete='.base64_encode($filename).'" onClick="javascript: if (confirm(\'Удалить?\')) { return true;} else { return false;}"><img src="/wedadmin/images/b_drop.png" border="0" /></A></TD></TR>';
		}
		echo '</TABLE>';
		echo '<BR/><FORM method="post" enctype="multipart/form-data" action="/wedadmin/news_images.php?id='.$id.'&upload"><INPUT type="file" name="picture"><BR/><BR/><INPUT type="submit" value="добавить"></FORM><BR/><BR/>';
	}



	//include(NX_PATH.'wedadmin/footer.inc.php');

?>