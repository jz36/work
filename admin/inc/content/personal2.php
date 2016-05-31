<?

#выводим список сотрудников
if (empty($id)){

$res=row_select("id,name,content,visible,dolznost,tel,email","","top=0 and visible=1");
while ($r=$res->ga()){?>
	<h3><?=$r["name"]?></h3>
	<table class=tableno width=90%><tr><td valign=top width=70%><table width=95%><?
	if ($r["dolznost"]!="") echo "<tr><td valign=top width=20%><b>Должность:</b></td><td>".$r["dolznost"]."</td></tr>";
	if ($r["tel"]!="") echo "<tr><td valign=top><b>Телефон:</b></td><td>".$r["tel"]."</td></tr>";
	if ($r["email"]!="") echo "<tr><td valign=top><b>E-mail:</b></td><td>".email_echo($r["email"])."</td></tr>";
	if ($r["content"]!="") echo "<tr><td colspan=2>".substr(strip_tags($r["content"]),0,200)." ... <a href=".PATH.SPAGE."?main=".$main."&id=".$r["id"]." class=small>[далее]</a></td></tr>";
	?>
	</table></td><td valign=top>
	<?if (getimg($main,$r["id"],"m")!="img/0.gif") {?>
	<a href="<?=PATH.SPAGE?>?main=<?=$main?>&id=<?=$r["id"]?>" title="Подробнее"><img src="<?=getimg($main,$r["id"],"m")?>" width=100 border=0 align=right></a>
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

