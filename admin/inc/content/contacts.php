<?
if (!empty($id)) $select=" and id=".$id; else $select="";
$res=row_select("id,name,content","","top=0 and visible=1 ".$select."");
while ($r=$res->ga()){
	$map=getimg($main,$r["id"],0);?>
	<h3><?echo $r[1];?></h3>
	<table class=table1><?
	$res2=row_select("id,name,content","","top=".$r["id"]." and visible=1");
	while ($r2=$res2->ga()){
		if (substr($r2["name"],-1,1)!=":" && !empty($r2["name"])) $r2["name"]=$r2["name"].":";
		echo "<tr><td width=30%>".$r2["name"]."&nbsp;</td>";
		echo "<td>".email_echo($r2["content"])."</td></tr>";	
	}?>
	</table><br>
	<?

	if (getimg($main,$r[0],0)!="img/0.gif") {?>
	<div align="center"><A href='<?echo popupimg($main,$r[0],0,1)?>' class="small" title="—хема проезда [открыть в новом окне]" ><img src="<?=getimg($main,$r[0],0)?>"></a></div>
	<?}
}
?>



