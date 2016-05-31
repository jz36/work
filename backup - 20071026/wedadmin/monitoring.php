<?php

$xihna_enabled = false;

if (!defined("NX_PATH")) define ("NX_PATH", "../");

if (!defined("WA_URL")) define ("WA_URL", "../wedadmin/");

if (!defined("WA_PATH")) define("WA_PATH", "./");

require_once(NX_PATH.'wedadmin/lib/global.lib.php');

require_once(NX_PATH.'wedadmin/config.inc.php');

require_once(NX_PATH.'wedadmin/lib/mysql.lib.php');

include_once(WA_PATH.'lib/news.lib.php');

@set_magic_quotes_runtime(0);

db_open();

if (!isset($_SESSION["WA_USER"])) {Header("Location: index.php");}

///////////

if ($_POST['add']) {
	AddServer($_POST['name'], $_POST['type'], $_POST['host'], $_POST['port'], $_POST['qport']);
	Header("Location: monitoring.php");
}

if ($_POST['edit']) {
	EditServer($_POST['id'], $_POST['name'], $_POST['type'], $_POST['host'], $_POST['port'], $_POST['qport']);
	Header("Location: monitoring.php");
}

if (isset($_GET['order']) AND isset($_GET['id'])) {
	if ($_GET['order']<1) {$_GET['order']=1;}
	$sql="SELECT `order` FROM wed_monitoring ORDER BY `order` DESC LIMIT 1";
	$result=mysql_query($sql);
	list($order_max)=mysql_fetch_array($result);
	if ($_GET['order']>$order_max) {$_GET['order']=$order_max;}

	$sql="SELECT `order` FROM wed_monitoring WHERE id = '$_GET[id]'";
	$result=mysql_query($sql);
	list($order)=mysql_fetch_array($result);
	$sql="UPDATE wed_monitoring SET `order` = '$order' WHERE `order` = '$_GET[order]'";
	mysql_query($sql);
	$sql="UPDATE wed_monitoring SET `order` = '$_GET[order]' WHERE id = '$_GET[id]'";
	mysql_query($sql);
	Header("Location: $_SERVER[HTTP_REFERER]");
}
if (isset($_GET['top']) and isset($_GET['id'])) {
	$sql="SELECT `order` FROM wed_monitoring WHERE id = '$_GET[id]'";
	$result=mysql_query($sql);
	list($order)=mysql_fetch_array($result);
	$sql="SELECT `order` FROM wed_monitoring ORDER BY `order` DESC LIMIT 1";
	$result=mysql_query($sql);
	list($order_max)=mysql_fetch_array($result);

	if ($_GET['top']==1) {
		$sql="UPDATE wed_monitoring SET `order` = `order`+1 WHERE `order` < '$order'";
		mysql_query($sql);
		$sql="UPDATE wed_monitoring SET `order` = '1' WHERE id = '$_GET[id]'";
		mysql_query($sql);
	} else {
		$sql="UPDATE wed_monitoring SET `order` = `order`-1 WHERE `order` > '$order'";
		mysql_query($sql);
		$sql="UPDATE wed_monitoring SET `order` = '$order_max' WHERE id = '$_GET[id]'";
		mysql_query($sql);
	}
	Header("Location: $_SERVER[HTTP_REFERER]");
}

if ($_GET['unvis']) {
	$sql="UPDATE wed_monitoring SET hide = '1' WHERE id = '$_GET[unvis]'";
	mysql_query($sql);
	Header("Location: $_SERVER[HTTP_REFERER]");
}

if ($_GET['vis']) {
	$sql="UPDATE wed_monitoring SET hide = '0' WHERE id = '$_GET[vis]'";
	mysql_query($sql);
	Header("Location: $_SERVER[HTTP_REFERER]");
}

if ($_GET['del']) {
	$sql="SELECT `order` FROM wed_monitoring WHERE id = '$_GET[del]'";
	$result=mysql_query($sql);
	list($order)=mysql_fetch_array($result);
	$sql="DELETE FROM wed_monitoring WHERE id = '$_GET[del]'";
	mysql_query($sql);
	$sql="UPDATE wed_monitoring SET `order` = `order`-1 WHERE `order` > '$order'";
	mysql_query($sql);
	Header("Location: $_SERVER[HTTP_REFERER]");
}

include(WA_PATH.'header.inc.php');


if (isset($_GET['add'])) {
	AddServerForm();
} else if ($_GET['edit']) {
	EditServerForm($_GET['edit']);
} else {
	ShowServers();
}

include(WA_PATH.'footer.inc.php');

