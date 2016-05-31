<?

$rand=rand(0,32000);

function set_connection() {
	$conn=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("Can't connect with database!"); ;
	mysql_select_db(DB_NAME, $conn);
	
	//mysql_query("SET character_set_client = CP1251_general_ci");
	//mysql_query("SET character_set_results = CP1251_general_ci");
	//mysql_query("SET character_set_connection = CP1251_general_ci");

	}

# ==================== Вложенные функции и классы ===========

# Класс для отправки писем в хтмл с аттачами (все комментарии внутри)
require("".SITE_ADMIN_DIR."/function/func_email.php");




# ==================== работа с файлами ===========

# Удаляем файл
function del_file($rissrc) {
	$rissrc=to_lat($rissrc,"_");
	chdir("files"); $dir=opendir(".");
	while ($strfile=readdir($dir)) {
	if (@ereg($rissrc, $strfile)) {unlink($strfile);} }
	chdir("..");$dir=opendir("."); }

# Загружаем на сервер файл
function load_file($file, $target, $name_file, $folder="files") {
	del_file($target);
	$name_file=to_lat($name_file,"_");
	if (strlen($name_file)>0) {
		$im_ris=split("\.", $name_file);
		$imm=$im_ris[(count($im_ris)-1)];
		$src=$folder."/".$target.".".$imm;
		copy($file, $src); return $imm;
	}}

# Проверяем существование файла
function test_file($src) {
	$src=to_lat($src,"_");
	chdir("files"); $dir=opendir(".");
	while ($strfile=readdir($dir)) {
		if (@ereg($src, $strfile)) $flag=$strfile;
	}
	chdir("..");$dir=opendir(".");
	if (isset($flag)) return $flag; else return false;}

# Определяем тип файла (по расширению), и выводим картинку соответствующую
# По умолчанию выдает путь, может выдавать альт.
function test_file_ext($name="",$type="",$ext="") {

if (empty($ext)) $ext=substr(test_file($name),-3);
switch (strtolower($ext)) {

	case "zip":
	case "rar":
	case "arj":		$src="".SITE_ADMIN_DIR."/img/file-zip.gif";		$alt="Файл в архиве ".$ext;			break;
	case "xls":		$src="".SITE_ADMIN_DIR."/img/file-excel.gif";		$alt="Файл в формате MS Excel";		break;
	case "doc":		$src="".SITE_ADMIN_DIR."/img/file-word.gif";		$alt="Файл в формате MS Word";		break;
	case "txt":		$src="".SITE_ADMIN_DIR."/img/file-txt.gif";		$alt="Текстовый файл";					break;
	case "avi":		$src="".SITE_ADMIN_DIR."/img/file-video.gif";		$alt="Видео-файл в формате ".$ext;	break;
	case "mp3":
	case "wav":
	case "mid":		$src="".SITE_ADMIN_DIR."/img/file-audio.gif";		$alt="Аудио-файл в формате ".$ext;	break;
	case "htm":
	case "html":	$src="".SITE_ADMIN_DIR."/img/file-htm.gif";		$alt="Файл в формате ".$ext;			break;
	case "jpg":
	case "gif":
	case "png":
	case "bmp":		$src="".SITE_ADMIN_DIR."/img/file-img.gif";		$alt="Изображение в формате ".$ext;	break;
	default:			$src="".SITE_ADMIN_DIR."/img/file-un.gif";			$alt="Файл в формате ".$ext;			break;
	}
if ($type=="") return $src;
if ($type=="alt") return $alt;
}

# Для редактора
function del_temp_file() {
	$dir=opendir("./".SITE_FILES_TMP_DIR);
	while ($strfile=readdir($dir)) {
		if (ereg("temp", $strfile)) unlink("./".SITE_FILES_TMP_DIR."/".$strfile);
	}}

# Для редактора
function show_temp_file() {
	$dir=opendir("./".SITE_FILES_TMP_DIR);
	while ($strfile=readdir($dir)) {
		if (ereg("temp", $strfile)) $flag=SITE_FILES_TMP_DIR."/".$strfile;
	}
	if (isset($flag)) return $flag; else return false;}

# ==================== работа с картинками ===========
if (!defined('IMG_KAT')) define("IMG_KAT","img/kat");
if (!defined('LANG')) define("LANG","rus");

# Удаляем рисунок
function del_ris($rissrc) {
	chdir(IMG_KAT);
	$dir=opendir(".");
	while ($strfile=readdir($dir)) {
		if (ereg($rissrc, $strfile)) unlink($strfile); }
		chdir("../..");$dir=opendir("."); }

# Загружаем на сервер рисунок
function load_image($textris, $imgname, $prefix, $id, $numb) {
	del_ris($prefix.$id.$numb);
	if (strlen($textris)>0) {
	$im_ris=split("\.", $textris);
	$imm=$im_ris[(count($im_ris)-1)];
	$src=IMG_KAT."/".$prefix.$id.$numb.".".$imm;
	copy($imgname, $src); return $imm;}}

# Проверяем существование картинок
function test_ris($src,$kat=IMG_KAT,$chdir="../..") {
	chdir($kat);
	$dir=opendir(".");
	while ($strfile=readdir($dir)) {
		if (ereg($src, $strfile)) $flag=$strfile;
	}
	chdir($chdir);$dir=opendir(".");
	if (isset($flag)) return $flag; else return false;}

#  Проверяет наличие картинки, если нет ее, то выдает пустой гиф
function getimg($pref,$idnum,$ended="0",$path="",$img_kat=""){
	//$rr=$path.IMG_KAT."/".$pref.$idnum.$ended.".gif";
	if (empty($img_kat)) $img_kat=IMG_KAT;
	if ($img_kat=="no") $img_kat="";
	$rr="img/0.gif";
		 if(file_exists($path.$img_kat."/".$pref.$idnum.$ended.".gif")) $rr=$path.$img_kat."/".$pref.$idnum.$ended.".gif";
	elseif(file_exists($path.$img_kat."/".$pref.$idnum.$ended.".GIF")) $rr=$path.$img_kat."/".$pref.$idnum.$ended.".GIF";
	elseif(file_exists($path.$img_kat."/".$pref.$idnum.$ended.".jpg")) $rr=$path.$img_kat."/".$pref.$idnum.$ended.".jpg";
	elseif(file_exists($path.$img_kat."/".$pref.$idnum.$ended.".JPG")) $rr=$path.$img_kat."/".$pref.$idnum.$ended.".JPG";
	elseif(file_exists($path.$img_kat."/".$pref.$idnum.$ended.".PNG")) $rr=$path.$img_kat."/".$pref.$idnum.$ended.".PNG";
	elseif(file_exists($path.$img_kat."/".$pref.$idnum.$ended.".png")) $rr=$path.$img_kat."/".$pref.$idnum.$ended.".png";
	//echo ($path.$img_kat."/".$pref.$idnum.$ended.".jpg").$rr;
	return $rr;}

