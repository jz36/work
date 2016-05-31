<?require("../admin/functions.php");set_connection();?>
<html>
<head>
<title>Схема проезда</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">
<!--
body,td,div,p  {
	font-family: Arial, Verdana, Tahoma, sans-serif;
	font-size: 11px;
}
body {
	margin: 0px;
	padding: 0px;
	background-color: #7D8693;
}
A,A:visited,A:link,A:active {
	color: #fff;
	text-decoration: none;
	font-family: Arial, Verdana, Tahoma, sans-serif;
}	
A:hover {
	color: #fff;
	text-decoration: underline;
-->
</style>
</head>

<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 bgcolor=#ffffff>
<img src="../<?=$file;?>" border=0>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr><td><a href="javascript://" onClick="pDoit();">Распечатать карту</a></td>
<td align=right><a href="javascript://" onClick="javascript:window.close();">Закрыть окно</a></td></tr>
</table>
</body></html>