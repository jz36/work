<?
#���������� � ��������� ���������#0
# ���� �������� ������� ������� table1
$query="
  dolznost varchar(255) NULL,
  tel varchar(255) NULL,
  email varchar(255) NULL,";

create_MySQL_table($query,1,1);

# =======================================
	$this_element="����������";

	define_edit_param();
	
	if (isset($add) || (isset($id) && $sub==1)) {
		$ed=new table_edit();
		$ed->input_names=array("name","dolznost","tel","email","m","b","m,b");
		$ed->input_komments=array("���:","���������:","�������:","E-mail:","���� ���������:","���� �������:","�������������� ��������");
		$ed->input_types=array("text","text","textarea2","text","img","img","imgres");
		act_message($ed);}
	
	if (isset($id) && $sub==2) {
		$ed=new edit();
		$ed->input_names=array();
		$ed->input_komments=array();
		$ed->input_types=array();
		$ed->input_data_types=array();
		act_message($ed);		
	}
		
		

#======================================	
	
	$rec=row_select("id,name,visible,dolznost");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"]."&sub=1","text","���");
		tpr("icon","edit","id=".$row["id"]."&sub=2","","��������� ��������");
		tpr("title",$row["dolznost"],"","","���������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
?>