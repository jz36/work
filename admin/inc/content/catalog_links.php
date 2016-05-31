<?
#Каталог ссылок многоуровневый#0

if (!isset($top)) $top=0;

if (empty($id) && param("def_razd",$main)!=0){
	$id=s_select("id","","visible=1 AND top=0","ord");
}

if (empty($id)){
	echo s_select("content","","top=333","","","","1");
	$view=param("view",$main);
	$num_cols=param("num_cols",$main);
	if (empty($view)) {$view="list"; $num_cols="1";}
	printSubList($main,0,$view,$num_cols);
}

else{?>
	<div align=right style="float:right;"><?printSubList($main,0,"select","30%","и другие");?></div><?
	$res=row_select("name,id,content","","id=".$id);
	$r=$res->ga();
	if ($title!=$r[0]){ echo "<h2 nowrap>$r[0]</h2>"; }
	printSubList($main,$id,"text","0");
	echo $r[2];
	
	require(SITE_ADMIN_DIR."/inc/content/photogal-inner.php");
	require(SITE_ADMIN_DIR."/inc/content/inner_files.php");
}
?>


