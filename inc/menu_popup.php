<?
#========================================
# Формируем выпадающее меню 
function menu_popup() {
global $main;global $menutop;global $id;
$i=1;
$res=row_select("name,id,page,menu_top,alert,shablon","admin_tree","global_id=0 and visible=1 and menu_top=\"0\"");
while($r=$res->ga()){
	if ($main==$r[2] || $menutop==$r[2]) {$isnow=1;} else {$isnow=0;} # проверяем, выбранный это пункт или нет
	if ($r[4]==1){	
		?>
		<div id=h<?=$i?> class="popup" onmouseover="clearTimeout(tid);m_over(<?=$i?>,<?=$isnow?>);" onmouseout="hiding(<?=$i?>,1);m_out(<?=$i?>,<?=$isnow?>);">
			<?
			if (substr($r["shablon"],0,5)!="1page" && $r["shablon"]!="redirect"){
			@$res2=row_select("name,id","$r[2]","top=0 and visible=1");
			while(@$r2=$res2->gr()){ ?>
				<div class="popup-item <?if ($main==$r[2] && $id==$r2[1]) {echo "popup-item_is";} ?>"><A href="index.php?main=<?=$r[2]?>&id=<?=$r2[1]?>"><?=$r2[0]?></a></div><?
			}}
			$res1=row_select("name,id,page","admin_tree","global_id=0 and visible=1 and menu_top=\"$r[1]\"");
			while($r1=$res1->gr()){ ?>
				<div class="popup-item <?if ($main==$r1[2]) {echo "popup-item_is";} ?>"><A href="index.php?main=<?=$r1[2]?>"><?=$r1[0]?></a></div><?
			}
		$i++;?></div><?
	}
	else {
		$i++;
	}
}}
?>