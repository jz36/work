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

?>
<!DOCTYPE HTML	PUBLIC "-//W3C//DTD HTML 4.01	Transitional//EN"	"http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title><?=$title?>. <?=$site_title?></title>
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
				?>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td height="82" valign="bottom"><img src="img/13.gif" width="18" height="18" /></td>
					<?foreach ($countr as $key => $value) {?>
    					<td valign="bottom" class="flag_item"><a href="?main=countries&id=<?=$value[1]?>"><img src="<?=getimg("countries",$value[1],"logo")?>" width="51" height="64" border="0" onmouseover="MM_effectAppearFade(this);"/></a></td>
					<?}?>
						<td width="40%" valign="bottom" style="background:url(img/top_img/<?=RAND(11,21)?>.jpg) no-repeat top left;" >&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					<?foreach ($countr as $key => $value) {?>
    					<td class="flag_title"><a href="?main=countries&id=<?=$value[1]?>"><?=$value[2]?></a></td>
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
<? @menu(70,140,"c")?>
</tr></table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="line3" ><img src="img/41.gif" width="159" height="21" /><br><? search("Поиск по сайту :")?></td>
		<td valign="bottom" class="line3"><h1><?=$title?></h1></td>
		<td class="line3">&nbsp;</td>
	</tr>
	<tr>
		<td class="left">
			<?if ($main=="main") { banners_news(1); } # Новости Британского совета ?> 
			<?@menu2()?>
			<? @vote()?>
		</td>
		<td class="center">
        	<div id="content">
			<? 
			if ($main!="main" || empty($main)) {
				?><div id="navline"><?nav_line($main,$id," / ",1,1,1,1,1,"small gray",1);?></div><?
			}?>
			
			<? require(SITE_ADMIN_DIR."/inc/content.php")?></div>
			
			</div>
			</td>
		<td class="right">
		<?require("inc/content/right.php");?>
		<? @banners("","right_informers1")?>
		<? @banners("","right_informers2")?>
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
<? menu_popup()?>
<?print_r($dbg_listing);?>
</body>
</html>
