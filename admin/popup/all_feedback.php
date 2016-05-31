<?
//===============================================================
// Файл в котором происходит оценка статей, картинок и написание/просмотр к ним комментариев



//===============================================================
require"".SITE_ADMIN_DIR."/functions.php";
set_connection();

$main="all_feedback";
$dbg=0;
$title=s_select("name",$top_table,"id=$top_id");
if (empty($title)){
	switch ($top_table) {
		case "images": $title="Картинка"; break;
		case "files": $title="Файл"; break;
		case "links": $title="Ссылка"; break;	
	}
}
$title.=" | Оценка и комментарии";

//===============================================================

	//setcookie("".$top_table.$top_id."");unset($_COOKIE[''.$top_table.$top_id.'']);
	if (isset($_POST['fio'])) setcookie("forum_name", $_POST['fio'], 0x7FFFFFFF);
	if (isset($_POST['email'])) setcookie("forum_email", $_POST['email'], 0x7FFFFFFF);

/////////////////////////////
// Если проголосовали, то добавляем кукисы об этом

if (@$_POST['mark']!=0 && empty($_COOKIE[''.$top_table.$top_id.''])){
	setcookie("".$top_table.$top_id."",date("d.m.y H:i")."#".$_POST['mark'], 0x7FFFFFFF);
	//$reload=1;
	}
/////////////////////////////




?>
<html>
<head>
<title><?=$title?></title>
<link href="<?=PATH?>/style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<script language="JavaScript" src="<?=SITE_ADMIN_DIR?>/js/index_scripts.js"></script>
</head>
<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 <?if (!empty($reload)) {?>onunload="window.opener.location.reload()"<?}?>>

<?

#======================================================================
#===========================================================
# Получаем и обрабатываем данные из формы

if(isset($_POST['sbm'])){
	
	$error="";

	$_POST['fio']=strip_tags($_POST['fio']);
	$_POST['email']=strip_tags($_POST['email']);
	$_POST['message']=strip_tags($_POST['message']);
	
	if(!get_magic_quotes_gpc())
	{
		$_POST['fio']=addslashes($_POST['fio']);
		$_POST['email']=addslashes($_POST['email']);
		$_POST['message']=addslashes($_POST['message']);
	}
	
	if(strlen($_POST['fio'])>250) $_POST['fio']=substr($_POST['fio'],0,150);
	if(strlen($_POST['email'])>250) $_POST['email']=substr($_POST['email'],0,150);
	if(strlen($_POST['message'])>650) $_POST['message']=substr($_POST['message'],0,650);
	
	/*  проверим, не создана ли уже такая тема, то есть не нажали ли F5 чтобы обновить */
	if(strlen($_POST['message'])!=0){
		$res=row_select("id","","author=\"".$_POST['fio']."\" and name=\"".$_POST['message']."\"");
		
		if($res->nr()>0)
		{
			$error="<span class=red>Этот коментарий уже добавлен</span>";
		}
	}
	
	/*  проверим, не проголосовал ли человек еще раз*/
	if (!empty($_COOKIE[''.$top_table.$top_id.''])) $_POST['mark']=0;
	if (!isset($_POST['mark'])) $_POST['mark']=0;
	
	if($error=="")  // ошибок не было
	{
		$cur_date=date("Y-m-d");
		$cur_time=date("H:i");
		$ip=$_SERVER['REMOTE_ADDR'];
		$lastID=s_select("max(id)");
		if (empty($lastID)) $lastID=100000;

		$res=s_insert("","id,name,author,email,data,time,top_table,top_id,mark,ip,visible",  "($lastID+1),\"".$_POST['message']."\",\"".$_POST['fio']."\",\"".$_POST['email']."\",\"$cur_date\",\"$cur_time\",'".$_POST['top_table']."','".$_POST['top_id']."','".$_POST['mark']."','$ip',1");
		
		echo "<p class='bold red' align=center>Спасибо, ваше мнение принято.</p>";
	}
	elseif($error!=""){
		echo "<p class='bold red' align=center>$error</p>";
	}
}
#=============================================
#===========================================================
# Выводим форму с полем для комментария и оценкой
?>	<div id=fb_form>
	<br><table width="90%" border="0" cellspacing="0" cellpadding="0" class=table_forum_form>
	<form name="post" method="post" action="popup.php" onSubmit="
	<?if (!empty($need_comment)) {?>
		if(document.post.fio.value=='') {document.post.fio.value='Гость';}
		if(document.post.email.value!='' && !check_email(document.post.email.value)) {alert('Введите в поле E-mail корректный адрес, либо не вводите его совсем');return false;}	
	<?}?>
	">
	<?if (!empty($need_comment)) {?>
		<tr><td align=right width="1%" nowrap>Ф.И.О.:&nbsp;&nbsp;</td><td width=99%><input type="text" name="fio"  style="width:97%" maxlength="41" value="<?if (isset($_POST['fio'])) echo $_POST['fio']; else echo @$_COOKIE['forum_name']?>"></td></tr>
		<tr><td align=right nowrap>E-mail:&nbsp;&nbsp;</td><td><input type="text" name="email"  style="width:97%" maxlength="41" value="<?=@$_COOKIE['forum_email']?>"></td></tr>
		<tr><td align=right valign=top nowrap>Комментарий:&nbsp;&nbsp;</td>
		<td><textarea name="message" style="width:97%" rows=5  wrap="virtual" ><?if (isset($cit)) echo "[quote][b]".s_select("author","","id=$cit").":[/b] ".s_select("name","","id=$cit")."[/quote]
";	?></textarea></td></tr>
	<?} 
	if (!empty($need_mark) && empty($_COOKIE[''.$top_table.$top_id.''])){
		if (@$_POST['mark']!=0) {}
		else {
			$res=row_select("sum(mark) as mark, count(id) as num","all_feedback","top_table='$top_table' AND top_id='$top_id' and visible=1 AND mark!=0");
			$r=$res->ga();
			$num=$r['num'];
			if (!empty($num)) $mark=round(($r['mark'])/($num),2);
			if (empty($num)) $mark="-";
			?>	
	
			<tr><td align=right nowrap>Оценка:&nbsp;&nbsp;</td><td class=small><select name=mark><option selected value='0'>^ Выберите оценку</option><option value='1'>Ужасно</option><option value='2'>Плохо</option><option value='3'>Средне</option><option value='4'>Хорошо</option><option value='5'>Отлично</option></select> Текущая - <?=$mark?></td></tr>
	<?}}?>
	<tr><td>&nbsp;</td><td height="25"><input name="file" type="hidden" value="all_feedback.php"><input name="top_id" type="hidden" value="<?=$top_id?>"><input name="need_mark" type="hidden" value="<?=$need_mark?>"><input name="need_comment" type="hidden" value="<?=$need_comment?>"><input name="top_table" type="hidden" value="<?=$top_table?>"><input name="sbm" type="hidden" value="1"><input type="submit"  style="width:99%" name="Button" value="<?echo "Добавить";?>"></td></tr>
	</form></table></div>
