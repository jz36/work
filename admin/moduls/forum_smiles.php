<?
#Список смайликов для форума#0
# Блок проверки наличия таблицы table1
$query="";  
create_MySQL_table($query,1,0);

# =======================================

	define_edit_param();

	if (isset($add) || isset($edit) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","img");
		$ed->input_komments=array("Название:","Тег:","img");
		$ed->input_types=array("text","text","text");
		act_message($ed);}


#======================================			
		
		$rec=row_select("","","","name");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"],"text","Название");
		tpr("title","<img src=".SITE_ADMIN_DIR."/img/forum/smiles/".$row["img"].">","","","img");
		tpr("input",$row["content"],$row["id"],"content","Дополнение");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
			
?>