# Возвращает путь для открытия картинки в попап окне
# Может возвращать просто ссылку, а может в виде яваскрипта
function popupimg($pref,$idnum,$ended="0",$i=0){
$razm=getimagesize(getimg($pref,$idnum,$ended));
$razm[1]+=20;
if ($razm[0]<=300) $razm[1]+=20;
	if ($i==0)
		$path="popup.php?file=photo.php&pref=".$pref."&id=".$idnum."&ended=".$ended."";
	if ($i==1)
		$path="javascript:void(0);' onClick='window.open(\"popup.php?file=photo.php&pref=".$pref."&id=".$idnum."&ended=".$ended."\",\"_blank\",\"left=50,top=50,width=$razm[0],height=$razm[1],scrollbars=no\"); return false";
	return $path;}

# Непонятно, вроде не дает кешировать картинки, но зачем, непонятно, нужно для adm_fun
function lec_image($str, $ind=0) { global $rand;
	if ($ind==0) {
		$str=str_replace("src=&quot;", "src=\"", $str);
		$str=str_replace("onclick=&quot;", "onclick=\"", $str);
		$str=str_replace(")&quot;", ")\"", $str);
		$str=str_replace(".jpg&quot;", ".jpg?rand=$rand", $str);
		$str=str_replace(".gif&quot;", ".gif?rand=$rand", $str);
		$str=str_replace(".png&quot;", ".png?rand=$rand", $str);
		$str=str_replace(".bmp&quot;", ".bmp?rand=$rand", $str);

		$str=str_replace(".jpg", ".jpg?rand=$rand", $str);
		$str=str_replace(".gif", ".gif?rand=$rand", $str);
		$str=str_replace(".png", ".png?rand=$rand", $str);
		$str=str_replace(".bmp", ".bmp?rand=$rand", $str);}
	else { $newstr=$str;
	$parts=split("\.jpg\?rand", $str);
	if (count($parts)>1) {$newstr=$parts[0]; for ($i=1;$i<count($parts);$i++) {$dopparts=split(chr(34), $parts[$i]);
	$newstr.=".jpg\"";for ($j=1;$j<count($dopparts);$j++) $newstr.=$dopparts[$j]."\"";$newstr=substr($newstr, 0, strlen($newstr)-1);}}
	$parts=split("\.gif\?rand", $str);
	if (count($parts)>1) {$newstr=$parts[0]; for ($i=1;$i<count($parts);$i++) {$dopparts=split(chr(34), $parts[$i]);
	$newstr.=".gif\"";for ($j=1;$j<count($dopparts);$j++) $newstr.=$dopparts[$j]."\"";$newstr=substr($newstr, 0, strlen($newstr)-1);}}
	$parts=split("\.png\?rand", $str);
	if (count($parts)>1) {$newstr=$parts[0]; for ($i=1;$i<count($parts);$i++) {$dopparts=split(chr(34), $parts[$i]);
	$newstr.=".png\"";for ($j=1;$j<count($dopparts);$j++) $newstr.=$dopparts[$j]."\"";$newstr=substr($newstr, 0, strlen($newstr)-1);}}
	$str=$newstr;}
	return $str;}


# ==================== работа с датами ===========

# Преобразуем дату в четыре различных вида
function date_preobr($date, $ind=0) {
	$date1=substr($date, 8, 2); $date21=substr($date, 5, 2); $date3=substr($date, 0, 4);
	if ($ind==0) $data=$date1.".".$date21.".".$date3;
	elseif ($ind==1) $data=$date1.".".$date21.".".substr($date3, 2, strlen($date3)-2);
	elseif ($ind==2) {
		if (LANG=="rus"){
			switch ($date21) {
				case "01":$date21="января";break;
				case "02":$date21="февраля";break;
				case "03":$date21="марта";break;
				case "04":$date21="апреля";break;
				case "05":$date21="мая";break;
				case "06":$date21="июня";break;
				case "07":$date21="июля";break;
				case "08":$date21="августа";break;
				case "09":$date21="сентября";break;
				case "10":$date21="октября";break;
				case "11":$date21="ноября";break;
				case "12":$date21="декабря";break;
			}
		}
		elseif (LANG=="eng"){
			$date21=date("F",mktime(0,0,0,$date21,1,2000));


		}
	$data=$date1." ".$date21." ".$date3;
	}
	elseif ($ind==3) {
		$date=explode(" ",$date);
		switch (toup($date[1])) {
			case "ЯНВАРЯ"	:$date21="01";break;
			case "ФЕВРАЛЯ"	:$date21="02";break;
			case "МАРТА"	:$date21="03";break;
			case "АПРЕЛЯ"	:$date21="04";break;
			case "МАЯ"		:$date21="05";break;
			case "ИЮНЯ"		:$date21="06";break;
			case "ИЮЛЯ"		:$date21="07";break;
			case "АВГУСТА"	:$date21="08";break;
			case "СЕНТЯБРЯ":$date21="09";break;
			case "ОКТЯБРЯ"	:$date21="10";break;
			case "НОЯБРЯ"	:$date21="11";break;
			case "ДЕКАБРЯ"	:$date21="12";break;
		}
		$date[1]=$date21;
		$date[0]=fixDate($date[0]);
		$data=$date[2]."-".$date[1]."-".$date[0];
	}
	return $data;}

# Преобразуем название дня недели в русский язык
function day_rus($day, $ind=0) {
	if ($ind==0){
	switch ($day) {
		case "1":case "Monday":		$day="Понедельник";break;
		case "2":case "Tuesday":	$day="Вторник";break;
		case "3":case "Wednesday":	$day="Среда";break;
		case "4":case "Thursday":	$day="Четверг";break;
		case "5":case "Friday":		$day="Пятница";break;
		case "6":case "Saturday":	$day="Суббота";break;
		case "7":case "0":case "Sunday":		$day="Воскресенье";break;
	}}
	if ($ind==1){
	switch ($day) {
		case "1":case "Monday":		$day="Пн";break;
		case "2":case "Tuesday":	$day="Вт";break;
		case "3":case "Wednesday":	$day="Ср";break;
		case "4":case "Thursday":	$day="Чт";break;
		case "5":case "Friday":		$day="Пт";break;
		case "6":case "Saturday":	$day="Сб";break;
		case "7":case "0":case "Sunday":		$day="Вс";break;
	}}
	return $day;}

