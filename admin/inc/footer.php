<?
#========================================
# Формируем колонтитул

function footer() {
global $main;
$res=row_select("name,content","footer","visible=1","ord");
while ($r=$res->ga()) {
	$string=$r["name"];
	if ($r["content"]!="") $string.=": ".email_echo($r["content"]);
	$string.="<br>";
	echo $string;
}
}
?>