<?php
if (!defined("NX_PATH")) define ("NX_PATH", "../");

require_once(NX_PATH.'wedadmin/lib/global.lib.php');

require_once(NX_PATH.'wedadmin/config.inc.php');

require_once(NX_PATH.'wedadmin/lib/mysql.lib.php');



if (!defined("WA_PATH")) define("WA_PATH", "./");

if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");



@set_magic_quotes_runtime(0);

@session_start();

if (!isset($_SESSION['hide_ext'])){

	$_SESSION['hide_ext'] = false;

}

if (isset($_GET['hide'])){

	$_SESSION['hide_ext'] = true;

}

if (isset($_GET['show'])){

	$_SESSION['hide_ext'] = false;

}


//validate_post_vars();

function categories_options($cid = 0, $level = 0, &$cat_options, $selected = 0){

	if ($query = mysql_query("SELECT * FROM `wed_files` WHERE `parent_id` = '$cid'")){

		while ($cat = mysql_fetch_array($query)) {

			$cat_option = '<option value="'.$cat["id"].'"';

			if ($cat['id'] == $selected) $cat_option .= ' selected';

			$cat_option .= '>'.str_repeat('- ', $level).$cat["title"].'</option>';

			$cat_options[] = $cat_option;

			categories_options($cat["id"], $level+1, $cat_options, $selected);

		}

	}

	return implode("\n", $cat_options);

}



