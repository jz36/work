<?
if (!defined("NX_PATH")) define ("NX_PATH", "../");

require_once(NX_PATH.'wedadmin/lib/global.lib.php');

require_once(NX_PATH.'wedadmin/config.inc.php');

require_once(NX_PATH.'wedadmin/lib/mysql.lib.php');



if (!defined("WA_PATH")) define("WA_PATH", "./");

if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");

@set_magic_quotes_runtime(0);

@session_start();

if (!isset($_SESSION['hide_ext'])){

	$_SESSION['hide_ext'] = false;

}

if (isset($_GET['hide'])){

	$_SESSION['hide_ext'] = true;

}

if (isset($_GET['show'])){

	$_SESSION['hide_ext'] = false;

}

if (!isset($_SESSION["WA_USER"])) { require ("index.php"); die(); }

require_once(NX_PATH.'wedadmin/header.inc.php');

db_open();

if ($_POST['add']) {Add($_GET['id'], $_POST['product']);}
GetCategory($_GET['id']);
ShowProducts($_GET['id']);
AddForm($_GET['id']);

function GetCategory($id) {
	$sql="SELECT title FROM wed_categories WHERE id = '$id'";
	$result=mysql_query($sql);
	list($title)=mysql_fetch_array($result);
	print "<font color='red'><b>Категория: $title</b></font><br><br>";
}

function ShowProducts($id) {
	$sql="SELECT items FROM wed_items WHERE cid = '$id'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if (!$num) {
		print "В данной категории нет ни одного поля!<br>";
		print "Используйте <a href='catalog.php?edit=$id&check=edititems'>эту форму</a> для добавления полей!";
		return 0;
	}
	print "<table border='1' cellspacing='0' cellpadding='3'>";
	list($items)=mysql_fetch_array($result);
	$e=explode(";",$items);
	print "<tr>";
	for ($i=0;$i<count($e);$i++) {
		print "<td><b>$e[$i]</b></td>";
	}
	print "</tr>";
	$sql="SELECT body FROM wed_products WHERE cid = '$id' ORDER BY id ASC";
	$result=mysql_query($sql);
	while(list($body)=mysql_fetch_array($result)) {
		$m=explode("<||>",$body);
		print "<tr>";
		for ($i=0;$i<count($e);$i++) {
			if ($m[$i]) {
				print "<td>$m[$i]</td>";
			} else {
				print "<td>&nbsp;</td>";
			}
		}
		print "</tr>";
	}
	print "</table>";
}

function AddForm($id) {
	$sql="SELECT items FROM wed_items WHERE cid = '$id'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if (!$num) {return 0;}
	list($items)=mysql_fetch_array($result);
	print "<form method='post' action=''>";
	print "<b><i>$items</i></b>, разделенные табуляцией!<br>";
	print "<textarea name='product' style='width: 600px; height: 200px;'></textarea><br>";
	print "<input type='submit' name='add' value='Добавить'>";
	print "</form>";
}

function Add($id, $product) {
	if (!$product or !$id) {print "error!"; return 0;}
	$product=str_replace("	","<||>",$product);
	$e=explode("\r\n",$product);
	for ($i=0;$i<count($e);$i++) {
		if ($e[$i]) {
			$add="INSERT INTO wed_products VALUES ('', '$id', '".$e[$i]."')";
			mysql_query($add);
		}
	}
	print "ok!<br><br>";
}

require_once(NX_PATH.'wedadmin/footer.inc.php');
?>