<?
#build 22112004

# Возвращает права доступа админа к разделу
function main_access($page="") {
global $main; global $main_access_off;
if ($page=="") $page=$main;
	if (empty($main_access_off))
		return s_select($page,"admin_user_groups","id=".$_SESSION['user_group']);
	else 
		return "3";
}

# Формирует строку с доп параметрами для ссылок
function url_dop_param($out="") {
global $main;global $top;global $sub;global $main;global $top_table;global $top_id;global $delc;
$str="";

	if (isset($top) and ($top!="") and ($top!=0)) 
		$str.="&top=$top";
	if (isset($sub) and ($sub!="") and ($sub!=1)) 
		$str.="&sub=$sub";
	if (isset($edit) and $edit!="") 
		$str.="&edit=$edit";
	if (isset($top_table) and $top_table!="") 
		$str.="&top_table=$top_table";
	if (isset($top_id) and $top_id!="") 
		$str.="&top_id=$top_id";
		$str.=$delc;

	if ($out=="") echo $str;
	if ($out==1) return $str;
}

# Создает таблицу для раздела, если она не создана
function create_MySQL_table($query_dop,$name_cont=0,$maxris=0,$table="") {
global $main;	
if ($table=="") $table=$main;

$res=row_select("id","$table","","","1");
if (mysql_errno()==1146){
	
	$query="
		CREATE TABLE IF NOT EXISTS ".PREF."_$table (
		id int(7) NOT NULL default 0,";
		
	if ($name_cont!=0) 
	$query.="
	  name text,
	  content text,";	
		
	if ($maxris!=0) 
	$query.="
		maxris int(4) NOT NULL default 0,";	
		
	$query.=$query_dop;
	
	$query.="
		visible int(2) NOT NULL default 1,
		top int(11) NOT NULL default 0,
		isfolder int(2) NOT NULL default 0,
		ord int(6) NOT NULL default 0,
		admin varchar(255) NOT NULL default 0,";
		
	$query.="	
		PRIMARY KEY (id));";
	
	$res=mysql_query($query);

	DbgPrint($query,0,"create_MySQL_table");
}
else {	
	# маленький патчик на все разделы, поле, что бы показать, раздел это или нет
	mysql_query("ALTER TABLE ".PREF."_".$main." ADD isfolder int(2) NOT NULL default 0 AFTER visible");
	}
}
	

function define_edit_param() {
	global $main;global $top;global $id;global $sub;global $did;global $drop;global $main;global $ord;global $input;global $select;global $check;global $alert;global $add;global $id;global $save;
	global $list_change;global $change_stat;global $pager;global $add_new;

	if (!isset($top) || $top=="") {$top=0;}
	if (!isset($sub) || $sub=="") {$sub=1;}
	
	# Удаляем если did
	if (isset($did)) {
		did("$main");
		$change_stat[0]="del";
		$change_stat[1]="";
	}
	
	# Удаляем таблицу если drop
	if (isset($drop)) {
		$dtable=s_select("page","admin_tree","id=$drop");
		mysql_query("DROP TABLE ".PREF."_".$dtable);
		$change_stat[0]="drop";
		$change_stat[1]="$dtable";
		s_delete("admin_site","page='".s_select("page","admin_tree","id=$drop")."'");
	}
	
	# Если изменения в списке, то заносим в базу
	if (isset($list_change)) { 
		check_info();
		$change_stat[0]="list_edit";
		$change_stat[1]="$id";
	}
	
	# Записываем доп. свойства раздела в базу, если обновили
	global $param;global $param_id;
	if (isset($param)) {
		for ($i=0;$i<count($param);$i++){
			s_update("content=\"$param[$i]\"","admin_site","id=$param_id[$i]");
	}}
	
	if (((!isset($add)) && (!isset($id))) || (isset($save) && !isset($add_new))) table_print_zagl();
	
	if (isset($pager) and (empty($add))) pager(0);
}

