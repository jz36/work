<?

$rand=rand(0,32000);

function set_connection() {
	$conn=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("Can't connect with database!"); ;
	mysql_select_db(DB_NAME, $conn);
	
	//mysql_query("SET character_set_client = CP1251_general_ci");
	//mysql_query("SET character_set_results = CP1251_general_ci");
	//mysql_query("SET character_set_connection = CP1251_general_ci");

	}

# ==================== ��������� ������� � ������ ===========

# ����� ��� �������� ����� � ���� � �������� (��� ����������� ������)
require("".SITE_ADMIN_DIR."/function/func_email.php");




# ==================== ������ � ������� ===========

# ������� ����
function del_file($rissrc) {
	$rissrc=to_lat($rissrc,"_");
	chdir("files"); $dir=opendir(".");
	while ($strfile=readdir($dir)) {
	if (@ereg($rissrc, $strfile)) {unlink($strfile);} }
	chdir("..");$dir=opendir("."); }

# ��������� �� ������ ����
function load_file($file, $target, $name_file, $folder="files") {
	del_file($target);
	$name_file=to_lat($name_file,"_");
	if (strlen($name_file)>0) {
		$im_ris=split("\.", $name_file);
		$imm=$im_ris[(count($im_ris)-1)];
		$src=$folder."/".$target.".".$imm;
		copy($file, $src); return $imm;
	}}

# ��������� ������������� �����
function test_file($src) {
	$src=to_lat($src,"_");
	chdir("files"); $dir=opendir(".");
	while ($strfile=readdir($dir)) {
		if (@ereg($src, $strfile)) $flag=$strfile;
	}
	chdir("..");$dir=opendir(".");
	if (isset($flag)) return $flag; else return false;}

# ���������� ��� ����� (�� ����������), � ������� �������� ���������������
# �� ��������� ������ ����, ����� �������� ����.
function test_file_ext($name="",$type="",$ext="") {

if (empty($ext)) $ext=substr(test_file($name),-3);
switch (strtolower($ext)) {

	case "zip":
	case "rar":
	case "arj":		$src="".SITE_ADMIN_DIR."/img/file-zip.gif";		$alt="���� � ������ ".$ext;			break;
	case "xls":		$src="".SITE_ADMIN_DIR."/img/file-excel.gif";		$alt="���� � ������� MS Excel";		break;
	case "doc":		$src="".SITE_ADMIN_DIR."/img/file-word.gif";		$alt="���� � ������� MS Word";		break;
	case "txt":		$src="".SITE_ADMIN_DIR."/img/file-txt.gif";		$alt="��������� ����";					break;
	case "avi":		$src="".SITE_ADMIN_DIR."/img/file-video.gif";		$alt="�����-���� � ������� ".$ext;	break;
	case "mp3":
	case "wav":
	case "mid":		$src="".SITE_ADMIN_DIR."/img/file-audio.gif";		$alt="�����-���� � ������� ".$ext;	break;
	case "htm":
	case "html":	$src="".SITE_ADMIN_DIR."/img/file-htm.gif";		$alt="���� � ������� ".$ext;			break;
	case "jpg":
	case "gif":
	case "png":
	case "bmp":		$src="".SITE_ADMIN_DIR."/img/file-img.gif";		$alt="����������� � ������� ".$ext;	break;
	default:			$src="".SITE_ADMIN_DIR."/img/file-un.gif";			$alt="���� � ������� ".$ext;			break;
	}
if ($type=="") return $src;
if ($type=="alt") return $alt;
}

# ��� ���������
function del_temp_file() {
	$dir=opendir("./".SITE_FILES_TMP_DIR);
	while ($strfile=readdir($dir)) {
		if (ereg("temp", $strfile)) unlink("./".SITE_FILES_TMP_DIR."/".$strfile);
	}}

# ��� ���������
function show_temp_file() {
	$dir=opendir("./".SITE_FILES_TMP_DIR);
	while ($strfile=readdir($dir)) {
		if (ereg("temp", $strfile)) $flag=SITE_FILES_TMP_DIR."/".$strfile;
	}
	if (isset($flag)) return $flag; else return false;}

# ==================== ������ � ���������� ===========
if (!defined('IMG_KAT')) define("IMG_KAT","img/kat");
if (!defined('LANG')) define("LANG","rus");

