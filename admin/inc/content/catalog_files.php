
<?
$res0=row_select("id,name,content",$main,"top=0 AND visible=1");
while($r0=$res0->ga()){
	
	echo "<h2>".$r0["name"]."</h2><table class=table3 width=70%>";
	
	$res=row_select("id,name,content","files","top_table=\"$main\" AND top_id=\"".$r0["id"]."\" AND visible=1");
	while($r=$res->ga()){	
		
		if (test_file($r["content"])!="") {
			$time=filectime("files/".test_file($r["content"]));
			$data=getdate($time);
			$time=fixDate($data["mday"]).".".fixDate($data["mon"]).".".$data["year"];
			$size=round(filesize("files/".test_file($r["content"]))/1024,1);
		}
		else {
			$time="";
			$data="";
			$time="";
			$size="";
		}
	
		
		$ico="<img src=\"".test_file_ext($r["content"])."\"  alt=\"".test_file_ext($r["content"],"alt")."\" width=16 align=absmiddle border=0>&nbsp;";
		$file=$r["name"];
		if (!empty($time)) {
			$link="<nobr><a href='files/".test_file($r["content"])."' title='Скачать файл' target=_blank>".$file."</a></nobr>";
			$comment="Размер: <b>".@$size." Кб</b>. Загружен: ".@$time;
		}
		else {
			$link=$file;
			$comment="<nobr><span class=small>[Файл незагружен]</a></nobr>";
		}
	
		
	?>
	          <tr>
	            <td valign="top" align=center><?=@$ico?></td><td class=list_item><?=@$link?>
	            <div class=comment><?=@$comment?></div>
	            </td>
	          </tr>
	          
	<?}
	echo "</table><br>";
}?>