# Убиваем все что нужно убить
function did_one($did,$table="") {
global $main;

if ($table!=""){
		
	# Удаляем запись
	s_delete("$table","id=$did");

	# Удаляем файлы
	$res=row_select("name","files","top_table=\"".$table."\" and top_id=\"".$did."\"");
	while ($r=$res->ga()){
		if(file_exists("files/".$r["name"])) unlink("files/".$r["name"]);}
		s_delete("files","top_table=\"".$table."\" and top_id=\"".$did."\"");
		
	# Удаляем картинки
	$res=row_select("name","images","top_table=\"".$table."\" and top_id=\"".$did."\"");
	while ($r=$res->ga()){
		if(file_exists("img/kat/images".$r["id"]."m")) unlink("img/kat/images".$r["id"].m);
		if(file_exists("img/kat/images".$r["id"]."b")) unlink("img/kat/images".$r["id"].b);}
		s_delete("images","top_table=\"".$table."\" and top_id=\"".$did."\"");
		
	# Удаляем ссылки
	s_delete("links","top_table=\"".$table."\" and top_id=$did");

}}

function did($img="") {
	global $main;global $top;global $did;

	mysql_query("lock tables ".PREF."_$main,".PREF."_files,".PREF."_images,".PREF."_links write");
	
	# Удаление таблицы из базы, если удаляем раздел
	if ($main=="admin_tree") {
		$table=s_select("page","admin_tree","id=$did");
		if ($table!="admin_tree" AND $table!=""){
			# Удаляем все что связано с этим разделом
			$res=row_select("id",$table);
			while ($r=$res->ga()){
				did_one($r["id"],$table);
			}
			# Удаляем таблицу
			mysql_query("DROP TABLE ".PREF."_".$table);
			# Если это был форум то удаляем еще таблицы
			if (s_select("shablon","admin_tree","id=$did")=="forum") {
				mysql_query("DROP TABLE ".PREF."_".$table."_smiles");
				mysql_query("DROP TABLE ".PREF."_".$table."_words");
				mysql_query("DROP TABLE ".PREF."_".$table."_bun");			
			}
		}
		else 
			echo "Ошибка!! Попытка удаления структуры сайта!!";
	}
	
	# Удаление файла, если мы удаляем из таблицы файлов
	if ($main=="files") {
		$res=row_select("content","files","id=$did");
		$r=$res->ga();
		del_file($r["content"]);
	}
	
	# Удаляем рисунки
	if ($img!="") del_ris($img.$did);
	
	# Удаляем запись	
	did_one($did,$main);
	
	# Удаляем детей записи, их файлы и картинки
		$res=row_select("id",$main,"top=$did");
		while ($r=$res->ga()){
			$res2=row_select("id",$main,"top=".$r["id"]."");
			while ($r2=$res2->ga()){
				did_one($r2["id"],$table);
			}
			did_one($r["id"],$main);
		}
		
	mysql_query("unlock tables");	
}

# Обновляем инфомацию на странице, где выводится список разделов
function check_info() {
global $main;global $top;global $ord;global $mid;global $check;global $main;global $search;global $alert;global $column;global $input;global $select;
global $top_table;global $top_id;

	$wtop="";
	if(isset($top) and $top!="") $wtop.="and top=$top "; else $wtop="and top=0 ";
	if(isset($top_table)) $wtop.="and top_table=\"$top_table\" and top_id=$top_id "; 

	for($i=1;$i<=count($mid);$i++) {
		if (count($check)!=0 && !isset($check[$i])) $check[$i]=0;
		if (count($search)!=0 && !isset($search[$i])) $search[$i]=0;
		if (count($alert)!=0 && !isset($alert[$i])) $alert[$i]=0;
		
		if (count($ord)!=0)		s_update("ord=".$ord[$i],"","id=".$mid[$i]." $wtop");
		if (count($check)!=0)	s_update("visible=".$check[$i],"","id=".$mid[$i]." $wtop");
		if (count($search)!=0)	s_update("search=".$search[$i],"","id=".$mid[$i]." $wtop");
		if (count($alert)!=0)	s_update("alert=".$alert[$i],"","id=".$mid[$i]." $wtop");
		if (count($column)!=0)			
			foreach ($column[$i] as $key => $col){
				s_update($col."=\"".del_quotes($input[$i]["$key"])."\"","","id=".$mid[$i]);
			}
		if (count($select)!=0)	s_update($column[$i]."=\"$select[$page]\"","","id=".$mid[$i]);
	}
}
# Обновляем единичную инфомацию на странице, типа "текст в начале раздела"
function insert_dop_info($col="content",$name,$table,$i) {
global $main;global $top;global $ord;global $text;global $top_table;global $top_id;

	# Вытаскиваем текст из базы
	$id=100000+$i;
	if (s_select("id",$table,"id=".$id)==""){
		s_insert($table,"id,name,top","$id,'$name','333'");
		}
	
	# Записываем его в базу, если обновили
	if (isset($text[$i])) {
		s_update("$col=\"$text[$i]\"",$table,"id=$id");
	}
	# Засовываем форму на страницу
	?>
	<tr class="t-main1">
		<td valign=top colspan=20><div class=comment style="width:65%"><b><?=s_select("name",$table,"id=".$id,"","","","1")?>:</b></div><textarea name=text[<?=$i?>] rows=4 style="width:70%"><?=s_select("content",$table,"id=".$id,"","","","1")?></textarea><input type=submit class=button value='Сохранить текст'></td>
	</tr><?
}

