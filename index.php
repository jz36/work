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
require_once("inc/_index_fun.php");
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


$content_plus='';
if($id) {

	//mysql_query("SET CHARACTER SET cp1251");
	$content=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=".$id);
	$content_row=mysql_fetch_assoc($content);
	
	if ($id==592) $content_plus=include( "form.inc.php" );

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
	<link REL="SHORTCUT ICON" href="img/favicon.ico">
	<link href='style.css' rel='stylesheet' type='text/css'>
	<?add_css();?>
	<script language="JavaScript" type="text/javascript" src="/js/listmenu.js"></script>
	<script src="spryassets/spryeffects.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/javascr.js"></script>
	<script src="js/swfobject_modified.js" type="text/javascript"></script>
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
<!--body marginheight="0" marginwidth="0" leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0"-->
<body style="margin:0">


<div style="height:90px; background:url('/img/britanix.jpg') top center,url('/img/britanix_b.png') top repeat-x"></div>

<div style="background: #FFF">
<div class='top0'>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap="nowrap" height="111" width="322" valign="TOP" style="background:url('img/b-logo.png') top left no-repeat;cursor:pointer" onclick="location.href='/'">
	<?/*	<object id="FlashID2" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="413" height="113" style="margin-right:-100px">
  <param name="movie" value="top2.swf" />
  <param name="quality" value="high" />
  <param name="wmode" value="transparent" />
  <param name="swfversion" value="8.0.35.0" />
  <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
  <param name="expressinstall" value="js/expressInstall.swf" />
  <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
  <!--[if !IE]>-->
  <object type="application/x-shockwave-flash" data="top2.swf" width="413" height="113">
    <!--<![endif]-->
    <param name="quality" value="high" />
    <param name="wmode" value="transparent" />
    <param name="swfversion" value="8.0.35.0" />
    <param name="expressinstall" value="js/expressInstall.swf" />
    <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
    <div>
      <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
    </div>
    <!--[if !IE]>-->
  </object>
  <!--<![endif]-->
</object>
		*/?>
		</td>
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
</table></div><div class="menu" id='menu'><table class="table_no" width="100%">
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
		<div align="right"><a href="/index.php?cid=148"><B>English</B></a></div>
		</td>
	</tr>
</table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<!--<td valign="top" class="line3" ><img src="img/41.gif" width="159" height="21" /><br><? #search("Поиск по сайту :"); ?></td>-->
		<td valign="bottom" class="line3" align="center">
		<!--  <a
			href="http://britanix.ru/index.php?id=427"
			style="font-size: 22px; text-decoration: none; color: blue; font-weight: bold"><img src="/img/but2010.gif" alt="Весна-Лето 2010" border="0" /></a>
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
		<td class="line3"><span style="font-size: 17px; color: #B70404; font-weight: bold;">(343) 375-00-00,<br>310-10-45, 310-10-46</span></td>
	</tr>
	<tr>
		<td class="left" bgcolor="#f2eddf">
		<br><span style="font-size: 17px; color: #B70404; font-weight: bold;">Обучение за рубежом</span>
		<table class="new_left_menu">
		<?php
		//////////////////////////////////////////////////
		//
		// Show left menu (modified by kortes, 21.05.2009)
		//
		//////////////////////////////////////////////////
		
		if ($id && !$cid){
			$content=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=".$id);
			$content_row=mysql_fetch_assoc($content);
			$cid = $content_row['category_id'];
		}
		if ($cid) $sel = $cid;
		if (!$cid) $sel = 50;
		
		//$bc = bca($sel);
		$bc = get_parents_info($sel);
		
		if ($bc[0][0] == 31) {
			
			$sel = 50;
		}

			$bc = get_parents_info($sel);
			//var_dump($bc);
			//print lm($bc);
			if ($id<744) print show_left_menu($bc, $sel);else print show_left_menu(get_parents_info(50),50);


		
		?>
		
		<tr><td><a  href="/?all_grants" style="font-size:14px;color:#000000;font-weight:bold;">Стипендии и гранты</a></td></tr>
		</table>
		<? $q=mysql_query("SELECT * FROM `wed_news_items2` WHERE `is_favorite`=1 ORDER BY `date` DESC");
		
		if (mysql_num_rows($q)) {
		?>
				
		
		<?}?>

<CENTER>
<BR><BR>
<!-- banner -->
<p>
<a href="http://www.quality-english.com/" target="_blank"><img src="http://britanix.ru/catalogue/customimages/169/QE-Agents-logo-2-col.jpg"  /></a>
</p>
<!-- Facebook Badge START --><a href="http://www.facebook.com/Britannix" target="_TOP" style="font-family: &quot;lucida grande&quot;,tahoma,verdana,arial,sans-serif; font-size: 11px; font-variant: normal; font-style: normal; font-weight: normal; color: #3B5998; text-decoration: none;" title="Britannix Educational Agency">Britannix Educational Agency</a><br/><a href="http://www.facebook.com/Britannix" target="_TOP" title="Britannix Educational Agency"><img src="http://badge.facebook.com/badge/320853801302748.1890.745132727.png" style="border: 0px;" /></a><br/><!-- Facebook Badge END -->
<br><br>
<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
<!-- VK Widget -->
<div id="vk_groups"></div>
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 0, width: "200", height: "280"}, 59388096);
</script>
</CENTER>



		</td>
		<td class="center">
		<div id="content"><?php


		if ($id) {

			//mysql_query("SET CHARACTER SET cp1251");
			$content=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=".$id);
			$content_count=mysql_num_rows($content);

			$content_row=mysql_fetch_assoc($content);

			if (parent_navbits($content_row['category_id'])) echo '<div id="navline">'.parent_navbits($content_row['category_id']).'</div>';
			echo $content_row['full_text'];
			echo  $content_plus;
		}
		elseif ($cid) {
			/*$content = mysql_query("SELECT * FROM `wed_library` WHERE `id`=".$cid." AND `hide`=0");
			$content_row=mysql_fetch_assoc($content);
			var_dump($content_row['parent_id']);*/
			$content=mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=".$cid." ORDER BY `order`");
			$content_count=mysql_num_rows($content);

			if($content_count) {

				$content_row=mysql_fetch_assoc($content);
				//echo '<h2>'.$content_row['title'].'</h2>';
				if (parent_navbits($content_row['category_id'])) echo '<div id="navline">'.parent_navbits($content_row['category_id']).'</div>';
				echo $content_row['full_text'];
			}
			else {

				$content = mysql_query("SELECT * FROM `wed_library` WHERE `parent_id`=".$cid." AND `hide`=0");
				$count = mysql_num_rows($content);

				if ($count) {

					print '<table cellpadding="5" cellspacing="0" class="table">';
					for ($i=0;$i<$count;$i++) {

						$content_row = mysql_fetch_assoc($content);

						print '<tr><td><a href="/?cid='.$content_row['id'].'">'.$content_row['title'].'</a></td></tr>';
					}
					print '</table>';

					$content=mysql_query("SELECT * FROM `wed_library` WHERE `id`=".$cid);
					$content_row=mysql_fetch_assoc($content);
				}
				else {

					$content = mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=".$cid);
					$count = mysql_num_rows($content);

					if ($count) {

						print '<div id="content">';
						if (parent_navbits($content_row['category_id'])) echo '<div id="navline">'.parent_navbits($content_row['category_id']).'</div>';
						print '<table cellpadding="5" cellspacing="0" class="table" width="100%">';
						for ($i=0;$i<$count;$i++) {

							$content_row = mysql_fetch_assoc($content);

							if($i%2 == 0) print '<tr>';
							print '<td width="33%" valign="middle"><a href="index.php?cid='.$cid.'&id='.$content_row['id'].'"><img src="library/images/'.$content_row['small_picture'].'" align="middle" hspace="5"></a> <a href="index.php?cid='.$cid.'&id='.$content_row['id'].'">'.$content_row['title'].'</a></td>';
							if($i%2 == 1) print '</tr>';
						}
						print '</table></div>';
					}
				}
			}
		}
		elseif (isset($_GET['all_news'])) {

			if(!isset($_GET['details'])) {

				echo '<h2>Наши новости</h2>';

				$news=mysql_query("SELECT * FROM `wed_news_items` WHERE `is_favorite`=1 ORDER BY `date` DESC");
				$news_count=mysql_num_rows($news);

				for ($i=0;$i<$news_count;$i++) {

					$news_row = mysql_fetch_assoc($news);

					print '<table class="tableno">';
					print '<tr>';
					//print '<td width="150" align="center">';
					//if ($news_row['small_picture'] != "") print '<a href="index.php?all_news&details='.$news_row['id'].'" class="header_items"><img src="'.$news_row['small_picture'].'"></a>';
					print '<td valign="top">';
					print '<span class="date">'.date("d.m.y",$news_row['date']).'</span>';
					print '<a href="index.php?all_news&details='.$news_row['id'].'" class="header_items"><B>'.$news_row['title'].'</B></a><br/>';
					print $news_row['short_text'].'</td>';
					print '</tr>';
					print '</table>';
				}
			}
			else {

				$news=mysql_query("SELECT * FROM `wed_news_items` WHERE `id`=".$_GET['details']);
				$news_row=mysql_fetch_assoc($news);

				print '<h2>'.$news_row['title'].'</h2>';
				print '<span class="date">'.date("d.m.y",$news_row['date']).'</span>';
				print $news_row['full_text'].'<br/><br/>';
				print '<br/><a href="index.php?all_news" class="small gray">Все новости</a><br/><br/>';
			}
		}
		elseif (isset($_GET['all_grants'])) {

			if(!isset($_GET['details'])) {

				echo '<h2>Стипендии и гранты</h2>';

				$news=mysql_query("SELECT * FROM `wed_news_items2` WHERE `is_favorite`=1 ORDER BY `date` DESC");
				$news_count=mysql_num_rows($news);

				for ($i=0;$i<$news_count;$i++) {

					$news_row = mysql_fetch_assoc($news);

					print '<table class="tableno">';
					print '<tr>';
					//print '<td width="150" align="center">';
					//if ($news_row['small_picture'] != "") print '<a href="index.php?all_city_news&details='.$news_row['id'].'" class="header_items"><img src="'.$news_row['small_picture'].'"></a>';
					print '<td valign="top">';
					print '<span class="date">'.date("d.m.y",$news_row['date']).'</span>';
					print '<a href="index.php?all_grants&details='.$news_row['id'].'" class="header_items">'.$news_row['title'].'</a><br/>';
					print $news_row['short_text'].'</td>';
					print '</tr>';
					print '</table>';
				}
			}
			else {

				$news=mysql_query("SELECT * FROM `wed_news_items2` WHERE `id`=".$_GET['details']);
				$news_row=mysql_fetch_assoc($news);

				print '<h2>'.$news_row['title'].'</h2>';
				print '<span class="date">'.date("d.m.y",$news_row['date']).'</span>';
				print $news_row['full_text'].'<br/><br/>';
				// print '<br/><a href="index.php?all_city_news" class="small gray">Все мероприятия</a><br/><br/>';
			}
		}
		else {

			// Show special block
			$sql = "SELECT `title`, `full_text` FROM `wed_library_items` WHERE `id` = 303";
			$query = mysql_query($sql);
			list($special_title, $special_text) = mysql_fetch_array($query);
			print "<h2><font size='4'>$special_title</font></h2>";
			print $special_text;

			//mysql_query("SET CHARACTER SET cp1251");
			$content=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=169");
			$content_count=mysql_num_rows($content);

			$content_row=mysql_fetch_assoc($content);

			if (parent_navbits($content_row['category_id'])) echo '<br/>'.parent_navbits($content_row['category_id']).'<br/><br/>';
			print $content_row['full_text'];


			print '<h3><a href="index.php?all_news" class="h3_dop gray">Смотреть все</a>Наши новости</h3>';
			$news=mysql_query("SELECT * FROM `wed_news_items` WHERE `is_favorite`=1 AND `date` > ".(time()-60*60*24*365)." ORDER BY `date` DESC");
			$news_count=mysql_num_rows($news);

			for ($i=0;$i<$news_count;$i++) {

				$news_row = mysql_fetch_assoc($news);

				print '<table class="tableno">';
				print '<tr>';

				//print '<td width="150" align="center">';

				//if ($news_row['small_picture'] != "") print '<a href="index.php?all_news&details='.$news_row['id'].'" class="header_items"><img src="'.$news_row['small_picture'].'"></a>';
				print '<td valign="top">';
				print '<span class="date">'.date("d.m.y",$news_row['date']).'</span>';
				print '<a href="index.php?all_news&details='.$news_row['id'].'" class="header_items">'.$news_row['title'].'</a><br/>';
				print $news_row['short_text'].'</td>';
				print '</tr>';
				print '</table>';
			}
		}

		?> <? //require(SITE_ADMIN_DIR."/inc/content.php")?>
		<hr>
		</div>
		</div>
		</td>
		<td class="right">
		<br><span style="font-size: 17px; color: #B70404; font-weight: bold;">Курсы в Екатеринбурге</span>
		<table class="new_left_menu">
