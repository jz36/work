<?
#������������ ����������#3
# ���� �������� ������� ������� table1
$query="
	password varchar(150) NOT NULL,
	email varchar(150) NOT NULL,
	fio varchar(255) NOT NULL,
	dolznost text,
	user_group int(6) NOT NULL default 0,";  
create_MySQL_table($query,1,0);

mysql_query("INSERT INTO ".PREF."_$main VALUES (100001, \"root\", NULL, \"29ce45f70295605f\", \"igor@e-mail66.ru\", \"�����������\", \"�����������\", 0, 1, 0, 0)");
# =======================================
	$this_element="������������";

	define_edit_param();

	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("fio","user_group","name","password","email","dolznost");
		$ed->input_komments=array("���:","������","login","����� ������","E-mail","���������");
		$ed->input_komments2[3]="���������� ���� \"������\" ������ ������� ��� �������� ���� ���������";
		$ed->input_types=array("textarea2","select","text","password","text","text");
		
		$rec=row_select("id,name","admin_user_groups","visible=1","name");
		$tps1="0,,-- ������ �������������";
		while($row=$rec->gr()){$tps1.="#".$row[0].",,".$row[1]."";}
		
		$ed->input_data_values=array("",$tps1);
		act_message($ed);}
		
#======================================			
		
		$rec=row_select("","","name!='root'");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,
		
		tpr(0,"");
		tpr_fast_icon("check","�������");
		tpr("title",$row["name"],"id=".$row["id"]."","text","Login");
		tpr("title",$row["fio"],"","","���");
		tpr("title",$row["dolznost"],"","","���������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
			
?>