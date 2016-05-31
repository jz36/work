<?
#Колонтитул#2
# Блок проверки наличия таблицы table1
$query="";  
create_MySQL_table($query,1,0);

# =======================================
	define_edit_param();

	if (isset($add) || isset($edit) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content");
		$ed->input_komments=array("Название:","Описание:");
		$ed->input_types=array("textarea2","textarea2");
		act_message($ed);}


#======================================			
		
		$rec=row_select("id, name,visible,content");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,
		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"]."","text");
		tpr("input",$row["content"],$row["id"],"content","",45,1);
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
			
?>