# ������� �������
function del_ris($rissrc) {
	chdir(IMG_KAT);
	$dir=opendir(".");
	while ($strfile=readdir($dir)) {
		if (ereg($rissrc, $strfile)) unlink($strfile); }
		chdir("../..");$dir=opendir("."); }

# ��������� �� ������ �������
function load_image($textris, $imgname, $prefix, $id, $numb) {
	del_ris($prefix.$id.$numb);
	if (strlen($textris)>0) {
	$im_ris=split("\.", $textris);
	$imm=$im_ris[(count($im_ris)-1)];
	$src=IMG_KAT."/".$prefix.$id.$numb.".".$imm;
	copy($imgname, $src); return $imm;}}

# ��������� ������������� ��������
function test_ris($src,$kat=IMG_KAT,$chdir="../..") {
	chdir($kat);
	$dir=opendir(".");
	while ($strfile=readdir($dir)) {
		if (ereg($src, $strfile)) $flag=$strfile;
	}
	chdir($chdir);$dir=opendir(".");
	if (isset($flag)) return $flag; else return false;}

#  ��������� ������� ��������, ���� ��� ��, �� ������ ������ ���
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

# ���������� ���� ��� �������� �������� � ����� ����
# ����� ���������� ������ ������, � ����� � ���� ����������
function popupimg($pref,$idnum,$ended="0",$i=0){
$razm=getimagesize(getimg($pref,$idnum,$ended));
$razm[1]+=20;
if ($razm[0]<=300) $razm[1]+=20;
	if ($i==0)
		$path="popup.php?file=photo.php&pref=".$pref."&id=".$idnum."&ended=".$ended."";
	if ($i==1)
		$path="javascript:void(0);' onClick='window.open(\"popup.php?file=photo.php&pref=".$pref."&id=".$idnum."&ended=".$ended."\",\"_blank\",\"left=50,top=50,width=$razm[0],height=$razm[1],scrollbars=no\"); return false";
	return $path;}

# ���������, ����� �� ���� ���������� ��������, �� �����, ���������, ����� ��� adm_fun
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


# ==================== ������ � ������ ===========

