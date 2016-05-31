<?php

if(isset($_GET['id'])) $id=intval($_GET['id']);
if(isset($_GET['cid'])) $cid=intval($_GET['cid']);

if(isset($id) or isset($cid))
{
	include "wedadmin/config.inc.php";
	include "wedadmin/lib/mysql.lib.php";
	include "functions.php";
	
	db_open();
}


#unset($title);
global $title;


if($id!=0)
{
	mysql_query("SET CHARACTER SET cp1251");
	$ttl=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=".$id);
	setlocale(LC_ALL, 'ru_RU.CP1251');
	$ttl_row=mysql_fetch_assoc($ttl);

	if(trim($ttl_row['meta_description'])!='') $title=ucfirst($ttl_row['meta_description']);
	else $title=ucfirst($ttl_row['title']);
}
elseif(isset($_GET['cid']))
{
	mysql_query("SET CHARACTER SET cp1251");
	$ttl=mysql_query("SELECT * FROM `wed_library` WHERE `id`=".intval($_GET['cid']));
	setlocale(LC_ALL, 'ru_RU.CP1251');
	$ttl_row=mysql_fetch_assoc($ttl);
	$title=ucfirst($ttl_row['title']);
}

if (!$title) { $title="Обучение за рубежом. Екатеринбург. Британикс"; }
else { if ($titleadd) { $title.=" и обучение. Из Екатеринбурга"; } }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML>
	<HEAD>
		<title><?=$title?></title>
		<link href="/styles.css" rel="stylesheet" type="text/css">
		<META http-equiv=Content-Type content="text/html; charset=windows-1251">
		<meta name="Keywords" CONTENT="Обучение, за рубежом, Курсы, зарубежом, образование, английский ">
		<meta name="description" content="Британикс. Обучение за рубежом">
		<script type="text/javascript" src="/javascr.js"></script>
	</HEAD>
<BODY onLoad="MM_preloadImages('img/knopka-2.jpg','img/knopka-4.jpg','img/knopka-6.jpg','img/knopka-8.jpg')">
<TABLE width=1002 border=0 align="center" cellPadding=0 cellSpacing=0>
  <TBODY>
    <TR> 
      <TD height=127 colSpan=2><img src="img/rob-1.jpg" width="1000" height="131" border="0" usemap="#koll" style="border-style:none" alt="620151, г. Екатеринбург, ул. Гоголя, 15а-9. Тел (343) 375-65-00">
<div>
<map id="koll" name="koll">
<area shape="rect"  coords="0 0 239 131" href="/" title="На главную"/>
<area shape="rect"  coords="259 15 316 50" href="/es_sc.shtml"/>
<area shape="rect"  coords="683 17 744 52" href="/es_usa.shtml"/>
<area shape="default" nohref="nohref" alt="" />
</div></TD>
    </TR>
  <TR bgcolor="#0066CC"> 
    <TD align=left vAlign=top bgcolor="#FFFFFF" rowspan="2" width="193"></TD>
    <TD height=2 align=left vAlign=middle bgcolor="#FFFFFF" width="809">
    	<p align="right"><a href="index.shtml" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image6','','img/knopka-2.jpg',1)"><font color="#FFFFFF">....</font><img src="img/knopka.jpg" name="Image6" width="141" height="20" border="0"></a> 
        <a href="about.shtml" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image7','','img/knopka-4.jpg',1)"><img src="img/knopka-3.jpg" name="Image7" width="141" height="20" border="0"></a> 
        <a href="spec.shtml" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image8','','img/knopka-6.jpg',1)"><img src="img/knopka-5.jpg" name="Image8" width="153" height="20" border="0"></a> 
        <a href="contact.shtml" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image9','','img/knopka-8.jpg',1)"><img src="img/knopka-7.jpg" name="Image9" width="141" height="20" border="0"></a> 
        <font color="#FFFFFF"> </font></TD>
  </TR>

  <TR> 
<?php
if(isset($id) or isset($cid))
{
	echo '<TD width=100%  align=left vAlign=top bgcolor="#FFFFFF">
		<TABLE width="1000" height="100%" border=0 align="left" cellPadding=0 cellSpacing=0>
        <TBODY>
          <TR>';
}
else echo '<TD width=804  align=right vAlign=top bgcolor="#FFFFFF">
			<TABLE width=804 height="100%" border=0 align="left" cellPadding=0 cellSpacing=0>
        <TBODY>
          <TR>';
?>
            <TD width=804 align=left vAlign=top bordercolor="#CCCCCC" > 
              <P align="center">&nbsp;</P>
<font face="arial" size="2">