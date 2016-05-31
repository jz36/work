<?
#Каталог статей#0

@$top=s_select("top","","id=".$id);
if (empty($top)) $top=0;
if (empty($id)) $id=0;

#=========================================
# Если выводим подразделы

if (level_in("",1)==false){

	if (empty($id)){
		echo s_select("content","","top=333");
		$res=row_select("id,name,content","","top=$top");
		while ($r=$res->ga()){
			echo "<h2>".$r["name"]."</h2>";
			echo "<div>".$r["content"]."</div>";	
			printSubList($main,$r["id"],"list","30%","и другие");	
		
		}

	}
	
	else{
		echo "<h2>".s_select("name","","id=".$id)."</h2>";
		echo s_select("content","","top=333");
		$res=row_select("id,name,anons,data,ist","","top=".$id);
		while ($r=$res->ga()){
			echo "<h3>".$r["name"]."</h3>";
			echo "<div>".$r["anons"]."</div>";	
			echo "<div style='float:right' class=small>Опубликовано: ".remakedata($r["data"])."</div>";		
			echo "<div style='float:left' class=small><a href='".SPAGE."?main=".$main."&id=".$r["id"]."'>Смотреть далее >></a></div><br clear=all>";	
		
		}
	}
}
#=========================================
# Если выводим статьи

if (level_in("",1)==true){

	if (empty($id)){
		echo s_select("content","","top=333");
		$res=row_select("id,name,anons,data,ist","","top=".$id);
		while ($r=$res->ga()){
			echo "<h3>".$r["name"]."</h3>";
			echo "<div>".$r["anons"]."</div>";	
			echo "<div style='float:right' class=small>Опубликовано: ".remakedata($r["data"])."</div>";		
			echo "<div style='float:left' class=small><a href='".SPAGE."?main=".$main."&id=".$r["id"]."'>Смотреть далее >></a></div><br clear=all><hr>";	
		
		}

	}
	else {
		$res=mysql_query("select name,id,content from ".PREF."_$main where id=\"$id\"");
		$r=mysql_fetch_row($res);
		if ($title!=$r[0]){ echo "<h2 nowrap>$r[0]</h2>"; }
		echo $r[2]."<hr>";
		printSubList($main,$top,"select","80%","Все публикации");
	}
}




?>