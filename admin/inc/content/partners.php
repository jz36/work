<?

#выводим список друзей, партнеров

echo s_select("content","","id=100000","","","","1");
?>
<table class=tableno width=90%><?
$city="";
$res=row_select("id,name,content,link,email,city","","top=0 and visible=1");
while ($r=$res->ga()){?>
	
	<tr><td valign=top><?
	if ($ris=test_ris($main.$r["id"]."m")){
		$size_m=param("size_m",$main);
		if (!empty($size_m)){
			$temp=getimagesize("img/kat/".$ris);
			$razm[0]=$size_m;
			$razm[1]=$razm[0] * $temp[1] / $temp[0];
		}
		else
			$razm=getimagesize("img/kat/".$ris);
		?>
		<a href="<?=$r["link"]?>" target=_blank><img src="<?=getimg($main,$r["id"],"m")?>" border=0 width=<?=$razm[0]?> height=<?=$razm[1]?>></a>
		<?
	}
	elseif ($ris=test_ris($main.$r["id"]."f")){
		$size_m=param("size_m",$main);
		if (!empty($size_m)){
			$temp=getimagesize("img/kat/".$ris);
			$razm[0]=$size_m;
			$razm[1]=$razm[0] * $temp[1] / $temp[0];
		}
		else
			$razm=getimagesize("img/kat/".$ris);?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash4/cabs/swflash.cab#version=4,0,0,0"
		width=<?=$razm[0]?> height=<?=$razm[1]?> style="overflow:hidden; z-index:0;">
		<param name="quality" value="high">
		<param name="movie" value="<?="img/kat/".$ris?>">
		<param name="base" value="<?=$r["link"]?>">
		<embed wmode="transparent" name="banner" src="<?="img/kat/".$ris?>" quality="high" style="overflow:hidden; z-index:0;"
		base="<?=PATH?><?=SPAGE?>?main=banners&id=<?=$r["id"]?>" BORDER="0" FRAMEBORDER="NO" width=<?=$razm[0]?> height=<?=$razm[1]?> type="application/x-shockwave-flash"
		pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
	  </embed></OBJECT><br clear=all><?
	}
	else{
		echo "&nbsp;";	
	}
	// Проверяем вывод Уникальных городов
	if ($city!=$r["city"]) { $city=$r["city"]; $city_new=1;} 
	else $city_new=0;
	?>		
	</td>
	<td valign=top width=100%>
	<?if ($city_new==1) {
		//echo "<h2>".$city."</h2>";
	}?>
	<h3><?=$r["name"]?></h3><?
	if (!empty($r["link"])) echo "<a href=".$r["link"]." target=_blank>".$r["link"]."</a>";
	if (!empty($r["email"])) echo "<div class=small><b>E-mail:</b> ".email_echo($r["email"])."</div>";
	if (!empty($r["city"])) echo "<div class=small><b>Город:</b> ".$r["city"]."</div>";
	if (!empty($r["content"])) echo "<div class=small>".$r["content"]."</div>";
	?>
	</td></tr>
	
	<?}

?>
</table>
