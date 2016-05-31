<?
#Настройка сайта#2
# Блок проверки наличия таблицы table1
$query="
	param varchar(255) default NULL,";  

create_MySQL_table($query,1,0);
mysql_query("ALTER TABLE ".PREF."_admin_site ADD page VARCHAR(100) AFTER param");
s_insert("","id,name,content,param","1000016, \"Разбивка на страницы по количеству\", \"30\", \"kfp\"");



# =======================================
	//$noadd=1;
	define_edit_param();

	if (isset($add) || isset($edit) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","param","content");
		$ed->input_komments=array("Свойство:","Переменная:","Значение:");
		$ed->input_types=array("textarea2","text","text");
		act_message($ed);}


#======================================
		
		$rec=row_select("","","","top,name");
		table_if_empty($rec);
		$i=0;$style="";
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,

		# Определяем имя раздела, к которому относится переменная
		if ($row["top"]==0) $razd="Сайт:";
		else $razd=s_select("name","admin_tree","id=".$row["top"]);
		
		# Делаем черезполосицу
		if ($i==0) $topid=0;
		if ($topid!=$row["top"]) {
		$topid=$row["top"];
		if ($style=="") $style="ico-no-comment"; else $style="";
		}

		tpr(0,"");
		tpr("title",$razd,"","","Раздел",$style);
		tpr("title",$row["name"],"id=".$row["id"]."","text","Свойство",$style);
		tpr("title",$row["param"],"","","Переменная",$style);
		tpr("input",$row["content"],$row["id"],"content","Значение");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
			
?>