# Преобразуем Дату в формат для базы 0000-00-00
function date_kod($date) {
	//echo $date."asdfasdfasfd";
	$part=split("\.", $date);
	if (strlen($part[2])==2) $part[2]="20".$part[2];
	if (strlen($part[1])==1) $part[1]="0".$part[1];
	if (strlen($part[0])==1) $part[0]="0".$part[0];
	$newstr=$part[2]."-".$part[1]."-".$part[0];
	return $newstr; }

# Добавляем нолик в дату, если там одно число
function fixDate($int){
	$int = ($int<10)?str_replace("0", "", $int):$int;
	$int = ($int<10)?"0$int":$int;
	return $int; }

# Переделывает дату в вид через точку
function remakedata($st){
	$a=fixDate(substr($st,8,2));
	$b=substr($st,5,2);
	$c=substr($st,2,2);
	$d=$a.".".$b.".".$c;
	return $d;}

# ==================== работа с числами, текстом ===========

# Убиваем пробел на конце
function del_end_space($str) {
	$newstr="";$flag=0;
	for ($i=(strlen($str)-1);$i>=0;$i--) {
	$simv=substr($str, $i,1);
	if ($simv!=" ") {$flag=1;$newstr=$simv.$newstr;}
	else {if ($flag>0) $newstr=$simv.$newstr;}}
	return $newstr;}

# заменяем enter и начало строки на br, и наоборот
function change_enter($str, $ind=0) {
	if ($ind==0) {
	$str=str_replace(chr(13), "<br>", $str);
	$str=str_replace(chr(10), "", $str);}
	else $str=str_replace("<br>", chr(13).chr(10), $str);
	return $str; }

# заменяем запятые на точки, нужно для нецелых чисел
function change_zap($str) {
	$newstr="";
	for ($i=0;$i<strlen($str);$i++) {
	$simv=substr($str,$i,1); if ($simv==",") $newstr.="."; else $newstr.=$simv;}
	return $newstr;}

# Вроде бы тоже убирает кавычки
function addquotes($str) {
	$newstr="";
	for ($i=0;$i<strlen($str);$i++) {
	$simv=substr($str,$i,1); if ($simv=="\"") $newstr.="&quot;"; else $newstr.=$simv;}
	return $newstr;}

# Убирает кавычки
function del_quotes($str) {
	$newstr=str_replace("\"", "&quot;", $str);return $newstr;}

# Убирает слэши
	function delslashes($stroka) {
	$stroka=str_replace("\\", "", $stroka);
	return $stroka; }

# Убирает двойные слэши
function deldoubleslashes($stroka) {
	$newstr="";
	for ($i=0; $i<strlen($stroka); $i++) {
	$simv=substr($stroka, $i, 1);
	if ($simv=="\\" && (substr($stroka, ($i+1), 1)=="\"" || substr($stroka, ($i+1), 1)=="\\")) {}
	else $newstr.=$simv; }
	return $newstr; }

# Правильность емайла
function test_email($email) {
	if (!ereg("^.+@.+\\.[a-zA-z]+$", $email) || strlen($email)<7  )  return false; else return true; }

# Правильность пароля
function test_password($str) {
	if (!ereg("^[a-zA-Z0-9]+$", $str) || strlen($str)<7  )  return false; else return true; }

# Проверяем существование пробелов в строке
function test_empty($str) {
	if (ereg("^ +$", $str) || $str=="") return false;
	else return true; }

# Вычищаем теги
function del_tags($str) {
	$newstr="";
	$arr1=split("<", $str);
	for ($i=0;$i<count($arr1);$i++) {$arr2=split(">", $arr1[$i]);
	if (isset($arr2[1])) $newstr.=$arr2[1]; else $newstr.=$arr2[0];}
	return $newstr; }

# Убиваем еденичные пробелы
function del_single_space($str) {$newstr="";
	for ($i=0;$i<strlen($str);$i++) {$simv=substr($str, $i, 1);
	if ($simv==" " && $i<(strlen($str)-1) && substr($str, $i+1, 1)==" ") {}
	else $newstr.=$simv;}
	if (substr($newstr, strlen($newstr)-1, 1)==chr(32) || substr($newstr, strlen($newstr)-1, 1)==chr(13) || substr($newstr, strlen($str)-1, 1)==chr(9)) $newstr=substr($newstr, 0, strlen($newstr)-1);
	if (substr($newstr, 0, 1)==chr(32) || substr($newstr, 0, 1)==chr(13) || substr($newstr, 0, 1)==chr(9)) $newstr=substr($newstr, 1, strlen($newstr)-1);
	return $newstr; }

# Вроде бы выделяет слово среди текста, выводит его жирным
function show_slovo($slovo, $str) {$newstr="";
	$str=str_replace($slovo, "<b>".$slovo."</b>", $str);
	$str=str_replace(strtoupper($slovo), "<b>".strtoupper($slovo)."</b>", $str);
	$str=str_replace(strtolower($slovo), "<b>".strtolower($slovo)."</b>", $str);$
	$str=str_replace(ucfirst($slovo), "<b>".ucfirst($slovo)."</b>", $str);
	$parts=split("\.", $str);
	if (count($parts)<2) $parts=split("!", $str);
	for ($i=0;$i<count($parts);$i++) {
		if ($newstr=="" && (ereg($slovo, $parts[$i]) || ereg(ucfirst($slovo), $parts[$i]) || ereg(strtoupper($slovo), $parts[$i]) || ereg(strtolower($slovo), $parts[$i]))) $newstr=$parts[$i];}
	if ($newstr=="") {
		$newstr.=$parts[0];
		if (strlen($str)<10 && isset($parts[1])) $newstr.=".".$parts[1].".";}
	return $newstr;}

# Проверка правильности емайла
function check_email($email)
{
	$pattern="^[a-zA-Z0-9_]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9\.-]+[a-zA-Z0-9]+\.[a-zA-Z]{2,}$";
    if(eregi($pattern,$email,$regs))
    {
    	if($regs[0]==$email) return true;
        else return false;
    }
    else return false;
}

# Вывод емайла на страницу
function email_echo($email,$string="",$class=""){

	if ($class!="") $class="class=".$class;
	if ($string=="") $string=$email;
	if (check_email(trim($email))!=false)
		$string="<a href='mailto:".$email."' ".$class.">".$string."</a>";
	return $string;

}

