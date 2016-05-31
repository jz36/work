<?php
	session_start();
	define('WA_URL', '');
	define('WA_PATH', '');
	error_reporting(E_ALL);
	include_once('config.inc.php');
	include_once('lib/mysql.lib.php');
	if(isset($_SESSION['WA_USER']) && db_open())
	{
		include_once('header.inc.php');
		
		function dump_map($pid, $tab)
		{
			if(($qr = mysql_query('SELECT `id`, `title` FROM `wed_sitemap` WHERE `pid`='.$pid.' ORDER BY `order`')) !== false)
				for($i = mysql_num_rows($qr); $i; $i--)
				{
					$row = mysql_fetch_row($qr);
					if($i === 1)
					{
						print "{$tab}<div class=\"lastitem\">\r\n{$tab}\t<a href=\"sitemap.php?edit&id={$row[0]}\">{$row[1]}</a><a href=\"sitemap.php?add&pid={$row[0]}\"><img src=\"images/icons/plus.gif\" style=\"width: 16px; height: 16px;\" alt=\"\" title=\"Добавить ссылку\" /></a><a href=\"sitemap.php?up&id={$row[0]}\"><img src=\"images/upbl.gif\" alt=\"\" title=\"Поднять выше\" /></a><a href=\"sitemap.php?down&id={$row[0]}\"><img src=\"images/downbl.gif\" alt=\"\" title=\"Опустить ниже\" /></a><a href=\"sitemap.php?absup&id={$row[0]}\"><img src=\"images/upbld.png\" alt=\"\" title=\"Поднять в начало\" /></a><a href=\"sitemap.php?absdown&id={$row[0]}\"><img src=\"images/downbld.png\" alt=\"\" title=\"Опустить в конец\" /></a><a href=\"sitemap.php?delete&id={$row[0]}\" onclick=\"if(confirm('Вы уверены?')){return true;}else{return false;}\"><img src=\"images/deleteor.gif\" alt=\"\" title=\"Удалить ссылку\" /></a>\r\n{$tab}</div>\r\n{$tab}<div class=\"lastnode\">\r\n";
						dump_map($row[0], $tab."\t");
						print "{$tab}</div>\r\n";
					}
					else
					{
						print "{$tab}<div class=\"item\">\r\n{$tab}\t<a href=\"sitemap.php?edit&id={$row[0]}\">{$row[1]}</a><a href=\"sitemap.php?add&pid={$row[0]}\"><img src=\"images/icons/plus.gif\" style=\"width: 16px; height: 16px;\" alt=\"\" title=\"Добавить ссылку\" /></a><a href=\"sitemap.php?up&id={$row[0]}\"><img src=\"images/upbl.gif\" alt=\"\" title=\"Поднять выше\" /></a><a href=\"sitemap.php?down&id={$row[0]}\"><img src=\"images/downbl.gif\" alt=\"\" title=\"Опустить ниже\" /></a><a href=\"sitemap.php?absup&id={$row[0]}\"><img src=\"images/upbld.png\" alt=\"\" title=\"Поднять в начало\" /></a><a href=\"sitemap.php?absdown&id={$row[0]}\"><img src=\"images/downbld.png\" alt=\"\" title=\"Опустить в конец\" /></a><a href=\"sitemap.php?delete&id={$row[0]}\" onclick=\"if(confirm('Вы уверены?')){return true;}else{return false;}\"><img src=\"images/deleteor.gif\" alt=\"\" title=\"Удалить ссылку\" /></a>\r\n{$tab}</div>\r\n{$tab}<div class=\"node\">\r\n";
						dump_map($row[0], $tab."\t");
						print "{$tab}</div>\r\n";
					}
				}
		}
		
		$show = true;
		
		if(isset($_GET['up'], $_GET['id']))
		{
			if(($qr = mysql_query('SELECT `order`, `pid` FROM `wed_sitemap` WHERE `id`='.intval($_GET['id']))) !== false && mysql_num_rows($qr))
			{
				list($order, $pid) = mysql_fetch_row($qr);
				if(($qr = mysql_query('SELECT `id`, `order` FROM `wed_sitemap` WHERE `order`<'.intval($order).' AND `pid`='.intval($pid).' ORDER BY `order` DESC LIMIT 1')) !== false && mysql_num_rows($qr))
				{
					list($id, $neworder) = mysql_fetch_row($qr);
					mysql_query('UPDATE `wed_sitemap` SET `order`='.intval($order).' WHERE `id`='.intval($id));
					mysql_query('UPDATE `wed_sitemap` SET `order`='.intval($neworder).' WHERE `id`='.intval($_GET['id']));
				}
			}
		}
		else if(isset($_GET['down'], $_GET['id']))
		{
			if(($qr = mysql_query('SELECT `order`, `pid` FROM `wed_sitemap` WHERE `id`='.intval($_GET['id']))) !== false && mysql_num_rows($qr))
			{
				list($order, $pid) = mysql_fetch_row($qr);
				if(($qr = mysql_query('SELECT `id`, `order` FROM `wed_sitemap` WHERE `order`>'.intval($order).' AND `pid`='.intval($pid).' ORDER BY `order` ASC LIMIT 1')) !== false && mysql_num_rows($qr))
				{
					list($id, $neworder) = mysql_fetch_row($qr);
					mysql_query('UPDATE `wed_sitemap` SET `order`='.intval($order).' WHERE `id`='.intval($id));
					mysql_query('UPDATE `wed_sitemap` SET `order`='.intval($neworder).' WHERE `id`='.intval($_GET['id']));
				}
			}
		}
		else if(isset($_GET['absup'], $_GET['id']))
		{
			if(($qr = mysql_query('SELECT `order`, `pid` FROM `wed_sitemap` WHERE `id`='.intval($_GET['id']))) !== false && mysql_num_rows($qr))
			{
				list($order, $pid) = mysql_fetch_row($qr);
				mysql_query('UPDATE `wed_sitemap` SET `order`=`order`+1 WHERE `order`<'.intval($order).' AND `pid`='.intval($pid));
				mysql_query('UPDATE `wed_sitemap` SET `order`=0 WHERE `id`='.intval($_GET['id']));
			}
		}
		else if(isset($_GET['absdown'], $_GET['id']))
		{
			if(($qr = mysql_query('SELECT `order`, `pid` FROM `wed_sitemap` WHERE `id`='.intval($_GET['id']))) !== false && mysql_num_rows($qr))
			{
				list($order, $pid) = mysql_fetch_row($qr);
				if(($qr = mysql_query('SELECT MAX(`order`) FROM `wed_sitemap` WHERE `pid`='.intval($pid))) !== false && mysql_num_rows($qr))
				{
					list($neworder) = mysql_fetch_row($qr);
					if($neworder > $order)
					{
						mysql_query('UPDATE `wed_sitemap` SET `order`=`order`-1 WHERE `order`>'.intval($order).' AND `pid`='.intval($pid));
						mysql_query('UPDATE `wed_sitemap` SET `order`='.intval($neworder).' WHERE `id`='.intval($_GET['id']));
					}
				}
			}
		}
		else if(isset($_GET['delete'], $_GET['id']) && ($qr = mysql_query('SELECT `pid`, `order` FROM `wed_sitemap` WHERE `id`='.intval($_GET['id']))) !== false && mysql_num_rows($qr))
		{
			list($pid, $order) = mysql_fetch_row($qr);
			mysql_query('DELETE FROM `wed_sitemap` WHERE `id`='.intval($_GET['id']));
			$task = array($id);
			while(count($task))
			{
				$id = intval(array_pop($task));
				if(($qr = mysql_query('SELECT `id` FROM `wed_sitemap` WHERE `pid`='.$id)) !== false)
					while(($row = mysql_fetch_row($qr)) !== false)
						array_push($task, $row[0]);
				mysql_query('DELETE FROM `wed_sitemap` WHERE `id`='.$id);
			}
			mysql_query('UPDATE `wed_sitemap` SET `order`=`order`-1 WHERE `pid`='.intval($pid).' AND `order`>'.$order);
		}
		else if(isset($_GET['add'], $_GET['pid']))
		{
			if(isset($_POST['title'], $_POST['link']))
			{
				$order = 0;
				if(($qr = mysql_query('SELECT COUNT(*), MAX(`order`) FROM `wed_sitemap` WHERE `pid`='.intval($_GET['pid']))) !== false && mysql_num_rows($qr))
				{
					list($count, $order) = mysql_fetch_row($qr);
					if($count)
						$order++;
				}
				mysql_query('INSERT INTO `wed_sitemap` SET `pid`='.intval($_GET['pid']).', `order`='.(int)$order.', `title`="'.addslashes($_POST['title']).'", `link`="'.addslashes($_POST['link']).'"');
			}
			else
			{
				$show = false;
?>
	<form action="sitemap.php?add&pid=<?=intval($_GET['pid'])?>" method="post">
		<table border="0" cellpadding="0" cellspacing="5" align="center">
			<tr>
				<td style="font-family: Tahoma, Verdana, 'Times New Roman'; font-size: 10pt; color: #990099; font-weight: bold;">Создание новой ссылки</td>
				<td></td>
			</tr>
			<tr>
				<td>Заголовок:</td>
				<td><input type="text" style="width: 200px;" name="title" /></td>
			</tr>
			<tr>
				<td>Ссылка:</td>
				<td><input type="text" style="width: 200px;" name="link" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="submit" style="width: 100px;" value="Создать" />
				</td>
			</tr>
		</table>
	</form>
<?php
			}
		}
		else if(isset($_GET['edit'], $_GET['id']))
		{
			if(isset($_POST['title'], $_POST['link']))
				mysql_query('UPDATE `wed_sitemap` SET `title`="'.addslashes($_POST['title']).'", `link`="'.addslashes($_POST['link']).'" WHERE `id`='.intval($_GET['id']));
			else if(($qr = mysql_query('SELECT `title`, `link` FROM `wed_sitemap` WHERE `id`='.intval($_GET['id']))) !== false && mysql_num_rows($qr))
			{
				$show = false;
				list($title, $link) = mysql_fetch_row($qr);
?>
	<form action="sitemap.php?edit&id=<?=intval($_GET['id'])?>" method="post">
		<table border="0" cellpadding="0" cellspacing="5" align="center">
			<tr>
				<td style="font-family: Tahoma, Verdana, 'Times New Roman'; font-size: 10pt; color: #990099; font-weight: bold;">Изменение ссылки</td>
				<td></td>
			</tr>
			<tr>
				<td>Заголовок:</td>
				<td><input type="text" style="width: 200px;" name="title" value="<?=$title?>" /></td>
			</tr>
			<tr>
				<td>Ссылка:</td>
				<td><input type="text" style="width: 200px;" name="link" value="<?=$link?>" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="submit" style="width: 100px;" value="Сохранить" />
				</td>
			</tr>
		</table>
	</form>
<?php
			}
		}
		
		if($show)
		{
?>
		<style type="text/css" media="all">
			.cTree
			{
				margin: 20px;
			}
			.cTree a
			{
				font-family: Tahoma, Verdana, "Times New Roman";
				text-decoration: none;
				font-weight: normal;
				line-height: 16px;
				font-size: 10pt;
				color: #000099;
			}
			.cTree img
			{
				width: 11px;
				height: 11px;
				margin-left: 5px;
				border: 0;
			}
			.cTree div
			{
				line-height: 32px;
			}
			.cTree div.node
			{
				background-repeat: repeat-y;
				background-image: url(/img/node[tree].gif);
				padding-left: 20px;
			}
			.cTree div.lastnode
			{
				padding-left: 20px;
			}
			.cTree div.item
			{
				display: block;
				background-repeat: no-repeat;
				background-position: 0px 0px;
				background-color: white;
				background-image: url(/img/item[tree].gif);
				padding-left: 20px;
			}
			.cTree div.lastitem
			{
				display: block;
				background-repeat: no-repeat;
				background-position: 0px 0px;
				background-image: url(/img/litem[tree].gif);
				padding-left: 20px;
				background-color: white;
			}
		</style>
		<div class="cTree">
			<div>
				<a style="font-family: Tahoma, Verdana, 'Times New Roman'; font-size: 10pt; color: #990099; font-weight: bold;" href="sitemap.php?add&pid=0">Карта сайта</a>
				<a href="sitemap.php?add&pid=0"><img style="width: 16px; height: 16px;" src="images/icons/plus.gif" alt="" title="Добавить ссылку" /></a>
			</div>
<?php
			dump_map(0, "\t\t\t");
			print "\t\t</div>";
		}
		include_once('footer.inc.php');
	}
?>