<?
#������� ��������#0
# ���� �������� ������� ������� table1
$query="";  
	
create_MySQL_table($query,1,0);

mysql_query("ALTER TABLE ".PREF."_$main ADD link varchar(255) NOT NULL  AFTER content");
mysql_query("ALTER TABLE ".PREF."_$main ADD alert  int(2) NOT NULL default 0  AFTER link");
# =======================================
	$this_element="������";
	define_edit_param();

	if (isset($add) || isset($id)) {		
		$ed=new table_edit();
		$ed->input_names=array("name","content","link");
		$ed->input_komments=array("������:","��������:","������");
		$ed->input_komments2[2]="���� ��������� ������ �� �������� � ��������� ���������";
		$ed->input_types=array("text","textarea","text");
		act_message($ed);}

#======================================			
		
insert_dop_info("content","����� ����� ��������",$main,1);

	$rec=row_select("","","","name");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("alert","�����");
		tpr("title",$row["name"],"id=".$row["id"]."","text","������");
		tpr("title",$row["content"],"","","��������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;			
		}
?>