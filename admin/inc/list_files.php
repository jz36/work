<?
#========================================
# Формируем колонтитул

function prices() {
$main="prices";

?><div class=prices>
<table>
<?
$res=row_select("id,name,content","files","top_table=\"$main\"");
while($r=$res->ga()){
	
	$ico="<img src='img/i_file_xls.gif'  alt='прайс-лист' width=22 align=absmiddle border=0>&nbsp;";
	$file="<nobr><a href='files/".test_file($r["content"])."' title='Скачать файл' target=_blank class=small>".$r["name"]."</a></nobr>";
	$link="<nobr><a href='files/".test_file($r["content"])."' title='Скачать файл' target=_blank class=small>[Скачать файл >>]</a></nobr>";
	
	if (test_file($r["content"])!="") {
		$time=filectime("files/".test_file($r["content"]));
		$data=getdate($time);
		$time=fixDate($data["mday"]).".".fixDate($data["mon"]).".".$data["year"];
		$size=round(filesize("files/".test_file($r["content"]))/1024);
		}
	
?>
          <tr>
            <td valign="top" align=right style="padding-top:5px;"><?=$ico?></td><td class=list_item><?=$file?>
            <div class=comment><nobr>Размер файла: <?=$size?> Кб<br>Загружен: <?=$time;?></nobr></div>
            </td>
          </tr>
          
<?}?>
</table></div><br clear=all><?
}

?>