# Проверка передаваемого контента из форм со страниц
function email_echo1($name,$error_name,$strip_tag="1",$q_replace="1",$strlen="0"){

	if ($class!="") $class="class=".$class;
	if ($email!="") $string="<a href='mailto:".$email."' ".$class.">".$string."</a>";
	echo $string;

}
# Переводит в латинницу русские буквы
function to_lat($str,$space=" ") {
$newstr="";
for ($i=0;$i<strlen($str);$i++) {
$simv=substr($str,$i,1);
switch ($simv) {
case " ": $newstr.=$space;break;
case "а": $newstr.="a";break;case "б": $newstr.="b";break;case "в": $newstr.="v";break;
case "г": $newstr.="g";break; case "д": $newstr.="d";break;case "е": $newstr.="e";break;
case "ж": $newstr.="zh";break;case "з": $newstr.="z";break;case "и": $newstr.="i";break;
case "к": $newstr.="k";break;case "л": $newstr.="l";break;case "м": $newstr.="m";break;
case "н": $newstr.="n";break;case "о": $newstr.="o";break;case "п": $newstr.="p";break;
case "р": $newstr.="r";break;case "с": $newstr.="s";break;case "т": $newstr.="t";break;
case "у": $newstr.="u";break;case "ф": $newstr.="f";break;case "х": $newstr.="h";break;
case "ц": $newstr.="c";break;case "й": $newstr.="y";break;case "ч": $newstr.="ch";break;
case "ш": $newstr.="sh";break;case "щ": $newstr.="shy";break;case "ъ": $newstr.="'";break;
case "ы": $newstr.="i";break;case "ь": $newstr.="'";break;case "э": $newstr.="e";break;
case "ю": $newstr.="yu";break;case "я": $newstr.="ya";break;
case "А": $newstr.="A";break;case "Б": $newstr.="B";break;case "В": $newstr.="V";break;
case "Г": $newstr.="G";break; case "Д": $newstr.="D";break;case "Е": $newstr.="E";break;
case "Ж": $newstr.="Zh";break;case "З": $newstr.="Z";break;case "И": $newstr.="I";break;
case "К": $newstr.="K";break;case "Л": $newstr.="L";break;case "М": $newstr.="M";break;
case "Н": $newstr.="N";break;case "О": $newstr.="O";break;case "П": $newstr.="P";break;
case "Р": $newstr.="R";break;case "С": $newstr.="S";break;case "Т": $newstr.="T";break;
case "У": $newstr.="U";break;case "Ф": $newstr.="F";break;case "Х": $newstr.="H";break;
case "Ц": $newstr.="C";break;case "Й": $newstr.="Y";break;case "Ч": $newstr.="Ch";break;
case "Ш": $newstr.="Sh";break;case "Щ": $newstr.="Shy";break;case "Ъ": $newstr.="'";break;
case "Ы": $newstr.="I";break;case "Ь": $newstr.="'";break;case "Э": $newstr.="E";break;
case "Ю": $newstr.="Yu";break;case "Я": $newstr.="Ya";break;
default: $newstr.=$simv;}}
return $newstr;}


# Делает все буквы большими, приходится использовать из-за корявой работы с русскими буквами стандартной функции
function toup($str) {
$newstr="";
for ($i=0;$i<strlen($str);$i++) {
$simv=substr($str,$i,1);
switch ($simv) {
case "а": $newstr.="А";break;case "б": $newstr.="Б";break;case "в": $newstr.="В";break;
case "г": $newstr.="Г";break; case "д": $newstr.="Д";break;case "е": $newstr.="Е";break;
case "ж": $newstr.="Ж";break;case "з": $newstr.="З";break;case "и": $newstr.="И";break;
case "к": $newstr.="К";break;case "л": $newstr.="Л";break;case "м": $newstr.="М";break;
case "н": $newstr.="Н";break;case "о": $newstr.="О";break;case "п": $newstr.="П";break;
case "р": $newstr.="Р";break;case "с": $newstr.="С";break;case "т": $newstr.="Т";break;
case "у": $newstr.="У";break;case "ф": $newstr.="Ф";break;case "х": $newstr.="Х";break;
case "ц": $newstr.="Ц";break;case "й": $newstr.="Й";break;case "ч": $newstr.="Ч";break;
case "ш": $newstr.="Ш";break;case "щ": $newstr.="Щ";break;case "ъ": $newstr.="Ъ";break;
case "ы": $newstr.="Ы";break;case "ь": $newstr.="Ь";break;case "э": $newstr.="Э";break;
case "ю": $newstr.="Ю";break;case "я": $newstr.="Я";break;
case "a": $newstr.="A";break;case "b": $newstr.="B";break;case "c": $newstr.="C";break;
case "d": $newstr.="D";break;case "e": $newstr.="E";break;case "f": $newstr.="F";break;
case "g": $newstr.="G";break;case "h": $newstr.="H";break;case "i": $newstr.="I";break;
case "j": $newstr.="J";break;case "k": $newstr.="K";break;case "l": $newstr.="L";break;
case "m": $newstr.="M";break;case "n": $newstr.="N";break;case "o": $newstr.="O";break;
case "p": $newstr.="P";break;case "q": $newstr.="Q";break;case "r": $newstr.="R";break;
case "s": $newstr.="S";break;case "t": $newstr.="T";break;case "u": $newstr.="U";break;
case "v": $newstr.="V";break;case "w": $newstr.="W";break;case "x": $newstr.="X";break;
case "y": $newstr.="Y";break;case "z": $newstr.="Z";break;default: $newstr.=$simv;}}
return $newstr;}

