<?
#������� ������#0
#param#level_in;������� �����������;0#
# ���� �������� ������� ������� table1
$query=""; 
create_MySQL_table($query,1,1);

# =======================================
	$this_element="������";
	define_edit_param();
	
	if (isset($add)) {
		$ed=new table_edit();
		$ed->input_names=array("name");
		$ed->input_komments=array("��������:");
		$ed->input_types=array("text");
		act_message($ed,1);}
	
	if (isset($edit) || isset($id)) {
		$ed=new edit();
		$ed->input_names=array();
		$ed->input_komments=array();
		$ed->input_types=array();
		$ed->input_data_types=array();
		act_message($ed,1);}
			

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
		tpr_fast_icon("link");
		tpr_fast_icon("del");
		tpr(1);

		$i++;}