<?
#Каталог тектовый, многоуровневый#0

///////////////////////////////////////////////////////
//////////////////////////////////////////////////////
// Вывод каталога магазина
/////////////////////////////////////////////////////

	if (!isset($top)) $top=0;
	
	if (empty($id)){
		$id=0;
		echo s_select("content","","top=333");
		}
	else {
		echo "<h2>".s_select("name","","id=$id")."</h2>";	
		}

// Если у раздела есть подразделы значит это не конечный продукт
if (s_select("id","","top=$id")!=""){
	// Выводим список разделов
	?><table class=table1><?
	$res=row_select("*","","top=$id and visible=1");
	while($r=$res->ga()){
		// Если есть подразделы, значит это оне конечный продукт. Выводим подразделы
		?>
			<tr><td valign=top><a href='<?SPAGE?>?main=<?=$main?>&id=<?=$r["id"]?>'<?if ($id==$r["id"]) echo $this;?>><h4><?=$r["name"]?><?
			$res2=row_select("name,id","","top=".$r["id"]);
			$kol=$res2->nr();
			while($r2=$res2->ga()){
				// Выводим, только если внутри есть подразделы
				if (s_select("id","","top=".$r2["id"])!=""){
					if (empty($new)) { echo "</h4></a><ul>"; $new=1;}?>
					<li><a href='<?SPAGE?>?main=<?=$main?>&id=<?=$r2["id"]?>'<?if ($id==$r2["id"]) echo $this;?>><?=$r2["name"]?></a></li><?
				}
				else {
					echo " <span class='small' style='font-weight:normal'>[Товаров: ".$kol."]</span></h4></a>";
					break;
				}
			}
			if (@$new==1) {echo "</ul>"; $new=0;}
			?></td></tr></table><table class=table1><?

			// Если подразделов нет, значит выводим список конечных продуктов
			if (empty($new)) {
				$new=1;
				foreach($cat as $value){
					echo "<th>".$value["name"]."</th>";				
				}
				echo "<th>Добавить</th>";	
			}
			echo "<tr>";
			foreach($cat as $value){
				if (!empty($value["align"])) $align="align=".$value["align"]; else $align="";
				if ($value["col"]=="name") echo "<td ".$align."><a href='".SPAGE."?main=".$main."&id=".$r["id"]."'>".$r[$value["col"]]."</a></td>";
				else echo "<td class=small ".$align.">".$r[$value["col"]]."</td>";
			}
			?><td><input name=i<?=$r["id"]?> size=2 value=0><a href='javascript:void(0);' class=small title='<?=$title?>' onclick ="window.cart.location='popup.php?file=cart.php&action=view&add=1&top_table=<?=$main?>&top_id=<?=$r["id"]?>&kolvo=' + document.getElementById('i<?=$r["id"]?>').value + '';alert('Товар добавлен в корзину');"><img src='img/i_cart_add.gif' width=18 align=absmiddle border=0></a></td><?

	}?></table><?
}

else{?>
	<div align=right style="float:right;"><?printSubList($main,0,"select","0","и другие");?></div><?
	$res=mysql_query("select name,id,content from ".PREF."_$main where id=\"$id\"");
	$r=mysql_fetch_row($res);
	if ($title!=$r[0]){ echo "<h2 nowrap>$r[0]</h2>"; }
	echo $r[2];
}

?>