<?
#Структура сайта#2
# Блок проверки наличия таблицы table1
$query="
  page varchar(255) NOT NULL,
  shablon varchar(255),
  shablon_out varchar(255),
  global_id int(6) NOT NULL default 0,
  alert int(2) NOT NULL default 1,";

create_MySQL_table($query,1,0);

# =======================================
	$this_element="раздел";

	define_edit_param();
	# Устанавливаем, к каким блокам сайта мы имеем доступ по умолчанию
	if ($_SESSION["user_name"]=="root") $colrazd=3; else $colrazd=3;
	
	# Выводим страницу редактирования
	if (isset($add) || isset($id)) {

		$ed=new table_edit();
		$ed->input_names=array("name", "page", "shablon", "shablon_out", "content", "global_id", "menu_top", "0");
		$ed->input_komments=array("Название:", "Синоним раздела:", "Шаблон ввода:", "Шаблон вывода:", "Комментарий к разделу:", "В каком блоке:", "В каком разделе находится:", "Картинка для раздела, если есть");
		$ed->input_types=array("text", "text", "select", "text", "textarea", "select", "select","img");
		$ed->input_komments2[1]="Нужен для индентификации раздела. Может содержать только английские буквы и знак &quot;_&quot;. Например: &quot;about&quot;, &quot;qwer_ty&quot;, &quot;aaa&quot;.";
		$ed->input_komments2[3]="По умолчанию тот же шаблон что и для ввода.";
		$tps2="0,,Выберите шаблон";

	# Формируем список файлов из admin/moduls, и вытаскиваем оттуда название шаблона и его принадлежность
	$i=0;
	for ($n=1;$n<=2;$n++){
		if ($n==1) $dir=SITE_ADMIN_DIR."/moduls/";
		if ($n==2) $dir=SITE_ADMIN_DIR."/moduls/__".PREF."/";
		if (@dir($dir)){
		$dir_index = dir($dir); 
		while($get_filename=$dir_index->read()) { 
			if ( $get_filename != '.' && $get_filename != '..' && $get_filename != 'content' && substr($get_filename,0,2) != '__'){
			$flist = file($dir.$get_filename);
			# Проверяем, вдруг этот шаблон для какого-то конкретного сайта
			# для этого второй строкой в шаблоне должна стоять: #only#PREF
			$only = split("#",$flist[2]);
			if((@$only[1]=="only" && @$only[2]==PREF) || @$only[1]!="only"){
				$parts = split("#",$flist[1]);
				$file_index[$i]["name"] = $parts[1];
				$file_index[$i]["razd"] = $parts[2];
				$filetmp=split(".php",$get_filename);
				$file_index[$i]["file"] = $filetmp[0];
			}
			$i++;
		}}
		$dir_index->close();
	}}
		sort($file_index); 
		
		for ($ri=0;$ri<=$colrazd;$ri++){
			$tps2.="#,,------------------------";
			for ($i=0;$i<(count($file_index));$i++){
				if ($file_index[$i]["razd"]==$ri)
				$tps2.="#".$file_index[$i]["file"].",,- ".$file_index[$i]["name"]."";
			}
		}
	# Закончили 

		$tps5="0,,Раздел сайта#1,,Раздел сервис";
		if ($colrazd==3) $tps5.="#2,,Настройка сайта#3,,Раздел админчасти";

		# Выводим список разделов верхнего уровня
		if (isset($id)) $id_where="and id!=".$id; else $id_where="";

		$tps6="0,,Верхний уровень";
		for ($ri=0;$ri<=$colrazd;$ri++){
			$tps6.="#,,------------------------";
			# Первый уровень меню
			$rec=row_select("id,name","","(menu_top=\"0\" OR menu_top='') and global_id=\"$ri\" $id_where ","global_id");
			while($row=$rec->gr()){
				$tps6.="#".$row[0].",,".$row[1]."";
				# Второй уровень меню
				$rec2=row_select("id,name","","menu_top=\"$row[0]\" and global_id=\"$ri\" $id_where ","global_id");
				while($row2=$rec2->gr()){
					$tps6.="#".$row2[0].",,-- ".$row2[1]."";
				}
			}
		}
		
		#Формируем список занятых синонимов разделов
		$res=row_select("page","admin_tree");
		$java="0";
		while ($r=$res->ga()){
			$java.="#".$r["page"];		
		}
		
		$ed->java=array(1,$java);
		
		
		# Если раздел создан уже, то закрываем поле синоним
		if (isset($id)) {
			$ed->java[1]="";
			$ed->input_types[1]="textprint2";
			$ed->input_komments2[1]="Изменить синоним раздела нельзя";
			$tps1=s_select("page","","id=".$id);
		}
		$ed->input_data_values=array("",@$tps1,$tps2,"","",$tps5,$tps6);
		
		act_message($ed);}
