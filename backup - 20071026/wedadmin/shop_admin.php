<?php
	session_start();
	define('MAX_THUMB_SIZE', 70);
	require_once 'config.inc.php';
	require_once 'lib/mysql.lib.php';
	if(isset($_SESSION['WA_USER']) && db_open())
	{
		include_once 'header.inc.php';
?>
	<script type="text/javascript" language="javascript">
		function markAllRows(container_id, val)
		{
			var rows = document.getElementById(container_id).getElementsByTagName('tr');
			var unique_id;
			var checkbox;
			for(var i = 0; i < rows.length; i++ )
			{
				checkbox = rows[i].getElementsByTagName('input');
				for(var j = 0; j < checkbox.length; j++)
					if(checkbox[j] && checkbox[j].type == 'checkbox')
						checkbox[j].checked = val;
			}
			return true;
		}
	</script>
<?php
		$id = 0;
		$cid = 0;
		$cat_parent = 0;
		if(isset($_GET['id']))
		{
			$id = intval($_GET['id']);
			if($id)
			{
				if(($qr = mysql_query('SELECT `pid` FROM `wed_shop_items` WHERE `id`='.$id)) !== false && mysql_num_rows($qr))
					list($cid) = mysql_fetch_row($qr);
				else
				{
					print 'идентификатор товара задан неверно<br /><a href="shop_admin.php">в каталог</a>';
					include_once 'footer.inc.php';
					exit(0);
				}
			}
		}
		if(!$id && isset($_GET['cid']))
		{
			$cid = intval($_GET['cid']);
			if((($qr = mysql_query('SELECT `id`, `pid` FROM `wed_shop` WHERE `id`='.$cid)) !== false && mysql_num_rows($qr)))
				list(, $cat_parent) = mysql_fetch_row($qr);
			else if($cid)
			{
				print 'идентификатор раздела задан неверно<br /><a href="shop_admin.php">в каталог</a>';
				include_once 'footer.inc.php';
				exit(0);
			}
		}
		function get_wid($word)
		{
			$result = 0;
			if(($qr = mysql_query('SELECT `id` FROM `search_words` WHERE `word`="'.$word.'" LIMIT 1')) !== false && mysql_num_rows($qr))
				list($result) = mysql_fetch_row($qr);
			else if(mysql_query('INSERT INTO `search_words` SET `word`="'.$word.'"') !== false)
				$result = mysql_insert_id();
			return $result;
		}
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
			imagecopyresized($thumb, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
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
		function get_ext($str)
		{
			if(preg_match('/\.([^\.]+)$/ims', $str, $vals))
				return $vals[1];
			else
				return false;
		}
		function find_img($label)
		{
			if(file_exists('../icon/'.$label.'.jpg'))
				return 'jpg';
			else if(file_exists('../icon/'.$label.'.png'))
				return 'png';
			else if(file_exists('../icon/'.$label.'.gif'))
				return 'gif';
			return '';
		}
		function set_cat_image($id, $src, $ext, $mode = 2)
		{
			if(($qr = mysql_query('SELECT `label_id` FROM `wed_shop` WHERE `id`='.$id.' LIMIT 1')) !== false && mysql_num_rows($qr))
			{
				list($label) = mysql_fetch_row($qr);
				if($mode == 2)
				{
					if(is_uploaded_file($src))
						move_uploaded_file($src, '../icon/big_'.$label.'.'.$ext);
					else
						copy($src, '../icon/big_'.$label.'.'.$ext);
					small_image('../icon/big_'.$label.'.'.$ext, '../icon/small_'.$label.'.'.$ext);
					chmod('../icon/big_'.$label.'.'.$ext, 644);
					mysql_query('UPDATE `wed_shop` SET `img_big`="'.addslashes($ext).'", `img_small`="'.addslashes($ext).'" WHERE `id`='.$id);
				}
				else
				{
					if(is_uploaded_file($src))
						move_uploaded_file($src, '../icon/small_'.$label.'.'.$ext);
					else
						copy($src, '../icon/small_'.$label.'.'.$ext);
					chmod('../icon/small_'.$label.'.'.$ext, 644);
					mysql_query('UPDATE `wed_shop` SET `img_small`="'.addslashes($ext).'" WHERE `id`='.$id);
				}
			}
			else
				return false;
			return true;
		}
		function set_pos_image($id, $src, $ext, $mode = 2)
		{
			if(($qr = mysql_query('SELECT `label_id` FROM `wed_shop_items` WHERE `id`='.$id.' LIMIT 1')) !== false && mysql_num_rows($qr))
			{
				list($label) = mysql_fetch_row($qr);
				if($mode == 2)
				{
					if(is_uploaded_file($src))
						move_uploaded_file($src, '../icon/big_'.$label.'.'.$ext);
					else
						copy($src, '../icon/big_'.$label.'.'.$ext);
					small_image('../icon/big_'.$label.'.'.$ext, '../icon/small_'.$label.'.'.$ext);
					chmod('../icon/big_'.$label.'.'.$ext, 644);
					mysql_query('UPDATE `wed_shop_items` SET `img_big`="'.addslashes($ext).'", `img_small`="'.addslashes($ext).'" WHERE `id`='.$id);
				}
				else
				{
					if(is_uploaded_file($src))
						move_uploaded_file($src, '../icon/small_'.$label.'.'.$ext);
					else
						copy($src, '../icon/small_'.$label.'.'.$ext);
					chmod('../icon/small_'.$label.'.'.$ext, 644);
					mysql_query('UPDATE `wed_shop_items` SET `img_small`="'.addslashes($ext).'" WHERE `id`='.$id);
				}
			}
			else
				return false;
			return true;
		}
		function show_parents($cid)
		{
			$parents = array();
			while($cid)
				if(($qr = mysql_query('SELECT `id`, `pid`, `name` FROM `wed_shop` WHERE `id`='.$cid)) !== false && mysql_num_rows($qr))
				{
					$row = mysql_fetch_row($qr);
					$cid = $row[1];
					array_unshift($parents, $row);
				}
				else
					return;
			print "<a href=\"shop_admin.php\">Каталог</a>\r\n";
			foreach($parents as $val)
				print "&raquo;&nbsp;<a href=\"shop_admin.php?cid={$val[0]}\">{$val[2]}</a>\r\n";
			print "<br /><br />\r\n";
		}
		function print_fastjmp($pid, $tab = '&nbsp;&nbsp;&nbsp;&nbsp;')
		{
			global $tree, $cat_parent;
			foreach($tree[$pid][1] as $id)
			{
				$mod = '';
				if($id == $cat_parent)
					$mod = ' selected="selected"';
				print "\t\t\t\t\t\t\t<option{$mod} value=\"{$id}\">{$tab}{$tree[$id][0]}</option>\r\n";
				print_fastjmp($id, $tab.'&nbsp;&nbsp;&nbsp;');
			}
		}
		function print_subcat($pid, $tab = 0, $row = 0)
		{
			global $tree, $cid;
			foreach($tree[$pid][1] as $id)
			{
?>
			<tr class="r<?=$row % 2?>">
				<td>
					<input type="checkbox" name="sid[]" value="<?=$id?>" />
				</td>
				<td style="padding-left: <?=$tab?>px;">
					<a href="shop_admin.php?cid=<?=$id?>"><b><?=$tree[$id][0]?></b></a>
				</td>
				<td>
					<a href="shop_admin.php?cid=<?=$cid?>&amp;target=<?=$id?>&amp;up">
						<img alt="" title="поднять" src="images/upbl.gif" border="0" />
					</a>
					<a href="shop_admin.php?cid=<?=$cid?>&amp;target=<?=$id?>&amp;down">
						<img alt="" title="опустить" src="images/downbl.gif" border="0" />
					</a>
				</td>
			</tr>
<?php
				$row++;
				print_subcat($id, $tab + 20, &$row);
			}
		}
		function delete_cat($cid)
		{
			mysql_query('DELETE FROM `wed_shop` WHERE `id`='.$cid);
			mysql_query('DELETE FROM `wed_shop_items` WHERE `pid`='.$cid);
			if(($qr = mysql_query('SELECT `id` FROM `wed_shop` WHERE `pid`='.$cid)) !== false)
				while(($row = mysql_fetch_row($qr)) !== false)
					delete_cat($row[0]);
		}
		function r_val($data)
		{
			$result = 0;
			foreach($data as $val)
				if(strlen($val))
					$result++;
			return $result;
		}
		function parse_no($str)
		{
			$point = explode('.', $str);
			$result = array();
			foreach($point as $val)
				if(strlen($val))
					$result[] = intval($val);
			return $result;
		}
		function load_price($data, $root_id = 0)
		{
			$data = explode("\n", $data);
			for($i = 0; $i < count($data); $i++)
			{
				$data[$i] = explode("\t", $data[$i]);
				for($j = 0; $j < count($data[$i]); $j++)
				{
					$data[$i][$j] = trim($data[$i][$j]);
				}
			}
			$tree = array();
			$no = array();
			$result = array();
			$cp = array(0);
			foreach($data as $row)
				if(strlen($row[0]))
				{
					$tree[] = array($cp = parse_no($row[0]), implode('', array_slice($row, 1, 6)), $row[7]);
					$no[] = $cp;
				}
				else if(r_val($row) > 1)
					$result[] = array($cp, $row[1], $row[2], $row[3], $row[4], intval($row[5]), floatval(str_replace(',', '.', $row[6])), (isset($row[7]) && strlen($row[7]))?$row[7]:'');
			unset($data);
			$root = array();
			foreach($tree as $key => $val)
			{
				$order = array_pop($val[0]);
				if(array_search($val[0], $no) === false)
					$root[$key] = $order;
			}
			$order = 0;
			if(($qr = mysql_query('SELECT MAX(`order`) FROM `wed_shop` WHERE `pid`='.$root_id)) !== false)
				list($order) = mysql_fetch_row($qr);
			$order++;
			$parents = array();
			function price_tree($root, &$tree, &$parents, $pid, $order = 1)
			{
				foreach($root as $val)
				{
					$result[] = $tree[$val][1];
					if(mysql_query('INSERT INTO `wed_shop` SET `name`="'.addslashes($tree[$val][1]).'", `pid`='.intval($pid).', `order`='.intval($order)) !== false)
					{
						$sub = array();
						$parent = $tree[$val][0];
						$parents[$id = mysql_insert_id()] = $parent;
						foreach($tree as $key => $cmp_val)
						{
							$norder = array_pop($cmp_val[0]);
							if($cmp_val[0] == $parent)
								$sub[$key] = $norder;
						}
						asort($sub);
						price_tree(array_keys($sub), $tree, $parents, $id);
						$order++;
					}
				}
			}
			asort($root);
			price_tree(array_keys($root), $tree, $parents, $root_id, $order);
			$keys = array_keys($parents);
			$last = array($keys[0], $parents[$keys[0]]);
			$orders = array();
			foreach($result as $data)
			{
				$pid = false;
				if($data[0] == $last[1])
					$pid = $last[0];
				else if(($pid = array_search($data[0], $parents)) !== false)
					$last = array($pid, $data[0]);
				if($pid === false)
					$pid = $root_id;
				$order = 0;
				if(isset($orders[$pid]))
					$order = ++$orders[$pid];
				else if(($qr = mysql_query('SELECT MAX(`order`) FROM `wed_shop_items` WHERE `pid`='.$pid)) !== false)
				{
					list($order) = mysql_fetch_row($qr);
					$orders[$pid] = ++$order;
				}
				mysql_query("INSERT INTO `wed_shop_items` SET `pid`={$pid}, `name`='".addslashes($data[1])."', `label`='".addslashes($data[2])."', `description`='".addslashes($data[3])."', `comment`='".addslashes($data[4])."', `pack_num`={$data[5]}, `price`={$data[6]}, `label_id`='".addslashes($data[7])."', `order`={$order}");
			}
		}
		if($id)
		{
			if(isset($_POST['save'], $_POST['name'], $_POST['label'], $_POST['description'], $_POST['comment'], $_POST['pack_num'], $_POST['price'], $_POST['label_id'], $_POST['full_text']))
			{
				$ext_big = find_img('big_'.$_POST['label_id']);
				$ext_small = find_img('small_'.$_POST['label_id']);
				mysql_query('DELETE FROM `search_index` WHERE `sid`='.$id);
				mysql_query('UPDATE `wed_shop_items` SET `name`="'.addslashes($_POST['name']).'", `label`="'.addslashes($_POST['label']).'", `description`="'.addslashes($_POST['description']).'", `comment`="'.addslashes($_POST['comment']).'", `pack_num`='.intval($_POST['pack_num']).', `price`='.floatval($_POST['price']).', `label_id`="'.addslashes($_POST['label_id']).'", `full_text`="'.addslashes($_POST['full_text']).'", `soffer`='.(isset($_POST['soffer'])?'1':'0').', `img_big`="'.addslashes($ext_big).'", `img_small`="'.addslashes($ext_small).'" WHERE `id`='.$id);
				if(mysql_errno())
					print "<b>При попытке сохранения данных произошла ошибка</b><br /><br />";
				else
					print "<b>Данные успешно сохранены</b><br /><br />";
				if(($qr = mysql_query('SELECT `id`, CONCAT(`name`, " ", `label`, " ", `description`, " ", `comment`, " ", `label_id`, " ", `full_text`) FROM `wed_shop_items` WHERE `id`='.$id)) !== false)
					while(($row = mysql_fetch_row($qr)) !== false)
					{
						$lower = 'ёйцукенгшщзхъфывапролджэячсмитьбю';
						$upper = 'ЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ';
						$row[1] = strtolower($row[1]);
						$row[1] = strtr($row[1], $upper, $lower);
						preg_match_all('/([\wА-Яа-яёЁ]+)/ims', $row[1], $vals, PREG_PATTERN_ORDER);
						foreach($vals[1] as $val)
							mysql_query('INSERT INTO `search_index` SET `sid`='.$row[0].', `src`=0, `wid`='.get_wid(addslashes($val)));
					}
			}
			if(isset($_POST['moveto'], $_POST['pid']))
			{
				mysql_query('UPDATE `wed_shop_items` SET `pid`="'.intval($_POST['pid']).'" WHERE `id`='.$id);
				$cid = $cat_parent = intval($_POST['pid']);
				if(mysql_errno())
					print "<b>При попытке сохранения данных произошла ошибка</b><br /><br />";
				else
					print "<b>Данные успешно сохранены</b><br /><br />";
			}
			if(isset($_POST['setimg'], $_FILES['image']) && file_exists($_FILES['image']['tmp_name']))
				set_pos_image($id, $_FILES['image']['tmp_name'], get_ext($_FILES['image']['name']));
			if(isset($_GET['clearimg']) && ($qr = mysql_query('SELECT `label_id`, `img_big` FROM `wed_shop_items` WHERE `id`='.$id.' LIMIT 1')) !== false && mysql_num_rows($qr))
			{
				list($name, $ext) = mysql_fetch_row($qr);
				if(strlen($ext) && file_exists('../icon/big_'.$name.'.'.$ext))
					unlink('../icon/big_'.$name.'.'.$ext);
				mysql_query('UPDATE `wed_shop_items` SET `img_big`="" WHERE `id`='.$id);
			}
			if(isset($_POST['setimgsm'], $_FILES['imagesm']) && file_exists($_FILES['imagesm']['tmp_name']))
				set_pos_image($id, $_FILES['imagesm']['tmp_name'], get_ext($_FILES['imagesm']['name']), 1);
			if(isset($_GET['clearimgsm']) && ($qr = mysql_query('SELECT `label_id`, `img_small` FROM `wed_shop_items` WHERE `id`='.$id.' LIMIT 1')) !== false && mysql_num_rows($qr))
			{
				list($name, $ext) = mysql_fetch_row($qr);
				if(strlen($ext) && file_exists('../icon/small_'.$name.'.'.$ext))
					unlink('../icon/small_'.$name.'.'.$ext);
				mysql_query('UPDATE `wed_shop_items` SET `img_small`="" WHERE `id`='.$id);
			}
		}
		else
		{
			if($cid && isset($_GET['clearimg']) && ($qr = mysql_query('SELECT `label_id`, `img_big` FROM `wed_shop` WHERE `id`='.$cid.' LIMIT 1')) !== false && mysql_num_rows($qr))
			{
				list($name, $ext) = mysql_fetch_row($qr);
				if(strlen($ext) && file_exists('../icon/big_'.$name.'.'.$ext))
					unlink('../icon/big_'.$name.'.'.$ext);
				mysql_query('UPDATE `wed_shop` SET `img_big`="" WHERE `id`='.$cid);
			}
			if($cid && isset($_POST['setimg'], $_FILES['image']) && file_exists($_FILES['image']['tmp_name']))
				set_cat_image($cid, $_FILES['image']['tmp_name'], get_ext($_FILES['image']['name']));
			if($cid && isset($_GET['clearimgsm']) && ($qr = mysql_query('SELECT `label_id`, `img_small` FROM `wed_shop` WHERE `id`='.$cid.' LIMIT 1')) !== false && mysql_num_rows($qr))
			{
				list($name, $ext) = mysql_fetch_row($qr);
				if(strlen($ext) && file_exists('../icon/small_'.$name.'.'.$ext))
					unlink('../icon/small_'.$name.'.'.$ext);
				mysql_query('UPDATE `wed_shop` SET `img_small`="" WHERE `id`='.$cid);
			}
			if($cid && isset($_POST['setimgsm'], $_FILES['imagesm']) && file_exists($_FILES['imagesm']['tmp_name']))
				set_cat_image($cid, $_FILES['imagesm']['tmp_name'], get_ext($_FILES['imagesm']['name']), 1);
			if(isset($_GET['target'], $_GET['up']))
			{
				if(($qr = mysql_query('SELECT `pid`, `order` FROM `wed_shop` WHERE `id`='.intval($_GET['target']))) !== false)
				{
					list($pid, $order) = mysql_fetch_row($qr);
					if($order > 1)
					{
						$order--;
						mysql_query('UPDATE `wed_shop` SET `order`=`order`+1 WHERE `order`='.$order.' AND `pid`='.$pid);
						mysql_query('UPDATE `wed_shop` SET `order`=`order`-1 WHERE `id`='.intval($_GET['target']));
					}
				}
			}
			if(isset($_GET['target'], $_GET['down']))
			{
				if(($qr = mysql_query('SELECT `pid`, `order` FROM `wed_shop` WHERE `id`='.intval($_GET['target']))) !== false)
				{
					list($pid, $order) = mysql_fetch_row($qr);
					if(($qr = mysql_query('SELECT MAX(`order`) FROM `wed_shop` WHERE `pid`='.$pid)) !== false && ($max = mysql_fetch_row($qr)) !== false && $order < $max[0])
					{
						mysql_query('UPDATE `wed_shop` SET `order`=`order`-1 WHERE `order`='.($order + 1).' AND `pid`='.$pid);
						mysql_query('UPDATE `wed_shop` SET `order`=`order`+1 WHERE `id`='.intval($_GET['target']));
					}
				}
			}
			if(isset($_GET['target_item'], $_GET['up']))
			{
				if(($qr = mysql_query('SELECT `pid`, `order` FROM `wed_shop_items` WHERE `id`='.intval($_GET['target_item']))) !== false)
				{
					list($pid, $order) = mysql_fetch_row($qr);
					if($order > 1)
					{
						$order--;
						mysql_query('UPDATE `wed_shop_items` SET `order`=`order`+1 WHERE `order`='.$order.' AND `pid`='.$pid);
						mysql_query('UPDATE `wed_shop_items` SET `order`=`order`-1 WHERE `id`='.intval($_GET['target_item']));
					}
				}
			}
			if(isset($_GET['target_item'], $_GET['down']))
			{
				if(($qr = mysql_query('SELECT `pid`, `order` FROM `wed_shop_items` WHERE `id`='.intval($_GET['target_item']))) !== false)
				{
					list($pid, $order) = mysql_fetch_row($qr);
					if(($qr = mysql_query('SELECT MAX(`order`) FROM `wed_shop_items` WHERE `pid`='.$pid)) !== false && ($max = mysql_fetch_row($qr)) !== false && $order < $max[0])
					{
						mysql_query('UPDATE `wed_shop_items` SET `order`=`order`-1 WHERE `order`='.($order + 1).' AND `pid`='.$pid);
						mysql_query('UPDATE `wed_shop_items` SET `order`=`order`+1 WHERE `id`='.intval($_GET['target_item']));
					}
				}
			}
			if(isset($_GET['target_item'], $_GET['nospo']))
				mysql_query('UPDATE `wed_shop_items` SET `soffer`=0 WHERE `id`='.intval($_GET['target_item']));
			if($cid && isset($_POST['save'], $_POST['name'], $_POST['description'], $_POST['label_id']))
			{
				$ext_big = find_img('big_'.$_POST['label_id']);
				$ext_small = find_img('small_'.$_POST['label_id']);
				mysql_query('UPDATE `wed_shop` SET `name`="'.addslashes($_POST['name']).'", `description`="'.addslashes($_POST['description']).'", `label_id`="'.addslashes($_POST['label_id']).'", `img_big`="'.addslashes($ext_big).'", `img_small`="'.addslashes($ext_small).'" WHERE `id`='.$cid);
				if(mysql_errno())
					print "<b>При попытке сохранения данных произошла ошибка</b><br /><br />";
				else
					print "<b>Данные успешно сохранены</b><br /><br />";
			}
			if($cid && isset($_POST['moveto'], $_POST['dcid']))
			{
				mysql_query('UPDATE `wed_shop` SET `pid`="'.intval($_POST['dcid']).'" WHERE `id`='.$cid);
				$cat_parent = intval($_POST['dcid']);
				if(mysql_errno())
					print "<b>При попытке сохранения данных произошла ошибка</b><br /><br />";
				else
					print "<b>Данные успешно сохранены</b><br /><br />";
			}
			if(isset($_POST['addcat'], $_POST['name'], $_POST['description'], $_POST['pid']))
				if(($qr = mysql_query('SELECT MAX(`order`) FROM `wed_shop` WHERE `pid`='.intval($_POST['pid']))) !== false && mysql_num_rows($qr))
				{
					list($order) = mysql_fetch_row($qr);
					mysql_query('INSERT INTO `wed_shop` SET `pid`='.intval($_POST['pid']).', `name`="'.addslashes($_POST['name']).'", `description`="'.addslashes($_POST['description']).'", `order`='.intval($order + 1));
				}
			if(isset($_POST['additem'], $_POST['name'], $_POST['label'], $_POST['description'], $_POST['comment'], $_POST['pack_num'], $_POST['price'], $_POST['label_id'], $_POST['full_text'], $_POST['pid']) && ($qr = mysql_query('SELECT MAX(`order`) FROM `wed_shop_items` WHERE `pid`='.intval($_POST['pid']))) !== false)
			{
				list($order) = mysql_fetch_row($qr);
				$cid = intval($_POST['pid']);
				$ext = '';
				if(strlen($_POST['label_id']))
				{
					if(file_exists('../icon/'.$_POST['label_id'].'.jpg'))
						$ext = 'jpg';
					else if(file_exists('../icon/'.$_POST['label_id'].'.png'))
						$ext = 'png';
					else if(file_exists('../icon/'.$_POST['label_id'].'.gif'))
						$ext = 'gif';
				}
				mysql_query('INSERT INTO `wed_shop_items` SET `name`="'.addslashes($_POST['name']).'", `label`="'.addslashes($_POST['label']).'", `description`="'.addslashes($_POST['description']).'", `comment`="'.addslashes($_POST['comment']).'", `pack_num`='.intval($_POST['pack_num']).', `price`='.floatval($_POST['price']).', `label_id`="'.addslashes($_POST['label_id']).'", `full_text`="'.addslashes($_POST['full_text']).'", `img_small`="'.addslashes($ext).'", `order`='.intval($order + 1).', `soffer`='.(isset($_POST['soffer'])?'1':'0').', `pid`='.intval($_POST['pid']));
				if(($qr = mysql_query('SELECT `id`, CONCAT(`name`, " ", `label`, " ", `description`, " ", `comment`, " ", `label_id`, " ", `full_text`) FROM `wed_shop_items` WHERE `id`='.mysql_insert_id())) !== false)
					while(($row = mysql_fetch_row($qr)) !== false)
					{
						$lower = 'ёйцукенгшщзхъфывапролджэячсмитьбю';
						$upper = 'ЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ';
						$row[1] = strtolower($row[1]);
						$row[1] = strtr($row[1], $upper, $lower);
						preg_match_all('/([\wА-Яа-яёЁ]+)/ims', $row[1], $vals, PREG_PATTERN_ORDER);
						foreach($vals[1] as $val)
							mysql_query('INSERT INTO `search_index` SET `sid`='.$row[0].', `src`=0, `wid`='.get_wid(addslashes($val)));
					}
			}
			if(isset($_POST['upload'], $_POST['file']))
				load_price($_POST['file'], $cid);
			if(isset($_POST['action'], $_POST['sid']))
				switch($_POST['action'])
				{
					case 'update':
						foreach($_POST['sid'] as $key => $val)
							$_POST['sid'][$key] = intval($val);
						if(($qr = mysql_query('SELECT `id`, `label_id`, `img_big`, `img_small` FROM `wed_shop` WHERE `id` IN ('.implode(', ', $_POST['sid']).')')) !== false)
							while(($row = mysql_fetch_row($qr)) !== false)
							{
								$ext_big = find_img('big_'.$row[1]);
								$ext_small = find_img('small_'.$row[1]);
								if(strlen($row[2]) && strlen($row[1]) && !file_exists('../icon/big_'.$row[1].'.'.$row[2]))
									mysql_query('UPDATE `wed_shop` SET `img_big` = "'.addslashes($ext_big).'" WHERE `id`='.$row[0]);
								else if(strlen($row[1]) && !strlen($row[2]))
									mysql_query('UPDATE `wed_shop` SET `img_big` = "'.addslashes($ext_big).'" WHERE `id`='.$row[0]);
								if(strlen($row[3]) && strlen($row[1]) && !file_exists('../icon/small_'.$row[1].'.'.$row[3]))
									mysql_query('UPDATE `wed_shop` SET `img_small` = "'.addslashes($ext_small).'" WHERE `id`='.$row[0]);
								else if(strlen($row[1]) && !strlen($row[3]))
									mysql_query('UPDATE `wed_shop` SET `img_small` = "'.addslashes($ext_small).'" WHERE `id`='.$row[0]);
							}
						if(($qr = mysql_query('SELECT `id`, `label_id`, `img_big`, `img_small` FROM `wed_shop_items` WHERE `pid` IN ('.implode(', ', $_POST['sid']).')')) !== false)
							while(($row = mysql_fetch_row($qr)) !== false)
							{
								$ext_big = find_img('big_'.$row[1]);
								$ext_small = find_img('small_'.$row[1]);
								if(strlen($row[2]) && strlen($row[1]) && !file_exists('../icon/big_'.$row[1].'.'.$row[2]))
									mysql_query('UPDATE `wed_shop_items` SET `img_big`="'.addslashes($ext_big).'" WHERE `id`='.$row[0]);
								else if(strlen($row[1]) && !strlen($row[2]))
									mysql_query('UPDATE `wed_shop_items` SET `img_big`="'.addslashes($ext_big).'" WHERE `id`='.$row[0]);
								if(strlen($row[3]) && strlen($row[1]) && !file_exists('../icon/small_'.$row[1].'.'.$row[3]))
									mysql_query('UPDATE `wed_shop_items` SET `img_small`="'.addslashes($ext_small).'" WHERE `id`='.$row[0]);
								else if(strlen($row[1]) && !strlen($row[2]))
									mysql_query('UPDATE `wed_shop_items` SET `img_small`="'.addslashes($ext_small).'" WHERE `id`='.$row[0]);
							}
						break;
					case 'delete':
						foreach($_POST['sid'] as $c_id)
							delete_cat(intval($c_id));
				}
			if(isset($_POST['pos_action'], $_POST['sid']))
				switch($_POST['pos_action'])
				{
					case 'update':
						foreach($_POST['sid'] as $key => $val)
							$_POST['sid'][$key] = intval($val);
						if(($qr = mysql_query('SELECT `id`, `label_id`, `img_small` FROM `wed_shop_items` WHERE `id` IN ('.implode(', ', $_POST['sid']).')')) !== false)
							while(($row = mysql_fetch_row($qr)) !== false)
							{
								$ext = '';
								if(file_exists('../icon/'.$row[1].'.jpg'))
									$ext = 'jpg';
								else if(file_exists('../icon/'.$row[1].'.png'))
									$ext = 'png';
								else if(file_exists('../icon/'.$row[1].'.gif'))
									$ext = 'gif';
								if(strlen($row[2]) && strlen($row[1]) && !file_exists('../icon/'.$row[1].'.'.$row[2]))
									mysql_query('UPDATE `wed_shop_items` SET `img_small`="'.addslashes($ext).'" WHERE `id`='.$row[0]);
								else if(strlen($row[1]) && !strlen($row[2]))
									mysql_query('UPDATE `wed_shop_items` SET `img_small`="'.addslashes($ext).'" WHERE `id`='.$row[0]);
							}
						break;
					case 'delete':
						foreach($_POST['sid'] as $key => $val)
							$_POST['sid'][$key] = intval($val);
						if(count($_POST['sid']))
							mysql_query('DELETE FROM `wed_shop_items` WHERE `id` IN('.implode(', ', $_POST['sid']).')');
				}
		}
		show_parents($cid);
		if(!isset($_GET['fastjmp']))
			print "<a href=\"/?".($id?'id='.$id:'cid='.$cid)."\">Посмотреть эту страницу на сайте</a><br /><br />\r\n<a href=\"shop_admin.php?addcat&amp;cid={$cid}\">Создать категорию</a><br /><br />\r\n<a href=\"shop_admin.php?additem&amp;cid={$cid}\">Добавить товар</a><br /><br />\r\n";
		$tree = array(0 => array('', array()));
		$task = array(0);
		while(count($task))
		{
			$t_cid = array_pop($task);
			if(($qr = mysql_query('SELECT `id`, `name` FROM `wed_shop` WHERE `pid`='.$t_cid.' ORDER BY `order`')) !== false)
				while(($row = mysql_fetch_row($qr)) !== false)
				{
					$task[] = $row[0];
					$tree[$row[0]] = array($row[1], array());
					$tree[$t_cid][1][] = $row[0];
				}
		}
		if(isset($_GET['fastjmp']))
		{
?>
	<form action="shop_admin.php" method="get" id="fast_nav">
		<fieldset title="Быстрый переход" style="width: 400px;">
			<legend>Быстрый переход</legend>
			<table cellpadding="0" cellspacing="3" border="0" style="width: 100%;">
				<tr>
					<td style="width: 100px;">Позиция:</td>
					<td style="width: 60px;" align="left"><input type="text" name="id" style="width: 50px; height: 14px; line-height: 14px;" /></td>
					<td></td>
				</tr>
				<tr>
					<td style="width: 100px;">Категория:</td>
					<td style="width: 60px;" align="left"><input type="text" name="cid" style="width: 50px; height: 14px; line-height: 14px;" /></td>
					<td align="left"><input type="submit" value="Перейти" /></td>
				</tr>
				<tr>
					<td colspan="3">
						<select style="width: 100%; height: 20px; line-height: 20px;" onChange="document.location.replace('shop_admin.php?cid=' + this.value);">
							<option selected="selected">Выберите категорию...</option>
							<option value="0">&nbsp;Каталог</option>
							<? print_fastjmp(0); ?>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
	</form>
<?php
		}
		else if(isset($_GET['addcat']))
		{
			$cat_parent = $cid;
?>
	<form action="shop_admin.php?cid=<?=$cid?>" method="post" enctype="multipart/form-data">
		<fieldset style="width: 600px;">
			<legend>Категория</legend>
			<table cellpadding="0" cellspacing="3" border="0" style="width: 100%;">
				<tr>
					<td style="width: 150px;">Наименование:</td>
					<td align="left" style="width: 100%;"><input type="text" style="width: 450px; height: 14px; line-height: 14px;" name="name" /></td>
					<td><input type="submit" value="Создать" name="addcat" /></td>
				</tr>
				<tr>
					<td colspan="3">
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
									'description'
								];
								xinha_config = xinha_config ? xinha_config() : new HTMLArea.Config();
								xinha_config.width  = '580px';
								xinha_config.height = '500px';
								xinha_editors   = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);
								HTMLArea.startEditors(xinha_editors);
							}
							window.onload = xinha_init;
						</script>
						Описание:<br />
						<textarea style="width: 100%; height: 400px;" id="description" name="description"><?=$row[3]?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<hr />Месторасположение:
						<select name="pid" style="width: 488px; height: 20px; line-height: 20px;">
							<option value="0">&nbsp;Каталог</option>
							<? print_fastjmp(0); ?>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
	</form><br />
