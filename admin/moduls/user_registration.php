<?
#Регистрация на сайте#0
#Блок проверки наличия таблицы table1
$query="
	type varchar(255) NOT NULL,
	alert int(2) NOT NULL default 0,
	dop varchar(255) NOT NULL,";
  
create_MySQL_table($query,1,0);
# =======================================
	$this_element="поле формы";

	define_edit_param();
	
	if (isset($add) || isset($id)) {

		$ed=new table_edit();
		$ed->input_names=array("name","type","content","dop");
		$ed->input_komments=array("Название поля:","Тип поля:","Значение:","Дополнение:");
		$ed->input_types=array("textarea2","select","textarea","text");
		$ed->input_komments2[2]="Если тип поля - \"выпадающий список\",то укажите здесь значения пунктов списка, поставив каждое значение с новой строки.";

		$tps1="input,,Текстовая поле - 1 строка#textarea,,Текстовое поле - несколько строк#select,,Выпадающий список#text,,Текстовый блок (не поле)#data,,Дата#password,,Пароль#password2,,Проверка пароля#email,,E-mail";

		$ed->input_data_values=array("",$tps1,"","");
		act_message($ed);}
	
	# Если добавили новое поле анкеты, то создаем для него колонку в таблице юзеров
	if (isset($save)) {
		if (isset($add)) $tid=s_select("max(id)");
		if (isset($id))  $tid=$id;
		$type=s_select("type","","id=".$tid);
		$ttype="varchar(255) NOT NULL";
		switch ($type) {
			case "textarea": $ttype="text";break;
		}
		mysql_query("ALTER TABLE ".PREF."_users ADD i".$tid." ".$ttype);
	}

#======================================	
insert_dop_info("content","Текст перед формой",$main,1);
insert_dop_info("content","Текст с подтверждением регистрации",$main,15);
	
	$rec=row_select();
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("alert","Важность");
		tpr("title",$row["name"],"id=".$row["id"]."","text","Название поля");
		tpr("title",$row["type"],"","","Тип поля");
		tpr("title",$row["content"],"","","Значение");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
?>