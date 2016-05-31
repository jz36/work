<?
#Бэкап базы данных#3
# Блок проверки наличия таблицы table1
//$query="";	
	
//create_MySQL_table($query,1,0);

# =======================================
	$nobutton=1;
	define_edit_param();
///////////////////////////////////////////////////////////////////
// Задаем параметры - имя папки (если нет ее то создаем), имя файла,

	$dbname=DB_NAME;
	$folder="files/db_backup";
	//$folder="img/kat";
	$makefolder=@mkdir($folder, 0755);
	$zext=".gz";
	$echo="";

/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
// Удаляем выбранных файл

if (@$action=="del" && !empty($delfile)){
	unlink($folder."/".$delfile);
	$echo="<div class='red bold'>Файл $delfile удален!</div>";
}



/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
// Делаем бэкап, создаем файл, запаковываем его

if (@$action=="create"){
	
	$zapros_table="";$string_all="";	
	$file=PREF."_db_backup_".date("Y.m.d-H.i").".sql";
	
	
	// Если есть комментарий к базе то пишем его первой строкой
	if (!empty($file_comment))
	{
		$zapros_table="#".str_replace("\r\n","<br>",$file_comment)."
";
	}
	
	
	
	//Узнаём какие таблицы существуют
	$query_t=@mysql_query("show tables;");
	
	while($tables=@mysql_fetch_array($query_t)){
		
		$table_name=$tables['Tables_in_'.$dbname];
		
		//Проверяем, относится таблица к этому сайту или нет, по префиксу
		$pref=explode("_",$table_name);
		if ($pref[0]==PREF){
			$prim_k="";
			$query_t_struc=@mysql_query("DESCRIBE ".$table_name.";");
			$query="DROP TABLE IF EXISTS ".$table_name.";
";
			$query.="create table ".$table_name." (";
				
					
			//Далее создаем запрос на восстановление значений полей в данной таблице
		
			//Выбираем из таблицы все значения
		
			$query_describe=@mysql_query("DESCRIBE ".$table_name.";");
			$column_num=0;
		
			//Узнаем сколько ввсего ячеек в таблице
			//Если ячейка является полем типа datetime или text то заключаем в кавычки вида: ", 'значение',"
		
			while($describe=@mysql_fetch_array($query_describe))
			{
				if(strpos($describe[1],"date")!==false || strpos($describe[1],"time")!==false || $describe[1]=="text" || $describe[1]=="char" || strpos($describe[1],"varchar")!==false){
					$kav[$column_num]=1;
				}
				else{
					$kav[$column_num]=0;
				}
				$column_num++;
			}
		
			//Делаем запрос на вывод всех значений
		
			$query_column=@mysql_query("select * from ".$table_name.";");
			$query_count=mysql_query("select count(*) from ".$table_name.";");
			$count_row=mysql_fetch_row($query_count);
			if($count_row[0]!=0){
				$zapros="";
				
				//Составляем строку запроса
				
				while($column=@mysql_fetch_array($query_column)){
					$column_string="";
					for($z=0; $z<$column_num; $z++){
						if($kav[$z]==1){
							$column[$z]=str_replace("\r\n","",$column[$z]);
							$column[$z]=str_replace("     "," ",$column[$z]);
							$column[$z]=str_replace("    "," ",$column[$z]);
							$column[$z]=str_replace("   "," ",$column[$z]);
							$column[$z]=addslashes($column[$z]);
							$column_string=$column_string."'".$column[$z]."'";
						}
						else{
							$column_string=$column_string.$column[$z];
						}
						if($z<($column_num-1)){
							$column_string.=", ";
						}
					}
					$zapros[]="insert into ".$table_name." values(".$column_string.");";
				}
				
				//Массив с запросами преобразуем в одну строку для записи в файл
				
				$string="";
				for($i=0;$i<count($zapros);$i++){
					$string=$string.$zapros[$i]."
";
				}
				
				//Записываем всё в одно место, что бы потом закинуть в файл, после создания таблицы
				
				$string_all.="
#
#Дамп данных таблицы $table_name
#
";
				$string_all.=$string;
				$echo.="<li>Запрос для таблицы ".$table_name." готов";
				$echo.="(<b>Записей: ".count($zapros)."</b>)";
			}
		
		//Создание запроса на создание таблиц
		
			while($t_str=@mysql_fetch_array($query_t_struc)){
				$t='';
		
				if($t_str['Key']=="PRI"){$prim_k=$t_str['Field'];}
		
				if($t_str['Null']==''){$t='not null';}
				
				$default=" default '".$t_str['Default']."'";
								
				if ($t_str['Extra']=="auto_increment") $default='';
				
				$query=$query.$t_str['Field']." ".$t_str['Type']." ".$t." ".$t_str['Extra']." ".$default." , ";
			}
			if($prim_k!=''){
				$zapros_table=$zapros_table.$query." Primary key (".$prim_k.")) type=myisam;
";
			}
			else{
				$zapros_table=$zapros_table.substr($query,0,-2).") type=myisam;
";				
			}
		}
	}
	//Сохраняем запрос на создание таблиц в файл $file.$zext
	
	//$file_t=fopen($folder."/".$file, 'w');
	//fputs($file_t, $zapros_table);
	//fputs($file_t, $string_all);
	//fclose($file_t);

	$zp = gzopen($folder."/".$file.$zext, "w9"); // w - для записи, 9 - степень сжатия (1-9)
	// Записываем в файл наши данные
    gzwrite($zp, $zapros_table.$string_all);
	// закрываем файл
    gzclose($zp);
   $echo.="<br>&nbsp;<li><b>Файл ".$file.$zext." создан успешно\n<br>\n</b>";
	//$echo.="<li>Запрос для создания таблиц готов, он находится в файле ".$folder."/".$file;
	//echo "<META HTTP-EQUIV='Refresh' CONTENT='5; URL=?'>";
}

