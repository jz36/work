<?
#Ответы на вопросы#0

echo s_select("content",$main,"id=100001","","","","1")."<hr>";

$i=0;
$res=row_select("","","id!=100001 and visible=1");
while ($r=$res->ga()) {?>
	
	<div><b><?=$r["content"]?></b></div>
	<div align=right class=small><i><?if ($r["email"]!=""){?><a href="mailto:<?=$r["email"]?>"><?=$r["name"]?></a><?} else {?><?=$r["name"]?><?}?></i></div>
	<p><?=$r["otvet"]?></p>
	<hr>
<?	$i++;
	}
?>