function ShowServers() {
	print "<script language='javascript'>
		function sure() {
			a=confirm('Действительно удалить?');
			if (a) {
				return true;
			} else {
				return false;
			}
		}
	</script>";
	$sql="SELECT id, name, type, host, port, qport, hide, `order` FROM wed_monitoring ORDER BY `order`";
	$result=mysql_query($sql);
	print "<center><a href='?add'>[ Добавить сервер ]</a></center><br>";
	print "<table border='0' cellspacing='1' cellpadding='3' align='center' width='500'>";
	print "<tr bgcolor='#cccccc'><td align='center'><b>Order</b></td><td><b>Название</b></td><td><b>Тип</b></td><td><b>Хост</b></td><td align='right'><b>Порт</b></td><td align='right'><b>Query-порт</b></td><td align='center'><b>Edit</b></td></tr>";
	$color="ffffff";
	while (list($id, $name, $type, $host, $port, $qport, $hide, $order)=mysql_fetch_array($result)) {
		if ($color=="eeeeee") {$color="ffffff";} else {$color="eeeeee";}
		if ($hide==0) {
			$visible="<a href='?unvis=$id'><img src='images/star_on_2.gif' border='0' alt='Скрыть' /></a>";
		} else {
			$visible="<a href='?vis=$id'><img src='images/star_off_2.gif' border='0' alt='Скрыть' /></a>";
		}
		print "<tr bgcolor='#$color'><td align='center'><a href='?id=$id&amp;order=".($order-1)."'><img src='images/upbl.gif' border='0' alt='Выше' /></a>&nbsp;<a href='?id=$id&amp;order=".($order+1)."'><img src='images/downbl.gif' border='0' alt='Ниже' /></a>&nbsp;<a href='?id=$id&amp;top=1'><img src='images/upbld.png' border='0' alt='Вверх' /></a>&nbsp;<a href='?id=$id&amp;top=0'><img src='images/downbld.png' border='0' alt='Вниз' /></a></td><td><a href='?edit=$id' title='Редактировать'>$name</a></td><td>$type</td><td>$host</td><td align='right'>$port</td><td align='right'>$qport</td><td align='center'>".$visible."&nbsp;&nbsp;&nbsp;<a href='?edit=$id'><img src='images/editor.png' border='0' alt='Редактировать' /></a>&nbsp;&nbsp;&nbsp;<a href='?del=$id' onclick='return sure();'><img src='images/deleteor.gif' border='0' alt='Удалить' /></a></td></tr>";
	}
	print "</table><br>";
	print "<center><a href='?add'>[ Добавить сервер ]</a></center>";
}

function AddServerForm() {
	print "<form method='post' action=''>";
	print "<table border='0' cellspacing='0' cellpadding='3'>";
	print "<tr><td>Название:</td><td><input type='text' name='name' /></td></tr>";
	print "<tr><td>Тип игры:</td><td>";
	print "<select name='type'>";
	print '<option value="aa">America`s Army</option><option value="atron">Armagetronad</option><option value="bf">Battlefield 1942</option><option value="bfv">Battlefield Vietname</option><option value="bf2">Battlefield 2</option><option value="cod">Call of Duty</option><option value="cod2">Call of Duty 2</option><option value="descent3">Descent 3 (without gamespy)</option><option value="des3gs">Descent 3 (gamespy)</option><option value="d3">Doom 3</option><option value="et">Enemy Territory</option><option value="fear">FEAR</option><option value="halo">HALO</option><option value="hl_old">Half-Life / CS OLD</option><option value="hl">Half-Life / CS</option><option value="hl2">Half-Life 2 / CS:S</option><option value="hd2">Hidden & Dangerous 2</option><option value="jedi">Jedi Knight: Jedi Academy</option><option value="jedi2">Jedi knight II</option><option value="mohaa">Medal of Honor</option><option value="nolf">No One Lives Forever</option><option value="pk">Painkiller</option><option value="ro">Red Orchestra</option><option value="rtcw">Return to Castle Wolfenstein</option><option value="rune">Rune</option><option value="sof2">Soldier of Fortune 2</option><option value="swat">SWAT 4</option><option value="ts">Teamspeak</option><option value="ut">Unreal Tournament</option><option value="ut2003">Unreal Tournament 2003</option><option value="ut2004">Unreal Tournament 2004</option><option value="qw">Quakeworld</option><option value="q1">Quake 1</option><option value="q2">Quake 2</option><option value="q3">Quake 3</option><option value="q4">Quake 4</option><option value="warsow">Warsow</option>';
	print "</select>";
	print "</td></tr>";
	print "<tr><td>Хост:</td><td><input type='text' name='host' /></td></tr>";
	print "<tr><td>Порт:</td><td><input type='text' name='port' /></td></tr>";
	print "<tr><td>Query порт:</td><td><input type='text' name='qport' /></td></tr>";
	print "<tr><td colspan='2' align='right'><input type='submit' name='add' value='Добавить' /></td></tr>";
	print "</table>";
	print "</form>";
}

