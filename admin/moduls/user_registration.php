<?
#����������� �� �����#0
#���� �������� ������� ������� table1
$query="
	type varchar(255) NOT NULL,
	alert int(2) NOT NULL default 0,
	dop varchar(255) NOT NULL,";
  
create_MySQL_table($query,1,0);
# =======================================
	$this_element="���� �����";

	define_edit_param();
	
	if (isset($add) || isset($id)) {

		$ed=new table_edit();
		$ed->input_names=array("name","type","content","dop");
		$ed->input_komments=array("�������� ����:","��� ����:","��������:","����������:");
		$ed->input_types=array("textarea2","select","textarea","text");
		$ed->input_komments2[2]="���� ��� ���� - \"���������� ������\",�� ������� ����� �������� ������� ������, �������� ������ �������� � ����� ������.";

		$tps1="input,,��������� ���� - 1 ������#textarea,,��������� ���� - ��������� �����#select,,���������� ������#text,,��������� ���� (�� ����)#data,,����#password,,������#password2,,�������� ������#email,,E-mail";

		$ed->input_data_values=array("",$tps1,"","");
		act_message($ed);}
	
	# ���� �������� ����� ���� ������, �� ������� ��� ���� ������� � ������� ������
	if (isset($save)) {
		if (isset($add)) $tid=s_select("max(id)");
		if (isset($id))  $tid=$id;
		$type=s_select("type","","id=".$tid);
		$ttype="varchar(255) NOT NULL";
		switch ($type) {
			case "textarea": $ttype="text";break;
		}
		mysql_query("ALTER TABLE ".PREF."_users ADD i".$tid." ".$ttype);
	}

#======================================	
insert_dop_info("content","����� ����� ������",$main,1);
insert_dop_info("content","����� � �������������� �����������",$main,15);
	
	$rec=row_select();
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("alert","��������");
		tpr("title",$row["name"],"id=".$row["id"]."","text","�������� ����");
		tpr("title",$row["type"],"","","��� ����");
		tpr("title",$row["content"],"","","��������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
?>