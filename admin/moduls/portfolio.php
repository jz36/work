<?
#Наши работы (веб)#0
#param#size_m;Ширина маленькой картинки;150#size_b;Размер большой картинки (по большей стороне);500#
# Блок проверки наличия таблицы table1
$query="
  link varchar(255) NULL,
  year varchar(255) NULL,
  workers text,";

create_MySQL_table($query,1,0);

# =======================================
	$this_element="готовый проект";

	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","link","year","workers","m","b","m,b");
		$ed->input_komments=array("Название:","Описание:","Ссылка:","Год:","Разработчики:","Маленькая картинка:","Большая картинка:","Необработанная картинка");
		$ed->input_types=array("text","textarea","text","text","textarea2","img","img","imgres");
		act_message($ed);}

#======================================	
	insert_dop_info("content","Текст в начале раздела",$main,0);
	
	$rec=row_select("id,name,link,visible");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"],"text","Название");
		tpr("link",$row["link"],$row["link"],"","Ссылка");
		tpr("img",$row["id"],"m","","Маленькая");
		tpr("img",$row["id"],"b","","Большая");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
?>