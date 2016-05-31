<?
#Вывод карты сайта#0

$num_cols=param("num_cols",$main);
$in_cols=param("in_cols",$main);


# Первый уровень
$id_where=" AND visible=1";
$res=row_select("id,name,page,alert,shablon","admin_tree","(menu_top=\"0\" OR menu_top='') and global_id=\"0\" $id_where ","global_id");
$n=$res->nr();
$i=0;$col=1;
?>
<table  cellpadding=10 class=tableno><tr><td valign=top><?
while($r=$res->ga()){
	echo "<a href=".SPAGE."?main=$r[2]><h3>$r[1]</h3></a><ul>";
	# Второй уровень
	if ($r["alert"]==1){
	$res2=row_select("id,name,page,alert,shablon","admin_tree","menu_top=\"$r[0]\" and global_id=\"0\" $id_where ","global_id");
	# Выводим самостоятельные подразделы
	while($r2=$res2->ga()){
		echo "<li><div class=bold><a href=".SPAGE."?main=$r2[2]>$r2[1]</a></div>";
		# Третий уровень
		if ($r2["alert"]==1){
			#Выводим подразделы из каталога
			if (substr($r2["shablon"],0,5)!="1page" && $r2["shablon"]!="redirect"){
				@$res3=row_select("name,id",$r2["page"],"top=0 and visible=1");
				while(@$r3=$res3->ga()){
					echo "<span>[<a href=".SPAGE."?main=".$r2["page"]."&id=".$r3["id"].">".$r3["name"]."</a>]&nbsp;</span> ";
			}}			
			#Выводим самостоятельные подразделы
			$res3=row_select("id,name,page","admin_tree","menu_top=\"$r2[0]\" and global_id=\"0\" $id_where ","global_id");
			while($r3=$res3->ga()){
				echo "<span>[<a href=".SPAGE."?main=$r3[2]>$r3[1]</a>]&nbsp;</span>";
			}
		}
	}
	# Выводим подразделы каталога
		if (substr($r["shablon"],0,5)!="1page" && $r["shablon"]!="redirect"){
			@$res2=row_select("name,id",$r["page"],"top=0 and visible=1");
			while(@$r2=$res2->ga()){
			echo "<li><div class=bold><a href=".SPAGE."?main=".$r["page"]."&id=".$r2["id"].">".$r2["name"]."</a></div>";
			}
		}			
	}
	echo "</ul>";
	if ($i%$in_cols==($in_cols-1) AND $col<$num_cols) {echo "</td><td valign=top>";$col++;}
	$i++;
}
?>

</td></tr></table><?


?>


