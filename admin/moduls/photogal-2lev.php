<?
#����������� � ���������#0
#param#level_in;������� �����������;0#cols;���������� �������� ��� ������ ����;3#gal_cols;���������� �������� ��� ������ ������ �������;1#kfp;���������� ����� �� ��������;10#size_m;������ ��������� ��������;150#size_b;������ ������� �������� (�� ������� �������);500#need_mark;��������� ������;1#need_comment;�������� �����������;1#
# ���� �������� ������� ������� table1
$query=""; 
create_MySQL_table($query,1,1);

# =======================================
	$this_element="�������";
	define_edit_param();

if($sub==1){
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name", "content");
		$ed->input_komments=array("��������:", "����������:");
		$ed->input_types=array("textarea", "textarea");
		act_message($ed);
	}
			

#======================================			
		$level_in_is=level_in("",1);
		
		$rec=row_select("id,name,visible","","top=".$top);
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();		
		
# 	$type, $name, $link, $icon, $i,
		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr_fast_icon("img");
		if ($level_in_is>0)
		tpr("title",$row["name"],"id=".$row["id"]."&top=$top&sub=$sub","text","������");
		else{
		tpr("title",$row["name"],"top=".$row["id"]."&sub=$sub","folder","������");
		tpr_fast_icon("edit","��������");}
		tpr_fast_icon("del");
		tpr(1);

		$i++;}
			
}?>