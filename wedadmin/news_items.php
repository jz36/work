<?php



$xihna_enabled = true;







if (!defined("NX_PATH")) define ("NX_PATH", "../");



if (!defined("WA_URL")) define ("WA_URL", "../wedadmin/");



if (!defined("WA_PATH")) define("WA_PATH", "./");



require_once(NX_PATH.'wedadmin/lib/global.lib.php');



require_once(NX_PATH.'wedadmin/config.inc.php');



require_once(NX_PATH.'wedadmin/lib/mysql.lib.php');



include_once(WA_PATH.'lib/news.lib.php');



@set_magic_quotes_runtime(0);







db_open();



include(WA_PATH.'header.inc.php');





function resize($filename, $to, $type, $x) {



print '$filename, $to, $type, $x'."$filename, $to, $type, $x<BR>"; 

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





if (isset($_SESSION["WA_USER"])){











## delete picture



	if (isset($_GET['delete_picture'])&&isset($_GET['id'])){



	  # удаляем картинку в категории



	  $c_path="../news/images/";



	  $id=intval($_GET[id]);



	  # select filename



	  # delete pic_filename



	  # update



		$string="select small_picture from `wed_news_items` where `id`=$id";	



		#print "string=$string<BR>";



		$pic_filename= mysql_result(mysql_query($string),0);



		if (is_file($c_path.$pic_filename)){



			unlink($c_path.$pic_filename);



		}



		$string="update `wed_news_items` set small_picture='' where `id`='$id'";



		mysql_query($string);



		print	"<B>Картинка $pic_filename удалена</B><BR>";



	}







	#устанавливаем новости на главной / в архиве



	if (isset($_GET["fav"])){



		$id = intval($_GET["fav"]);



		#db_open();



		if ($query = mysql_query("SELECT * FROM `wed_news_items` WHERE `id` = '$id'")){



			if ($item = mysql_fetch_array($query)){



				$is_favorite = 1;



				if ($item['is_favorite']) $is_favorite = 0;



				$sql = "UPDATE `wed_news_items` SET `is_favorite` = '$is_favorite' WHERE `id` = '$id'";



				mysql_query($sql);



				@header("Location: /wedadmin/news_items.php");



			}



		}



	}















	if (isset($_GET["deletei"])){



		$id = intval($_GET["deletei"]);



		mysql_query("DELETE FROM `wed_news_items` WHERE `id` = '$id'");



	}



	if (isset($_GET["updatei"])){



		$id = intval($_GET["updatei"]);



		$date_a = explode('/', $_POST["date"]);



		if (count($date_a)==3){



			if (checkdate($date_a[1],$date_a[0],$date_a[2])){



				$date = mktime(0,0,0,$date_a[1], $date_a[0], $date_a[2]);



				$title = addslashes($_POST["title"]);



				$short_text = addslashes($_POST["short_text"]);



				$full_text = addslashes($_POST["full_text"]);



				$small_uploaded = FALSE;



				if (isset($_FILES["small_picture"])){



					$filename = $_FILES["small_picture"]["name"];



					$tmpname =  $_FILES["small_picture"]["tmp_name"];



					$exts = explode('.', $filename);



					if (count($exts)){



						$new_filename = 'small_'.$id.'.'.$exts[count($exts)-1];



					}else{



						$new_filename = 'small_'.$id;



					}



					if (is_uploaded_file($tmpname)){





			if ($_FILES['small_picture']['name']) {

				$e=explode(".",$_FILES['small_picture']['name']);

				$type=end($e);

				$new_filename="../news/temp/".time().".".$type;

				$to="../news/small/".time().".".$type;

				$name=time().".".$type;

				move_uploaded_file($_FILES['small_picture']['tmp_name'], $new_filename);

				resize($new_filename, $to, $type, 135);

				unlink($new_filename);

				$small_uploaded = TRUE;

			}





						#if (move_uploaded_file($tmpname, NX_PATH.'news/images/'.$new_filename)){

						#

						#	$small_uploaded = TRUE;

						#

						#}



					}



				}



				if ($small_uploaded){



					mysql_query("UPDATE `wed_news_items` SET `small_picture` = '$to' WHERE `id` = '$id'");



				}



				mysql_query("UPDATE `wed_news_items` SET `date` = '$date', `title` = '$title', `short_text` = '$short_text',`full_text` = '$full_text' WHERE `id` = '$id'");



			}



		}







	}



	if (isset($_GET["createi"])){



		if (checkdate($_POST["date_m"],$_POST["date_d"],$_POST["date_y"])){



			$date = mktime(0,0,0,$_POST["date_m"], $_POST["date_d"], $_POST["date_y"]);



			$title = addslashes($_POST["title"]);



			$short_text = addslashes($_POST["short_text"]);



			$full_text = addslashes($_POST["full_text"]);



			if ($_FILES['small_picture']['name']) {

				$e=explode(".",$_FILES['small_picture']['name']);

				$type=end($e);

				$filename="../news/temp/".time().".".$type;

				$to="../news/small/".time().".".$type;

				$name=time().".".$type;

				move_uploaded_file($_FILES['small_picture']['tmp_name'], $filename);

				resize($filename, $to, $type, 200);

				unlink($filename);

			}



			mysql_query("INSERT INTO `wed_news_items` (`date`, `title`, `short_text`, `full_text`, `small_picture`) VALUES ('$date', '$title', '$short_text', '$full_text', '$name')");



			$id = mysql_insert_id();



			$small_uploaded = FALSE;



			if (isset($_FILES["small_picture"])){



				$filename = $_FILES["small_picture"]["name"];



				$tmpname =  $_FILES["small_picture"]["tmp_name"];



				$exts = explode('.', $filename);



				if (count($exts)){



					$new_filename = 'small_'.$id.'.'.$exts[count($exts)-1];



				}else{



					$new_filename = 'small_'.$id;



				}



				if (is_uploaded_file($tmpname)){



					if (move_uploaded_file($tmpname, NX_PATH.'news/images/'.$new_filename)){



						$small_uploaded = TRUE;



					}



				}



			}



			if ($small_uploaded){



				mysql_query("UPDATE `wed_news_items` SET `small_picture` = '$new_filename' WHERE `id` = '$id'");



			}



		}



	}



	if (isset($_GET["editi"])){



		$id = intval($_GET["editi"]);



		db_open();







		if ($item = mysql_fetch_array(mysql_query("SELECT * FROM `wed_news_items` WHERE `id` = '$id'"))){



			$xihna_enabled = true;	











                echo '<H1>НОВОСТИ</H1>';



                echo '<FORM method="post" enctype="multipart/form-data" action="'.WA_URL.'news_items.php?updatei='.$item["id"].'">';



                echo '<B>Дата:</B> <INPUT type="text" name="date" value="'.date('d/m/Y',$item["date"]).'"> ';



                echo '<BR/><BR/>';



                echo '<B>Заголовок:</B><BR/><INPUT type="text" name="title" value="'.htmlspecialchars($item["title"]).'" class="w100" size=50><BR/><BR/>';



                echo ' Изображение:';



                if ($item["small_picture"] !== ''){



                	echo '<BR/>загружено:'.$item["small_picture"].'<BR/><IMG src="/news/small/'.$item["small_picture"].'">';



			echo '[ <A href="/wedadmin/news_items.php?delete_picture=1&id='.$item["id"].'" onClick="javascript: if (confirm('."'Удалить картинку?')) { return true;} else { return false;}\"".'>удалить</A> ]<BR/>';



                }



                echo '<BR/><INPUT type="file" name="small_picture" value="" class="w100"><BR/><BR/>';



                



                echo '<B>Краткий текст:</B><BR/><TEXTAREA style="width:500px;height:100px;" id="short_text" name="short_text" rows=15 cols=50>'.$item["short_text"].'</TEXTAREA><BR/><BR/>';



                echo '<B>Полный текст (для отдельной страницы):</B><BR/><TEXTAREA style="width:500px;height:300px;" id="full_text" name="full_text" rows=15 cols=50>'.$item["full_text"].'</TEXTAREA><BR/><BR/>';



				echo '<a href="#ab" onclick="open(\'/wedadmin/news_images.php?id='.$item['id'].'\', \'popUpWin\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=600,height=700,left=0, top=0,screenX=0,screenY=0\');">Загрузить изображения</a><br><br>';







                echo ' <INPUT type="submit" value="сохранить">';







                echo '</FORM>';





			include(WA_PATH.'footer.inc.php');





                die();



		}



	}



	if (isset($_GET["addi"])){



		db_open();



		$xihna_enabled = true;





                echo '<H1>НОВОСТИ</H1>';



                echo '<div style="margin:25px;"><FORM method="post" enctype="multipart/form-data" action="'.WA_URL.'news_items.php?createi">';



                echo 'Дата:<BR/><table cellpadding=0 cellspacing=0 border=0><tr><td><INPUT type="text" name="date_d" value="'.date('d').'" style="width:20px;"></td><td>&nbsp;/&nbsp;</td><td><INPUT type="text" name="date_m" value="'.date('m').'" style="width:20px;"></td><td>&nbsp;/&nbsp;</td><td><INPUT type="text" name="date_y" value="'.date('Y').'" style="width:40px;"></td></tr></table> ';



                echo '<BR/>';



                echo ' Заголовок:<BR/><INPUT type="text" name="title" value=""><BR/><BR/>';



                echo ' Изображение:';



                echo '<BR/><INPUT type="file" name="small_picture" value="" class="w100"><BR/><BR/>';



                



                echo '<P>Краткий текст:<BR/><TEXTAREA style="width:500px;height:100px;" id="short_text" name="short_text" rows=5 cols=50></TEXTAREA><BR/><BR/>';



                echo '<P>Полный текст:<BR/><TEXTAREA style="width:500px;height:400px;" id="full_text" name="full_text" rows=25 cols=50></TEXTAREA><BR/><BR/>';



				$sql="SHOW TABLE STATUS LIKE 'wed_news_items'";



				$result=mysql_query($sql);



				$arr=mysql_fetch_array($result);



				$nextid=$arr['Auto_increment'];



				echo '<a href="#ab" onclick="open(\'/wedadmin/news_images.php?id='.$nextid.'\', \'popUpWin\', \'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=600,height=700,left=0, top=0,screenX=0,screenY=0\');">Загрузить изображения</a><br><br>';



                echo ' <INPUT type="submit" value="сохранить"><BR/><BR/>';







                echo '</FORM></div>';



			include(WA_PATH.'footer.inc.php');



			die();

	}







	#$xihna_enabled = true;



	$page = 0;



	if (isset($_GET["page"])) $page = intval($_GET["page"]);







	db_open();







	$count = 10;



	$from = $page * $count;



	$news_total = mysql_result(mysql_query("SELECT COUNT(*) FROM `wed_news_items`"), 0);



	$pages_count = ceil($news_total / $count);











	echo '<H1>НОВОСТИ</H1>';



    echo '<center>[ <A href="'.WA_URL.'news_items.php?addi">добавить новость</A> ]</center><BR/>';



	if ($pages_count > 1){



		echo '<div align="right" style="margin:5px;">Страницы: ';



		$i = 0;



		while ($i < ($pages_count)){



			echo ' <A href="'.WA_URL.'news_items.php?page='.$i.'">';



			if ($i == $page){







			}



			echo ($i+1).'</A> ';



			$i++;



		}



		echo '</div>';



	}



	$query = mysql_query("SELECT * FROM `wed_news_items` ORDER BY `is_favorite` DESC, `date` DESC, `id` DESC LIMIT $from, $count");



		$r = 1;



		if (mysql_num_rows($query)){



			echo '<div align="center">';



			echo '<table cellpadding="3" cellspacing="0" border="0" width="550" class="catalogue">';



			echo '<tr class="th"><td>&nbsp;</td><td>Дата</td><td>Заголовок</td><td>Действия</td></tr>';



			while ($item = mysql_fetch_array($query)) {



				$r++;



				if ($r > 1) $r = 0;



				echo "\n".'<tr class="r'.$r.'">';







				echo '<td><nobr>';



				echo ' <A href="/wedadmin/news_items.php?fav='.$item["id"].'"><img src="/wedadmin/images/star_';



				if ($item['is_favorite']) echo 'on'; else echo 'off';



				echo '_2.gif" border="0" /></A></td>';



				echo '<td align="left" width="80" nowrap class="id">'.date("d/m/Y",$item["date"]).'</td>';



				//echo '<td align="left" width="5"><img src="'.WA_URL.'images/icons/dot_violet.png" border="0"></td>';



				echo '<td align="left"><B>'.$item["title"].'</B> '.$item["short_text"].'</td>';



				echo '<td align="left" width="80" nowrap>';



				echo '<a href="'.WA_URL.'news_items.php?editi='.$item["id"].'">';



				echo '<img src="'.WA_URL.'images/icons/b_props.png" width="16" height="16" border="0">';



				echo '</a>';



				echo '&nbsp;';



				echo '<a href="'.WA_URL.'news_items.php?deletei='.$item["id"].'" onClick="javascript: if (confirm(\'Удалить новость?\')) { return true;} else { return false;}">';



				echo '<img src="'.WA_URL.'images/icons/b_drop.png" width="16" height="16" border="0">';



				echo '</a>';



				echo '</td></tr>';



			}



			echo '</table>';



			echo '</div>';



		}



		//	echo '<TABLE>';



		//	while ($item = mysql_fetch_array($news)) {



		//		echo '<TR><TD>'.date('d.m.y',$item["date"]).'</TD><TD>'.$item["title"].'</TD><TD>[ <A href="'.WA_URL.'news_items.php?edit='.$item["id"].'">изменить</A> ]</TD></TR>';



		//	}



		//	echo '</TABLE>';



		echo '<BR/><center>[ <A href="'.WA_URL.'news_items.php?addi">добавить новость</A> ]</center><BR/><BR/>';









}



	include(WA_PATH.'footer.inc.php');

?>