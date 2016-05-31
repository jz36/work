<?
#Корзина товаров для интернет-магазина#0

/////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Вывод козины и обработка заказа
////////////////////////////////////////////////
	
	//echo "<h2>".s_select("name","admin_tree","page='cart'")."</h2>";	
	?><table class=table1><?	
	$res0=row_select("id,top_id,kolvo","","sid='".$_COOKIE['cart_sid']."'");
	while ($r0=$res0->ga()){
		$res=row_select("*",$cat_table,"id=".$r0["top_id"]);
		$r=$res->ga();
		if (empty($new)) {
			$new=1;
			foreach($cat as $value){
				echo "<th>".$value["name"]."</th>";				
			}
			echo "<th>Кол-во</th>";	
			echo "<th>Сумма, руб</th>";	
			echo "<th>Удалить</th>";
		}
		echo "<tr>";
		foreach($cat as $value){
			if (!empty($value["align"])) $align="align=".$value["align"]; else $align="";
			if ($value["col"]=="name") echo "<td ".$align."><a href='".SPAGE."?main=".$cat_table."&id=".$r["id"]."'>".$r[$value["col"]]."</a></td>";
			else echo "<td class=small ".$align.">".$r[$value["col"]]."</td>";
		}
		?><td><input name=i<?=$r["id"]?> size=2 value=<?=$r0["kolvo"]?>></td><?
		echo "<td align='".$cat[$cat_price]["align"]."'>".$r0["kolvo"]*$r[$cat[$cat_price]["col"]]."</td>";
		@$all_price+=$r0["kolvo"]*$r[$cat[$cat_price]["col"]];
		?><td align='center'><a href='javascript:void(0);' class=small title='<?=$title?>' onclick ="window.cart.location='popup.php?file=cart.php&action=view&did=1&top_table=<?=$cat_table?>&top_id=<?=$r["id"]?>';//window.location.reload();"><img src='img/i_cart_add.gif' width=18 align=absmiddle border=0></a></td><?
	}
	?></table><?
	if ($res0->nr()!=0)
		echo "<div class=bold align=right>Итого: ".$all_price."</div>";
	else
		echo "<h4>В корзине нет выбранных товаров</h4>";
?>