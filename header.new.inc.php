<?php
if	(!isset($admin_preview)) {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");		//	Всегда!
	header("Last-Modified: " .	gmdate("D, d M	Y H:i:s") .	" GMT");
	header("Cache-Control: no-store,	no-cache, must-revalidate");	//	HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0",	false);
	header("Pragma: no-cache");													//	HTTP/1.0

	//Устанавливаем куки	для форума
	if	(@$main=="forum"){
		if	(isset($_POST['fio'])) setcookie("forum_name", $_POST['fio'], 0x7FFFFFFF);
		if	(isset($_POST['email'])) setcookie("forum_email", $_POST['email'], 0x7FFFFFFF);
	}

	require("init.php");
	require(SITE_ADMIN_DIR."/functions.php");
	set_connection();
}
require("inc/_index_fun.php");
//mysql_query("SET CHARACTER SET cp1251");

$cid = intval($_GET['cid']);
$id = intval($_GET['id']);

function bca($cid) {
	$bca = array();
	$sql = "SELECT `id`, `parent_id` FROM `wed_library` WHERE `id` = $cid";
	if ($q = mysql_query($sql)) {
		if ($ci = mysql_fetch_assoc($q)) {
			$bca[] = $ci['id'];
			if ($pbca = bca($ci['parent_id'])){
				$bca = array_merge($pbca, $bca);
			}
			return $bca;
		}
	}
	return false;
}

function lm($bca, $root = 40){
	$h = '';
	$sql = "SELECT * FROM `wed_library` WHERE `parent_id` = $root";
	$h .= '<ul>';
	if ($q = mysql_query($sql)) {
		while ($ci = mysql_fetch_assoc($q)) {
			$h .= '<li><a href="?id='.$ci['id'].'">'.$ci['title'].'</a></li>';
			if (in_array($ci['id'], $bca)){
				$h .= lm($bca, $ci['id']);
			}
		}
	}
	$h .= '</ul>';
	return $h;
}

// Get date for menu structure
function get_parents_info($cid) {

	$bc = array();
	$sql = "SELECT `id`, `parent_id`, `title`, `description` FROM `wed_library` WHERE `id` = $cid";
	if ($query = mysql_query($sql)) {

		if (list($id, $parent_id, $title, $description, $static_url) = mysql_fetch_array($query)) {

			$bc[] = array($id, $parent_id, $title, $description, $static_url);
			$parents = get_parents_info($parent_id);

			if (count($parents)) $bc = array_merge($parents, $bc);
		}
	}

	return $bc;
}


// Show left menu
function show_left_menu($bc, $sel = 0, $level = 0, $cid = 0) {

	if (!$level) {

		foreach ($bc as $value) {

			$parents_list[] = $value[0];
		}
	}
	else $parents_list = $bc;
	if (!$cid){
		if (count($parents_list)) $cid = array_shift($parents_list);
		else $cid = 1;
	}


	if ($query = mysql_query("SELECT `id`, `title`, `description`  FROM `wed_library` WHERE `parent_id` = $cid AND `hide` = 0 ORDER BY `order`")) {

		if (mysql_num_rows($query)) {

			$i = 0;
			while (list($id, $title, $description) = mysql_fetch_array($query)) {

				$c1 = mysql_result(mysql_query("SELECT COUNT(*)  FROM `wed_library` WHERE `parent_id` = $id AND `hide` = 0"), 0);
				$c2 = mysql_result(mysql_query("SELECT COUNT(*)  FROM `wed_library_items` WHERE `category_id` = $id"), 0);
				$cc = $c1 + $c2;


				$class_td = '';
				$class_link = "";
				$class_alink = "";

				if ($level) {

					$result .= '<table class="new_left_submenu">';
					if ($id == $sel){

						$class_link = 'class="current"';
						if (isset($_GET['id'])){
							$class_alink = 'class="current"';
							$class_link = '';
						}
					}
				}
				else {

					if ((count($parents_list) and in_array($id, $parents_list)) or ($description == trim($_SERVER['REQUEST_URI']))) {
							
						$_SESSION['last_sel'] = $id;
						$class_td = 'class="active"';
						//$class_link = 'class="f_14_white_a"';
					}
				}

				if (strlen(trim($description))) {
					$url = trim($description);
					$cc = 1;
				}else $url = "/index.php?cid=$id";
				$url = str_replace("?id=", "?cid=$id&id=", $url);
				if (!$level) {

					$result .= '<tr>';
					if ($cc){
						$result .= "<td><a href='$url' $class_link style='font-size:14px;color:#000000;font-weight:bold;'>$title</a></div></td>";
					}else{
						$result .= "<td style='color:#999;'>$title</td>";
					}
					$result .= '</tr>';
					$result .= '<tr><td height=1 style="background-color: #ccc" width="88%"><img src="/1x1.gif" width=20 height=1></td></tr>';
		
				}
				else {

					$padd = $level * 7;
					$result .= "<tr><td $class_td style='padding-left: {$padd}px; padding-right: 15px;' bgcolor='#f2eddf'>";
					if ($cc){
						$result .= "<a href='$url' $class_link style='font-size:12px;color:#0009b9;'>$title</a>";
					}else{
						$result .= '<span style="font-size:12px;color: #999;">'.$title.'</span>';
					}
					$result .= '<tr><td height=1 style="background-color: #ccc" width="200"><img src="/1x1.gif" width=20 height=1></td></tr>';

					if ($id == $sel) {
							
						$sq = mysql_query("SELECT `id`, `title`, `short_text`, `full_text` FROM `wed_library_items` WHERE `category_id` = $id ORDER BY `order`");
						if (mysql_num_rows($sq)) {

							$result .= "<tr><td class='no_fon' style='padding-left: {$padd}px; padding-right: 15px;'>";
							while (list($a_id, $a_title, $a_descr, $a_text) = mysql_fetch_array($sq)) {

								$a_url = '/?id='.$a_id;
								if (trim($a_descr)) {
									$a_url = trim($a_descr);
								}
								$a_url = str_replace("?id=", "?cid=$id&id=", $a_url);
								if (trim(strip_tags($a_text)) == ''){
									if (!strlen(trim($a_descr))) {
										$a_url = 0;
									}
								}
									
								$result .= "<span style='display: block; margin: 3px 3px 3px 15px;font-size: 12px;'>";
								if ($_GET['id'] == $a_id){
									$cl = $class_alink;
								}else $cl = '';
								if ($a_url) $result .= "<a href='$a_url' ".$cl.">";
								$result .= $a_title;
								if ($a_url) $result .= "</a>";
								$result .= "</span>";
								$result.='<img src="/1-gray.gif" width=200 height=1 style="padding-top:5px;padding-bottom:5px;paddint-left:30px;">';
								#$result .= '<hr width="100%" color="#ffffff" size=1 style="padding-left:25px;">';
							}
							$result .= "</td></tr>";
							#$result .= '<tr><td height=1 style="background-color: #ccc" width="200"><img src="/1x1.gif" width=20 height=1></td></tr>';
						}
					}

					$result .= "</td></tr>";
				}


				if (count($parents_list) and in_array($id, $parents_list)) {

					$submenu = show_left_menu($parents_list, $sel, $level + 1, $id);
					if ($submenu) $result .= "<tr><td class='no_fon'>$submenu</td></tr>";
				}

				$i++;
			}
			if ($level) $result .= '</table>';
		}
	}

	return $result;
}


