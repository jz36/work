<?
#������� ������#1
#param#speed;�������� ��������� (�� 1 �� 10);5#msg_for_page;������� ��������� ������������ ������������ �� ����� ��������;10#margin;���������� ����� �����������;200#
# ���� �������� ������� ������� table1

$query="
	data_pub date NOT NULL default 0,
	time_pub varchar(10),
	data_end date NOT NULL default 0,
	time_end varchar(10),
	alert int(2) NOT NULL default 0,";
  
create_MySQL_table($query,1,0);

# =======================================
	$this_element="���������";
	$pager=1;
	define_edit_param();

	if (isset($id) || isset($add) || isset($edit)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","data_pub","time_pub","data_end","time_end","alert");
		$ed->input_komments=array("���������:","������ �� ������","���� ����������:","����� ����������:","���� ��������� ����������:","����� ��������� ����������:","�������� ���������:");
		$ed->input_types=array("textarea2","textarea2","text2","text2","text2","text2","select");
		$ed->input_default_values=array("","",date("Y-m-d"),date("h:i"),(date("Y")+1).date("-m-d"),date("h:i"));
		$ed->input_data_types=array(1,1,3,1,3,1,1,1);
		
		# ������� ��������� ����� ����������
		$tps5="0,,�������#1,,������#2,,����� ������#3,,������ �� �������";
		
		$ed->input_data_values=array("","","","","","",$tps5);
		
		act_message($ed,2);}
	
# =======================================
	
	$alert="0,,�������#1,,������#2,,����� ������#3,,������ �� �������";

	$rec=row_select_pages("id, content, name, data_pub, data_end, time_pub, time_end, visible,alert","","","ord,data_pub DESC,time_pub DESC");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		$class=""; if (@$row["alert"]!=0) $class="bold";
		tpr("title",$row["name"],"id=".$row["id"],"text","���������",$class);
		tpr("link","������",$row["content"],"","������");
		tpr("select",$row["alert"],$row["id"],"alert","��������",$alert);		
		tpr("data",remakedata($row["data_pub"]).", ".substr($row["time_pub"],0,5),"1","","������");
		tpr("data",remakedata($row["data_end"]).", ".substr($row["time_end"],0,5),"1","","���������");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
		
		
		
	?>