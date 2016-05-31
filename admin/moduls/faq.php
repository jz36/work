<?
#Ответы на вопросы#0
# Блок проверки наличия таблицы table1
$query="
	otvet text,
	email varchar(255),";  
	
create_MySQL_table($query,1,0);

# =======================================
	$this_element="вопрос-ответ";
	define_edit_param();

	if (isset($add) || isset($id)) {		
		$ed=new table_edit();
		$ed->input_names=array("name","email","content","otvet");
		$ed->input_komments=array("Автор вопроса:","E-mail:","Вопрос:","Ответ:");
		$ed->input_types=array("text","text","textarea","textarea");
		act_message($ed);}

#======================================			
		
insert_dop_info("content","Текст перед вопросами-ответами",$main,1);

	$rec=row_select();
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title","<b>Вопрос:</b> ".$row["content"]."</a><div class=small>".$row["name"]."</div><a>","id=".$row["id"]."","text","Вопрос");
		tpr("title","<b>Ответ:</b> ".$row["otvet"],"","text","Ответ");
		tpr_fast_icon("del");
		tpr(1);
		$i++;			
		}
?>