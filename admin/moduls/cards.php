<?
#Отправка открыток с сайта#0
#param#size_m;Ширина маленькой картинки;150#size_b;Размер большой картинки (по большей стороне);500#
# Блок проверки наличия таблицы table1

$query="
	fraza text,				
	fwidth  INT(3) NOT NULL default 0,
	flines  INT(3) NOT NULL default 0,
	coord_x varchar(255) NULL,	
	coord_y varchar(255) NULL,	
	ungle  varchar(255) NULL default 0,	
	color1  varchar(255) NULL,	
	color2  varchar(255) NULL,	
	font_size INT(3) NOT NULL default 0,	
	font_style  varchar(255) NULL,	
	font_name  varchar(255) NULL,	
		
	data date NOT NULL default 0,
	email_from varchar(255) NULL,
	email_to varchar(255) NULL,
	card_title varchar(255) NULL,
	card_text text,
	ip  varchar(255) NULL,
	user_id INT(7) NOT NULL default 0,
	
	";
  
create_MySQL_table($query,1,0);

mysql_query("ALTER TABLE ".PREF."_$main ADD lines INT(3) NOT NULL default 0 AFTER fwidth");
# =======================================	
if (!empty($top)) { 
	
	$this_element="тектовое поле";	
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "content","fwidth","coord_x","coord_y","ungle","color1","color2","font_size","font_name","card" );
		$ed->input_komments=array("Название поля:", "Текст по умолчанию:","Ограничение по количеству символов в одной строке","Отступ слева","Отступ сверху","Угол поворота (+/-180)","Цвет текста (RGB)","Цвет тени (RGB)","Размер шрифта","Название шрифта","Шаблон открытки");
		$ed->input_komments2[7]="Если тени нет, то цвет не указывайте";
		$ed->input_komments2[6]="Например - FF0000 - красный цвет";
		$ed->input_types=array("text", "textarea2", "text2","text2","text2","text2","text2","text2","text2","select","textprint");
		
		//Вытаскиваем имена файлов со шрифтами из папки admin/files/fonts
		$dir = dir(SITE_ADMIN_DIR."/files/fonts");$i=0;
		$tps9="0,,--- Выберите шрифт";
		while($line = $dir->read()){
			if ( $line != '.' &&  $line != '..'){
				$tps9.="#".$line.",,".$line."";
			}
		}
		$ed->input_data_values[9]=$tps9;	
		
		$ed->input_default_values[10]="<img src='".getimg($main,$top,"b")."'>";
		$ed->input_default_values[5]="0";
		
		act_message($ed);
		
	}

	# ============	
	$rec=row_select("id, name, visible, content, coord_x, coord_y, font_size, color1, color2, ungle","","top=".$top);
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
	# 	$type, $name, $link, $icon, $i,		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"&id=".$row["id"]."".url_dop_param(1),"text","Название поля");
		tpr("title",$row["content"],"","text","Фраза по умолчанию");
		tpr("input",$row["coord_x"],$row["id"],"coord_x","Слева",5);
		tpr("input",$row["coord_y"],$row["id"],"coord_y","Сверху",5);
		tpr("input",$row["ungle"],$row["id"],"ungle","Угол",3);
		tpr("input",$row["color1"],$row["id"],"color1","Цвет шрифта",8);
		tpr("input",$row["color2"],$row["id"],"color2","Цвет тени",8);
		tpr("input",$row["font_size"],$row["id"],"font_size","Размер шрифта",4);
		tpr_fast_icon("del");
		tpr(1);
		$i++;
	}
	
}

# =======================================
else {
	
	$this_element="открытку";	
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "content","email_from","card_title","card_text","fraza","m","b","m,b");
		$ed->input_komments=array("Название открытки:", "Описание:","Емайл, который будет стоять в качестве обратного","Заголовок письма","Текст письма","Текст после отправки открытки","Маленькая картинка:","Большая картинка:","Необработанная картинка");
		$ed->input_types=array("textarea2", "textarea2", "text", "textarea2", "textarea2","textarea2","img","img","imgres");
		
		$ed->input_default_values[5]="Ваша открытка ушла адресату. Удачи!";
		
		act_message($ed);
		}

	# ============
	$rec=row_select("id, name, visible","","top=0");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
	# 	$type, $name, $link, $icon, $i,		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"top=".$row["id"],"folder","Название открытки");
		tpr_fast_icon("edit","Свойства открытки");
		tpr("link","Открыть в новом окне",SPAGE."?main=".$main."&id=".$row["id"],"center","Просмотр");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
	
}?>