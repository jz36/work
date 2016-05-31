<script>strok=location.href;if (strok.search("<? echo PATH;?>/")>0) location.href="<? echo PATH; ?><?=PAGE?>";</script>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="<?=SITE_ADMIN_DIR?>/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin: 0px;
	padding: 0px;
	background-color: #ECEBE5;
	}
h2 {
	color:#e00000;
	}
textarea, list, select, option, input {
	font-size: 12px;
	}
.error {
	color:#ff0000;
	font-weight:bold;
	font-size:12px;
	margin:10px;
}

-->
</style></head>

<body onload="document.form.user_name.focus()">
<table width="100%"  height=60% border="0" cellspacing="0" cellpadding="10">
<tr><td  height=50px align=center>
<?if (empty($db_error)) {?>
<h2><?=s_select("content","admin_site","param='site_title'")?></h2>
<h3>Система управления сайтом <a href=<?=PATH?>><?=PATH?></a></h3></td></tr>
<tr>
<td align="center" bgcolor="#f2f2f2">
<div class=error><?=$error?></div>
<table width="300"  border="0" cellpadding="3" cellspacing="1" bgcolor="#eeeeee" style="border:1px solid #999">
<form action="<?=PAGE?>" method=post name=form><input type=hidden name=rand value=<? echo $rand ?>>
<tr align="center" bgcolor="#86B7DB">
<td height="25"  colspan=2 class=white><b>Вход в режим управления сайтом </b></td>
</tr>
<tr bgcolor="#FFFFFF">
<?
if (ereg("localhost",PATH)) {$login="admin";$pwd="passworr";}
?>
<td align="right">Логин:</td>
<td align="left"><input type="text" name="user_name" maxlength=20 value="<?=@$login?>"></td>
</tr>
<tr bgcolor="#FFFFFF">
<td align="right">Пароль:</td>
<td align="left"><input type="password" name="user_passw" maxlength=20 value="<?=@$pwd?>"></td>
</tr>
<tr bgcolor="#fafafa">
<td align="right"></td>
<td align="left" class=small ><input type="checkbox" class="noborder" name="coockie" id="coockie" maxlength=20 value="<?=@$pwd?>">
<label for="coockie">Запомнить меня на этом компьютере</label></td>
</tr>
<tr bgcolor="#FAFAFA">
<td>&nbsp;</td>
<td align="left"><input type="submit" name="Submit" value="Войти"></td>
</tr>
</form>
</table>
<? }
else echo "<div class=error>$error</div>Обратитесь к администратору";?>
</td>
</tr>
</table>
<? print_r($dbg_listing);?>
</body>
</html>