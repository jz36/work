<?
if(empty($id)){

echo s_select("content","","top=333");

$cols=param("cols",$main);
$res=row_select_pages("id,name, content","","visible=1");
$num=$res->nr();
$i=0;
	pager(1,"","7","","","Страницы:");
?>
<table width="100%" cellspacing="10" cellpadding="0" border="0"><?
while ($r=$res->ga()){
	if ($i%$cols==0 ) echo "<tr>";
	?>
	<td align="center" valign=top><a href='<?=popupimg($main,$r["id"],"b",1)?>' title="Увеличить фото"><img src="<?=getimg($main,$r["id"],"m")?>"><br><?=$r["name"]?></a>
	<?feedback($main,$r["id"])?>
	<?if (!empty($r["content"])) {?><div class='comment' style="padding-top:5px;"> <?=$r["content"]?></div><?}?>
	</td>
	<?
	if ($i%$cols==($cols-1) ) echo "<tr>";
	$i++;
}

?>
</table>
<?
	pager(1,"","7","","","Страницы:");
}?>