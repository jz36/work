<?
$cols=3;
$res2=row_select("id,name,content","files","top_table=\"$main\" AND top_id='$id' AND visible='1'");
if ($res2->nr()>0) echo "<hr><h3>Файлы для скачивания:</h3>";
while($r2=$res2->ga()){
	
	$file="<a href='files/".test_file($r2["content"])."' title='Скачать файл' target=_blank>";
	$file.="<img src=\"".test_file_ext($r2["content"])."\"  alt=\"".test_file_ext($r2["content"],"alt")."\" width=16 align=absmiddle border=0>&nbsp;";
	$file.=$r2["name"]."</a>";
	
	if (test_file($r2["content"])!="") {
		$size=round(filesize("files/".test_file($r2["content"]))/1000);
		?><div class=small><?=$file?> [<?=$size?> Кб]</div><?
		$sets=1;
	}
}
?>