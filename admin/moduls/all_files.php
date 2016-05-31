<?
#Общий каталог файлов#1
# Блок проверки наличия таблицы table1
$query="
	gout int(8) NOT NULL default 0,
	top_table varchar(255) NOT NULL,
	top_id int(11) NOT NULL default 0,";

create_MySQL_table($query,1,0);

mysql_query("ALTER TABLE ".PREF."_$main ADD gout int(8) NOT NULL default 0 AFTER top_id");
# =======================================
	$this_element="файл";
	if (!isset($top_table)) $noadd=1;	
	define_edit_param();
	
	if (isset($add)) {
		
		$filename="qqq";$flag=0;
		while ($flag==0) {
			echo test_file(PREF."_file".$rand);
			if (test_file(PREF."_file".$rand)=="") {
				$filename=PREF."_".$top_table."_file".$rand;
				$flag=1;
		}}
		
		$ed=new table_edit();
		$ed->input_names=array("name","content","inputs3","top_table","top_id");
		$ed->input_komments=array("Описание файла:","Имя файла на сайте:","Загрузить файл:");
		$ed->input_komments2[1]="(Вы можете указать свое имя файла. Расширение файла указывать<br> не надо!)";
		$ed->input_types=array("textarea2","text","file","hidden","hidden");
		$ed->input_default_values=array("",$filename,"",$top_table,$top_id);
		$ed->java=array(0,1);		
		act_message($ed);}
	
	if (isset($id)) {
		
		$name=s_select("content","","id=$id");
		
		$ed=new table_edit();
		$ed->input_names=array("name","content",$name,"top_table","top_id");
		$ed->input_komments=array("Описание файла:","","Обновить файл:");
		$ed->input_types=array("textarea2","hidden","file","hidden","hidden");
		$ed->input_default_values=array("","","",$top_table,$top_id);
		act_message($ed,1);}
		

#======================================	
if (isset($top_table)){
$where="top_table=\"$top_table\" AND top_id=\"$top_id\"";
$ord="ord,name";}
else {
$where="";
$ord="top_table, top_id, name";}
		
	$rec=row_select("","",$where,$ord);

	table_if_empty($rec);
	$i=0;
	while (($rec->nr())>=$i) {
	if ($i!=1) $row=$rec->ga();
			
	$file=to_lat($row["content"],"_");
	if (test_file($file)!="") {
		$time=filectime("files/".test_file($file));
		$data=getdate($time);
		$time=fixDate($data["mday"]).".".fixDate($data["mon"]).".".$data["year"];
		$size=round(filesize("files/".test_file($file))/1000);
		}
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		if (!isset($top_table)){
		tpr("title",nav_line($row["top_table"],$row["top_id"]),"","","Раздел","small");
		}
		tpr("title",$row["name"],"id=".$row["id"]."".url_dop_param(1),"text","Описание файла");
		tpr("file",$file);
		tpr("data",@$time,"1","","Добавлен");
		tpr("title","<b>".@$size."</b> кБ","","","Размер","","align=right");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
		
		
?>