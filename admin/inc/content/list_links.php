<?

#выводим список ссылок


if (!empty($id)){
	
	?><script>
	window.open("<?=s_select("url","links","id=".$id)?>","_blank");
	</script><?
	
	s_update("gout=(gout+1)","links","id=".$id);	
	
	}
	
	?><table class=tableno width=90%><?
	$res=row_select("id,name,content,url,gout,alert","links","top_table='".$main."' and visible=1");
	while ($r=$res->ga()){?>
		
		<tr>
		<td valign=top width=100%>
		<?if (!empty($r["alert"])) $alert="class='red'"; else  $alert="";?>
		<b <?=$alert?>><?=$r["name"]?></b><br><?
		if (!empty($r["url"])) echo "<a href=".SPAGE."?main=".$main."&id=".$r["id"].">".$r["url"]."</a>";
		if (!empty($r["content"])) echo "<div class=small>".$r["content"]."</div>";
		echo "<div class=small><b>Переходов:</b> ".$r["gout"]."</div>";
		?>
		</td></tr>
		
		<?}?>
	</table>
