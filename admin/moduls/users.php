<?
#������������ �����#0
# ������� �������� �������
$query="
	schet int(6) not NULL default 0,
	schet2 int(6) not NULL default 0,
	reg_time datetime NOT NULL default 0,
	last_time datetime NOT NULL default 0,";
create_MySQL_table($query,1,0);
create_MySQL_table($query,1,0,"users1");

mysql_query("ALTER TABLE ".PREF."_".$main." CHANGE id id INT(7) AUTO_INCREMENT");
mysql_query("ALTER TABLE ".PREF."_".$main."1 CHANGE id id INT(7) INCREMENT");
mysql_query("ALTER TABLE ".PREF."_".$main." ADD schet2 int(6) not NULL default 0 AFTER schet");
mysql_query("ALTER TABLE ".PREF."_".$main."1 ADD schet2 int(6) not NULL default 0 AFTER schet");
# =======================================
# �������������� �������������

	$this_element="������������";
	define_edit_param();

	if (isset($add) || isset($id)) {		
		$ed=new table_edit();
		$ed->input_names=array("schet","reg_time","last_time");
		$ed->input_komments=array("����:","���� �����������:","��������� ���������:");
		$ed->input_types=array("text2","text","text");
		$res=row_select("id,name","user_registration","type!=\"\"");
		$i=3;
		while ($r=$res->ga()){
			$ed->input_names[$i]=("i".$r["id"]);
			$ed->input_komments[$i]=($r["name"]);
			$ed->input_types[$i]=("text");
			$i++;
		}
		
		act_message($ed);}

	#======================================			
		
	$rec=row_select("","","","reg_time DESC,i".s_select("id","user_registration","name=\"�����\""));
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
		//s_select("id",$main."_registration","name=\"�����\"");
		
	# 	$type, $name, $link, $icon, $i,
		$re[2]=0;$re[3]=0;$re[4]=0;
		$res=row_select("id","stakes","user_id=".$row["id"]);
		$res2=row_select("value","stakes","user_id=".$row["id"]." AND added=0");
		while ($r=$res2->ga()){
			$re[2]+=$r["value"];
		}
		$res3=row_select("value2","stakes","user_id=".$row["id"]." AND added=1");	
		while ($r=$res3->ga()){
			$re[3]+=$r["value2"];
		}
		$res4=row_select("value","stakes","user_id=".$row["id"]." AND added=2");	
		while ($r=$res4->ga()){
			$re[4]+=$r["value"];
		}
		
		tpr(0,"");
		tpr_fast_icon("ord");
		tpr_fast_icon("check");
		tpr("title",$row["i".s_select("id","user_registration","name=\"�����\"")],"id=".$row["id"]."","text","�����");
		tpr("title",$row["schet"],"","","����");
		tpr("title",$res->nr(),"","","������");
		tpr("title",$res2->nr(),"","","��������");
		tpr("title",$re[2],"","","�����");
		tpr("title",$res3->nr(),"","","�����.");
		tpr("title",$re[3],"","","�����");
		tpr("title",$res4->nr(),"","","������.");
		tpr("title",$re[4],"","","�����");
		if (empty($row["i100016"])) tpr("title",$row["i100009"],"","","�����");	else tpr("title",$row["i100016"],"","","�����");
		tpr_fast_icon("del");
		tpr(1);
		$i++;			
		}


?>