<?php
	session_start();
	error_reporting(0);
	include_once('../wedadmin/config.inc.php');
	include_once('../wedadmin/lib/mysql.lib.php');
	
	function print_split($text, $link = '', $width = 600)
	{
		print "<table cellspacing=\"0\" cellpadding=\"0\" class=\"doc\" style=\"width: $width;\">\r\n\t<tr>\r\n";
		$text = explode('__', $text);
		if(count($text))
			$width = round($width / count($text) - 15);
		if(strlen($link = trim($link)))
			for($i = 0; $i < count($text); $i++)
			{
				$mod = '';
				if($i == count($text) - 1)
					$mod = ' align="right"';
				print "\t\t<td{$mod}><a style=\"width: $width;\" href=\"$link\" onfocus=\"blur();\">{$text[$i]}</a></td>\r\n";
			}
		else
			for($i = 0; $i < count($text); $i++)
			{
				$mod = '';
				if($i == count($text) - 1)
					$mod = ' align="right"';
				print "\t\t<td{$mod}><div style=\"width: $width;\">{$text[$i]}</div></td>\r\n";
			}
		print "\t</tr>\r\n</table>\r\n";
	}
	
	function dual_view($items)
	{
		$keys = array_keys($items);
		for($i = 0; $i < count($keys); $i++)
		{
			print "<table cellspacing=\"0\" cellpadding=\"0\">\r\n\t<tr>\r\n\t\t<td style=\"width: 300px; padding-right: 5px;\">\r\n";
			list($text, $link) = $items[$keys[$i++]];
			print_split($text, $link, 295);
			print "\t\t</td>\r\n\t\t<td style=\"width: 300px; padding-left: 5px;\">\r\n";
			if($i < count($keys))
			{
				list($text, $link) = $items[$keys[$i]];
				print_split($text, $link, 295);
			}
			print "\t\t</td>\r\n\t</tr>\r\n</table>\r\n";
		}
	}
	
	if(db_open())
	{
		if(isset($_GET['cid']) && ($qr = mysql_query('SELECT `name` FROM `wed_docs` WHERE `id`='.intval($_GET['cid']).';')) !== false)
		{
			include_once('../header.inc.php');
			list($title) = mysql_fetch_row($qr);
			list($title) = explode('__', $title);
			$rows = array();
			$parent = array();
			$cid = intval($_GET['cid']);
			while($cid)
				if(($qr = mysql_query('SELECT `pid` FROM `wed_docs` WHERE `id`='.$cid)) !== false && mysql_num_rows($qr))
				{
					$parent[] = $cid;
					list($cid) = mysql_fetch_row($qr);
				}
				else
					break;
			if(($qr = mysql_query('SELECT `id`, `name` FROM `wed_docs` WHERE `pid`='.intval($_GET['cid']).' ORDER BY `order` DESC;')) !== false)
				while(($item = mysql_fetch_row($qr)) !== false)
				{
					if(in_array(25, $parent))
					{
						$count = 0;
						$ids = array($item[0]);
						for($i = 0; $i < count($ids); $i++)
							if(($qr1 = mysql_query('SELECT `id` FROM `wed_docs` WHERE `pid`='.$ids[$i])) !== false)
								while(($row = mysql_fetch_row($qr1)) !== false)
									$ids[] = $row[0];
						if(($qr1 = mysql_query('SELECT COUNT(*) FROM `wed_docs_items` WHERE `pid` IN ('.implode(', ', $ids).')')) !== false)
							list($count) = mysql_fetch_row($qr1);
						if($count == 1)
							$word = 'спецпредложение';
						else if(in_array($count, array(2, 3, 4)))
							$word = 'спецпредложения';
						else
							$word = 'спецпредложений';
						$item[1] .= '__'.$count.' '.$word;
					}
					$rows[] = array($item[1], 'library/docs.php?cid='.$item[0]);
				}
			if(($qr = mysql_query('SELECT `id`, `name`, `ext` FROM `wed_docs_items` WHERE `pid`='.intval($_GET['cid']).' ORDER BY `order` DESC;')) !== false)
				while(($item = mysql_fetch_row($qr)) !== false)
					$rows[] = array($item[1], (strlen(trim($item[2])))?'library/docs.php?id='.$item[0]:'');
			$cid = intval($_GET['cid']);
			if(in_array($cid, array())) // dual view, standart title
			{
				print "<div class=\"title\">$title</div>\r\n";
				dual_view($rows);
			}
			else if(in_array($cid, array())) // dual view, row-style title
			{
				print_split("<center>$title</center>");
				dual_view($rows);
			}
			else
			{
				print "<div class=\"title\">$title</div>\r\n";
				foreach($rows as $row)
					print_split($row[0], $row[1]);
			}
			if(isset($_SESSION['WA_USER']))
				print '<div align="right">[&nbsp;<a href="wedadmin/docs.php?cid='.$_GET['cid'].'">Редактировать</a>&nbsp;]</div>';
			include_once('../footer.inc.php');
		}
		else if(isset($_GET['id']) && ($qr = mysql_query('SELECT `name`, `ext` FROM `wed_docs_items` WHERE `id` = '.intval($_GET['id']).';')) !== false && mysql_num_rows($qr))
		{
			list($name, $ext) = mysql_fetch_row($qr);
			if(strlen($ext))
			{
				list($name) = explode('__', $name);
				header('content-type: '.mime_content_type('1.'.$ext));
				header('content-disposition: attachment; filename="'.$name.'.'.$ext.'"');
				readfile('../docs/'.intval($_GET['id']).'.dat');
			}
		}
	}
?>