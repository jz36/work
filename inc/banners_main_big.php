<?
#========================================
# ������� �� �����

function banners_main_big($num,$pref) {
global $main;global $id;global $from;	
$table="informers"; if (empty($id)) $id=0;
	

@$top=row_select("id,kolvo,sorting",$table,"content=\"$pref\"");
if ($top=$top->ga()){
$num=$top["kolvo"];
$sort=$top["sorting"];

$res=row_select("id,name,content,url,format,rate,file,divisions,view,maxview,target",$table,"top=".$top["id"]." AND data_end>=\"".date("Y-m-d")."\" AND data_pub<=\"".date("Y-m-d")."\" AND visible=1",$sort,$num);
$kol=$res->nr();
//echo $kol;
if($kol<=$num || $num==0) 
$num=$kol;
$i=0;$n=0;$flag=0;$str="";
while($i<$num){
	$url="";
	$header="";
	$content="";
	$img="";
	
	if ($sort=="RAND()1"){

	}
	elseif ($sort=="RAND()"){
		$r=$res->ga();
	}
	else {
		$r=$res->ga();

	}

if ($i==$num) break;
s_update("view=".($r["view"]+1),$table,"id=".$r["id"]);


if ($r["target"]==1) $target="target='_blank'"; else  $target="";


if ($ris=test_ris($table.$r["id"]."i")){
	$razm=getimagesize("img/kat/".$ris);
	@$img.="<img src='".getimg("$table",$r["id"],'i')."'  alt='".$r["name"]."' width=".$razm[0]." height=".$razm[1]." border=0>";
	if ($r["url"]!=""){
		$img="<a href='".PATH.SPAGE."?main=".$table."&id=".$r["id"]."' ".$target.">".$img."</a>";
	}
}

elseif ($ris=test_ris($table.$r["id"]."f")){
	$razm=getimagesize("img/kat/".$ris);?>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash4/cabs/swflash.cab#version=4,0,0,0"
	width=<?=$razm[0]?> height=<?=$razm[1]?> style="overflow:hidden; z-index:0;">
  <param name="quality" value="high">
  <param name="movie" value="<?="img/kat/".$ris?>">
  <param name="base" value="<?=PATH?><?=SPAGE?>?main=<?=$table?>&id=<?=$r["id"]?>">
  <embed wmode="transparent" name="banner" src="<?="img/kat/".$ris?>" quality="high" style="overflow:hidden; z-index:0;"
	base="<?=PATH?><?=SPAGE?>?main=<?=$table?>&id=<?=$r["id"]?>" BORDER="0" FRAMEBORDER="NO" width=<?=$razm[0]?> height=<?=$razm[1]?> type="application/x-shockwave-flash"
	pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
  </embed></OBJECT><br clear=all>
	<?
}

$i++;


#=====================================================
?>
		<div class="banner_big">
		<table border="0" align="center" cellpadding="0" cellspacing="0"><tr>
		<td width="10" class="border"><img src="img/0.jpg" width="13" height="1" border="0" /></td>
		<td class="image"><?=@$img?><?=@$header?></td>
		<td width="10" class="border"><img src="img/0.jpg" width="13" height="1" border="0" /></td>
		</table>		
		</div>
<?
}
}
}
?>