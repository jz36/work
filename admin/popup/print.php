
<!DOCTYPE HTML	PUBLIC "-//W3C//DTD HTML 4.01	Transitional//EN"	"http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<title><?=$title?>. <?=$site_title?></title>
<meta	http-equiv="Content-Type" content="text/html; charset=windows-1251">
<?meta()?>
<script language="JavaScript" src="<?=SITE_ADMIN_DIR?>/js/index_scripts.js"></script>
<LINK REL="SHORTCUT ICON" href="img/favicon.ico"> 
<link	href='style.css' rel='stylesheet' type='text/css'>
<?add_css();?>
</head>
<?#= Шапка сайта ==============================?>
<table width="660"  border="0" cellspacing="0" cellpadding="0" align=center>
<tr>
<td height=50 valign=bottom><h1><?=toup($title)?></h1>
<?if ($main!="welcome") nav_line($main,$id," -> ",1,1,1,0,1,"small gray",1);?>
</td>
</tr>
</table><hr width=660>
<table width="660"  border="0" cellspacing="0" cellpadding="10" align=center>
<tr>
<td valign=top bgcolor="#ffffff" id=content>
<? require(SITE_ADMIN_DIR."/inc/content.php")?><hr>
</td>
</tr>
<tr>
<td height="50" bgcolor=#ffffff style="padding:0px 0px 0px 10px;" class=small><? footer()?><?=param("copyright")?></td>
</tr> 
</table>
</body>
</html>