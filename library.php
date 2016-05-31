<?
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

if ($_GET['cid']) $cid = intval($_GET['cid']);
else $cid = 0; 

if ($_GET['id']) $id = intval($_GET['id']);
else $id = 0; 


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
function show_left_menu($bc, $sel = 47, $level = 0, $cid = 47) {

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

//print "SELECT `id`, `title`, `description`  FROM `wed_library` WHERE `parent_id` = $cid AND `hide` = 0 ORDER BY `order`";
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
<html><head>
<title><?=$title?>. Британикс. Все виды обучения за рубежом. Екатеринбург.</title>
<meta	http-equiv="Content-Type" content="text/html; charset=windows-1251">
<?meta()?>
<script language="JavaScript" src="<?=SITE_ADMIN_DIR?>/js/index_scripts.js"></script>
<LINK REL="SHORTCUT ICON" href="img/favicon.ico"> 
<link	href='style.css' rel='stylesheet' type='text/css'>
<?add_css();?>
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

<body	marginheight="0" marginwidth="0"	leftmargin="0"	rightmargin="0" topmargin="0"	bottommargin="0" >
<div style="background:#FFF">
<div class='top0'>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td nowrap="nowrap"><a href="<?=PATH?>"><img src="img/11logo.gif" alt="<?=param("site_title")?>" width="106" height="82" border=0 /><img src="img/12logo.gif" alt="<?=param("site_title")?>" width="216" height="82" border=0 /></a><br />
				<img src="img/21.gif" width="106" height="31" /><img src="img/22.gif" width="216" height="31" /></td>
			<td valign="top" width="70%">
			<div class="flags">
				<? $res=row_select("id,name","countries","","","6");
				while ($r=$res->ga()){
					@$i++;
					$countr[$i][1]=$r["id"];
					$countr[$i][2]=$r["name"];									
				}
					
					#$countr[3][1]='';
					$countr[4][2]='Канада';									

					$q = mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=33 and `id`!=116 ORDER BY `order`");

					for ($i = 1; $i < 8; $i++) {

						$row = mysql_fetch_assoc($q);
						$countr[$i][3] = $row["id"];
					}
				?>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td height="82" valign="bottom"><img src="img/13.gif" width="18" height="18" /></td>
					<?foreach ($countr as $key => $value) {?>
    					<td valign="bottom" class="flag_item"><a href="index.php?id=<?=$value[3]?>"><img src="<?=getimg("countries",$value[1],"logo")?>" width="51" height="64" border="0" onmouseover="MM_effectAppearFade(this);"/></a></td>
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
<table class="table_no"><tr>
<?php
$q = mysql_query("SELECT * FROM `wed_library` WHERE `parent_id`=31 ORDER BY `order`");
$count = mysql_num_rows($q);

for ($i=0;$i<$count;$i++) {
	
	$row = mysql_fetch_assoc($q);

	if (trim($row['description'])) print '<td class="menu-item"><a href="'.$row['description'].'">'.$row['title'].'</a></td>';
	else {
	    print '<td class="menu-item" id="d'.($i+1).'" onmouseover="javascript:show(\''.($i+1).'\',70,140,1,\'c\');clearTimeout(tid);" onclick="window.location.href=\'index.php?cid='.$row['id'].'\'" onmouseout=javascript:hiding('.($i+1).',1); nowrap>';
		print '<a href="index.php?cid='.$row['id'].'">'.$row['title'].'</a>';
		print '</td>';
	}
}

//@menu(70,140,"c")
?>
</tr></table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="line3" ><img src="img/41.gif" width="159" height="21" /><br><? search("Поиск по сайту :")?></td>
		<td valign="bottom" class="line3">
<?php
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
<h1><?=$title?></h1>

		</td>
		<td class="line3"><span style="font-size: 17px; color:#B70404;font-weight: bold;">(343) 375-00-00</span></td>
	</tr>
	<tr>
		<td class="left">
<?php

if (!$id and !$cid) {

/*
	print '<div style="border-style: solid; border-color: rgb(169, 169, 169); border-width: 1px 1px 0px; margin: 10px 0px 0px; padding: 5px 15px 3px 10px; background: rgb(0, 105, 183); color: rgb(255, 255, 255); font-size: 11px; display: block;">Программа мероприятий в Екатеринбурге</div>';
	
	$news=mysql_query("SELECT * FROM `wed_news_items2` WHERE `is_favorite`=1 ORDER BY `date` DESC");
	$news_count=mysql_num_rows($news);
				
	for ($i=0;$i<$news_count;$i++) {
	
		$news_row=mysql_fetch_assoc($news);
		
		$text = '<div style="border: 1px solid rgb(193, 193, 193); padding: 6px; background: rgb(229, 240, 252); font-size: 11px;">'; 
		$text .= '<table class="tableno">';
	
		$h="<span style='color:#666; font-size:10px;'>".date("d.m.y",$news_row['date'])."</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php?all_city_news&details=".$news_row['id']."'>".$news_row['title']."</a><br/>";
		$text .= '<td valign="top">'.$h.$news_row['short_text'].'</td></tr>';
		$text .= '</table>';
	}
	
	print $text; 
	print '<div style="border-top: 1px dotted rgb(221, 221, 221); padding: 3px 0px 0px 65px;" align="right"><a href="index.php?all_city_news" class="small gray">Все мероприятия &gt;&gt;</a></div></div>';
*/
	include_once "dist.php";
}
else {

	if (!$cid and $id) {

		$q = mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=$id");
		$row = mysql_fetch_assoc($q);
		$cid = $row["category_id"];
		$cid_string = parent_menu($cid);
	}

	if (check_cid($cid_string)) $cid = 34;

	/*
	$content = mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=".$cid);
	$count = mysql_num_rows($content);
	
	if ($count) {
	
		print '<div class="menu2_top" style="background: transparent url(img/m2_bg.jpg) no-repeat left top;"><div class="menu2">';

		for ($i=0;$i<$count;$i++) {
	
			$content_row = mysql_fetch_assoc($content);
			print '<div class="menu2-item"><a href="index.php?cid='.$cid.'&id='.$content_row['id'].'">'.$content_row['title'].'</a></div>';
		}

		print '</div></div>';
	}*/
		print '<table class="new_left_menu">';
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
		print '</table>';

//	$content = mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=".$cid);
//	$row = mysql_fetch_assoc($content);
//	$id = $row['id'];
}
?>
		</td>
		<td class="center">
        	<div id="content">
			<? /*
			if ($main!="main" || empty($main)) {
				?><div id="navline"><?nav_line($main,$id," / ",1,1,1,1,1,"small gray",1);?></div><?
			}
			*/
			?>

<?php

if($id) {	

	//mysql_query("SET CHARACTER SET cp1251");
	$content=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=".$id);
	$content_count=mysql_num_rows($content);
	
	$content_row=mysql_fetch_assoc($content);


	//echo '<h2>'.$content_row['title'].'</h2>';
	if (parent_navbits($content_row['category_id'])) echo '<div id="navline">'.parent_navbits($content_row['category_id']).'</div>';
	print $content_row['full_text'];
}
elseif($cid)
{
	//mysql_query("SET CHARACTER SET cp1251");
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
				
				print '<tr><td>'.$content_row['title'].'</td></tr>';
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
			print '<td width="150" align="center">';
			if ($news_row['small_picture'] != "") print '<a href="index.php?all_news&details='.$news_row['id'].'" class="header_items"><img src="'.$news_row['small_picture'].'"></a>';
			print '<td valign="top">';
			print '<span class="date">'.date("d.m.y",$news_row['date']).'</span>';
			print '<a href="index.php?all_news&details='.$news_row['id'].'" class="header_items">'.$news_row['title'].'</a><br/>';
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
elseif (isset($_GET['all_city_news'])) {

	if(!isset($_GET['details'])) {

		echo '<h2>Программа мероприятий в Екатеринбурге</h2>';

		$news=mysql_query("SELECT * FROM `wed_news_items2` WHERE `is_favorite`=1 ORDER BY `date` DESC");
		$news_count=mysql_num_rows($news);
		
		for ($i=0;$i<$news_count;$i++) {

			$news_row = mysql_fetch_assoc($news);

			print '<table class="tableno">';
			print '<tr>';
			print '<td width="150" align="center">';
			if ($news_row['small_picture'] != "") print '<a href="index.php?all_city_news&details='.$news_row['id'].'" class="header_items"><img src="'.$news_row['small_picture'].'"></a>';
			print '<td valign="top">';
			print '<span class="date">'.date("d.m.y",$news_row['date']).'</span>';
			print '<a href="index.php?all_city_news&details='.$news_row['id'].'" class="header_items">'.$news_row['title'].'</a><br/>';
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
		print '<br/><a href="index.php?all_city_news" class="small gray">Все мероприятия</a><br/><br/>';
	}
}
else {

	//mysql_query("SET CHARACTER SET cp1251");
	$content=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=169");
	$content_count=mysql_num_rows($content);
	
	$content_row=mysql_fetch_assoc($content);

	//echo '<h1>'.$content_row['title'].'</h1>';
	if (parent_navbits($content_row['category_id'])) echo '<br/>'.parent_navbits($content_row['category_id']).'<br/><br/>';
	print $content_row['full_text'];


	print '<h3><a href="index.php?all_news" class="h3_dop gray">Смотреть все</a>Наши новости</h3>';	
	$news=mysql_query("SELECT * FROM `wed_news_items` WHERE `is_favorite`=1 ORDER BY `date` DESC");
	$news_count=mysql_num_rows($news);
				
	for ($i=0;$i<$news_count;$i++) {
	
		$news_row = mysql_fetch_assoc($news);

		print '<table class="tableno">';
		print '<tr>';

		print '<td width="150" align="center">';

		if ($news_row['small_picture'] != "") print '<a href="index.php?all_news&details='.$news_row['id'].'" class="header_items"><img src="'.$news_row['small_picture'].'"></a>';
		print '<td valign="top">';
		print '<span class="date">'.date("d.m.y",$news_row['date']).'</span>';
		print '<a href="index.php?all_news&details='.$news_row['id'].'" class="header_items">'.$news_row['title'].'</a><br/>';
		print $news_row['short_text'].'</td>';
		print '</tr>';
		print '</table>';
	}
}

?>






			
			<? //require(SITE_ADMIN_DIR."/inc/content.php")?><hr></div>
			
			</div>
			</td>
		<td class="right">
<?

$q = mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=179");
$row = mysql_fetch_assoc($q);

print '<h2 style="border: 0px solid rgb(255, 255, 255); margin: 10px -8px 0px; padding: 3px 5px; background: rgb(189, 0, 0); display: block; font-size: 14px; color: rgb(255, 255, 255);">Наши предложения</h2>';
print $row['full_text'];
?>
		</td>
	</tr>
  <tr>
    <td height="30" bgcolor="#FFFFFF">&nbsp;</td>
    <td align=left style="border-bottom:1px solid #CFCFCF "><div class="body_foot">
    	<a href="#">Наверх</a>
    	<a href="javascript:window.history.back()">Назад</a>
    	<a href="<?=SPAGE?>">Главная</a>
    	<a class=small href="print.php?<?if ($main!="") echo "main=$main";if ($id!="") echo "&id=$id";if (isset($top)) echo "&top=$top";if (isset($sub)) echo "&sub=$sub";?>" target="_blank"> Версия для печати</a>
    	<a  href="index.php?main=site_map">Карта сайта</a>
		<a  href="index.php?main=contacts">Контакты</a>
		</div></td>
    <td bgcolor="#F2EDDF" style="border-bottom:1px solid #CFCFCF ">&nbsp;</td>
  </tr>
</table>
<div class="foot">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:1px;">
  <tr>
    <td height="70" align="center" width=23%>


</td>


    <td width=57%><div class="foot2"><? footer()?></div></td>
    <td width=20%><?counter("")?><BR><BR>

<noindex>
<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/go/britannix.ru' "+
"target=_blank rel=nofollow><img src='http://counter.yadro.ru/hit?t12.10;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border=0 width=88 height=31><\/a>")//--></script><!--/LiveInternet-->
</noindex>

</td>
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
</body>
</html>