# Помогает формировать массив переменных для вывода страницы редактрования
function act_message($ed,$type=1,$table="") {
global $main;global $id;global $did;global $add;global $main;global $top;

		if ($type==1) {
		$ed->input_data_types=array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);}
		if ($table==""){
		$ed->table=PREF."_$main";}
		else {
		$ed->table=PREF."_$table";}
		$ed->kod="$main";
		$ed->top_need=1;
		$ed->top=$top;
		
	if (isset($add)) {	
		$ed->id="add";
		$ed->uspeh="Добавление прошло успешно";
		$ed->emerg="Невозможно добавить!";
		$ed->zagl="Идет добавление:";
	}

	if (isset($id) || isset($edit)) {			
		$ed->id=$id;
		$ed->uspeh="Наименование отредактировано";
		$ed->emerg="Невозможно редактировать наименование";
		$ed->zagl="Идет редактирование:";
	}
		$ed->start();

}

# Выводит самый главный заголовок страницы и кнопку назад и вспомогательную менюшку для каждого раздела
function adm_print_top($title, $main) {
global $delc; global $main;global $edit;global $main;global $id;global $top_table;global $top_id;global $top;global $sub;global $preview;global $noadd;
 if (empty($noadd) && !isset($preview)) {  ?>
 <div class="h1menu">
 <? // ссылка на мета-теги, если это раздел сайта
 if (s_select("global_id","admin_tree","page='$main'")==0) {
 	if (!isset($top_id) && !empty($id)) $ttop=$id; elseif (!isset($top_id) && !empty($top))  $ttop=$top; elseif (isset($top_id))  $ttop=$top_id; else $ttop=0;
 	?>
 	<a href=<?=PAGE?>?main=meta&top_table=<?if (!isset($top_table)) echo $main; else echo $top_table;?>&top_id=<?=$ttop?> class='small <?if (s_select("id","meta","top_table='".$top_table."' AND top_id='".$ttop."'")) echo "red' title='Ключевые слова заданы";?>'>Ключевые слова</a> | 
 	<?
	}
  // ссылка на свойства раздела ?>
 <a href=<?=PAGE?>?main=admin_tree&id=<?=s_select("id","admin_tree","page='$main'")?>&top_table=<?if (!isset($top_table)) echo $main; else echo $top_table;?> class=small>Свойства раздела</a>
 </div>
 <h1>
  <img src="<? echo SITE_ADMIN_DIR?>/img/tri2.gif" width="7" height="7" hspace="8" align="absmiddle"><?
 
	if ($top>0 && empty($top_table)) {
		@$res=mysql_query("select name,top from ".PREF."_$main where id=\"$top\"");
		@$r=mysql_fetch_row($res);
		echo $r[0]."</h1>";
	}
	elseif (isset($top_table)) {echo $edit;
		if (!empty($top_id))
			$res=s_select("name,top",$top_table,"id='".$top_id."'");
		else
			$res=s_select("name,top","admin_tree","page='".$top_table."'");
		echo $res,"&nbsp|&nbsp;";
		if ($main=="links") echo  "Ссылки";
		elseif ($main=="files") echo  "Файлы";
		elseif ($main=="images") echo  "Изображения";
		elseif ($main=="admin_tree") echo  "Свойства раздела";
		else echo s_select("name","admin_tree","page='".$main."'");
		echo "</h1>";
	}
	else echo "$title</h1>";
 ?>
 <table width="100%" border="0" cellspacing="0" cellpadding="10">
<tr>
<td vAlign=top>
<SPAN class=adminText>
 
 <?}}

