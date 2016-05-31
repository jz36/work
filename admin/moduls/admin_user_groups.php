<?
#Группы пользователей#3
# Блок проверки наличия таблицы table1
$query="
	default_access int(2) NOT NULL default 0,
	";

create_MySQL_table($query,1,0);
mysql_query("ALTER TABLE ".PREF."_".$main." ADD for_part int(1) NOT NULL default 0 AFTER default_access");
# =======================================
	$this_element="группу";	
	if($top!=0) $noadd=1;
	define_edit_param();

	if (isset($add) || isset($edit) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","default_access","for_part");
		$ed->input_komments=array("Название:","Описание:","Права по умолчанию для новых разделов","Выбор назначения группы");
		$ed->input_types=array("text","textarea","select","select");
		
		$tps2="0,,0--Доступ закрыт#1,,1--Только просмотр#2,,2--Просмотр и добавление,#3,,3--Все разрешено";
		$tps3="0,,0--Для системы администрирования#1,,1--Для просмотра сайта";
		$ed->input_data_values=array("","",$tps2,$tps3);

		act_message($ed);}

#======================================	
if ($top==0) {
	
		$rec=row_select("id,name,visible","","name!='root'","name");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("check","Отключить");
		tpr("title",$row["name"],"top=".$row["id"]."","folder","Группа доступа");
		tpr_fast_icon("edit","Описание");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
}	

#======================================
	
	# Составляем список возможных значений для доступа
	$for=s_select("for_part","","id=$top");
	if ($for==0) 
		$access="0,,0--Доступ закрыт#1,,1--Только просмотр#2,,2--Просмотр и добавление,#3,,3--Все разрешено";
	elseif ($for==1)
		$access="0,,0--Доступ закрыт#1,,1--Только просмотр";
/*	
	# Менюшка c выбором одного значения для всех?>
	<form method='get' action='<?=SPAGE?>?ddd' name=allaccessvalue>
	<input type=hidden name="main" value='<?=$main?>'>
	<input type=hidden name="top" value='<?=$top?>'>
	<input type=hidden name="rand" value='<?=RAND()?>'>
	<input type=hidden name="delcookie" value='<?=$delcookie?>'>
	<div class=comment>Поставить для всех разделов значение:
	<select name=allaccess>
		<option value="--">Выберите значение</option>
		<option value="0">0--Доступ закрыт</option>
		<option value="1">1--Только просмотр</option>
		<option value="2">2--Просмотр и добавление</option>
		<option value="3">3--Все разрешено</option>
	</select>
	<input type=submit class=button value='Применить'>
	</form></div>
	
	<?
	echo $allaccess;
*/
if ($top!=0) {

	$rec=row_select("id,name,page,global_id","admin_tree","(menu_top=\"0\" OR menu_top='')","global_id,ord,menu_top");
		table_if_empty($rec);
		$i=0;$n=0;
		while (($rec->nr())>=$n) {
		if ($i!=1) $row=$rec->ga();

# 	$type, $name, $link, $icon, $i,

		if ($i!=0){
		
		$r=s_select($row["page"],$main,"id=$top");
		}
		
		if ($row[3]==0)	tpr(0,"menu-color0");
		if ($row[3]==1)	tpr(0,"menu-color1");
		if ($row[3]==2)	tpr(0,"menu-color2");
		if ($row[3]==3)	tpr(0,"menu-color3");
		tpr("title","","","","--------");
		$class=""; if ($row[3]==0) $class="bold";
		tpr("title",$row["name"],"","text","Название раздела",$class);
		tpr("select",$r,$top,$row["page"],"Права доступа",$access);
		tpr(1);
		$i++;$n++;
		
			$rec2=row_select("id,name,page,global_id","admin_tree","menu_top=\"".$row["id"]."\"","global_id,ord,menu_top");
			while ($row2=$rec2->ga()) {
				if ($i!=1) {
	
				$r=s_select($row2["page"],$main,"id=$top");			
	
				tpr(0,"menu-color1");
				tpr("title","","","");
				tpr("title",$row2["name"],"","folder","Название раздела","t-main-sub");
				tpr("select",$r,$top,$row2["page"],"Права доступа",$access);
				tpr(1);
				if ($i!=1) $i++;
			}
				$rec3=row_select("id,name,page,global_id","admin_tree","menu_top=\"".$row2["id"]."\"","global_id,ord,menu_top");
				while ($row3=$rec3->ga()) {
					if ($i!=1) {
		
					$r=s_select($row3["page"],$main,"id=$top");			
		
					tpr(0,"menu-color1");
					tpr("title","","","");
					tpr("title",$row3["name"],"","folder","Название раздела","t-main-sub2");
					tpr("select",$r,$top,$row3["page"],"Права доступа",$access);
					tpr(1);
					if ($i!=1) $i++;
				}}
			
			
			}
		}
		
}
?>