<?php
		}
		else if(isset($_GET['additem']))
		{
?>
	<form action="shop_admin.php?cid=<?=$cid?>" method="post" enctype="multipart/form-data">
		<fieldset style="width: 600px;">
			<legend>Позиция</legend>
			<table cellpadding="0" cellspacing="3" border="0" style="width: 100%;">
				<tr>
					<td style="width: 150px;">Наименование</td>
					<td style="width: 100%;"><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="name" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Описание</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="label" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Доп. описание</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="description" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Комментарии</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="comment" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Кол-во в упаковке</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="pack_num" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Цена</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="price" /></td>
				</tr>
				<tr>
					<td colspan="2">
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
									'full_text'
								];
								xinha_config = xinha_config ? xinha_config() : new HTMLArea.Config();
								xinha_config.width  = '580px';
								xinha_config.height = '500px';
								xinha_editors   = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);
								HTMLArea.startEditors(xinha_editors);
							}
							window.onload = xinha_init;
						</script>
						Полное описание:<br />
						<textarea id="full_text" name="full_text" style="width: 580px; height: 500px;"></textarea>
					</td>
				</tr>
				<tr>
					<td style="width: 150px;">Идентификатор</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="label_id" /></td>
				</tr>
				<tr>
					<td colspan="2">
						Месторасположение:
						<select name="pid" style="width: 430px; height: 20px; line-height: 20px;">
							<option value="0">&nbsp;Каталог</option>
							<? $cat_parent = $cid; print_fastjmp(0); ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input type="checkbox" name="soffer" />&nbsp;&nbsp;спецпредложение</td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input type="submit" value="Добавить" name="additem" /></td>
				</tr>
			</table>
		</fieldset>
	</form><br />
