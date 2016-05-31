<?
#Перенаправление#0
# Блок проверки наличия таблицы table1
$query="
  target varchar(255) NOT NULL default 0,";  
create_MySQL_table($query,1,0);

mysql_query("INSERT INTO ".PREF."_$main (id,name) VALUES (100001,\"Заголовок\")");
# =======================================

	$nobutton=1;$noback=1;
	define_edit_param();

echo "<h3>При выборе этого раздел, посетитель будет автоматически переходить 
		на другой раздел сайта или по ссылке, которую вы укажете.</h3>
		<p>Если будет указан и раздел и ссылка, то переход будет в выбранный вами раздел.</p>
		";

		$ed=new table_edit();
		$ed->input_names=array("name","content","target");
		$ed->input_komments=array("Открыть раздел сайта:","Открыть ссылку:","Как открыть:");
		$ed->input_types=array("select","textarea2","select");
		$ed->id=100001;
		
		# Выводим список разделов
		if (isset($id)) $id_where="and id!=".$id; else $id_where="";

		$tps1="0,,------------------------";
			# Первый уровень меню
			$rec=row_select("id,name","admin_tree","(menu_top=\"0\" OR menu_top='') and global_id=\"0\" $id_where ","global_id");
			while($row=$rec->ga()){
				$tps1.="#".$row[0].",,".$row[1]."";
				# Второй уровень меню
				$rec2=row_select("id,name","admin_tree","menu_top=\"$row[0]\" and global_id=\"0\" $id_where ","global_id");
				while($row2=$rec2->ga()){
					$tps1.="#".$row2[0].",,-- ".$row2[1]."";
					# Третий уровень меню
					$rec3=row_select("id,name","admin_tree","menu_top=\"$row2[0]\" and global_id=\"0\" $id_where ","global_id");
					while($row3=$rec3->ga()){
						$tps1.="#".$row3[0].",,-- -- ".$row3[1]."";
					}
				}
			}
		
		# Выводим варианты открытия
		$tps2="_self,,В этом же окне#_blank,,В новом окне";
		
		$ed->input_data_values=array($tps1,"",$tps2);
		
		act_message($ed);
?>