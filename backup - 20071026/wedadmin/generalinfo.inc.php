<?php

if (!defined("WA_PATH")) define("WA_PATH", "./");

if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");

require_once(WA_PATH.'lib/global.lib.php');

require_once(WA_PATH.'config.inc.php');

require_once(WA_PATH.'lib/mysql.lib.php');



?>

<div class="lefttitle">General server information:</div>

<table width="160" border="0" cellspacing="0" cellpadding="3" class="generalinfo">

  <tr>

    <td>Operating system</td>

    <td><?=php_uname('s')?></td>

  </tr>

  <tr>

    <td>Kernel version</td>

    <td><?=php_uname('r')?></td>

  </tr>

  <tr>

    <td>Machine Type</td>

    <td><?=php_uname('m')?></td>

  </tr>

  <tr>

    <td colspan="2"></td>

  </tr>

  

  <tr>

    <td>Apache version</td>

    <td><?php if (preg_match("'^Apache\/([\d|\.]+ \([\w]+\))'i", $_SERVER['SERVER_SOFTWARE'], $s)) echo $s[1]; else echo 'unknown'?></td>

  </tr>

  <tr>

    <td>PHP version</td>

    <td><?=phpversion();?></td>

  </tr>

  <tr>

    <td>MySQL version</td>

    <td><?php db_open(); if (function_exists("mysql_get_server_info")) echo mysql_get_server_info(); else echo 'нет' ?></td>

  </tr>

  <tr>

    <td>WEDadmin Build</td>

    <td>2.0-RELEASE</td>

  </tr>

  <tr>

    <td></td>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

    <td>&nbsp;</td>

  </tr>

</table>

<br clear="all">