function resize_image($filename, $new_filename, $maxwidth, $maxheight){

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

			if ($width > $height){

				// dx

				$xh = ($height / $width) * $nwidth;

				//$dy = ($nheight - $xh) / 2;

			}else{

				// dy

				$xw = ($width / $height) * $nheight;

				//$dx = ($nwidth - $xw) / 2;

			}

			$nim = imagecreatetruecolor($xw, $xh);

			imagecopyresampled($nim, $im, 0, 0, 0, 0, $xw, $xh, $width, $height);

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



function file_extension($filename){

	$parts = explode('.', $filename);

	if (count($parts)) return $parts[count($parts) - 1];

	return '';

}

db_open();



if (!isset($_SESSION["WA_USER"])) { require ("index.php"); die(); }



$cid = 0;

if (isset($_GET['cid'])) $cid = intval($_GET['cid']);



$id = 0;

if (isset($_GET["id"])) $id = intval($_GET["id"]);









if ($id && isset($_GET['updatei'])){

	if ($query = mysql_query("SELECT * FROM `wed_files_items` WHERE `id` = '$id'")){

		if ($item = mysql_fetch_array($query)){

			if ($cquery = mysql_query("SELECT * FROM `wed_files` WHERE `id` = '".$item['category_id']."'")){

				if ($cat = mysql_fetch_array($cquery)){

					$date_a = explode('/', $_POST["date"]);

					$time_a = explode(":", $_POST["time"]);

					if (count($date_a)==3 && count($time_a)==2){

						$hour = intval($time_a[0]);

						$min = intval($time_a[1]);

						if (checkdate($date_a[1],$date_a[0],$date_a[2]) && $hour >= 0 && $hour <= 23 && $min >= 0 && $min <= 59){

							$date = mktime($hour, $min,0,$date_a[1], $date_a[0], $date_a[2]);

							$c_id = intval($_POST["category"]);

							$title = addslashes($_POST["title"]);

							$file_text = addslashes($_POST["filename"]);

							$short_text = addslashes($_POST["short_text"]);

							$order=intval(addslashes($_POST["order"]));

							$full_text = addslashes($_POST["full_text"]);

							$file_uploaded = FALSE;

							$wysiwyg = 0;

							$icons_size = ($cat['icons_size']?$cat['icons_size']:120);

							if (isset($_POST['wysiwyg']) && ($_POST['wysiwyg'] == 1)) $wysiwyg = 1;

							if (isset($_FILES["file"])){

								$filename = $_FILES["file"]["name"];

								$tmpname =  $_FILES["file"]["tmp_name"];

								$exts = explode('.', $filename);

								$new_filename = $filename;

								if (is_uploaded_file($tmpname)){

									if (copy($tmpname, NX_PATH.'files/'.$new_filename)){

										$file_uploaded = TRUE;

									}

								}

							}

							if ($file_uploaded){

								mysql_query("UPDATE `wed_files_items` SET `filename` = '$new_filename' WHERE `id` = '$id'");

							} else {

								mysql_query("UPDATE `wed_files_items` SET `filename` = '$file_text' WHERE `id` = '$id'");

							}
							mysql_query("UPDATE `wed_files_items` SET `producer_id` = '$producer_id', `category_id` = '$c_id', `date` = '$date', `title` = '$title', `short_text` = '$short_text', `full_text` = '$full_text', `order`='$order' ,`wysiwyg` = '$wysiwyg' WHERE `id` = '$id'");

						}

					}

				}

			}

		}

	}

}





$xihna_enabled = true;

if ($id){

	if ($item = mysql_fetch_array(mysql_query("SELECT * FROM `wed_files_items` WHERE `id` = '$id'"))){

		if (!$item['wysiwyg']) $xihna_enabled = false;

	}

}

require_once(NX_PATH.'wedadmin/header.inc.php');

$cid = 0;

if (isset($_GET['cid'])) $cid = intval($_GET['cid']);



$id = 0;

if (isset($_GET["id"])) $id = intval($_GET["id"]);



if ($cid && isset($_GET['update'])){

	if ($query = mysql_query("SELECT * FROM `wed_files` WHERE `id` = '$cid'")){

		if ($cat = mysql_fetch_array($query)) {

			$title = addslashes($_POST['title']);

			$hide = 0;

			if (isset($_POST['hide']) && ($_POST['hide'] == 1)) $hide = 1;

			$description = addslashes($_POST['description']);

			$order = intval(addslashes($_POST['catorder']));	

			$icons_size = intval($_POST['size']);

			mysql_query("UPDATE `wed_files` SET `title` = '$title', `description` = '$description', `hide` = '$hide', `icons_size` = '$icons_size', `order`='$order' WHERE `id` = '$cid'");

			if (isset($_FILES['icon'])){

				$file = $_FILES['icon'];

				$tmp = $file['tmp_name'];

				$new = 'f_'.$cid.'.'.file_extension($file['name']);

				if (copy($tmp, NX_PATH.'files/icons/'.$new)){

					resize_image(NX_PATH.'files/icons/'.$new, NX_PATH.'files/icons/'.$new, 50, 50);

					mysql_query("UPDATE `wed_files` SET `icon` = '".addslashes($new)."' WHERE `id` = '$cid'");

				}

			}

		}

	}

}



if (isset($_GET["deletei"])){

	$item_id = intval($_GET['deletei']);

	if ($query = mysql_query("SELECT * FROM `wed_files_items` WHERE `id` = '$item_id'")){

		if ($item = mysql_fetch_array($query)){

			$cid = $_GET['cid'] = $item['category_id'];

			mysql_query("DELETE FROM `wed_files_items` WHERE `id` = '$item_id'");

		}

	}

}

if (isset($_GET["deletec"])){

	$item_id = intval($_GET['deletec']);

	if ($query = mysql_query("SELECT * FROM `wed_files` WHERE `id` = '$item_id'")){

		if ($item = mysql_fetch_array($query)){

			$cid = $_GET['cid'] = $item['parent_id'];

			mysql_query("DELETE FROM `wed_files` WHERE `id` = '$item_id'");

		}

	}

}



// BREADCRUMB

$parent_cid = $cid;

$finish_with_item = false;

if ($id){

	if ($query = mysql_query("SELECT * FROM `wed_files_items` WHERE `id` = '$id'")){

		if ($item = mysql_fetch_array($query)){

			$cid = $item['category_id'];

			$parent_cid = $cid;

			$finish_with_item = true;

		}

	}

}

function parent_navbits($cid, $showfirst = false){

	$html = '';

	if ($cid !== 0){

		if ($query = mysql_query("SELECT * FROM `wed_files` WHERE `id` = '$cid'")){

			if ($cat = mysql_fetch_array($query)){

				$html = parent_navbits($cat['parent_id'], true);

				if ($showfirst) $html .= ' &gt; <a href="/wedadmin/files.php?cid='.$cat['id'].'">'.

				$cat['title'].'</a> ';

			}

		}

	}

	return $html;

}





echo '<div>';

if ($_SESSION['hide_ext']){

	echo '<a href="/wedadmin/files.php?cid='.$cid.'&show">показать</a>';

}else{

	echo '<a href="/wedadmin/files.php?cid='.$cid.'&hide">скрыть</a>';

}

echo '</div><br />';



echo '<a href="/wedadmin/files.php"><img src="images/navbits_start.gif" border="0" /></a>';

echo '&nbsp; <a href="/wedadmin/files.php">Файловый архив</a>';

echo parent_navbits($parent_cid, true);

echo '<hr size="1"/>';

/// EDIT CAT

if ($cid){

	if ($query = mysql_query("SELECT * FROM `wed_files` WHERE `id` = '$cid'")){

		if ($cat = mysql_fetch_array($query)) {

			if (isset($_GET["deleteimg"])){

				mysql_query("UPDATE `wed_files` SET `icon` = '' WHERE `id` = '$cid'");

			}

			echo '<form action="/wedadmin/files.php?cid='.$cid.'&update" method="post" enctype="multipart/form-data">';

			echo '<table width="100%"><tr><td width="70">';

			if ($cat['icon'] !== ''){

				echo '<img src="/files/icons/'.$cat['icon'].'" />';

				echo '<br /><a href="/wedadmin/files.php?cid='.$cid.'&deleteimg" onClick="javascript: if (confirm(\'Удалить?\')) { return true;} else { return false;}">удалить иконку</a>';

			}else{

				echo '<img src="images/spacer.gif" alt="нет иконки" width="50" height="50" />';

			}

			echo '</td><td>';

			echo '<div><input type="text" name="title" style="width:90%;" value="'.htmlspecialchars($cat['title']).'" /></a></div><BR />';

			echo'<div>Описание </div>';	

			echo '<div><textarea name="description" style="width:90%;">'.$cat['description'].'</textarea></div><br />';

			echo"<span>Порядок категории&nbsp;</span><input type=\"text\" name=\"catorder\" value=\"$cat[order]\"> &nbsp;&nbsp;";	

			echo '<input type="submit" value="сохранить"></form>';

			echo '</td></tr></table>';

			echo '<hr size="1"/>';

		}

	}

}

if ($id){

	if ($item = mysql_fetch_array(mysql_query("SELECT * FROM `wed_files_items` WHERE `id` = '$id'"))){

		echo '<H1>Файл:</H1><br />';

		echo '<FORM method="post" enctype="multipart/form-data" action="/wedadmin/files.php?id='.$item["id"].'&updatei">';

		echo 'Дата: <INPUT type="text" name="date" value="'.date('d/m/Y',$item["date"]).'"> ';

		echo 'Время: <INPUT type="text" name="time" value="'.date('H:i',$item["date"]).'"><BR><BR>';

		echo 'Категория: <SELECT name="category" style="width: 500px;">';

		echo categories_options(0,0,$cat_options = array(),$item['category_id']);

		echo '</SELECT>';

		echo '<BR/><BR/>';

		echo 'Заголовок:<BR/><INPUT type="text" name="title" value="'.htmlspecialchars($item["title"]).'" class="w100"><BR/><BR/>';

		echo 'Загрузить файл (Максимальный размер: '.ini_get("upload_max_filesize").'):<BR/><INPUT type="file" name="file" class="w100"><BR/><BR/>';

		echo 'Или напишите имя файла на сервере (В директории http://'.$_SERVER['SERVER_NAME'].'/files):<BR/><INPUT type="text" name="filename" value="'.htmlspecialchars($item["filename"]).'" class="w100"><BR/><BR/>';

		echo 'Краткое описание файла:<BR/><TEXTAREA class="w90" id="short_text" name="short_text" rows=5 cols=50>'.$item["short_text"].'</TEXTAREA><BR/><BR/>';

echo"<span>Порядок  файла&nbsp;</span><input type=\"text\" name=\"order\" value=\"$item[order]\"><BR /><BR />";

		echo 'Полное описание:<BR/><TEXTAREA class="w90" id="full_text" name="full_text" rows=30 cols=80>';

		function remove_tag($tagname, $html){

			return preg_replace("#</?".$tagname."[^>]*>#ims", "", $html);

		}

		function remove_param($param, $html){

			return preg_replace("#<([a-z]+) ([^>]* )?(".$param."=([\S]+|\"[^>]+\"))( [^>]*)?>#ims", "<\\1 \\2 \\5>", $html);

		}

		function nx_notepad($html){

			return remove_param('style', remove_tag('span', remove_tag('font', $html)));

		}

		function leave_bbcode($html){

			return preg_replace("#\[(/?[^\]]*)\]#ims", "<\\1>", preg_replace("#</?[^>]*>#ims", "", preg_replace("#<(/?(b|i|a|p|img)(| [^>]*))>#ims", "[\\1]", $html)));

		}

		function nx_msword($html){

			return remove_param('style', leave_bbcode($html));

		}

		if (isset($_GET['notepad'])){

			echo nx_notepad($item['full_text']);

		}elseif (isset($_GET['msword'])){

			echo nx_msword($item['full_text']);

		}else{

			echo $item["full_text"];

		}

		echo '</TEXTAREA><BR/><BR/>';



		echo '<input type="checkbox" name="wysiwyg" value="1"';

		if ($item['wysiwyg']) echo ' checked="checked"';

		echo ' /> визуальный редактор (вкл/выкл)';

		echo '<br /><br />';

		echo '<a href="/wedadmin/files.php?id='.$id.'&notepad">убрать форматирование</a> (не забудьте сохранить)';

		echo '<br /><br />';

		echo '<a href="/wedadmin/files.php?id='.$id.'&msword">максимально убрать форматирование</a> (специально для MS Word... не забудьте сохранить)';

		echo '<br /><br />';

		echo '<a href="#" onclick="open(\'/wedadmin/files_images.php?id='.$item['id'].'\', \'popUpWin\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=600,height=700,left=0, top=0,screenX=0,screenY=0\');">загрузить изображения</a>';

		echo '<br /><br />';

		echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';



		echo '</FORM>';

		//

		//                <script type="text/javascript" defer="1">

		//

		//

		//                var config = new HTMLArea.Config();

		//                config.height = '600px';

		//                config.width = '700px';

		//                HTMLArea.replace('full_text', config);

		//                </script>

		//

		include(NX_PATH.'wedadmin/footer.inc.php');

		die();

	}

}

function nx_mysql_result($sql){

	return mysql_result(mysql_query($sql), 0);

}

//-------------------------------------------------------------------

function table_down_id($table, $item_id, $parent_field = 'parent_id', $step = 1){

	if ($query = mysql_query("SELECT * FROM `$table` WHERE `id` = '$item_id'")){

		if ($item = mysql_fetch_array($query)){

			$cid = $item['parent_id'];

			$order = $item['order'];

			//$block_id = $item['block_id'];

			$parent_id = $item[$parent_field];

			$from = $step - 1;

			if ($query2 = mysql_query("SELECT * FROM `$table` WHERE `order` > '$order' AND `$parent_field` = '$parent_id' ORDER BY `order` ASC LIMIT $from, 1")){

				if ($item2 = mysql_fetch_array($query2)){

					$id2 = $item2['id'];

					$order2 = $item2['order'];

					mysql_query("UPDATE `$table` SET `order` = '$order2' WHERE `id` = '$item_id'");

					mysql_query("UPDATE `$table` SET `order` = '$order' WHERE `id` = '$id2'");

					//header("Location: ".$location_prefix.$cid);

				}

			}

		}

	}

}

function table_up_id($table, $item_id, $parent_field = 'parent_id', $step = 1){

	if ($query = mysql_query("SELECT * FROM `$table` WHERE `id` = '$item_id'")){

		if ($item = mysql_fetch_array($query)){

			$cid = $item['parent_id'];

			$order = $item['order'];

			//$block_id = $item['block_id'];

			$parent_id = $item[$parent_field];

			$from = $step - 1;

			if ($query2 = mysql_query("SELECT * FROM `$table` WHERE `order` < '$order' AND `$parent_field` = '$parent_id' ORDER BY `order` DESC LIMIT $from, 1")){

				if ($item2 = mysql_fetch_array($query2)){

					$id2 = $item2['id'];

					$order2 = $item2['order'];

					mysql_query("UPDATE `$table` SET `order` = '$order2' WHERE `id` = '$item_id'");

					mysql_query("UPDATE `$table` SET `order` = '$order' WHERE `id` = '$id2'");

					//header("Location: ".$location_prefix.$cid);

				}

			}

		}

	}

}

if (isset($_GET['upc'])){

	$item_id = intval($_GET['upc']);

	table_up_id('wed_files', $item_id, 'parent_id');

}

if (isset($_GET['downc'])){

	$item_id = intval($_GET['downc']);

	table_down_id('wed_files', $item_id, 'parent_id');

}

if (isset($_GET['upcd'])){

	$item_id = intval($_GET['upcd']);

	for ($i = 0; $i < 10; $i++){

		table_up_id('wed_files', $item_id, 'parent_id', 1);

	}

}

if (isset($_GET['downcd'])){

	$item_id = intval($_GET['downcd']);

	for ($i = 0; $i < 10; $i++){

		table_down_id('wed_files', $item_id, 'parent_id', 1);

	}

}

if (isset($_GET['upi'])){

	$item_id = intval($_GET['upi']);

	table_up_id('wed_files_items', $item_id, 'category_id');

}

if (isset($_GET['downi'])){

	$item_id = intval($_GET['downi']);

	table_down_id('wed_files_items', $item_id, 'category_id');

}

if (isset($_GET['upid'])){

	$item_id = intval($_GET['upid']);

	for ($i = 0; $i < 10; $i++){

		table_up_id('wed_files_items', $item_id, 'category_id', 1);

	}

}

if (isset($_GET['downid'])){

	$item_id = intval($_GET['downid']);

	for ($i = 0; $i < 10; $i++){

		table_down_id('wed_files_items', $item_id, 'category_id', 1);

	}

}



// --------------------------------------------------- CATS ^_^ yeah....----

if ($query = mysql_query("SELECT * FROM `wed_files` WHERE `parent_id` = '$cid' ORDER BY `order` ASC")){

	if (mysql_num_rows($query)){

		echo '<table width="90%">';

		while ($cat = mysql_fetch_array($query)) {

			echo '<tr><td>';

			if (!$_SESSION['hide_ext']){

				if ($cat['icon'] !== ''){

					echo '<img src="/files/icons/'.$cat['icon'].'" />';

				}else{

					echo '<img src="images/spacer.gif" alt="нет иконки" width="50" height="50" />';

				}

			}

			echo '</td><td><div>';

			echo ' <a href="/wedadmin/files.php?cid='.$cid.'&upc='.$cat['id'].'" title="переместить вверх"><img src="/wedadmin/images/upbl.gif" border="0" width="11" height="11" alt="" /></a>';

			echo ' <a href="/wedadmin/files.php?cid='.$cid.'&downc='.$cat['id'].'" title="переместить вниз"><img src="/wedadmin/images/downbl.gif" border="0" width="11" height="11" alt="" /></a>';

			echo ' <a href="/wedadmin/files.php?cid='.$cid.'&upcd='.$cat['id'].'" title="переместить вверх"><img src="/wedadmin/images/upbld.png" border="0" width="11" height="11" alt="" /></a>';

			echo ' <a href="/wedadmin/files.php?cid='.$cid.'&downcd='.$cat['id'].'" title="переместить вниз"><img src="/wedadmin/images/downbld.png" border="0" width="11" height="11" alt="" /></a>';

			echo ' <a href="/wedadmin/files.php?cid='.$cat['id'].'">';

			if ($cat['hide']) echo '<font color="#888888">';

			echo $cat['title'];

			echo '</a>';

			if ($cat['hide']) echo '</font>';

			$subcount = nx_mysql_result("SELECT COUNT(*) FROM `wed_files` WHERE `parent_id` = '".$cat['id']."'") + nx_mysql_result("SELECT COUNT(*) FROM `wed_files_items` WHERE `category_id` = '".$cat['id']."'");

			if (!$subcount) echo ' &nbsp; <a href="/wedadmin/files.php?deletec='.$cat['id'].'" onClick="javascript: if (confirm(\'Удалить?\')) { return true;} else { return false;}"><img src="/wedadmin/images/deleteor.gif" border="0"></a>';

			echo '</div>';

			if (!$_SESSION['hide_ext'])	echo '<div>'.$cat['description'].'</div><br />';

			echo '</td></tr>';

		}

		echo '</table>';

	}

}





// ----------------------------------------------------------DOGS ^_^ AKA ITEMS---------------------

if ($query = mysql_query("SELECT * FROM `wed_files_items` WHERE `category_id` = '$cid' ORDER BY `order` ASC")){

	if (mysql_num_rows($query)){

		echo '<table width=90%>';

		while ($item = mysql_fetch_array($query)) {

			echo '<tr><td>';

			echo ' <a href="/wedadmin/files.php?cid='.$cid.'&upi='.$item['id'].'" title="переместить вверх"><img src="/wedadmin/images/upbl.gif" border="0" width="11" height="11" alt="" /></a>';

			echo ' <a href="/wedadmin/files.php?cid='.$cid.'&downi='.$item['id'].'" title="переместить вниз"><img src="/wedadmin/images/downbl.gif" border="0" width="11" height="11" alt="" /></a>';

			echo ' <a href="/wedadmin/files.php?cid='.$cid.'&upid='.$item['id'].'" title="переместить вверх"><img src="/wedadmin/images/upbld.png" border="0" width="11" height="11" alt="" /></a>';

			echo ' <a href="/wedadmin/files.php?cid='.$cid.'&downid='.$item['id'].'" title="переместить вниз"><img src="/wedadmin/images/downbld.png" border="0" width="11" height="11" alt="" /></a>';

			echo ' <a href="/wedadmin/files.php?deletei='.$item['id'].'" onClick="javascript: if (confirm(\'Удалить?\')) { return true;} else { return false;}"><img src="/wedadmin/images/deleteor.gif" border="0"></a>';

			echo '<br />';

			echo '<br />';

			echo '</td><td><div><a href="/wedadmin/files.php?id='.$item['id'].'">'.$item['title'].'</a></div>';

			if (!$_SESSION['hide_ext']) echo '<div>'.$item['short_text'].'</div><br />';

			echo '</td></tr>';

		}

		echo '</table>';

	}

}

function js_location($location){

	echo "\r\n".'<script type="text/javascript" language="javascript">'."\r\n";

	echo 'location = \''.$location.'\''."\r\n";

	echo '</script>'."\r\n";

}

//--------------------------------- ADDS

if (isset($_GET['addi']) && isset($_POST['aititle']) && (trim($_POST['aititle']) !== '')){

	$title = addslashes($_POST['aititle']);

	mysql_query("INSERT INTO `wed_files_items` SET `category_id` = '$cid', `title` = '$title', `date` = '".time()."'");

	if ($id = mysql_insert_id()){

		mysql_query("UPDATE `wed_files_items` SET `order` = '$id' WHERE `id` = '$id'");

		js_location("/wedadmin/files.php?id=".$id);

		die();

	}

}

if (isset($_GET['addc']) && isset($_POST['actitle']) && (trim($_POST['actitle']) !== '')){

	$title = addslashes($_POST['actitle']);

	mysql_query("INSERT INTO `wed_files` SET `parent_id` = '$cid', `title` = '$title'");

	if ($cid = mysql_insert_id()){

		mysql_query("UPDATE `wed_files` SET `order` = '$cid' WHERE `id` = '$cid'");

		js_location("/wedadmin/files.php?cid=".$cid);

		die();

	}

}

echo '<hr size="1"><br />';

echo '<form method="post" action="/wedadmin/files.php?cid='.$cid.'&addc">';

echo '<input type="text" name="actitle" value="">';

echo ' &nbsp; <input type="submit" value="добавить категорию">';

echo '</form><br />';

echo '<form method="post" action="/wedadmin/files.php?cid='.$cid.'&addi">';

echo '<input type="text" name="aititle" value="">';

echo ' &nbsp; <input type="submit" value="добавить файл">';

echo '</form>';

require_once(NX_PATH.'wedadmin/footer.inc.php');
?>