<?
#������� ���������, ��������������#0
#param#level_in;������� �����������;5#view;�������� ������: <br>������� - table<br>������ - list<br>���������� ������ - select<br>������ ������ - text<br>����� ���� ������� �� ���� �������� - all;list#num_cols;���������� ��������, ���� ������� ��������;1#def_razd;�������� �� ��������� ������ ��������� (0-���, 1-��);0#need_popup;���������� ���������� ���� ����������� � ������  (0-���, 1-��);1#size_logo;������ ���� �������;100#
# ���� �������� ������� ������� table1
$query=""; 
create_MySQL_table($query,1,1);

# =======================================
# =======================================

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
	$this_element="������";
	define_edit_param();
	
	if (isset($add)) {
		$ed=new table_edit();
		$ed->input_names=array("name");
		$ed->input_komments=array("��������:");
		$ed->input_types=array("textarea2");
		act_message($ed,1);}
	
	if ((isset($edit) || isset($id)) && $sub==1) {
		$ed=new edit();
		$ed->input_names=array();
		$ed->input_komments=array();
		$ed->input_types=array();
		$ed->input_data_types=array();
		act_message($ed,1);}
			
	if ($sub!=1 && isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "logo","logo");
		$ed->input_komments=array("�������� ����� �������:", "���� �������:","�������������� ��������");
		$ed->input_types=array("textprint", "img","imgres");
		
		$tps0=s_select("name","","id=".$id);
		$ed->input_data_values[0]=$tps0;
		$ed->input_default_values[0]=$tps0;
		act_message($ed);}
			

#======================================			
		$level_in_is=level_in("",1);
		if (empty($top)) insert_dop_info("content","����� � ������ �������",$main,0);
		
		$rec=row_select("id,name,visible","","top=".$top);
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();		
		
# 	$type, $name, $link, $icon, $i,
		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		if ($level_in_is>0)
		tpr("title",$row["name"],"id=".$row["id"]."&top=$top","text","������");
		else{
		tpr("title",$row["name"],"top=".$row["id"]."","folder","������");
		tpr_fast_icon("edit","��������");}
		tpr("title","����","id=".$row["id"]."&sub=2","","����","small","align=center");
		tpr_fast_icon("file");
		tpr_fast_icon("img");
		tpr_fast_icon("link");
		tpr_fast_icon("desc");
		tpr_fast_icon("del");
		tpr(1);

		$i++;}
			
?>