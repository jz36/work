<?
#Каталог статей#0
#param#level_in;Уровень вложенности;0#size_m;Ширина маленькой картинки;150#size_b;Размер большой картинки (по большей стороне);500#
# Блок проверки наличия таблицы table1
$query="
	anons text,
	data date NOT NULL default 0,
	ist text,
	"; 
create_MySQL_table($query,1,1);


# =======================================

	if (level_in("",1)==true) $this_element="статью";
	else $this_element="раздел";
	define_edit_param();
	
	level_in("",1);
	if ((isset($add) || isset($id))) {
		
		if (level_in("",1)==false){	
			$ed=new table_edit();
			$ed->input_names=array("name","content");
			$ed->input_komments=array("Название:","Описание раздела");
			$ed->input_types=array("text","textarea");
			$ed->input_data_types=array(1,1,1,1,1,1);
			$n=2;
		}
		else {
			$ed=new edit();
			$ed->input_names=array("anons","data","ist");
			$ed->input_komments=array("Анонс статьи","Дата публикации","Источник статьи");
			$ed->input_types=array("textarea","text2","text");
			
			$ed->input_komments2=array("","(В формате дд.мм.гг)");
			$ed->input_default_values=array("",date("Y-m-d"));
			$ed->input_data_types=array(1,2,1,1,1,1);
			$n=3;
		}
		
		if (isset($id)){
			# Перемещение в другой раздел
			$ttop="";

			@$ttop=s_select("top","","id=".$top);
			if (!empty($top)) $ttop=" || top=".$ttop;
			@$res=row_select("id,name,top","","(top=".$top.$ttop.") AND id!=".$id,"top,ord");
			$tps3=s_select("top","","id=".$id,"ord").",,---------------#0,,Верхний уровень#";
			while ($r=$res->ga()){
				$tps3.=$r["id"].",,--- ".$r["name"]."#";
			}		
			$ed->input_names[$n]="top";
			$ed->input_komments[$n]="Переместить в другой раздел";
			$ed->input_types[$n]="select";
			$ed->input_data_values[$n]=$tps3;
		}
		
		act_message($ed,2);}
			
			

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
		tpr("title",$row["name"],"id=".$row["id"]."&top=$top","text","Статья");
		else{
		tpr("title",$row["name"],"top=".$row["id"]."","folder","Раздел");
		tpr_fast_icon("edit","Описание");}
		tpr("title","лого","id=".$row["id"]."&sub=2","","Лого","small","align=center");
		tpr_fast_icon("file");
		tpr_fast_icon("img");
		tpr_fast_icon("link");
		tpr_fast_icon("copy");
		tpr_fast_icon("del");
		tpr(1);

		$i++;}
			
?>