<?php

// Формируем меню первого уровня

function menu($x="0",$y="0",$pos="v") {

	global $main;
	global $menutop;
	global $idtop;
	global $id;

	$menutop = show_ttop($menutop,"admin_tree","menu_top");
	$i=1;
	$res=row_select("name,id,page,menu_top,alert,shablon","admin_tree","global_id=0 and visible=1 and menu_top=\"0\"");

	while($r=$res->ga()){ 
		if ($main==$r["page"] || $menutop==$r["id"]) {$isnow=1;} else {$isnow=0;} # проверяем, выбранный это пункт или нет
		if ($r["alert"]==1) {$vis=1;} else {$vis=0;} # проверяем, показывать попап меню или нет
		if ($isnow==1) {$img=1;} else $img=0;
?>
<td  id='d<?=$i?>' nowrap
	class='menu-item <?if ($isnow==1) {echo "menu-item_is";} ?>'
		onmouseover="javascript:show('<?=$i?>',<?=$x?>,<?=$y?>,<?=$vis?>,'<?=$pos?>');clearTimeout(tid);" 
		onmouseout="javascript:hiding(<?=$i?>,<?=$vis?>);"
		onClick="window.location.href='index.php?main=<?=$r["page"]?>'"
	><a href="index.php?main=<?=$r["page"]?>"><?=$r["name"]?></a></td>

	<?
	$i++;
}}
?>