<?
#========================================
# Формируем Мета-теги

function meta() {
global $main;
$res=row_select("","meta","name=\"".$main."\"");
if ($r=$res->ga()){
	$r=$res->ga();}
else {
	$res=row_select("","meta","name=\"0\"");
	$r=$res->ga();
	}
?>
<meta name="KEYWORDS" content="<?echo $r["keywords"];?>">
<meta http-equiv="KEYWORDS" content="<?echo $r["keywords"];?>">
<meta name="DESCRIPTION" content="<?echo $r["description"];?>">
<meta http-equiv="DESCRIPTION" content="<?echo $r["description"];?>">
<META name="REVISIT-AFTER" content="2 days">
<META name="ROBOTS" content="ALL">
<META name="RATING" content="General">
<META name="DISTRIBUTION" content="GLOBAL">
<META name="RESOURCE-TYPE" content="DOCUMENT">
<META name="URL" content="<?=PATH?>"><?
}?>