# Делает все буквы Маленькими, приходится использовать из-за корявой работы с русскими буквами стандартной функции
function todown($str) {
$newstr="";
for ($i=0;$i<strlen($str);$i++) {
$simv=substr($str,$i,1);
switch ($simv) {
case "А": $newstr.="а";break;case "Б": $newstr.="б";break;case "В": $newstr.="в";break;
case "Г": $newstr.="г";break;case "Д": $newstr.="д";break;case "Е": $newstr.="е";break;
case "Ж": $newstr.="ж";break;case "З": $newstr.="з";break;case "И": $newstr.="и";break;
case "К": $newstr.="к";break;case "Л": $newstr.="л";break;case "М": $newstr.="м";break;
case "Н": $newstr.="н";break;case "О": $newstr.="о";break;case "П": $newstr.="п";break;
case "Р": $newstr.="р";break;case "С": $newstr.="с";break;case "Т": $newstr.="т";break;
case "У": $newstr.="у";break;case "Ф": $newstr.="ф";break;case "Х": $newstr.="х";break;
case "Ц": $newstr.="ц";break;case "Й": $newstr.="й";break;case "Ч": $newstr.="ч";break;
case "Ш": $newstr.="ш";break;case "Щ": $newstr.="щ";break;case "Ъ": $newstr.="ъ";break;
case "Ы": $newstr.="ы";break;case "Ь": $newstr.="ь";break;case "Э": $newstr.="э";break;
case "Ю": $newstr.="ю";break;case "Я": $newstr.="я";break;                     
case "A": $newstr.="a";break;case "B": $newstr.="b";break;case "C": $newstr.="c";break;
case "D": $newstr.="d";break;case "E": $newstr.="e";break;case "F": $newstr.="f";break;
case "G": $newstr.="g";break;case "H": $newstr.="h";break;case "I": $newstr.="i";break;
case "J": $newstr.="j";break;case "K": $newstr.="k";break;case "L": $newstr.="l";break;
case "M": $newstr.="m";break;case "N": $newstr.="n";break;case "O": $newstr.="o";break;
case "P": $newstr.="p";break;case "Q": $newstr.="q";break;case "R": $newstr.="r";break;
case "S": $newstr.="s";break;case "T": $newstr.="t";break;case "U": $newstr.="u";break;
case "V": $newstr.="v";break;case "W": $newstr.="w";break;case "X": $newstr.="x";break;
case "Y": $newstr.="y";break;case "Z": $newstr.="z";break;default: $newstr.=$simv;}}
return $newstr;}

# Делает первую букву большой
function first_toup($str) {
if (strlen($str)<1) return "";else {
$str=toup(substr($str,0,1)).substr($str,1,strlen($str)-1);
 $str;}}


# Разбиваем текст по количеству символов, не забывая про разделители (например точка)
function divide_text($text="",$kolvo=100,$start=0,$div=".") {
	$pos = strpos(substr($text,$kolvo),$div);	
	$text = substr($text, $start, ($kolvo+$pos)).$div;
	return $text;
}

# Пререводим текст из вин кодировки в уникод
function win2uni($s)
  {
    // преобразование win1251 -> iso8859-5
    $s = convert_cyr_string($s,'w','i'); 
    // преобразование iso8859-5 -> unicode:
    for ($result='', $i=0; $i<strlen($s); $i++) {
      $charcode = ord($s[$i]);
      $result .= ($charcode>175)?"&#".(1040+($charcode-176)).";":$s[$i];
    }
    return $result;
  }
# ==================== запросы в базу, работа с топами и идишниками, построение нав. строки ===========

# Выводим алфавитный рубрикатор
function print_abc_list($table,$rus=1,$eng=1,$title="Алфавитный указатель"){
	global $abc_list;global $main;global $id;global $top;

	$res=row_select("distinct left(name,1) fl",$table,"left(name,1)!=''","fl");
	echo "<div class=abc_list>".$title.": ";
	while ($r=$res->ga()){
		if (empty($lang) AND ord($r["fl"])>=192) {echo " | "; $lang=1;}
		echo "<a href='?main=".$main."&top=".@$top."&abc_list=".$r["fl"]."'>";
		if(@$abc_list==$r["fl"])
			echo "<span class=this>".toup($r["fl"])."</span>";
		else
			echo toup($r["fl"]);
		echo "</a>&nbsp;";

	}
	echo "| <a href='?main=".$main."&top=".@$top."'>Все<a/></div>";

	}

# Выводим поисковик по списку в данной таблице
function print_this_search($table="",$column="name",$title=""){global $main;$i=0;global $id;global $top;$dop="";
	global $main;$i=0;global $id;global $top;$dop="";
	echo "<div class=this_search>";
	?>
	<form action="<?=PAGE?>" method="get">
	<input type="hidden" name="main" value="<?=$main?>" >
	<input type="hidden" name="main1" value="<?=$GLOBALS["QUERY_STRING"]?>" >

	<input id=ft name="search_text" type="text" value="<?=$title?>" onFocus="if(this.form.search_text.value=='<?=$title?>') this.form.search_text.value=''" onBlur="if(this.form.search_text.value=='') this.form.search_text.value='<?=$title?>'" style="width:120px">

	<input type="submit" name="Submit" value="Ок" onClick="if(ft.value=='<?=$title?>') {alert('Введите строку для поиска');return false;}">
	<?echo "</form></div>";

	}

