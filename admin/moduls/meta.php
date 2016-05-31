<?
#META-данные#2
# Блок проверки наличия таблицы table1
$query="
	keywords text,
	description text,";
  
create_MySQL_table($query,1,0);

mysql_query("ALTER TABLE ".PREF."_$main ADD top_id int(8) NOT NULL default 0 AFTER description");
mysql_query("ALTER TABLE ".PREF."_$main ADD top_table varchar(255) NOT NULL AFTER description");
# =======================================
	$this_element="описание";
	define_edit_param();
	
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","keywords","description","top_table","top_id");
		$ed->input_komments=array("Для какого раздела:","Ключевые слова:","Описание:","","");
		$ed->input_types=array("select","textarea","textarea","hidden","hidden");
		$ed->input_default_values=array("","","",$top_table,$top_id);
		
		$rec=row_select("name,page,menu_top","admin_tree","visible=1 and global_id=0");
		$tps0="0,,Для всего сайта#1,,----------------";
		while($row=$rec->ga()){
			if (empty($row["menu_top"])) $tps0.="#".$row[1].",,".$row[0]."";
			else $tps0.="#".$row[1].",, -- ".$row[0]."";
		}
		
		$ed->input_data_values=array("$tps0");		
		act_message($ed,1);}

#======================================	
	
if (!empty($top_table)){
$where="top_table='$top_table' AND top_id='$top_id'";
$ord="name";}
else {
$where="";
$ord="top_table, top_id, name";}
		
	$rec=row_select("","",$where,$ord);

	table_if_empty($rec);
	$i=0;
	while (($rec->nr())>=$i) {
	if ($i!=1) $row=$rec->ga();

	
# 	$type, $name, $link, $icon, $i, $title

		tpr(0);
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		if (empty($top_table)){
		tpr("title",nav_line($row["top_table"],$row["top_id"]),"1","","Раздел","small");
		}
		tpr("title",s_select("name",$top_table,"id='".$top_id."'"),"id=".$row["id"]."".url_dop_param(1),"text","Название","small");
		tpr("title",$row["description"],"","text","Описание","small");
		tpr("title",$row["keywords"],"","text","Ключевые слова","small");

		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
		
?>