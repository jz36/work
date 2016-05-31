<?
$res=row_select("name,content","all_desc","top_table=\"$main\" AND top_id='$id' AND visible='1'");
while($r=$res->ga()){?>
	
	<div class="all_desc">
	<h3><?=$r["name"]?></h3>
	<div class=text><?=$r["content"]?></div>
	</div>

	<?

}
?>