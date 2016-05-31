<?php
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("NEX_PATH")) define("NEX_PATH", "../");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'auth.inc.php');
if (!isset($_SESSION["WA_USER"])) include(WA_PATH.'index.php'); else{
	include(WA_PATH.'header.inc.php');
	echo '<div align="center">';
	echo '<h1><a href="'.WA_URL.'catalogue_storefront.php">ЛИДЕРЫ ПРОДАЖ</a></h1>';
	db_open();
	$continue = true;

	/*
	________________________________________________________________________________________________________________

	DELETE (FINISHED)
	________________________________________________________________________________________________________________

	*/

	if (isset($_GET["delete"])){
		$id = intval($_GET["delete"]);
		mysql_query("DELETE FROM `wed_shop_storefront` WHERE `item_id` = '$id'");
	}
	if (isset($_GET["deletex"])){
		$id = intval($_GET["deletex"]);
		mysql_query("DELETE FROM `wed_shop_storefront` WHERE `id` = '$id'");
	}
	/*
	________________________________________________________________________________________________________________

	UPDATE
	________________________________________________________________________________________________________________

	*/
	if (isset($_GET["update"])){
		$id = intval($_GET["update"]);
		$order = intval($_POST["order"]);
		mysql_query("UPDATE `wed_shop_storefront` SET `order` = '$order' WHERE `item_id` = '$id'");
	}
	/*
	----------------------------------------------------------------------------------------------------------------
	EDIT
	----------------------------------------------------------------------------------------------------------------
	*/
	if (isset($_GET["edit"])){
		$id = intval($_GET["edit"]);
		db_open();
		if ($item = mysql_fetch_array(mysql_query("SELECT * FROM `wed_shop_storefront` WHERE `item_id` = '$id'"))){
			echo '<H1>редактирование позиции</H1>';
			echo '<table cellpadding="0" cellspacing="0" border="0" width="500"><tr><td align="left">';
			echo '<FORM method="post" enctype="multipart/form-data" action="'.WA_URL.'catalogue_storefront.php?update='.$id.'">';
			echo ' Порядок отображения:<BR/><INPUT type="text" name="order" value="'.$item["order"].'" style="width:500px;"><BR/><BR/>';
			echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';
			echo '</FORM>';
			echo '</td></tr></table>';
			$continue = false;
		}
	}


	if ($continue){
		$cid = 0;
		// items
		$r = 1;
		$query = mysql_query("SELECT * FROM `wed_shop_storefront` ORDER BY `order`");
		if (mysql_num_rows($query)){
			echo '<table cellpadding="3" cellspacing="0" border="0" width="550" class="catalogue">';
			echo '<tr class="th"><td>Порядок</td><td>Название</td><td>Действия</td></tr>';
			while ($item_front = mysql_fetch_array($query)) {
				$id = $item_front["item_id"];
				$exists = false;
				if ($queryx = mysql_query("SELECT * FROM `wed_shop_items` WHERE `id` = '$id'")){
					if ($item = mysql_fetch_array($queryx)){
						$exists = true;
					}
				}
				$r++;
				if ($r > 1) $r = 0;
				echo "\n".'<tr class="r'.$r.'">';
				echo '<td align="left" width="10" nowrap class="id">'.$item_front["order"].'</td>';
				echo '<td align="left">';
				if ($exists) echo $item["title"];
				echo '</td>';
				echo '<td align="left">';
				if ($exists){
					echo '<a href="'.WA_URL.'catalogue_storefront.php?edit='.$item["id"].'" title="свойства">';
					echo '<img src="'.WA_URL.'images/icons/b_props.png" width="16" height="16" border="0">';
					echo '</a>';
					echo '&nbsp;';
					echo '<a href="'.WA_URL.'catalogue_storefront.php?delete='.$item["id"].'" title="удалить из лидеров" onClick="javascript: if (confirm(\'Удалить товар из лидеров продаж?\')) { return true;} else { return false;}">';
					echo '<img src="'.WA_URL.'images/icons/b_drop.png" width="16" height="16" border="0">';
					echo '</a>';
				}else{
					echo '<a href="'.WA_URL.'catalogue_storefront.php?deletex='.$item_front["id"].'" title="удалить из лидеров" onClick="javascript: if (confirm(\'Удалить товар из лидеров продаж?\')) { return true;} else { return false;}">';
					echo '<img src="'.WA_URL.'images/icons/b_drop.png" width="16" height="16" border="0">';
					echo '</a>';
				}
				echo '</td></tr>';
			}
			echo '</table>';
		}
	}
	echo '<br/><br/></div>';
	include(WA_PATH.'footer.inc.php');
}
?>