# ����������� ���� � ������ ��������� ����
function date_preobr($date, $ind=0) {
	$date1=substr($date, 8, 2); $date21=substr($date, 5, 2); $date3=substr($date, 0, 4);
	if ($ind==0) $data=$date1.".".$date21.".".$date3;
	elseif ($ind==1) $data=$date1.".".$date21.".".substr($date3, 2, strlen($date3)-2);
	elseif ($ind==2) {
		if (LANG=="rus"){
			switch ($date21) {
				case "01":$date21="������";break;
				case "02":$date21="�������";break;
				case "03":$date21="�����";break;
				case "04":$date21="������";break;
				case "05":$date21="���";break;
				case "06":$date21="����";break;
				case "07":$date21="����";break;
				case "08":$date21="�������";break;
				case "09":$date21="��������";break;
				case "10":$date21="�������";break;
				case "11":$date21="������";break;
				case "12":$date21="�������";break;
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
			case "������"	:$date21="01";break;
			case "�������"	:$date21="02";break;
			case "�����"	:$date21="03";break;
			case "������"	:$date21="04";break;
			case "���"		:$date21="05";break;
			case "����"		:$date21="06";break;
			case "����"		:$date21="07";break;
			case "�������"	:$date21="08";break;
			case "��������":$date21="09";break;
			case "�������"	:$date21="10";break;
			case "������"	:$date21="11";break;
			case "�������"	:$date21="12";break;
		}
		$date[1]=$date21;
		$date[0]=fixDate($date[0]);
		$data=$date[2]."-".$date[1]."-".$date[0];
	}
	return $data;}

# ����������� �������� ��� ������ � ������� ����
function day_rus($day, $ind=0) {
	if ($ind==0){
	switch ($day) {
		case "1":case "Monday":		$day="�����������";break;
		case "2":case "Tuesday":	$day="�������";break;
		case "3":case "Wednesday":	$day="�����";break;
		case "4":case "Thursday":	$day="�������";break;
		case "5":case "Friday":		$day="�������";break;
		case "6":case "Saturday":	$day="�������";break;
		case "7":case "0":case "Sunday":		$day="�����������";break;
	}}
	if ($ind==1){
	switch ($day) {
		case "1":case "Monday":		$day="��";break;
		case "2":case "Tuesday":	$day="��";break;
		case "3":case "Wednesday":	$day="��";break;
		case "4":case "Thursday":	$day="��";break;
		case "5":case "Friday":		$day="��";break;
		case "6":case "Saturday":	$day="��";break;
		case "7":case "0":case "Sunday":		$day="��";break;
	}}
	return $day;}

# ����������� ���� � ������ ��� ���� 0000-00-00
function date_kod($date) {
	//echo $date."asdfasdfasfd";
	$part=split("\.", $date);
	if (strlen($part[2])==2) $part[2]="20".$part[2];
	if (strlen($part[1])==1) $part[1]="0".$part[1];
	if (strlen($part[0])==1) $part[0]="0".$part[0];
	$newstr=$part[2]."-".$part[1]."-".$part[0];
	return $newstr; }

# ��������� ����� � ����, ���� ��� ���� �����
function fixDate($int){
	$int = ($int<10)?str_replace("0", "", $int):$int;
	$int = ($int<10)?"0$int":$int;
	return $int; }

# ������������ ���� � ��� ����� �����
function remakedata($st){
	$a=fixDate(substr($st,8,2));
	$b=substr($st,5,2);
	$c=substr($st,2,2);
	$d=$a.".".$b.".".$c;
	return $d;}

# ==================== ������ � �������, ������� ===========

# ������� ������ �� �����
function del_end_space($str) {
	$newstr="";$flag=0;
	for ($i=(strlen($str)-1);$i>=0;$i--) {
	$simv=substr($str, $i,1);
	if ($simv!=" ") {$flag=1;$newstr=$simv.$newstr;}
	else {if ($flag>0) $newstr=$simv.$newstr;}}
	return $newstr;}

# �������� enter � ������ ������ �� br, � ��������
function change_enter($str, $ind=0) {
	if ($ind==0) {
	$str=str_replace(chr(13), "<br>", $str);
	$str=str_replace(chr(10), "", $str);}
	else $str=str_replace("<br>", chr(13).chr(10), $str);
	return $str; }

# �������� ������� �� �����, ����� ��� ������� �����
function change_zap($str) {
	$newstr="";
	for ($i=0;$i<strlen($str);$i++) {
	$simv=substr($str,$i,1); if ($simv==",") $newstr.="."; else $newstr.=$simv;}
	return $newstr;}

# ����� �� ���� ������� �������
function addquotes($str) {
	$newstr="";
	for ($i=0;$i<strlen($str);$i++) {
	$simv=substr($str,$i,1); if ($simv=="\"") $newstr.="&quot;"; else $newstr.=$simv;}
	return $newstr;}

# ������� �������
function del_quotes($str) {
	$newstr=str_replace("\"", "&quot;", $str);return $newstr;}

# ������� �����
	function delslashes($stroka) {
	$stroka=str_replace("\\", "", $stroka);
	return $stroka; }

# ������� ������� �����
function deldoubleslashes($stroka) {
	$newstr="";
	for ($i=0; $i<strlen($stroka); $i++) {
	$simv=substr($stroka, $i, 1);
	if ($simv=="\\" && (substr($stroka, ($i+1), 1)=="\"" || substr($stroka, ($i+1), 1)=="\\")) {}
	else $newstr.=$simv; }
	return $newstr; }

# ������������ ������
function test_email($email) {
	if (!ereg("^.+@.+\\.[a-zA-z]+$", $email) || strlen($email)<7  )  return false; else return true; }

# ������������ ������
function test_password($str) {
	if (!ereg("^[a-zA-Z0-9]+$", $str) || strlen($str)<7  )  return false; else return true; }

# ��������� ������������� �������� � ������
function test_empty($str) {
	if (ereg("^ +$", $str) || $str=="") return false;
	else return true; }

# �������� ����
function del_tags($str) {
	$newstr="";
	$arr1=split("<", $str);
	for ($i=0;$i<count($arr1);$i++) {$arr2=split(">", $arr1[$i]);
	if (isset($arr2[1])) $newstr.=$arr2[1]; else $newstr.=$arr2[0];}
	return $newstr; }

# ������� ��������� �������
function del_single_space($str) {$newstr="";
	for ($i=0;$i<strlen($str);$i++) {$simv=substr($str, $i, 1);
	if ($simv==" " && $i<(strlen($str)-1) && substr($str, $i+1, 1)==" ") {}
	else $newstr.=$simv;}
	if (substr($newstr, strlen($newstr)-1, 1)==chr(32) || substr($newstr, strlen($newstr)-1, 1)==chr(13) || substr($newstr, strlen($str)-1, 1)==chr(9)) $newstr=substr($newstr, 0, strlen($newstr)-1);
	if (substr($newstr, 0, 1)==chr(32) || substr($newstr, 0, 1)==chr(13) || substr($newstr, 0, 1)==chr(9)) $newstr=substr($newstr, 1, strlen($newstr)-1);
	return $newstr; }

# ����� �� �������� ����� ����� ������, ������� ��� ������
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

# �������� ������������ ������
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

# ����� ������ �� ��������
function email_echo($email,$string="",$class=""){

	if ($class!="") $class="class=".$class;
	if ($string=="") $string=$email;
	if (check_email(trim($email))!=false)
		$string="<a href='mailto:".$email."' ".$class.">".$string."</a>";
	return $string;

}

# �������� ������������� �������� �� ���� �� �������
function email_echo1($name,$error_name,$strip_tag="1",$q_replace="1",$strlen="0"){

	if ($class!="") $class="class=".$class;
	if ($email!="") $string="<a href='mailto:".$email."' ".$class.">".$string."</a>";
	echo $string;

}
# ��������� � ��������� ������� �����
function to_lat($str,$space=" ") {
$newstr="";
for ($i=0;$i<strlen($str);$i++) {
$simv=substr($str,$i,1);
switch ($simv) {
case " ": $newstr.=$space;break;
case "�": $newstr.="a";break;case "�": $newstr.="b";break;case "�": $newstr.="v";break;
case "�": $newstr.="g";break; case "�": $newstr.="d";break;case "�": $newstr.="e";break;
case "�": $newstr.="zh";break;case "�": $newstr.="z";break;case "�": $newstr.="i";break;
case "�": $newstr.="k";break;case "�": $newstr.="l";break;case "�": $newstr.="m";break;
case "�": $newstr.="n";break;case "�": $newstr.="o";break;case "�": $newstr.="p";break;
case "�": $newstr.="r";break;case "�": $newstr.="s";break;case "�": $newstr.="t";break;
case "�": $newstr.="u";break;case "�": $newstr.="f";break;case "�": $newstr.="h";break;
case "�": $newstr.="c";break;case "�": $newstr.="y";break;case "�": $newstr.="ch";break;
case "�": $newstr.="sh";break;case "�": $newstr.="shy";break;case "�": $newstr.="'";break;
case "�": $newstr.="i";break;case "�": $newstr.="'";break;case "�": $newstr.="e";break;
case "�": $newstr.="yu";break;case "�": $newstr.="ya";break;
case "�": $newstr.="A";break;case "�": $newstr.="B";break;case "�": $newstr.="V";break;
case "�": $newstr.="G";break; case "�": $newstr.="D";break;case "�": $newstr.="E";break;
case "�": $newstr.="Zh";break;case "�": $newstr.="Z";break;case "�": $newstr.="I";break;
case "�": $newstr.="K";break;case "�": $newstr.="L";break;case "�": $newstr.="M";break;
case "�": $newstr.="N";break;case "�": $newstr.="O";break;case "�": $newstr.="P";break;
case "�": $newstr.="R";break;case "�": $newstr.="S";break;case "�": $newstr.="T";break;
case "�": $newstr.="U";break;case "�": $newstr.="F";break;case "�": $newstr.="H";break;
case "�": $newstr.="C";break;case "�": $newstr.="Y";break;case "�": $newstr.="Ch";break;
case "�": $newstr.="Sh";break;case "�": $newstr.="Shy";break;case "�": $newstr.="'";break;
case "�": $newstr.="I";break;case "�": $newstr.="'";break;case "�": $newstr.="E";break;
case "�": $newstr.="Yu";break;case "�": $newstr.="Ya";break;
default: $newstr.=$simv;}}
return $newstr;}


# ������ ��� ����� ��������, ���������� ������������ ��-�� ������� ������ � �������� ������� ����������� �������
function toup($str) {
$newstr="";
for ($i=0;$i<strlen($str);$i++) {
$simv=substr($str,$i,1);
switch ($simv) {
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break; case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;
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

# ������ ��� ����� ����������, ���������� ������������ ��-�� ������� ������ � �������� ������� ����������� �������
function todown($str) {
$newstr="";
for ($i=0;$i<strlen($str);$i++) {
$simv=substr($str,$i,1);
switch ($simv) {
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;case "�": $newstr.="�";break;
case "�": $newstr.="�";break;case "�": $newstr.="�";break;                     
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

# ������ ������ ����� �������
function first_toup($str) {
if (strlen($str)<1) return "";else {
$str=toup(substr($str,0,1)).substr($str,1,strlen($str)-1);
 $str;}}


# ��������� ����� �� ���������� ��������, �� ������� ��� ����������� (�������� �����)
function divide_text($text="",$kolvo=100,$start=0,$div=".") {
	$pos = strpos(substr($text,$kolvo),$div);	
	$text = substr($text, $start, ($kolvo+$pos)).$div;
	return $text;
}

# ���������� ����� �� ��� ��������� � ������
function win2uni($s)
  {
    // �������������� win1251 -> iso8859-5
    $s = convert_cyr_string($s,'w','i'); 
    // �������������� iso8859-5 -> unicode:
    for ($result='', $i=0; $i<strlen($s); $i++) {
      $charcode = ord($s[$i]);
      $result .= ($charcode>175)?"&#".(1040+($charcode-176)).";":$s[$i];
    }
    return $result;
  }
# ==================== ������� � ����, ������ � ������ � ����������, ���������� ���. ������ ===========

# ������� ���������� ����������
function print_abc_list($table,$rus=1,$eng=1,$title="���������� ���������"){
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
	echo "| <a href='?main=".$main."&top=".@$top."'>���<a/></div>";

	}

# ������� ��������� �� ������ � ������ �������
function print_this_search($table="",$column="name",$title=""){global $main;$i=0;global $id;global $top;$dop="";
	global $main;$i=0;global $id;global $top;$dop="";
	echo "<div class=this_search>";
	?>
	<form action="<?=PAGE?>" method="get">
	<input type="hidden" name="main" value="<?=$main?>" >
	<input type="hidden" name="main1" value="<?=$GLOBALS["QUERY_STRING"]?>" >

	<input id=ft name="search_text" type="text" value="<?=$title?>" onFocus="if(this.form.search_text.value=='<?=$title?>') this.form.search_text.value=''" onBlur="if(this.form.search_text.value=='') this.form.search_text.value='<?=$title?>'" style="width:120px">

	<input type="submit" name="Submit" value="��" onClick="if(ft.value=='<?=$title?>') {alert('������� ������ ��� ������');return false;}">
	<?echo "</form></div>";

	}

# ������� ������ �������� ����� ������, � ���� ������ ��� ������� � ������ ����������� ��������
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
	// ������� ���������� ��������
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
	// ������� ���������� �������
	if ($view=="list"){?>
		<ul><?
		while($r=$res->ga()){?>
			<li><a href='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1;?>><?=$r[0]?></a></li><?
		}?>
		</ul><?
	}
	// ������� ���������� ������, ����� �����������
	if ($view=="text"){?>
		<div><?
		while($r=$res->ga()){?>
			<nobr>
			<?if (@$i==1) echo " | "; else $i=1;?><a href='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1;?>><?=$r[0]?></a></nobr><?
		}?>
		</div><?
	}
	// ������� ���������� ���������� �������
	if ($view=="select"){?>
		<select onChange="jmpMenu('parent',this,0)" <?if (!empty($table_cols)) echo "style='width:".$table_cols."'";?>>
		<?if ($table_cols==0) {?><option value="#">... <?=$table_class?></option><? $this1="";}
		while($r=$res->ga()){?>
			<option value='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1."> -> "; else echo ">"?><?=$r[0]?></option><?
		}?>
		</select><?
	}
	// ������� ���������� ���������  � �������� ������ ���� �� ������
	if ($view=="all"){?>
		<div><?
		while($r=$res->ga()){?>
			<h3><?=$r[0]?></h3>
			<div><?=$r[2]?></div><?
		}?>
		</div><?
	}
	// ������� ���������� � �������� ������ ���� �� ������
	if ($view=="anonse"){?>
		<table class=tableno><?
		while($r=$res->ga()){?>
			<tr><td valign=top align=right><a href='index.php?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'><img src=<?=getimg($table,$r[1],"logo")?> align=left alt='<?=$r[0]?>' vspace=10 hspace=4 class=borderno></a></td>
			<td valign=top><a href='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1;?>><h3><?=$r[0]?></h3></a>
			<?if (!empty($r["content"])) {?><div><?=(divide_text(strip_tags($r[2]),100,0,"."))?> <a href='<?SPAGE?>?main=<?=$table?>&id=<?=$r[1]?><?=$dop?>'<?if ($id==$r[1]) echo $this1;?>>�����</a></div><?}?>
			</td></tr><?
		}?>
		</table><?
	}





}


#����������� ���������� ���������� � ������� ���� � ��� ������� (������� � ������ / ������� � �������)
function get_forms($name="",$table_form="",$where="",$table_info=""){

	#���� �� ������ $id, ����� ���������� $id �� table_form
	if ($table_info=""){
		return s_select("id",$table_form,"name=\"$name\"");
		}
	#���� ������ $id, ����� ���������� ���������� �������� �� table_info
	else {
		$id_from=s_select("id",$table_form,"name=\"$name\"");
		return s_select("i".$id_from,$table_info,$where);
		}
}

# ��������� ������� �����������, ���������� �� ��������� ����� �����������
# ���� $isnow=1 �� ����������, ��������� ��� ��� �������, ��������� ��� � ���������� level_in ��� ����� �������
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

# ��������� ������������ ������, � ������� ��������� �������...
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

# ��������� ������ �� ���������� --- ��� ������� / ������� / ������� / �������
# $type - ��������� �������� ������ � ������ ���������
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

# ������� ������ �� ������� � �������
function s_delete($table="", $where="") {
global $id;global $main;

	if ($table=="") $table=$main;

	$rec=mysql_query("delete from ".PREF."_$table where 1=1 and $where");
	DbgPrint("delete from ".PREF."_$table where 1=1 and $where",0,"s_delete");
	}

# ��������� � ���� ����� ������ �� �������, ������� � ��������
function s_insert($table, $into, $values) {
global $id;global $main;

	$parts=split("_",$table);
	if (ereg("_",PREF) AND !empty($parts[1])) $parts[0]=@$parts[0]."_".@$parts[1];
	if($parts[0]!=PREF && $table!="") $table=PREF."_".$table;
	if ($table=="") $table=PREF."_".$main;

	$rec=mysql_query("INSERT INTO $table ($into) VALUES ($values)");
	DbgPrint("INSERT INTO $table ($into) VALUES ($values)",0,"s_insert");
	}

# ���������� � ���� �������� �� �������, ������� � ��������
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

# ���������� �������� ������ ������� �� ������� � �������
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

# ���������� id ������ �������� �������� � top=0
function show_ttop($id, $table,$ttop="top") {
	$top=$id;
	while ($top>0) {
		$rec=row_select("id,".$ttop,$table,"id='".$top."'");
		$row=$rec->ga();
		$top=$row[$ttop];
		//echo $top.$row["id"];
	}
	return $row["id"];}

# ��������� ������ ��������� ��� ������� � ������� �������
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


# ��������� ������ � ������� �����
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

# ������������ ������ ���������
function nav_line($table,$id_start,$razd=" &gt; ",$all_tables=0,$echo=0,$start=0,$this_need=1,$link_need=1,$link_class="",$is_admin=0,$start_name="�������") {
global $main;global $id;global $rand;$navline="";
if (LANG=="rus") {
	$lng["home"]="�������";
}
if (LANG=="eng") {
	$lng["home"]="Home";
}
if ($start_name=="�������") $start_name=$lng["home"];



	# ���� ��� ��� ����� , �� ��������� ��� ���������
	if ($is_admin==1) $dop_par="";
	if ($is_admin==0) $dop_par="&rand=$rand&delcookie=1";

	# �������� ������ � ������ �������
	if($id_start!=""){
		$tid=$id_start;
		while($tid!=0){
			$res=row_select("top,name",$table,"id=$tid");
			$r=$res->ga();
			# ���� ������ ������
			if ($this_need==1 && $tid==$id_start)
				$navline="<span class='$link_class'>".$r["name"]."</span>".$navline;
			# ��� ��������� �������
			if ($tid!=$id_start)  {
				$temp=$r["name"];
				# ���� ����� ������
				if ($link_need==1) $temp="<a href='?main=$table&id=$tid".$dop_par."' class='$link_class'>".$temp."</a>";
				$navline=$temp.$razd.$navline;
				}
			$tid=$r["top"];
		}}

		# �������� ������ ����� ���� ��������
		$res=row_select("id","admin_tree","page='".$table."'");
		$r=$res->ga();
		$tid=$r["id"];
		while($tid!=0){
			$res=row_select("menu_top,name,page","admin_tree","id=$tid");
			$r=$res->ga();
			$temp=$r["name"];
			$r2=$r["page"];
			# ���� ����� ������
			if ($link_need==1) $temp="<a href='?main=".$r2."".$dop_par."' class='$link_class'>".$temp."</a>";
			if ($r["name"]!=$start_name) $navline=$temp.$razd.$navline;
			$tid=$r["menu_top"];
			if ($all_tables==0) $tid=0;
		}

	# ���� ��� ��� ����� , �� ��������� ��� ���������



	# ��������� ����� ������� �������
		if (($start==1 && $main!="main"))
		$navline="<a href='?".$dop_par."' class='$link_class'>$start_name</a>".$razd.$navline;


	# �������, ����� ���������
	if($echo==0) return $navline;
	if($echo==1) echo $navline;

}


# ==================== ������ ===========

# ������� (���� �����) ������ �� ������ � ������������ � ������� �������/������/��������
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
			$mark="<span class='$class'>������[$mark]</span> ";
			$title.="�������,";
		}
		if ($need_comment==1){
			$res=row_select("id","all_feedback","top_table='$table' AND top_id='$tid' AND name!='' and visible=1");
			$num=$res->nr();
			if ($num!=0) {$comm=$num;$class="";}
			else {$comm=0; $class="gray";}
			$comm="<span class='$class'>�����������[$comm]</span>";
			$title.="����������/�������� �����������";
		}
		?><br><a href='javascript:void(0);' class=small title='<?=$title?>' onclick ="window.open('popup.php?file=all_feedback.php&top_table=<?=$table?>&top_id=<?=$tid?>&need_mark=<?=$need_mark?>&need_comment=<?=$need_comment?>','_blank','left=200,top=250,width=500,height=450,scrollbars=yes,resizable');"><?=$mark." ".$comm?></a><?
	}

}


# �������� � ���������� ����� � mod_rewrite
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

# ��������� ������������ �������
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
					<span class='this' title="�������� <?=$i?>. ������� � <?=$t_start?> ��  <?=$t_end?>">&nbsp;<?=$i?>&nbsp;</span><?}
				else{?>
					<a href="<?=$page?>?main=<?=$main?><?=$link?><?=$toa?><?=@$abc_list1?>&pg=<?=$i?>" title="�������� <?=$i?>. ������� � <?=$t_start?> ��  <?=$t_end?>"><?=$i?></a>&nbsp;<?}
			}
			if ($end0!=$pagecount)echo "<a href='".$page."?main=".$main.$link.$toa.@$abc_list1."&pg=".$end0."'>&gt;&gt;</a>&nbsp;";?>
			</div><?
		}
	}
}}