# Выводим конец страницы
function table_dop_property() {  
global $main;global $main;global $top;global $delc;global $sub;global $top_table;global $top_id;global $this_element;global $nobutton;global $rand;global $pg;global $noparam;
	
	# Выводим доп. свойства раздела, если они есть
	if (empty($noparam)){
		$res=row_select("","admin_site","page=\"$main\"","id");
		if ($top==0 and ($res->nr())!=0){
			echo "<div class=table3><form action=".PAGE."?main=$main method=post><h3><img src=".SITE_ADMIN_DIR."/img/tri2.gif width=7 height=7 hspace=0 align=absmiddle>&nbsp;&nbsp;Свойства раздела:</h3><table width=50% class=table3>";
			$i=0;
			while ($r=$res->ga()){
				echo "<tr><td align=left>".$r["name"].":</td>";
				echo "<td><input name=param[$i] value=".$r["content"]."><input type=hidden name=param_id[$i] value=".$r["id"]." ></td></tr>";
				$i++;}
			echo "</table>";
			if ((main_access()==3))
				echo "<input type=submit class=button value='Изменить'>";
			echo "</form></div>";
		}	
	}
}


# Выводим начало страницы, если нет содержания
function table_if_empty ($rec) {
global $main;global $main;global $top;global $delc;global $sub;global $rand;global $dbg_listing;
	if (($rec->nr())<1) {
		echo "</td></tr></table></form><hr><b>Записи отсутствуют!<br><br>";
		
		table_dop_property();
		require("".SITE_ADMIN_DIR."/_adm_bot.php");
		exit;
}}

# Выводим начало страницы, и возможные действия
function table_print_zagl() {  
global $delc;global $main;global $id;global $top;global $sub;global $edit;global $main;global $top_id;global $top_table;global $this_element;global $nobutton;
?>
	<form action="<?=PAGE?>?main=<?=$main?>" method=post><?
	if (empty($nobutton)) {
		table_print_button();
	}
	?><table class="table1" width=100% cellspacing=1 cellpadding=2><?
	}

# Выводим конец страницы
function table_print_end() {  
global $main;global $main;global $top;global $delc;global $sub;global $top_table;global $top_id;global $this_element;global $nobutton;global $rand;global $pg;global $abc_list;global $razdel_comment;

	echo "</table>";	
	echo "<input type=hidden name=top value=$top><input type=hidden name=sub value='$sub'><input type=hidden name=rand value='$rand'>";
	if(isset($top_table)) echo"<input type=hidden name=top_table value='$top_table'>";
	if(isset($top_id)) echo"<input type=hidden name=top_id value='$top_id'>";
	if(!empty($pg)) echo"<input type=hidden name=pg value='$pg'>";
	if(!empty($abc_list)) echo"<input type=hidden name=abc_list value='$abc_list'>";
	if(empty($nobutton)) table_print_button();	
	echo "</form>";
	echo @$razdel_comment; 
	table_dop_property();

}
 
