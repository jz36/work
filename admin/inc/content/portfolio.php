<?

#Вывод портфолио для веба


	echo "<p>".s_select("content","","top=333")."</p>";
?>

<table class=tableno width=90%><?
$res=row_select("id,name,content,link,year,workers","","top=0 and visible=1");
while ($r=$res->ga()){?>
	
	<tr><td valign=top><?
	if ($ris=test_ris($main.$r["id"]."m")){
		$size_m=param("size_m",$main);
		if (!empty($size_m)){
			$temp=getimagesize("img/kat/".$ris);
			$razm[0]=$size_m;
		}
		else
			$razm=getimagesize("img/kat/".$ris);
		if (test_ris($main.$r["id"]."b")){
			?>
			<a href='<?=popupimg($main,$r["id"],"b",1)?>' title="Увеличить"><img src="<?=getimg($main,$r["id"],"m")?>" border=0 width=<?=$razm[0]?> ></a>
			<?
		}
		else{
			?>
			<img src="<?=getimg($main,$r["id"],"m")?>" border=0 width=<?=$razm[0]?> >
			<?
		}
	}
	else{
		echo "&nbsp;";	
	}
	
	?>		
	</td>
	<td valign=top width=100%>
	<h3><?=$r["name"]?></h3><?
	$r["link"]=str_replace("http://","",$r["link"]);
	if (!empty($r["link"])) echo "<a href=http://".$r["link"]." target=_blank>".$r["link"]."</a>";
	if (!empty($r["year"])) echo "<div class=small><b>Год:</b> ".$r["year"]."</div>";
	if (!empty($r["workers"])) echo "<div class=small><b>Разработчики:</b> ".$r["workers"]."</div>";
	if (!empty($r["content"])) echo "<div class=comment>".$r["content"]."</div>";
	?>
	<hr></td></tr>
	
	<?}

?>
</table>
