<?php
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("NX_PATH")) define("NX_PATH", "../");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'auth.inc.php');
if (!(isset($_SESSION["WA_USER"]) && $_SESSION["WA_USER"]["is_admin"])) include(WA_PATH.'index.php'); else{
	include(WA_PATH.'header.inc.php');
	db_open();
	$continue = true;
	//==================================================================================================================
	//------------------------------------------------------------------------------------------------------------------
	//
	//														DELETE
	//
	//------------------------------------------------------------------------------------------------------------------
	//==================================================================================================================

	if (isset($_GET["delete"])){
		$id = intval($_GET["delete"]);
		mysql_query("DELETE FROM `wed_accounts` WHERE `id` = '$id'");
	}
	//==================================================================================================================
	//------------------------------------------------------------------------------------------------------------------
	//
	//														UPDATE
	//
	//------------------------------------------------------------------------------------------------------------------
	//==================================================================================================================

	if (isset($_GET["update"])){
		if ($cat = get_row_by_id("wed_accounts", intval($_GET["update"]))){
			$login = addslashes($_POST["login"]);
			$fullname = addslashes($_POST["fullname"]);
			if (isset($_POST["isadmin"]) && ($_POST["isadmin"] == 1)) $isadmin = 1; else $isadmin = 0;
			if (isset($_POST["password"]) && $_POST["password"] !== ''){
				$password = $_POST["password"];
				$key = new_key();
				$hash = password_hash($password, $key);
				mysql_query("UPDATE `wed_accounts` SET `login`='$login', `fullname`='$fullname', `is_admin`='$isadmin', `key`='$key', `hash`='$hash' WHERE `id` = '".$cat["id"]."'");
			}else{
				mysql_query("UPDATE `wed_accounts` SET `login`='$login', `fullname`='$fullname', `is_admin`='$isadmin' WHERE `id` = '".$cat["id"]."'");
			}
		}
	}
	//==================================================================================================================
	//------------------------------------------------------------------------------------------------------------------
	//
	//														CREATE
	//
	//------------------------------------------------------------------------------------------------------------------
	//==================================================================================================================

	if (isset($_GET["create"])){
		//echo 'Matched.';
		$login = addslashes($_POST["login"]);
		$password = $_POST["password"];
		$key = new_key();
		$hash = password_hash($password, $key);
		$fullname = addslashes($_POST["fullname"]);
		if (isset($_POST["isadmin"]) && ($_POST["isadmin"] == 1)) $isadmin = 1; else $isadmin = 0;
		mysql_query("INSERT INTO `wed_accounts` (`login`, `key`, `hash`, `fullname`, `is_admin`) VALUES ('$login', '$key', '$hash', '$fullname', '$isadmin')");
		//echo mysql_error();
	}

	//==================================================================================================================
	//------------------------------------------------------------------------------------------------------------------
	//
	//														EDIT
	//
	//------------------------------------------------------------------------------------------------------------------
	//==================================================================================================================

	if (isset($_GET["edit"])){
		if ($cat = get_row_by_id("wed_accounts", intval($_GET["edit"]))){
			echo '<H1>ACCOUNTS: edit</H1>';
			echo  '<FORM method="post" action="'.WA_URL.'accounts.php?update='.$cat["id"].'">';
			echo 'Логин:<BR/><INPUT type="text" name="login" value="'.htmlspecialchars($cat["login"]).'"><BR/><BR/>';
			echo 'Новый пароль (необязательно):<BR/><INPUT type="password" name="password" value=""><BR/><BR/>';
			echo 'Полное имя:<BR/><INPUT type="text" name="fullname" value="'.htmlspecialchars($cat["fullname"]).'"><BR/><BR/>';
			echo 'Администратор (право доступа к аккаунтам):<BR/><INPUT type="checkbox" name="isadmin" value="1"';
			if ($cat["is_admin"]) echo ' checked';
			echo '><BR/><BR/>';
			echo '<INPUT type="submit" value="сохранить"><BR/><BR/>';
			echo '</FORM>';
			$continue = false;
		}
	}

	//==================================================================================================================
	//------------------------------------------------------------------------------------------------------------------
	//
	//														ADD
	//
	//------------------------------------------------------------------------------------------------------------------
	//==================================================================================================================

	if (isset($_GET["add"])){
		echo '<H1>ACCOUNTS: add</H1>';
		echo  '<FORM method="post" action="'.WA_URL.'accounts.php?create">';
		echo 'Логин:<BR/><INPUT type="text" name="login" value=""><BR/><BR/>';
		echo 'Пароль:<BR/><INPUT type="password" name="password" value=""><BR/><BR/>';
		echo 'Полное имя:<BR/><INPUT type="text" name="fullname" value=""><BR/><BR/>';
		echo 'Администратор (право доступа к аккаунтам):<BR/><INPUT type="checkbox" name="isadmin" value="1"><BR/><BR/>';
		echo '<INPUT type="submit" value="сохранить"><BR/><BR/>';
		echo '</FORM>';
		$continue = false;
	}

	//==================================================================================================================
	//------------------------------------------------------------------------------------------------------------------
	//
	//														PRIMARY
	//
	//------------------------------------------------------------------------------------------------------------------
	//==================================================================================================================
	if ($continue){
		echo '<H1>ПОЛЬЗОВАТЕЛИ</H1>';

		$query = mysql_query("SELECT * FROM `wed_accounts`");
		$r = 0;
		if (mysql_num_rows($query)){
			echo '<TABLE border="0" cellspacing="1" cellpadding="5" bgcolor="#666666">';
			echo '<TR bgcolor="#cccccc"><TD>Логин</TD><TD>Имя</TD><TD>Права</TD><TD>Действия</TD></TR>';
			while ($account = mysql_fetch_array($query)){
				$r++;
				if ($r > 1) $r = 0;
				echo '<TR class="r'.$r.'"><TD>'.htmlspecialchars($account["login"]).'</TD><TD>'.htmlspecialchars($account["fullname"]).'</TD><TD>';
				if ($account["is_admin"]) echo 'администратор'; else echo 'менеджер';
				echo '</TD>';
				echo '<TD><A href="'.WA_URL.'accounts.php?edit='.$account["id"].'">изменить</A>&nbsp;&nbsp;<A href="'.WA_URL.'accounts.php?delete='.$account["id"].'">удалить</A></TD>';
				echo '</TR>';
			}
			echo '</TABLE><BR/><BR/>';
			if (TRUE){ // if admin
			echo '<A href="'.WA_URL.'accounts.php?add">новый пользователь</A>';
			}
		}else{
			echo '<A href="'.WA_URL.'accounts.php?add">add first, superuser account</A>';
		}
	}
}
include(WA_PATH.'footer.inc.php');
?>