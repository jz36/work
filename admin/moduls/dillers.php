<?
#���� �������������#0
# ���� �������� ������� ������� table1

$query="
  tel varchar(255) NOT NULL default 0,
  adress varchar(255) NOT NULL default 0,
  email varchar(255) NOT NULL default 0,
	";
  
create_MySQL_table($query,1,0);

# =======================================	
if (isset($top) && $top>0) { 
	
	$this_element="��������";	
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "content", "tel","adress","email");
		$ed->input_komments=array("��������:", "��������:" ,"�������:" ,"�����:" ,"�-����:");
		$ed->input_types=array("text", "textarea","text","text","text");
		act_message($ed);
	}

	# ============	
	$rec=row_select("id, name,tel,adress,email, visible, content","","top=".$top);
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
	# 	$type, $name, $link, $icon, $i,		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"&id=".$row["id"]."".url_dop_param(1),"text");
		tpr("title",$row["tel"],"","","�������");
		tpr("title",$row["adress"],"","","�������");
		tpr("email",$row["email"],$row["email"],"","�������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;
	}
	
}

# =======================================
else {
	
	$this_element="�����";	
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "content");
		$ed->input_komments=array("�����:", "�����������:");
		$ed->input_types=array("text", "textarea2");
		act_message($ed);
		}

	# ============
	$rec=row_select("id, name, visible","","top=0");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
	# 	$type, $name, $link, $icon, $i,		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"top=".$row["id"],"folder","���������");
		tpr_fast_icon("edit","��������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
	
}?>