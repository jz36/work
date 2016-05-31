<?

$title=s_select("name","links","top_table=\"$main\" AND top_id='$id' AND content='Заголовок'");
if (empty($title)) $title="Смотри также";
$res=row_select("id,name,content,url,gout,alert","links","top_table=\"$main\" AND top_id='$id' AND visible='1'");
if ($res->nr()>0) echo "<div class=all_links><h3>".$title."</h3>";
while($r=$res->ga()){?>
	
	<div class=text>
	<?
	if (!empty($r["alert"])) $alert="class='bold'"; else  $alert="";
	if (!empty($r["url"])) echo "<a href=".$r["url"]." >".$r["name"]."</a>";
	if (!empty($r["content"])) echo "<div class=small>".$r["content"]."</div>";
	?>
	</div>
	<?
}

if ($res->nr()>0) echo "</div>";
?>