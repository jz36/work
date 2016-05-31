<?
class edit {
/*Структура таблицы должна быть такой:
Обязательные поля:
id - идентификатор, int(6) not null
name - название раздела или описания,
content - текст,
maxris - int(4) количество существующих рисунков в описании

Необязательные:
top int(6)  - ссылка на родительский раздел
type int(1) - для обозначения разных типов разделов в одной таблице
ord int(6) - для обозначения порядкового номера
data date - дата
anons - для анонса

*/
#Это обязательные свойства:
var $base_path = "../admin/edit/";
var $zagl="";var $id="";var $top=0;var $type=0;
var $ord=0;var $table="";var $kod="";
var $uspeh="";var $emerg="";var $dattim=0;var $anons="";

#Это - необязательные
#определение стилей
var $table_top=""; #теги шапки "внешней" таблицы - если верстальщик предусмотрел вложенные таблицы
var $table_bot=""; #теги нижней части "внешней" таблицы - если верстальщик предусмотрел вложенные таблицы
var $table_style=""; # атрибуты тега <table>
 var $trfirst_style="";# атрибуты тега <tr> первой строки
 var $tr_style=""; # атрибуты тега <tr> остальных строк
 var $th_style="";# атрибуты тега <td> первой строки
 var $td_style="";# атрибуты тега <td> остальных строк
var $p_style="";# атрибуты тега <p>
var $a_style="";# атрибуты тега <a>
#т.е. определяем стили соответствующих тегов. $table_top и $table_bot - это если вложенная таблица, то определяем стиль "шапки" и стиль нижней части таблицы
var $maxwidth=0; var $maxheight=0; #определение максимальных размеров изображений. Если 0 - то допускаются любые размеры
#дополнительные поля:
var $input_types=array();  var $input_komments=array();   var $input_komments2=array(); var $input_names=array(); var $input_html_name="content";
var $input_data_types=array();var $input_default_values=array();
var $input_data_values=array(); #только для поля типа Select - в формате value#значение;  Первым выводится значение по умолчанию
/* $zagl - заголовок, который показывается при редактировании(например, $zagl="описания"),
$id - id подраздела или описания. Если добавление - то  $id="add", $top - id родительского подраздела (по
умолчанию 0, $type - тип описания (например, новость или информационный подраздел,
$ord - если нужно сделать возможность изменения порядка описаний - то 1, если нет - 0,
$table - название таблицы в базе,
$kod - префикс для рисунков. Файлы с рисунками переименовываются так: $kod.$id.номер рисунка
Все рисунки загружаются в каталог img/kat
, $uspeh - текст, который выводится при успешном завершении транзакции,
$emerg - текст, который выводится при неудачном завершении транзакции,
$dattim - если нужно, чтобы при вводе новой позиции
подставлялась текущая дата в поле data, то равен 1, $anons - если необходима генерация анонса, то равен 1.

Необязательные:
$input_names - массив с названиями колонок в таблице, 
$input_html_name - название поля в котором хтмл код,
$input_komments - массив с названиями элементов,
$input_komments2 - массив с комментариями названий,
$input_types - массив с типами дополнительных элементов,
$input_data_types - массив с типами данных для дополнительных элементов (0 - число, 1 - текст, 2  - дата),
$input_default_values - массив со значениями по умолчанию для дополнительных элементов,  */

# "Технические" параметры. Их менять не надо.
var $min=100001; var $maxris=0; var $name=""; var $cookie=0; var $error="";
var $numbimage=0; var $htmlcode=""; var $images=array();var $inputs=array();

function start() {				// основная функция, которая вызывается первой из админской части

global $maxris; global $cookie_name;
$tempfile=show_temp_file(); //находит temp* файл и возвращает его имя или FALSE

global $name; global $kodnew; global $kodres; global $htmlcode;global $sub;
global $HTTP_POST_VARS; global $HTTP_POST_FILES;global $numbimage; global $anons; global $top; global $type;
global $inputs;global $input_types;global $input_komments;global $input_komments2;global $input_names;global $input_html_name;
global $input_data_types; global $input_data_values; global $cookie_inputs; global $admid; global $admtype;global $rand;global $main;global $dbg_listing;



if (isset($maxris) && $maxris!="") $this->maxris=$maxris;
if (isset($cookie_name)) {$this->name=$cookie_name;$this->cookie=1;}
if (isset($name)) $this->name=$name;
if (isset($top)) $this->top=$top;

if (isset($type)) $this->type=$type;
if (isset($anons)) $this->anons=$anons;
if (isset($htmlcode))								// если пришли данные из ПИНа
	{
	$this->htmlcode=lec_image($htmlcode, 1);	// правим для кэша
	$this->text=lec_image($htmlcode, 1);
	if (($this->anons)>0)
		$this->anons=$htmlcode;
	}
if (isset($_FILES) && $_FILES) $this->images=$_FILES;
if (isset($numbimage)) $this->numbimage=count($numbimage);

if (isset($inputs)) $this->inputs=$inputs;  // если есть доп. параметры, то припишем их
else $this->inputs=array();					// иначе просто пустой массив

if (isset($cookie_inputs)) {$parts=explode("`", $cookie_inputs);$this->inputs=$parts;$this->cookie=1;}
if (isset($input_types)) $this->input_types=$input_types;
if (isset($input_komments)) $this->input_komments=$input_komments;
if (isset($input_komments2)) $this->input_komments2=$input_komments2;
if (isset($input_names)) $this->input_names=$input_names;
if (isset($input_html_name)) $this->input_html_name=$input_html_name;
if (isset($input_data_types)) $this->input_data_types=$input_data_types;
if (isset($input_data_values)) $this->input_data_values=$input_data_values;

$zagl=$this->zagl;
$id=$this->id;
$top=$this->top;
$type=$this->type;
$name=$this->name;
$kod=$this->kod;
$cookie=$this->cookie;
$numbimage=$this->numbimage;
$htmlcode=$this->htmlcode;
$images=$this->images;
$table=$this->table;
$maxwidth=$this->maxwidth;
$maxheight=$this->maxheight;


/*if ($kodnew>0)
	{
	$this->load_kod();
	}
elseif ($kodres>0)
	{
	if ($id=="add"){
    $number=max_count($table, 100001);
    $maxris=0;
    if ($numbimage>0) {
      $f=fopen(SITE_FILES_TMP_DIR."/".SITE_TEMPKOD_FILE,"r+");
      $cont=fread($f, filesize(SITE_FILES_TMP_DIR."/".SITE_TEMPKOD_FILE));
      for ($i=0; $i<$numbimage; $i++) {
        $razm=getimagesize($images['pathimage'.$i]['tmp_name']);
        if (($maxwidth>0 && $razm[0]>$maxwidth) || ($maxheight>0 && $razm[1]>$maxheight)) $error="Размер одного или нескольких изображений не соответствуют стандартам, <br>принятым для Вашего сайта (ширина - <i>$maxwidth px</i>, высота - <i>$maxheight px</i>)!";
        $ext=load_image($images['pathimage'.$i]['name'], $images['pathimage'.$i]['tmp_name'], $kod, $number, $i);
        if ($images['bigimage'.$i] && $images['bigimage'.$i]['name'] && $images['bigimage'.$i]['size'] && $ext) {
         $razmb=getimagesize($images['bigimage'.$i]['tmp_name']);
         $extb=load_image($images['bigimage'.$i]['name'], $images['bigimage'.$i]['tmp_name'], $kod, $number, $i."big");
         $cont=str_replace($kod.$number.$i.".".$ext."\"",$kod.$number.$i.".".$ext."\" style=\"cursor:hand\" onClick=\"wopen('".$kod.$number.$i."big"."','".$extb."',".max($razmb[0],40).",".max($razmb[1],40).")\"",$cont);
        }
      }
      rewind($f);
      fwrite($f, $cont);
      fclose($f);
      $maxris=$i--;
    }
  }
	else {
    $maxris=0;
    if ($numbimage>0) {
      $rec=new recordset("select maxris from $table where id=".$id);
      list($maxris)=$rec->gr();
      $f=fopen(SITE_FILES_TMP_DIR."/".SITE_TEMPKOD_FILE,"r+");
      $cont=fread($f, filesize(SITE_FILES_TMP_DIR."/".SITE_TEMPKOD_FILE));
      for ($i=0; $i<$numbimage; $i++) {
        $razm=getimagesize($images['pathimage'.$i]['tmp_name']);
        if (($maxwidth>0 && $razm[0]>$maxwidth) || ($maxheight>0 && $razm[1]>$maxheight)) $error="Размер одного или нескольких изображений не соответствуют стандартам, принятым для Вашего сайта (ширина - <i>$maxwidth px</i>, высота - <i>$maxheight px</i>)!";
        $ext=load_image($images['pathimage'.$i]['name'], $images['pathimage'.$i]['tmp_name'], $kod, $id, $maxris);
        if ($images['bigimage'.$i] && $images['bigimage'.$i]['name'] && $images['bigimage'.$i]['size'] && $ext) {
         $razmb=getimagesize($images['bigimage'.$i]['tmp_name']);
         $extb=load_image($images['bigimage'.$i]['name'], $images['bigimage'.$i]['tmp_name'], $kod, $id, $maxris."big");
         $cont=str_replace($kod.$id.$maxris.".".$ext."\"",$kod.$id.$maxris.".".$ext."\" style=\"cursor:hand\" onClick=\"wopen('".$kod.$id.$maxris."big"."','".$extb."',".max($razmb[0],40).",".max($razmb[1],40).")\"",$cont);
        }
        $maxris++;
      }
      rewind($f);
      fwrite($f, $cont);
      fclose($f);
    }
  }
	$this->maxris=$maxris;
	if (!isset($error)) $this->show_kod();
	else
		{
		$this->error=$error;
		$this->cookie=1;
		require("".SITE_ADMIN_DIR."/_adm_top.php");
		$this->show_	();
		require("".SITE_ADMIN_DIR."/_adm_bot.php");
		}
	}
elseif ($htmlcode!="")							// если "приехали" с ПИНа то сюда
	{
	$file=fopen($tempfile, "w");
	fputs($file, delslashes($htmlcode));  // сохраняем ПИН в файл temp*
	fclose($file);
	$this->text=$htmlcode;
	$this->parse_content();					// вызываем метод EDIT parse_content();
	adm_stop('');								// просто рисуем bottom
	exit;
	}
else
	{
	$this->show_content(); // сюда попадаем просто при первой загрузки редактирования ПИНа
	}*/
	$this->parse_content();					// вызываем метод EDIT parse_content();
	if (isset($_REQUEST['save'])) $this->load_kod();
	else $this->show_content();
}		// конец функции START()


function show_content() {
global $_VARS; global $_FILES;
$tempfile=show_temp_file(); //находит temp* файл и возвращает его имя или FALSE
global $rand; global $sub;global $main;global $top; global $noback;
#global $admsession;
$zagl=$this->zagl;
$id=$this->id;
$error=$this->error;
$top=$this->top;
$type=$this->type; 
$name=$this->name; 
$table=$this->table;
$kod=$this->kod; 
$cookie=$this->cookie; 
$numbimage=$this->numbimage;
$htmlcode=$this->htmlcode; 
$inputs=$this->inputs;
$input_types=$this->input_types;
$input_komments=$this->input_komments;
$input_komments2=$this->input_komments2;
$input_names=$this->input_names;
$input_html_name=$this->input_html_name;
$input_data_types=$this->input_data_types;
$input_data_values=$this->input_data_values;
$input_default_values=$this->input_default_values;


if ($id!="add") 
{	// тут если раздел не добавляется, а модифицируется имеющийся

		//echo realpath("inc/edit");
		$q="select name, ".$input_html_name;		//-----
		$q.=" from $table where id=$id";  //  тут читаем из предоставленной таблицы
		$res=mysql_query($q);				//  прежние данные name и content
		$row=mysql_fetch_row($res);		//-----
		$name=$row[0];						//  заносим в $name значение поля name из таблицы
		$cont=$row[1];  // ??? вызываем метод prepare_content с параметром content
		
}

	// прошли подготовку к добавлению либо модификации
?>
<table class="table2">
<FORM name="data" action="<?=PAGE?>?main=<?=$main?>" method="post"  ENCTYPE="multipart/form-data">
<input type=hidden name=top value=<? echo $top ?>>
<input type=hidden name=sub value=<? echo $sub ?>>
<?	if(isset($top_table)) echo"<input type=hidden name=top_table value=$top_table>";
if(isset($top_id)) echo"<input type=hidden name=top_id value=$top_id>";?>
<input type=hidden name=type value=<? echo $type ?>>
<input type=hidden name=rand value=<? echo $rand ?>>
<?
if ($error!="") echo "<b><font color=red>".$error."</b></font><br><br>";	// если ошибка то выводим ее
//  формируем заголовок
//выводим кнопку назад
if (empty($noback)){	
	echo "<img src=".SITE_ADMIN_DIR."/img/tri3.gif width=7>&nbsp;&nbsp;";																//
	echo $zagl."&nbsp;|&nbsp;<a href=".PAGE."?main=$main",url_dop_param()," onClick=\"javascript:window.history.back();\"><b>Назад</b></a><br><br>";
}
?>
<tr><td class=text valign=top><b>Название:</b></td><td>
<textarea name=name cols=50 rows=3><? $name=str_replace("\\'", "'", $name); echo delslashes(addquotes($name)) ?></textarea></td></tr><?
if (count($input_names)>0)			// $input_names[] - для доп. полей
	{										// работаем тут когда кроме name и content есть еще поля

	if ($id!="add" && $cookie<1)					// если не добавляем новый раздел и cookie=0
		{
		$q="select ";
		for ($i=0;$i<count($input_names);$i++){ // составляем запрос с дополнительыми полями из таблицы
			if ($input_types[$i]!="img" && $input_types[$i]!="imgres" && $input_types[$i]!="file" && $input_types[$i]!="flash" && $input_types[$i]!="textprint") 
				$q.=$input_names[$i].", ";			
			}
		$q=substr($q, 0, strlen($q)-2);		// удалим последний пробел и запятую из запроса (чистка)
		
		$q.=" from $table where id=$id";		// допишем запрос
		$rec3=new recordset($q);
		$row3=$rec3->ga();							// $row3[] - данные доп. полей
		for ($i=0;$i<count($input_names);$i++)
			if ($input_types[$i]!="img" && $input_types[$i]!="imgres" && $input_types[$i]!="file" && $input_types[$i]!="flash"  && $input_types[$i]!="textprint") {
				$inputs[$i]=$row3[$input_names[$i]];				// заполним системный массив значениями из полей в порядке следования
			}
			else $inputs[$i]="";
		}
	elseif ($id=="add" && $cookie<1)					// если добавляем новый раздел и cookie=0
		{
		for ($i=0;$i<count($input_names);$i++)			//
			{														//	пробегаем по всем доп. параметрам
			if (isset($input_default_values[$i]))		//	и если они имеют значения по умолчанию, то
				$inputs[$i]=$input_default_values[$i];  //	заносим их в $inputs[] иначе в $inputs[]
			else $inputs[$i]="";								//	запишем пустую строку.
			}
		}
	for ($i=0;$i<count($input_names);$i++)							// пробегаем по всем полям $inputs[]
		{																		// в котором значения параметров $input_names[]
		$inputs[$i]=str_replace("\\'", "'", $inputs[$i]);		// заменяем кавычки
		if ($input_types[$i]!="hidden") {							// пишем ПОДПИСЬ к параметру если не hidden
		echo "<tr><td valign=top class=text><b>$input_komments[$i]</b>";
		if (!empty($input_komments2[$i])) echo "<div class=comment>$input_komments2[$i]</div>";
		echo "</td><td valign=middle>";}
		if ($input_data_types[$i]==2 && $inputs[$i]!="" && $cookie<1) // если параметр - это НЕпустая дата
			$inputs[$i]=date_preobr($inputs[$i]);							//  то преобразуем к человечесому виду
																							// и запишем обратно
		switch($input_types[$i])
			{
			case "text":
				$inputs[$i]=str_replace("\\'", "'", $inputs[$i]);
			?><input size=50 name=inputs[<? echo $i ?>] value="<? echo delslashes(addquotes($inputs[$i])); ?>"><?
			if ($input_data_types[$i]==2) {
				echo "<a href='javascript:void(0);' onClick='window.open(\"popup.php?file=calendar.php&name=inputs[".$i."]&form=data&from=&to=&date=\" + document.data[\"inputs[".$i."]\"].value +\"&initdate=\" + document.data[\"inputs[".$i."]\"].value +\"\",\"_blank\",\"left=\" + (window.event.screenX+80/2) + \",top=\" + (window.event.screenY+20+160>screen.height-40? window.event.screenY-45-160:window.event.screenY+20-80) + \",width=180,height=160,scrollbars=no\"); return false' title='Календарь'><img src=\"".SITE_ADMIN_DIR."/img/calendar.gif\" alt=\"Календарь\" width=\"15\" height=\"15\" border=\"0\"></a>";
			
			}
			break;
			
			case "text2":
				$inputs[$i]=str_replace("\\'", "'", $inputs[$i]);
			?><input size=20 name=inputs[<? echo $i ?>] value="<? echo delslashes(addquotes($inputs[$i])); ?>"><?
			if ($input_data_types[$i]==2) {
				echo "<a href='javascript:void(0);' onClick='window.open(\"popup.php?file=calendar.php&name=inputs[".$i."]&form=data&from=&to=&date=\" + document.data[\"inputs[".$i."]\"].value +\"&initdate=\" + document.data[\"inputs[".$i."]\"].value +\"\",\"_blank\",\"left=\" + (window.event.screenX+80/2) + \",top=\" + (window.event.screenY+20+160>screen.height-40? window.event.screenY-45-160:window.event.screenY+20-80) + \",width=180,height=160,scrollbars=no\"); return false' title='Календарь'><img src=\"".SITE_ADMIN_DIR."/img/calendar.gif\" alt=\"Календарь\" width=\"15\" height=\"15\" border=\"0\"></a>";
			
			}
			break;

			case "hidden":
			?><input type=hidden name=inputs[<? echo $i ?>] value="<? echo delslashes(addquotes($inputs[$i])); ?>"><?
			break;


			case "textarea": $inputs[$i]=str_replace("\\'", "'", $inputs[$i]);
			?><textarea name=inputs[<? echo $i ?>] cols=70 rows=11><? echo delslashes(addquotes($inputs[$i])); ?></textarea><?
			break;
			
			case "textarea2": $inputs[$i]=str_replace("\\'", "'", $inputs[$i]);
			?><textarea name=inputs[<? echo $i ?>] cols=70 rows=5><? echo delslashes(addquotes($inputs[$i])); ?></textarea><?
			break;
			
			case "check":
			?><input name="inputs[<? echo $i ?>]" type="checkbox" <?if ($inputs[$i]==1) echo "value=1 checked"; else echo "value=0";?>><?
			break;

			case "select":										// выпадающий список
			$parts=split("#", $input_data_values[$i]);  // разделяем сначала по ; exp:$parts[]=(0#Да,1#Нет,2#Не знаю)
				$keys=array();	$values=array();
			for ($j=0;$j<count($parts);$j++)	{
				if ($parts[$j]!=""){
					$dopparts=split(",,", $parts[$j]);		// разбиваем еще на кусочки $dopparts[]=(0,Да;1,Нет;2,Не знаю)
					$keys[count($keys)]=$dopparts[0];		// массив $keys[]=(0,1,2)
					$values[count($values)]=$dopparts[1];	// массив $values[]=(Да,Нет,Не знаю)
					}
				}
			?><select name="inputs[<? echo $i ?>]" style="width:80%"><?
			for ($j=0;$j<count($keys);$j++)
					{
					if ($values[$j]!="")
						{
						echo "<option value='".delslashes(addquotes($keys[$j]))."'";
					if ($inputs[$i]==$keys[$j]) echo " selected";
					elseif ($id=="add" AND @$input_default_values[$i]==$keys[$j]) echo " selected";
						echo ">".delslashes(addquotes($values[$j]))."</option>";
						}
					}?>
			</select><?
			break;

			case "select2":										// выпадающий список
			$parts=split("#", $input_data_values[$i]);  // разделяем сначала по ; exp:$parts[]=(0#Да,1#Нет,2#Не знаю)
				$keys=array();	$values=array();
			for ($j=0;$j<count($parts);$j++)	{
				if ($parts[$j]!=""){
					$dopparts=split(",,", $parts[$j]);		// разбиваем еще на кусочки $dopparts[]=(0,Да;1,Нет;2,Не знаю)
					$keys[count($keys)]=$dopparts[0];		// массив $keys[]=(0,1,2)
					$values[count($values)]=$dopparts[1];	// массив $values[]=(Да,Нет,Не знаю)
					}
				}
			?><select name="inputs[<? echo $i ?>]" style="width:80%" multiple><?
			for ($j=0;$j<count($keys);$j++)
					{
					if ($values[$j]!="")
						{
						echo "<option value='".delslashes(addquotes($keys[$j]))."'";
					if ($inputs[$i]==$keys[$j]) echo " selected";
					elseif ($id=="add" AND @$input_default_values[$i]==$keys[$j]) echo " selected";
						echo ">".delslashes(addquotes($values[$j]))."</option>";
						}
					}?>
			</select><?
			break;
			
		case "select3":
			$parts=split("#", $input_data_values[$i]);
			$keys=array();$values=array();
			for ($j=0;$j<count($parts);$j++) {
				if ($parts[$j]!=""){
				$dopparts=split(",,", $parts[$j]);
				$keys[count($keys)]=$dopparts[0];
				$values[count($values)]=$dopparts[1];
				}}
			?><select name="inputs[<? echo $i ?>]" size="<?=count($keys)?>" multiple>
			<? for ($j=0;$j<count($keys);$j++) {
				if ($values[$j]!="") {
					$tmp=explode("|",@$input_default_values[$i]);
					echo "<option value=$keys[$j]";
					if (in_array($keys[$j],$tmp)) echo " selected";
					echo ">$values[$j]</option>\n";
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
			<? if (($id!="add" || $table=='') && $name_file=test_file($input_names[$i])) {
					$arr_data=getdate(filectime("files/".$name_file)); 
					$razm=filesize("files/".$name_file)/1000;
					echo "</td></tr><tr><td class=text>Сейчас на<br> сервере файл:</td><td>Имя: <b>$name_file</b>.<br>Размер: <b>".$razm."</b> кБ. <br>Последнее обновление: <b>".$arr_data["mday"].".".$arr_data["mon"].".".$arr_data["year"]."</b>.";
				}
				//else echo "В настоящее время файл не загружен!<br>";
				//echo $input_names[$i-1];
			break;
# ============================================================
		case "img": 
			if (isset($input_data_values[$i])) $parts=explode(":", $input_data_values[$i]);
			else unset($parts);?>
			<input name="inputs[<? echo $i ?>]" type=file size=50 onFocus="if (data['inputs[<? echo $i ?>]'].value!='') {document.all['img<? echo $i ?>'].src=data['inputs[<? echo $i ?>]'].value; <? if (isset($parts)) { ?>document.all['img<? echo $i ?>'].width=<? echo $parts[0] ?>;document.all['img<? echo $i ?>'].height=<? echo $parts[1] ?>;<? } ?>}"><br>
			<img name="img<? echo $i ?>" src="<? if ($id!="add" && $ris=test_ris($kod.$id.$input_names[$i])) echo "img/kat/".$ris; else echo "".SITE_ADMIN_DIR."/img/0.gif"; ?>?rand=<? echo $rand ?>" <? if (isset($parts) && $ris=test_ris($kod.$id.$input_names[$i])) echo " width=$parts[0] height=$parts[1]"; ?>><br>
			<? if ($id!="add" && $ris=test_ris($kod.$id.$input_names[$i]) && !isset($input_nodel[$i])) { ?><input type=checkbox name="del_ris<? echo $i ?>"><span class=small>Удалить рисунок: </span><? } ?><? break;

		case "imgres": 
			if (isset($input_data_values[$i])) $parts=split(":", $input_data_values[$i]);
			else unset($parts);?>
			<input name="inputs[<? echo $i ?>]" type=file size=50 onFocus="if (data['inputs[<? echo $i ?>]'].value!='') {document.all['img<? echo $i ?>'].src=data['inputs[<? echo $i ?>]'].value; <? if (isset($parts)) { ?>document.all['img<? echo $i ?>'].width=<? echo $parts[0] ?>;document.all['img<? echo $i ?>'].height=<? echo $parts[1] ?>;<? } ?>}"><br>
			<img name="img<? echo $i ?>" src="<? echo SITE_ADMIN_DIR."/img/0.gif"; ?>?rand=<? echo $rand ?>">
			<? break;
					
		case "flash": 
			if (isset($input_data_values[$i])) $parts=split(":", $input_data_values[$i]);else unset($parts);
			?><input name="inputs<? echo $i ?>" type=file size=50 onFocus="if (data.inputs<? echo $i ?>.value!='') {document.all['obj<? echo $i ?>'].movie=data.inputs<? echo $i ?>.value; <? if (isset($parts)) { ?>document.all['obj<? echo $i ?>'].width=<? echo $parts[0] ?>;document.all['obj<? echo $i ?>'].height=<? echo $parts[1] ?>;<? } ?>}"><br>
			<object name=obj<? echo $i ?> classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" <? if (isset($parts) && $ris=test_ris($kod.$id.$input_names[$i])) echo " width=$parts[0] height=$parts[1]"; else echo "width=1 height=1"; ?>>
			<param name=movie value="<? if ($id!="add" && $ris=test_ris($kod.$id.$input_names[$i])) echo "img/kat/".$ris; else echo "img/0.swf"; ?>?rand=<? echo $rand ?>">
			<param name=quality value=high>
			</object><br><? break;
				}
		if ($input_types[$i]!="hidden") echo "</td></tr>";
		}																// закончили бегать по $inputs[]
	}?></table><br>
<table  class=table2 width=80%>
<TR bgColor="#F0F0F0" height="26"><TD nowrap>

<INPUT type=hidden name=htmlcode><input type=hidden name=adcookie value=1>
<? if ($id=='add') { ?><input type=hidden name=add value=1><? }
   else { ?><input type=hidden name=id value=<?=$id?>><? }
/*   
   $_GET['Skin'] = 'office2003';   
   include($this->base_path."fckeditor.php");
   $editor = new FCKeditor($input_html_name);
   $editor->BasePath = $this->base_path;
   $editor->Height = '300';
   $editor->Config['SkinPath'] = preg_replace("/^\./xis", "", $this->base_path . 'editor/skins/silver/') ;
   $editor->Value = $cont;
   $editor->Create();
*/
?>
<br><input type="submit" name="save" value="Сохранить">&nbsp;&nbsp;
<!-- <input type="submit" name="cancel" value="Отменить" onMouseDown="javascript:history.back();"> -->
</FORM>

</td></tr></table>
<?
adm_stop('');	// просто вызвать adm_bot.php
exit;			// выходим
}

function parse_content() {
global $main;global $rand;global $sub;global $top_table; global $top_id;
global $HTTP_POST_VARS;  global $_FILES;

$tempfile=show_temp_file();						// получаем имя временного файла, в котором полный не
#global $admsession;									// модифицированный ПИН из DHTML

$name=$this->name;
$text=$this->htmlcode;
$text=str_replace("\\'", "'", $text);
$table=$this->table;
$kod=$this->kod;
$id=$this->id;
$top=$this->top;
$type=$this->type;
$min=$this->min;
$anons=$this->anons;
$text=deldoubleslashes($text);					// удалить двойные слеши
$numbimage=$this->numbimage;
$htmlcode=$this->htmlcode;
$inputs=$this->inputs;
$input_types=$this->input_types;
$input_komments=$this->input_komments;
$input_names=$this->input_names;
$input_html_name=$this->input_html_name;
$input_data_types=$this->input_data_types;
$input_data_values=$this->input_data_values;
$table_top=$this->table_top;
$table_bot=$this->table_bot;
$table_style=$this->table_style;
$tr_style=$this->tr_style;
$trfirst_style=$this->trfirst_style;
$td_style=$this->td_style;
$th_style=$this->th_style;
$p_style=$this->p_style;
$a_style=$this->a_style;
$maxwidth=$this->maxwidth;
$maxheight=$this->maxheight;

if ($table_bot=="" && $table_top!="") $table_bot="</TD></TR></TABLE>";

$file=fopen($tempfile, "w");
fputs($file, $text);
fclose($file);

$newstr="";

# ===================================================
# Вставляем файл с парсером
//require("".SITE_ADMIN_DIR."/class/edit_parser.php");

# ===================================================
# Закачка картинок если они есть

echo "asdfasdfasdfasdfasdfasdf";
print_r($_FILES);

if ($id=='add') $real_id=max_count($table); else $real_id=$id;
if($_FILES) for ($i=0; $i<count($inputs); $i++) { 
	if (isset($_POST['del_ris'.$i])) del_ris($kod.$real_id.$input_names[$i]);
	// Загружаем картинки
	if (($input_types[$i]=='img') && $_FILES['inputs']['size'][$i]) load_image($_FILES['inputs']['name'][$i], $_FILES['inputs']['tmp_name'][$i], $kod, $real_id, $input_names[$i]);
	// Загружаем файлы
	elseif (($input_types[$i]=='file') && $_FILES['inputs']['size'][$i]) {del_file($kod.$real_id.$input_names[$i]); load_file($_FILES['inputs']['tmp_name'][$i], $kod.$real_id.$input_names[$i].'.'.array_pop(explode('.',$_FILES['inputs']['name'][$i])));} # 29.12.2004
	// Загружаем и ресайзим необработанные картинки
	elseif (($input_types[$i]=="imgres") && $_FILES['inputs']['size'][$i]) {
		if(!extension_loaded('gd')) dl('php_gd2.dll'); // Грузим библиотеку GD		
		load_image($_FILES['inputs']['name'][$i], $_FILES['inputs']['tmp_name'][$i], $kod, $real_id, "res");
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
			if ($im_numbs[$x]=="b") {
				if (empty($size)) $size=500;
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

/*
# ===================================================

	$file=fopen(SITE_FILES_TMP_DIR."/".SITE_TEMPKOD_FILE, "w");
	fputs($file, $newstr);
	fclose($file);
	$textcont=str_replace(" . ", "", $textcont);
	$textcont=del_end_space($textcont);
	if (!isset($error))
		{
// =========================================================
// Выводим страничку, на которой указываем и загружаем картинки вставленные в поле ПИН
		?>
		<form action="<?=PAGE?>?main=<?=$main?>" method=post ENCTYPE="multipart/form-data" name=adform <? if (count($images)>0) { ?>
		onSubmit="err=0;for (i=0;i<<? echo count($images) ?>;i++) {if (adform['pathimage'+i].value=='') err=1;}
		if (err>0) {alert('Обязательно выберите все рисунки!');return false;}"<? } ?>><input type=hidden name=rand value=<? echo $rand ?>>
		<input type=hidden name=preview value=1>
		<input type=hidden name=name value="<? echo addquotes(delslashes($name)) ?>">
		<input type=hidden name=noadds value=1>
		<input type=hidden name=top value=<? echo $top ?>>
		<input type=hidden name=type value=<? echo $type ?>>
		<input type=hidden name=sub value="<? echo $sub ?>">
		<?	if(isset($top_table)) echo"<input type=hidden name=top_table value=$top_table>";
		if(isset($top_id)) echo"<input type=hidden name=top_id value=$top_id>";?>
		<input type=hidden name=kodres value=1>
		<?
		if ($id=="add"){?><input type=hidden name=add value=1><?}
		else {?><input type=hidden name=id value=<? echo $id ?>><?}
		if ($anons!=""){ ?><input type=hidden name=anons value="<? echo addquotes($textcont) ?>"><?}
		if (count($images)==0) { ?></form><script> adform.submit(); </script><? }
		else {?>
		<b>В ВАШЕМ ОПИСАНИИ ОБНАРУЖЕНЫ ССЫЛКИ НА СЛЕДУЮЩИЕ РИСУНКИ:</b><br>
		Укажите их расположение на диске Вашего компьютера:<BR><BR>
		<table cellpadding=2><?
		for ($i=0; $i<count($images); $i++) {
		?>
		<tr valign="top"><td class=adminText bgColor="#f0f0f0"><div class=comment><b>Загрузить картинку:</b><br><?=$images[$i]?></div>
		<input type=file size="30" name="pathimage<?=$i?>"><div class=comment><input type=checkbox style="align:absmiddle" onClick="if(this.checked) getElementById('td<?=$i?>').style.display=''; else getElementById('td<?=$i?>').style.display='none'">
		Создать ссылку на большую картинку</div>
		<img src="<?=$images[$i]?>" height=50 hspace=5>
		<input type=hidden name="numbimage[<?=$i?>]" value="<? echo ($i+$maximg) ?>"></td>
		<td id="td<?=$i?>" style="display: none" class=adminText bgColor="#f0f0f0"><div class=comment><b>Загрузить ее увеличенную копию:</b><br></div><input type=file size="30" name="bigimage<?=$i?>"><div class=comment>(она будет открываться в новом окне при щелчке на маленькую картинку)</div></td></tr><? 
		}
		for ($i=0;$i<count($inputs);$i++) echo "<input type=hidden name=inputs[$i] value=\"".addquotes(delslashes($inputs[$i]))."\">";
		?>
		</table><input type=submit class=button value="Отправить рисунки"></form><?
// =========================================================
	}
	}
else								// если ошибка
	{
	$this->error=$error;
	$this->cookie=1;
	$this->show_content();	// покажем контент
	}
*/
}


function load_kod()
{
	global $HTTP_POST_VARS; global $HTTP_POST_FILES;global $rand;global $change_stat;global $main;

	//$tempfile=show_temp_file();		// получаем имя временного файла
	#global $admsession;
	$name=$this->name;$table=$this->table;$uspeh=$this->uspeh;$emerg=$this->emerg;
	$id=$this->id;$maxris=$this->maxris;$dattim=$this->dattim;$min=$this->min;$ord=$this->ord;
	$anons=$this->anons;$top=$this->top;$type=$this->type;$numbimage=$this->numbimage;
	$kod=$this->kod;
	$inputs=$this->inputs;
	$input_types=$this->input_types;$input_komments=$this->input_komments;
	$input_names=$this->input_names;
	$input_html_name=$this->input_html_name;
	$input_data_types=$this->input_data_types;
	$input_data_values=$this->input_data_values;

	$numb=max_count($table, $min);				// возвращает число на 1 больше чем макс ID в таблице
	//$filekod=fopen(SITE_FILES_TMP_DIR."/".SITE_TEMPKOD_FILE, "r");				// открываем файл temkod на чтение (там просто текст без шапки)
	//$textar=file(SITE_FILES_TMP_DIR."/".SITE_TEMPKOD_FILE);
	$name=str_replace("\\\\'", "'", $name);
	$name=str_replace("\\'", "'", $name);
	$text=isset($_REQUEST['content'])?stripslashes($_REQUEST['content']):"";
		
if ($id=="add") $real_id=$numb; else $real_id=$id;


#MINE CODE
		$text=trim($text);		// замочим тучу пробелов и \r\n-ов
#MINE CODE
	//fclose($filekod);
	$text=addslashes($text);
	$text=str_replace("\\'", "'", $text);
	if ($id=="add")
		{
		$name=str_replace("\\'", "'", $name);
		$name=del_quotes($name);
		$q1="insert into $table (id, name, ".$input_html_name.", ";
		$q2=") values ($numb, \"$name\", \"$text\", ";
		if ($dattim>0)
			{
				$q1.="data, ";
				$q2.="curdate(), ";
				}
		if ($maxris>0)
			{
				$q1.="maxris, ";
				$q2.="$maxris, ";
				}
		if ($type>0)
		{
		$q1.="type, ";
		$q2.="$type, ";
		}
 		if ($top>0)
			{
			$q1.="top, ";
			$q2.="$top, ";
			}
		if (count($inputs)>0)
			{
			for ($i=0;$i<count($input_names);$i++)
				{
				if ($input_types[$i]!="img" && $input_types[$i]!="imgres" && $input_types[$i]!="file" && $input_types[$i]!="flash" && $input_types[$i]!="select"  && $input_types[$i]!="select2"  && $input_types[$i]!="textprint")
				{
					$inputs[$i]=delslashes($inputs[$i]);
					$inputs[$i]=addslashes($inputs[$i]);
					$q1.=$input_names[$i].", ";
					switch ($input_data_types[$i])
							{
						case 0:
								$q2.=$inputs[$i];
								break;
						case 1:
								$q2.="\"".$inputs[$i]."\"";
								break;
						case 2:
								$q2.="\"".date_kod($inputs[$i])."\"";
								break;
							}
					$q2.=", ";
					}
					if ($input_types[$i]=="select") {
						$q1.=$input_names[$i].", ";
						$val=del_quotes($inputs[$i]);
						if (!empty($inputs[$i."333"])) {
							$id_from=s_select("max(id)",$input_from[$i]);
							s_insert($input_from[$i],"id,name",($id_from+1).",'".del_quotes($inputs[$i."333"])."'");
							$val=($id_from+1);
						}				
						$q2.="'".$val."'"; $q2.=", "; 
					}
					if ($input_types[$i]=="select2") {
						$q1.=$input_names[$i].", ";
						$val=del_quotes($inputs[$i]);
						if (!empty($inputs[$i."333"])) {
							$id_from=s_select("max(id)",$input_from[$i]);
							s_insert($input_from[$i],"id,name",($id_from+1).",'".del_quotes($inputs[$i."333"])."'");
							$val=($id_from+1);
						}				
						$q2.="'".$val."'"; $q2.=", "; 
					}
					if ($input_types[$i]=="select3") {
						$q1.=$input_names[$i].", ";
						if (isset($inputs[$i])) 
							$val="|".implode("|",($inputs[$i]))."|"; 	# убиваем кавычки
						else
							$val="";				
						if (!empty($inputs[$i."333"])) {
							$id_from=s_select("max(id)",$input_from[$i]);
							s_insert($input_from[$i],"id,name",($id_from+1).",'".del_quotes($inputs[$i."333"])."'");
							$val=($id_from+1);
						}
						$q2.="'".$val."'"; $q2.=", "; 
					}
				}
			}
		if ($anons!="")
			{
			$parts=split("\.", $anons);
			$anons="";
			for ($k=0; $k<count($parts); $k++)
				{
					if (strlen($anons)>200)
						break;
					if ($parts[$k]!="" && $parts[$k]!=" ")
						$anons.=$parts[$k].".";
					}
			$q1.="anons, ";
			$q2.="\"$anons\", ";
				}
		$q1=substr($q1, 0, strlen($q1)-2);
		$q2=substr($q2, 0, strlen($q2)-2);
		$q1.=$q2.")";
DbgPrint($q1,0,"edit - load_kod");
		$res=mysql_query($q1);
		if ($ord>0)
			{
			if ($ord==1)
				$rec=row_select("max(ord)",$table);
			elseif ($ord==2)
				$rec=row_select("max(ord)",$table,"top=$top");
			$row=$rec->gr();
			$res2=mysql_query("update $table set ord=($row[0]+1) where id=$numb");
			}
		if (!$res || $res<1)
			echo $emerg;
		else {
			echo $uspeh;
			s_update("admin=\"".$_SESSION["user_id"]."#".date("Y-m-d")."#".date("H:i")."\"",$table,"id=".$numb);
			$change_stat[0]="add";
			$change_stat[1]=$numb;
		}}
	else															// если не добавляем, а изменяем раздел
		{
		
		$name=del_quotes($name);
		$q="update $table set name=\"$name\", ".$input_html_name."=\"$text\", ";
		if ($maxris>0)
			$q.="maxris=$maxris, ";
		if (count($inputs)>0)
			{
			for ($i=0;$i<count($input_names);$i++)
				{
				if ($input_types[$i]!="img" && $input_types[$i]!="imgres" && $input_types[$i]!="file" && $input_types[$i]!="flash" && $input_types[$i]!="select"  && $input_types[$i]!="select2"  && $input_types[$i]!="textprint")
					{
					$inputs[$i]=delslashes($inputs[$i]);
					$inputs[$i]=addslashes($inputs[$i]);
					$q.=$input_names[$i]."=";
					switch ($input_data_types[$i])
							{
						case 0:
								$q.=$inputs[$i];
								break;
						case 1:
								$q.="\"".$inputs[$i]."\"";
								break;
						case 2:
								$q.="\"".date_kod($inputs[$i])."\"";
								break;
							}
					$q.=", ";
					}
					if ($input_types[$i]=="select") {
							$q.=$input_names[$i]."=";
							$val=del_quotes($inputs[$i]);
							if (!empty($inputs[$i."333"])) {
								$id_from=s_select("max(id)",$input_from[$i]);
								s_insert($input_from[$i],"id,name",($id_from+1).",'".del_quotes($inputs[$i."333"])."'");
								$val=($id_from+1);
							}
							$q.="'".$val."'";$q.=", "; 
					}
					if ($input_types[$i]=="select2") {
							$q.=$input_names[$i]."=";
							print_r($inputs);
							if (!empty($inputs[$i])) 
								$val="|".implode("|",($inputs[$i]))."|"; 	# убиваем кавычки
							else
								$val="";
							if (!empty($inputs[$i."333"])) {
								$id_from=s_select("max(id)",$input_from[$i]);
								s_insert($input_from[$i],"id,name",($id_from+1).",'".del_quotes($inputs[$i."333"])."'");
								$val=($id_from+1);
							}
							$q.="'".$val."'";$q.=", "; 
					}
					if ($input_types[$i]=="select3") {
							$q.=$input_names[$i]."=";
							if (isset($inputs[$i])) 
								$val="|".implode("|",($inputs[$i]))."|"; 	# убиваем кавычки
							else
								$val="";
							if (!empty($inputs[$i."333"])) {
								$id_from=s_select("max(id)",$input_from[$i]);
								s_insert($input_from[$i],"id,name",($id_from+1).",'".$inputs[$i."333"]."'");
								$val="|".($id_from+1)."|";
							}
							$q.="'".$val."'";$q.=", "; 
					}
				}
			}
		if ($anons!="")
			{
				$parts=split("\.", $anons);
				$anons="";
			for ($k=0; $k<count($parts); $k++)
					{
					if (strlen($anons)>200)
						break;
					if ($parts[$k]!="")
						$anons.=$parts[$k].".";
					}
			$q.="anons=\"$anons\", ";
				}
		$q=substr($q, 0, strlen($q)-2);
		$q.=" where id=$id";
		DbgPrint($q,0,"edit - load_kod");
		$res=mysql_query($q);
		if (!$res || $res<1)
			echo $emerg;
		else {
			echo $uspeh;
			s_update("admin=\"".$_SESSION["user_id"]."#".date("Y-m-d")."#".date("H:i")."\"",$table,"id=".$id);
			$change_stat[0]="edit";
			$change_stat[1]=$id;
		}
	}
}

}
?>