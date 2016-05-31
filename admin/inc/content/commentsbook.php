<?
#Отзывы клиентов#0

if (empty($id)) {
	echo "<div class=\"small comment\">".s_select("content",$main,"top=333","","","","1")."</div><br>";
	
	$res=row_select("name, content, data","","visible=1","data DESC");?>
	<?
	while ($r=$res->ga()) {
		?>
		<p><?=$r["content"]?><div class="bold" align=right><div align='left' style="float:left;" class="date"><?=@date_preobr($r["data"],2)?></div><?=$r["name"]?></div><hr></p>
		
		<?
	}
}

else {
	$res=row_select("name,content","","visible=1 AND id='".$id."'","",1);
	$r=$res->ga();?>
	<p><?=$r["content"]?><div class="bold" align=right><?=$r["name"]?></div><hr></p>
	<a href="<?=SPAGE."?main=".$main ?>">Перейти к общему списку</a>
	<?
}



?>


