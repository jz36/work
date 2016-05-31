<?php
function db_open(){
	$tries = 0;
	while ($tries < 5){
	        $tries++;
		$CFG =& $_SESSION["NEX_CFG"];
		if (@mysql_pconnect($CFG["db_hostname"], $CFG["db_username"], $CFG["db_password"])){
			if (@mysql_select_db($CFG["db_basename"])){
				mysql_query("SET CHARACTER SET cp1251_cp1251");
				mysql_query("SET NAMES 'cp1251'");
				$_SESSION["DB_OPENED"] = TRUE;
				return TRUE;
			}
		}
	}
	print "can't connect to database<BR>\n";
	return FALSE;
}
function get_row_by_id($tablename, $id){
	if (!$_SESSION["DB_OPENED"]) db_open();
	$query = mysql_query("SELECT * FROM `".$tablename."` WHERE `id` = '$id'");
	return  mysql_fetch_array($query);
}

?>