<?
#������#1
# ���� �������� ������� ������� table1
$query="
	type varchar(255) NOT NULL,
	alert int(2) NOT NULL default 0,
	counter int(6) NOT NULL default 0,
	data_pub date NOT NULL default 0,
	data_end date NOT NULL default 0,";
  
create_MySQL_table($query,1,0);

# ======================================= ���� �� ����������� ������� ������
if (empty($top)) {
	
	$this_element="����� �����";
	define_edit_param();	

	if (isset($add) || isset($id)) {

		$ed=new table_edit();
		$ed->input_names=array("name","type","content","counter","data_pub","data_end");
		$ed->input_komments=array("�������� ������:","��� ������:","�������� ������:","�������� �������","���� ������ ������ ������:","���� ��������� ������ ������:",);
		$ed->input_types=array("textarea2","select","textarea2","text2","text2","text2");
		$ed->input_default_values=array("","","","",date("Y-m-d"),(date("Y")+1).date("-m-d"));
		$ed->input_data_types=array(1,1,1,1,3,3);

		$tps1="radio,,����� ������� ������ ���� �����#checkbox,,����� ������� ��������� �������";

		$ed->input_data_values=array("",$tps1,"","","");
		act_message($ed,2);}

#======================================	
	$rec=row_select("","","top=0");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
		$res=row_select("counter","$main","top=\"".$row["id"]."\"");
		$ii=$res->nr();

# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("alert","��������");
		tpr("title",$row["name"],"top=".$row["id"]."","folder","�������� ������");
		tpr_fast_icon("edit","��������");
		tpr("link",$ii,"","center","��������");
		tpr("link",$row["counter"],"","center","�������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
}

#====================================== ���� �� ����������� �������� �������
if (!empty($top)) {	
	
	$this_element="������� ������";
	define_edit_param();	

	if (isset($add) || isset($id)) {

		$ed=new table_edit();
		$ed->input_names=array("name","counter");
		$ed->input_komments=array("������� ������:","�������:");
		$ed->input_types=array("textarea2","text2");
		
		act_message($ed);}

#======================================	
	$rec=row_select("","","top=\"$top\"","ord,id");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("edit");
		tpr("input",$row["name"],$row["id"],"name","�������� �������","50");
		tpr("input",$row["counter"],$row["id"],"counter","����� ������","5");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
}	
?>