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

if ($_GET['check']=="editprod") {$xihna_enabled = true;} else {$xihna_enabled = false;}

require_once(NX_PATH.'wedadmin/header.inc.php');

db_open();


if ($_POST['add']) {
	Add($_POST['parent'], $_POST['category'], $_POST['info'], $_POST['items']);
}
if ($_GET['del']) {
	Del($_GET['del']);
}
if ($_GET['edit']) {
	GetCategory($_GET['edit']);
	switch ($_GET['check']) {
		case "delprod":
		Delprod($_GET['id']);
		EditF($_GET['edit']);
		break;

		case "editprod":
		EditProdForm($_GET['edit'], $_GET['id']);
		MainPhoto($_GET['id']);
		MiniInfo($_GET['id']);
		FullInfo($_GET['id']);
		break;

		case "edititems":
		EditItemsForm($_GET['edit']);
		break;

		default:
		EditF($_GET['edit']);
		EditCat($_GET['edit']);
		break;
	}
} else if ($_GET['copy']) {
	CopyForm($_GET['copy']);
} else {
	EditForm();
	AddForm();
}

function GetCategory($id) {
	$sql="SELECT title FROM wed_categories WHERE id = '$id'";
	$result=mysql_query($sql);
	list($title)=mysql_fetch_array($result);
	print "<font color='red'><b>Категория: $title</b></font><br><br>";
}

function Row($parent, $level) {
	$parentid=0;
	if ($_GET['copy']) {$_GET['edit']=$_GET['copy'];}
	if ($_GET['edit']) {
		$sql2="SELECT parentid FROM wed_categories WHERE id = '$_GET[edit]'";
		$result2=mysql_query($sql2);
		list($parentid)=mysql_fetch_array($result2);
	}
	$sql="SELECT id, title FROM wed_categories WHERE parentid = '$parent' ORDER BY binary(title)";
	$result=mysql_query($sql);
	while (list($id, $title)=mysql_fetch_array($result)) {
		$tire='';
		for ($l=0;$l<$level;$l++) {$tire.='&nbsp;&nbsp;&nbsp;';}
		if ($id==$parentid) {$selected="selected";} else {$selected="";}
		print "<option value='$id' $selected>".$tire."|--".$title."</option>"."\r\n";
		$a=$level; $a++;
		Row($id, $a);
	}
}

function Row2($parent, $level) {
	$sql="SELECT id, title FROM wed_categories WHERE parentid = '$parent' ORDER BY binary(title)";
	$result=mysql_query($sql);
	while (list($id, $title)=mysql_fetch_array($result)) {
		$tire='';
		for ($l=0;$l<$level;$l++) {$tire.='&nbsp;&nbsp;'; };
		print "<tr><td align='center'><a href='?edit=$id'><img src='images/editor.png' border='0' alt='Редактировать'></a>&nbsp;<a href='?copy=$id'><img src='images/copy.gif' border='0' alt='Копировать'></a></td><td>".$tire."|--<a href='catalog_products.php?id=$id'>".$title."</a></td><td align='center'><a href='?del=$id' onclick=\"return Sure('Действительно желаете удалить категорию $title?');\"><img src='images/deleteor.gif' border='0' alt='Удалить'></a></td></tr>"."\r\n";
		$a=$level; $a++;
		Row2($id, $a);
	}
}

function AddForm() {
	print "<form method='post' action=''>"
	."<table border='0'>"
	."<tr><td>Родительская категория:&nbsp;</td><td>"
		."<select name='parent'>"
		."<option value='0'>Главная категория</option>"."\r\n";
			Row(0,1);
		print "</select></td></tr>"
	."<tr><td>Название категории:</td><td><input type='text' name='category' style='width: 100%;'></td></tr>"
	."<tr style='height: 100px;'><td>Описание категории:</td><td><textarea name='info' style='width: 100%; height: 100%;'></textarea></td></tr>"
	."<tr style='height: 100px;'><td>Поля в категории<br>(через точку с запятой!).<br>Пример:<br>Название;Тип;Цена:</td><td><textarea name='items' style='width: 100%; height: 100%;'></textarea></td></tr>"
	."<tr><td colspan='2' align='right'><input type='submit' name='add' value='Добавить'></td></tr>"
	."</table>"
	."</form>";
}

