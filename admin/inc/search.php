<?
#========================================
# Поиск по сайту

function search($name='поиск по сайту',$butt_name="Ок",$width="120px",$search_only="",$search_fields="") {

	
?>	
<div class='search'><nobr>
<form action="<?=SPAGE?>?main=search" method="get">
<input type="hidden" name="main" value="search" >
<?
// ==== Если мы ищем только в какой-то одной таблице
if (!empty($search_only)) {
	echo "<input type='hidden' name='search_only' value='".$search_only."' >";
}
// ==== Если мы указали в каких полях искать
if (!empty($search_fields)) {
	echo "<input type='hidden' name='search_fields' value='".$search_fields."' >";
}
?>	
<input id='ft' name="search_text" type="text" value="<?=$name?>" onFocus="if(this.form.search_text.value=='<?=$name?>') this.form.search_text.value=''" onBlur="if(this.form.search_text.value=='') this.form.search_text.value='<?=$name?>'" style="width:<?=$width?>"><?
if ($ris=test_ris("_butt_search","img","../")) { 
	$razm=getimagesize("img/".$ris);				
	$input="type='image' src='img/".$ris."' class='noborder submit' style='width:".$razm[0]."px;height:".$razm[1]."px'"; 
	
}
else  
	$input="type=submit value='".$butt_name."' class=button";
?><input <?=$input?> onClick="if(ft.value=='<?=$name?>') {alert('Введите строку для поиска');return false;}" class="button"></form>
</nobr>
</div>

<?
}
?>
