<?
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");		// Всегда!
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");	// HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");													// HTTP/1.0

	require("init.php"); 
	require(SITE_ADMIN_DIR."/functions.php");
	set_connection();

require("inc/_index_fun.php");

require(SITE_ADMIN_DIR."/popup/print.php");
?>
