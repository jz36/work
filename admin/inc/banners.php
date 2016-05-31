<?
#========================================
# Баннеры на сайте

function banners($num,$pref,$sort="") {
global $main;global $id;global $from;	
$table="informers"; if (empty($id)) $id=0;
	

$top=row_select("id,kolvo,sorting","banners","content=\"$pref\"");
$top=$top->ga();
$num=$top["kolvo"];
$sort=$top["sorting"];


$res=row_select("id,name,content,url,format,rate,file,divisions,view,maxview,target",$table,"top=".$top["id"]." AND data_end>=\"".date("Y-m-d")."\" AND data_pub<=\"".date("Y-m-d")."\" AND visible=1",$sort,$num);
$kol=$res->nr();
//echo $kol;
if($kol<=$num || $num==0) 
$num=$kol;
$i=0;$n=0;$flag=0;$str="";
while($i<$num){
	if ($sort=="RAND()111"){
		$flag=0;$n=0;$maxrand=100;
		while ($flag==0){
			if ($n==$kol) $res->ds(0);
			$r=$res->ga();
			if ($n>=$kol) $maxrand+=(-20);
			$flag=0;
			$rand=rand(1,$maxrand);
			if ($r["rate"]>=$rand){
				$divs=explode("|",$r["divisions"]);
				if (($divs[0]=="0" || in_array($main,$divs)==true) && ($r["maxview"]==0 || $r["view"]<$r["maxview"])){
					$flag=1;
					//print_r ($divs);
				}
				else 
					$i++;
			}
			//if ($i==$num) break;
			$n++;
			//echo " - ".$r["rate"]." - ".$rand." - ".$maxrand." - ".$n." <br> ";
		}
		//$arr[]=$r["rate"];
		//echo " - ".$r["rate"]." - ".$rand." - ".$flag." - ".$n." <br> ";
		//$str.=$r["id"]." - ".$r["rate"]." - ".$rand." <br> ";
	}
	elseif ($sort=="RAND()"){
		$r=$res->ga();
	}
	else {
		$r=$res->ga();

	}

if ($i==$num) break;
s_update("view=".($r["view"]+1),"banners","id=".$r["id"]);

echo "<div class=".$pref."_banner>";
if ((!empty($r["name"]) AND !empty($r["content"]))   || (!empty($r["name"]) AND !empty($r["file"]))) {
	$header=$r["name"];
	if (!empty($r["url"])) {
		$header1="<a href='".PATH.SPAGE."?main=banners&id=".$r["id"]."' "; 
		if ($r["target"]==1) $header1.="target='_blank' ";
		$header=$header1.">".$header."</a>";
	}
	echo "<h2>".$header."</h2>";
}
if ($ris=test_ris($table.$r["id"]."i")){
	$razm=getimagesize("img/kat/".$ris);
	if ($r["url"]==""){?>
	<img src=<?=getimg("$table",$r["id"],'i')?>  alt="<?=$r["name"]?>" width=<?=$razm[0]?> height=<?=$razm[1]?> align=left border=0><?
	}
	else {
	?>
	<a href="<?=PATH?><?=SPAGE?>?main=banners&id=<?=$r["id"]?>" <?if ($r["target"]==1) echo "target=_blank"?>>
	<img src=<?=getimg("$table",$r["id"],'i')?>  alt="<?=$r["name"]?>" width=<?=$razm[0]?> height=<?=$razm[1]?> align=left border=0>
	</a>
	<?}?>
	<br clear=all>
	<?
}
elseif ($ris=test_ris($table.$r["id"]."f")){
	$razm=getimagesize("img/kat/".$ris);?>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash4/cabs/swflash.cab#version=4,0,0,0"
	width=<?=$razm[0]?> height=<?=$razm[1]?> style="overflow:hidden; z-index:0;">
  <param name="quality" value="high">
  <param name="movie" value="<?="img/kat/".$ris?>">
  <param name="base" value="<?=PATH?><?=SPAGE?>?main=banners&id=<?=$r["id"]?>">
  <embed wmode="transparent" name="banner" src="<?="img/kat/".$ris?>" quality="high" style="overflow:hidden; z-index:0;"
	base="<?=PATH?><?=SPAGE?>?main=banners&id=<?=$r["id"]?>" BORDER="0" FRAMEBORDER="NO" width=<?=$razm[0]?> height=<?=$razm[1]?> type="application/x-shockwave-flash"
	pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
  </embed></OBJECT><br clear=all>
	<?
}
if (!empty($r["content"])){
	echo $r["content"];
	if (!empty($r["url"])) {
		$header1="<a href='".PATH.SPAGE."?main=banners&id=".$r["id"]."' "; 
		if ($r["target"]==1) $header1.="target='_blank' ";
		echo "<span class=small>".$header1.">[перейти]</a></span>";
	}
}
if (!empty($r["file"]) && test_file($r["file"])){
	echo "<nobr><a href='files/".test_file($r["file"])."' title='Скачать файл' target=_blank class=small>[Скачать файл]</a> [".round(filesize("files/".test_file($r["file"]))/1024,2)."Кб]</nobr>";
	}
echo "</div>";
$i++;
}
//print_r (array_count_values($arr));
//DbgPrint($table."===".$str,0,"banners");
#=====================================================
# Если мы пришли с какого то баннера, то заносим это в базу данных


}
?>