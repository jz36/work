<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><? echo PATH; ?>. Система управления сайтом.</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>
<script>var admin_path="<?=SITE_ADMIN_DIR?>";</script>
<script language="JavaScript" src="<?=SITE_ADMIN_DIR?>/js/admin_scripts.js"></script>

<!-- STEP 1: Editor Localization: Include language file -->
<script language=JavaScript src='<?=SITE_ADMIN_DIR?>/editor/scripts/language/russian/editor_lang.js'></script>
<script>
//oEdit1.publishingPath = "<?=PATH?>";
</script>
<!-- STEP 1: Include the Editor js file -->
<?

//Check user's Browser
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
	echo "<script language=JavaScript src='".SITE_ADMIN_DIR."/editor/scripts/editor.js'></script>";
else
	echo "<script language=JavaScript src='".SITE_ADMIN_DIR."/editor/scripts/moz/editor.js'></script>";

?>
<script>
function submitForm()
	{
	//STEP 4: Set the Hidden Form field with the edited content.
	document.forms.adform.elements.inpContent.value = oEdit1.getHTMLBody();
	
	//STEP 5: Submit the Form.
	document.forms.adform.submit()
	}
</script>
<link href="<?=SITE_ADMIN_DIR?>/style.css" rel="stylesheet" type="text/css">
<body>
<!-------- Для редактора  -->
<pre id="idTemporary" name="idTemporary" style="display:none">
<?
if(isset($_POST["inpContent"])) 
	{
	$sContent=stripslashes($_POST['inpContent']);//remove slashes (/)	
	echo htmlentities($sContent);
	}
?>
</pre>
<!-------- ---------------  -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td  class="top small" align=left>
<select onChange="jmpMenu('parent',this,0)" style="width:100%">
<option value=#>Настройка сайта --></option>
<?
for ($i=2;$i<4;$i++){?>
<option value=#>--------------------------</option>
<?$res=mysql_query("select name,page,shablon,id from ".PREF."_admin_tree where global_id=\"$i\" and (menu_top=\"0\" OR menu_top='') order by ord");
	while($r=mysql_fetch_row($res)){
	if (@main_access($r[1])!=0){?>
		<option value="<?=PAGE?>?main=<?=$r[1]?>&rand=<? echo $rand ?>" <?//if ($main==$r[1]) echo "selected";?>><?=$r[0]?></option>
	<?	}
	$res1=mysql_query("select name,page,shablon from ".PREF."_admin_tree where  global_id=\"$i\" and menu_top=\"$r[3]\" order by ord");
	while($r1=mysql_fetch_row($res1)){
	if (@main_access($r[1])!=0){?>
		<option value="<?=PAGE?>?main=<?=$r1[1]?>&rand=<? echo $rand ?>" <?//if ($main==$r1[1]) echo "selected";?>>-- <?=$r1[0]?></option>
<?}}}
if ($i==3){?>
		<option value="<?=PAGE?>?main=exit&rand=23008" >Выйти из системы</option>
<?}?>
<?}?>
</select>
</td>
<td class="top small">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
&nbsp;
</td>
<td><input type=button style="width:150px;" value="Создать новый раздел" onClick="window.location.href='<?=PAGE?>?main=admin_tree&rand=28947&delcookie=1&add=1'"></td>
<td width=100% align=right>
<form action="<?=PAGE?>?main=search" method="get">
<input type="hidden" name="main" value="search" >
<input id=ft name="search_text" type="text" value="поиск по сайту" onFocus="if(this.form.search_text.value=='поиск по сайту') this.form.search_text.value=''" onBlur="if(this.form.search_text.value=='') this.form.search_text.value='поиск по сайту'" style="width:120px">
<input type="submit" name="Submit" value="Ок" onClick="if(ft.value=='поиск по сайту') {alert('Введите строку для поиска');return false;}">
</form></td>
</tr></table>		
</td></tr>
<tr>
<td valign=top width=20%><img src="<?=SITE_ADMIN_DIR?>/img/0.gif" width=160></td>
<td valign="top">