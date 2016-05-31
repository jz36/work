<?
#Одиночная страница#0

$res=row_select("name,content,target,id","","id=\"100001\"");
$r=$res->ga();

if (!empty($r["name"])){?>
	<script>
		window.open("<?=SPAGE?>?main=<?=s_select("page","admin_tree","id=".$r["name"])?>","<?=$r["target"]?>","");
	</script>
<?
}
if (empty($r["name"])){?>
	<script>
		window.open("<?=$r["content"]?>","<?=$r["target"]?>","");
	</script>
<?
}
?>


