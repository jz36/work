<?
#����������#0
#param#size_m;������ ��������� ��������;150#size_b;������ ������� �������� (�� ������� �������);500#
# ���� �������� ������� ������� table1
$query="
  dolznost varchar(255) NULL,
  tel varchar(255) NULL,
  email varchar(255) NULL,
  edu text,
  stag text,
  sertif text,
  otdel text,";

create_MySQL_table($query,1,0);

# =======================================
	$this_element="����������";

	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","dolznost","tel","email","content","otdel","edu","stag","sertif","m","b","m,b");
		$ed->input_komments=array("���:","���������:","�������:","E-mail:","���������:","�����","�����������","����������","�����������","���� ���������:","���� �������:","�������������� ��������");
		$ed->input_types=array("text","text","text","text","textarea","text","textarea2","textarea2","textarea","img","img","imgres");
		act_message($ed);}

#======================================	

	if (empty($top)) insert_dop_info("content","����� � ������ �������",$main,0);	
		
	$rec=row_select("id,name,visible,dolznost,otdel");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"],"text","���");
		tpr("title",$row["dolznost"],"","","���������");
		tpr("title",$row["otdel"],"","","�����");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
?>