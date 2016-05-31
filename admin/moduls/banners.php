<?
#Баннеры на сайте#1
# Блок проверки наличия таблицы table1

$query="
  format varchar(255),
  url text,
  view int(10) NOT NULL default 0,
  maxview int(10) NOT NULL default 0,
  click int(10) NOT NULL default 0,
  data_pub date NOT NULL default 0,
  data_end date NOT NULL default 0,
  rate int(3) NOT NULL default 0,
  divisions varchar(255) NOT NULL default 0,
  ";
create_MySQL_table($query,1,0);

# Создаем таблицу для статистики
$query="
  url text,
  ";
  
//create_MySQL_table($query,1,0);

mysql_query("ALTER TABLE ".PREF."_$main ADD maxview int(10) NOT NULL default 0 AFTER view");
mysql_query("ALTER TABLE ".PREF."_$main ADD target int(1) NOT NULL default 0 AFTER view");
mysql_query("ALTER TABLE ".PREF."_$main ADD kolvo int(3) NOT NULL default 0 AFTER view");
mysql_query("ALTER TABLE ".PREF."_$main ADD sorting varchar(30) NOT NULL default '' AFTER view");
mysql_query("ALTER TABLE ".PREF."_$main ADD file varchar(255) NOT NULL default '' AFTER view");

# =======================================

# Редактируем группы баннеров
if ($top==0) {
	$this_element="группу баннеров";
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","format","kolvo","sorting");
		$ed->input_komments=array("Название группы:","Синоним группы:","Формат группы:","Количество на странице","Тип сортировки при выводе");
		$ed->input_komments2[2]="Ширина и высота картинки в баннере, высоту можно не указывать. Пример - 100,100";
		$ed->input_komments2[3]="Если количество - 0, то выводятся все активные";
		$ed->input_types=array("textarea2","text","text2","text","select");
		$ed->input_default_values[3]="0";
		
		# Выводим виды форматов
		$tps2="Не определен,,Не определен#468x60,,468x60#160x300,,160x300#120x60,,120x60#100x100,,100x100#Текстовый баннер,,Текстовый баннер";
				 
		# Выводим виды сортировки
		$tps4="RAND(),,Случайный выбор#ord,,По порядку";
		
		$ed->input_data_values=array("","",$tps2,"",$tps4);
		$ed->java=array("",1);
		
		act_message($ed);}


	#======================================	
	
	$rec=row_select("id, name, content,format, visible","","top=0");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
		$r=row_select("id","","top=".$row["id"]);
		$tmp=$r->nr();
		
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"top=".$row["id"]."","folder","Группа баннеров");
		tpr_fast_icon("edit");
		tpr("title",$row["content"],"","","Синоним");
		tpr("link",$row["format"],"","center","Формат");
		tpr("link",$tmp,"","center","Кол-во баннеров");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
}
#======================================

# Редактируем баннеры
if ($top!=0) {
	$this_element="баннер";
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","url","data_pub","data_end","rate","maxview","target","i","f","file","inputs10","divisions");
		$ed->input_komments=array("Название:","Текст:","Ссылка:","Дата начала:","Дата окончания:","Частота показов:","Максимальное количество показов:","Открывать в новом окне:","Картинка:","Флэш:","Имя файла на сервере:","Файл:","В каких разделах показывать:");
		$ed->input_types=array("textarea2","textarea2","textarea2","text2","text2","text2","text2","select","img","flash","text","file","select2");
		$ed->input_komments2[0]="Используется также в подсказке к картинке";
		$ed->input_komments2[1]="Нужен только для текстовых баннеров";
		$ed->input_komments2[5]="Задается в процентах (от 1 до 100). Чем больше цифра, тем больше вероятность показа баннера. Значок \"%\" ставить не надо!!!";
		$ed->input_komments2[6]="Если значение 0, то баннер показывается без ограничений.";
		$ed->input_komments2[10]="Если загружается файл, то ОБЯЗАТЕЛЬНО написать его имя, (без расширения файла!!) ";
		$ed->input_komments2[12]="Если вы выберите какие-то конкретные разделы, то баннер будет показываться только в них. <br>Но приоритет показа в этих разделах у него станет выше.";
		$ed->input_default_values=array("","","",date("Y-m-d"),(date("Y")+1).date("-m-d"),"100","0","","","","","","0");
		$ed->input_data_types=array(1,1,1,3,3,1,1,1,1,1,1,1,1,1,1);
		
		// Если мы редактируем запись, вывод файла если есть
		if (isset($id)) {
			$name=s_select("file","","id=$id");
			$ed->input_names[11]=$name;
			//$ed->input_types[10]="hidden";
		}
		
		# Как открываем ссылку, "в новом окне?"
		$tps7="0,,Нет#1,,Да";
		
		# Выводим виды форматов
		$tps12="0,,Все разделы";
		$row=row_select("id,name,page","admin_tree","visible=1 AND (menu_top=\"0\" OR menu_top='') AND global_id=0");
		while ($r=$row->ga()){
			$tps12.="#".$r["page"].",,- ".$r["name"];	
			$row2=row_select("id,name,page","admin_tree","visible=1 AND menu_top=".$r["id"]." AND global_id=0");
			while ($r2=$row2->ga()){
				$tps12.="#".$r2["page"].",,----- ".$r2["name"];	
			}
		}
		$ed->input_data_values[7]=$tps7;
		$ed->input_data_values[12]=$tps12;
		
		act_message($ed,2);}

	#======================================	
	
	$rec=row_select("id, name, content, rate, view, click, visible,maxview","","top=$top");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
		$r=row_select("id","","top=".$row["id"]);
		$tmp=$r->nr();
		if ($row["maxview"]!=0) $maxview=" из ".$row["maxview"]; else  $maxview="";
		
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"]."&top=$top","text","Заголовок");
		tpr("img",$row["id"],"i","","Баннер");
		tpr("flash",$row["id"],"f","","Флэш");
		tpr("link",$row["rate"],"","center","Частота показов");
		tpr("link",$row["view"]."$maxview","","center","Показов");
		tpr("link",$row["click"],"","center","Кликов");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
}
#======================================
		
?>


