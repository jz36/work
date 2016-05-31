<?php
if (!defined("NX_PATH")) define("NX_PATH", "../");
if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");
require_once(WA_PATH.'lib/global.lib.php');
require_once(WA_PATH.'config.inc.php');
require_once(WA_PATH.'lib/mysql.lib.php');
require_once(WA_PATH.'auth.inc.php');
if (isset($_SESSION["WA_USER"])){
db_open();

$filename = NX_PATH.'about.inc.php';
include(WA_PATH.'header.inc.php');

if (isset($_POST["html"])){
	$f = fopen($filename, 'w');
	fputs($f, $_POST["html"]);
	fclose($f);
}
		?>
		<script type="text/javascript">
		_editor_url = "<?=WA_URL?>htmlarea/";
		_editor_lang = "en";
		</script>
<script type="text/javascript" src="<?=WA_URL?>htmlarea/htmlarea.js"></script>
		<?php
		echo '<H1>О КОМПАНИИ: редактирование</H1>';
		echo '<FORM method="post" action="'.WA_URL.'html_about.php">';
		echo '<TEXTAREA style="width:550px;height:400px;" id="html" name="html" rows=15 cols=50>';
		$f = fopen($filename, 'r');
		while ($s = fgets($f)){
			echo $s;
		}
		fclose($f);
		echo '</TEXTAREA><BR/><BR/>';
		echo ' <div align="center"><INPUT type="submit" value="сохранить"></div><BR/><BR/>';

		echo '</FORM>';
		?>
		<script type="text/javascript" defer="1">
		var config = new HTMLArea.Config();
		config.height = '400px';
		config.width = '550px';
		HTMLArea.replace('html', config);
		</script>
		<?php
		include(WA_PATH.'footer.inc.php');
}
?>