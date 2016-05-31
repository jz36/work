<?
if(empty($id)){
$gal_cols=param("gal_cols",$main);$i=0;
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="5">
<?
$res=row_select("id,name,content","","top=0 AND visible=1","ord,name");
while($r=$res->ga())	{
	$res2=row_select("id,name,data","images","top_table=\"$main\" AND top_id=$r[0]","id DESC");
	$num=$res2->nr();
	$r2=$res2->ga();
	if ($i%$gal_cols==0 ) echo "<tr>";?>
	<td width=70 valign=top><a  href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r[0]?>"><img src=<?=getimg("images",$r2["id"],"m")?> width=70 class=gal></a></td>
	<td valign=top><b><a  href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r["id"]?>"><?=$r["name"]?></a></b><br>
	<div class=small>фотографий: <?=$num?>, последнее добавление: <?=remakedata($r2["data"])?></div>
	<div class=comment><?=$r["content"]?></div></td>
	<?
	if ($i%$gal_cols==($gal_cols-1)) echo "</tr>";
	$i++;
}?></table>

<?}
else
{
$cols=param("cols",$main);
$res=row_select_pages("id,name,content","images","top_table=\"$main\" AND top_id=$id","ord,id");
$num=$res->nr();
$i=0;
	pager(1,"images","7","","","Страницы:");
?>
<h2><?=s_select("name","","id=".$id);?></h2><br>
<table width="100%" cellspacing="10" cellpadding="0" border="0"><?
while ($r=$res->ga()){
	if ($i%$cols==0 ) echo "<tr>";
	?>
	<td align="center" valign=top><a href='<?=popupimg("images",$r["id"],"b",1)?>' title="Увеличить фото"><img src="<?=getimg("images",$r["id"],"m")?>"><br><?=$r["name"]?></a>
	<?feedback("images",$r["id"])?>
	<?if (!empty($r["content"])) {?><div class='comment' style="padding-top:5px;"> <?=$r["content"]?></div><?}?>
	</td>
	<?
	if ($i%$cols==($cols-1) ) echo "<tr>";
	$i++;
}

?>
</table>
<?

	pager(1,"images","7","","","Страницы:");

}?>