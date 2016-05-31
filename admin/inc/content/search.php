<?
#ѕоиск по сайту#1
#========= языковой пакет ===============================
if (LANG=="rus") {
	$lng["search"]="ѕоиск по сайту";
	$lng["search_on"]="ѕоиск по сайту";
	$lng["enter_search"]="¬ведите строку дл€ поиска";
	$lng["result_no"]="ѕо ¬ашему запросу ничего не найдено.";
	$lng["search_for"]="поиск по фразе";
	$lng["was_founded"]="всего найдено совпадений";
	$lng["togetmore"]="перейти";
}
if (LANG=="eng") {
	$lng["search"]="Search";
	$lng["search_on"]="Search";
	$lng["enter_search"]="Type words for search";
	$lng["result_no"]="Nothing is found";
	$lng["search_for"]="Search for";
	$lng["was_founded"]="References found";
	$lng["togetmore"]="to get more";
}

# ======== ¬ывод формы запроса ===============================
?>	
		<form action="<?=SPAGE?>?main=search" method="get">
		<input type="hidden" name="main" value="search" >
		<?
		// ==== ≈сли мы ищем только в какой-то одной таблице
		if (!empty($search_only)) {
			echo "<input type='hidden' name='search_only' value='".$search_only."' >";
		}
		// ==== ≈сли мы указали в каких пол€х искать
		if (!empty($search_fields)) {
			echo "<input type='hidden' name='search_fields' value='".$search_fields."' >";
		}
		?>		
		
		<input id=ft name="search_text" type="text" value="<?=$lng["search"]?>" onFocus="if(this.form.search_text.value=='<?=$lng["search"]?>') this.form.search_text.value=''" onBlur="if(this.form.search_text.value=='') this.form.search_text.value='<?=$lng["search"]?>'" style="width:120px">
		<input type="submit" name="Submit" value="ќк" onClick="if(ft.value=='<?=$lng["search"]?>') {alert('<?=$lng["enter_search"]?>');return false;}">
		</form>
<?
#======================================			
		
	if (isset($search_text)) {
		
		echo "<hr>";
		
		require SITE_ADMIN_DIR."/class/search.php";
		
		if(!isset($pg))
		{
		$dbg=0;
		$i=0;
		$page_kol=40;
		//******************************************************************************
		
		if (!empty($search_only)) {
			$rec=row_select("page","admin_tree","page='".$search_only."'");
		}
		else {		
			$rec=row_select("page","admin_tree","search=1 AND visible=1");
		}
		$i=0;$n=0;
		while ($row=$rec->ga()) {
			//$row=$rec->ga();
			
			if (mysql_errno()!=1146){
				
				$search_tbl[]=$row["page"];
				//echo $row["page"]."<br>";
				
				if (empty($search_fields)) $search_fields="name,content";
				
				@$search_field[$search_tbl[$n]]=explode(",",$search_fields);
				
				//@$search_field[$search_tbl[$n]][]="name";
				//@$search_field[$search_tbl[$n]][]="content";
				
				@$search_redirect_page[$search_tbl[$n]]=$row["page"];
				
				@$search_title[$search_tbl[$n]]="name"; // поле которое записать как заголовок
				@$search_title_numchars[$search_tbl[$n]]=150; // число символов в заголовке
				@$search_content[$search_tbl[$n]]="content"; // поле которое записаь как контент
				@$search_content_numchars[$search_tbl[$n]]=250; // число символов в контенте
				$n++;
				
			}
			$i++;
			
		}
		
		//******************************************************************************
		
		$search_text=strtr($search_text,"QWERTYUIOPASDFGHJKLZXCVBNM®…÷” ≈Ќ√Ўў«’Џ‘џ¬јѕ–ќЋƒ∆Ёя„—ћ»“№Ѕё","qwertyuiopasdfghjklzxcvbnmЄйцукенгшщзхъфывапролджэ€чсмитьбю");
		$save_table="search";
		
		if(!search_init($save_table)){
			echo "CANT INITIALIZE SEACH TABLE. Halted.";
		   exit;
		}
		@search_start($search_text,$search_tbl,$search_field,$save_table,$search_title,$search_title_numchars,$search_content,$search_content_numchars,$search_redirect_page);
		
		} // end ALREADY BLOCK
		
		if(!isset($pg) or $pg=='') $pg=1;
		
		$res=row_select("id","search");
		$kol=$res->nr();
		if($kol==0) 
			echo"<span>".$lng["result_no"].".</span>";
		else{
			echo $lng["search_for"].": \"<b>".$search_text."</b>\"<br>".$lng["was_founded"].": <b>".$res->nr()."</b><hr> ";		
		}
		$b=$kol%$page_kol;
		$a=($kol-$b)/$page_kol;
		$from=($pg-1)*$page_kol;
		$tmp1=$kol-$from;
		if($tmp1<=$b) $cont=$b;
		else $cont=$page_kol;
		
		$res=row_select("id,name,content,tops,page,relevancy","search","","relevancy DESC",$from.",".$cont);
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
		 	nav_line($top_table,$top_id," -> ",1,1,1,0,1,"",1);
		 else
		 	nav_line($r["page"],$id," -> ",1,1,1,0,1,"",1);
		 
		 ?></div><div>
		 <b><?=str_replace("$search_text","<span class=red>$search_text</span>",$r["name"])?></b><br>
		 <? if (!empty($r["content"])) echo str_replace("$search_text","<span class=red>$search_text</span>",$r["content"])."<br>";?>
		 <? if ($top_table==""){ ?>
		 <div class=small>—овпадений: <?=$r["relevancy"]?>
		 <a href="<?=SPAGE?>?main=<?=$r["page"]?><?if($id!=0) echo'&id='.$id?><?if($top_table!="") echo '&top_table='.$top_table.'&top_id='.$top_id?>" class=small>[<?=$lng["togetmore"]?>]</a>
		 </div>
		<?}?>
		 </div><br><?
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