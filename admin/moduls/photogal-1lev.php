<?
#Фотогалерея без разделов#0
#param#cols;Количество столбцов для вывода фото;3#kfp;Количество фоток на странице;12#size_m;Ширина маленькой картинки;150#size_b;Размер большой картинки (по большей стороне);500#need_mark;Поставить оценку;1#need_comment;Оставить комментарий;1#
# Блок проверки наличия таблицы table1
$query="
	data date NOT NULL default 0,";

create_MySQL_table($query,1,0);

# =======================================
	$this_element="фото";

	define_edit_param();
	
	if (isset($add) || isset($id)) {
		
		$ed=new table_edit();
		$ed->input_names=array("name","content","data","m","b","m,b");
		$ed->input_komments=array("Название:","Описание","Дата: </b>(ДД.ММ.ГГ)<b>","Маленькая картинка:","Большая картинка:","Необработанная картинка");
		$ed->input_types=array("textarea2","textarea","text","img","img","imgres");
		$ed->input_default_values=array("","","","","");
		$ed->input_data_types=array(1,1,3,1,1,1,1);		
		$ed->input_default_values[2]=date("Y-m-d");
		act_message($ed,2);}

#======================================	
	if (empty($top)) insert_dop_info("content","Текст в начале раздела",$main,0);		
	
	$rec=row_select("","");

	table_if_empty($rec);
	$i=0;
	while (($rec->nr())>=$i) {
	if ($i!=1) $row=$rec->ga();

	
# 	$type, $name, $link, $icon, $i, $title

		tpr(0,"");
		tpr_fast_icon("check");
		tpr_fast_icon("ord");
		tpr("title",$row["name"],"id=".$row["id"],"text","Описание фото");
		tpr("img",$row["id"],"m","view","Просмотр");
		tpr("img",$row["id"],"m","","Маленькая");
		tpr("img",$row["id"],"b","","Большая");
		tpr("data",$row["data"],"","","Дата");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
		
?>