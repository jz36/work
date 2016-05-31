<?

//define ("PATH", "http://britannix/");
define ("PATH", "http://britannix.ru/");

#$parts=split("tp://britannix.ru/", PATH);
if ($parts[1]) {
	define ("DB_HOST", "localhost");
	define ("DB_NAME", "britannixru");
	define ("DB_USER", "root");
	define ("DB_PASSWORD", "hgnnn43");
	define ("SITE_ADMIN_DIR", "../_admin");
}
if (!$parts[1]) {
	define ("DB_HOST", "localhost");
	define ("DB_NAME", "britannixru");
	define ("DB_USER", "britannixru");
	define ("DB_PASSWORD", "hgnnn43");
	define ("SITE_ADMIN_DIR", "admin");
}

define("PREF", "brit");

define("PAGE", "admin.php");
define("SPAGE", "index.php");
define ("SITE_ADMIN_FILE", PAGE);


#Стиль для бэкграунда active-x в админке
define("ADMIN_ACTIVEX_BG_STYLE","background:#FFF;");
?>