function Add($parent, $category, $info, $items) {
	if (!$category) {print "Не заполнены обязательные поля!"; return 0;}
	$add="INSERT INTO wed_categories VALUES ('', '$parent', '$category', '$info')";
	mysql_query($add);
	if ($items) {
		$cid=mysql_insert_id();
		$add2="INSERT INTO wed_items VALUES ('', '$cid', '$items')";
		mysql_query($add2);
	}
	print "ok!";
}

function EditForm() {
	print "<table border='1' cellspacing='0' cellpadding='3'>"
	."<tr><td>&nbsp;</td><td>Главная категория</td><td>&nbsp;</td></tr>";
	Row2(0,1);
	print "</table><br><br>";
}

function Del($id) {
	$del="DELETE FROM wed_categories WHERE id = '$id'";
	mysql_query($del);
	$del2="DELETE FROM wed_categories WHERE parentid = '$id'";
	mysql_query($del2);
	$sql="SELECT parentid FROM wed_categories WHERE parentid > '0'";
	$result=mysql_query($sql);
	while (list($parentid)=mysql_fetch_array($result)) {
		$sql2="SELECT * FROM wed_categories WHERE id = '$parentid'";
		$result2=mysql_query($sql2);
		$num=mysql_num_rows($result2);
		if (!num) {
			$del3="DELETE FROM wed_categories WHERE id = '$parentid'";
			mysql_query($del3);
		}
	}
	print "ok!";
}

function EditF($id) {
	$sql="SELECT items FROM wed_items WHERE cid = '$id'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if (!$num) {
		print "<a href='catalog.php?edit=$id&check=edititems'>Добавить поля</a><br>";
		return 0;
	}
	print "<table border='1' cellspacing='0' cellpadding='3'>";
	list($items)=mysql_fetch_array($result);
	$e=explode(";",$items);
	print "<tr><td align='center'><a href='?edit=$id&check=edititems'><img src='images/editor.png' border='0' alt='Редактировать'></a></td>";
	for ($i=0;$i<count($e);$i++) {
		print "<td><b>$e[$i]</b></td>";
	}
	print "<td>&nbsp;</td></tr>";
	$sql="SELECT id, body FROM wed_products WHERE cid = '$id' ORDER BY id ASC";
	$result=mysql_query($sql);
	while(list($prid, $body)=mysql_fetch_array($result)) {
		$m=explode("<||>",$body);
		print "<tr><td align='center'><a href='?edit=$id&check=editprod&id=$prid'><img src='images/editor.png' border='0' alt='Редактировать'></a></td>";
		for ($i=0;$i<count($e);$i++) {
			if ($m[$i]) {
				print "<td>$m[$i]</td>";
			} else {
				print "<td>&nbsp;</td>";
			}
		}
		print "<td align='center'><a href='?edit=$id&check=delprod&id=$prid' onclick=\"return Sure('Действительно желаете удалить продукт?');\"><img src='images/deleteor.gif' border='0' alt='Удалить'></a></td></tr>";
	}
	print "</table>";
}

function Delprod($id) {
	$del="DELETE FROM wed_products WHERE id = '$id'";
	mysql_query($del);
	print "ok!";
}

