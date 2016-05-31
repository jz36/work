<?
#Новости#0
if (empty($id)) {
	pager(1,"","7","","","Страницы:");
	$res=row_select_pages("name,data,id","","visible=1","data DESC, id DESC","");
	while($r=$res->ga())
	{?>
		<div class="news">
		<span class="date"><?=date_preobr($r["data"],2)?></span>
		<a href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r["id"]?><?=lparam("pg",$pg)?>"><?=$r["name"]?></a>
		</div><?
	}}
	pager(1,"","7","","","Страницы:");


//=====================================================================================
if (!empty($id)) {
	$res=row_select("name,data,content","","id=$id","","1");
	$r=$res->ga();?>
	<h3><span class=date><?=date_preobr($r["data"],2)?></span><?=$r["name"]?></h3>
	<div align=justify><?=$r["content"]?></div><hr><?
	$res=row_select_pages("name,data,id,alert","","visible=1","data DESC, id DESC","15");
	pager(1,"","7","","","Страницы:");
	while($r=$res->ga())
	{?>
		<div class="news">
		<span class="date <?if ($r["id"]==$id) echo "redbg"?>";><?=date_preobr($r["data"],2)?></span>
		<a href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r["id"]?><?=lparam("pg",$pg)?>" <?if ($r["alert"]==1) echo "class=alert";?>><?=$r["name"]?></a>
		</div><?
	}
	pager(1,"","7","","","Страницы:");
}?>