<?
#========================================
# Формируем меню второго уровня
function menu2() {
global $main;global $menutop;global $idtop;global $id;global $menutopname;

$menutop=show_ttop($menutop,"admin_tree","menu_top");
$i=0;
$res=row_select("name,id,page,menu_top,alert,shablon","admin_tree","(page='$main' OR id='$menutop') AND global_id=0 and visible=1 and alert=1 and menu_top=\"0\"","",1);
$r=$res->ga();?>

<?
	if (substr($r["shablon"],0,5)!="1page" && $r["shablon"]!="redirect" && $r["alert"]!=0){
		@$res2=row_select("name,id",$r["page"],"top=0 and visible=1");
		while(@$r2=$res2->ga()){ 
			$i++;
			if ($i==1) { echo "<div class='menu2_top' style='background:url(img/m2_bg.jpg) no-repeat left top;'><div class='menu2'>";}
			?>
			<div class="menu2-item <?if ($main==$r["page"] && $r2["id"]==$id) echo "menu2-item_is"?>"><A href="index.php?main=<?=$r["page"]?>&id=<?=$r2["id"]?>"><?=$r2["name"]?></a></div>
			<?
		}
	}
	$res1=row_select("name,id,page,menu_top,shablon,alert","admin_tree","global_id=0 and visible=1 and menu_top=\"".$r["id"]."\"");
	while($r1=$res1->ga()){ 
		$i++;
		if ($i==1) { echo "<div class='menu2_top' style='background:url(img/m2_bg.jpg) no-repeat left top;'><div class='menu2'>";}
		?>
		<div class="menu2-item <?if ($r1["page"]==$main || s_select("menu_top","admin_tree","page='".$main."'")==$r1["id"] ) echo "menu2-item_is"?>"><A href="index.php?main=<?=$r1["page"]?>"><?=$r1["name"]?></a></div><?
		if ($r1["alert"]==1 && ($main==$r1["page"] || $r1["id"]==s_select("menu_top","admin_tree","page='$main'"))){
		if (substr($r1["shablon"],0,5)!="1page" && $r1["shablon"]!="redirect"){
			@$res12=row_select("name,id",$r1["page"],"top=0 and visible=1");
			while(@$r12=$res12->ga()){ ?>
				<div class="menu3-item <?if ($main==$r1["page"] && $r12["id"]==$id) echo "menu3-item_is"?>"><A href="index.php?main=<?=$r1["page"]?>&id=<?=$r12["id"]?>"><?=$r12["name"]?></a></div>
			<?
		}}
		if ($r1["alert"]==1){
			$res11=row_select("name,id,page,menu_top","admin_tree","global_id=0 and visible=1 and menu_top=\"".$r1["id"]."\"");
			while($r11=$res11->ga()){ ?>
				<div class="menu3-item <?if ($r11["page"]==$main) echo "menu3-item_is"?>"><A href="index.php?main=<?=$r11["page"]?>"><?=$r11["name"]?></a></div>
				<?
		}}}
	}
	if ($i>0) { echo "</div></div>";}
	else 
?><?
}
?>