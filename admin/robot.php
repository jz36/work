<? header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
clearstatcache();

# Система администрирования сайта v.18.11.04

require(SITE_ADMIN_DIR."/_adm_init.php"); 
require(SITE_ADMIN_DIR."/functions.php");

#Куча начальных переменных по дефолту
if (!isset($dbg)) $dbg=0;
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



#Блок авторизации

$res=row_select("","admin_users", "name=\"robot\" and password=password(\"robotrobotrobotrobot\")");
$r=$res->ga();
	session_register("user_name","user_pass");
	$_SESSION['user_name']="robot";
	$_SESSION['user_passw']="";
	$_SESSION['user_fio']=$r['fio'];
	$_SESSION['user_group']=$r['user_group'];
	$_SESSION['user_id']=$r['id'];
	$_SESSION['user_email']=$r['email'];
	
	if (isset($_SESSION["user_name"])) {
	require("".SITE_ADMIN_DIR."/_adm_fun.php");
	if(@$preview!=1)

#Выбор раздела и его вывод
	$main="cat_import";
	$res=row_select("name,page,shablon,id","admin_tree","page=\"".$main."\"");
	$r=$res->gr();
	if (!isset($top) || $top=="") {$top=0;}
	if (!isset($sub) || $sub=="") {$sub=1;}
	adm_print_top($r[0], $main);	
	require(SITE_ADMIN_DIR."/moduls/__virt/db_import.php");
	table_print_end();

#-------------------------

}

?>