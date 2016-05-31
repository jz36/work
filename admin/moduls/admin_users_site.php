<?
#Пользователи сайта#3
# Блок проверки наличия таблицы table1
$query="
	password varchar(150) NOT NULL,
	email varchar(150) NOT NULL,
	tel varchar(150) NOT NULL,
	fio varchar(255) NOT NULL,
	dolznost text,
	user_group int(6) NOT NULL default 0,
	counter int(6) NOT NULL default 0,
	data datetime NOT NULL default 0,
	otdel	text,
	";  
create_MySQL_table($query,1,0);

mysql_query("INSERT INTO ".PREF."_$main VALUES (100001, \"root\", NULL, \"29ce45f70295605f\", \"igor@e-mail66.ru\", \"Супервайзер\", \"Супервайзер\", 0, 1, 0, 0)");
# =======================================
	$this_element="пользователя";

	define_edit_param();

	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("fio","user_group","name","password","email","tel","dolznost","otdel");
		$ed->input_komments=array("ФИО:","Статус","login","Новый пароль","E-mail","Телефон","Должность","Отдел");
		$ed->input_types=array("textarea2","select","text","text","text","text","text","text");
		
		$rec=row_select("id,name","admin_user_groups","visible=1 AND for_part=1","name");
		$tps1="0,,-- Группа пользователей";
		while($row=$rec->gr()){$tps1.="#".$row[0].",,".$row[1]."";}
		
		#Формируем список занятых логинов
		
		# Если раздел создан уже, то закрываем поле синоним
		if (isset($id)) {
			$where="name!='".s_select("name","","id=".$id)."'";
		}
		$res=row_select("name","",@$where);
		$java="0";
		while ($r=$res->ga()){
			$java.="#".$r["name"];		
		}

		$ed->java=array(1,1,$java,1);		
		$ed->input_data_values=array("",$tps1);
		
		act_message($ed);}
		
#======================================			
		
		$rec=row_select("","","name!=\"root\"");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,
		
		tpr(0,"");
		tpr_fast_icon("check","Включен");
		tpr("title",$row["name"],"id=".$row["id"]."","text","Login");
		tpr("title",$row["fio"],"","","ФИО");
		tpr("title",$row["dolznost"],"","","Должность");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
			
?>