<?php
		}
		else if(isset($_GET['SPO']))
		{
			if(($qr = mysql_query('SELECT `id`, `name`, `label`, `price` FROM `wed_shop_items` WHERE `soffer`<>0 ORDER BY `order`')) !== false && mysql_num_rows($qr))
			{
?>
	<h3>Позиции</h3><br /><br />
	<table cellpadding="3" cellspacing="0" border="0" class="catalogue">
		<tr class="th">
			<td>
				Наименование
			</td>
			<td>
				Описание
			</td>
			<td>
				Цена
			</td>
			<td>
				Действия
			</td>
		</tr>
<?php
				while(($row = mysql_fetch_row($qr)) !== false)
				{
?>
		<tr>
			<td>
				<a href="shop_admin.php?id=<?=$row[0]?>"><?=$row[1]?></a>
			</td>
			<td>
				<a href="shop_admin.php?id=<?=$row[0]?>"><?=$row[2]?></a>
			</td>
			<td>
				<a href="shop_admin.php?id=<?=$row[0]?>"><?=sprintf('%10.1f', $row[3])?></a>
			</td>
			<td>
				<a href="shop_admin.php?target_item=<?=$row[0]?>&amp;nospo&amp;SPO">
					<img alt="" title="Удалить СПО" src="images/deleteor.gif" border="0" />
				</a>
			</td>
		</tr>
<?php
				}
?>
	</table>
<?php
			}
		}
		else if($id)
		{
			if(($qr = mysql_query('SELECT `name`, `label`, `description`, `comment`, `pack_num`, `price`, `pid`, `label_id`, `img_small`, `full_text`, `soffer`, `img_big` FROM `wed_shop_items` WHERE `id`='.$id)) !== false)
			{
				$row = mysql_fetch_row($qr);
?>
	<form action="shop_admin.php?id=<?=$id?>" method="post" enctype="multipart/form-data">
		<fieldset style="width: 600px;">
			<legend>Позиция</legend>
			<table cellpadding="0" cellspacing="3" border="0" style="width: 100%;">
				<tr>
					<td style="width: 150px;">Наименование</td>
					<td style="width: 100%;"><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="name" value="<?=$row[0]?>" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Описание</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="label" value="<?=$row[1]?>" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Доп. описание</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="description" value="<?=$row[2]?>" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Комментарии</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="comment" value="<?=$row[3]?>" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Кол-во в упаковке</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="pack_num" value="<?=$row[4]?>" /></td>
				</tr>
				<tr>
					<td style="width: 150px;">Цена</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="price" value="<?=$row[5]?>" /></td>
				</tr>
				<tr>
					<td colspan="2">
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
									'full_text'
								];
								xinha_config = xinha_config ? xinha_config() : new HTMLArea.Config();
								xinha_config.width  = '580px';
								xinha_config.height = '500px';
								xinha_editors   = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);
								HTMLArea.startEditors(xinha_editors);
							}
							window.onload = xinha_init;
						</script>
						Полное описание:<br />
						<textarea id="full_text" name="full_text" style="width: 580px; height: 500px;"><?=$row[9]?></textarea>
					</td>
				</tr>
				<tr>
					<td style="width: 150px;">Идентификатор</td>
					<td><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="label_id" value="<?=$row[7]?>" /></td>
				</tr>
				<? if(strlen($row[11]) && file_exists('../icon/big_'.$row[7].'.'.$row[11])){ ?>
				<tr valign="top">
					<td style="width: 150px;">Большое изображение</td>
					<td>
						<img border="0" src="/icon/big_<?=$row[7]?>.<?=$row[11]?>" alt="" title="" />
						<a href="shop_admin.php?clearimg&amp;id=<?=$id?>">
							<img src="images/deleteor.gif" border="0" onClick="javascript: if (confirm('Удалить?')) { return true;} else { return false;}" alt="Удалить изображение" title="Удалить изображение" />
						</a>
					</td>
				</tr>
				<? }else if(strlen($row[7])){ ?>
				<tr>
					<td colspan="2" style="height: 50px;"><input type="file" name="image" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="setimg" value="Загрузить большое изображение" /></td>
				</tr>
				<? } ?>
				<? if(strlen($row[8]) && file_exists('../icon/small_'.$row[7].'.'.$row[8])){ ?>
				<tr valign="top">
					<td style="width: 150px;">Маленькое изображение</td>
					<td>
						<img border="0" src="/icon/small_<?=$row[7]?>.<?=$row[8]?>" alt="" title="" />
						<a href="shop_admin.php?clearimgsm&amp;id=<?=$id?>">
							<img src="images/deleteor.gif" border="0" onClick="javascript: if (confirm('Удалить?')) { return true;} else { return false;}" alt="Удалить изображение" title="Удалить изображение" />
						</a>
					</td>
				</tr>
				<? }else if(strlen($row[7])){ ?>
				<tr>
					<td colspan="2" style="height: 50px;"><input type="file" name="imagesm" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="setimgsm" value="Загрузить маленькое изображение" /></td>
				</tr>
				<? } ?>
				<tr>
					<td colspan="2" align="right"><input type="checkbox" name="soffer"<?=$row[10]?' checked="checked"':''?> />&nbsp;&nbsp;спецпредложение</td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input type="submit" value="Сохранить" name="save" /></td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />Месторасположение:
						<select name="pid" style="width: 430px; height: 20px; line-height: 20px;">
							<option value="0">&nbsp;Каталог</option>
							<? $cat_parent = $row[6]; print_fastjmp(0); ?>
						</select>
						<input type="submit" name="moveto" value="Переместить" style="width: 110px;" />
					</td>
				</tr>
			</table>
		</fieldset>
	</form>
