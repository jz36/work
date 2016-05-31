<?php
	session_start();
	define('MAX_THUMB_SIZE', 70);
	error_reporting(~E_ALL);
	require 'config.inc.php';
	require 'lib/mysql.lib.php';
	if(isset($_SESSION['WA_USER']))
	{
		db_open();
		include 'header.inc.php';
		function small_image($fname, $newname, $max = MAX_THUMB_SIZE)
		{
			$size = getimagesize($fname);
			if($size === false)
				return false;
			if($size[0] > $size[1] && $size[0] > $max)
			{
				$width = $max;
				$height = $size[1] * $max / $size[0];
			}
			else if($size[1] > $max)
			{
				$width = $size[0] * $max / $size[1];
				$height = $max;
			}
			else
			{
				copy($fname, $newname);
				return true;
			}
			if(preg_match('/\.(jpg|jpeg|png|gif)$/ims', $fname, $vals))
				$ext = strtolower($vals[1]);
			else
				return false;
			switch($ext)
			{
				case 'jpg':
				case 'jpeg':
					$image = imagecreatefromjpeg($fname);
					break;
				case 'png':
					$image = imagecreatefrompng($fname);
					break;
				case 'gif':
					$image = imagecreatefromgif($fname);
					break;
			}
			if(!$image)
				return false;
			$thumb = imagecreatetruecolor($width, $height);
			imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			switch($ext)
			{
				case 'jpg':
				case 'jpeg':
					imagejpeg($thumb, $newname);
					break;
				case 'png':
					imagepng($thumb, $newname);
					break;
				case 'gif':
					imagegif($thumb, $newname);
					break;
			}
			imagedestroy($image);
			imagedestroy($thumb);
			chmod($newname, 644);
			return true;
		}
		function check_dir($dir)
		{
			if(($hdir = opendir($dir)) !== false)
			{
				while(($file = readdir($hdir)) !== false)
				{
					if($file != '.' && $file != '..')
					{
						if(is_dir($dir.$file))
						{
							check_dir($dir.$file.'/');
							rmdir($dir.$file);
						}
						else
						{
							if(preg_match('/^(.*)\.(jpg|png|gif)$/ims', $file, $vals))
							{
								$name = strtolower($vals[1]);
								$ext = strtolower($vals[2]);
								$cats = array();
								$items = array();
								if(($qr = mysql_query('SELECT `id` FROM `wed_shop` WHERE `label_id`="'.$name.'"')) !== false)
									while(($row = mysql_fetch_row($qr)) !== false)
										$cats[] = $row[0];
								if(($qr = mysql_query('SELECT `id` FROM `wed_shop_items` WHERE `label_id`="'.$name.'"')) !== false)
									while(($row = mysql_fetch_row($qr)) !== false)
										$items[] = $row[0];
								if(count($cats) + count($items))
								{
									copy($dir.$file, '../icon/big_'.$name.'.'.$ext);
									small_image($dir.$file, '../icon/small_'.$name.'.'.$ext);
									if(count($cats))
										mysql_query('UPDATE `wed_shop` SET `img_small`="'.$ext.'", `img_big`="'.$ext.'" WHERE `id` IN ('.implode(', ', $cats).')');
									if(count($items))
										mysql_query('UPDATE `wed_shop_items` SET `img_small`="'.$ext.'", `img_big`="'.$ext.'" WHERE `id` IN ('.implode(', ', $items).')');
									print "изображение {$name}.{$ext} было связано с ".count($cats)." категориями и ".count($items)." позициями\r\n";
								}
							}
							unlink($dir.$file);
						}
					}
				}
				closedir($hdir);
			}
		}
		if(isset($_FILES['file']['tmp_name']) && file_exists($_FILES['file']['tmp_name']))
		{
			mkdir('../tmp_dir');
			move_uploaded_file($_FILES['file']['tmp_name'], '../tmp_dir/package.zip');
			print '<pre>';
			$folder = dirname(dirname(__FILE__));
			if(system('unzip '.$folder.'/tmp_dir/package.zip -d '.$folder.'/tmp_dir/'))
				check_dir($folder.'/tmp_dir/');
			print '</pre>';
			rmdir('../tmp_dir');
		}
		else
		{
?>
	<form action="img_upload.php" enctype="multipart/form-data" method="post">
		<table border="0" cellpadding="0" cellspacing="10">
			<tr>
				<td>
					<input type="file" name="file" />
				</td>
			</tr>
			<tr align="right">
				<td>
					<input type="submit" value="Загрузить" style="width: 100px;" />
				</td>
			</tr>
		</table>
	</form>
<?php
		}
		include 'footer.inc.php';
	}
	else
		require 'index.php';
?>