<?
#Поиск по сайту#1
# Блок проверки наличия таблицы table1
$query="  
	tops varchar(255) default '',
	names text,
	page varchar(255) default '',
	relevancy int(7) default NULL,";

create_MySQL_table($query,1,0);
mysql_query("ALTER TABLE ".PREF."_$main CHANGE id id INT(7) AUTO_INCREMENT");
# =======================================
	$nobutton=1;
	define_edit_param();

	if (!isset($search_text)) {?>	
		<form action="<?=PAGE?>?main=search" method="get">
		<input type="hidden" name="main" value="search" >
		<input id=ft name="search_text" type="text" value="поиск по сайту" onFocus="if(this.form.search_text.value=='поиск по сайту') this.form.search_text.value=''" onBlur="if(this.form.search_text.value=='') this.form.search_text.value='поиск по сайту'" style="width:120px">
		<input type="submit" name="Submit" value="Ок" onClick="if(ft.value=='поиск по сайту') {alert('Введите строку для поиска');return false;}">
		</form>
		<p>Выбрать то, в каких разделах должен осуществлятся поиск, в можете в разделе <a href=<?=PAGE?>?main=admin_tree>структура сайта</a></p>
	<?}
#======================================			
		
	else {
	
		require "".SITE_ADMIN_DIR."/class/search.php";
		
		if(!isset($pg))
		{
			$dbg=0;
			$i=0;
			$page_kol=40;
			//******************************************************************************
			
			$rec=row_select("page","admin_tree","search=1");
			$i=0;
			while (($rec->nr())>$i) {
				$row=$rec->ga();
				
				$search_tbl[]=$row["page"];
				
				$search_field[$search_tbl[$i]][]="name";
				$search_field[$search_tbl[$i]][]="content";
				
				$search_redirect_page[$search_tbl[$i]]=$row["page"];
				
				$search_title[$search_tbl[$i]]="name"; // поле которое записать как заголовок
				$search_title_numchars[$search_tbl[$i]]=150; // число символов в заголовке
				$search_content[$search_tbl[$i]]="content"; // поле которое записаь как контент
				$search_content_numchars[$search_tbl[$i]]=250; // число символов в контенте
				
				$i++;
			}
			
			//******************************************************************************
			
			$search_text=strtr($search_text,"QWERTYUIOPASDFGHJKLZXCVBNMЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ","qwertyuiopasdfghjklzxcvbnmёйцукенгшщзхъфывапролджэячсмитьбю");
			$save_table="search";
			
			if(!search_init($save_table)){
				echo "CANT INITIALIZE SEACH TABLE. Halted.";
			   exit;
			}
			search_start($search_text,$search_tbl,$search_field,$save_table,$search_title,$search_title_numchars,$search_content,$search_content_numchars,$search_redirect_page);
		
		} // end ALREADY BLOCK
		
		if(!isset($pg) or $pg=='') $pg=1;
		
		$res=row_select("id","search");
		$kol=$res->nr();
		if($kol==0) 
			echo"<span>По Вашему запросу ничего не найдено.</span>";
		else{
			echo "поиск по:\"<b>".$search_text."</b>\"<br>всего найдено совпадений: <b>".$res->nr()."</b><hr> ";		
		}
		$b=$kol%$page_kol;
		$a=($kol-$b)/$page_kol;
		$from=($pg-1)*$page_kol;
		$tmp1=$kol-$from;
		if($tmp1<=$b) $cont=$b;
		else $cont=$page_kol;
		
		$res=row_select("id,name,content,tops,page","search","","relevancy",$from.",".$cont);
		$i=0;
		while($r=$res->ga()){
		$i++;
		$tmp_array=explode("#",$r["tops"]);
		$id=$tmp_array[0];
		$top_table=$tmp_array[1];
		$top_id=$tmp_array[2];
		?>
		 <div class=small>
		 <?
		 if ($top_table!="")
		 	nav_line($top_table,$top_id," -> ",1,1,1,1,1,"",1);
		 else
		 	nav_line($r["page"],$id," -> ",1,1,1,0,1,"",1);
		 
		 ?></div><div>
		 <b><?=$r["name"]?></b><br>
		 <?=$r["content"]?>...<a href="<?=PAGE?>?main=<?=$r["page"]?><?if($id!=0) echo'&id='.$id?><?if($top_table!="") echo '&top_table='.$top_table.'&top_id='.$top_id?>&rand=29357">[перейти]</a>
		 </div><br><br><?
		}?><div align=center><?

		
		if($b==0) $aa=0;else $aa=1;
		$alpg=$a+$aa;
				
		for($i=1;$i<=$alpg;$i++){
		if($i==$pg){
		?>
		<span class="pages"><?=$i?></span>
		<?}
		else{
		?>
		<a class="pages" href="?main=search&pg=<?=$i?>"><?=$i?></a>
		<?}
		
		if($i!=$alpg) echo"&nbsp;|&nbsp;";
		
		}?></div>

	
	
	<?}
			
?>