<? $q=mysql_query("SELECT `title`,`description` FROM `wed_library` WHERE `parent_id`=202 ORDER BY `order`");
while ($itm=mysql_fetch_array($q))
{
?>
	<tr><td><a href="<?=$itm[1]?>" style="font-size:14px;color:#000000;font-weight:bold;"><?=$itm[0]?></a></td></tr>
		<tr><td height="1" style="background-color: #ccc" width="88%"><img src="/1x1.gif" width="20" height="1"></td></tr>
<?}?>
</table>
		
<?
		require_once "./dist.php";
		//$q = mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=179");
		//$row = mysql_fetch_assoc($q);

		//print '<h2 style="border: 0px solid rgb(255, 255, 255); margin: 10px -8px 0px; padding: 3px 5px; background: rgb(189, 0, 0); display: block; font-size: 14px; color: rgb(255, 255, 255);">Наши предложения</h2>';
		//print $row['full_text'];
		?></td>
	</tr>
	<tr>
		<td height="30" bgcolor="#FFFFFF">&nbsp;</td>
		<td align=left style="border-bottom: 1px solid #CFCFCF">
		<div class="body_foot"><a href="#">Наверх</a> <a href="javascript:window.history.back()">Назад</a> <a
			href="<?=SPAGE?>">Главная</a> <a class=small
			href="print.php?<?if ($main!="") echo "main=$main";if ($id!="") echo "&id=$id";if (isset($top)) echo "&top=$top";if (isset($sub)) echo "&sub=$sub";?>"
			target="_blank"> Версия для печати</a> <a href="index.php?main=site_map">Карта сайта</a> <a
			href="index.php?main=contacts">Контакты</a></div>
		</td>
		<td bgcolor='#F2EDDF' style="border-bottom: 1px solid #CFCFCF">&nbsp;</td>
	</tr>
