<?php
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("NX_PATH")) define("NX_PATH", "../");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'auth.inc.php');
require_once(NX_PATH.'cms.conf.php');
if (!isset($_SESSION["WA_USER"])) include(WA_PATH.'index.php'); else{
	$wa_width = 1000;
	include(WA_PATH.'header.inc.php');
	echo '<div align="center">';
	echo '<h1><a href="'.WA_URL.'simple_cms.php">Редактор страниц</a></h1>';
	db_open();
	$continue = true;
	$menu =& $GLOBALS["globalmenu"];

	/*
	________________________________________________________________________________________________________________

	DELETE (FINISHED)
	________________________________________________________________________________________________________________

	*/

	if (isset($_GET["deletei"])){
		$id = intval($_GET["deletei"]);
		mysql_query("DELETE FROM `wed_cms_pages` WHERE `id` = '$id'");
	}
	/*
	________________________________________________________________________________________________________________

	CREATE
	________________________________________________________________________________________________________________

	*/
	if (isset($_GET["createi"])){
		//$id = intval($_GET["createi"]);
		$parent_id = intval($_POST["cid"]);
		$title = addslashes($_POST["title"]);
		$content = addslashes($_POST["content"]);
		$order = intval($_POST["order"]);
		if (isset($_POST["default"]) && ($_POST["default"] == 1)) $is_default = 1; else $is_default = 0;
		mysql_query("INSERT INTO `wed_cms_pages` (`parent_id`, `title`, `content`, `order`, `is_default`) VALUES ('$parent_id', '$title', '$content', '$order', '$is_default')");
	}
	/*
	________________________________________________________________________________________________________________

	UPDATE
	________________________________________________________________________________________________________________

	*/
	if (isset($_GET["updatei"])){
		$id = intval($_GET["updatei"]);
		$parent_id = intval($_POST["cid"]);
		$title = addslashes($_POST["title"]);
		$content = addslashes($_POST["content"]);
		$order = intval($_POST["order"]);
		if (isset($_POST["default"]) && ($_POST["default"] == 1)) $is_default = 1; else $is_default = 0;
		mysql_query("UPDATE `wed_cms_pages` SET `order` = '$order', `title` = '$title', `content` = '$content', `parent_id` = '$parent_id', `is_default` = '$is_default' WHERE `id` = '$id'");
	}
	/*
	----------------------------------------------------------------------------------------------------------------
	EDIT
	----------------------------------------------------------------------------------------------------------------
	*/
	if (isset($_GET["editi"])){
		$id = intval($_GET["editi"]);
		db_open();
		if ($item = mysql_fetch_array(mysql_query("SELECT * FROM `wed_cms_pages` WHERE `id` = '$id' LIMIT 0,1"))){
                ?>
                <script type="text/javascript">
                _editor_url = "<?=WA_URL?>htmlarea/";
                _editor_lang = "en";
                </script>
				<script type="text/javascript" src="<?=WA_URL?>htmlarea/htmlarea.js"></script>
                <?php
                echo '<H1>редактирование страницы</H1>';
                echo '<table cellpadding="0" cellspacing="0" border="0" width="500"><tr><td align="left">';
                echo '<FORM method="post" action="'.WA_URL.'simple_cms.php?updatei='.$id.'">';
                echo ' Категория:<BR/><SELECT name="cid">';
                foreach ($menu as $mid => $point) {
                	echo '<option value="'.$mid.'"';
                	if ($mid == $item["parent_id"]) echo ' selected';
                	echo '>'.$point["title"].'</option>';
                }
                echo '</SELECT><BR/><BR/>';
                echo ' Заголовок страницы:<BR/><INPUT type="text" name="title" value="'.$item["title"].'" style="width:500px;"><BR/><BR/>';
                echo ' Содержимое:<BR/><textarea name="content" id="content" cols="50" rows="10">'.htmlspecialchars($item["content"]).'</textarea><BR/><BR/>';
                echo ' Порядок отображения:<BR/><INPUT type="text" name="order" value="'.$item["order"].'" style="width:500px;"><BR/><BR/>';
                echo ' <INPUT type="checkbox" name="default" value="1"';
                if ($item["is_default"]) echo ' checked';
                echo '> по умолчанию<BR/><BR/>';
                echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';
                echo '</FORM>';
               ?>
                <script type="text/javascript" defer="1">
                var config = new HTMLArea.Config();
                config.width = '<?=($wa_width-10)?>px';
                config.height = '600px';
                HTMLArea.replace('content', config);
                </script>
                <?php
                echo '</td></tr></table>';
                $continue = false;
		}
	}
	/*
	----------------------------------------------------------------------------------------------------------------
	ADD
	----------------------------------------------------------------------------------------------------------------
	*/
	if (isset($_GET["addi"])){
		$id = intval($_GET["addi"]);
		//db_open();
		//if ($item = mysql_fetch_array(mysql_query("SELECT * FROM `wed_cms_pages` WHERE `id` = '$id' LIMIT 0,1"))){
                ?>
                <script type="text/javascript">
                _editor_url = "<?=WA_URL?>htmlarea/";
                _editor_lang = "en";
                </script>
				<script type="text/javascript" src="<?=WA_URL?>htmlarea/htmlarea.js"></script>
                <?php
                echo '<H1>добавление страницы</H1>';
                echo '<table cellpadding="0" cellspacing="0" border="0" width="500"><tr><td align="left">';
                echo '<FORM method="post" action="'.WA_URL.'simple_cms.php?createi">';
                echo ' Категория:<BR/><SELECT name="cid">';
                foreach ($menu as $mid => $point) {
                	echo '<option value="'.$mid.'"';
                	echo '>'.$point["title"].'</option>';
                }
                echo '</SELECT><BR/><BR/>';
                echo ' Заголовок страницы:<BR/><INPUT type="text" name="title" value="" style="width:500px;"><BR/><BR/>';
                echo ' Содержимое:<BR/><textarea name="content" id="content" cols="50" rows="10"></textarea><BR/><BR/>';
                echo ' Порядок отображения:<BR/><INPUT type="text" name="order" value="" style="width:500px;"><BR/><BR/>';
                echo ' <INPUT type="checkbox" name="default" value="1"> по умолчанию<BR/><BR/>';
                echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';
                echo '</FORM>';
               ?>
                <script type="text/javascript" defer="1">
                var config = new HTMLArea.Config();
                config.width = '<?=($wa_width-10)?>px';
                config.height = '600px';
                HTMLArea.replace('content', config);
                </script>
                <?php
                echo '</td></tr></table>';
                $continue = false;
		//}
	}


	if ($continue){
		$cid = 0;


		echo '<table cellpadding="3" cellspacing="0" border="0" width="550" class="catalogue">';
		echo '<tr class="th"><td align="left">+</td></tr>';
		//echo '<tr class="r1"><td><a href="'.WA_URL.'catalogue.php?addc&cid='.$cid.'">новая категория</a></td></tr>';
		echo '<tr class="r0"><td align="left"><a href="'.WA_URL.'simple_cms.php?addi">новая страница</a></td></tr>';
		echo '</table>';

		foreach ($menu as $parent_id => $item) {
			echo '<h2>'.$item["title"].'</h2>';
			$r = 1;
			$query = mysql_query("SELECT * FROM `wed_cms_pages` WHERE `parent_id` = '$parent_id' ORDER BY `order`");
			if (mysql_num_rows($query)){
				echo '<table cellpadding="3" cellspacing="0" border="0" width="550" class="catalogue">';
				echo '<tr class="th"><td>Порядок</td><td>Название</td><td>Действия</td></tr>';
				//echo '<tr class="r1"><td colspan=3>добавить страницу</td></tr>';
				while ($page = mysql_fetch_array($query)) {
					$r++;
					if ($r > 1) $r = 0;
					echo "\n".'<tr class="r';
					if ($page["is_default"]) echo 's'; else echo $r;
					echo '">';
					echo '<td align="left" width="10" nowrap class="id">'.$page["order"].'</td>';
					echo '<td align="left">';
					echo $page["title"];
					echo '</td>';
					echo '<td align="left" width="80">';
					echo '<a href="'.WA_URL.'simple_cms.php?editi='.$page["id"].'" title="свойства">';
					echo '<img src="'.WA_URL.'images/icons/b_props.png" width="16" height="16" border="0">';
					echo '</a>';
					echo '&nbsp;';
					echo '<a href="'.WA_URL.'simple_cms.php?deletei='.$page["id"].'" title="удалить" onClick="javascript: if (confirm(\'Удалить страницу?\')) { return true;} else { return false;}">';
					echo '<img src="'.WA_URL.'images/icons/b_drop.png" width="16" height="16" border="0">';
					echo '</a>';
					echo '</td></tr>';
				}
				echo '</table>';
			}
		}
		echo '<br/><br/></div>';
	}
	include(WA_PATH.'footer.inc.php');

}
?>