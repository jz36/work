<?php
	session_start();
	error_reporting(~E_ALL);
	require 'config.inc.php';
	require 'lib/mysql.lib.php';
	if(isset($_SESSION['WA_USER']) && db_open())
	{
		// create / save doc
		function save_doc($id)
		{
			if(isset($_POST['pid'], $_POST['type'], $_POST['name']))
			{
				$pid = intval($_POST['pid']);
				$type = intval($_POST['type']);
				$name = $_POST['name'];
				if($id && ($qr = mysql_query('SELECT `type` FROM `wed_docs` WHERE `id`='.$id)) !== false && mysql_num_rows($qr))
				{
					list($type) = mysql_fetch_row($qr);
					switch($type)
					{
						case 1:
							mysql_query('UPDATE `wed_docs` SET `pid`='.$pid.', `name`="'.$name.'", `ext`="" WHERE `id`='.$id);
							return;
						case 2:
							if(isset($_POST['text']) && ($f = fopen("../info/{$id}.dat", 'wb')) !== false)
							{
								fwrite($f, $_POST['text']);
								fclose($f);
								mysql_query('UPDATE `wed_docs` SET `pid`='.$pid.', `name`="'.$name.'", `ext`="html" WHERE `id`='.$id);
							}
							return;
						case 3:
							if(isset($_FILES['file']['tmp_name'], $_FILES['file']['name']) && file_exists($_FILES['file']['tmp_name']))
							{
								move_uploaded_file($_FILES['file']['tmp_name'], "../info/{$id}.dat");
								$ext = '';
								if(preg_match('/\.([^\.]+)$/ims', $_FILES['file']['name'], $vals))
									if(strlen($vals[1]) < 6)
										$ext = addslashes($vals[1]);
								mysql_query('UPDATE `wed_docs` SET `pid`='.$pid.', `name`="'.$name.'", `ext`="'.$ext.'" WHERE `id`='.$id);
							}
							else
								mysql_query('UPDATE `wed_docs` SET `pid`='.$pid.', `name`="'.$name.'" WHERE `id`='.$id);
							return;
					}
				}
				else switch($type)
				{
					case 1:
						mysql_query('INSERT INTO `wed_docs` SET `pid`='.$pid.', `type`=1, `name`="'.$name.'", `ext`=""');
						return;
					case 2:
						if(mysql_query('INSERT INTO `wed_docs` SET `pid`='.$pid.', `type`=2, `name`="'.$name.'", `ext`="html"'))
						{
							$id = mysql_insert_id();
							if(isset($_POST['text']) && ($f = fopen("../info/{$id}.dat", 'wb')) !== false)
							{
								fwrite($f, $_POST['text']);
								fclose($f);
							}
						}
						return;
					case 3:
						if(isset($_FILES['file']['tmp_name'], $_FILES['file']['name']) && file_exists($_FILES['file']['tmp_name']))
						{
							$ext = '';
							if(preg_match('/\.([^\.]+)$/ims', $_FILES['file']['name'], $vals))
								if(strlen($vals[1]) < 6)
									$ext = addslashes($vals[1]);
							if(mysql_query('INSERT INTO `wed_docs` SET `pid`='.$pid.', `type`=3, `name`="'.$name.'", `ext`="'.$ext.'"'))
								move_uploaded_file($_FILES['file']['tmp_name'], '../info/'.mysql_insert_id().'.dat');
						}
						return;
				}
			}
		}
		// create / edit doc form
		function edit_doc($id, $pid, $type = 0)
		{
			$name = $ext = '';
			include 'header.inc.php';
			if($id && ($qr = mysql_query('SELECT `pid`, `type`, `name`, `ext` FROM `wed_docs` WHERE `id`='.$id)) !== false && mysql_num_rows($qr))
				list($pid, $type, $name, $ext) = mysql_fetch_row($qr);
			else
				$id = 0;
			$t_pid = $pid;
			$parents = array();
			while($t_pid)
				if(($qr = mysql_query('SELECT `pid`, `name` FROM `wed_docs` WHERE `id`='.$t_pid)) !== false && mysql_num_rows($qr))
				{
					$row = mysql_fetch_row($qr);
					$parents[] = array($t_pid, $row[1]);
					$t_pid = $row[0];
				}
				else
					break;
			print "<a class=\"domain\" href=".basename(__FILE__).">Документы</a>\r\n";
			foreach(array_reverse($parents) as $parent)
				print " &raquo; <a class=\"domain\" href=\"".basename(__FILE__)."?id={$parent[0]}\">{$parent[1]}</a>\r\n";
			if($id)
				print " &raquo; <a class=\"domain\" href=\"".basename(__FILE__)."?id={$id}\">{$name}</a>\r\n";
			else
				print "<br /><br />\r\n";
			switch($type)
			{
				case 2:  // html document
?>
	<form action="<?=basename(__FILE__).'?id='.$id?>" method="post" enctype="multipart/form-data">
		<table style="width: 500px;" border="0" cellpadding="0" cellspacing="10" align="center">
			<tr>
				<td>Название:</td>
				<td style="width: 100%;">
					<input type="text" style="width: 99%;" name="name" value="<?=$name?>" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Текст:<br />
					<textarea style="width: 480px; height: 480px;" name="text" id="text"><?=$id?file_get_contents("../info/{$id}.dat"):''?></textarea>
					<script type="text/javascript">
						_editor_url  = "/wedadmin/xinha/";
						_editor_lang = "en";
						_editor_skin = "";
					</script>
					<script type="text/javascript" src="/wedadmin/xinha/htmlarea.js"></script>
					<script type="text/javascript">
						xinha_editors = null;
						xinha_init    = null;
						xinha_config  = null;
						xinha_plugins = null;
						xinha_init = xinha_init ? xinha_init : function()
						{
							xinha_plugins = xinha_plugins ? xinha_plugins :
							[
								'CharacterMap',
								'ContextMenu',
								'FullScreen',
								'ListType',
								'Stylist',
								'SuperClean',
								'TableOperations'
							];
							if(!HTMLArea.loadPlugins(xinha_plugins, xinha_init))
								return;
							xinha_editors = xinha_editors ? xinha_editors :
							[
								'text'
							];
							xinha_config = xinha_config ? xinha_config() : new HTMLArea.Config();
							xinha_config.width  = '480px';
							xinha_config.height = '500px';
							xinha_config.stylistLoadStylesheet('../css/style.css');
							xinha_editors = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);
							HTMLArea.startEditors(xinha_editors);
						}
						window.onload = xinha_init;
					</script>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="hidden" name="type" value="2" />
					<input type="hidden" name="pid" value="<?=$pid?>" />
					<input type="submit" name="save" value="<?=$id?'Сохранить':'Создать'?>" />
				</td>
			</tr>
		</table>
	</form>
<?php
					break;
				case 3:  // file
?>
	<form action="<?=basename(__FILE__).'?id='.$id?>" method="post" enctype="multipart/form-data">
		<table style="width: 500px;" border="0" cellpadding="0" cellspacing="10" align="center">
			<tr>
				<td>Название:</td>
				<td style="width: 100%;">
					<input type="text" style="width: 99%;" name="name" value="<?=$name?>" />
				</td>
			</tr>
			<tr>
				<td>Файл:</td>
				<td>
					<input type="file" name="file" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="hidden" name="type" value="3" />
					<input type="hidden" name="pid" value="<?=$pid?>" />
					<input type="submit" name="save" value="<?=$id?'Сохранить':'Загрузить'?>" />
				</td>
			</tr>
		</table>
	</form>
<?php
					break;
				case 1:  // category
?>
	<form action="<?=basename(__FILE__).'?id='.$id?>" method="post" enctype="multipart/form-data">
		<table style="width: 500px;" border="0" cellpadding="0" cellspacing="10" align="center">
			<tr>
				<td>Название:</td>
				<td style="width: 100%;">
					<input type="text" style="width: 99%;" name="name" value="<?=$name?>" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="hidden" name="type" value="1" />
					<input type="hidden" name="pid" value="<?=$pid?>" />
					<input type="submit" name="save" value="<?=$id?'Сохранить':'Создать'?>" />
				</td>
			</tr>
		</table>
	</form>
<?php
					if(!$id)
						break;
				default: // list category (by default - root)
?>
	<table>
	<tr>
	<td>
	<h3 style="display: block;">Категории</h3>
	<table style="width: 500px;" border="0" cellpadding="3" cellspacing="0" class="catalogue" align="left">
		<tr class="th">
			<td>Название</td>
			<td>Тип</td>
			<td>Действия</td>
		</tr>
<?php
		$script = basename(__FILE__);
		if(($qr = mysql_query('SELECT `id`, `type`, `name`, `ext` FROM `wed_docs` WHERE `type`=1 AND `pid`='.$id)) !== false)
		{
			if(mysql_num_rows($qr))
			{
				$i = 0;
				while(($row = mysql_fetch_row($qr)) !== false)
				{
					$type = '&lt;unknown&gt;';
					switch($row[1])
					{
						case 1:
							$type = 'Категория';
							break;
						case 2:
							$type = 'HTML-документ';
							break;
						case 3:
							$type = 'Файл';
							if(strlen($row[3]))
								 $type .= '('.$row[3].')';
							break;
					}
?>
		<tr class="r<?=$i?>">
			<td><a href="<?=$script.'?id='.$row[0]?>"><?=$row[2]?></a></td>
			<td><?=$type?></td>
			<td>
				<a href="<?=$script.'?delete&amp;target='.intval($row[0]).'&amp;id='.$id?>"><img src="images/deleteor.gif" border="0" alt="" /></a>
			</td>
		</tr>
<?php
					$i = ($i + 1) % 2;
				}
			}
			else
			{
?>
		<tr class="r0">
			<td colspan="3" align="center">нет</td>
		</tr>
<?php
			}
		}
?>
	</table>
	</td>
	</tr>
	<tr>
	<td>
	<h3 style="display: block;">Документы</h3>
	<table style="width: 500px;" border="0" cellpadding="3" cellspacing="0" class="catalogue" align="left">
		<tr class="th">
			<td>Название</td>
			<td>Тип</td>
			<td>Действия</td>
		</tr>
<?php
		$script = basename(__FILE__);
		if(($qr = mysql_query('SELECT `id`, `type`, `name`, `ext` FROM `wed_docs` WHERE `type`<>1 AND `pid`='.$id)) !== false)
		{
			if(mysql_num_rows($qr))
			{
				$i = 0;
				while(($row = mysql_fetch_row($qr)) !== false)
				{
					$type = '&lt;unknown&gt;';
					switch($row[1])
					{
						case 1:
							$type = 'Категория';
							break;
						case 2:
							$type = 'HTML-документ';
							break;
						case 3:
							$type = 'Файл';
							if(strlen($row[3]))
								 $type .= '('.$row[3].')';
							break;
					}
?>
		<tr class="r<?=$i?>">
			<td><a href="<?=$script.'?id='.$row[0]?>"><?=$row[2]?></a></td>
			<td><?=$type?></td>
			<td>
				<a href="<?=$script.'?delete&amp;target='.intval($row[0]).'&amp;id='.$id?>"><img src="images/deleteor.gif" border="0" alt="" /></a>
			</td>
		</tr>
<?php
					$i = ($i + 1) % 2;
				}
			}
			else
			{
?>
		<tr class="r0">
			<td colspan="3" align="center">нет</td>
		</tr>
<?php
			}
		}
?>
	</table></td></tr></table><br /><br />
	<a class="domain" href="<?=$script.'?create&amp;type=1&amp;pid='.$id?>">Создать категорию</a><br />
	<a class="domain" href="<?=$script.'?create&amp;type=2&amp;pid='.$id?>">Создать HTML-документ</a><br />
	<a class="domain" href="<?=$script.'?create&amp;type=3&amp;pid='.$id?>">Загрузить файл</a><br />
<?php
			}
			include 'footer.inc.php';
		}
		// delete doc
		function delete_doc($id)
		{
			$task = array($id);
			$to_delete = array($id);
			while(count($task))
			{
				if(($qr = mysql_query('SELECT `id` FROM `wed_docs` WHERE `pid`='.array_pop($task))) !== false)
					while(($row = mysql_fetch_row($qr)) !== false)
					{
						$task[] = $row[0];
						$to_delete[] = $row[0];
					}
			}
			mysql_query('DELETE FROM `wed_docs` WHERE `id` IN ('.implode(', ', $to_delete).')');
			foreach($to_delete as $id)
				if(file_exists("../info/{$id}.dat"))
					unlink("../info/{$id}.dat");
		}
		
		//---[ DOCUMENTS ADMIN ]---//
		$id = 0;
		if(isset($_GET['id']))
			$id = intval($_GET['id']);
		if(isset($_POST['save'], $_POST['pid']))
		{
			save_doc($id);
			edit_doc(intval($_POST['pid']));
		}
		else if(isset($_GET['delete'], $_GET['target']))
		{
			delete_doc(intval($_GET['target']));
			edit_doc($id);
		}
		else if(isset($_GET['create'], $_GET['pid'], $_GET['type']))
			edit_doc(0, intval($_GET['pid']), intval($_GET['type']));
		else
			edit_doc($id);
	}
?>