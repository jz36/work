<?
#������� �������#0
# ���� �������� ������� ������� table1
$query="
	top_table varchar(255) NOT NULL default '',
	top_id int(8) NOT NULL default 0,
	top_art varchar(255) NOT NULL default '',
	kolvo varchar(255) NOT NULL default '',
	sid varchar(255) NOT NULL default '',
	user_id int(8) NOT NULL default 0,"; 
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
	
	if ((isset($edit) || isset($id)) && $sub==1) {
		$ed=new edit();
		$ed->input_names=array();
		$ed->input_komments=array();
		$ed->input_types=array();
		$ed->input_data_types=array();
		act_message($ed,1);}
			
	if ($sub!=1 && isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "logo");
		$ed->input_komments=array("�������� ����� �������:", "���� �������:");
		$ed->input_types=array("textprint", "img");
		act_message($ed);}
			

#======================================			
		$rec=row_select("");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("edit");
		tpr("title",$row["top_table"],"","","������");
		tpr("title",$row["top_id"],"","","�����");
		tpr("title",$row["kolvo"],"","","����������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
			
?>