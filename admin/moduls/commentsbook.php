<?
#������ ��������#0
#kfp;���������� �������� �� ��������;20#
# ���� �������� ������� ������� table1
$query="
  data date NOT NULL default 0,
  ";

create_MySQL_table($query,1,0);

# =======================================
	$this_element="�����";


// ������� ���. ���������� =============================
if	(empty($_POST["preview"])) {
	echo "
	<table width=30% cellpadding=5 class=table2 style='padding:3px;'>
	<tr>
	<td class='small alert'>� ������� ����� ����������:";
	//if (empty($save) || empty($prop)) echo "<br><a href='?main=".$main."&prop=1'>������������� �������� �������</a>";
	
	echo "</td>";
			$rec=row_select("id,name,visible","","id=0");
			$row=$rec->ga();
			$i=1;
			tpr_fast_icon("file");
			tpr_fast_icon("img");
			tpr_fast_icon("link");
			tpr_fast_icon("desc");
	
	echo "
	</tr>	
	</table><hr>";	
}
//=====================================================

	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","data");
		$ed->input_komments=array("�������:","�����:","data:");
		$ed->input_types=array("textarea2","textarea","text");
		$ed->input_komments2[2]="(� ������� ��.��.��)";	
		$ed->input_default_values[2]=date("Y-m-d");
		$ed->input_data_types=array(1,1,3,1,1,1,1,1,1,1,1);
		act_message($ed,2);}

#======================================	
	if (empty($top)) insert_dop_info("content","����� � ������ �������",$main,0);	
	
	$rec=row_select_pages("id,name,data,visible","","","data DESC");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("data",$row["data"],"","");
		tpr("title",$row["name"],"id=".$row["id"],"text","��������");
		tpr_fast_icon("img");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
?>