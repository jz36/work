<?
#Список забаненых юзеров#0
# Блок проверки наличия таблицы table1
$query="
	ip varchar(20) NOT NULL DEFAULT 0,";  
create_MySQL_table($query,1,0);

# =======================================

	$this_element="запрещенный IP";
	define_edit_param();

	if (isset($add) || isset($edit) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","ip","content");
		$ed->input_komments=array("Автор:","IP:","Причина:");
		$ed->input_types=array("text","text","textarea2");
		if (isset($author)) $ed->input_default_values[0]=$author;
		if (isset($ip)) $ed->input_default_values[1]=$ip;
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
		tpr("title",$row["name"],"id=".$row["id"],"text","Автор");
		tpr("input",$row["ip"],$row["id"],"content","IP");
		tpr("input",$row["content"],$row["id"],"content","Причина");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
			
?>