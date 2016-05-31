<?
#Форум#0
# Блок проверки наличия таблицы table1

$res=row_select("id","","","","1");
if (mysql_errno()!=0){
	echo mysql_errno();
	require(SITE_ADMIN_DIR."/moduls/__forum_init.php");
	for ($n=0;$n<count($sql);$n++){
		mysql_query($sql[$n]);
		echo $sql[$n]."<p><font color=red><b>".mysql_errno().": ".mysql_error()."</b></font><BR>";
	}
}


# =======================================
#Вычисляем уровень
$tmp=s_select("top","","id=".$top);

# Редактируем форумы
if ($top==0){
	
	$this_element="форум";
	define_edit_param();
	
	if (isset($add) || isset($edit) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content");
		$ed->input_komments=array("Название:","Описание:");
		$ed->input_types=array("textarea2","textarea");
		act_message($ed);
	}

	#======================================

	$rec=row_select("","","top=0");
	table_if_empty($rec);
	$i=0;
	while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
			
		$tmp=row_select("id","","top=".$row["id"]);
		$num_t=$tmp->nr();
		
		# 	$type, $name, $link, $icon, $i,
	   
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("alert");
		tpr("title",$row["name"]."</a><div class=comment>".$row["content"]."</div><a>","top=".$row["id"]."","folder","Название форума");
		tpr("data",$num_t,"1","","Темы");
		tpr_fast_icon("edit","Описание");
		tpr_fast_icon("del");
		tpr(1);
		$i++;
	}
}

# =======================================
# Редактируем Темы
elseif ($top!=0 && $tmp==0){
	
	$this_element="тему";
	define_edit_param();
	
	if (isset($add) || isset($edit) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("author","email","name","ip","data","time","top");
		$ed->input_komments=array("Автор:","E-mail:","Тема обсуждения:","IP автора","","","Переместить в другой форум");
		$ed->input_types=array("text","text","textarea","text2","hidden","hidden","select");
		$ed->input_default_values[0]=$_SESSION["user_fio"];
		$ed->input_default_values[1]=$_SESSION["user_email"];
		$ed->input_default_values[4]=date("Y-m-d");
		$ed->input_default_values[5]=date("h:m");
		
		
		# Перемещение в другой форум
		$ttop=s_select("top","","id=".$top);
		$res=row_select("id,name","","top=".$ttop,"ord");
		$tps6="";
		while ($r=$res->ga()){
			$tps6.=$r["id"].",,".$r["name"]."#";
		}
		
		$ed->input_data_values[6]=$tps6;
		
		act_message($ed);
	}

	#======================================

	$rec=row_select("","","top=$top");
	table_if_empty($rec);
	$i=0;
	while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
			
		$tmp=row_select("id","","top=".$row["id"]);
		$num_t=$tmp->nr();
			
		# 	$type, $name, $link, $icon, $i,
	   
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("alert");
		tpr("title",$row["name"],"top=".$row["id"]."","folder","Название темы");
		tpr_fast_icon("edit","Описание");
		tpr("data",$num_t,"1","","Сообщения");
		tpr("email",$row["author"],$row["email"],"","Автор");
		tpr("link","забанить автора",PAGE."?main=".$main."_bun&add=1&ip=".$row["ip"]."&author=".$row["author"],"center","Забанить","small","1");
		tpr("data",remakedata($row["data"])."&nbsp;| ".substr($row["time"],0,5),"1","","Добавлена");
		tpr_fast_icon("del");
		tpr(1);
		$i++;
	}
}


# =======================================
# Редактируем сообщения	
elseif ($top!=0 && $tmp!=0){
	
	$this_element="сообщение";
	define_edit_param();
	
	if (isset($add) || isset($edit) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("author","email","name","ip","data","time","top");
		$ed->input_komments=array("Автор:","E-mail:","Сообщение:","IP автора","","","Переместить в другой форум");
		$ed->input_types=array("text","text","textarea","text2","hidden","hidden","select");
		$ed->input_default_values[0]=$_SESSION["user_fio"];
		$ed->input_default_values[1]=$_SESSION["user_email"];
		$ed->input_default_values[3]=$_SERVER['REMOTE_ADDR'];
		$ed->input_default_values[4]=date("Y-m-d");
		$ed->input_default_values[5]=date("h:m");
		
		# Перемещение в другой форум
		$ttop=s_select("top","","id=".$top);
		$res=row_select("id,name","","top=".$ttop,"ord");
		$tps6="";
		while ($r=$res->ga()){
			$tps6.=$r["id"].",,".$r["name"]."#";
		}
		
		$ed->input_data_values[6]=$tps6;	
		
		act_message($ed);
	}

	#======================================

	$rec=row_select("","","top=$top","id");
	table_if_empty($rec);
	$i=0;
	while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
			
		$tmp=row_select("id","","top=".$row["id"]);
		$num_t=$tmp->nr();
			
		# 	$type, $name, $link, $icon, $i,
	   
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("alert");
		tpr("title",$row["name"],"id=".$row["id"]."&top=".$top,"text","Сообщение");
		tpr("email",$row["author"],$row["email"],"","Автор");
		tpr("link","забанить автора",PAGE."?main=".$main."_bun&add=1&ip=".$row["ip"]."&author=".$row["author"],"center","Забанить","small","1");
		tpr("data",remakedata($row["data"])."&nbsp;| ".substr($row["time"],0,5),"1","","Добавлено");
		tpr_fast_icon("del");
		tpr(1);
		$i++;
	}
}
?>