</table>
<div class="foot">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top: 1px;">
	<tr>
		<td height="70" align="center" width="23%">
		<noindex> <!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/go/britannix.ru' "+
"target=_blank rel=nofollow><img src='http://counter.yadro.ru/hit?t12.10;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border=0 width=88 height=31><\/a>")//--></script><!--/LiveInternet--> </noindex>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter34417590 = new Ya.Metrika({
                    id:34417590,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    trackHash:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/34417590" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-69087500-2', 'auto');
  ga('send', 'pageview');

</script>

</td>


		<td width=47%>
		<div class="foot2">Адрес: : г. Екатеринбург, ул. Гоголя, 15а, оф. 9<br />
		(Вход через крыльцо Генерального консульства США, первый этаж)<br />
		Тел: (343) 375-00-00, 310-10-45, 310-10-46<br />
		офисный мобильный номер +7 912 266 55 74<br>
		E-mail: <a href='mailto:info@britannix.ru'>info@britannix.ru</a><br />
		Skype: britannix<br />		
		Информация, размещенная на сайте, является справочной и может быть изменена в одностороннем порядке.
		</div>
		</td>
		<td width=30%><?counter("")?><BR>
		<BR>

		<a href="http://d1.ru">Программирование сайта Екатеринбург - D1.ru</a><BR>
		<BR>
		<a href="http://y1.ru">Оптимизация сайта Екатеринбург - Y1.ru</a></td>
	</tr>
</table>
</div>
</div>
		<? // menu_popup()

		$q = mysql_query("SELECT * FROM `wed_library` WHERE `parent_id`=31 AND `hide`=0 ORDER BY `order`");
		$count = mysql_num_rows($q);

		for ($i=0;$i<$count;$i++) {

			$row = mysql_fetch_assoc($q);

			if($row['id'] != 36 and $row['id'] != 38) {

				$qi = mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=".$row['id']." ORDER BY `order`");
				$counti = mysql_num_rows($qi);

				print '<div class=popup id=h'.($i+1).' style="top:0px; left: 0px;" onmouseover=clearTimeout(tid);m_over('.($i+1).',0); onmouseout=hiding('.($i+1).',1);m_out('.($i+1).',0);>';
				for ($j=0; $j<$counti; $j++) {

					$rowi = mysql_fetch_assoc($qi);
					print '<div class="popup-item"><a href="index.php?id='.$rowi['id'].'">'.$rowi['title'].'</a></div>';
				}
				print '</div>';
			}
		}
		?>
	<script type="text/javascript">
	<!--
	swfobject.registerObject("FlashID");
	swfobject.registerObject("FlashID2");
	//-->
	</script>
	</body>
</html>
