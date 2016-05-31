<?php
/*
#
#		DOCUMENT MANAGER 1.0
#



на самом деле проблема уже решилась :)
оказывается Русский Апач пытается перекодировать бинарный файл, идущий через CGI.
Я поставил в .htaccess, лежащий в папке, где скрипт, директиву
CharsetRecodeMethodsIn None
CharsetRecodeMethodsOut None
потом обнаружил, что вместо русского языка, который должна возвратить CGI программа,
я вижу крокозябры :) и убрал директиву CharsetRecodeMethodsOut None. И все заработало :)
*/
if (!defined("NX_PATH")) define("NX_PATH", "../");
if (!defined("NX_URL")) define("NX_URL", "/");
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'lib/translit.lib.php');
require_once(WA_PATH.'auth.inc.php');
if (!isset($_SESSION["WA_USER"])) include(WA_PATH.'index.php'); else{
	db_open();





	// CONFIGURATION
	$docs_path = NX_PATH.'price/';
	$docs_url = NX_URL.'price/';
	$docs_table = 'wed_tariffs_docs';
	$page_title = 'ПРАЙСЫ';
	$script_name = 'price_docs.php';

	// TABLE FIELDS:
	// id
	// title
	// html
	// filename
	// order

	$error_msg = '';

	include(WA_PATH.'header.inc.php');
	echo '<div align="center">';
	echo '<h1><a href="'.WA_URL.$script_name.'">'.$page_title.'</a></h1>';
	$continue = true;


	// DELETE
	if (isset($_GET["deletei"])){
		$id = intval($_GET["deletei"]);
		mysql_query("DELETE FROM `$docs_table` WHERE `id` = '$id'");
	}

	// CREATE DOCUMENT
	if (isset($_GET["createi"])){
		$title = addslashes($_POST["title"]);
		$order = intval($_POST["order"]);
		$html = addslashes($_POST["html"]);
		if (mysql_query("INSERT INTO `$docs_table` (`title`, `html`, `order`) VALUES ('$title', '$html', '$order')")){
			if ($id = mysql_insert_id()){
				if (isset($_FILES["file"])){
					// DOC and XLS supported only
					$tmpinfo = $_FILES["file"];
					$filename = $tmpinfo["name"];
					$tmpname = $tmpinfo["tmp_name"];
					$exts = explode('.', $filename);
					if (count($exts) > 1){
						$extension = $exts[count($exts)-1];
						unset($exts[count($exts)-1]);
						if (($extension == 'doc') OR ($extension == 'xls')){
							if (count($exts)>1)	$addon = implode(".", $exts); else $addon = array_pop($exts);
							$addon = translit($addon);
							$addon = preg_replace("'([^a-z|0-9]+)'i", "_", $addon);
							$new_filename = $id.'_'.$addon.'.'.$extension;
							echo $docs_path.$new_filename;
							if (is_uploaded_file($tmpname)){
								if (move_uploaded_file($tmpname, $docs_path.$new_filename)){
									echo $docs_path.$new_filename;
									@chmod($docs_path.$new_filename, 644);
									//echo ' ow:'.fileowner($docs_path.$new_filename);
									//echo ' o28:'.fileowner($docs_path.'28.xls');
									//echo ' pw:'.fileperms($docs_path.$new_filename);
									//echo ' p28:'.fileperms($docs_path.'28.xls');
									mysql_query("UPDATE `$docs_table` SET `filename` = '$new_filename' WHERE `id` = '$id'");
								}
							}
						}else{
							$error_msg .= "К загрузке допускаются только документы с расширением .doc (MS Word) или .xls (MS Excel)<br/>\n";
						}
					}
				}
			}
		}
	}
	// UPDATE DOCUMENT
	if (isset($_GET["updatei"])){
		$id = intval($_GET["updatei"]);
		if (mysql_result(mysql_query("SELECT COUNT(*) FROM `$docs_table` WHERE `id` = '$id'"),0)){
			$title = addslashes($_POST["title"]);
			$order = intval($_POST["order"]);
			$html = addslashes($_POST["html"]);
			if (mysql_query("UPDATE `$docs_table` SET `title` = '$title', `html` = '$html', `order` = '$order' WHERE `id` = '$id'")){
				if (isset($_FILES["file"])){
					// DOC and XLS supported only
					$tmpinfo = $_FILES["file"];
					$filename = $tmpinfo["name"];
					echo $filename;
					$tmpname = $tmpinfo["tmp_name"];
					echo $tmpname;
					$exts = explode('.', $filename);
					if (count($exts) > 1){
						$extension = $exts[count($exts)-1];
						unset($exts[count($exts)-1]);
						if (($extension == 'doc') OR ($extension == 'xls')){
							if (count($exts)>1)	$addon = implode(".", $exts); else $addon = array_pop($exts);
							$addon = translit($addon);
							$addon = preg_replace("'([^a-z|0-9]+)'i", "_", $addon);
							$new_filename = $id.'_'.$addon.'.'.$extension;
							echo $docs_path.$new_filename;
							if (is_uploaded_file($tmpname)){
								if (file_exists($docs_path.$new_filename)) unlink($docs_path.$new_filename);
								if (move_uploaded_file($tmpname, $docs_path.$new_filename)){
									//echo $docs_path.$new_filename;
									@chmod($docs_path.$new_filename, 644);
									//echo ' ow:'.fileowner($docs_path.$new_filename);
									//echo ' o28:'.fileowner($docs_path.'28.xls');
									//echo ' pw:'.fileperms($docs_path.$new_filename);
									//echo ' p28:'.fileperms($docs_path.'28.xls');
									mysql_query("UPDATE `$docs_table` SET `filename` = '$new_filename' WHERE `id` = '$id'");
								}
							}
						}else{
							$error_msg .= "К загрузке допускаются только документы с расширением .doc (MS Word) или .xls (MS Excel)<br/>\n";
						}
					}
				}
			}
		}
	}



	// ADD DOCUMENT
	if (isset($_GET["addi"])){
                ?>
                <script type="text/javascript">
                _editor_url = "<?=WA_URL?>htmlarea/";
                _editor_lang = "en";
                </script>
<script type="text/javascript" src="<?=WA_URL?>htmlarea/htmlarea.js"></script>
                <?php
                echo '<H1>добавление документа</H1>';
                echo '<table cellpadding="0" cellspacing="0" border="0" width="500"><tr><td align="left">';
                echo '<FORM method="post" enctype="multipart/form-data" action="'.WA_URL.$script_name.'?createi">';
                echo ' Заголовок:<BR/><INPUT type="text" name="title" value="" style="width:500px;"><BR/><BR/>';
                echo ' Порядок отображения:<BR/><INPUT type="text" name="order" value="" style="width:500px;"><BR/><BR/>';
                echo ' Файл:';
                echo '<BR/><INPUT type="file" name="file" value=""><BR/><BR/>';
                echo ' Текст документа (при отсутствии файла):<BR/><TEXTAREA style="width:500px;height:100px;" id="html" name="html" rows=5 cols=50></TEXTAREA><BR/><BR/>';
                echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';
                echo '</FORM>';
                echo '</td></tr></table>';
                ?>
                <script type="text/javascript" defer="1">
                var config = new HTMLArea.Config();
                config.width = '500px';
                config.height = '400px';
                HTMLArea.replace('html', config);
                </script>
                <?php
                $continue = false;
	}

	// EDIT DOCUMENT
	if (isset($_GET["editi"])){
		$id = intval($_GET["editi"]);
		if ($query = mysql_query("SELECT * FROM `$docs_table` WHERE `id` = '$id'")){
			if ($item = mysql_fetch_array($query)){
                ?>
                <script type="text/javascript">
                _editor_url = "<?=WA_URL?>htmlarea/";
                _editor_lang = "en";
                </script>
<script type="text/javascript" src="<?=WA_URL?>htmlarea/htmlarea.js"></script>
                <?php
                echo '<H1>правка документа</H1>';
                echo '<table cellpadding="0" cellspacing="0" border="0" width="500"><tr><td align="left">';
                echo '<FORM method="post" enctype="multipart/form-data" action="'.WA_URL.$script_name.'?updatei='.$id.'">';
                echo ' Заголовок:<BR/><INPUT type="text" name="title" value="'.htmlspecialchars($item["title"]).'" style="width:500px;"><BR/><BR/>';
                echo ' Порядок отображения:<BR/><INPUT type="text" name="order" value="'.$item["order"].'" style="width:500px;"><BR/><BR/>';
                echo ' Файл:';
                if ($item["filename"] !== '' && file_exists($docs_path.$item["filename"])) echo '<br/>загружен '.$item["filename"].', '.filesize($docs_path.$item["filename"]).' байт';
                echo '<BR/><INPUT type="file" name="file" value=""><BR/><BR/>';
                echo ' Текст документа (при отсутствии файла):<BR/><TEXTAREA style="width:500px;height:100px;" id="html" name="html" rows=5 cols=50>'.htmlspecialchars($item["html"]).'</TEXTAREA><BR/><BR/>';
                echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';
                echo '</FORM>';
                echo '</td></tr></table>';
                ?>
                <script type="text/javascript" defer="1">
                var config = new HTMLArea.Config();
                config.width = '500px';
                config.height = '400px';
                HTMLArea.replace('html', config);
                </script>
                <?php
                $continue = false;
			}
		}
	}




	if ($continue){
		echo '<table cellpadding="3" cellspacing="0" border="0" width="550" class="catalogue">';
		echo '<tr class="th"><td>+</td></tr>';
		//echo '<tr class="r1"><td><a href="'.WA_URL.'docs.php?addc&cid='.$cid.'">новая категория</a></td></tr>';
		echo '<tr class="r0"><td><a href="'.WA_URL.$script_name.'?addi">новый документ</a></td></tr>';
		echo '</table>';
		echo '<br/><br/>';
		$query = mysql_query("SELECT * FROM `$docs_table` ORDER BY `order` ASC");
		if (mysql_num_rows($query)){
			$r = 1;
			echo '<table cellpadding="3" cellspacing="0" border="0" width="550" class="catalogue">';
			echo '<tr class="th"><td>#</td><td>Порядок</td><td>Название</td><td>Тип</td><td>Действия</td></tr>';
			while ($item = mysql_fetch_array($query)) {
				$r++;
				if ($r > 1) $r = 0;
				echo "\n".'<tr class="r'.$r.'">';
				echo '<td align="left" width="10" nowrap class="id">'.$item["id"].'</td>';
				echo '<td align="right" width="10" nowrap class="id">'.$item["order"].'</td>';
				//echo '<td align="left" width="5"><img src="'.WA_URL.'images/icons/dot_violet.png" border="0"></td>';
				echo '<td align="left">'.$item["title"].'</td>';
				echo '<td align="left">';
				if ($item["filename"] !== '') echo 'файл'; elseif (trim($item["html"]) !== '') echo 'текст'; else echo 'нет';
				echo '</td>';
				echo '<td align="left">';
				echo '<a href="'.WA_URL.$script_name.'?editi='.$item["id"].'" title="свойства">';
				echo '<img src="'.WA_URL.'images/icons/b_props.png" width="16" height="16" border="0">';
				echo '</a>';
				echo '&nbsp;';
				echo '<a href="'.WA_URL.$script_name.'?deletei='.$item["id"].'&cid='.$cid.'" title="удалить" onClick="javascript: if (confirm(\'Удалить документ?\')) { return true;} else { return false;}">';
				echo '<img src="'.WA_URL.'images/icons/b_drop.png" width="16" height="16" border="0">';
				echo '</a>';
				//echo '&nbsp;';
				//echo '<a href="'.WA_URL.$script_name.'?storefront='.$item["id"].'&cid='.$cid.'" title="в лидеры продаж">';
				//echo '<img src="'.WA_URL.'images/icons/b_bookmark.png" width="16" height="16" border="0">';
				//echo '</a>';
				echo '</td></tr>';
			}
			echo '</table>';
		}
	}
	echo '</div>';
	include(WA_PATH.'footer.inc.php');
}
?>