# Выводим полоску с кнопками (наверх,добавить,внести изменения)
function table_print_button() {
global $delc;global $main;global $top;global $sub;global $edit;global $main;global $top_id;global $top_table;global $this_element;global $nobutton;global $noadd;global $topbutton;global $abc_list;

	if ($top>0) {
		@$res=mysql_query("select name,top from ".PREF."_$main where id=\"$top\"");
		@$r=mysql_fetch_row($res);
		if (!isset($id)) $q="<input type=button class='button back' value=Наверх onClick=\"location.href='".PAGE."?main=$main$delc&top=$r[1]&sub=$sub".((!empty($abc_list))?"&abc_list=".$abc_list:"")."'\">";
	}
	if (isset($topbutton)) {
		$q="<input type=button class='button back' value=Наверх onClick=\"location.href='".PAGE."?main=$main$delc&$topbutton'\">";
	}
	if ($top_table!="") {
		if (!isset($id)) $q="<input type=button class='button back' title='".@$r["name"]."' value=Наверх onClick=\"location.href='".PAGE."?main=".$top_table.$delc."'\">";
	}?>	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td align=left><?=@$q?></td>
	<td align=right class="table-button">
		<?	if ((main_access()==2 || main_access()==3) && empty($noadd)){?>
				<input type=button class=button value='Добавить <?if (isset($this_element)) echo $this_element; else echo "позицию";?>' onClick="location.href='<?=PAGE?>?main=<?=$main?><?url_dop_param();?>&add=1'">
		<?}
			if ((main_access()==3)){?>
				<input type=hidden name='list_change' value='1'>
				<input type=submit class=button value='Внести изменения'>
		<?}?>		
	</td>
	</tr></table><?
}

# Выводим хидер таблицы
function tpr_head($title_this="",$title_standart="") {
global $i;

$str="";
if ($i==0) {
	$str.="<th>";
	if ($title_this=="") $str.=$title_standart; else $str.=$title_this;
	$str.="</th>";
	echo $str;
}}

# Выводим простые иконки в таблице
function tpr_fast_icon($icon,$title="") {
global $delc;global $i;global $main;global $main;global $row;global $row2;global $row3;
	
switch ($icon) {

case "ord":
	tpr("ord","",$row["id"]);break;	
case "check":
	tpr("check",$row["visible"],$row["id"]);break;
case "search":
	tpr("search",$row["search"],$row["id"],"",$title);break;
case "alert":
	tpr("alert",$row["alert"],$row["id"],"",$title);break;
case "file":	
	$res=row_select("id","files","top_table=\"".$main."\" and top_id=\"".$row["id"]."\"");
	tpr("icon","file","top_table=$main&top_id=$row[0]",$res->nr(),$title);break;
case "img":	
	$res=row_select("id","images","top_table=\"".$main."\" and top_id=\"".$row["id"]."\"");
	tpr("icon","img","top_table=$main&top_id=".$row["id"]."",$res->nr(),$title);break;
case "link":	
	$res=row_select("id","links","top_table=\"".$main."\" and top_id=\"".$row["id"]."\"");
	tpr("icon","link","top_table=$main&top_id=".$row["id"]."",$res->nr(),$title);break;
case "desc":	
	$res=row_select("id","all_desc","top_table=\"".$main."\" and top_id=\"".$row["id"]."\"");
	tpr("icon","desc","top_table=$main&top_id=".$row["id"]."",$res->nr(),$title);break;
case "edit":
	tpr("icon","edit","id=".$row["id"]."".url_dop_param(1),"",$title);break;
case "add":
	tpr("icon","add","add=1&top=".$row["id"]."".url_dop_param(1),"",$title);break;
case "drop":	
	if ($title==2) $id=$row2["id"];
	else if ($title==3) $id=$row3["id"];
	else $id=$row["id"];
	tpr("icon","drop",url_dop_param(1)."&drop=".$id."","",$title);break;
case "copy":	
	tpr("icon","copy",url_dop_param(1)."&cid=".$row["id"]."","",$title);break;
case "del":	
	tpr("icon","del",url_dop_param(1)."&did=".$row["id"]."","",$title);break;
}}


