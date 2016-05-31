<?


#========================================
# Опрос на сайте

function vote($pref="") {
global $main;global $id;global $from;	

	if (empty($pref)) $pref="vote";
	$data_string="data_pub<=\"".date("Y-m-d")."\" AND data_end>=\"".date("Y-m-d")."\"";

	
	$res=row_select("id,name,content,type,counter","$pref","visible=1 AND alert=1 AND $data_string AND top=0","RAND()");
	$num=$res->nr();$i=1;
	while ($r=$res->ga()){	
		# Если мы отвечали на опрос, смотрим, есть ли еще не отвеченные активные опросы, цикл повторяется
		if (!empty($_COOKIE[''.$pref.$r["id"].'']) && $i<$num){ 
			}
		# Если мы еще не отвечали то показываем опрос
		elseif (empty($_COOKIE[''.$pref.$r["id"].''])){
			?>
			<div class='vote'>
			<h2><?=$r["name"]?></h2>
			<div class=vote_comment><?=$r["content"]?></div>
			<table><form action="javascript:void(0);" name='vote'>
			<script>var check="";</script>
			<?
			$res2=row_select("id,name,content","$pref"," top=".$r["id"]."");
			while  ($r2=$res2->ga()){
				if ($r["type"]=="radio") {
					$name="check".$r["id"];$alert="Пожалуйста выберите вариант ответа!";}
				if ($r["type"]=="checkbox") {
					$name="check".$r2["id"];$alert="Пожалуйста выберите один или несколько ответов из списка!";}
				
				echo "<tr><td width=1%><input type='".$r["type"]."' name='".$name."' value='0' id='check".$r2["id"]."' class=check 
						onClick=\"";
				if($r["type"]=="radio") echo "check='".$r2["id"]."-1,';";
				if($r["type"]=="checkbox") echo "checkbox(this);check=check + '".$r2["id"]."-' + document.getElementById('check".$r2["id"]."').value + ',';";
				echo	"\"></td><td class=vote_item width=100%><label for='check".$r2["id"]."'>".$r2["name"]."</label></td></tr>";
			}
			
			?>
			<tr><td colspan=2>
			<?
			if ($ris=test_ris("_butt_vote","img","../")) { 
				$razm=getimagesize("img/".$ris);				
				$input="type='image' src='img/".$ris."' class='noborder submit' style='width:".$razm[0]."px;height:".$razm[1]."px'"; 
				
			}
			else  
				$input="type=button value='Ответить' class=submit";
			?>
			<input <?=$input?>  onclick =" if (check=='') {alert('<?=@$alert?>');} else window.open('popup.php?file=vote.php&main=<?=$pref?>&vote=<?=$r["id"]?>&id='+check,'_blank','left=200,top=250,width=500,height=300,scrollbars=yes,resizable');">
			</td></tr></form></table><div align=center><a href='#' class=small onclick ="window.open('popup.php?file=vote.php&main=<?=$pref?>&vote=<?=$r["id"]?>&all=1','_blank','left=200,top=250,width=500,height=450,scrollbars=yes,resizable');">[посмотреть архив]</a></div></div>
			<?
			break;
			
		}
		# Если мы уже отвечали то показываем результат опроса
		elseif (!empty($_COOKIE[''.$pref.$r["id"].''])){
			?>
			<div class=vote>
			<h2><?=$r["name"]?></h2>
			<div class=vote_comment><?=$r["content"]?></div>
			<table class=tableno>
			<?
			$counter=s_select("SUM(counter)",$pref," top=".$r["id"]."");
			$res2=row_select("id,name,content,counter",$pref," top=".$r["id"]."");
			while  ($r2=$res2->ga()){
				if (!empty($r["counter"])) $width=$r2["counter"]*100/$counter;
				else $width=100;
				echo "<tr><td class='vote_item' width='100%'>".$r2["name"]."</td>";
				echo "<td align=right valign=bottom class='vote_item red'>".round($width)."%</td></tr>";
				echo "<tr><td colspan=2 class=line_bg><div class='line_red' style='width:".$width."%'><img src='img/0.gif' height='5' width='1'></div></td></tr>";
			}
			
			?>
			<tr><td colspan=2>
			</td></tr></table>
			<div class=vote_comment>Всего ответило: <b><?=$r["counter"]?></b> чел.<?
			if (!empty($_COOKIE[''.$pref.$r["id"].''])) echo " Ваш ответ: ".$_COOKIE[''.$pref.$r["id"].''];
			?>
			
			</div><div align=center><a href='#' class=small onclick ="window.open('popup.php?file=vote.php&main=<?=$pref?>&vote=<?=$r["id"]?>&all=1','_blank','left=200,top=250,width=500,height=450,scrollbars=yes,resizable');">[посмотреть архив]</a></div></div>
			</div>
			<?
		}		
		$i++;
	}
}
?>