function EditProdForm($cid, $id) {
	if ($_POST['edit']) {
		$body="";
		for ($j=0;$j<count($_POST['prod']);$j++) {
			if ($j==count($_POST['prod'])-1) {$end="";} else {$end="<||>";}
			$body.=$_POST['prod'][$j].$end;
		}
		$sql="UPDATE wed_products SET body = '$body' WHERE id = '$id'";
		mysql_query($sql);
		print "ok!";
	}
	print "<form method='post' action='?edit=$cid&check=editprod&id=$id'>";
	print "<table border='0' style='width: 450px;'>";
	$sql2="SELECT items FROM wed_items WHERE cid = '$cid'";
	$result2=mysql_query($sql2);
	list($items)=mysql_fetch_array($result2);
	$sql="SELECT body FROM wed_products WHERE id = '$id'";
	$result=mysql_query($sql);
	list($body)=mysql_fetch_array($result);
	$e=explode(";",$items);
	$m=explode("<||>",$body);
	for ($i=0;$i<count($e);$i++) {
		print "<tr><td nowrap><b>$e[$i]:&nbsp;</b></td><td style='width: 100%;'><input type='text' name='prod[]' value='$m[$i]' style='width: 100%;'></td></tr>";
	}
	print "<tr><td colspan='2' align='right'><input type='submit' name='edit' value='Сохранить'></td></tr>";
	print "</table>";
	print "</form>";
}

function EditItemsForm($cid) {
	if ($_POST['edit']) {
		for ($j=0;$j<count($_POST['item']);$j++) {
			if ($j==count($_POST['item'])-1 or !$_POST['item'][$j]) {$end="";} else {$end=";";}
			if ($_POST['item'][$j]) {$k[]=$j;}
			$items2.=$_POST['item'][$j].$end;
		}
		$if=substr($items2,-1);
		if ($if==";") {
			$items2=substr($items2, 0, -1);
		}
		if (!$items2) {
			$del="DELETE FROM wed_items WHERE cid = '$cid'";
			mysql_query($del);
			$del2="DELETE FROM wed_products WHERE cid = '$cid'";
			mysql_query($del2);
		} else {
			$sql="UPDATE wed_items SET items = '$items2' WHERE cid = '$cid'";
			mysql_query($sql);

			$sql2="SELECT id, body FROM wed_products WHERE cid = '$cid'";
			$result2=mysql_query($sql2);
			while (list($pid, $body)=mysql_fetch_array($result2)) {
				$e=explode("<||>",$body);
				$body2="";
				for ($l=0;$l<count($k);$l++) {
					if ($l==count($k)-1) {$end2="";} else {$end2="<||>";}
					$body2.=$e[$k[$l]].$end2;
				}
				$sql3="UPDATE wed_products SET body = '$body2' WHERE id = '$pid'";
				mysql_query($sql3);
			}
		}
		print "ok!<br><br>";
	}
	if ($_POST['edit2']) {
		$sql="SELECT * FROM wed_items WHERE cid = '$cid'";
		$result=mysql_query($sql);
		$ok=mysql_num_rows($result);
		if ($ok) {
			$sql2="UPDATE wed_items SET items = '$_POST[items]' WHERE cid = '$cid'";
			mysql_query($sql2);
		} else {
			$sql2="INSERT INTO wed_items VALUES ('', '$cid', '$_POST[items]')";
			mysql_query($sql2);
		}
	}
	$sql="SELECT items FROM wed_items WHERE cid = '$cid'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	if ($num) {
		print "Чтобы удалить какое-нибудь значение, просто оставьте соответствующее поле пустым!<br><br>";
		print "<form method='post' action=''>";
		list($items)=mysql_fetch_array($result);
		$e=explode(";",$items);
		for ($i=0;$i<count($e);$i++) {
			print "<input type='text' name='item[]' value='$e[$i]'><br>";
		}
		print "<input type='submit' name='edit' value='Сохранить'>";
		print "</form><br>";
		$next="ВНИМАНИЕ! Удалять поля в этой форме нельзя! Используйте форму выше!<br>";
	} else {
		$next="";
	}
	print "Для добавления поля, добавьте значения в нужном порядке (поля отделяются точкой с запятой!)<br>$next";
	print "<form method='post' action=''>";
	print "<textarea name='items' style='width: 400px; height: 150px;'>$items</textarea><br>";
	print "<input type='submit' name='edit2' value='Сохранить'>";
	print "</form>";
}

