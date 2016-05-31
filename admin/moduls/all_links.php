<?
#Общий каталог ссылок#1
# Блок проверки наличия таблицы table1
$query="
	url text,
	top_table varchar(255) NOT NULL,
	top_id int(8) NOT NULL default 0,
	gout int(4) NOT NULL default 0,
	alert int(2) NOT NULL default 0,";

create_MySQL_table($query,1,0);

# =======================================
	$this_element="ссылку";
	if (empty($top_table)) $noadd=1;
	define_edit_param();
	
	if (!empty($add) || !empty($id)) {
		
		$ed=new table_edit();
		$ed->input_names=array("name","content","url","top_table","top_id");
		$ed->input_komments=array("Название:","Описание:","Ссылка:");
		$ed->input_types=array("textarea2","textarea","text","hidden","hidden");
		$ed->input_default_values=array("","","",$top_table,$top_id);
		act_message($ed,1);
		}

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
		tpr_fast_icon("alert","Важно");
		if (empty($top_table)){
		tpr("title",nav_line($row["top_table"],$row["top_id"]),"","","Раздел","small");
		}
		$class=""; if (@$row["alert"]!=0) $class="bold";
		tpr("title",$row["name"],"id=".$row["id"]."".url_dop_param(1),"text","Название ссылки",@$class);
		tpr("link","Открыть",$row["url"],"center","Открыть ссылку");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
		
?>