<?
#Каталог текстовый, многоуровневый#0
#param#level_in;Уровень вложенности;5#view;Параметр вывода: <br>таблица - table<br>список - list<br>выпадающий список - select<br>список ссылки - text<br>Вывод всех текстов на одну страницу - all;list#num_cols;Количество столбцов, если выводим таблицей;1#def_razd;Выводить по умолчанию первый подраздел (0-нет, 1-да);0#need_popup;Показывать выпадающее меню подразделов в тексте  (0-нет, 1-да);1#size_logo;Ширина лого раздела;100#
# Блок проверки наличия таблицы table1
$query=""; 
create_MySQL_table($query,1,1);

# =======================================
# =======================================

if	(empty($_POST["preview"])) {
	echo "
	<table width=30% cellpadding=5 class=table2 style='padding:3px;'>
	<tr>
	<td class='small alert'>К разделу можно прикрепить:";
	//if (empty($save) || empty($prop)) echo "<br><a href='?main=".$main."&prop=1'>Редактировать свойства раздела</a>";
	
	echo "</td>";
			$rec=row_select("id,name,visible","","id=0");
			$row=$rec->ga();
			$i=1;
			tpr_fast_icon("file");
			tpr_fast_icon("img");
			tpr_fast_icon("link");
			tpr_fast_icon("desc");
	
	echo "
	</tr>	
	</table><hr>";	
}


//=====================================================
	$this_element="раздел";
	define_edit_param();
	
	if (isset($add)) {
		$ed=new table_edit();
		$ed->input_names=array("name");
		$ed->input_komments=array("Название:");
		$ed->input_types=array("textarea2");
		act_message($ed,1);}
	
	if ((isset($edit) || isset($id)) && $sub==1) {
		$ed=new edit();
		$ed->input_names=array();
		$ed->input_komments=array();
		$ed->input_types=array();
		$ed->input_data_types=array();
		act_message($ed,1);}
			
	if ($sub!=1 && isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "logo","logo");
		$ed->input_komments=array("Название этого раздела:", "Лого раздела:","Необработанная картинка");
		$ed->input_types=array("textprint", "img","imgres");
		
		$tps0=s_select("name","","id=".$id);
		$ed->input_data_values[0]=$tps0;
		$ed->input_default_values[0]=$tps0;
		act_message($ed);}
			

#======================================			
		$level_in_is=level_in("",1);
		if (empty($top)) insert_dop_info("content","Текст в начале раздела",$main,0);
		
		$rec=row_select("id,name,visible","","top=".$top);
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();		
		
# 	$type, $name, $link, $icon, $i,
		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		if ($level_in_is>0)
		tpr("title",$row["name"],"id=".$row["id"]."&top=$top","text","Раздел");
		else{
		tpr("title",$row["name"],"top=".$row["id"]."","folder","Раздел");
		tpr_fast_icon("edit","Описание");}
		tpr("title","лого","id=".$row["id"]."&sub=2","","Лого","small","align=center");
		tpr_fast_icon("file");
		tpr_fast_icon("img");
		tpr_fast_icon("link");
		tpr_fast_icon("desc");
		tpr_fast_icon("del");
		tpr(1);

		$i++;}
			
?>