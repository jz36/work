<?
#Тестирование онлайн#0
#param#email;Емайл на который будут дублироваться сообщения;#
#Блок проверки наличия таблицы table1
$query="";
  
create_MySQL_table($query,1,0);
# =======================================
	$this_element="поле формы";

	define_edit_param();
	
	if (isset($add) || isset($id)) {

		$ed=new table_edit();
		$ed->input_names=array("name","content");
		$ed->input_komments=array("Название поля:","Значение:");
		$ed->input_types=array("textarea2","textarea3");


		act_message($ed);}
	

#======================================	
insert_dop_info("content","Текст перед формой",$main,1);
insert_dop_info("content","Сообщение после отправки формы",$main,2);
	
	$rec=row_select();
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"]."","text","Название поля");
		tpr("title",$row["content"],"","","Значение");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
?>