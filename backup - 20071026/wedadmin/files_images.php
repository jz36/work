<?
$id = $_GET['id'];
$path = "customimages";

$dir = "../files/$path/$id";

if ($_POST['upload'] and $_FILES['img']['name']) {
	move_uploaded_file($_FILES['img']['tmp_name'], "$dir/".$_FILES['img']['name']);
	chmod("$dir/".$_FILES['img']['name'], 0755);
	Header("Location: $_SERVER[HTTP_REFERER]");
}
if ($_GET['drop']) {
	unlink($_GET['drop']);
	Header("Location: $_SERVER[HTTP_REFERER]");
}
if ($_POST['resize']) {
	@resize($_POST['file'], $_POST['file'], $_POST['width']);
	Header("Location: $_SERVER[HTTP_REFERER]");
}

function resize($filename, $to, $x) {
	list($width, $height) = getimagesize($filename);
	$e = explode(".", $filename);
	$type = end($e);
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
	}
}

include "header.mini.php";

if (!@opendir($dir)) {
	mkdir($dir, 0777);
}

$handle = opendir($dir);
$i=0;
while (false !== ($file = readdir($handle))) {
	if ($file != "." && $file != "..") {
		$i++;
		list($width, $height) = getimagesize("http://$_SERVER[SERVER_NAME]/files/$path/$id/$file");
		print "<table border='0' cellspacing='0' cellpadding='3' style='width: 100%;'>";
		print "<tr><td><img src='http://$_SERVER[SERVER_NAME]/files/$path/$id/$file' border='0' alt='' /></td><td width='100%'><a onclick=\"return sure('$file');\" href='?drop=$dir/$file'><img src='images/b_drop.png' border='0' alt='Удалить' /></a></td></tr>";
		print "</table>";
		print "<b>Ссылка:</b> http://$_SERVER[SERVER_NAME]/files/$path/$id/$file<br />";
		print "<form method='post' action='' name='form$i'>";
		print "<input type='hidden' name='file' value='../files/$path/$id/$file' />";
		print "<table border='0' cellspacing='0' cellpadding='3'>";
		print "<tr><td><input maxlength='4' type='text' name='width' value='$width' id='width$i' onKeyUp=\"a=$width/this.value; document.form$i.height$i.value=Math.round($height/a);\" /></td><td>x</td><td><input maxlength='4' type='text' name='height' value='$height' id='height$i' onKeyUp=\"b=$height/this.value; document.form$i.width$i.value=Math.round($width/b);\" /></td><td><input type='submit' name='resize' value='Сохранить' /></tr>";
		print "</table>";
		print "</form><br />";
	}
}
closedir($handle);

print "<b>Загрузить:</b><br />";
print "<form method='post' action='' enctype='multipart/form-data'>";
print "<table border='0' cellspacing='0' cellpadding='3'>";
print "<tr><td><input type='file' name='img' /></td><td><input type='submit' name='upload' value='Загрузить' /></td></tr>";
print "</table>";
print "</form>";

include "footer.mini.php";
?>