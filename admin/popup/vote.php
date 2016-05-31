<?
require"".SITE_ADMIN_DIR."/functions.php";
set_connection();

#========= Языковой пакет ===============================
if (LANG=="rus") {
	$lng["answers"]="Ответило";
	$lng["answered"]="Ваш ответ получен";
	$lng["close"]="Закрыть окно";
	$lng["ppls"]="чел";
}
if (LANG=="eng") {
	$lng["answers"]="Total";
	$lng["answered"]="Your answer";
	$lng["close"]="Close";
	$lng["ppls"]="answers";
}




$pref=$main;
$dbg=0;

	//setcookie("".$pref."100001");
	//unset($_COOKIE[''.$pref.'100001']);

/////////////////////////////
// Если проголосовали, то добавляем инфу в базу об этом

if (!empty($_GET['id']) && empty($_COOKIE[''.$pref.$_GET['vote'].''])){

	# Развертываем и заносим пришедшие из опроса данные в массив
	$id=explode(",",$_GET['id']);
	
	foreach ($id as $value){
		if (!empty($value)){
			$tmp=explode("-",$value);
			$voted[$tmp[0]]=$tmp[1];
		}
	
	}
	# Заносим пришедшие данные в базу
	foreach ($voted as $key => $value){
		if (!empty($value))
			s_update("counter=counter+".$value,"","id=".$key);
	
	}
	s_update("counter=counter + 1","","id=".$vote);
	setcookie("".$pref.$_GET['vote']."",date("d.m.y H:i"), 0x7FFFFFFF);
	$reload=1;
	}
/////////////////////////////

// Определяем параметры вывода конкретного опроса, и всех опросов из архива
if (!empty($_GET['vote'])){
	$title=s_select("name","","id=".$_GET['vote'])." | Опрос";
	$where="id=".$_GET['vote'];
	$lim=1;
	$ord="";
}
if (!empty($_GET['all'])){
	$title="Архив опросов";
	$where="visible=1 AND top=0";
	$lim="";
	$ord="id DESC";
}


?>
<html>
<head>
<title><?=$title?></title>
<link href="<?=PATH?>/style.css" rel="stylesheet" type="text/css">
<link href="<?=PATH?>/css/vote.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 <?if (!empty($reload)) {?>onunload="window.opener.location.reload()"<?}?>>
	<?
	$res=row_select("id,name,content,counter","",$where,$ord,$lim);
	while ($r=$res->ga()) {
		# Проверка на старую версию, где не учитывалась версия опросника с выбором нескольких ответов
		if (empty($r["counter"])) {
			$count=s_select("SUM(counter)",""," top=".$r["id"]);
			s_update("counter=".$count,"","id=".$r["id"]);
			}		
		
		?>
		<div class='vote votepopup'>
		<h2><?=$r["name"]?></h2>
		<div class=vote_comment><?=$r["content"]?></div>
		<table class=tableno>
		<?
		$voted=$r["counter"];
		$counter=s_select("SUM(counter)",""," top=".$r["id"]."");
		$res2=row_select("id,name,content,counter","$pref"," top=".$r["id"]."");
		while  ($r2=$res2->ga()){
			if (!empty($counter)) $width=$r2["counter"]*100/$counter;
			else $width=0;
			echo "<tr><td class=vote_item>".$r2["name"]."</td>\n";
			echo "<td align=right valign=bottom class='vote_item red'>".round($width)."% (".$r2["counter"].")</td></tr>\n";
			echo "<tr><td colspan=2 class=line_bg><div class=line_red style='width:".round($width)."%'><img/0.gif height=5 width=1></td></tr>\n";
		}
		
		?>
		<tr><td colspan=2>
		</td></tr></table>
		<div class=vote_comment><?=$lng["answers"]?>: <b><?=$voted?></b> <?=$lng["ppls"]?>.<?
		if (!empty($_COOKIE[''.$pref.$r["id"].''])) echo " ".$lng["answered"].": ".$_COOKIE[''.$pref.$r["id"].''];
		?>
		
		</div>
		</div>
		<?
	}?>
<div class="votepopup_close"><a href="javascript:void(0);" onClick="window.close();"><?=$lng["close"]?></a></div>
<?
print_r($dbg_listing);?>
</body></html>