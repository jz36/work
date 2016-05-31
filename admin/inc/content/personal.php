<?

#выводим список сотрудников
if (empty($id)){

$otdel="";
$res=row_select("id,name,content,visible,dolznost,tel,email,edu,stag,sertif,otdel","","top=0 and visible=1","ord,id");
while ($r=$res->ga()){
	if ($otdel!=$r["otdel"]) {
		$otdel=$r["otdel"];
		echo "<hr><h2>".$otdel."</h2>";
	}
	?>
	<h3><?=$r["name"]?></h3>
	<table class=tableno width=100%><tr><td valign=top width=80%><table width=100%><?
	if ($r["dolznost"]!="") echo "<tr><td valign=top width=20%><b>Должность:</b></td><td>".$r["dolznost"]."</td></tr>";
	if ($r["tel"]!="") echo "<tr><td valign=top><b>Телефон:</b></td><td>".$r["tel"]."</td></tr>";
	if ($r["email"]!="") echo "<tr><td valign=top><b>E-mail:</b></td><td>".email_echo($r["email"])."</td></tr>";
	if ($r["content"]!="") echo "<tr><td colspan=2>".$r["content"]."</td></tr>";
	if ($r["edu"]!="") echo "<tr><td valign=top><b>Образование:</b></td><td>".$r["edu"]."</td></tr>";
	if ($r["stag"]!="") echo "<tr><td valign=top><b>Стажировки:</b></td><td>".$r["stag"]."</td></tr>";
	if ($r["sertif"]!="") echo "<tr><td valign=top><b>Сертификаты:</b></td><td>".$r["sertif"]."</td></tr>";
	?>
	</table></td><td valign=top>
	<?if (getimg($main,$r["id"],"m")!="img/0.gif") {?>
	<a 
	<? if (getimg($main,$r["id"],"b")!="img/0.gif") {?> href='<?=popupimg($main,$r["id"],"b",1)?>' title="Увеличить фото" <?}?>>
	<img src="<?=getimg($main,$r["id"],"m")?>" width="<?=param("size_m",$main);?>" border=0 align=right>
	</a>
	<?}?>
	</td></tr>
	</table>
	<?}
}	
	
#====================================================
# Выводим конкретное описание
if (!empty($id)){

$res=row_select("id,name,content,visible,dolznost,tel,email","","id=".$id);
$r=$res->ga();?>
	<h2><?=$r["name"]?></h2><br>
	<table class="tableno border" width=60%><?
	if ($r["dolznost"]!="") echo "<tr><td valign=top width=20%><b>Должность:</b></td><td>".$r["dolznost"]."</td></tr>";
	if ($r["tel"]!="") echo "<tr><td valign=top><b>Телефон:</b></td><td>".$r["tel"]."</td></tr>";
	if ($r["email"]!="") echo "<tr><td valign=top><b>E-mail:</b></td><td>".email_echo($r["email"])."</td></tr>";
	?>
	</table>
	<?if (getimg($main,$r["id"],"m")!="img/0.gif") {?>
	<a href='<?=popupimg($main,$r["id"],"b",1)?>' title="Увеличить фото"><img src="<?=getimg($main,$r["id"],"m")?>" width=100 border=0 align=right></a>
	<?}?>
	<?
	echo $r["content"]." ... <p><a href=".PATH.SPAGE."?main=".$main." class=small>[вернуться назад к списку]</a>";
	?>
	
	<?	
}
?>