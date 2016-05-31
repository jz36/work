<?
#Каталог тектовый, многоуровневый#0

if (empty($top)) $top=0;
if (!empty($id)) $top=s_select("top","","id=".$id); else $top=0;

if (empty($id) && param("def_razd",$main)!=0){
	$id=s_select("id","","visible=1 AND top=0","ord");
}

if (empty($id) || (!empty($id) && empty($top))){
	echo s_select("content","","top=333","","","","1");
	echo "<h2>Наши предложения</h2>";
	
	if (!empty($top)) $wtop=" AND top=".$top;	else  $wtop=" AND top=0";	
	if (!empty($id) && empty($top)) $wtop=" AND id=".$id;
	// Выводим селектор по возрасту
	$res=row_select("id,name,content,top","now","visible=1".@$wtop);
	while ($r=$res->ga()){
		
		// Выводим селектор по стране	
		echo "<h3>".$r["name"]."</h3>";
		$res2=row_select("id,name,content,top","now","visible=1 AND top='".$r["id"]."'");
		while ($r2=$res2->ga()){
			
			// Выводим селектор по курсу	
			echo "<div class='red bold'>".$r2["name"]."</div><ul>";
			$res3=row_select("id,name,content,top","now","visible=1 AND top='".$r2["id"]."'");
			while ($r3=$res3->ga()){
				echo "<li><a href='?main=now&id=".$r3['id']."'> ".$r3["name"]."</a></li>";
			
			}
		echo "</ul>";
		
		}
	}
}

else{
	if ((param("need_popup",$main))!="" && (param("need_popup",$main))!=0) {
	?><?
	}
	$res=row_select("name,id,content,top","","id=".$id);
	$r=$res->ga();
	echo "<h1>".s_select("name","now","id=".$r["top"])."</h1>";
	if ($title!=$r[0]){ echo "<h2 nowrap>".$r[0]."</h2>"; }
	printSubList($main,$id,param("view",$main),"0");
	//echo $r[2];
	$r[2]=str_replace("localhost/", "", $r[2]);
	echo str_replace("britannix", "britannix.ru", $r[2]);	
}
	require(SITE_ADMIN_DIR."/inc/content/inner_desc.php");
	require(SITE_ADMIN_DIR."/inc/content/inner_links.php");
	require(SITE_ADMIN_DIR."/inc/content/inner_files.php");
	require(SITE_ADMIN_DIR."/inc/content/photogal-inner.php");

?>