function EditCat($id) {
	if ($_POST['edit']) {
		$sql="UPDATE wed_categories SET parentid = '$_POST[parent]', title = '$_POST[category]', info = '$_POST[info]' WHERE id = '$id'";
		mysql_query($sql);
		print "ok!<br>";
	}
	$sql="SELECT title, info FROM wed_categories WHERE id = '$id'";
	$result=mysql_query($sql);
	list($title, $info)=mysql_fetch_array($result);
	print "<form method='post' action=''>"
	."<table border='0'>"
	."<tr><td>Родительская категория:&nbsp;</td><td>"
		."<select name='parent'>"
		."<option value='0'>Главная категория</option>"."\r\n";
			Row(0,1);
		print "</select></td></tr>"
	."<tr><td>Название категории:</td><td><input type='text' name='category' value='$title' style='width: 100%;'></td></tr>"
	."<tr style='height: 100px;'><td>Описание категории:</td><td><textarea name='info' style='width: 100%; height: 100%;'>$info</textarea></td></tr>"
	."<tr><td colspan='2' align='right'><input type='submit' name='edit' value='Сохранить'></td></tr>"
	."</table>"
	."</form>";
}

function CopyForm($id) {
	if ($_POST['copy']) {
		$sql="INSERT INTO wed_categories VALUES ('', '$_POST[parent]', '$_POST[category]', '$_POST[info]')";
		mysql_query($sql);
		$lid=mysql_insert_id();
		$sql3="INSERT INTO wed_items VALUES ('', '$lid', '$_POST[items]')";
		mysql_query($sql3);
		print "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=catalog_products.php?id=$lid'>";
	}
	$sql2="SELECT items FROM wed_items WHERE cid = '$id'";
	$result2=mysql_query($sql2);
	list($items)=mysql_fetch_array($result2);
	print "<form method='post' action=''>"
	."<table border='0'>"
	."<tr><td>Родительская категория:&nbsp;</td><td>"
		."<select name='parent'>"
		."<option value='0'>Главная категория</option>"."\r\n";
			Row(0,1);
		print "</select></td></tr>"
	."<tr><td>Название категории:</td><td><input type='text' name='category' value='' style='width: 100%;'></td></tr>"
	."<tr style='height: 100px;'><td>Описание категории:</td><td><textarea name='info' style='width: 100%; height: 100%;'></textarea></td></tr>"
	."<tr style='height: 100px;'><td>Поля в категории<br>(через точку с запятой!).<br>Пример:<br>Название;Тип;Цена:</td><td><textarea name='items' style='width: 100%; height: 100%;'>$items</textarea></td></tr>"
	."<tr><td colspan='2' align='right'><input type='submit' name='copy' value='Скопировать'></td></tr>"
	."</table>"
	."</form>";
}

function resize($filename, $to, $type, $x) {
	list($width, $height) = getimagesize($filename);
	if ($width > $x) {
		$percent = $width / $x;
		$newwidth = $x;
		$newheight = $height / $percent;
		$thumb = imagecreatetruecolor($newwidth, $newheight);
		if ($type=="jpg" or $type=="jpeg") {
			$source = imagecreatefromjpeg($filename);
		} else if ($type=="gif") {
			$source = imagecreatefromgif($filename);
		} else if ($type=="png") {
			$source = imagecreatefrompng($filename);
		} else {
			return 0;
		}
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		imagejpeg($thumb, $to, 100);
	} else {
		copy($filename, $to);
	}
}

