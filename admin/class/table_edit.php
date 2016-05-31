<?
class table_edit {

var $zagl="";var $id="";var $top=0;var $type=0;
var $ord=0;var $table="";var $kod="";
var $uspeh="";var $emerg="";var $dattim=0;
var $top_need=0;var $type_need=0;
#Это - необязательные
var $input_types=array();  var $input_komments=array();  var $input_komments2=array(); var $input_from=array(); var $input_names=array(); var $without_td=array();
var $input_data_types=array(); var $input_default_values=array();
var $input_data_values=array();var $input_nodel=array(); var $max=0; var $java=array();
/* $zagl - заголовок, $id - id подраздела или описания. Если добавление - то =add, $top - id родительского подраздела (по
умолчанию 0, $type - тип описания (например, новость или информационный подраздел, $top_need и $type_need - показывают, нужно
ли подставлять в запросы $type и $top, $ord - если нужно
сделать возможность изменения порядка описаний - то 1, если нет - 0, $table - название таблицы в базе,
$kod - префикс для рисунков, $uspeh - текст, который выводится при успешном завершении транзакции,
$emerg - текст, который выводится при неудачном завершении транзакции, $dattim - если нужно, чтобы при вводе новой позиции
подставлялась текущая дата, то равен 1,
$java - скрипт, который можно навесить на Submit формы
$input_names - массив с названиями колонок в таблице или с порядковыми номерами (для изображений) или с названиями файлов,
$input_komments - массив с названиями элементов,
$input_komments2 - массив с комментариями названий,
$input_from - название таблицы из которой берем значение для этого поля,
$without_td - Если этот ряд не нужен
$input_types - массив с типами дополнительных элементов (img - если изображение, file - файл, text, hidden, select, textarea),
$input_data_types - массив с типами данных для дополнительных элементов (0 - число, 1 - текст, 2  - дата(дд.мм.гг), 3 - дата(дд.мм.гггг))  */

function start() {
global $subm; global $main;global $top;global $sub;global $id;global $rand;global $save;global $add_new;global $id;global $_POST;
	if (isset($subm)) {
		$this->load();
		if (isset($add_new)){
			$this->id="add";
			$this->show();
		}
	}
	else 
		$this->show();
}

function show() { 
	global $rand; global $sub;global $main;global $top_table; global $top_id; global $dbg_listing; global $noback;
	$id=$this->id;
	$top=$this->top;
	$type=$this->type;
	$java=$this->java;
	$top_need=$this->top_need;
	$type_need=$this->type_need;
	$zagl=$this->zagl; 
	$table=$this->table;
	$kod=$this->kod; 
	$input_nodel=$this->input_nodel; 
	$input_types=$this->input_types;
	$input_komments=$this->input_komments;
	$input_komments2=$this->input_komments2;
	$input_from=$this->input_from;
	$input_names=$this->input_names;
	$input_data_types=$this->input_data_types;
	$input_data_values=$this->input_data_values; 
	$input_default_values=$this->input_default_values;
	$without_td=$this->without_td;
?><table class="table2">
<form action="<?=PAGE?>?main=<?=$main?>" method=post ENCTYPE="multipart/form-data" name="adform" <? 
	if (!empty($java)) { 
		?>onSubmit="err=0;msg='';<?
		for ($i=0;$i<count($java);$i++) {
			if ($java[$i]=="1"){?>
				if (adform['inputs<?=$i?>'].value=='') {msg='Заполните поле &quot;<?=$input_komments[$i]?>&quot;!, '+msg; err=1;}<?}
			elseif ($java[$i]=="2"){?>
				if (adform['inputs<?=$i?>'].value=='0') {msg='Выберите значение поля &quot;<?=$input_komments[$i]?>&quot;!, '+msg; err=1;}
			<?}		
			// Проверяем что бы поле (было:1) (не было:0) равно значенииям переданным в java
			else {
				$j=explode("#",$java[$i]);
				if ($j[0]==1) {
					$uslov="!=";
					$razd="AND";
					$message="Выберите значение поля &quot;".$input_komments[$i]."&quot; из предложенного списка!";
				}
				if ($j[0]==0) {
					$uslov="==";
					$razd="||";
					$message="Такое значение поля &quot;".$input_komments[$i]."&quot; уже существует! Выберите другое значение";					
				}
				for($x=1;$x<count($j);$x++){
					@$stroke.=" adform['inputs".$i."'].value".$uslov."'".$j[$x]."' ".$razd;		
				}	
				?>
				if (<?=substr($stroke,0,-3)?>) {msg='<?=$message?>, '+msg; err=1;}
				if (adform['inputs<?=$i?>'].value=='') {msg='Заполните поле &quot;<?=$input_komments[$i]?>&quot;!, '+msg; err=1;}
			<?}		
		}
		?>if (err!=0) {alert(msg);return false;}"<?		
	} 
	
	?>><input type=hidden name=rand value=<? echo $rand ?>><?
	if ($id=="add") echo "<input type=hidden name=add value=1>"; 
	else echo "<input type=hidden name=id value=$id>";
	?><input type=hidden name=top value=<? echo $top ?>>
	<input type=hidden name=sub value=<? echo $sub ?>>
	<?	if(isset($top_table)) echo"<input type=hidden name=top_table value=$top_table>";
	if(isset($top_id)) echo"<input type=hidden name=top_id value=$top_id>";?>
	<input type=hidden name=type value=<? echo $type ?>>
	<?
	// формируем заголовок
	//выводим кнопку назад
	if (empty($noback)){	
		echo "<img src=".SITE_ADMIN_DIR."/img/tri3.gif width=7>&nbsp;&nbsp;";	
		echo $zagl."&nbsp;|&nbsp;<a href=".PAGE."?main=$main",url_dop_param()," onClick=\"javascript:window.history.back();\"><b>Назад</b></a><br><br>";
		}
	
	if ($id!="add") {
		$q="select "; 
		for ($i=0;$i<count($input_names);$i++) {
			if ($input_types[$i]!="img" && $input_types[$i]!="imgres" && $input_types[$i]!="file" && $input_types[$i]!="flash" && $input_types[$i]!="textprint" && $input_types[$i]!="divider") 
				$q.=$input_names[$i].", ";
		}
		$q=substr($q, 0, strlen($q)-2); 
		$q.=" from $table where id=$id";
		$rec=new recordset($q); 
		$row=$rec->gr(); 
		$j=0; 
		for ($i=0;$i<count($input_names);$i++) {
			if ($input_types[$i]!="img" && $input_types[$i]!="imgres" && $input_types[$i]!="file" && $input_types[$i]!="flash" && $input_types[$i]!="textprint" && $input_types[$i]!="divider") {
				if (isset($row[$j])) $input_default_values[$i]=$row[$j]; 
				else $input_default_values[$i]=""; 
				$j++;
		}}}
	
	// Выводим основные конопки (сохранить, отменить и тд)
	?>
	<tr><td colspan=2 align=center class="menu-color1">

	<?	if (main_access()==3 || (main_access()==2 && $id=="add")){?>
	<input type=hidden name=subm value=1><input type=hidden name=save value=1>
	<input type=submit class=button value="Сохранить" name="save">
	<input type=hidden value="1" name="save">
	<input type=submit class=button value="Сохранить и добавить еще" name="add_new">
	<?}?>
	<input type=button class=button value=Отменить onClick="location.href='<?=PAGE?>?main=<? echo $main ?><?url_dop_param();?>&type=<? echo $type ?>'"></td></tr>
	
	<?
	// Выводим поля формы ========================================================================
	
	for ($i=0;$i<count($input_names);$i++) {
		if ($input_types[$i]!="hidden" && $input_types[$i]!="divider" ) {
			if (empty($without_td[$i])) echo "<tr><td valign=top class=text width=40%>";
			echo "<b>$input_komments[$i]</b>";
			if (!empty($input_komments2[$i])) echo "<div class=comment>$input_komments2[$i]</div>";
			if (empty($without_td[$i])) echo "</td><td valign=middle class=text>";
		}
		elseif ($input_types[$i]=="divider") {
		echo "<tr><td valign=top class=menu-color3 colspan=2 align=center><h3>";
		
		}
		switch ($input_types[$i]) {
			
		// Разделитель
		case "divider": 
				echo $input_komments[$i];
			break;
			
		// Текстовое поле длинное
		case "text": ?>
			<input name="inputs<? echo $i ?>" size=50 value="<? if (isset($input_default_values[$i])) {if ($input_data_types[$i]==2) $input_default_values[$i]=date_preobr($input_default_values[$i],1);
			if ($input_data_types[$i]==3) $input_default_values[$i]=date_preobr($input_default_values[$i]); echo addquotes($input_default_values[$i]);} ?>"><? 
			if ($input_data_types[$i]==3) {
				echo "<a href='javascript:void(0);' onClick='window.open(\"popup.php?file=calendar.php&name=inputs".$i."&form=adform&from=&to=&date=\" + document.adform.inputs".$i.".value +\"&initdate=\" + document.adform.inputs".$i.".value +\"\",\"_blank\",\"left=\" + (window.event.screenX+80/2) + \",top=\" + (window.event.screenY+20+160>screen.height-40? window.event.screenY-45-160:window.event.screenY+20-80) + \",width=180,height=160,scrollbars=no\"); return false' title='Календарь'><img src=\"".SITE_ADMIN_DIR."/img/calendar.gif\" alt=\"Календарь\" width=\"15\" height=\"15\" border=\"0\"></a>";
			
			}	
			break;			
		
		// Текстовое поле короткое
		case "text2": ?>
			<input name="inputs<? echo $i ?>" size=20 value="<? if (isset($input_default_values[$i])) {if ($input_data_types[$i]==2) $input_default_values[$i]=date_preobr($input_default_values[$i],1);
			if ($input_data_types[$i]==3) $input_default_values[$i]=date_preobr($input_default_values[$i]); echo addquotes($input_default_values[$i]);} ?>"><? 
			if ($input_data_types[$i]==3) {
				echo "<a href='javascript:void(0);' onClick='window.open(\"popup.php?file=calendar.php&name=inputs".$i."&form=adform&from=&to=&date=\" + document.adform.inputs".$i.".value +\"&initdate=\" + document.adform.inputs".$i.".value +\"\",\"_blank\",\"left=\" + (window.event.screenX+80/2) + \",top=\" + (window.event.screenY+20+160>screen.height-40? window.event.screenY-45-160:window.event.screenY+20-80) + \",width=180,height=160,scrollbars=no\"); return false' title='Календарь'><img src=\"".SITE_ADMIN_DIR."/img/calendar.gif\" alt=\"Календарь\" width=\"15\" height=\"15\" border=\"0\"></a>";
			
			}	
			break;			
		
		// Дата
		case "date": ?>
			<input name="inputs<? echo $i ?>" size=20 value="<? 
			if (isset($input_default_values[$i])) {
				$input_default_values[$i]=date_preobr($input_default_values[$i]); echo addquotes($input_default_values[$i]); ?>"><? 
			}
			echo "<a href='javascript:void(0);' onClick='window.open(\"popup.php?file=calendar.php&name=inputs".$i."&form=adform&from=&to=&date=\" + document.adform.inputs".$i.".value +\"&initdate=\" + document.adform.inputs".$i.".value +\"\",\"_blank\",\"left=\" + (window.event.screenX+80/2) + \",top=\" + (window.event.screenY+20+160>screen.height-40? window.event.screenY-45-160:window.event.screenY+20-80) + \",width=180,height=160,scrollbars=no\"); return false' title='Календарь'><img src=\"".SITE_ADMIN_DIR."/img/calendar.gif\" alt=\"Календарь\" width=\"15\" height=\"15\" border=\"0\"></a>";
			
			break;		

		case "textprint": ?>
			<input type=hidden name="inputs<? echo $i ?>" value="<? if (isset($input_default_values[$i])) echo addquotes($input_default_values[$i]);?>"><?echo $input_default_values[$i]?><? break;
		
		case "textprint2": ?>
			<input type=hidden name="inputs<? echo $i ?>" value="<? if (isset($input_default_values[$i])) echo addquotes($input_default_values[$i]);?>"><?echo $input_default_values[$i]?><? break;
		
		case "password": ?>
			<input name="inputs<? echo $i ?>" size=50 type=password value="<? if (isset($input_default_values[$i])) echo addquotes($input_default_values[$i]); ?>"><? break;
		case "hidden": ?>
			<input type=hidden name="inputs<? echo $i ?>" value="<? if (isset($input_default_values[$i])) echo addquotes($input_default_values[$i]); ?>"><? break;
		case "textarea": ?>
			<textarea name="inputs<? echo $i ?>" style="width:100%" rows=11><? if (isset($input_default_values[$i])) echo change_enter(addquotes($input_default_values[$i]),1); ?></textarea><? break;
		case "textarea2": ?>
			<textarea name="inputs<? echo $i ?>" style="width:100%" rows=3><? if (isset($input_default_values[$i])) echo change_enter(addquotes($input_default_values[$i]),1); ?></textarea><? break;
		
		# Вставляем WISIWIG редактор
		case "textarea3": ?>
			<script>
				var oEdit1 = new InnovaEditor("oEdit1");
				oEdit1.cmdAssetManager="modalDialogShow('<?=PATH.SITE_ADMIN_DIR?>/editor/assetmanager/assetmanager.php',640,465)";//Use "relative to root" path		
				oEdit1.btnFlash=true;//Show 'Insert Flash' button
				oEdit1.btnMedia=true;//Show 'Insert Media' button
				
				oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
			</script>
			<input type="hidden" name="inpContent" id="inpContent">
			<? break;
			
						
		case "check": ?>
			<input name="inputs<? echo $i ?>" type="checkbox" <?if (@$input_default_values[$i]==1) echo "value=1 checked"; else echo "value=2";?> onClick="javascript:checkbox(this);"><?break;

		case "select":
			$parts=split("#", $input_data_values[$i]);
			$keys=array();$values=array();
			for ($j=0;$j<count($parts);$j++) {
				if ($parts[$j]!=""){
				$dopparts=split(",,", $parts[$j]);
				$keys[count($keys)]=$dopparts[0];
				$values[count($values)]=$dopparts[1];
				}}
			?><select name="inputs<? echo $i ?>" style="1width:98%">
			<? for ($j=0;$j<count($keys);$j++) {
				if ($values[$j]!="") {
					echo "<option value='$keys[$j]'";
					if (isset($input_default_values[$i]) && $input_default_values[$i]==$keys[$j]) echo " selected"; 
					echo ">$values[$j]</option>";
					}
				}  ?>
			</select>
			<?
			if (!empty($input_from[$i])) {
				?><div class=small>&nbsp;Другое: <input name="inputs<? echo $i ?>333" size=50 value=""></div><?
			}
			break;
			
		case "select2":
			$parts=split("#", $input_data_values[$i]);
			$keys=array();$values=array();
			for ($j=0;$j<count($parts);$j++) {
				if ($parts[$j]!=""){
				$dopparts=split(",,", $parts[$j]);
				$keys[count($keys)]=$dopparts[0];
				$values[count($values)]=$dopparts[1];
				}}
			?><select name="inputs<? echo $i ?>[]" size="<?=count($keys)?>" multiple>
			<? for ($j=0;$j<count($keys);$j++) {
				if ($values[$j]!="") {
					$tmp=explode("|",@$input_default_values[$i]);
					echo "<option value='$keys[$j]'";
					if (in_array($keys[$j],$tmp)) echo " selected";
					echo ">$values[$j]</option>";
					}
				}  ?>
			</select>
			<?
			if (!empty($input_from[$i])) {
				?><div class=small>&nbsp;Другое: <input name="inputs<? echo $i ?>333" size=50 value=""></div><?
			}
			break;

		case "file": 
# редактируем файлы ======================================?>		
			<input name="top_table" type=hidden value=<?=$top_table?>>
			<input name="top_id" type=hidden value=<?=$top_id?>>
			<input name="inputs<? echo $i ?>" type=file size=50><br>
			<? 
				#Если вытаскиваем файл из общей таблицы файлов то берем имя оттуда
				if (!empty($top_table)) 
					$name_file=test_file($input_default_values[$i-1]);
				# Если храним файл в таблице раздела то берем название из предыдущего поля
				else 
					$name_file=test_file($input_default_values[$i-1]);
				
				if (($id!="add" || $table=='') && $name_file) {
					$arr_data=getdate(filectime("files/".$name_file)); 
					$razm=filesize("files/".$name_file)/1000;
					echo "</td></tr><tr><td class=text>Сейчас на<br> сервере файл:</td><td>Имя: <a href='files/$name_file'><b>$name_file</b></a>.<br>Размер: <b>".$razm."</b> кБ. <br>Последнее обновление: <b>".$arr_data["mday"].".".$arr_data["mon"].".".$arr_data["year"]."</b>.";
				}
				else echo "В настоящее время файл не загружен!<br>";
			break;
# ============================================================
		case "img": 
			if (isset($input_data_values[$i])) $parts=split(":", $input_data_values[$i]);
			else unset($parts);?>
			<input name="inputs<? echo $i ?>" type=file size=50 onFocus="if (adform.inputs<? echo $i ?>.value!='') {document.all['img<? echo $i ?>'].src=adform.inputs<? echo $i ?>.value; <? if (isset($parts)) { ?>document.all['img<? echo $i ?>'].width=<? echo $parts[0] ?>;document.all['img<? echo $i ?>'].height=<? echo $parts[1] ?>;<? } ?>}"><br>
			<img name="img<? echo $i ?>" src="<? if ($id!="add" && $ris=test_ris($kod.$id.$input_names[$i])) echo "img/kat/".$ris; else echo "".SITE_ADMIN_DIR."/img/0.gif"; ?>?rand=<? echo $rand ?>" <? if (isset($parts) && $ris=test_ris($kod.$id.$input_names[$i])) echo " width=$parts[0] height=$parts[1]"; ?>><br>
			<? if ($id!="add" && $ris=test_ris($kod.$id.$input_names[$i]) && !isset($input_nodel[$i])) { ?><input type=checkbox name="del_ris<? echo $i ?>"><span class=small>Удалить рисунок: </span><? } ?><? break;
		case "imgres": 
			if (isset($input_data_values[$i])) $parts=split(":", $input_data_values[$i]);
			else unset($parts);?>
			<input name="inputs<? echo $i ?>" type=file size=50 onFocus="if (adform.inputs<? echo $i ?>.value!='') {document.all['img<? echo $i ?>'].src=adform.inputs<? echo $i ?>.value; <? if (isset($parts)) { ?>document.all['img<? echo $i ?>'].width=<? echo $parts[0] ?>;document.all['img<? echo $i ?>'].height=<? echo $parts[1] ?>;<? } ?>}"><br>
			<img name="img<? echo $i ?>" src="<?echo "".SITE_ADMIN_DIR."/img/0.gif"; ?>?rand=<? echo $rand ?>">
			<? break;
		case "flash": 
			if (isset($input_data_values[$i])) $parts=split(":", $input_data_values[$i]);else unset($parts);
			?><input name="inputs<? echo $i ?>" type=file size=50 onFocus="if (adform.inputs<? echo $i ?>.value!='') {document.all['obj<? echo $i ?>'].movie=adform.inputs<? echo $i ?>.value; }if (document.all['obj<? echo $i ?>'].movie!='img/0.swf' ) { document.all['obj<? echo $i ?>'].width=200;document.all['obj<? echo $i ?>'].height=200;}"><br>
			<object name=obj<? echo $i ?> classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" <? if ($id!="add" && $ris=test_ris($kod.$id.$input_names[$i])) echo " width=200 height=200"; else echo " width=1 height=1";  ?>>
			<param name=movie value="<? if ($id!="add" && $ris=test_ris($kod.$id.$input_names[$i])) echo "img/kat/".$ris; else echo "img/0.swf"; ?>?rand=<? echo $rand ?>">
			<param name=quality value=high>
			<EMBED src="<? if ($id!="add" && $ris=test_ris($kod.$id.$input_names[$i])) echo "img/kat/".$ris; else echo "img/0.swf"; ?>?rand=<? echo $rand ?>" quality=high bgcolor='#FFFFFF'  WIDTH=100 HEIGHT=100 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></EMBED>
			</object><br>
			<? if ($id!="add" && $ris=test_ris($kod.$id.$input_names[$i]) && !isset($input_nodel[$i])) { ?><input type=checkbox name="del_ris<? echo $i ?>"><span class=small>Удалить флэш: </span><? } ?><? break;
		
		}
# конец разбора блоков ===============================================

