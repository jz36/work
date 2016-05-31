<?
#Наши представители

$res=row_select("id,name","","top=0 and visible=1");
while ($r=$res->ga()){?>
	<h3><?=$r[1]?></h3>
	<ul><?
	$res2=row_select("id,name","","top=".$r["id"]." and visible=1");
	while ($r2=$res2->ga()){?>
		<li><a href="javascript:void(0);" onClick="window.open('dillers.php?table=<?=$main?>&id=<?=$r2["id"]?>','banner','left=200,top=200,width=400,height=200');"><?=$r2["name"]?></a></li>
	<?}?>
		<li>Приглашаем к сотрудничеству в этом городе</li>
	</ul>
	<?
}
?>

