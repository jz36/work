<?php
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("NX_PATH")) define("NX_PATH", "../");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'auth.inc.php');
if (!isset($_SESSION["WA_USER"])) include(WA_PATH.'index.php'); else{
	include(WA_PATH.'header.inc.php');
	echo '<div align="center">';
	echo '<h1><a href="'.WA_URL.'upload_price.php">ЗАГРУЗКА ПРАЙСА (Формат Excel, *.xls)</a></h1>';
	if (isset($_FILES["price"])){
		if (is_uploaded_file($_FILES["price"]["tmp_name"])){
			if (move_uploaded_file($_FILES["price"]["tmp_name"], NX_PATH.'data/M-models.xls')){
				chmod(NX_PATH.'data/M-models.xls', 0644);
				echo 'Файл успешно загружен.<br/><br/>';
			}
		}
	}
	echo '<form method="POST" enctype="multipart/form-data" action="'.WA_URL.'upload_price.php">';
	echo '<input type="file" name="price" value=""><br/><br/>';
	echo '<input type="submit" value="загрузить">';
	echo '</form>';
	include(WA_PATH.'footer.inc.php');
}
?>