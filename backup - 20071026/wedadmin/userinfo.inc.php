<?php
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'auth.inc.php');
if (isset($_SESSION["WA_USER"])){
	include(WA_PATH.'header.inc.php');
	echo '<div align="center"><h1>WEDadmin 2.0</h1>';
	echo 'Добро пожаловать, '.$_SESSION["WA_USER"]["fullname"];
?>
<br>
<br>
<table border="0" cellpadding="3" cellspacing="0" class="menutable">
<?php 
if ($_SESSION["WA_USER"]["is_admin"]){
?>
  <tr>
    <td width="100"><a href="<?=WA_URL?>accounts.php"><img src="images/users.png" width="48" height="48" border="0"></a><br>
      <a href="<?=WA_URL?>accounts.php">Пользователи</a></td>
    <td width="100">&nbsp;</td>
    <td width="100">&nbsp;</td>
    <td width="100">&nbsp;</td>
    <td width="100">&nbsp;</td>
  </tr>
<?php
}
?>
  <tr>



    <td width="100">
&nbsp; </td>
    <td width="100">
&nbsp;</td>
    <td width="100">&nbsp;</td>
    <td width="100">&nbsp;</td>
  </tr>
  <tr>
    <!-- td width="100"><a href="<?=WA_URL?>catalogue.php"><img src="images/app_largeicons.png" width="48" height="48" border="0"></a><br>
    <a href="<?=WA_URL?>caltalogue.php">Каталог</a></td -->
    <td width="100">&nbsp;</td>
    <td>&nbsp;</td>
    <td width="100">&nbsp;</td>
  </tr>
</table>
<br>

<?php
echo '</div>';
include(WA_PATH.'footer.inc.php');
}
?>