# Выводим ряды в таблице
function tpr($type, $name="", $link="", $icon="", $title="", $class="",$dop="") {
global $delc;global $main;global $id;global $i;global $row;
$udal="if (window.confirm('Подтверждаете удаление раздела?')) return true; else return false;";
$clear="if (window.confirm('Подтверждаете уделение всех данных из раздела?')) return true; else return false;";

switch ($type) {
	
case "0":
	?><tr class="t-main1 <?=$name?>"><?
	break;	
	
case "1":
	?></tr><?
	break;

case "icon";
	switch ($name) {
	case "del":
		if ($i==0) tpr_head($title,"Del"); else {
		$admin=@s_select("admin",$main,"id=".$row["id"]);
		if (main_access()==3 || (main_access()==2 && $_SESSION["user_id"]==$admin)){
			echo "<TD align=center class=comment><a href='".PAGE."?main=$main&$link$delc' onClick=\"$udal\"><img src='".SITE_ADMIN_DIR."/img/ico-del.gif' alt=\"Удалить\" border=0 height=25></a></td>\n";}
		else
			echo "<TD align=center class=comment><img src='".SITE_ADMIN_DIR."/img/0.gif' border=0 height=1></td>\n";
		}break;
	case "drop":
		if ($i==0) tpr_head($title,"Очистить"); else {
		$admin=s_select("admin",$main,"id=".$row["id"]);
		if (main_access()==3 || (main_access()==2 && $_SESSION["user_id"]==$admin)){
			echo "<TD align=center class='small'><a href='".PAGE."?main=$main&$link$delc' onClick=\"$clear\" title='Удалить все данные из раздела'>Очистить</a></td>\n";}
		else
			echo "<TD align=center class=comment><img src='".SITE_ADMIN_DIR."/img/0.gif' border=0 height=1></td>\n";
		}break;
	case "edit":
		if ($i==0) tpr_head($title,"Изменить"); else {
		echo "<TD align=center><a href='".PAGE."?main=$main&$link$delc'><img src='".SITE_ADMIN_DIR."/img/ico-change.gif' alt=\"Изменить\" border=0 height=25></a></td>\n";}break;
	case "copy":
		if ($i==0) tpr_head($title,"Копи"); else {
		echo "<TD align=center><a href='".PAGE."?main=$main&$link$delc'><img src='".SITE_ADMIN_DIR."/img/ico-copy.gif' alt=\"Копировать в буфер обмена\" border=0 height=25></a></td>\n";}break;
	case "add":
		if ($i==0) tpr_head($title,"Добавить"); else {
		echo "<TD align=center><a href='".PAGE."?main=$main&$link$delc'><img src='".SITE_ADMIN_DIR."/img/ico-add.gif' alt=\"Добавить\" border=0 height=25></a></td>\n";}break;
	case "file":
		if ($i==0) tpr_head($title,"Файлы"); else {
		echo"<TD align=center class=comment><a href='".PAGE."?main=files&$link$delc'><img src='".SITE_ADMIN_DIR."/img/ico-file.gif' alt=\"";
		if ($icon!="") echo "Файлов: ".$icon.""; else echo "Прикрепить файлы";
		echo "\" border=0 height=25 align=absbottom>";
		echo "(".$icon.")"; echo "</a></td>\n";}break;
	case "img":
		if ($i==0) tpr_head($title,"Фото"); else {
		echo"<TD align=center class=comment><a href='".PAGE."?main=images&$link$delc'><img src='".SITE_ADMIN_DIR."/img/ico-foto.gif' alt=\"";
		if ($icon!="") echo "Изображений: ".$icon.""; else echo "Прикрепить изображения";
		echo "\" border=0 height=25 align=absbottom>";
		echo "(".$icon.")"; echo "</a></td>\n";}break;
	case "link":
		if ($i==0) tpr_head($title,"Ссылки"); else {
		echo"<TD align=center class=comment><a href='".PAGE."?main=links&$link$delc'><img src='".SITE_ADMIN_DIR."/img/ico-link.gif' alt=\"";
		if ($icon!="") echo "Ссылок: ".$icon.""; else echo "Добавить ссылку";
		echo "\" border=0 height=25 align=absbottom>";
		echo "(".$icon.")"; echo "</a></td>\n";}break;
	case "desc":
		if ($i==0) tpr_head($title,"Доп.текст"); else {
		echo"<TD align=center class=comment><a href='".PAGE."?main=all_desc&$link$delc'><img src='".SITE_ADMIN_DIR."/img/ico-change.gif' alt=\"";
		echo "Перейти к редактированию";
		echo "\" border=0 height=25 align=absbottom>";
		echo "(".$icon.")"; 
		echo "</a></td>\n";}	break;
}break; 

case "ord":
	if ($i==0) tpr_head($title,"Порядок"); else {
	?><TD align=center class=comment width="5%"><?
	echo "<input type=hidden name=mid[$i] value=$link><input name=ord[$i] size=3 value=".$i."0></td>\n";}
	break;

case "input":
	if ($i==0) tpr_head($title,"Описание"); else {
	?><TD align=center><?
	if ($class=="") $class=30;
	echo "<input type=hidden name=mid[$i] value=$link><input type=hidden name=column[$i][\"$icon\"] value=$icon>";
	if ($dop=="")echo "<input name=input[$i][\"$icon\"]  value=\"$name\" size=$class >";
	if ($dop!="") echo "<textarea name=input[$i][\"$icon\"] cols=$class rows=3>$name</textarea>";
	echo "</td>\n";}
	break;

case "select":
	if ($i==0) tpr_head($title,"Тип"); else {
	?><TD align=center><?
	echo "<input type=hidden name=mid[$i] value=$link><input type=hidden name=column[$i][\"$icon\"] value=$icon>\n";
		$parts=split("#",$class);  // разделяем сначала по ; exp:$parts[]=(0#Да,1#Нет,2#Не знаю)
		$keys=array();
		$values=array();
		for ($j=0;$j<count($parts);$j++)	{
			$dopparts=split(",,", $parts[$j]);		 // разбиваем еще на кусочки $dopparts[]=(0,Да;1,Нет;2,Не знаю)
			$keys[count($keys)]=$dopparts[0];		 // массив $keys[]=(0,1,2)
			$values[count($values)]=$dopparts[1];	// массив $values[]=(Да,Нет,Не знаю)
			}
		echo "<select name=input[$i][\"$icon\"]>";
		for ($j=0;$j<count($keys);$j++) {
			if ($values[$j]!=""){
				echo "<option value=".$keys[$j];
				if ($name==$keys[$j]) echo " selected";
				echo ">".delslashes(addquotes($values[$j]))."</option>\n";
		}}
		echo "</select></td>\n";
		}
	break;

case "check":
	if ($i==0) tpr_head($title,"Вывод"); else {
	?><TD align=center class=comment width="5%"><?
	if ($i==1)  echo "<input type=hidden name='check[0]' value=1>";
	if ($name==1) $ch="checked"; else $ch="";
	echo "<input type='hidden' name='mid[$i]' value='$link'><input type='checkbox' name='check[$i]' value='$name' $ch onClick=\"javascript:checkbox(this);\"></td>\n";}
	break;

case "alert":
	if ($i==0) tpr_head($title,"Важно"); else {
	?><TD align=center class=comment width="5%" nowrap><?
	if ($i==1)  echo "<input type=hidden name='alert[0]' value=1>";
	if ($name==1) $ch="checked"; else $ch="";
	echo "<input type='hidden' name='mid[$i]' value='$link'><input type='checkbox' name='alert[$i]' value='$name' $ch onClick=\"javascript:checkbox(this);\"></td>\n";}
	break;

case "search":
	if ($i==0) tpr_head($title,"Поиск"); else {
	?><TD align=center class=comment width="5%"><?
	if ($i==1)  echo "<input type=hidden name='search[0]' value=1>";
	if ($name==1) $ch="checked"; else $ch="";
	echo "<input type='hidden' name='mid[$i]' value='$link'><input type=checkbox name='search[$i]' value='$name' $ch onClick=\"javascript:checkbox(this);\"></td>\n";}
	break;

case "title":
	if ($i==0) tpr_head($title,"Название"); else {
	?><TD valign=top class='<?if ($icon!="") echo "ico-".$icon." "; else echo "ico-no "; if ($class!="") echo $class;?>' <?if ($dop!="") echo "$dop";?>><?
	if ($icon!="" && $link!="" && isset($row["id"])) {
		if ($row["id"]) 
		$admin=explode("#",s_select("admin",$main,"id=".$row["id"]));
		if (!isset($admin[1])) $admin[0]="0";
		if (!isset($admin[1])) $admin[1]=""; else $admin[1]=remakedata($admin[1]);
		if (!isset($admin[2])) $admin[2]="";
		$atitle=s_select("fio","admin_users","id=".$admin[0]);
		if ($admin[0]=="0") $atitle="Администратор";
		if ($admin[0]=="1") $atitle="Посетитель сайта";
		if ($atitle=="") $atitle="Неизвестно";
		$atitle="title=\"Изменено: ".$atitle." | ".$admin[1]." | $admin[2]\"";} 	else $atitle="";
	if ($link!="") {$a1="<a href='".PAGE."?main=$main&$link$delc' $atitle>";$a2="</a>";}
	else {$a1="";$a2="";}
	if (empty($name) AND $link!="") $name="--------";
	echo $a1."$name$a2</td>\n";}
	break;

case "data":
	if ($i==0) tpr_head($title,"Дата"); else {
	?><TD align=center class=comment><?
	if ($link=="")echo remakedata($name); 
	if ($link=="1")echo $name; 
	echo "</td>\n";}
	break;

case "email":
	if ($i==0) tpr_head($title,"Е-майл"); else {
	?><TD align=left><?
	if ($link!="")	echo "<a href='mailto:$link' title='Написать письмо'>";
	echo "$name";
	if ($id!="") echo "</a>";
	echo "</td>\n";}
	break;

case "link":
	if ($i==0) tpr_head($title,"Ссылка"); else {
	?><TD align=<?if ($icon!="") echo $icon; else echo "left"; if ($link=="" && $class!="" ) echo " class='".$class."'"?>><?
	if ($link!="")	{
		echo "<a href='$link'";
		if ($dop=="") echo " title='Открыть в новом окне' target=_blank";
		else echo " title='Перейти'";
		if ($class!="") echo " class='$class'";
		echo ">";
	}
	echo "$name";
	if ($link!="") echo "</a>";
	echo "</td>\n";}
	break;

case "file":
	if ($i==0) tpr_head($title,"Файл"); else {
	?><TD align=<?if ($icon!="") echo $icon; else echo "left";?>><?
	if ($link!="")	echo "<a href='$link' title='Открыть файл' target=_blank>";
	if ($link=="")	echo "<a href='files/".test_file($name)."' title='Открыть файл' target=_blank>";
	echo "<img src=\"".test_file_ext($name)."\"  alt=\"".test_file_ext($name,"alt")."\" width=16 align=absmiddle border=0>&nbsp;";
	echo "files/".test_file($name);
	if ($id!="") echo "</a>";
	echo "</td>\n";}
	break;

case "img":
	$size=round(filesize(getimg($main,$name,$link))/1000);
	$img=getimg($main,$name,$link);
	if ($i==0) tpr_head($title,"Фото"); else {
		if ($icon=="view"){
			?><TD align=center><?
			if ($img!="img/0.gif"){
				if ($link!="")	echo "<a href='".popupimg($main,$name,$link,1)."' title='Открыть в новом окне'>";
				echo "<img src='$img' width=50 border=0>";
				if ($id!="") echo "</a>";
			}
			else
				echo "--------";
			echo "</td>\n";
		}
		else {
			?><TD align=center><?
			if ($img!="img/0.gif"){
				if ($link!="")	echo "<a href='".popupimg($main,$name,$link,1)."' title='Открыть в новом окне'>";
				echo "<nobr>Смотреть <span class=small>(".$size."кБ)</span>";
				if ($id!="") echo "</a>";
			}
			else
				echo "--------";
			echo "</td>\n";
		}
	}
	break;

case "flash":
	if ($i==0) tpr_head($title,"Фото"); else {
	?><TD align=center><?
	if ($ris=test_ris($main.$name.$link)){
		if ($link!="")	echo "<a href='img/kat/".$ris."' target=_blank title='Открыть в новом окне'>";
		$size=round(filesize("img/kat/".$ris)/1000);
		echo "<nobr>Смотреть <span class=small>(".$size."кБ)</span>";
		if ($id!="") echo "</a>";
	}
	else
		echo "--------";
	echo "</td>\n";}
	break;
	
}}

function adm_stop($stroka, $ind=0) {
global $main;global $top;global $sub;global $id;global $rand;global $dbg_listing;
	
	if ($ind>0) echo "</table>";
	echo $stroka; 
	require("".SITE_ADMIN_DIR."/_adm_bot.php");
}

//================================================================================================

require("".SITE_ADMIN_DIR."/class/table_edit.php");
require("".SITE_ADMIN_DIR."/class/edit.php");

?>