# Выводим список разделов этого уровня, в виде списка или таблицы с разным количеством столбцов
function printSubList($table="",$ttop="",$view="",$table_cols="",$table_class=""){
global $main;$i=0;global $id;global $top;$dop="";
	$this1='';

	if ($table=="") $table=$main;
	if ($table_class=="") $table_class="table";
	if ($table_cols=="") $table_cols="2";
	if (isset($id) && $id!=0) {
		$this1=" class=bold";
		if ($view=="select") $this1="selected";
	}
	else $id=0;
	if (!empty($ttop)) $dop.="&top=$ttop";
	if (!empty($sub) && $sub!=1) $dop.="&sub=$sub";


	$res=row_select("name,id,content","$table","top=\"$ttop\" AND visible=1");
	// Выводим подразделы таблицей
	if ($view=="table"){?>
		<table class=table><?
		while($r=$res->ga()){
			if ($i%$table_cols==0 ) echo "<tr>";
			?>
			<td width=2%><a href='index.php?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'><img src=<?=getimg($table,$r[1],"logo")?> width=<?=param("size_logo",$main)?>  align=left alt=<?=$r[0]?>></a></td>
			<td width=30%><a href='index.php?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'><?=$r[0]?></a></td><?
			if ($i%$table_cols==($table_cols-1)) echo "</tr>";
			$i++;
		}?>
		</table><?
	}
	// Выводим подразделы списком
	if ($view=="list"){?>
		<ul><?
		while($r=$res->ga()){?>
			<li><a href='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1;?>><?=$r[0]?></a></li><?
		}?>
		</ul><?
	}
	// Выводим подразделы подряд, через разделитель
	if ($view=="text"){?>
		<div><?
		while($r=$res->ga()){?>
			<nobr>
			<?if (@$i==1) echo " | "; else $i=1;?><a href='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1;?>><?=$r[0]?></a></nobr><?
		}?>
		</div><?
	}
	// Выводим подразделы выпадающим списком
	if ($view=="select"){?>
		<select onChange="jmpMenu('parent',this,0)" <?if (!empty($table_cols)) echo "style='width:".$table_cols."'";?>>
		<?if ($table_cols==0) {?><option value="#">... <?=$table_class?></option><? $this1="";}
		while($r=$res->ga()){?>
			<option value='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1."> -> "; else echo ">"?><?=$r[0]?></option><?
		}?>
		</select><?
	}
	// Выводим подразделы полностью  с текстами подряд друг за другом
	if ($view=="all"){?>
		<div><?
		while($r=$res->ga()){?>
			<h3><?=$r[0]?></h3>
			<div><?=$r[2]?></div><?
		}?>
		</div><?
	}
	// Выводим подразделы с анонсами подряд друг за другом
	if ($view=="anonse"){?>
		<table class=tableno><?
		while($r=$res->ga()){?>
			<tr><td valign=top align=right><a href='index.php?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'><img src=<?=getimg($table,$r[1],"logo")?> align=left alt='<?=$r[0]?>' vspace=10 hspace=4 class=borderno></a></td>
			<td valign=top><a href='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1;?>><h3><?=$r[0]?></h3></a>
			<?if (!empty($r["content"])) {?><div><?=(divide_text(strip_tags($r[2]),100,0,"."))?> <a href='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1;?>>далее</a></div><?}?>
			</td></tr><?
		}?>
		</table><?
	}





}


#Вытаскиваем информацию записанную с помощью форм в две таблицы (таблица с формой / таблица с данными)
function get_forms($name="",$table_form="",$where="",$table_info=""){

	#Если не пришло $id, тогда возвращаем $id из table_form
	if ($table_info=""){
		return s_select("id",$table_form,"name=\"$name\"");
		}
	#Если пришло $id, тогда возвращаем конкретное значение из table_info
	else {
		$id_from=s_select("id",$table_form,"name=\"$name\"");
		return s_select("i".$id_from,$table_info,$where);
		}
}

# Вычисляем уровень вложенности, возвращает по умолчанию число вложенности
# Если $isnow=1 то возвращает, достигнут или нет уровень, сравнивая его с переменной level_in для этого раздела
function level_in($topid="",$isnow=0){
global $top;global $main;

	if ($topid=="") $topid=$top;

		for($n=0;$topid>0;$n++){
			if ($topid!=0)
				$topid=s_select("top","","id=$topid");
		}
	if ($isnow==0) return $n;

	if ($isnow==1){
		$level_in=s_select("content","admin_site","param=\"level_in\" and page=\"$main\"");
		if ($level_in==$n) return true;
		else return false;
}}

# Формируем постраничный запрос, с помощью следующей функции...
function row_select_pages($from="", $table="", $where="", $ord="",$limit="",$type="",$notop="") {
global $id;global $main;global $pg;

	if(!isset($pg)) $pg=1;
	$kfp=param("kfp",$main);
	if ($kfp=="") $kfp=param("kfp","");

	$rec=row_select("id",$table,$where);
	$kol=$rec->nr();

	$kfp_from=($pg-1)*$kfp;

	DbgPrint("to row_select ".param("kfp",$table),0,"row_select_pages");
	$rec=row_select($from,$table,$where,$ord,$kfp_from.",".$kfp);

	return $rec;
}

# Формируем запрос по требованию --- что выбрать / таблица / условия / порядок
# $type - позволяет выводить данные в разных вариантах
# $type = "nr"   -  nr();
function row_select($from="", $table="", $where="", $ord="",$limit="",$type="",$notop="",$nopref="") {
global $id;global $main;

	if (empty($from)) $from="*";
	if (empty($table)) $table=$main;
	if (empty($nopref)) $table=PREF."_".$table; else $table=$table;
	if ($notop=="") $top="top!=333"; else $top="1=1";
	if (!empty($where)) $where="where ".$top." AND ".$where;
	else $where="where ".$top;
	if ($ord!="") $ord="order by ".$ord.",ord ";
	else $ord="order by ord";
	if ($limit!="") $limit="limit ".$limit;
	$rec=new recordset("select $from from ".$table." ".$where." ".$ord." ".$limit);
	DbgPrint("select $from from ".$table." ".$where." ".$ord." ".$limit,0,"row_select");

	switch ($type){
		case "": return $rec;break;
		case "nr": return ($rec->nr());break;
	}
}

# Удаляет записи по таблице и условию
function s_delete($table="", $where="") {
global $id;global $main;

	if ($table=="") $table=$main;

	$rec=mysql_query("delete from ".PREF."_$table where 1=1 and $where");
	DbgPrint("delete from ".PREF."_$table where 1=1 and $where",0,"s_delete");
	}

# Добавляет в базу новую запись по таблице, запросу и значению
function s_insert($table, $into, $values) {
global $id;global $main;

	$parts=split("_",$table);
	if (ereg("_",PREF) AND !empty($parts[1])) $parts[0]=@$parts[0]."_".@$parts[1];
	if($parts[0]!=PREF && $table!="") $table=PREF."_".$table;
	if ($table=="") $table=PREF."_".$main;

	$rec=mysql_query("INSERT INTO $table ($into) VALUES ($values)");
	DbgPrint("INSERT INTO $table ($into) VALUES ($values)",0,"s_insert");
	}

# Записывает в базу значение по таблице, запросу и значению
function s_update($set, $table, $where="") {
global $id;global $main;

	$parts=split("_",$table);
	if (ereg("_",PREF) AND !empty($parts[1])) {
		$parts[0]=$parts[0]."_".$parts[1];
	}
	if($parts[0]!=PREF  && $table!="") $table=PREF."_".$table;
	if ($table=="") $table=PREF."_".$main;

	$rec=mysql_query("update $table set $set where 1=1 and $where");
	DbgPrint("update $table set $set where 1=1 and $where",0,"s_update");
	}

# Возвращает значение нужной колонки по таблице и запросу
function s_select($from="", $table="", $where="", $ord="",$limit="1",$type="",$notop="",$nopref="") {
global $id; global $main;

	if (empty($from)) $from="*";
	if (empty($table)) $table=$main;
	if (empty($nopref)) $table=PREF."_".$table; else $table=$table;
	if ($notop=="") $top="top!=333"; else $top="1=1";
	if (!empty($where)) $where="where ".$top." AND ".$where;
	else $where="where ".$top;
	if ($ord!="") $ord="order by ".$ord.",ord ";
	else $ord="order by ord";
	if ($limit!="") $limit="limit ".$limit;

	DbgPrint("select $from from ".$table." ".$where." ".$ord." ".$limit,0,"s_select");
	$rec=new recordset("select $from from ".$table." ".$where." ".$ord." ".$limit);
	if (!$row=$rec->ga()) return false; else return $row[0];
	}

# возвращает id самого верхнего родителя с top=0
function show_ttop($id, $table,$ttop="top") {
	$top=$id;
	while ($top>0) {
		$rec=row_select("id,".$ttop,$table,"id='".$top."'");
		$row=$rec->ga();
		$top=$row[$ttop];
		//echo $top.$row["id"];
	}
	return $row["id"];}

# Формируем строку навигации для доступа к данному разделу
function show_tops($table,$id, $top=0) {
	global $rand;$str="";global $main;
	if ($top==0) {
		$rec=new recordset("select id, name, top from ".PREF."_$table where id=$top");
		$row=$rec->gr();
		$ctop=$row[2];
		$cid=$row[0];
		$cname=$row[1];
	while($cid>0){
		$str="<a href=\"$table?top=$cid&rand=$rand\">$cname</a> : ".$str;
		$rec=new recordset("select id, name, top from ".PREF."_$table where id=$ctop");
		if ($row=$rec->gr()) {
			$ctop=$row[2];
			$cid=$row[0];
			$cname=$row[1];}
		else $cid=0;$ctop=0; }
	$str=substr($str,0,strlen($str)-3);}
return $str;}


# Формируем массив с деревом сайта
//$tree=array();
function make_tree($lev=0) {

	global $tree;
	$i=0;
	$res=mysql_query("select name,id,page,shablon,menu_top,global_id from ".PREF."_admin_tree where top=\"0\" AND visible=1 order by ord");
		while($r=mysql_fetch_array($res)){
			//$tree[$i]('name'=>$r['name']);
			$tree[$i]['name']=$r['name'];
			$tree[$i]['id']=$r['id'];
			$tree[$i]['page']=$r['page'];
			$i++;
			echo $i;
	}
}

# Формирование строки навигации
function nav_line($table,$id_start,$razd=" &gt; ",$all_tables=0,$echo=0,$start=0,$this_need=1,$link_need=1,$link_class="",$is_admin=0,$start_name="Главная") {
global $main;global $id;global $rand;$navline="";
if (LANG=="rus") {
	$lng["home"]="Главная";
}
if (LANG=="eng") {
	$lng["home"]="Home";
}
if ($start_name=="Главная") $start_name=$lng["home"];



	# Если это адм часть , то добавляем доп параметры
	if ($is_admin==1) $dop_par="";
	if ($is_admin==0) $dop_par="&rand=$rand&delcookie=1";

	# Собираем дерево в данной таблице
	if($id_start!=""){
		$tid=$id_start;
		while($tid!=0){
			$res=row_select("top,name",$table,"id=$tid");
			$r=$res->ga();
			# Если данный раздел
			if ($this_need==1 && $tid==$id_start)
				$navline="<span class='$link_class'>".$r["name"]."</span>".$navline;
			# Все остальные разделы
			if ($tid!=$id_start)  {
				$temp=$r["name"];
				# Если нужна ссылка
				if ($link_need==1) $temp="<a href='?main=$table&id=$tid".$dop_par."' class='$link_class'>".$temp."</a>";
				$navline=$temp.$razd.$navline;
				}
			$tid=$r["top"];
		}}

		# Собираем дерево среди всех разделов
		$res=row_select("id","admin_tree","page='".$table."'");
		$r=$res->ga();
		$tid=$r["id"];
		while($tid!=0){
			$res=row_select("menu_top,name,page","admin_tree","id=$tid");
			$r=$res->ga();
			$temp=$r["name"];
			$r2=$r["page"];
			# Если нужна ссылка
			if ($link_need==1) $temp="<a href='?main=".$r2."".$dop_par."' class='$link_class'>".$temp."</a>";
			if ($r["name"]!=$start_name) $navline=$temp.$razd.$navline;
			$tid=$r["menu_top"];
			if ($all_tables==0) $tid=0;
		}

	# Если это адм часть , то добавляем доп параметры



	# Добавляем самый верхний уровень
		if (($start==1 && $main!="main"))
		$navline="<a href='?".$dop_par."' class='$link_class'>$start_name</a>".$razd.$navline;


	# Выводим, двумя способами
	if($echo==0) return $navline;
	if($echo==1) echo $navline;

}


# ==================== разное ===========

# Выводим (если нужно) ссылку на окошко с комментарием и оценкой раздела/статьи/картинки
function feedback($table="",$tid=""){
global $main;global $id;

	if ($table=="") $table=$main;
	if ($tid=="") $tid=$id;
	$need_mark=param("need_mark",$main);
	$need_comment=param("need_comment",$main);
	$title="";$mark="";$comm="";$class="";

	if ($need_mark==1 || $need_comment==1){
		if ($need_mark==1){
			$res=row_select("sum(mark) as mark, count(id) as num","all_feedback","top_table='$table' AND top_id='$tid' and visible=1 AND mark!=0");
			$r=$res->ga();
			$num=$r['num'];
			if (!empty($num)) $mark=round(($r['mark'])/($num),2);
			if (empty($num)) {$mark="-"; $class="gray";}
			$mark="<span class='$class'>оценка[$mark]</span> ";
			$title.="оценить,";
		}
		if ($need_comment==1){
			$res=row_select("id","all_feedback","top_table='$table' AND top_id='$tid' AND name!='' and visible=1");
			$num=$res->nr();
			if ($num!=0) {$comm=$num;$class="";}
			else {$comm=0; $class="gray";}
			$comm="<span class='$class'>комментарии[$comm]</span>";
			$title.="посмотреть/оставить комментарии";
		}
		?><br><a href='javascript:void(0);' class=small title='<?=$title?>' onclick ="window.open('popup.php?file=all_feedback.php&top_table=<?=$table?>&top_id=<?=$tid?>&need_mark=<?=$need_mark?>&need_comment=<?=$need_comment?>','_blank','left=200,top=250,width=500,height=450,scrollbars=yes,resizable');"><?=$mark." ".$comm?></a><?
	}

}


# Работаем с переделкой путей в mod_rewrite
if (!defined('MOD_REWRITE')) define("MOD_REWRITE","0");
function mod_rewrite($path){
	if (MOD_REWRITE==0)
		return $path;
	else
		$path=ereg_replace("index.php\?main=","",$path);
		$path.="\\";
		$path=PATH."".$path;
		//$path=ereg_replace("","ss",$path);
		//$path=strtr($path,"id","/");
		return $path;
	}

# Формируем постраничный пейджер
function pager($site=1,$table="",$maxvis=7,$where="",$link="",$comment="",$style="pager") {
global $id;global $main;global $pg;global $top;global $sub;$vis="";$page=PAGE;$toa="";global $abc_list;
	if ($site==1) {$vis="visible=1 AND ";$page=SPAGE;}
	if (!empty($id) && $site!=1) {}
	else {
	if(!isset($pg)) $pg=1;
	$kfp=param("kfp",$main);
	if ($kfp=="") $kfp=param("kfp","");
	if (!empty($table) AND empty($where)){
		$vis.=" top_table=\"$main\" AND top_id=$id";
		$toa="&id=".$id;
		}
	elseif (empty($where)) {
		$table=$main;
		if (empty($top)) $top=0;
		$vis.=" top=$top";
		}
	else {
		$table=$main;
		if (empty($top)) $top=0;
		$vis.=$where;
		}
	if (empty($link)){
		$link="&top=".$top;
		}
	if (!empty($abc_list)){
		$abc_list1="&abc_list=".$abc_list;
		}


	$rec=row_select("id",$table,$vis);
	$kol=$rec->nr();

	if($kol>0){
		$a=(int)($kol/$kfp);
		$b=$kol%$kfp;
		$pagecount=$a+(($b>0)?1:0);

		$start=((($pg-$maxvis)>0)?($pg-$maxvis):1);
		$start0=((($pg-$maxvis-1)>0)?($pg-$maxvis-1):0);
		$end=((($pagecount-$pg)>$maxvis)?($pg+$maxvis):$pagecount);
		$end0=((($pagecount-$pg+1)>$maxvis)?($pg+$maxvis):$pagecount);

		if($pagecount>1){
			echo "<div class=".$style." nowrap><span class=pg_comment>".$comment."</span>";
			if ($start0!=0)echo "<a href='".$page."?main=".$main.$link.$toa.@$abc_list1."&pg=".$start0."'>&lt;&lt;</a>&nbsp;";
			for($i=$start;$i<=$end;$i++){
				$t_start=$kfp*($i-1)+1;
				$t_end=$kfp*($i);
				if($pg==$i){?>
					<span class='this' title="Страница <?=$i?>. Позиции с <?=$t_start?> по  <?=$t_end?>">&nbsp;<?=$i?>&nbsp;</span><?}
				else{?>
					<a href="<?=$page?>?main=<?=$main?><?=$link?><?=$toa?><?=@$abc_list1?>&pg=<?=$i?>" title="Страница <?=$i?>. Позиции с <?=$t_start?> по  <?=$t_end?>"><?=$i?></a>&nbsp;<?}
			}
			if ($end0!=$pagecount)echo "<a href='".$page."?main=".$main.$link.$toa.@$abc_list1."&pg=".$end0."'>&gt;&gt;</a>&nbsp;";?>
			</div><?
		}
	}
}}

# Проверяем наличие переменной и вставляем в ссылки
function lparam($pname="",$param="") {
	if($param!="") {
		return "&$pname=".$param;
	}
}


# Выдаем переменную из списка переменных в настройках сайта
function param($param="",$page="") {
	if ($page!="") $page="AND page=\"".$page."\"";
	DbgPrint("to s_select $page",0,"param");
	return s_select("content","admin_site","param=\"".$param."\" $page");
}



# ====================  ===========

# фиг знает
function max_count($table, $min=100001) {
	$res=mysql_query("select max(id) as count from $table");
	if (!$res || mysql_num_rows($res)<1) { return $min; exit;}
	$row=mysql_fetch_array($res);
	if (!isset($row["count"]))  { return $min; exit;}
	$count=$row["count"];
	if ($count==0) { return $min; exit;}
	$count++; return $count;}

# Упрощаем себе жизнь
class recordset {
var $res;
	function recordset($sql) {
		$this->res=mysql_query($sql);
		//if (!$this->res) echo $sql;
		}

	function gr() {
		$row=mysql_fetch_row($this->res);
		DbgPrint($this->res,0,"gr");
		return $row; }

	function ga() {
		@$row=mysql_fetch_array($this->res);
		DbgPrint($this->res,0,"ga");
		return $row; }

	function nr() {
		$num=mysql_num_rows($this->res);
		DbgPrint($this->res,0,"nr");
		return $num; }

	function ds($num) {
		$row=mysql_data_seek($this->res,$num);
		DbgPrint($this->res,0,"ds");
		return $row; }}

function DbgPrint($str,$format=0,$comment=""){
# Функция печатает отладочную информацию
# $str - переменная для вывода на экран
# $format=0 - просто текст, 1 - массив, 2 - Заголовок
global $dbg_listing;

	if(!empty($GLOBALS['dbg'])) {
	# Выводить на экран можно если установлен параметр отладки, например,  $dbg = 1
		$current_time=date("[H:i]");
		$current_date=date("d-m-Y");

		if($format==0) {
			$dbg_listing.="<br><b>[-FUNC-]: $comment </b><br><span class=small> $str</span><br>";
			}
		if($format==1) {
       	print_r($str)."<br>";
      	}
		if($format==2) {
			$dbg_listing.="<br><b>$current_time FUNCTION: $str<br></b>";
			}
		if ($GLOBALS['dbg']!=0 && mysql_errno()!=0 && $format!=1) {$dbg_listing.="<font color=red><b>".mysql_errno().": ".mysql_error()."</b></font><BR>";}
	}}

#===============  для забег.ру ===========================

# вычисляем дату последнего забега
function RunDate ($when="last"){

	$data[0]=s_select("data","ranning","data<\"".date("Y-m-d")."\"","data DESC");
	$data[1]=s_select("data","ranning","data=\"".date("Y-m-d")."\"");
	$data[2]=s_select("data","ranning","data>\"".date("Y-m-d")."\"","data");
	if ($when=="last"){
		if ($data[1]!=""){
			if ((date("H")*60+(date("i")))<=840){
				$data=$data[0];
			}
			else
				$data=$data[1];
		}
		else
			$data=$data[0];
	}
	if ($when=="next"){
		if ($data[1]!=""){
			if ((date("H")*60+(date("i")))<630){
				$data=$data[1];
			}
			else
				$data=$data[2];
		}
		else
			$data=$data[2];
	}
	if ($when=="stake"){
		if ($data[1]!=""){
			if ((date("H")*60+(date("i")))<720){
				$data=$data[1];
			}
			else
				$data=$data[2];
		}
		else
			$data=$data[2];
	}
	if ($when=="now"){
		if ($data[1]!=""){
			if ((date("H")*60+(date("i")))<840){
				$data=$data[1];
			}
			else
				$data=$data[2];
		}
		else
			$data=$data[2];
	}
	return $data;
}

?>