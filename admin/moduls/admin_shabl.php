<?
#Шаблоны ввода#3

# =======================================
	$this_element="шаблон";

	$nobutton=1;
	define_edit_param();
#======================================	
	
// Формируем список файлов из admin/moduls, и вытаскиваем оттуда название шаблона и его принадлежность
		$i=0;
		$dir_index = dir("".SITE_ADMIN_DIR."/moduls"); 
		while($get_filename=$dir_index->read()) { 
			if ( $get_filename != '.' && $get_filename != '..'){
			$flist = file("".SITE_ADMIN_DIR."/moduls/".$get_filename);
			# Проверяем, вдруг этот шаблон для какого-то конкретного сайта
			# для этого второй строкой в шаблоне должна стоять: #only#PREF
			$only = split("#",$flist[2]);
			if((@$only[1]=="only" && @$only[2]==PREF) || @$only[1]!="only"){
				$parts = split("#",$flist[1]);
				$file_index[$i]["name"] = $parts[1];
				$file_index[$i]["razd"] = $parts[2];
				$filetmp=split(".php",$get_filename);
				$file_index[$i]["file"] = $filetmp[0];
			}
			$i++;
		}}
		$dir_index->close(); 
		sort($file_index); 
		
		for ($i=0;$i<(count($file_index));$i++){

# 	$type, $name, $link, $icon, $i,

		if ($file_index[$i]["razd"]==0)	tpr(0,"menu-color0");
		if ($file_index[$i]["razd"]==1)	tpr(0,"menu-color1");
		if ($file_index[$i]["razd"]==2)	tpr(0,"menu-color2");
		if ($file_index[$i]["razd"]==3)	tpr(0,"menu-color3");
		//tpr_fast_icon("check");
		tpr("title",$file_index[$i]["name"],"","text");
		tpr("title",$file_index[$i]["file"],"","","Файл");
		tpr(1);
		}
?>