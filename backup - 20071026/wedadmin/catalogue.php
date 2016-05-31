<?php
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("NEX_PATH")) define("NEX_PATH", "../");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'auth.inc.php');

$cid=$_GET["cid"];

if (!isset($_SESSION["WA_USER"])) include(WA_PATH.'index.php'); else{
	include(WA_PATH.'header.inc.php');
	echo '<div align="center">';
	echo '<h1><a href="'.WA_URL.'catalogue.php">КАТАЛОГ ТОВАРОВ</a></h1>';
	db_open();
	$continue = true;

	/**
	* @return unknown
	* @param unknown $parent_id
	* @param unknown $exclude_id
	* @param unknown $prefix
	* @desc Внести описание здесь...
	*/
	function shop_categories_options($parent_id=0, $exclude_id=0, $prefix = '', $selected){
	        global $cid;
	        if (!$selected) { $selected = $cid; }
                db_open();
		$options_html = '';
		if ($parent_id == 0) $options_html .= '<option value="0">Главная</option>';
		if ($query = mysql_query("SELECT * FROM `wed_shop_categories` WHERE `parent_id` = '$parent_id'")){
			while ($option = mysql_fetch_array($query)) {
			        #print "cid=$cid, id_ = $option[id], cid_ = $option[cid]<BR>";
				if ($option["id"] !== $exclude_id) {
					$options_html .= '<option value="'.$option["id"].'"';
					if (intval($option["id"]) == intval($selected)) $options_html .= ' selected';
					#if ($cid == $option["id"]) $options_html .= ' selected';
					$options_html .= '>'.$prefix.$option["title"].'</option>';
				}
				if ($option["id"] !== '0') $options_html .= shop_categories_options($option["id"], $exclude_id, $prefix.$option["title"].' - ',$selected);
			}
		}
		return $options_html;
	}
	if (isset($_GET["storefront"])){
		$id = intval($_GET["storefront"]);
		if (!mysql_result(mysql_query("SELECT COUNT(*) FROM `wed_shop_storefront` WHERE `item_id` = '$id'"),0)){
			mysql_query("INSERT INTO `wed_shop_storefront` (`item_id`, `order`) VALUES ('$id', '999')");
		}
	}


	/*
	________________________________________________________________________________________________________________

	DELETE (FINISHED)
	________________________________________________________________________________________________________________

	*/
	if (isset($_GET["deletec"])){
		$id = intval($_GET["deletec"]);
		mysql_query("DELETE FROM `wed_shop_categories` WHERE `id` = '$id'");
	}
	if (isset($_GET["deletei"])){
		$id = intval($_GET["deletei"]);
		mysql_query("DELETE FROM `wed_shop_items` WHERE `id` = '$id'");
	}
	/*
	________________________________________________________________________________________________________________

	UPDATE
	________________________________________________________________________________________________________________

	*/
	if (isset($_GET["updatec"])){
		$id = intval($_GET["updatec"]);
		$newid = intval($_POST["id"]);
		if ($id !== $newid && mysql_result(mysql_query("SELECT COUNT(*) FROM `wed_shop_categories` WHERE `id` = '$newid'"),0)) {
			echo 'Ошибка: категория с данным номером уже существует<br/>';
			$_GET["editc"] = $id;
		}else{
			$title = addslashes($_POST["title"]);
			$category_id = intval($_POST["category_id"]);
			$order = intval($_POST["order"]);
			mysql_query("UPDATE `wed_shop_categories` SET `id` = '$newid', `parent_id` = '$category_id', `title` = '$title', `order` = '$order' WHERE `id` = '$id'");
		}
	}
	if (isset($_GET["updatei"])){
		$id = intval($_GET["updatei"]);
		$newid = intval($_POST["id"]);

		if ($id !== $newid && mysql_result(mysql_query("SELECT COUNT(*) FROM `wed_shop_items` WHERE `id` = '$newid'"),0)) {
			echo 'Ошибка: товар с данным номером уже существует<br/>';
			$_GET["editc"] = $id;
		}else{
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM `wed_shop_extensions` WHERE `item_id` = '$id'"),0)){
				$ext_id = mysql_result(mysql_query("SELECT `id` FROM `wed_shop_extensions` WHERE `item_id` = '$id'"),0);
			}else{
				mysql_query("INSERT INTO `wed_shop_extensions` (`item_id`) VALUES ('$id')");
				$ext_id = mysql_insert_id();
			}

			mysql_query("UPDATE `wed_shop_items` SET `id` = '$newid' WHERE `id` = '$id'");
			mysql_query("UPDATE `wed_shop_extensions` SET `item_id` = '$newid' WHERE `id` = '$ext_id'");

			$id = $newid;

			$title = addslashes($_POST["title"]);
			$category_id = intval($_POST["category_id"]);
			$price = addslashes($_POST["price"]);
			$description = addslashes($_POST["description"]);
			$full_description = addslashes($_POST["full_description"]);
			mysql_query("UPDATE `wed_shop_items` SET `title` = '$title', `category_id` = '$category_id', `price` = '$price',`description` = '$description' WHERE `id` = '$id'");
			mysql_query("UPDATE `wed_shop_extensions` SET `full_description` = '$full_description' WHERE `id` = '$ext_id'");
			$large_uploaded = false;
			if (isset($_FILES["large_picture"])){
				// JPEG SUPPORT ONLY !
				$tmpinfo = $_FILES["large_picture"];
				$filename = $tmpinfo["name"];
				$tmpname = $tmpinfo["tmp_name"];
				$exts = explode('.', $filename);
				if (count($exts)){
					$new_filename = 'large_'.$ext_id.'.'.$exts[count($exts)-1];
				}else{
					$new_filename = 'large_'.$ext_id;
				}
				if (is_uploaded_file($tmpname)){
					if (move_uploaded_file($tmpname, NEX_PATH.'catalogue/images/'.$new_filename)){
						if ($info = getimagesize(NEX_PATH.'catalogue/images/'.$new_filename)){
							if (($info[2] == 2) && function_exists("imagecreatetruecolor")){
								$im = imagecreatefromjpeg(NEX_PATH.'catalogue/images/'.$new_filename);
								$w = 500;
								$im_w = $info[0];
								$im_h = $info[1];
								if ($im_w > $w) $h = $im_h * $w / $im_w; else {$h = $im_h; $w = $im_w;}
								$im2 = imagecreatetruecolor($w, $h);
								imagecopyresampled($im2, $im, 0,0,0,0,$w,$h,$im_w, $im_h);
								imagejpeg($im2, NEX_PATH.'catalogue/images/'.$new_filename, 85);
							}
						}
						chmod(NEX_PATH.'catalogue/images/'.$new_filename, 0644);
						mysql_query("UPDATE `wed_shop_extensions` SET `large_picture` = '$new_filename' WHERE `id` = '$ext_id'");
						$large_uploaded = true;
					}
				}

			}
			if ($large_uploaded){
				$large_filename = $new_filename;
			}
			$small_uploaded = false;
			if (isset($_FILES["small_picture"])){
				if (isset($_FILES["small_picture"])){
					// JPEG SUPPORT ONLY !
					$tmpinfo = $_FILES["small_picture"];
					$filename = $tmpinfo["name"];
					$tmpname = $tmpinfo["tmp_name"];
					$exts = explode('.', $filename);
					if (count($exts)){
						$new_filename = 'small_'.$ext_id.'.'.$exts[count($exts)-1];
					}else{
						$new_filename = 'small_'.$ext_id;
					}
					if (is_uploaded_file($tmpname)){
						if (move_uploaded_file($tmpname, NEX_PATH.'catalogue/images/'.$new_filename)){
							if ($info = getimagesize(NEX_PATH.'catalogue/images/'.$new_filename)){
								if (($info[2] == 2) && function_exists("imagecreatetruecolor")){
									$im = imagecreatefromjpeg(NEX_PATH.'catalogue/images/'.$new_filename);
									$w = 120;
									$im_w = $info[0];
									$im_h = $info[1];
									if ($im_w > $w) $h = $im_h * $w / $im_w; else {$h = $im_h; $w = $im_w;}
									$im2 = imagecreatetruecolor($w, $h);
									imagecopyresampled($im2, $im, 0,0,0,0,$w,$h,$im_w, $im_h);
									imagejpeg($im2, NEX_PATH.'catalogue/images/'.$new_filename, 85);
								}
							}
							chmod(NEX_PATH.'catalogue/images/'.$new_filename, 0644);
							mysql_query("UPDATE `wed_shop_extensions` SET `small_picture` = '$new_filename' WHERE `id` = '$ext_id'");
							$small_uploaded = true;
						}
					}
				}
				if (!$small_uploaded && $large_uploaded){
					$exts = explode('.', $large_filename);
					if (count($exts)){
						$new_filename = 'small_'.$ext_id.'.'.$exts[count($exts)-1];
					}else{
						$new_filename = 'small_'.$ext_id;
					}
					if ($info = getimagesize(NEX_PATH.'catalogue/images/'.$large_filename)){
						if (($info[2] == 2) && function_exists("imagecreatetruecolor")){
							$im = imagecreatefromjpeg(NEX_PATH.'catalogue/images/'.$large_filename);
							$w = 120;
							$im_w = $info[0];
							$im_h = $info[1];
							if ($im_w > $w) $h = $im_h * $w / $im_w; else {$h = $im_h; $w = $im_w;}
							$im2 = imagecreatetruecolor($w, $h);
							imagecopyresampled($im2, $im, 0,0,0,0,$w,$h,$im_w, $im_h);
							imagejpeg($im2, NEX_PATH.'catalogue/images/'.$new_filename, 85);
						}
					}
					chmod(NEX_PATH.'catalogue/images/'.$new_filename, 0644);
					mysql_query("UPDATE `wed_shop_extensions` SET `small_picture` = '$new_filename' WHERE `id` = '$ext_id'");
				}
			}
		}
	}
	/*
	----------------------------------------------------------------------------------------------------------------
	CREATE
	----------------------------------------------------------------------------------------------------------------
	*/
	if (isset($_GET["createc"])){
		$id = intval($_POST["id"]);
		$category_id = intval($_POST["category_id"]);
		if (mysql_result(mysql_query("SELECT COUNT(*) FROM `wed_shop_categories` WHERE `id` = '$id'"),0)) {
			echo 'Ошибка: категория с данным номером уже существует<br/>';
			$_GET["addc"] = $category_id;
		}else{
			$title = addslashes($_POST["title"]);
			$order = intval($_POST["order"]);
			mysql_query("INSERT INTO `wed_shop_categories` (`id`, `parent_id`, `title`, `order`) VALUES ('$id', '$category_id', '$title', '$order')");
		}
	}
	if (isset($_GET["createi"])){

		$id = intval($_POST["id"]);
		$category_id = intval($_POST["category_id"]);

		if (mysql_result(mysql_query("SELECT COUNT(*) FROM `wed_shop_items` WHERE `id` = '$id'"),0)) {
			echo 'Ошибка: товар с данным номером уже существует<br/>';
			$_GET["addi"] = $category_id;
		}else{
			//$id = mysql_result(mysql_query("SELECT MAX(`id`) FROM `wed_shop_items`"),0)+1;
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM `wed_shop_extensions` WHERE `item_id` = '$id'"),0)){
				$ext_id = mysql_result(mysql_query("SELECT `id` FROM `wed_shop_extensions` WHERE `item_id` = '$id'"),0);
			}else{
				mysql_query("INSERT INTO `wed_shop_extensions` (`item_id`) VALUES ('$id')");
				$ext_id = mysql_insert_id();
			}
			$title = addslashes($_POST["title"]);
			$price = addslashes($_POST["price"]);
			$description = addslashes($_POST["description"]);
			$full_description = addslashes($_POST["full_description"]);
			mysql_query("INSERT INTO `wed_shop_items` (`id`, `category_id`, `title`, `price`, `description`) VALUES ('$id', '$category_id', '$title', '$price', '$description')");
			mysql_query("UPDATE `wed_shop_extensions` SET `full_description` = '$full_description' WHERE `id` = '$ext_id'");
			$large_uploaded = false;
			if (isset($_FILES["large_picture"])){
				// JPEG SUPPORT ONLY !
				$tmpinfo = $_FILES["large_picture"];
				$filename = $tmpinfo["name"];
				$tmpname = $tmpinfo["tmp_name"];
				$exts = explode('.', $filename);
				if (count($exts)){
					$new_filename = 'large_'.$ext_id.'.'.$exts[count($exts)-1];
				}else{
					$new_filename = 'large_'.$ext_id;
				}
				if (is_uploaded_file($tmpname)){
					if (move_uploaded_file($tmpname, NEX_PATH.'catalogue/images/'.$new_filename)){
						if ($info = getimagesize(NEX_PATH.'catalogue/images/'.$new_filename)){
							if (($info[2] == 2) && function_exists("imagecreatetruecolor")){
								$im = imagecreatefromjpeg(NEX_PATH.'catalogue/images/'.$new_filename);
								$w = 500;
								$im_w = $info[0];
								$im_h = $info[1];
								if ($im_w > $w) $h = $im_h * $w / $im_w; else {$h = $im_h; $w = $im_w;}
								$im2 = imagecreatetruecolor($w, $h);
								imagecopyresampled($im2, $im, 0,0,0,0,$w,$h,$im_w, $im_h);
								imagejpeg($im2, NEX_PATH.'catalogue/images/'.$new_filename, 85);
							}
						}
						chmod(NEX_PATH.'catalogue/images/'.$new_filename, 0644);
						mysql_query("UPDATE `wed_shop_extensions` SET `large_picture` = '$new_filename' WHERE `id` = '$ext_id'");
						$large_uploaded = true;
					}
				}

			}
			if ($large_uploaded){
				$large_filename = $new_filename;
			}
			$small_uploaded = false;
			if (isset($_FILES["small_picture"])){
				if (isset($_FILES["small_picture"])){
					// JPEG SUPPORT ONLY !
					$tmpinfo = $_FILES["small_picture"];
					$filename = $tmpinfo["name"];
					$tmpname = $tmpinfo["tmp_name"];
					$exts = explode('.', $filename);
					if (count($exts)){
						$new_filename = 'small_'.$ext_id.'.'.$exts[count($exts)-1];
					}else{
						$new_filename = 'small_'.$ext_id;
					}
					if (is_uploaded_file($tmpname)){
						if (move_uploaded_file($tmpname, NEX_PATH.'catalogue/images/'.$new_filename)){
							if ($info = getimagesize(NEX_PATH.'catalogue/images/'.$new_filename)){
								if (($info[2] == 2) && function_exists("imagecreatetruecolor")){
									$im = imagecreatefromjpeg(NEX_PATH.'catalogue/images/'.$new_filename);
									$w = 120;
									$im_w = $info[0];
									$im_h = $info[1];
									if ($im_w > $w) $h = $im_h * $w / $im_w; else {$h = $im_h; $w = $im_w;}
									$im2 = imagecreatetruecolor($w, $h);
									imagecopyresampled($im2, $im, 0,0,0,0,$w,$h,$im_w, $im_h);
									imagejpeg($im2, NEX_PATH.'catalogue/images/'.$new_filename, 85);
								}
							}
							chmod(NEX_PATH.'catalogue/images/'.$new_filename, 0644);
							mysql_query("UPDATE `wed_shop_extensions` SET `small_picture` = '$new_filename' WHERE `id` = '$ext_id'");
							$small_uploaded = true;
						}
					}
				}
				if (!$small_uploaded && $large_uploaded){
					$exts = explode('.', $large_filename);
					if (count($exts)){
						$new_filename = 'small_'.$ext_id.'.'.$exts[count($exts)-1];
					}else{
						$new_filename = 'small_'.$ext_id;
					}
					if ($info = getimagesize(NEX_PATH.'catalogue/images/'.$large_filename)){
						if (($info[2] == 2) && function_exists("imagecreatetruecolor")){
							$im = imagecreatefromjpeg(NEX_PATH.'catalogue/images/'.$large_filename);
							$w = 120;
							$im_w = $info[0];
							$im_h = $info[1];
							if ($im_w > $w) $h = $im_h * $w / $im_w; else {$h = $im_h; $w = $im_w;}
							$im2 = imagecreatetruecolor($w, $h);
							imagecopyresampled($im2, $im, 0,0,0,0,$w,$h,$im_w, $im_h);
							imagejpeg($im2, NEX_PATH.'catalogue/images/'.$new_filename, 85);
						}
					}
					chmod(NEX_PATH.'catalogue/images/'.$new_filename, 0644);
					mysql_query("UPDATE `wed_shop_extensions` SET `small_picture` = '$new_filename' WHERE `id` = '$ext_id'");
				}
			}
		}
	}
	/*
	----------------------------------------------------------------------------------------------------------------
	EDIT
	----------------------------------------------------------------------------------------------------------------
	*/
	if (isset($_GET["editc"])){
		$id = intval($_GET["editc"]);
		db_open();
		if ($item = mysql_fetch_array(mysql_query("SELECT * FROM `wed_shop_categories` WHERE `id` = '$id'"))){
                ?>
                <script type="text/javascript">
                _editor_url = "<?=WA_URL?>htmlarea/";
                _editor_lang = "en";
                </script>
<script type="text/javascript" src="<?=WA_URL?>htmlarea/htmlarea.js"></script>
                <?php
                echo '<H2>редактирование категории</H2>';
                echo '<table cellpadding="0" cellspacing="0" border="0" width="500"><tr><td align="left">';
                echo '<FORM method="post" action="'.WA_URL.'catalogue.php?updatec='.$item["id"].'&cid='.$item["parent_id"].'">';
                $item["id"]=max1Cid($item["id"]);
                echo ' Порядковый номер (Артикул):<BR/><INPUT type="text" name="id" value="'.$item["id"].'" style="width:500px;"><BR/><BR/>';
                echo ' Категория:<BR/><SELECT name="category_id">'.shop_categories_options(0,0,'',$item["parent_id"]).'</SELECT><BR/><BR/>';
                echo ' Заголовок:<BR/><INPUT type="text" name="title" value="'.htmlspecialchars($item["title"]).'" style="width:500px;"><BR/><BR/>';
                echo ' Порядок показа:<BR/><INPUT type="text" name="order" value="'.$item["order"].'" style="width:500px;"><BR/><BR/>';
                echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';

                echo '</FORM>';
                echo '</td></tr></table>';
               ?>
                <script type="text/javascript" defer="1">


                //var config = new HTMLArea.Config();
                //config.height = '400px';
                //HTMLArea.replace('full_text', config);
                </script>
                <?php
                $continue = false;
		}
	}
	if (isset($_GET["editi"])){
		$id = intval($_GET["editi"]);
		db_open();
		if ($item = mysql_fetch_array(mysql_query("SELECT * FROM `wed_shop_items` WHERE `id` = '$id'"))){
			if ($query = mysql_query("SELECT * FROM `wed_shop_extensions` WHERE `item_id` = '$id'")){
				if ($ext = mysql_fetch_array($query)){
					$ext["ext_id"] = $ext["id"];
					unset($ext["id"]);
					$item = array_merge($item, $ext);
				}
			}
			if (isset($_GET['noformat'])){
				$item['full_description'] = preg_replace("#<(\/)?(h1|h2|h3|font|span)([^>]*)?>#ims", "", preg_replace("#(<(\/)?[^>|\s]+)(\s+[^>]*?)?(>)#ims", "\\1\\4", $item['full_description']));
			}
                ?>
                <script type="text/javascript">
                _editor_url = "<?=WA_URL?>htmlarea/";
                _editor_lang = "en";
                </script>
<script type="text/javascript" src="<?=WA_URL?>htmlarea/htmlarea.js"></script>
                <?php
                echo '<H1>редактирование товара</H1>';
                echo '<table cellpadding="0" cellspacing="0" border="0" width="500"><tr><td align="left">';
                echo '<FORM method="post" enctype="multipart/form-data" action="'.WA_URL.'catalogue.php?updatei='.$item["id"].'&cid='.$item["category_id"].'">';
                echo ' Порядковый номер (Артикул):<BR/><INPUT type="text" name="id" value="'.$item["id"].'" style="width:500px;"><BR/><BR/>';
                echo ' Заголовок:<BR/><INPUT type="text" name="title" value="'.htmlspecialchars($item["title"]).'" style="width:500px;"><BR/><BR/>';

                echo ' Категория:<BR/><SELECT name="category_id">'.shop_categories_options(0,0,'',$item["category_id"]).'</SELECT><BR/><BR/>';

                echo ' Цена:<BR/><INPUT type="text" name="price" value="'.$item["price"].'" class="w100"><BR/><BR/>';
                echo ' Изображение (большое):';
                if (isset($item["large_picture"]) && $item["large_picture"] !== ''){
                	echo '<BR/>загружено:'.$item["large_picture"].'<BR/><IMG src="/catalogue/images/'.$item["large_picture"].'">';
                }
                echo '<BR/><INPUT type="file" name="large_picture" value=""><BR/><BR/>';
                echo ' Изображение (маленькое):';
                if (isset($item["small_picture"]) && $item["small_picture"] !== ''){
                	echo '<BR/>загружено:'.$item["small_picture"].'<BR/><IMG src="/catalogue/images/'.$item["small_picture"].'">';
                }
                echo '<BR/><INPUT type="file" name="small_picture" value=""><BR/><BR/>';
                echo ' Описание (краткое):<BR/><TEXTAREA style="width:500px;height:100px;" id="description" name="description" rows=5 cols=50>'.$item["description"].'</TEXTAREA><BR/><BR/>';
                echo '<div><br />* <a href="'.WA_URL.'catalogue.php?editi='.$item["id"].'&noformat">убрать форматирование в описании</a><br /><br /></div>';
                echo ' Описание (полное):<BR/><TEXTAREA style="width:500px;height:100px;" id="full_description" name="full_description" rows=5 cols=50>'.$item["full_description"].'</TEXTAREA><BR/><BR/>';
                echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';

                echo '</FORM>';
                echo '</td></tr></table>';

                #выдаем ссылку - изображения
		#echo '<a href="#ab" onclick="open(\'/wedadmin/lib_images.php?id='.$item['id'].'\', \'popUpWin\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=600,height=700,left=0, top=0,screenX=0,screenY=0\');">загрузить изображения</a>';


               ?>
                <script type="text/javascript" defer="1">


                var config = new HTMLArea.Config();
                config.width = '500px';
                config.height = '400px';
                HTMLArea.replace('full_description', config);
                </script>
                <?php
                $continue = false;
		}
	}
	/*
	________________________________________________________________________________________________________________

	ADD
	________________________________________________________________________________________________________________

	*/
	if (isset($_GET["addc"])){
        ?>
                <script type="text/javascript">
                _editor_url = "<?=WA_URL?>htmlarea/";
                _editor_lang = "en";
                </script>
<script type="text/javascript" src="<?=WA_URL?>htmlarea/htmlarea.js"></script>
        <?php
        echo '<H2>добавление категории</H2>';
        echo '<table cellpadding="0" cellspacing="0" border="0" width="500"><tr><td align="left">';
        echo '<FORM method="post" action="'.WA_URL.'catalogue.php?createc&cid='.$cid.'">';
        $item["id"]=max1Cid($item["id"]);
        echo ' Порядковый номер (Артикул):<BR/><INPUT type="text" name="id" value="'.$item[id].'" style="width:500px;"><BR/><BR/>';
        echo ' Категория:<BR/><SELECT name="category_id">'.shop_categories_options(0,0,'',$cid).'</SELECT><BR/><BR/>';
        echo ' Заголовок:<BR/><INPUT type="text" name="title" value="" style="width:500px;"><BR/><BR/>';
        echo ' Порядок показа (1,2...):<BR/><INPUT type="text" name="order" value="" style="width:500px;"><BR/><BR/>';
        echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';
        echo '</FORM>';
        echo '</td></tr></table>';
        ?>
                <script type="text/javascript" defer="1">


                //var config = new HTMLArea.Config();
                //config.height = '400px';
                //HTMLArea.replace('full_text', config);
                </script>
        <?php
        $continue = false;
	}
	if (isset($_GET["addi"])){
                
                   ?>
                
                <script type="text/javascript">
                _editor_url = "<?=WA_URL?>htmlarea/";
                _editor_lang = "en";
                </script>
<script type="text/javascript" src="<?=WA_URL?>htmlarea/htmlarea.js"></script>
                <?php
                echo '<H1>добавление товара</H1>';
                echo '<table cellpadding="0" cellspacing="0" border="0" width="500"><tr><td align="left">';
                echo '<FORM method="post" enctype="multipart/form-data" action="'.WA_URL.'catalogue.php?createi&cid='.$cid.'">';
                $item["id"]=max1Cid($item["id"]);
                echo ' Порядковый номер (Артикул):<BR/><INPUT type="text" name="id" value="'.$item[id].'" style="width:500px;"><BR/><BR/>';
                echo ' Заголовок:<BR/><INPUT type="text" name="title" value="" style="width:500px;"><BR/><BR/>';
                echo ' Категория:<BR/><SELECT name="category_id">'.shop_categories_options(0,0,'',$cid).'</SELECT><BR/><BR/>';
                echo ' Цена:<BR/><INPUT type="text" name="price" value="" class="w100"><BR/><BR/>';
                echo ' Изображение (большое):';
                echo '<BR/><INPUT type="file" name="large_picture" value=""><BR/><BR/>';
                echo ' Изображение (маленькое):';
                echo '<BR/><INPUT type="file" name="small_picture" value=""><BR/><BR/>';
                echo ' Описание (краткое):<BR/><TEXTAREA style="width:500px;height:100px;" id="description" name="description" rows=5 cols=50></TEXTAREA><BR/><BR/>';
                echo ' Описание (полное):<BR/><TEXTAREA style="width:500px;height:100px;" id="full_description" name="full_description" rows=5 cols=50></TEXTAREA><BR/><BR/>';
                echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';
                echo '</FORM>';
                echo '</td></tr></table>';
                ?>
                <script type="text/javascript" defer="1">


                var config = new HTMLArea.Config();
                config.width = '500px';
                config.height = '400px';
                HTMLArea.replace('full_description', config);
                </script>
                <?php
                $continue = false;
                //}
	}


	if (isset($_GET["xmlimport"])){
		$data_dir = WA_PATH.'data/';
		$filename = $data_dir.'catalogue.xml';
		if (isset($_FILES["xml"])){
			//echo 'ФП';
			if (is_uploaded_file($_FILES["xml"]["tmp_name"])){
				//echo 'ФU';
				if (move_uploaded_file($_FILES["xml"]["tmp_name"], $filename)){
					//echo 'ФM';
				}
			}
		}
		if (isset($_GET["start"])){
			// IMPORT
			if (file_exists($filename)) {
				$xml = implode('', file($filename));
				//echo $xml;
				if (preg_match("'<catalogue>(.*)<\/catalogue>'is", $xml, $s1)){
					//var_dump($s1);
					$xml_cat = $s1[1];
					//echo $xml_cat;
					if (preg_match("'<categories>(.*)<\/categories>'is", $xml_cat, $s2)){
						$cats = $s2[1];
						mysql_query('TRUNCATE TABLE `wed_shop_categories`');
						if (preg_match_all("'<category>(.*?)<\/category>'is", $cats, $s3)){
							//var_dump($s3);
							foreach ($s3[1] as $cat) {
								$catinfo = array();
								if (preg_match("'<id>(.*?)</id>'is", $cat, $s4)){
									$catinfo["id"] = intval($s4[1]);
								}
								if (preg_match("'<parent_id>(.*?)</parent_id>'is", $cat, $s4)){
									$catinfo["parent_id"] = intval($s4[1]);
								}
								if (preg_match("'<name>(.*?)</name>'is", $cat, $s4)){
									$catinfo["title"] = addslashes($s4[1]);
								}
								if (isset($catinfo["id"]) && isset($catinfo["parent_id"]) && isset($catinfo["title"])){
									mysql_query("INSERT INTO `wed_shop_categories` (`id`, `parent_id`, `title`) VALUES ('".$catinfo["id"]."', '".$catinfo["parent_id"]."', '".$catinfo["title"]."')");
								}
								//var_dump($cat);
							}
						}
					}
					if (preg_match("'<items>(.*)</items>'is", $xml_cat, $s2)){
						$items = $s2[1];
						mysql_query('TRUNCATE TABLE `wed_shop_items`');
						if (preg_match_all("'<item>(.*?)<\/item>'is", $items, $s3)){
							//var_dump($s3);
							foreach ($s3[1] as $item) {
								$iteminfo = array();
								if (preg_match("'<id>(.*?)</id>'is", $item, $s4)){
									$iteminfo["id"] = intval($s4[1]);
								}
								if (preg_match("'<category_id>(.*?)</category_id>'is", $item, $s4)){
									$iteminfo["category_id"] = intval($s4[1]);
								}
								if (preg_match("'<name>(.*?)</name>'is", $item, $s4)){
									$iteminfo["title"] = addslashes($s4[1]);
								}
								if (preg_match("'<description>(.*?)</description>'is", $item, $s4)){
									$iteminfo["description"] = addslashes($s4[1]);
								}
								if (preg_match("'<price>(.*?)</price>'is", $item, $s4)){
									$iteminfo["price"] = intval($s4[1]);
								}
								if (isset($iteminfo["id"]) && isset($iteminfo["category_id"]) && isset($iteminfo["title"])){
									mysql_query("INSERT INTO `wed_shop_items` (`id`, `category_id`, `title`, `description`, `price`) VALUES ('".$iteminfo["id"]."', '".$iteminfo["category_id"]."', '".$iteminfo["title"]."', '".$iteminfo["description"]."', '".$iteminfo["price"]."')");
								}
								//var_dump($cat);
							}
						}
					}
				}
			}
		}
		echo '<h2>Импорт XML</h2>';
		if (file_exists($filename)) {
			echo '<table><tr><td align="right"><h3>Файл с данными загружен</h3></td><td align="left">последняя модификация '.date("d/m/Y H:i:s",filemtime($filename)).'</td></tr>';
			echo '<tr><td></td><td align="left">размер файла '.filesize($filename).' (байт)</td></tr>';
			echo '<tr><td></td><td align="left"><form method="POST" action="'.WA_URL.'catalogue.php?xmlimport&start"><input type="submit" value="импортировать данные"></form></td></tr>';
		}
		echo '<form method="POST" enctype="multipart/form-data" action="'.WA_URL.'catalogue.php?xmlimport">';
		echo '<tr><td align="right"><h3>Загрузить/Обновить:</h3></td><td align="left"><input type="file" name="xml"></td></tr><tr><td></td><td align="left"><input type="submit" value="загрузить"></td></tr></table>';
		echo '</form>';
		$continue = false;
	}
	if ($continue){
		$cid = 0;
		if (isset($_GET["cid"])) $cid = intval($_GET["cid"]);
		echo '<table cellpadding="3" cellspacing="0" border="0" width="550" class="catalogue">';
		echo '<tr class="th"><td>+</td></tr>';
		echo '<tr class="r1"><td><a href="'.WA_URL.'catalogue.php?addc&cid='.$cid.'">новая категория</a></td></tr>';
		echo '<tr class="r0"><td><a href="'.WA_URL.'catalogue.php?addi&cid='.$cid.'">новый товар</a></td></tr>';
		echo '</table>';
		// categories
		$r = 1;
		$query = mysql_query("SELECT * FROM `wed_shop_categories` WHERE `parent_id` = '$cid' ORDER BY `order`");
		if (mysql_num_rows($query)){
			echo '<h2>КАТЕГОРИИ</h2>';
			echo '<table cellpadding="3" cellspacing="0" border="0" width="550" class="catalogue">';
			echo '<tr class="th"><td>#(Артикул)</td><td></td><td>Название</td><td>Действия</td></tr>';
			while ($cat = mysql_fetch_array($query)) {
				$r++;
				if ($r > 1) $r = 0;
				echo "\n".'<tr class="r'.$r.'">';
				echo '<td align="left" width="10" nowrap class="id">'.$cat["id"].'</td>';
				echo '<td align="left" width="16"><a href="'.WA_URL.'catalogue.php?cid='.$cat["id"].'"><img src="'.WA_URL.'images/icons/b_browse.png" width="16" height="16" border="0"></a></td><td align="left"><a href="'.WA_URL.'catalogue.php?cid='.$cat["id"].'">'.$cat["title"].'</a></td>';
				echo '<td align="left" width="60">';
				echo '<a href="'.WA_URL.'catalogue.php?editc='.$cat["id"].'" title="свойства">';
				echo '<img src="'.WA_URL.'images/icons/b_props.png" width="16" height="16" border="0">';
				echo '</a>';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				echo '<a href="'.WA_URL.'catalogue.php?deletec='.$cat["id"].'&cid='.$cid.'" title="удалить" onClick="javascript: if (confirm(\'Удалить категорию \\\''.addslashes(addslashes($cat["title"])).'\\\'?\')) { return true;} else { return false;}">';
				echo '<img src="'.WA_URL.'images/icons/b_drop.png" width="16" height="16" border="0">';
				echo '</a>';
				echo '</td></tr>';
			}
			echo '</table>';
		}
		// items
		$r = 1;
		$query = mysql_query("SELECT * FROM `wed_shop_items` WHERE `category_id` = '$cid'");
		if (mysql_num_rows($query)){
			echo '<h2>ТОВАРЫ</h2>';
			echo '<table cellpadding="3" cellspacing="0" border="0" width="550" class="catalogue">';
			echo '<tr class="th"><td>#(Артикул)</td><td>Название</td><td>Действия</td></tr>';
			while ($item = mysql_fetch_array($query)) {
				$r++;
				if ($r > 1) $r = 0;
				echo "\n".'<tr class="r'.$r.'">';
				echo '<td align="left" width="10" nowrap class="id">'.$item["id"].'</td>';
				//echo '<td align="left" width="5"><img src="'.WA_URL.'images/icons/dot_violet.png" border="0"></td>';
				echo '<td align="left"><a href="'.WA_URL.'catalogue.php?editi='.$item["id"].'">'.$item["title"].'</a></td>';
				echo '<td align="left">';
				echo '<a href="'.WA_URL.'catalogue.php?editi='.$item["id"].'" title="свойства">';
				echo '<img src="'.WA_URL.'images/icons/b_props.png" width="16" height="16" border="0">';
				echo '</a>';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				echo '<a href="'.WA_URL.'catalogue.php?deletei='.$item["id"].'&cid='.$cid.'" title="удалить" onClick="javascript: if (confirm(\'Удалить товар \\\''.addslashes(addslashes($item["title"])).'\\\'?\')) { return true;} else { return false;}">';
				echo '<img src="'.WA_URL.'images/icons/b_drop.png" width="16" height="16" border="0">';
				echo '</a>';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				echo '<a href="'.WA_URL.'catalogue.php?storefront='.$item["id"].'&cid='.$cid.'" title="в лидеры продаж">';
				echo '<img src="'.WA_URL.'images/icons/b_bookmark.png" width="16" height="16" border="0">';
				echo '</a>';
				echo '</td></tr>';
			}
			echo '</table>';
		}
		echo '<br/><br/><h3><img src="'.WA_URL.'images/icons/xml.gif" width="32" height="16" border="0">&nbsp;<a href="'.WA_URL.'catalogue.php?xmlimport">Импорт XML</a></h3>';
	}
	echo '<br/><br/></div>';
	include(WA_PATH.'footer.inc.php');
}
#############
# Возвращает максимальный Артикул id, выбирает из базы максимальный и добавляет 1
# Сделано для совместимости с Автозвуком
#############
function max1Cid($id){
  if ($id>0) { return $id; }
  db_open();
  $Q="select max(id) from wed_shop_items";
  $id1=mysql_result(mysql_query($Q),0)+1;
  $Q="select max(id) from wed_shop_categories";
  $id2=mysql_result(mysql_query($Q),0)+1;
  if ($id1>$id2) { return $id1; }
  else { return $id2; }
}


?>