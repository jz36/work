<?
if (!empty($id)) {
	
	$res=row_select("url,click,name,content","","id=$id");
	$r=$res->ga();
	s_update("click=".($r["click"]+1),$main,"id=".$id);
	?>
	<script>
	window.location.href="<?=$r["url"]?>";
	</script>	
	<p>���� �������� ������������� �� ���������������, �� �������������� ���� ������ 
	[<a href='<?=PATH.SPAGE.$r["url"]?>'><?=$r["name"]?></a>]
	<?
	

}