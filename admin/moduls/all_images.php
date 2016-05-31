<?
#Общий каталог изображений#1
# Блок проверки наличия таблицы table1
$query="
	top_table varchar(255) NOT NULL,
	top_id int(11) NOT NULL default 0,
	data date NOT NULL default 0,";

create_MySQL_table($query,1,0);

# =======================================
	$this_element="фото";
	if (!isset($top_table)) $noadd=1;

	define_edit_param();
	
	if (isset($add) || isset($id)) {
		
		$ed=new table_edit();
		$ed->input_names=array("name","content","data","m","b","top_table","top_id","m,b");
		$ed->input_komments=array("Название:","Описание","Дата: </b>(ДД.ММ.ГГ)<b>","Маленькая картинка:","Большая картинка:","","","Необработанная картинка");
		$ed->input_types=array("textarea2","textarea","text","img","img","hidden","hidden","imgres");
		$ed->input_default_values=array("","","","","",$top_table,$top_id);
		$ed->input_data_types=array(1,1,3,1,1,1,1,1,1,1);		
		$ed->input_default_values[2]=date("Y-m-d");
		act_message($ed,2);}

#======================================	
echo "<div class=comment><input type=button value='Показать изображения в списке' class=button></div>";

if (isset($top_table)){
$where="top_table=\"$top_table\" AND top_id=\"$top_id\"";
$ord="ord,id";}
else {
$where="";
$ord="top_table, top_id, name";}
		
	$rec=row_select("","",$where,$ord);

	table_if_empty($rec);
	$i=0;
	while (($rec->nr())>=$i) {
	if ($i!=1) $row=$rec->ga();

	
# 	$type, $name, $link, $icon, $i, $title

		tpr(0,"");
		tpr_fast_icon("check");
		tpr_fast_icon("ord");
		if (!isset($top_table)){
		tpr("title",nav_line($row["top_table"],$row["top_id"]),"","","Раздел","small");
		}
		tpr("title",$row["name"],"id=".$row["id"]."".url_dop_param(1),"text","Описание фото");
		tpr("img",$row["id"],"m","view","Просмотр");
		tpr("img",$row["id"],"m","","Маленькая");
		tpr("img",$row["id"],"b","","Большая");
		tpr("data",$row["data"],"","","Дата");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
		
?>