		if (empty($without_td[$i+1]))  echo "</td></tr>";
		?><?}
		
	// Выводим основные конпки (сохранить, отменить и тд)	
	?>
	<tr><td colspan=2 align=center class="menu-color1">

	<?	if (main_access()==3 || (main_access()==2 && $id=="add")){?>
	<input type=hidden name=subm value=1><input type=hidden name=save value=1>
	<input type=submit class=button value="Сохранить" name="save">
	<input type=hidden value="1" name="save">
	<input type=submit class=button value="Сохранить и добавить еще" name="add_new">
	<?}?>
	<input type=button class=button value=Отменить onClick="location.href='<?=PAGE?>?main=<? echo $main ?><?url_dop_param();?>&type=<? echo $type ?>'"></td></tr></form></table><?
	
	require("".SITE_ADMIN_DIR."/_adm_bot.php"); 
exit;}

# загружаем информацию на сервер
function load() { 
global $HTTP_POST_VARS; global $HTTP_POST_FILES;global $main;
$table=$this->table;$ord=$this->ord;$id=$this->id;$top=$this->top;$kod=$this->kod;$type=$this->type;$top_need=$this->top_need;$type_need=$this->type_need;
$dattim=$this->dattim;$max=$this->max;
$input_types=$this->input_types;$input_names=$this->input_names;
$input_from=$this->input_from;
$input_data_types=$this->input_data_types;$input_data_values=$this->input_data_values; $input_default_values=$this->input_default_values;
#global $admsession;

# Формируем строку запроса если добавляем
if ($id=="add") {
		#Если добавляем раздел, то создаем для него колонку в группах юзеров
		if ($table==PREF."_admin_tree"){
			$page=$HTTP_POST_VARS["inputs1"];
			$query="ALTER TABLE ".PREF."_admin_user_groups ADD ".$page." INT(2) DEFAULT 0 NOT NULL ";
			$res=mysql_query($query);
			#Для групп юзеров забиваем для нового раздела права -  сколько у них поставлено по умолчанию
			$res=row_select("name,default_access","admin_user_groups");
			while ($r=$res->ga()) {				
				mysql_query("UPDATE ".PREF."_admin_user_groups SET ".$page."=\"".$r["default_access"]."\" WHERE name =\"".$r["name"]."\" LIMIT 1");
			}
		}
		
		
		if ($max==0) $max=max_count($table); 
		else $max=max_count($table, $max); 
		if ($ord>0) {
			$q="select max(ord) from $table"; 
			if ($top_need>0 || $type_need>0) $q.=" where ";
			if ($top_need>0 && $type_need>0) $q.=" top=$top and type=$type";
			elseif ($top_need>0 && $type_need==0) $q.=" top=$top"; 
			elseif ($top_need==0 && $type_need>0) $q.=" type=$type";
			$rec=new recordset($q); 
			if ($row=$rec->gr()) $neword=$row[0]+1; 
			else $neword=1;
		}
		$q1="insert into $table (id, ";$q2=") values ($max, ";
		for ($i=0;$i<count($input_names);$i++) { 
# выдергиваем инфу из текстовых полей, в зависимости от заявленного типа поля 0 - число, 1 - текст, 2  - дата(дд.мм.гг), 3 - дата(дд.мм.гггг)
			if ($input_types[$i]!="file" && $input_types[$i]!="img" && $input_types[$i]!="imgres" && $input_types[$i]!="flash" && $input_types[$i]!="password" && $input_types[$i]!="select" && $input_types[$i]!="select2" && $input_types[$i]!="textprint" && $input_types[$i]!="divider") {
				if ($input_types[$i]=="check" && empty($HTTP_POST_VARS["inputs".$i])) $HTTP_POST_VARS["inputs".$i]=0;
				$q1.=$input_names[$i].", ";
				$val=del_quotes(@$HTTP_POST_VARS["inputs".$i]); # убиваем кавычки
				if ($input_data_types[$i]==1) $val=change_enter($val);
				if ($input_data_types[$i]==2 || $input_data_types[$i]==3 || $input_types[$i]=="date") $val=date_kod($val);
				if ($input_data_types[$i]==0) {$val=change_zap($val);$val*=1;}
				if ($input_data_types[$i]==0) $q2.=$val; 
				else $q2.="\"".$val."\""; $q2.=", "; 
		}
			if ($input_types[$i]=="password") {
				$q1.=$input_names[$i].", ";
				$q2.="password(\"".$HTTP_POST_VARS["inputs".$i]."\")";$q2.=", ";
		}
			if ($input_types[$i]=="select") {
				$q1.=$input_names[$i].", ";
				$val=del_quotes($HTTP_POST_VARS["inputs".$i]);
				if (!empty($HTTP_POST_VARS["inputs".$i."333"])) {
					$id_from=s_select("max(id)",$input_from[$i]);
					s_insert($input_from[$i],"id,name",($id_from+1).",'".del_quotes($HTTP_POST_VARS["inputs".$i."333"])."'");
					$val=($id_from+1);
				}				
				$q2.="'".$val."'"; $q2.=", "; 
			}
			if ($input_types[$i]=="select2") {
				$q1.=$input_names[$i].", ";
				if (isset($HTTP_POST_VARS["inputs".$i])) 
					$val="|".implode("|",($HTTP_POST_VARS["inputs".$i]))."|"; 	# убиваем кавычки
				else
					$val="";				
				if (!empty($HTTP_POST_VARS["inputs".$i."333"])) {
					$id_from=s_select("max(id)",$input_from[$i]);
					s_insert($input_from[$i],"id,name",($id_from+1).",'".del_quotes($HTTP_POST_VARS["inputs".$i."333"])."'");
					$val=($id_from+1);
				}
				$q2.="'".$val."'"; $q2.=", "; 
			}
		}
		if ($top_need>0) {$q1.="top, ";$q2.=$top.", ";} 
		if ($type_need>0) {$q1.="type, ";$q2.=$type.", ";}
		if (isset($neword)) {$q1.="ord, ";$q2.=$neword.", ";}
		if ($dattim>0) {$q1.="data, ";$q2.="curdate(), ";}
		$q1=substr($q1, 0, strlen($q1)-2); $q2=substr($q2, 0, strlen($q2)-2);$q=$q1.$q2.")"; 
		}
# Формируем строку запроса если редактируем
else {
	$q="update $table set ";
	for ($i=0;$i<count($input_names);$i++) {
		if ($input_types[$i]!="file" && $input_types[$i]!="img" && $input_types[$i]!="imgres" && $input_types[$i]!="flash" && $input_types[$i]!="select"  && $input_types[$i]!="select2"  && $input_types[$i]!="textprint" && $input_types[$i]!="divider") {
			if ($input_types[$i]=="check" && empty($HTTP_POST_VARS["inputs".$i])) $HTTP_POST_VARS["inputs".$i]=0;
			$q.=$input_names[$i]."=";$val=del_quotes(@$HTTP_POST_VARS["inputs".$i]); 	# убиваем кавычки
			if ($input_types[$i]=="password") {$pass[0]="password("; $pass[1]=")";} else { $pass[0]=""; $pass[1]="";}
			if ($input_data_types[$i]==2 || $input_data_types[$i]==3 || $input_types[$i]=="date") $val=date_kod($val);
			if ($input_data_types[$i]==0) {$val=change_zap($val);$val*=1;}
			if ($input_data_types[$i]==1) $val=change_enter($val);
			if ($input_data_types[$i]==0) $q.=$val; 
			else $q.=@$pass[0]."'".$val."'".@$pass[1]; $q.=", ";
		}
		if ($input_types[$i]=="select") {
				$q.=$input_names[$i]."=";
				$val=del_quotes($HTTP_POST_VARS["inputs".$i]);
				if (!empty($HTTP_POST_VARS["inputs".$i."333"])) {
					$id_from=s_select("max(id)",$input_from[$i]);
					s_insert($input_from[$i],"id,name",($id_from+1).",'".del_quotes($HTTP_POST_VARS["inputs".$i."333"])."'");
					$val=($id_from+1);
				}
				$q.="'".$val."'";$q.=", "; 
		}
		if ($input_types[$i]=="select2") {
				$q.=$input_names[$i]."=";
				if (isset($HTTP_POST_VARS["inputs".$i])) 
					$val="|".implode("|",($HTTP_POST_VARS["inputs".$i]))."|"; 	# убиваем кавычки
				else
					$val="";
				if (!empty($HTTP_POST_VARS["inputs".$i."333"])) {
					$id_from=s_select("max(id)",$input_from[$i]);
					s_insert($input_from[$i],"id,name",($id_from+1).",'".$HTTP_POST_VARS["inputs".$i."333"]."'");
					$val="|".($id_from+1)."|";
				}
				$q.="'".$val."'";$q.=", "; 
		}
		//echo $HTTP_POST_VARS["inputs".$i]."--<br>--";
	}
	$q=substr($q, 0, strlen($q)-2);$q.=" where id=$id";
}
if ($id=="add") $real_id=$max; else $real_id=$id;
for ($i=0;$i<count($input_names);$i++) {
	if ($input_types[$i]=="file" && $HTTP_POST_FILES["inputs".$i]["name"]!="") { 
		// Если загружаем файл из раздела файлов то берем имя которое вбили до этого
		if ($input_data_types[$i]==1) {
			$fname=to_lat($HTTP_POST_VARS["inputs".($i-1)],"_");
		}
		// Если просто поле с файлом, то заменяем на название латинницу, и берем это имя файла
		else {
			$fname=to_lat($HTTP_POST_FILES["inputs".$i]["name"],"_");
		}		 
		load_file($HTTP_POST_FILES["inputs".$i]["tmp_name"],$fname , $HTTP_POST_FILES["inputs".$i]["name"]);
	}
	if (isset($HTTP_POST_VARS["del_ris".$i])) 
		del_ris($kod.$real_id.$input_names[$i]);
	if (($input_types[$i]=="img" || $input_types[$i]=="flash") && $HTTP_POST_FILES["inputs".$i]["name"]!="") {
		load_image($HTTP_POST_FILES["inputs".$i]["name"], $HTTP_POST_FILES["inputs".$i]["tmp_name"], $kod, $real_id, $input_names[$i]);
		}
	if (($input_types[$i]=="imgres") && $HTTP_POST_FILES["inputs".$i]["name"]!="") {
		if(!extension_loaded('gd')) dl('php_gd2.dll'); // Грузим библиотеку GD		
		load_image($HTTP_POST_FILES["inputs".$i]["name"], $HTTP_POST_FILES["inputs".$i]["tmp_name"], $kod, $real_id, "res");
		$im_res=getimg($kod,$real_id,"res");
		if(stristr($im_res,'.jp'))
			$im=imagecreatefromjpeg($im_res);
		elseif(stristr($im_res,'.gif')) 
			$im=imagecreatefromgif($im_res);
		elseif(stristr($im_res,'.bmp') || stristr($im_res,'.rle')) 
			$im=imagecreatefromwbmp($im_res);
		elseif(stristr($im_res,'.pn')) 
			$im=imagecreatefrompng($im_res);
	    
		$im_numbs=explode(",",$input_names[$i]);
		global $top_table;
		if (isset($top_table)) $tpage=$top_table; else $tpage=$kod;
		for ($x=0;$x<count($im_numbs);$x++){
			$width=imagesx($im); $height=imagesy($im);
			$size=s_select("content","admin_site","page='$tpage' AND param='size_".$im_numbs[$x]."'");
			if (empty($size)) $size=s_select("content","admin_site","page='' AND param='size_".$im_numbs[$x]."'");
			if ($im_numbs[$x]=="b") {
				if (empty($size)) $size=600;
				if ($width>=$height) { 
					$wd=$size;
					if ($width<=$size) $wd=$width;
					$ht=$height*$wd/$width;
				}
				else {
					@$ht=$size;
					if ($height<=$size) $ht=$height;
					$wd=$width*$ht/$height;
				}
			}
			else {
				if (empty($size)) $size=150;
				$wd=$size;
				if ($width<=$size) $wd=$width;
				$ht=$height*$wd/$width;
			}
			$in=imagecreatetruecolor($wd,$ht);
			imagecopyresampled($in,$im,0,0,0,0,$wd,$ht,$width,$height);
			del_ris($kod.$id.$im_numbs[$x]);
			imagejpeg($in,'img/kat/'.$kod.$real_id.$im_numbs[$x].'.jpg',90);
		}
		del_ris($kod.$real_id."res");	
	}
}

DbgPrint($q,0,"t_edit - load");

	mysql_query("lock tables $table write");
	$res=mysql_query($q);
	mysql_query("unlock tables"); 
	if ($res>0) {
		echo "<b>".$this->uspeh."</b>";
		s_update("admin=\"".$_SESSION["user_id"]."#".date("Y-m-d")."#".date("H:i")."\"",$table,"id=".$real_id);
		if ($id=="add" && $table==PREF."_admin_tree") {
			# Если добавили новый раздел то, добавляем в группы юзеров новую колонку с названием добавленного раздела
			$tpage=s_select("page",$main,"id=".$real_id);
			mysql_query("ALTER TABLE ".PREF."_admin_user_groups ADD ".$tpage." INT(2) DEFAULT 0 NOT NULL ");
			#Для групп юзеров забиваем для нового раздела права -  сколько у них поставлено по умолчанию
			$res=row_select("name,default_access","admin_user_groups");
			while ($r=$res->ga()) {				
				s_update($tpage."='".$r["default_access"]."'","admin_user_groups","name ='".$r["name"]."' LIMIT 1");
				//mysql_query("UPDATE ".PREF."_admin_user_groups SET ".$tpage."=\"".$r["default_access"]."\" WHERE name =\"".$r["name"]."\" LIMIT 1");
			}}
		global $change_stat;
		if ($id=="add") $change_stat[0]="add"; 
		if ($id=="edit") $change_stat[0]="edit";
		$change_stat[1]=$real_id;
	} 
	else echo "<b><span class=red>".$this->emerg."</span></b>"; 
}
}



?>