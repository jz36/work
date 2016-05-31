<?
global $HTTP_POST_VARS; global $HTTP_POST_FILES;
$filekod=fopen(SITE_FILES_TMP_DIR."/".SITE_TEMPKOD_FILE, "r");

$name=$this->name;
$id=$this->id;
$maxris=$this->maxris;
$numbimage=$this->numbimage;
$anons=$this->anons;
$top=$this->top;
$type=$this->type;
$inputs=$this->inputs;
$input_types=$this->input_types;
$input_komments=$this->input_komments;
$input_names=$this->input_names;
$input_data_types=$this->input_data_types;
$textar=file(SITE_FILES_TMP_DIR."/".SITE_TEMPKOD_FILE);
$text="";
for ($i=0; $i<count($textar); $i++)
$text.=$textar[$i];
fclose($filekod);
?>
<form action="<?=PAGE?>?main=<?=$tempmain?>" method=post>
<input type=hidden name=rand value=<? echo $rand ?>>
<? if (count($inputs)>0) {for ($i=0;$i<count($inputs);$i++) echo "<input type=hidden name=inputs[$i] value=\"".addquotes(delslashes($inputs[$i]))."\">";}
if ($id=="add") { ?><input type=hidden name=add value=1><? $doplink="&add=1"; } else { ?>
<input type=hidden name=id value=<? echo $id ?>><? $doplink="&id=$id"; } $doplink.="&top=$top&type=$type"; ?>
<input type=hidden name=kodnew value=1><input type=hidden name=delcookie value=1>
<input type=hidden name=top value=<? echo $top ?>><input type=hidden name=type value=<? echo $type ?>>
<input type=hidden name=sub value=<? echo $sub ?>>
<?	if(isset($top_table)) echo"<input type=hidden name=top_table value=$top_table>";
if(isset($top_id)) echo"<input type=hidden name=top_id value=$top_id>";?>
<div style="background-color:#FFFDE9;font-size:11px;text-align:center;padding:5px;border:1px solid #666;position:absolute;z-index:4000;width:240px;left:20px;top:10px;">На сайте это будет выглядеть так: 
<? if ($maxris>0) { ?><input type=hidden name=maxris value=<? echo $maxris; ?>><? } ?>
<input type=hidden name=kodnew value=1><input type=hidden name=top value=<? echo $top ?>>
<input type=hidden name=anons value="<? echo addquotes(delslashes($anons)) ?>"><input type=hidden name=name value="<? echo addquotes(delslashes($name)) ?>">
<nobr><input type=submit class=button value="Принимается" name="save">&nbsp;<input class=button type=button value="Не принимается" onClick="location.href='<?=PAGE?>?main=<? echo $tempmain.$doplink; ?>&rand=<? echo $rand ?>'"></nobr></div></form>
<?

echo lec_image($text);
?>


