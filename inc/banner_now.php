<?
#========================================
# Выводим случайным образом термин из словаря
function banner_now() {
	
	echo "<h2 style='border:0px solid #FFF;display:block;background:#BD0000;margin:10px -8px 0px;padding: 3px 5px;font-size:14px;color:#FFF;'>Зима 2007. Наши предложения</h2>";
	
	// Выводим селектор по возрасту
	$res=row_select("id,name,content,top","now","visible=1 AND top=0");
	while ($r=$res->ga()){
		
		// Выводим селектор по стране	
		echo "<div style='border-bottom:1px solid #BD0000;'><h3 style='margin:0px 0px; font-size:13px;color:#333;'>".$r["name"]."</h3>";
		$res2=row_select("id,name,content,top","now","visible=1 AND top='".$r["id"]."'");
		while ($r2=$res2->ga()){
			
			// Выводим селектор по курсу	
			echo "<div class='red bold'>".$r2["name"]."</div><ul>";
			$res3=row_select("id,name,content,top","now","visible=1 AND top='".$r2["id"]."'");
			while ($r3=$res3->ga()){
				echo "<li><a href='?main=now&id=".$r3['id']."' class='small'> ".$r3["name"]."</a></li>";
			
			}
		echo "</ul>";
		
		}
	echo "</div>";
	}

	

}
?>