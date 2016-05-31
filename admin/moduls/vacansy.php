<?
#Вакансии#0
#param#email;Емайл на который будут дублироваться сообщения;#
# Блок проверки наличия таблицы table1
$query="
	conditions text,
	oplata text,
	data date NOT NULL default 0,";

create_MySQL_table($query,1,0);

# =======================================
	$this_element="вакансию";

	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","conditions","oplata","data");
		$ed->input_komments=array("Специальность:","Требования:","Условия:","Оплата:","Актуально до:</b><span class=small>(В формате дд.мм.гг)</span><b>");
		$ed->input_types=array("text","textarea","textarea","text","text");
		$ed->input_data_types=array(1,1,1,1,3);
		$ed->input_default_values[4]=date("Y-m-d");
		act_message($ed,2);}

#======================================	
	
	$rec=row_select("id,name,visible,data","","","ord,name");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		//tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("data",$row["data"],"","","Актуально до:");
		tpr("title",$row["name"],"id=".$row["id"],"text","Имя");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
?>