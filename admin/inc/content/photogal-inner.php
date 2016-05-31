<?

if (empty($default_cols)) {
	$default_cols=param("cols",$main);
	if (empty($default_cols)) 
		$default_cols=3; 
}
$cols=$default_cols;
$res_f=row_select_pages("id,name, content","images","top_table=\"$main\" AND top_id='$id' AND visible='1'","ord,id");
$num=$res_f->nr();
if (!empty($num)) { 
	$i=0;
	pager(1,"images","7","","","Страницы:");
	?>
	<table width="100%"  border="0" ><?
	while ($r_f=$res_f->ga()){
		if ($i%$cols==0 ) echo "<tr>";
		?>
		<td align="center" valign=top><a href='<?=popupimg("images",$r_f["id"],"b",1)?>' title="Увеличить фото"><img src="<?=getimg("images",$r_f["id"],"m")?>" border=0><br><?=$r_f["name"]?></a>
		<?if (!empty($r_f["content"])) {?><div class='comment' style="padding-top:5px;"> <?=$r_f["content"]?></div><?}?></td>
		<?
		if ($i%$cols==($cols-1) ) echo "<tr>";
		$i++;
	}
?>
</table>
<?

	pager(1,"images","7","","","Страницы:");
}?>