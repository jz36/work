<? header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
clearstatcache();

# Система администрирования сайта v.18.11.04

require(SITE_ADMIN_DIR."/_adm_init.php"); 
require(SITE_ADMIN_DIR."/functions.php");

#Куча начальных переменных по дефолту
if (empty($dbg)) $dbg=1;
if ($dbg==1) Error_Reporting(1+2+4+8);
$dbg_listing="";
$error="";
$delc="&rand=$rand&delcookie=1";
if (!empty($pg)) $delc.="&pg=$pg";
if (!isset($add) && !isset($id) && !isset($htmlcode) && !isset($edit) && !isset($edit) && !isset($preview)) $delcookie=1;
$change_stat[0]="";
if (!isset($main)) $main="admin_site";

session_name(PREF."user");
session_start();

#Коннектимся к базе
set_connection(); 


#Блок проверки наличия основных таблиц, и разворачивания сайта
@$tmp=s_select("name","admin_tree","1=1","","1");

if (mysql_errno()=="1046"){
	$db_error="db_error";
	$error="Не найдена база данных ".toup(DB_NAME);
}
if (mysql_errno()=="1146"){
	require("".SITE_ADMIN_DIR."/class/init_site.php");
	//echo "<BR>".mysql_errno().": ".mysql_error()."<BR>";
}

#Блок авторизации

//DbgPrint("name --".$HTTP_SESSION_VARS["user_name"]."pass --".$HTTP_SESSION_VARS["user_passw"],0,"Параметры сессии");
if ((!empty($HTTP_POST_VARS["user_name"]) && !empty($HTTP_POST_VARS["user_passw"])) && (empty($_SESSION['user_name']))) {
$res=row_select("","admin_users", "name=\"".$HTTP_POST_VARS['user_name']."\" and password=password(\"".$HTTP_POST_VARS['user_passw']."\")");
$r=$res->ga();
if ($HTTP_POST_VARS["user_name"]==$r["name"]) {
	session_register("user_name","user_pass");
	$_SESSION['user_name']=$HTTP_POST_VARS['user_name'];
	$_SESSION['user_passw']=$HTTP_POST_VARS['user_passw'];
	$_SESSION['user_fio']=$r['fio'];
	$_SESSION['user_group']=$r['user_group'];
	$_SESSION['user_id']=$r['id'];
	$_SESSION['user_email']=$r['email'];
	
}
}
if (!isset($HTTP_SESSION_VARS["user_name"])){
	# Вызываем входную страницу
	if ((isset($HTTP_POST_VARS["user_passw"])) || (isset($HTTP_POST_VARS["user_name"])))
		$error="Вы ввели неверный логин или пароль.";
	require("".SITE_ADMIN_DIR."/popup/auth.php");
	exit; }
	
#Конец блока авторизации

#Блок установки cookies
if (!isset($lim)) $lim=0;
if (!isset($main)) $main="";
if (isset($main) && $main=="exit") setcookie("admsession");
if (isset($adcookie)) {
	if (isset($name)) setcookie("cookie_name", $name, (time()+12000));
	if (isset($inputs)) {
		$cookie_inputs="";
		for ($i=0;$i<count($inputs);$i++) 
			$cookie_inputs.=$inputs[$i]."`";
			$cookie_inputs=substr($cookie_inputs, 0, strlen($cookie_inputs)-1);
			setcookie("cookie_inputs", $cookie_inputs, (time()+12000));}}
if (isset($delcookie)) {
	setcookie("cookie_name");unset($cookie_name);
	setcookie("cookie_inputs");unset($cookie_inputs);
	}
#Конец блока

if (isset($_SESSION["user_name"])) {
	require("".SITE_ADMIN_DIR."/_adm_fun.php");
	if(@$preview!=1)
	require("".SITE_ADMIN_DIR."/_adm_top.php");

#Выбор раздела и его вывод
if ($main!="" && $main!="exit") {
	$res=row_select("name,page,shablon,id","admin_tree","page='".$main."'");
	$r=$res->ga();
	if (!isset($top) || $top=="") {$top=0;}
	if (!isset($sub) || $sub=="") {$sub=1;}
	adm_print_top($r[0], $main);
	if (main_access()!=0 && $r[2]!="0"){	
		if (file_exists(SITE_ADMIN_DIR."/moduls/__".PREF."/$r[2].php"))
		require(SITE_ADMIN_DIR."/moduls/__".PREF."/$r[2].php");
		elseif (file_exists(SITE_ADMIN_DIR."/moduls/$r[2].php"))
		require(SITE_ADMIN_DIR."/moduls/$r[2].php");	
		else echo "Файл ".$r[2].".php не найден. Обратитесь к разработчику.";
		
		table_print_end();
	}
	
	if (main_access()!=0 && empty($r[2])){
		echo "<p><a href=?main=admin_tree&id=".$r[3]."&top_table=".$main."&rand=14496&delcookie=1>Выберите шаблон</a> для этого раздела!</p><br>";
	}

}

# Записываем всю изменения в таблицу логов
if ($change_stat[0]!="")
mysql_query("insert into ".PREF."_admin_change_stat (name, event_name, content,admin) values (\"".$_SESSION["user_id"]."\", \"$change_stat[0]\", \"$main#$change_stat[1]\", \"".date("Y-m-d")."#".date("H:i")."\") ");

#-------------------------

if ($main=="exit") {
unset($_SESSION['user_name']); 	
session_destroy();
?><script>location.href="<?=PAGE?>"</script><?
break;
}
require("".SITE_ADMIN_DIR."/_adm_bot.php");
}

?>