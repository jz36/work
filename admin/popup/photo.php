<?
require"".SITE_ADMIN_DIR."/functions.php";
set_connection();
?>
<html>
<head>
<?
if ($pref=="images")
	s_select("name",$pref,"id=".$id);
?>
<title>Фото</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">
<!--
td,tr,table,body,p,div, ol, ul, li {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; color: #334E76}
-->
</style>
</head>
<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 bgcolor=#D5DAE2>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
   <tr>
		<td align=center>
<?
$src=getimg($pref,$id,$ended);

?>
  <img src='<?=$src?>' border=0>

</td>
	</tr>
<tr><td><center><a href="javascript:print();">Распечатать картинку</a> | <a href='#' onClick='self.close();'>Закрыть окно</a></center></td></tr>
</table>
</body></html>