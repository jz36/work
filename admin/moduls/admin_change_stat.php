<?
#Статистика работы с сайтом#3
if (isset($erase)) mysql_query("DROP TABLE ".PREF."_$main");
# Блок проверки наличия таблицы table1
$query="
	event_name varchar(255) default NULL,";  

create_MySQL_table($query,1,0);
mysql_query("ALTER TABLE ".PREF."_$main CHANGE id id INT(7) AUTO_INCREMENT");
# =======================================

	$nobutton=1;
	$pager=1;
	define_edit_param();

if (!isset($id_user)) { $id_user=""; $where="";}
?>
<form action="<?=PAGE?>?main=<?=$main?>" method=post>
Показать для: <select name="id_user">
<option value="<?if ($id_user=="") echo " selected";?>">Все
<?
	$rec=row_select("id,fio","admin_users","","fio");
	while ($row=$rec->ga()) {
		echo "<option value=".$row["id"];
		if ($row["id"]==$id_user) echo " selected";
		echo ">".$row["fio"];	
	}
?>
</select><input type=submit value="Показать"><input type=button value="Очистить статистику" onClick="window.location.href='<?=PAGE?>?main=admin_change_stat&erase=1&rand=17032'"></form>

<?
		if (isset($id_user))	$where="name=".$id_user;
		if ($id_user=="")	$where="";
		$rec=row_select_pages("","",$where,"id DESC");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
		$admin=explode("#",$row["admin"]);
		$content=explode("#",$row["content"]); if (!isset($content[1])) $content[1]="";
		$content[0]=str_replace(PREF."_","", $content[0]);
		switch ($row["event_name"]) {
			case "add":			$event_name="Добавление:";break;
			case "edit": 		$event_name="Изменение:";break;
			case "list_edit": $event_name="Изменение в списке:";break;
			case "del": 		$event_name="Удаление:";break;
			case "drop": 		$event_name="Удаление данных из раздела:";break;
		}

# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr("title",s_select("fio","admin_users","id=".$row["name"]),"","text","Пользователь");
		tpr("title",$event_name,"","","Действие");
		tpr("title",nav_line($content[0],$content[1]),"","","Изменение");
		tpr("data",$admin[0],"","","");
		tpr("data",$admin[1],1,"","Время");
		tpr(1);
		$i++;}
			
?>