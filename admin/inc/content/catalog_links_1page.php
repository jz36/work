<?
#Каталог ссылок на 1-ной странице#0

$res=new recordset("select name,id from ".PREF."_$main where top=0 AND visible=1 order by ord,name");
while($r=$res->ga()){
	$res2=new recordset("select * from ".PREF."_links where top_table=\"".$main."\" AND top_id=".$r["id"]." order by ord,name");
	?>
	<h2><?=$r["name"]?></h2>
	<ol><?
	while($r2=$res2->ga()){?>
		<LI>
		<b><?=$r2["name"]?></b><br><a target=_blank href="<?=$r2["url"]?>" class=noul><?=$r2["url"]?></a><br>
		<?=$r2["content"]?><br><?
	}?>
	</ol><?
}

?>


