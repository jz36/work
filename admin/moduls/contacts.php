<?
#Контакты#0
# Блок проверки наличия таблицы table1

$query="";
  
create_MySQL_table($query,1,0);

# =======================================	
if (isset($top) && $top>0) { 
	
	$this_element="контакт";	
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "content");
		$ed->input_komments=array("Название:", "Содержание:");
		$ed->input_types=array("text", "textarea");
		act_message($ed);
	}

	# ============	
	$rec=row_select("id, name, visible, content","","top=".$top);
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
	# 	$type, $name, $link, $icon, $i,		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"&id=".$row["id"]."".url_dop_param(1),"text");
		tpr("input",$row["content"],$row["id"],"content","",45,1);
		tpr_fast_icon("del");
		tpr(1);
		$i++;
	}
	
}

# =======================================
else {
	
	$this_element="блок контактов";	
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "0");
		$ed->input_komments=array("Название:", "Схема проезда:");
		$ed->input_types=array("text", "img");
		act_message($ed);
		}

	# ============
	$rec=row_select("id, name, visible","","top=0");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
	# 	$type, $name, $link, $icon, $i,		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"top=".$row["id"],"folder","Реквизиты");
		tpr_fast_icon("edit","Изменить название и карту проезда");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
	
}?>