<?

#=============================================
# Зашли в конкретную тему

	$res=row_select("id,name,author,email,data,time,mark","","top_table='$top_table' AND top_id='$top_id' AND name!='' and visible=1","id");
	if($res && $res->nr()>0){?>
		<table class="table_forum" cellpadding=0 cellspacing=0>
		<tr>
			<th width=20%>Автор</th>
			<th width=10%>Оценка</th>	
			<th width=70%>Комментарий</th>			
		</tr><?
		$i=0;
		while($r=$res->ga()){?>
			<tr class=bgcolor<?if ($i%2) echo "1"; else echo "2"; ?>>
			<td align=center  valign=top class="small"><a name=<?=$r['id']?>></a><?
				echo remakedata($r["data"])."&nbsp;, ".substr($r["time"],0,5)."<br>";
				if ($r['email']!="") {echo "<a href='mailto:".$r['email']."' class=small>";}
				echo $r['author'];
				if ($r['email']!="") {echo "</a>"; }?>
			</td>
			<td class=small align=center><?if ($r["mark"]==0) echo "--"; else echo $r["mark"];?></td>
			<td valign=top ><?
			$text=$r['name'];
			$text=str_replace("[quote]","<div class=quote>",$text);$text=str_replace("[/quote]","</div>",$text);
			$text=str_replace("[b]","<b>",$text);$text=str_replace("[/b]","</b>",$text);
			echo $text;
			?></td>
			</tr>
			<!--
			<tr class=bgcolor<?if ($i%2) echo "1"; else echo "2"; ?>>
			<td><a href="#top" class=small>.</a></td>
			<td valign=top align=right><a href="<?=SPAGE?>?main=<?=$main?>&add=2&id=<?=$id?>&cit=<?=$r['id']?>" class=small>Ответить с цитатой</a></td>
			</tr>
			-->
			<tr><td colspan=3 class="divider"><img src=img/0.gif width=1 height=1 class=borderno></td></tr>
			<?
			$i++;		
		}
		?>
		</table><?
}


	if (!empty($top_id) && !empty($_GET['main']) && empty($_COOKIE[''.$top_table.$top_id.''])){
	}

	?>
<a href="javascript:void(0);" onClick="window.close();">Закрыть окно</a>
<?
print_r($dbg_listing);?>
</body></html>