# ��������� ������� ���������� � ��������� � ������
function lparam($pname="",$param="") {
	if($param!="") {
		return "&$pname=".$param;
	}
}


# ������ ���������� �� ������ ���������� � ���������� �����
function param($param="",$page="") {
	if ($page!="") $page="AND page=\"".$page."\"";
	DbgPrint("to s_select $page",0,"param");
	return s_select("content","admin_site","param=\"".$param."\" $page");
}



# ====================  ===========

# ��� �����
function max_count($table, $min=100001) {
	$res=mysql_query("select max(id) as count from $table");
	if (!$res || mysql_num_rows($res)<1) { return $min; exit;}
	$row=mysql_fetch_array($res);
	if (!isset($row["count"]))  { return $min; exit;}
	$count=$row["count"];
	if ($count==0) { return $min; exit;}
	$count++; return $count;}

# �������� ���� �����
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
# ������� �������� ���������� ����������
# $str - ���������� ��� ������ �� �����
# $format=0 - ������ �����, 1 - ������, 2 - ���������
global $dbg_listing;

	if(!empty($GLOBALS['dbg'])) {
	# �������� �� ����� ����� ���� ���������� �������� �������, ��������,  $dbg = 1
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

#===============  ��� �����.�� ===========================

# ��������� ���� ���������� ������
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