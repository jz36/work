<?
#Ответы на вопросы#0

if (empty($id)) {
	echo "<div class=\"small comment\">".s_select("content",$main,"top=333")."</div><br>";
	
	$res=row_select("","","top!=333 and visible=1","name");?>
	<table class=table1>
	<div class=small>Список всех терминов:</div>
	<select onChange="jmpMenu('parent',this,0)" style="width:90%"><?
	while ($r=$res->ga()) {
		$anch=$r["id"]?>
		<option value='<?SPAGE?>?main=<?=$main?>#<?=$anch?>'><?=$r["name"]?></option>
		<?
	}
	?></select><hr><?
	
	$res->ds(0);
	while ($r=$res->ga()) {
		$anch=$r["id"];
		?>
		
		<tr><td width=30%><h4><a name="<?=$anch?>"></a><?=$r["name"]?></h4></td>
		<td class=t9px align=center width=10%><a href="<?SPAGE?>?main=<?=$main?>&id=<?=$r["id"]?>">это >></a></td>
		<td><?=$r["content"]?>&nbsp;<?
		if (!empty($r["link"])) 
		echo "<br><a href='".$r["link"]."' class='small'>Подробная информация</a>";
		?></td></tr>
	<?}?>
	</table>
	<?
}

else {
	$res=row_select("name,content,link","","visible=1 AND id='".$id."'","",1);
	$r=$res->ga();
	echo "<h2>".$r["name"]." -</h2>";
	echo $r["content"]."";
	if (!empty($r["link"])) echo "<a href='".$r["link"]."'>Подробная информация</a>";
	?><p><a href="<?SPAGE?>?main=<?=$main?>">Вернуться к общему списку</a></p><?
	

}



?>


