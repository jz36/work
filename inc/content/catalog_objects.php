<?
#������� ��������, ��������������#0

if (!isset($top)) $top=0;


//======================================================
// ������� ������ ��������

if (empty($id)){
	$res=row_select("id,name,content","objects","visible=1 AND top=0");
	while ($r=$res->ga()){?>
		<h3><?=$r["name"]?></h3>
		<div style="padding:3px 0px 3px 0px; background:#eee; ">
		<?
		$res2=row_select("name,id","","top='".$r["id"]."' AND visible=1");?>
		<div style="display:block;float:left; background:#9C3230; color:#FFF;padding:3px 5px; font-size:11px; font-weight:bold; margin:-2px 5px 0px 5px;"><a href="<?=SPAGE?>?main=objects&id=<?=$r["id"]?>" class="white">�������� �� �������</a></div><?
		while($r2=$res2->ga()) {?>
			<a href="<?=SPAGE?>?main=objects&id=<?=$r2["id"]?>&top=<?=$r["id"]?>" class="small"><?=$r2["name"]?></a> | 
			
		<?
		}
		?>
		</div>
		<table class="tableno">
		<tr>
		<td valign="top"><a href="<?=SPAGE?>?main=objects&id=<?=$r["id"]?>" title="�������� �� �������"><img src="img/kat/objects<?=$r["id"]?>logo_b.jpg" vspace=10 class="borderno"></a></td>
		<td class="small" valign=top>
		<h2 class='red bold' clear=all align=right style="margin-bottom:5px;"><span style="border-left:1px solid #ddd;border-bottom:1px solid #ddd;">&nbsp;<?=s_select("content","objects","top=".$r["id"]." AND name='���� �� �������'")?></span></h2>
		<?=divide_text(s_select("content","objects","top=".$r["id"]." AND name='����� ��������'"),"100","0","</P>")?></td>
		</tr>
		</table>
		
	
	
	<?
	}
}


//======================================================
// ������� �������� �������

elseif ($top==0){
	$res=row_select("id,name,content","objects","visible=1 AND id='".$id."'");
	$r=$res->ga();?>
		<h2><?=$r["name"]?></h2>
		<div style="padding:3px 10px; background:#eee; ">
		<?
		$res2=row_select("name,id","","top='".$r["id"]."' AND alert=1");
		while($r2=$res2->ga()) {?>
			<a href="<?=SPAGE?>?main=objects&id=<?=$r2["id"]?>&top=<?=$r["id"]?>" class="small"><?=$r2["name"]?></a> | 
			
		<?
		}
		?>
		</div>
		<table class="tableno">
		<tr>
		<td valign="top" width=1% style="padding-right:15px;"><img src="img/kat/objects<?=$r["id"]?>logo_b.jpg" vspace=10 class="borderno"></td>
		<td class="small" valign=top><h2 class='red bold' align=right style="margin-bottom:5px;"><span style="border-left:1px solid #ddd;border-bottom:1px solid #ddd;">&nbsp;<?=s_select("content","objects","top=".$r["id"]." AND name='���� �� �������'")?></span></h2><?=divide_text(s_select("content","objects","top=".$r["id"]." AND name='����� ��������'"),"100","0","</P>")?></td>
		</tr>
		</table>
		<?
		
		// ����������� id �������� ��� �������
		$id_foto=s_select("id","objects","top=".$r["id"]." AND name='���������'","ord ,id ");
			// id ��������� �����
			$id_foto_last=s_select("id","images","top_table='objects' AND top_id='$id_foto' AND visible=1","ord ,id ","1");
			
		$id_ready=s_select("id","objects","top=".$r["id"]." AND name='����������'");
		$id_shema=s_select("id","objects","top=".$r["id"]." AND name='����� ������'");
		
		// ���������
		?>		
		
		<h3>���������� �������</h3>
		<table class="tableno">
		<tr>
		<td valign="top" width=1% style="padding-right:15px;" align=center class="small"><a href='<?=popupimg("images",$id_foto_last,"b",1)?>' title="��������� ����"><img src="<?=getimg("images",$id_foto_last,"b")?>" style="margin-top:10px;" class="borderno" width=150><br><?=s_select("name","images","top_table='objects' AND top_id='$id_foto'")?></a></td>
		<td class="small" valign=top align="left">
		<?=substr(divide_text(s_select("content","objects","top=".$r["id"]." AND name='����������'"),"100","0","<HR>"),0,-4)?>
		<div><a href="<?=SPAGE?>?main=objects&id=<?=$id_foto?>&top=<?=$id?>" class="small">���������� ��������� � ������������� �������</a></div></td>
		</tr>
		</table>		
	
		<h3>����� ������</h3>
		<table class="tableno">
		<tr>
		<td class="">
		<?=divide_text(s_select("content","objects","top=".$r["id"]." AND name='����� ������'"),"100","0","</P>")?></td>
		</tr>
		</table>	
	
	<?
	
}

//======================================================
// ������� ��������� �������

elseif ($top!=0){
	$res=row_select("id,name,content","objects","visible=1 AND id='".$top."'");
	$r=$res->ga();?>

	<h2><?=$r["name"]?></h2>
	<div style="padding:3px 10px; background:#eee; margin:0px; ">
	<?
	// ������� ���� �������
	$res2=row_select("name,id","","top='".$r["id"]."' AND alert=1");
	while($r2=$res2->ga()) {?>
		<a href="<?=SPAGE?>?main=objects&id=<?=$r2["id"]?>&top=<?=$r["id"]?>" class="small"><?=$r2["name"]?></a> | 
		
	<?
	}
	?>
	</div>
	<div style="display:block; float:right; background:#9C3230; width:20%; color:#FFF;padding:3px 5px; font-size:11px; font-weight:normal; margin:10px 0px 0px 0px;"><a href="<?=SPAGE?>?main=objects&id=<?=$r["id"]?>" class="white">���������&nbsp;��&nbsp;��������&nbsp;�������</a></div>
	<?


	$res=row_select("name,id,content","","id=".$id);
	$r=$res->ga();
	echo "<h2 nowrap>".$r["name"]."</h2>";
	printSubList($main,$id,param("view",$main),"0");
	echo $r["content"];
	
	require(SITE_ADMIN_DIR."/inc/content/inner_desc.php");
	require(SITE_ADMIN_DIR."/inc/content/inner_links.php");
	require(SITE_ADMIN_DIR."/inc/content/inner_files.php");
	require(SITE_ADMIN_DIR."/inc/content/photogal-inner.php");

}






?>