<?
#���� ��������#0
#param#size_m;������ ��������� ��������;88#
# ���� �������� ������� ������� table1
$query="
  link varchar(255) NULL,
  city varchar(255) NULL,
  email varchar(255) NULL,";

create_MySQL_table($query,1,0);

# =======================================
	$this_element="��������";


// ������� ���. ���������� =============================
if	(empty($_POST["preview"])) {
	echo "
	<table width=30% cellpadding=5 class=table2 style='padding:3px;'>
	<tr>
	<td class='small alert'>� ������� ����� ����������:";
	//if (empty($save) || empty($prop)) echo "<br><a href='?main=".$main."&prop=1'>������������� �������� �������</a>";
	
	echo "</td>";
			$rec=row_select("id,name,visible","","id=0");
			$row=$rec->ga();
			$i=1;
			tpr_fast_icon("file");
			tpr_fast_icon("img");
			tpr_fast_icon("link");
			tpr_fast_icon("desc");
	
	echo "
	</tr>	
	</table><hr>";	
}
//=====================================================

	define_edit_param();
	
	if (isset($add) || isset($id)) {
		$ed=new table_edit();
		$ed->input_names=array("name","content","link","email","city","m","f");
		$ed->input_komments=array("��������:","��������:","������:","E-mail:","����� (������):","������� (��� ������ ������):","������� (��� ������ ������) �� �����:");
		$ed->input_types=array("text","textarea","text","text","text","img","flash");
		act_message($ed);}

#======================================	
	if (empty($top)) insert_dop_info("content","����� � ������ �������",$main,0);	
	
	$rec=row_select("id,name,link,visible");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"],"text","��������");
		tpr("link",$row["link"],$row["link"],"","������");
		tpr("img",$row["id"],"m","","����");
		tpr("flash",$row["id"],"f","","����");
		tpr_fast_icon("del");
		tpr(1);
		$i++;}
		
?>