<?php
			}
		}
		else
		{
			if(($qr = mysql_query('SELECT `name`, `label_id`, `img_small`, `description`, `img_big` FROM `wed_shop` WHERE `id`='.$cid)) !== false && mysql_num_rows($qr))
			{
				$row = mysql_fetch_row($qr);
?>
	<form action="shop_admin.php?cid=<?=$cid?>" method="post" enctype="multipart/form-data">
		<fieldset style="width: 600px;">
			<legend>Категория</legend>
			<table cellpadding="0" cellspacing="3" border="0" style="width: 100%;">
				<tr>
					<td style="width: 150px;">Наименование:</td>
					<td align="left" style="width: 100%;"><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="name" value="<?=$row[0]?>" /></td>
				</tr>
				<tr>
					<td colspan="2">
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
									'description'
								];
								xinha_config = xinha_config ? xinha_config() : new HTMLArea.Config();
								xinha_config.width  = '580px';
								xinha_config.height = '500px';
								xinha_editors   = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);
								HTMLArea.startEditors(xinha_editors);
							}
							window.onload = xinha_init;
						</script>
						Описание:<br />
						<textarea style="width: 100%; height: 400px;" id="description" name="description"><?=$row[3]?></textarea>
					</td>
				</tr>
				<tr>
					<td style="width: 150px;">Идентификатор:</td>
					<td align="left" style="width: 100%;"><input type="text" style="width: 99%; height: 14px; line-height: 14px;" name="label_id" value="<?=$row[1]?>" /></td>
				</tr>
				<? if(strlen($row[4]) && file_exists('../icon/big_'.$row[1].'.'.$row[4])){ ?>
				<tr valign="top">
					<td>Большое изображение:</td>
					<td>
						<img src="/icon/big_<?=$row[1]?>.<?=$row[4]?>" alt="" title="" border="0" />
						<a href="shop_admin.php?clearimg&amp;cid=<?=$cid?>">
							<img src="images/deleteor.gif" border="0" onClick="javascript: if (confirm('Удалить?')) { return true;} else { return false;}" alt="Удалить изображение" title="Удалить изображение" />
						</a>
					</td>
				</tr>
				<? }else if(strlen($row[1])){ ?>
				<tr>
					<td colspan="2" style="height: 50px;"><input type="file" name="image" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="setimg" value="Загрузить большое изображение" /></td>
				</tr>
				<? } ?>
				<? if(strlen($row[2]) && file_exists('../icon/small_'.$row[1].'.'.$row[2])){ ?>
				<tr valign="top">
					<td>Маленькое изображение:</td>
					<td>
						<img src="/icon/small_<?=$row[1]?>.<?=$row[2]?>" alt="" title="" border="0" />
						<a href="shop_admin.php?clearimgsm&amp;cid=<?=$cid?>">
							<img src="images/deleteor.gif" border="0" onClick="javascript: if (confirm('Удалить?')) { return true;} else { return false;}" alt="Удалить изображение" title="Удалить изображение" />
						</a>
					</td>
				</tr>
				<? }else if(strlen($row[1])){ ?>
				<tr>
					<td colspan="2" style="height: 50px;"><input type="file" name="imagesm" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="setimgsm" value="Загрузить маленькое изображение" /></td>
				</tr>
				<? } ?>
				<tr>
					<td colspan="2" align="right">
						<input type="submit" value="Сохранить" name="save" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />Месторасположение:
						<select name="dcid" style="width: 388px; height: 20px; line-height: 20px;">
							<option value="0">&nbsp;Каталог</option>
							<? print_fastjmp(0); ?>
						</select>
						<input type="submit" name="moveto" value="Переместить" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />
						<table>
							<tr>
								<td>
									Загрузить прайс в текущий каталог:<br /><br />
									выделите блок из excel, скопируйте и вставьте в окно<br /><br />
									№&nbsp;|&nbsp;Наименование&nbsp;|&nbsp;Маркировка&nbsp;/&nbsp;тип&nbsp;|&nbsp;Описание&nbsp;|&nbsp;Примечание&nbsp;|&nbsp;Упаковка&nbsp;|&nbsp;Цена&nbsp;|&nbsp;Идентификатор<br /><br />
									<textarea style="width: 650px; height: 150px;" name="file"></textarea>
								</td>
							</tr>
							<tr>
								<td align="right">
									<input type="submit" name="upload" value="Загрузить" />
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
	</form><br />