function parent_navbits($cid, $showfirst=true)
{
	$html='';
	if($cid!=31)
	{
		if($query=mysql_query("SELECT * FROM `wed_library` WHERE `id`=$cid AND `hide`=0"))
		{
			if($cat=mysql_fetch_array($query))
			{
				$html=parent_navbits($cat['parent_id'], true);
				if ($showfirst) $html .= '<a href="index.php?cid='.$cat['id'].'" class="small gray">'.$cat['title'].'</a> / ';
			}
		}
	}
	return $html;
}

function parent_menu($cid, $showfirst=true)
{
	$html='';
	if($cid!=0)
	{
		if($query=mysql_query("SELECT * FROM `wed_library` WHERE `id`=$cid"))
		{
			if($cat=mysql_fetch_array($query))
			{
				$html=parent_menu($cat['parent_id'], true);
				if ($showfirst) $html .= ':'.$cat['id'];
			}
		}
	}
	return $html;
}

function check_cid($cid_string) {

	$need = 34;
	$cid_array = explode(":", $cid_string);
	if (in_array($need, $cid_array)) return true;
	else return false;
}


if($id) {

	//mysql_query("SET CHARACTER SET cp1251");
	$content=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=".$id);
	$content_row=mysql_fetch_assoc($content);

	$title = $content_row['title'];
}
elseif($cid)
{
	//mysql_query("SET CHARACTER SET cp1251");
	$content=mysql_query("SELECT * FROM `wed_library` WHERE `id`=".$cid);
	$content_row=mysql_fetch_assoc($content);

	$title = $content_row['title'];
}
else $title = "Главная";
?>
<!DOCTYPE HTML	PUBLIC "-//W3C//DTD HTML 4.01	Transitional//EN"	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$title?>. Британикс. Все виды обучения за рубежом. Екатеринбург.</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<?meta()?>
<script language="JavaScript" src="<?=SITE_ADMIN_DIR?>/js/index_scripts.js"></script>
<LINK REL="SHORTCUT ICON" href="img/favicon.ico">
<link href='style.css' rel='stylesheet' type='text/css'>
<?add_css();?>
<script language="JavaScript" type="text/javascript" src="/js/listmenu.js"></script>
<script src="spryassets/spryeffects.js" type="text/javascript"></script>
<script type="text/javascript" src="js/javascr.js"></script>
<script type="text/javascript">
<!--
function MM_effectAppearFade(targetElement, duration, from, to, toggle)
{
	Spry.Effect.DoFade(targetElement, {duration: 1000, from: 50, to: 100, toggle: false});
}

function MM_effectShake(targetElement)
{
	Spry.Effect.DoShake(targetElement);
}
//-->
</script>
</head>

