<?
#������� �� �����#1
# ���� �������� ������� ������� table1

$query="
  format varchar(255),
  url text,
  view int(10) NOT NULL default 0,
  maxview int(10) NOT NULL default 0,
  click int(10) NOT NULL default 0,
  data_pub date NOT NULL default 0,
  data_end date NOT NULL default 0,
  rate int(3) NOT NULL default 0,
  divisions varchar(255) NOT NULL default 0,
  ";
create_MySQL_table($query,1,0);

# ������� ������� ��� ����������
$query="
  url text,
  ";
  
//create_MySQL_table($query,1,0);

mysql_query("ALTER TABLE ".PREF."_$main ADD maxview int(10) NOT NULL default 0 AFTER view");
mysql_query("ALTER TABLE ".PREF."_$main ADD target int(1) NOT NULL default 0 AFTER view");
mysql_query("ALTER TABLE ".PREF."_$main ADD kolvo int(3) NOT NULL default 0 AFTER view");
mysql_query("ALTER TABLE ".PREF."_$main ADD sorting varchar(30) NOT NULL default '' AFTER view");
mysql_query("ALTER TABLE ".PREF."_$main ADD file varchar(255) NOT NULL default '' AFTER view");

# =======================================

# ����������� ������ ��������
if ($top==0) {
	$this_element="������ ��������";
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","format","kolvo","sorting");
		$ed->input_komments=array("�������� ������:","������� ������:","������ ������:","���������� �� ��������","��� ���������� ��� ������");
		$ed->input_komments2[2]="������ � ������ �������� � �������, ������ ����� �� ���������. ������ - 100,100";
		$ed->input_komments2[3]="���� ���������� - 0, �� ��������� ��� ��������";
		$ed->input_types=array("textarea2","text","text2","text","select");
		$ed->input_default_values[3]="0";
		
		# ������� ���� ��������
		$tps2="�� ���������,,�� ���������#468x60,,468x60#160x300,,160x300#120x60,,120x60#100x100,,100x100#��������� ������,,��������� ������";
				 
		# ������� ���� ����������
		$tps4="RAND(),,��������� �����#ord,,�� �������";
		
		$ed->input_data_values=array("","",$tps2,"",$tps4);
		$ed->java=array("",1);
		
		act_message($ed);}


	#======================================	
	
	$rec=row_select("id, name, content,format, visible","","top=0");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
		$r=row_select("id","","top=".$row["id"]);
		$tmp=$r->nr();
		
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"top=".$row["id"]."","folder","������ ��������");
		tpr_fast_icon("edit");
		tpr("title",$row["content"],"","","�������");
		tpr("link",$row["format"],"","center","������");
		tpr("link",$tmp,"","center","���-�� ��������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
}
#======================================

# ����������� �������
if ($top!=0) {
	$this_element="������";
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","url","data_pub","data_end","rate","maxview","target","i","f","file","inputs10","divisions");
		$ed->input_komments=array("��������:","�����:","������:","���� ������:","���� ���������:","������� �������:","������������ ���������� �������:","��������� � ����� ����:","��������:","����:","��� ����� �� �������:","����:","� ����� �������� ����������:");
		$ed->input_types=array("textarea2","textarea2","textarea2","text2","text2","text2","text2","select","img","flash","text","file","select2");
		$ed->input_komments2[0]="������������ ����� � ��������� � ��������";
		$ed->input_komments2[1]="����� ������ ��� ��������� ��������";
		$ed->input_komments2[5]="�������� � ��������� (�� 1 �� 100). ��� ������ �����, ��� ������ ����������� ������ �������. ������ \"%\" ������� �� ����!!!";
		$ed->input_komments2[6]="���� �������� 0, �� ������ ������������ ��� �����������.";
		$ed->input_komments2[10]="���� ����������� ����, �� ����������� �������� ��� ���, (��� ���������� �����!!) ";
		$ed->input_komments2[12]="���� �� �������� �����-�� ���������� �������, �� ������ ����� ������������ ������ � ���. <br>�� ��������� ������ � ���� �������� � ���� ������ ����.";
		$ed->input_default_values=array("","","",date("Y-m-d"),(date("Y")+1).date("-m-d"),"100","0","","","","","","0");
		$ed->input_data_types=array(1,1,1,3,3,1,1,1,1,1,1,1,1,1,1);
		
		// ���� �� ����������� ������, ����� ����� ���� ����
		if (isset($id)) {
			$name=s_select("file","","id=$id");
			$ed->input_names[11]=$name;
			//$ed->input_types[10]="hidden";
		}
		
		# ��� ��������� ������, "� ����� ����?"
		$tps7="0,,���#1,,��";
		
		# ������� ���� ��������
		$tps12="0,,��� �������";
		$row=row_select("id,name,page","admin_tree","visible=1 AND (menu_top=\"0\" OR menu_top='') AND global_id=0");
		while ($r=$row->ga()){
			$tps12.="#".$r["page"].",,- ".$r["name"];	
			$row2=row_select("id,name,page","admin_tree","visible=1 AND menu_top=".$r["id"]." AND global_id=0");
			while ($r2=$row2->ga()){
				$tps12.="#".$r2["page"].",,----- ".$r2["name"];	
			}
		}
		$ed->input_data_values[7]=$tps7;
		$ed->input_data_values[12]=$tps12;
		
		act_message($ed,2);}

	#======================================	
	
	$rec=row_select("id, name, content, rate, view, click, visible,maxview","","top=$top");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
		$r=row_select("id","","top=".$row["id"]);
		$tmp=$r->nr();
		if ($row["maxview"]!=0) $maxview=" �� ".$row["maxview"]; else  $maxview="";
		
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"]."&top=$top","text","���������");
		tpr("img",$row["id"],"i","","������");
		tpr("flash",$row["id"],"f","","����");
		tpr("link",$row["rate"],"","center","������� �������");
		tpr("link",$row["view"]."$maxview","","center","�������");
		tpr("link",$row["click"],"","center","������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
}
#======================================
		
?>


