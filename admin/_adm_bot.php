<? 
	if (PAGE=="admin2.php") { 
		require("".SITE_ADMIN_DIR."/moduls/__tls/_adm_bot.php");
		exit;	
	}?>
<? print_r($dbg_listing);?>
</table>
</td>
</tr>
<tr>
<td><img src="img/0.gif" width="1" height="1"></td>
<td>
<div class=foot> Система управления сайтом</div>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>

<div valign="top" class=rightmenu><?
for ($i=0;$i<2;$i++){?>
<div class="menu menu-color<?=$i?>">
<div class="head" onClick='showmenu("<?=$i?>"); return false;' style="cursor:pointer" title="Скрыть/показать секцию меню" ><img id="arr<?=$i?>" src="<?=SITE_ADMIN_DIR?>/img/tri2.gif" style="margin-bottom:0px;">&nbsp;&nbsp;<span class=white><?
if ($i==0) echo "Разделы сайта";
if ($i==1) echo "Сервисы";
if ($i==2) echo "Настройка сайта";
if ($i==3) echo "Администрирование";
?></span></div>
<div id="id<?=$i?>">
<?	# Выводим первый уровень
	$res=mysql_query("select name,page,shablon,id from ".PREF."_admin_tree where global_id=\"$i\" and (menu_top=\"0\" OR menu_top='')  order by ord");
	while($r=mysql_fetch_row($res)){
		if (@main_access($r[1])!=0){
			$res1=row_select("name,page,shablon,id","admin_tree","global_id='$i' and menu_top='$r[3]'");
			?>
			<div class="menu-item  menu-color<?=$i?>">
			<? 
			// Если есть подразделы, то выводим свапер
			if (($res1->nr())!=0) {?>
			<div class="menu-item-swap"  onClick='showmenu("<?=$r[3]?>","ico-folder2"); return false;' style="cursor:pointer" title="Скрыть/показать секцию меню" ><img src="<?=SITE_ADMIN_DIR ?>/img/ico-folder22.gif" id="arr<?=$r[3]?>"  ></div>
			<?
			$tid="id='id".$r[3]."'";
			}
			// Если нет, то просто картинку
			else {?>
			<div class="menu-item-swap"><img src="<?=SITE_ADMIN_DIR ?>/img/ico-folder21.gif"  ></div>
			<?
			$tid="";
			}
			if ($i<=1) echo "<b>";?><a href="<?=PAGE?>?main=<?=$r[1]?>&rand=<? echo $rand ?>" <?if ($main==$r[1]) {echo "class=is"; }?>><?=$r[0]?></a><?if ($i<=1) echo "</b>";?></div>
			<div <?=@$tid?>><?	

		# Выводим второй уровень
		
			while($r1=$res1->ga()){
				if (@main_access($r1[1])!=0){?>
					<div class=menu-sub-item><a href="<?=PAGE?>?main=<?=$r1[1]?>&rand=<? echo $rand ?>" <?if ($main==$r1[1]) echo "class=is";?>><?=$r1[0]?></a></div><?
	
				# Выводим третий уровень
				$res2=mysql_query("select name,page,shablon,id from ".PREF."_admin_tree where  global_id=\"$i\" and menu_top=\"$r1[3]\" order by ord");
				while($r2=mysql_fetch_row($res2)){
					if (@main_access($r2[1])!=0){?>
						<div class=menu-sub-item2><a href="<?=PAGE?>?main=<?=$r2[1]?>&rand=<? echo $rand ?>" <?if ($main==$r2[1]) echo "class=is";?>><?=$r2[0]?></a></div><?
					}
				}
			}}
		echo "</div>";
	}
	}
if ($i==3){?>
		<div class="menu-item  menu-color3"><a href="<?=PAGE?>?main=exit&rand=23008" >Выйти из системы</a></div>
<?}?>
<div class=menu-color<?=$i?>><img src="<?=SITE_ADMIN_DIR?>/img/0.gif" width=1 height=10></div></div></div>
<?}?>
<img id="arr2" src="../_admin/img/0.gif" width=1 height=1><div id="id2"></div>
<img id="arr3" src="../_admin/img/0.gif" width=1 height=1><div id="id3"></div>

</div>
<?
$ttop=s_select("id","admin_tree","page='".$main."'");
while ($ttop>0) {
	$rec=row_select("id,menu_top","admin_tree","id='".$ttop."'");
	$row=$rec->ga();
	$ttop=$row["menu_top"];
}
?>
<script>
DoOnLoad(<?=$row["id"]?>);
</script>
</body>
</html>