<?
#���������������#0
# ���� �������� ������� ������� table1
$query="
  target varchar(255) NOT NULL default 0,";  
create_MySQL_table($query,1,0);

mysql_query("INSERT INTO ".PREF."_$main (id,name) VALUES (100001,\"���������\")");
# =======================================

	$nobutton=1;$noback=1;
	define_edit_param();

echo "<h3>��� ������ ����� ������, ���������� ����� ������������� ���������� 
		�� ������ ������ ����� ��� �� ������, ������� �� �������.</h3>
		<p>���� ����� ������ � ������ � ������, �� ������� ����� � ��������� ���� ������.</p>
		";

		$ed=new table_edit();
		$ed->input_names=array("name","content","target");
		$ed->input_komments=array("������� ������ �����:","������� ������:","��� �������:");
		$ed->input_types=array("select","textarea2","select");
		$ed->id=100001;
		
		# ������� ������ ��������
		if (isset($id)) $id_where="and id!=".$id; else $id_where="";

		$tps1="0,,------------------------";
			# ������ ������� ����
			$rec=row_select("id,name","admin_tree","(menu_top=\"0\" OR menu_top='') and global_id=\"0\" $id_where ","global_id");
			while($row=$rec->ga()){
				$tps1.="#".$row[0].",,".$row[1]."";
				# ������ ������� ����
				$rec2=row_select("id,name","admin_tree","menu_top=\"$row[0]\" and global_id=\"0\" $id_where ","global_id");
				while($row2=$rec2->ga()){
					$tps1.="#".$row2[0].",,-- ".$row2[1]."";
					# ������ ������� ����
					$rec3=row_select("id,name","admin_tree","menu_top=\"$row2[0]\" and global_id=\"0\" $id_where ","global_id");
					while($row3=$rec3->ga()){
						$tps1.="#".$row3[0].",,-- -- ".$row3[1]."";
					}
				}
			}
		
		# ������� �������� ��������
		$tps2="_self,,� ���� �� ����#_blank,,� ����� ����";
		
		$ed->input_data_values=array($tps1,"",$tps2);
		
		act_message($ed);
?>