<?php
			}
			else
			{
?>
	<form action="shop_admin.php?cid=0" method="post" enctype="multipart/form-data">
		<table>
			<tr>
				<td>
					<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 14px;">Загрузить прайс в каталог:</font><br />
					<textarea style="width: 580px; height: 150px;" name="file"></textarea>
				</td>
			</tr>
			<tr>
				<td align="right">
					<input type="submit" name="upload" value="Загрузить прайс" />
				</td>
			</tr>
		</table>
	</form>
<?php
			}
			if(count($tree[$cid][1]))
			{
?>
	<h3>Подкаталоги</h3><br /><br />
	<form action="shop_admin.php?cid=<?=$cid?>" method="post" enctype="multipart/form-data" id="categories">
	<table cellpadding="3" cellspacing="0" border="0" class="catalogue">
		<tr class="th">
			<td>
				<input type="checkbox" onClick="markAllRows('categories', this.checked);" />
			</td>
			<td>
				Название
			</td>
			<td>
				Действия
			</td>
		</tr>
<?php
				print_subcat($cid);
?>
	</table>
	<font style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px;">с отмеченными:</font>
	<select style="width: 200px;" name="action">
		<option value="update">Обновить изображения</option>
		<option selected="selected" value="delete">Удалить</option>
	</select>
	<input type="submit" value="Выполнить" /><br /><br />
	</form><br />
<?php
			}
			if(($qr = mysql_query('SELECT `id`, `name`, `label`, `price` FROM `wed_shop_items` WHERE `pid`='.$cid.' ORDER BY `order`')) !== false && mysql_num_rows($qr))
			{
?>
	<h3>Позиции</h3><br /><br />
	<form action="shop_admin.php?cid=<?=$cid?>" method="post" enctype="multipart/form-data" id="items">
	<font style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px;">с отмеченными:</font>
	<select style="width: 200px;" name="pos_action">
		<option value="update">Обновить изображения</option>
		<option selected="selected" value="delete">Удалить</option>
	</select>
	<input type="submit" value="Выполнить" /><br /><br />
	<table cellpadding="3" cellspacing="0" border="0" class="catalogue">
		<tr class="th">
			<td>
				<input type="checkbox" onClick="markAllRows('items', this.checked);" />
			</td>
			<td>
				Наименование
			</td>
			<td>
				Описание
			</td>
			<td>
				Цена
			</td>
			<td>
				Действия
			</td>
		</tr>
<?php
				while(($row = mysql_fetch_row($qr)) !== false)
				{
?>
		<tr>
			<td>
				<input type="checkbox" name="sid[]" value="<?=$row[0]?>" />
			</td>
			<td>
				<a href="shop_admin.php?id=<?=$row[0]?>"><?=$row[1]?></a>
			</td>
			<td>
				<a href="shop_admin.php?id=<?=$row[0]?>"><?=$row[2]?></a>
			</td>
			<td>
				<a href="shop_admin.php?id=<?=$row[0]?>"><?=sprintf('%10.1f', $row[3])?></a>
			</td>
			<td>
				<a href="shop_admin.php?cid=<?=$cid?>&amp;target_item=<?=$row[0]?>&amp;up">
					<img alt="" title="поднять" src="images/upbl.gif" border="0" />
				</a>
				<a href="shop_admin.php?cid=<?=$cid?>&amp;target_item=<?=$row[0]?>&amp;down">
					<img alt="" title="опустить" src="images/downbl.gif" border="0" />
				</a>
			</td>
		</tr>
<?php
				}
?>
	</table>
<?php
			}
		}
		include_once 'footer.inc.php';
	}
	else
	{
		require_once('index.php');
		exit();
	}
?>