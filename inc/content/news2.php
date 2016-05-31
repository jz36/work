<?
#Новости#0
if (!empty($id)) {
	$res=row_select("name,data,content","","id=$id","","1");
	$r=$res->ga();?>
	<h3><span class=date><?=date_preobr($r["data"],2)?></span><?=$r["name"]?></h3>
	<div align=justify><?=$r["content"]?></div><hr><?
	$res=row_select_pages("name,data,id,alert","","visible=1","data DESC, id DESC","15");
}


	pager(1,"","7","","","Страницы:");
	$res=row_select_pages("name,data,content,id,alert","","visible=1","data DESC, id DESC","");
	?><table class=tableno><?
	while($r=$res->ga())
	{
		@$i++;
		$img=s_select("id","images","top_table='$main' AND top_id=".$r["id"]);
		$img=getimg("images",$img,"m");
		if (!empty($r["alert"])) $alert="bold"; else  $alert="";
		?><tr>
<!--		<td width="1%" valign="top"><? // if ((getimg($main,$r["id"],"m")!="img/0.gif")) { ?>
			<a href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r["id"]?><?=lparam("pg",$pg)?>" class="<?=$alert?>"><?
			//echo "<img src='".getimg($main,$r["id"],"m")."' style='margin-right:10px; padding:3px;'></a>"; }
		?>
		</td> -->
		<td class="news" valign=top style="padding:5px 0px 0px; border-bottom:1px solid #ddd;"><span class="date"><?=date_preobr($r["data"],2)?></span>
		<a href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r["id"]?><?=lparam("pg",$pg)?>" class="<?=$alert?>"><?=$r["name"]?></a>
		<div class="small "><?=divide_text(strip_tags($r["content"]),param("anonse",$main))?></div></td>
		</tr><?
	}
	?></table><?

	pager(1,"","7","","","Страницы:");


?>