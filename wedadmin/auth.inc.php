<?php
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'lib/auth.lib.php');
if (!isset($_SESSION["WA_USER"])){
	if (isset($_GET["auth"]) && isset($_POST["login"]) && isset($_POST["password"])){
		$login = addslashes($_POST["login"]);
		db_open();
		$query = mysql_query("SELECT * FROM `wed_accounts` WHERE `login` = '$login'");
		if (mysql_num_rows($query)){
			$acc_info = mysql_fetch_array($query);
			if (password_hash($_POST["password"], $acc_info["key"]) == $acc_info["hash"]){
				$_SESSION["WA_USER"] = $acc_info;
			}
		}
	}
}
if (isset($_GET["logout"])){
	if (isset($_SESSION["WA_USER"])) unset($_SESSION["WA_USER"]);
}
?>