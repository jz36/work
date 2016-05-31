<?
#========================================
# Формируем Бегущую строку

function marquee() {
global $main;

$kol=param("msg_for_page","marquee");
$speed=param("speed","marquee");
$data_string="data_pub<=\"".date("Y-m-d")."\" AND data_end>=\"".date("Y-m-d")."\"";
$string="";$i=0;
$string_all="";

?>
<div id=scroll_line>
<??>
<marquee id=marquee scrollamount=<?=$speed?> direction=left onMouseOver="javascript:document.getElementById('marquee').scrollAmount=0" onMouseOut="javascript:document.getElementById('marquee').scrollAmount=<?=$speed?>"><?
if ($main=="main"){
	$res=row_select("id,name,content,time_pub,time_end,alert","marquee","visible=1 AND alert=3 AND ".$data_string,"ord");
	if (($res->nr())>0) {$nkol=$res->nr(); $kol=$kol-$nkol;}
	while($nkol>0){
		$r=$res->ga();
		$string=$r["name"];
		$string="<span><b>".$string."</b></span>";
		if ($r["name"]!="" && $r["content"]!="") $string="<a href='".$r["content"]."'>".$string."</a>";
		$string.="<img src=img/0.gif width=200 height=1 border=0>";
		$string_all.=$string;
		$nkol--;
	}
}
$res=row_select("id,name,content,time_pub,time_end,alert","marquee","visible=1 AND alert!=3 AND ".$data_string,"RAND()");
if (($res->nr())<$kol) $kol=$res->nr();
while($kol>0){
	$r=$res->ga();
	$string=$r["name"];
	if ($r["alert"]=="1") $string="<span><b>".$string."</b></span>";
	if ($r["alert"]=="2") $string="<span class=red><b>".$string."</b></span>";
	if ($r["name"]!="" && $r["content"]!="") $string="<a href='".$r["content"]."'>".$string."</a>";
	$string.="<img src=img/0.gif width=200 height=1 border=0>";
	$string_all.=$string;
	$kol--;
}
echo $string_all;
?>
</marquee></div>
<?}?>