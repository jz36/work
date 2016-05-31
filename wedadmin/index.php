<?php 
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'auth.inc.php');
if (!isset($_SESSION["WA_USER"])){
include(WA_PATH.'header.inc.php');
?>
<div align="center"><h1 align="center">бунд б оюмекэ сопюбкемхъ</h1><br>
<form method="POST" action="<?=WA_URL?>?auth">
<table width="342" border="0" cellpadding="3" cellspacing="0">
<tr>
<td width="150" align="left">кНЦХМ</td>
<td width="150" align="left">оЮПНКЭ</td>
<td width="24">&nbsp;</td>
</tr>
<tr>
<td><input name="login" type="text" class="logintext"></td>
<td><input name="password" type="password" class="logintext"></td>
<td><input type="image" src="<?=WA_URL?>images/key.png" width="24" height="24" border="0"></td>
</tr>
</table>
</form>
</div>
<?php 
include(WA_PATH.'footer.inc.php');
}else include(WA_PATH.'userinfo.inc.php');
?>