function MainPhoto($id) {
	if ($_POST['addphoto']) {
		$e=explode(".",$_FILES['photo']['name']);
		$end=$e[count($e)-1];
		$name=time();
		$photo=$name.".".$end;
		move_uploaded_file($_FILES['photo']['tmp_name'], "../catalogue/mainimages/temp/".$photo);
		resize("../catalogue/mainimages/temp/".$photo, "../catalogue/mainimages/large/".$photo, $end, 400);
		resize("../catalogue/mainimages/temp/".$photo, "../catalogue/mainimages/small/".$photo, $end, 100);
		unlink("../catalogue/mainimages/temp/".$photo);
		$sql="SELECT * FROM wed_info WHERE pid = '$id'";
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);
		if ($num) {
			$upd="UPDATE wed_info SET photo = '$photo' WHERE pid = '$id'";
			mysql_query($upd);
			print "ok!<br>";
		} else {
			$add="INSERT INTO wed_info VALUES ('', '$id', '$photo', '', '')";
			mysql_query($add);
			print "ok!<br>";
		}
	}
	if ($_GET['mainphoto']=="del") {
		$del="UPDATE wed_info SET photo = '' WHERE pid = '$id'";
		mysql_query($del);
		print "ok!<br>";
	}
	$sql="SELECT photo FROM wed_info WHERE pid = '$id'";
	$result=mysql_query($sql);
	list($photo)=mysql_fetch_array($result);
	if ($photo) {
		$img="<a href='../catalogue/mainimages/large/".$photo."' target='_blank'><img src='../catalogue/mainimages/small/".$photo."' border='0' alt='Увеличить'></a>";
		$del="<a href='?edit=$_GET[edit]&check=editprod&id=$id&mainphoto=del' onclick=\"return Sure('Удалить фотографию?');\"><img src='images/b_drop.png' border='0' alt='Удалить'></a>";
	} else {
		$img="&nbsp;";
		$del="&nbsp;";
	}
	print "<form method='post' enctype='multipart/form-data' action='?edit=$_GET[edit]&check=editprod&id=$id'>"
	."<b>Главная фотография:</b><br>"
	."<table border='0' width='400'>"
	."<tr><td style='width: 100%;'><input type='file' name='photo' style='width: 100%;'></td><td>$img</td><td style='padding-left: 15px;'>$del</td></tr>"
	."<tr><td><input type='submit' name='addphoto' value='Сохранить'></td><td>&nbsp;</td><td>&nbsp;</td></tr>"
	."</table>"
	."</form>";
}

function MiniInfo($id) {
	if ($_POST['addminiinfo']) {
		$sql="SELECT * FROM wed_info WHERE pid = '$id'";
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);
		if ($num) {
			$upd="UPDATE wed_info SET mini = '$_POST[miniinfo]' WHERE pid = '$id'";
			mysql_query($upd);
			print "ok!<br>";
		} else {
			$add="INSERT INTO wed_info VALUES ('', '$id', '', '$_POST[miniinfo]', '')";
			mysql_query($add);
			print "ok!<br>";
		}
	}
	$sql="SELECT mini FROM wed_info WHERE pid = '$id'";
	$result=mysql_query($sql);
	list($mini)=mysql_fetch_array($result);
	print "<form method='post' action='?edit=$_GET[edit]&check=editprod&id=$id'>"
	."<b>Краткое описание:</b><br>"
	."<textarea name='miniinfo' style='width: 350px; height: 100px;'>$mini</textarea><br>"
	."<input type='submit' name='addminiinfo' value='Сохранить'>"
	."</form>";
}

function FullInfo($id) {
	if ($_POST['addinfo']) {
		$sql="SELECT * FROM wed_info WHERE pid = '$id'";
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);
		if ($num) {
			$upd="UPDATE wed_info SET full = '$_POST[info]' WHERE pid = '$id'";
			mysql_query($upd);
			print "ok!<br>";
		} else {
			$add="INSERT INTO wed_info VALUES ('', '$id', '', '', '$_POST[info]')";
			mysql_query($add);
			print "ok!<br>";
		}
	}
	$sql="SELECT full FROM wed_info WHERE pid = '$id'";
	$result=mysql_query($sql);
	list($full)=mysql_fetch_array($result);
	print "<form method='post' action='?edit=$_GET[edit]&check=editprod&id=$id'>"
	."<b>Полное описание:</b><br>"
	."<textarea name='info' style='width: 550px; height: 300px;' id='info'>$full</textarea><br>"
	."<input type='submit' name='addinfo' value='Сохранить'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
	."<a href='lib_images.php?id=$id' onclick=\"open('lib_images.php?id=$id', 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=600,height=700,left=0, top=0,screenX=0,screenY=0'); return false;\">Загрузить изображение</a>"
	."</form>";
}

require_once(NX_PATH.'wedadmin/footer.inc.php');
?>