function AddServer($name, $type, $host, $port, $qport) {
	$sql="SELECT `order` FROM wed_monitoring ORDER BY id DESC LIMIT 1";
	$result=mysql_query($sql);
	list($order)=mysql_fetch_array($result);
	if (!$order) {$order=0;}
	$order++;
	$sql="INSERT INTO wed_monitoring VALUES ('', '$name', '$type', '$host', '$port', '$qport', '0', '$order')";
	mysql_query($sql);
}

function EditServerForm($id) {
	$sql="SELECT name, type, host, port, qport, hide, `order` FROM wed_monitoring WHERE id = '$id'";
	$result=mysql_query($sql);
	list($name, $type, $host, $port, $qport)=mysql_fetch_array($result);

	print "<form method='post' action=''>";
	print "<input type='hidden' name='id' value='$id' />";
	print "<table border='0' cellspacing='0' cellpadding='3'>";
	print "<tr><td>Название:</td><td><input type='text' name='name' value='$name' /></td></tr>";
	print "<tr><td>Тип игры:</td><td>";
	print "<select name='type'>";

	$mas1[]="aa"; $mas2[]="America`s Army";
	$mas1[]="atron"; $mas2[]="Armagetronad";
	$mas1[]="bf"; $mas2[]="Battlefield 1942";
	$mas1[]="bfv"; $mas2[]="Battlefield Vietname";
	$mas1[]="bf2"; $mas2[]="Battlefield 2";
	$mas1[]="cod"; $mas2[]="Call of Duty";
	$mas1[]="cod2"; $mas2[]="Call of Duty 2";
	$mas1[]="descent3"; $mas2[]="Descent 3 (without gamespy)";
	$mas1[]="des3gs"; $mas2[]="Descent 3 (gamespy)";
	$mas1[]="d3"; $mas2[]="Doom 3";
	$mas1[]="et"; $mas2[]="Enemy Territory";
	$mas1[]="fear"; $mas2[]="FEAR";
	$mas1[]="halo"; $mas2[]="HALO";
	$mas1[]="hl_old"; $mas2[]="Half-Life / CS OLD";
	$mas1[]="hl"; $mas2[]="Half-Life / CS";
	$mas1[]="hl2"; $mas2[]="Half-Life 2 / CS:S";
	$mas1[]="hd2"; $mas2[]="Hidden & Dangerous 2";
	$mas1[]="jedi"; $mas2[]="Jedi Knight: Jedi Academy";
	$mas1[]="jedi2"; $mas2[]="Jedi knight II";
	$mas1[]="mohaa"; $mas2[]="Medal of Honor";
	$mas1[]="nolf"; $mas2[]="No One Lives Forever";
	$mas1[]="pk"; $mas2[]="Painkiller";
	$mas1[]="ro"; $mas2[]="Red Orchestra";
	$mas1[]="rtcw"; $mas2[]="Return to Castle Wolfenstein";
	$mas1[]="rune"; $mas2[]="Rune";
	$mas1[]="sof2"; $mas2[]="Soldier of Fortune 2";
	$mas1[]="swat"; $mas2[]="SWAT 4";
	$mas1[]="ts"; $mas2[]="Teamspeak";
	$mas1[]="ut"; $mas2[]="Unreal Tournament";
	$mas1[]="ut2003"; $mas2[]="Unreal Tournament 2003";
	$mas1[]="ut2004"; $mas2[]="Unreal Tournament 2004";
	$mas1[]="qw"; $mas2[]="Quakeworld";
	$mas1[]="q1"; $mas2[]="Quake 1";
	$mas1[]="q2"; $mas2[]="Quake 2";
	$mas1[]="q3"; $mas2[]="Quake 3";
	$mas1[]="q4"; $mas2[]="Quake 4";
	$mas1[]="warsow"; $mas2[]="Warsow";

	for ($i=0;$i<count($mas1);$i++) {
		if ($mas1[$i]==$type) {$selected="selected";} else {$selected="";}
		print "<option value='$mas1[$i]' $selected>$mas2[$i]</option>";
	}

	print "</select>";
	print "</td></tr>";
	print "<tr><td>Хост:</td><td><input type='text' name='host' value='$host' /></td></tr>";
	print "<tr><td>Порт:</td><td><input type='text' name='port' value='$port' /></td></tr>";
	print "<tr><td>Query порт:</td><td><input type='text' name='qport' value='$qport' /></td></tr>";
	print "<tr><td colspan='2' align='right'><input type='submit' name='edit' value='Сохранить' /></td></tr>";
	print "</table>";
	print "</form>";
}

function EditServer($id, $name, $type, $host, $port, $qport) {
	$sql="UPDATE wed_monitoring SET name = '$name', type = '$type', host = '$host', port = '$port', qport = '$qport' WHERE id = '$id'";
	mysql_query($sql);
}
?>