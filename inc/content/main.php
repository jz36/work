<?
#========================================
# Главная страница
@banners_main("","main_informers");
@banners_main_big("","main_informers2");

$res=row_select("name,content","main","","",1);
$r=$res->ga();
echo "<h2>".$r["name"]."&nbsp;&nbsp;<a href='?main=about_info' class='small'>[Подробнее]</a></h2>".$r["content"]."";

//=========================================================================================
//	Вставка из раздела новости

$news_id=s_select("id","admin_tree","page='news'");
$res0=row_select("page,name","admin_tree","menu_top='$news_id'");
echo "<table class=tableno><tr>";
$t=0;
while (( $r0=$res0->ga() ) and $t == 0) {
	$t++;
	@$y++;
	$res=row_select("name,data,content,id,alert",$r0["page"],"visible=1","data DESC, id DESC","10");
	?>
	<td valign=top><h3><a href="?main=<?=$r0["page"]?>" class="h3_dop gray">Смотреть все</a><?=$r0["name"]?></h3>
	<table class=tableno><?
	while($r=$res->ga())
	{
		@$i++;
		$img=s_select("id","images","top_table='$main' AND top_id=".$r["id"]);
		$img=getimg("images",$img,"m");
		if (!empty($r["alert"])) $alert="bold"; else  $alert="";
		?><tr>
<!--		<td width="1%" valign="top"><? //if ((getimg($r0["page"],$r["id"],"m")!="img/0.gif")) { ?>
			<a href="<?=SPAGE?>?main=<?=$r0["page"]?>&id=<?=$r["id"]?>" class="<?=$alert?>"><?
			//echo "<img src='".getimg($main,$r["id"],"m")."' style='margin-right:10px; padding:3px;' width=50></a>";}
		?>
		</td> -->
		<td class="news" valign=top style="padding:3px 0px 0px; border-bottom:0px solid #ddd;"><span class="date"><?=date_preobr($r["data"],1)?></span>
		<a href="<?=SPAGE?>?main=<?=$r0["page"]?>&id=<?=$r["id"]?>" class="<?=$alert?>"><?=$r["name"]?></a>
		<div class="small" style="color:#444;"><?=divide_text(strip_tags($r["content"]),50)?></div></td>
		</tr><?
	}
	?></table></td><?
	if ($y%1==0) echo "</tr><tr>";
}
?></tr></table><?



//=========================================================================================
// Баннер на главной
echo "<hr><div align=center>";
@banners_main("","main_informers1");
echo "</div>";



?>
           
<?	



?> 
</div>

