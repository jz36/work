<?
#Бегущая строка#1
#param#speed;Скорость прокрутки (от 1 до 10);5#msg_for_page;Сколько сообщений одновременно прокручивать на одной странице;10#margin;Расстояние между сообщениями;200#
# Блок проверки наличия таблицы table1

$query="
	data_pub date NOT NULL default 0,
	time_pub varchar(10),
	data_end date NOT NULL default 0,
	time_end varchar(10),
	alert int(2) NOT NULL default 0,";
  
create_MySQL_table($query,1,0);

# =======================================
	$this_element="сообщение";
	$pager=1;
	define_edit_param();

	if (isset($id) || isset($add) || isset($edit)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","data_pub","time_pub","data_end","time_end","alert");
		$ed->input_komments=array("Сообщение:","Ссылка на тексте","Дата публикации:","Время публикации:","Дата окончания публикации:","Время окончания публикации:","Важность сообщения:");
		$ed->input_types=array("textarea2","textarea2","text2","text2","text2","text2","select");
		$ed->input_default_values=array("","",date("Y-m-d"),date("h:i"),(date("Y")+1).date("-m-d"),date("h:i"));
		$ed->input_data_types=array(1,1,3,1,3,1,1,1);
		
		# Выводим возможное время публикации
		$tps5="0,,Обычное#1,,Важное#2,,Очень важное#3,,Только на главной";
		
		$ed->input_data_values=array("","","","","","",$tps5);
		
		act_message($ed,2);}
	
# =======================================
	
	$alert="0,,Обычное#1,,Важное#2,,Очень важное#3,,Только на главной";

	$rec=row_select_pages("id, content, name, data_pub, data_end, time_pub, time_end, visible,alert","","","ord,data_pub DESC,time_pub DESC");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		$class=""; if (@$row["alert"]!=0) $class="bold";
		tpr("title",$row["name"],"id=".$row["id"],"text","Сообщение",$class);
		tpr("link","Ссылка",$row["content"],"","Ссылка");
		tpr("select",$row["alert"],$row["id"],"alert","Важность",$alert);		
		tpr("data",remakedata($row["data_pub"]).", ".substr($row["time_pub"],0,5),"1","","Начало");
		tpr("data",remakedata($row["data_end"]).", ".substr($row["time_end"],0,5),"1","","Окончание");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
		
		
		
	?>