<body marginheight="0" marginwidth="0" leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
<div style="background: #FFF">
<div class='top0'>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap="nowrap" height="111" width="322" valign="TOP"><a href="/"><img src="/img/b-logo.png" width="322" height="113" border="0"></a></td>
		<td valign="top" width="70%">
		<div class="flags"><? $res=row_select("id,name","countries","","","6");
		while ($r=$res->ga()){
			@$i++;
			$countr[$i][1]=$r["id"];
			$countr[$i][2]=$r["name"];
		}
			
		#$countr[3][1]='';
		$countr[4][2]='Канада';

		$q = mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=33 and `id`!=116 and `id`!=110 and `id`!=111 ORDER BY `order`");

		for ($i = 1; $i < 7; $i++) {

			$row = mysql_fetch_assoc($q);
			$countr[$i][3] = $row["id"];
		}
		?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td height="82" valign="bottom"><img src="img/13.gif" width="18" height="18" /></td>
				<?foreach ($countr as $key => $value) {?>
				<td valign="bottom" class="flag_item"><!-- a href="index.php?id=<?=$value[3]?>" --><img
					src="<?=getimg("countries",$value[1],"logo")?>" width="51" height="64" border="0"
					onmouseover="MM_effectAppearFade(this);" /><!-- /a --></td>
					<?}?>
				<td width="40%" valign="bottom" style="background:url(img/top_img/<?=RAND(11,21)?>.jpg) no-repeat top left;" >&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<?foreach ($countr as $key => $value) {?>
				<td class="flag_title"><a href="index.php?id=<?=$value[3]?>"><?php if ($value[3] != 104) print $value[2]; else print 'Великобритания'; ?></a></td>
				<?}?>
				<td class="flag_title">&nbsp;</td>
			</tr>
		</table>
		</div>
		</td>
	</tr>
</table>
</div>
<div class="menu" id='menu'>
<table class="table_no" width="100%">
	<tr>
	<?php
	$q = mysql_query("SELECT * FROM `wed_library` WHERE `parent_id`=31 and `hide`=0 ORDER BY `order`");
	$count = mysql_num_rows($q);

	for ($i=0;$i<$count;$i++) {

		$row = mysql_fetch_assoc($q);

		if (trim($row['description'])) print '<td class="menu-item" width="100"><a href="'.$row['description'].'"><nobr>'.$row['title'].'</nobr></a></td>';
		else {
			print '<td class="menu-item" width="100" id="d'.($i+1).'" onmouseover="javascript:show(\''.($i+1).'\',70,140,1,\'c\');clearTimeout(tid);" onclick="window.location.href=\'index.php?cid='.$row['id'].'\'" onmouseout=javascript:hiding('.($i+1).',1); nowrap>';
			print '<a href="index.php?cid='.$row['id'].'"><NOBR>'.$row['title'].'</NOBR></a>';
			print '</td>';
		}

	}


	?>
		<td width="100%" class="menu-item">
		<div align="right"><a href="/library.php?id=292"><B>English</B></a></div>
		</td>
	</tr>
</table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<!--<td valign="top" class="line3" ><img src="img/41.gif" width="159" height="21" /><br><? #search("Поиск по сайту :"); ?></td>-->
		<td valign="bottom" class="line3" align="center">
		<!-- <a
			href="http://britanix.ru/index.php?id=427"
			style="font-size: 22px; text-decoration: none; color: blue; font-weight: bold"><img src="/img/but2010.gif" alt="Зима 2010" border="0" /></a>
		 -->
			</td>
		
		
		<td valign="bottom" class="line3"><?php
		if($id) {

			//mysql_query("SET CHARACTER SET cp1251");
			$content=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=".$id);
			$content_row=mysql_fetch_assoc($content);

			$title = $content_row['title'];
		}
		elseif($cid)
		{
			//mysql_query("SET CHARACTER SET cp1251");
			$content=mysql_query("SELECT * FROM `wed_library` WHERE `id`=".$cid);
			$content_row=mysql_fetch_assoc($content);

			$title = $content_row['title'];
		}
		else $title = "Главная";
		?>
		<h1><?php
		//print iconv("KOI8-R", "cp1251", $title);
		print $title;
		?></h1>
		</td>
		<td class="line3"><span style="font-size: 17px; color: #B70404; font-weight: bold;">(343) 375-00-00<br>310 10 45<br>310 10 46</span></td>
	</tr>
	<tr>
		<td class="left" bgcolor="#f2eddf">
		<table class="new_left_menu">
		<?php
		//////////////////////////////////////////////////
		//
		// Show left menu (modified by kortes, 21.05.2009)
		//
		//////////////////////////////////////////////////

		if ($cid) $sel = $cid;

		//$bc = bca($sel);
		$bc = get_parents_info($sel);

		if ($bc[0][0] == 50) print show_left_menu($bc, $sel);
		else {

			$bc = get_parents_info(50);
			//print lm($bc);
			print show_left_menu($bc, 50);
		}
		?>
		</table>
123
		</td>
		<td class="center">
		<div id="content">
