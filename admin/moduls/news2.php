<?
#������� � ����������#0
#param#news_max;������������ ���������� ���������� � ���� ��������;100#kfp;���������� �������� �� ��������;20#size_m;������ ��������� ��������;150#anonse;������ ������ �������;50#
# ���� �������� ������� ������� table1
$query="
	data date NOT NULL default 0,
	alert int(2) NOT NULL default 0,";
  
create_MySQL_table($query,1,1);

# =======================================
	$this_element="�������";
	$pager=1;
	define_edit_param();

	if (isset($maxnews)) {
		mysql_query("update ".PREF."_admin set maxnews=$maxnews, maxactualnews=$maxactualnews");}
	
	if (isset($add)) {
		$maxnews=param("news_max");
		$rec=row_select("id","","","data, id");
		$nr=$rec->nr();
		echo $maxnews.$nr;
		$i=0;
		while ($row=$rec->gr()) {
			if (($nr-$i)>=$maxnews) {
				mysql_query("delete from ".PREF."_$main where id=$row[0]");
				}
			$i++;
	}}
	
	if (isset($id) || isset($add) || isset($edit)) {
		$ed=new edit();
		$ed->input_names=array("data","m","m");
		$ed->input_komments=array("����:","��������","�������������� ��������");
		$ed->input_komments2=array("(� ������� ��.��.��)");		
		$ed->input_types=array("text2","img","imgres");
		$ed->input_default_values=array(date("Y-m-d"));
		$ed->input_data_types=array(2,1,1,1,1,1,1,1,1,1,1);
		act_message($ed,3);}
	
# =======================================

	$rec=row_select_pages("id, name, data, visible,alert","","","data desc");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("check");
		tpr_fast_icon("alert","�����");
		tpr("data",$row["data"],"","");
		$class=""; if (@$row["alert"]!=0) $class="bold";
		tpr("title",$row["name"],"id=".$row["id"],"text","�������� �������",$class);
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
		
		
		
	?>