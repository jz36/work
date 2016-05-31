<?
#========================================
# Баннеры на сайте

function banners_news($num) {
global $main;global $id;global $from;	

?>

	<div style="background:#0069B7; padding:5px 15px 3px 10px; margin:10px 0px 0px 0px; color:#FFF; font-size:11px; display:block; border:1px solid #A9A9A9; border-bottom:0px solid #A9A9A9; ">Программа мероприятий в Екатеринбурге</div>
	<div style="background:#E5F0FC; border:1px solid #C1C1C1; padding:6px; font-size:11px;">
	<? 
	echo "<table class='tableno'>";
	$res=row_select("name,data,id","eka_actions","visible=1","alert DESC, data DESC, id DESC","$num");
	while($r=$res->ga())
	{?>
		<tr>
		<td nowrap width="5%" style="border-bottom:0px	solid	#ddd;"><div	class="date"><?=date_preobr($r["data"],1)?></div></td>
		<td style="border-bottom:0px solid #ddd;"><a	href="<?=SPAGE?>?main=eka_actions&id=<?=$r["id"]?>"	class="small"><?=$r["name"]?></a></td>
		</tr><?
	}
	?></table><div	align=right style="border-top:1px dotted #ddd;padding:3px 0px 0px 65px;"><a	href="<?=SPAGE?>?main=eka_actions" class="small gray">Все	мероприятия >></a></div>
	
	</div>
	
<?
}
?>