/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
// Распаковываем файл, восстанавливаем базу данных
if (@$action=="backup"){

	//echo $folder."/".$file.".gz";
	$source=gzfile($folder."/".$file);

	//Прогоняем  массив через For, тем самым выполняя поочередно запросы
	$q=0;
	for($i=0;$i<count($source);$i++)
	{
		
		if ($source[$i]!="/r")
		{
			//$echo.=substr($source[$i],-4,-2);
			# Проверяем, вдруг строчка слишком длинная и разбита на несколько строк
			if ((substr($source[$i],0,6)=="insert" && substr($source[$i],-4,-2)!=");") || $q==1) {
				
				$q=1;
				@$string.=$source[$i];
				//$echo.=$i."<br>";
				# Если строчка заканчивается точкой с запятой, собираем запрос и выполняем
				if (substr($source[$i],-4,-2)==");") {
					$q=0;
					$query=mysql_query(str_replace(";","",$string));
					if(!$query && mysql_errno()!=1065)
					{
						$echo.="<div class='red bold'><li>Ошибка:".$source[$i]."</div>";
						$echo.="<font color=red><b>".mysql_errno().": ".mysql_error()."</b></font><BR>";
					}
					//$echo.="<div>".$string."</div>";
					@$string="";
				}
				else {
				}
				
			
			}
			# Если все нормально делаем обычный запрос
			else {
				$query=mysql_query(str_replace(";","",$source[$i]));
				if(!$query && mysql_errno()!=1065)
				{
					$echo.="<div class='red bold'><li>Ошибка:".$source[$i]."</div>";
					$echo.="<font color=red><b>".mysql_errno().": ".mysql_error()."</b></font><BR>";
				}
				else {}
					//$echo.="<div><li>".$source	[$i]."</div>";
			}
		}
	}
	$echo.="<div class=bold><li>Обработано запросов: ".$i.". База данных успешно восстановлена!</div>";


}


/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
// Выводим список файлов с бэкапами

$dir = dir($folder);$i=0;

while($line = $dir->read()){
	if ( $line != '.'){
		$pref=explode("_",$line);
		$source="";
		if ($pref[0]==PREF || $i==0){
			$size=round(filesize($folder."/".$line)/1024);
			$date=date("d.m.y",filemtime($folder."/".$line));
			$time=date("H:i",filemtime($folder."/".$line));
			if ( $line != '..'){
				$source=gzfile($folder."/".$line);
				$source=$source[0];
				if ((strpos($source,"#"))===0)
					$source=str_replace("#","",$source);
				else
					$source="---";
			}

			tpr(0,"");
			tpr("link","<b>".$date.", ".$time."</b>","","center","Дата бэкапа");
			tpr("link","Восстановить базу",PAGE."?main=".$main."&action=backup&file=$line","center","Восстановить","","1");
			tpr("link",$line,"files/db_backup/".$line,"","Файл","","small");
			tpr("link","<b>".$size."</b> кБ","","center","Размер");
			tpr("title",$source,"","","Комментарий к файлу","small");
			//tpr("link","Скачать",$folder."/".$line,"center","Скачать","","1");
			tpr("icon","del","action=del&delfile=$line","","");
			tpr(1);
			$i++;
		}
	}
}
echo "</table></form><hr><form action='".PAGE."'><divclass=small>Комментарий к создаваемому файлу:</div><textarea name='file_comment' style='width:300px;' rows=5></textarea><br><input type=hidden name='main' value='$main'><input type=hidden name='action' value='create'><input type=submit value='Создать резервную копию базы данных' style='width:300px;'></form><br><form><table>";
echo $echo;
?>