<?php
function last_news($count){
	if (!$_SESSION["DB_OPENED"]) db_open();
	$news_query = mysql_query("SELECT * FROM `wed_news_items` ORDER BY `date` DESC LIMIT 0, $count");
	$news = array();
	while ($item = mysql_fetch_array($news_query)) {
		$news[] = $item;
	}
	return $news;
}
function last_news_from($cid, $count){
	if (!$_SESSION["DB_OPENED"]) db_open();
	$news_query = mysql_query("SELECT * FROM `wed_news_items` WHERE `category_id` = '".$cid."' ORDER BY `date` DESC LIMIT 0, $count");
	$news = array();
	while ($item = mysql_fetch_array($news_query)) {
		$news[] = $item;
	}
	return $news;
}
function news_of($date_ts){
	if (!$_SESSION["DB_OPENED"]) db_open();
	$month = date('n', $date_ts);
	$year =  date('Y', $date_ts);
	$day =  date('d', $date_ts);
	$start_ts = mktime(0,0,0, $month, $day, $year);
	$finish_ts = mktime(23,59,59, $month, $day, $year);
	
	$news_query = mysql_query("SELECT * FROM `wed_news_items` WHERE `date` >= '".$start_ts."' AND `date` <= '".$finish_ts."' ORDER BY `date` DESC");
	$news = array();
	while ($item = mysql_fetch_array($news_query)) {
		$news[] = $item;
	}
	return $news;
}
function count_news_of($date_ts){
	if (!$_SESSION["DB_OPENED"]) db_open();
	$month = date('n', $date_ts);
	$year =  date('Y', $date_ts);
	$day =  date('d', $date_ts);
	$start_ts = mktime(0,0,0, $month, $day, $year);
	$finish_ts = mktime(23,59,59, $month, $day, $year);
	
	$news_query = mysql_query("SELECT COUNT(*) FROM `wed_news_items` WHERE `date` >= '".$start_ts."' AND `date` <= '".$finish_ts."'");
	return mysql_result($news_query, 0);
}
function news_categories(){
	if (!$_SESSION["DB_OPENED"]) db_open();
	$news_query = mysql_query("SELECT * FROM `wed_news_categories` ORDER BY `order`");
	$news_c = array();
	while ($item = mysql_fetch_array($news_query)) {
		$news_c[] = $item;
	}
	return $news_c;
}
function get_first_news_item_ts(){
	if (!$_SESSION["DB_OPENED"]) db_open();
	$ts = mysql_result(mysql_query("SELECT `date` FROM `wed_news_items` ORDER BY `date` ASC LIMIT 0, 1"),0);
	return $ts;
}
function get_news_category($cid){
	if (!$_SESSION["DB_OPENED"]) db_open();
	$news_query = mysql_query("SELECT * FROM `wed_news_categories` WHERE `id` = '$cid'");
	return  mysql_fetch_array($news_query);
}
?>