#======================================
		
	# Если только добавили раздел, то заносим его свойства в "настройки сайта и разделов"		
	if (isset($inputs1) && !empty($inputs2)) {
		$i=0;
		if (file_exists(SITE_ADMIN_DIR."/moduls/".$inputs2.".php"))
			$flist = file(SITE_ADMIN_DIR."/moduls/".$inputs2.".php");
		if (file_exists(SITE_ADMIN_DIR."/moduls/__".PREF."/".$inputs2.".php"))
			$flist = file(SITE_ADMIN_DIR."/moduls/__".PREF."/".$inputs2.".php");
		
		# Вытаскиваем свойства сайта, они должны быть второй или третьей строкой
		for ($n=2;$n<=3;$n++){
		$param = split("#",$flist[$n]);
		if(@$param[1]=="param"){
			$idmax=s_select("max(id)","admin_site");
			$idtop=s_select("id","admin_tree","page='$inputs1'");
			for ($i=2;$i<(count($param)-1);$i++){
				$parts = split(";",$param[$i]);
				if (s_select("id","admin_site","param='$parts[0]' and page='$inputs1'")==""){  //Если записи с такими свойствами еще нет, то ...
					s_insert("admin_site","id,name,content,param,page,top",($idmax+$i-1).",'$parts[1]','$parts[2]','$parts[0]','$inputs1','$idtop'");			//вставляем их в таблицу
				}
		}}}
	}
#======================================	
	# Если пришли в свойства раздела, то туда и возвращаемся
	if (!empty($top_table) && (!isset($id) || isset($inputs1))) {
		?>
		<script>
		window.location.href="<?=PAGE?>?main=<?=$top_table?>&rand=<?=$rand?>";
		</script>
		<?	
	}	
#======================================	
	
		$rec=row_select("","","(menu_top=\"0\" OR menu_top='') and global_id<=$colrazd","global_id,ord,id,menu_top");
		table_if_empty($rec);
		$i=0;$n=0;
		while (($rec->nr())>=$n) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		if ($row["global_id"]==0)	tpr(0,"menu-color0");
		if ($row["global_id"]==1)	tpr(0,"menu-color1");
		if ($row["global_id"]==2)	tpr(0,"menu-color2");
		if ($row["global_id"]==3)	tpr(0,"menu-color3");
		tpr_fast_icon("ord");
		if ($row["global_id"]<=1) tpr_fast_icon("check"); else tpr("title","");
		if ($row["global_id"]<=1) tpr_fast_icon("search"); else tpr("title","");
		if ($row["global_id"]<=0) tpr_fast_icon("alert","Popup"); else tpr("title","","","","Popup");
		$class=""; if ($row["global_id"]<=1) $class="bold";
		tpr("title",$row["name"],"id=".$row["id"]."","text","Название раздела",$class);
		tpr_fast_icon("drop");
		tpr_fast_icon("del");
		tpr(1);
		$i++;$n++;
		
			$rec2=row_select("","","menu_top=\"".$row["id"]."\"","global_id,ord,id,menu_top");
			while ($row2=$rec2->ga()) {			
			if ($i!=1) {
	
			if ($row["global_id"]==0)	tpr(0,"menu-color0");
			if ($row["global_id"]==1)	tpr(0,"menu-color1");
			if ($row["global_id"]==2)	tpr(0,"menu-color2");
			if ($row["global_id"]==3)	tpr(0,"menu-color3");
				tpr("ord","Порядок",$row2["id"]);
				tpr("check",$row2["visible"],$row2["id"]);
				tpr("search",$row2["search"],$row2["id"]);
				if ($row["global_id"]<=0) tpr("alert",$row2["alert"],$row2["id"],"","Popup"); else tpr("title","","","","Popup");
				tpr("title",$row2["name"],"id=".$row2["id"]."","text","","t-main-sub");
				tpr_fast_icon("drop",2);
				tpr("icon","del","did=".$row2["id"]."");
				tpr(1);
				if ($i!=1) $i++;
				}
		
				$rec3=row_select("","","menu_top=\"".$row2["id"]."\"","global_id,ord,id,menu_top");
				while ($row3=$rec3->ga()) {			
				if ($i!=1) {
		
				if ($row["global_id"]==0)	tpr(0,"menu-color0");
				if ($row["global_id"]==1)	tpr(0,"menu-color1");
				if ($row["global_id"]==2)	tpr(0,"menu-color2");
				if ($row["global_id"]==3)	tpr(0,"menu-color3");
					tpr("ord","Порядок",$row3["id"]);
					tpr("check",$row3["visible"],$row3["id"]);
					tpr("search",$row3["search"],$row3["id"]);
				if ($row["global_id"]<=0) tpr("alert",$row3["alert"],$row3["id"],"","Popup"); else tpr("title","","","","Popup");
					tpr("title",$row3["name"],"id=".$row3["id"]."","text","","t-main-sub2");
					tpr_fast_icon("drop",3);
					tpr("icon","del","did=".$row3["id"]."");
					tpr(1);
					if ($i!=1) $i++;
				}}
			}
		}
		
?>