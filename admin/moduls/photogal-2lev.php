<?
#Фотогалерея с разделами#0
#param#level_in;Уровень вложенности;0#cols;Количество столбцов для вывода фото;3#gal_cols;Количество столбцов для вывода списка галерей;1#kfp;Количество фоток на странице;10#size_m;Ширина маленькой картинки;150#size_b;Размер большой картинки (по большей стороне);500#need_mark;Поставить оценку;1#need_comment;Оставить комментарий;1#
# Блок проверки наличия таблицы table1
$query=""; 
create_MySQL_table($query,1,1);

# =======================================
	$this_element="галерею";
	define_edit_param();

if($sub==1){
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "content");
		$ed->input_komments=array("Название:", "Содержание:");
		$ed->input_types=array("textarea", "textarea");
		act_message($ed);
	}
			

#======================================			
		$level_in_is=level_in("",1);
		
		$rec=row_select("id,name,visible","","top=".$top);
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();		
		
# 	$type, $name, $link, $icon, $i,
		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("img");
		if ($level_in_is>0)
		tpr("title",$row["name"],"id=".$row["id"]."&top=$top&sub=$sub","text","Раздел");
		else{
		tpr("title",$row["name"],"top=".$row["id"]."&sub=$sub","folder","Раздел");
		tpr_fast_icon("edit","Описание");}
		tpr_fast_icon("del");
		tpr(1);

		$i++;}
			
}?>