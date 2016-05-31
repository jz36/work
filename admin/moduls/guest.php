<?
#Гостевая книга#0
#param#email;Емайл на который будут дублироваться сообщения;#
# Блок проверки наличия таблицы table1
$query="	
	email varchar(255),	
	data date default 0,
	";
create_MySQL_table($query,1,0);

mysql_query("ALTER TABLE ".PREF."_$main ADD ip varchar(255) DEFAULT 0 AFTER data");

# =======================================
	$this_element="сообщение";
	$pager=1;
	define_edit_param();
	
	if (isset($add) || isset($edit) || isset($id)) {
		
	if (empty($otvet)){
		$ed=new table_edit();
		$ed->input_names=array("name","email","content","data","ip");
		$ed->input_komments=array("Автор:","E-mail:","Сообщение:","Дата:","ip-адрес отправителя");
		$ed->input_komments2[3]="(В формате дд.мм.гг)";
		$ed->input_types=array("text","text","textarea","text2","text2");
		$ed->input_default_values[3]=date("Y-m-d");
		$ed->input_default_values[0]=$_SESSION["user_fio"];
		$ed->input_default_values[1]=$_SESSION["user_email"];
		$ed->input_data_types=array(1,1,1,3,1);
		act_message($ed,3);
		}
		
	if (!empty($otvet)) {
		$ed=new table_edit();
		$ed->input_names=array("name","email","content","data","ip","top");
		$ed->input_komments=array("Автор:","E-mail:","Сообщение:</td><td valign=top class=text>".s_select("content",$main,"id=".$otvet)."</td></tr><tr><td valign=top class=text>Ответ:","Дата:","Отправить ответ по почте","Ответ:");
		$ed->input_komments2[3]="(В формате дд.мм.гг)";
		$ed->input_types=array("text","text","textarea","text2","check","text2");
		$ed->input_default_values[0]=$_SESSION["user_fio"];
		$ed->input_default_values[1]=$_SESSION["user_email"];
		$ed->input_default_values[5]=$otvet;
		$ed->input_default_values[4]=0;
		$ed->input_default_values[3]=date("Y-m-d");
		$ed->input_data_types=array(1,1,1,3,1,1,1,1);
		$ip=s_select("ip","","id=".$id);
		if (!empty($ip)) { 
			$ed->input_types[4]="textprint";
			$ed->input_data_values[4]="Ответ отослан по почте";
			$ed->input_default_values[4]="<b class=red>Ответ отослан по почте</b>";
			}
		else {
			$ed->input_default_values[4]=0;
		}
		
		act_message($ed,3);
		}
	}
	if (!empty($inputs4) && !empty($inputs5) ) {
		echo $inputs4;
	}

#======================================		
//print_r($_POST);
echo $add;
if (isset($inputs5)){
if (!empty($id)) $tid=$id; elseif(!empty($add)) $tid=s_select("max(id)",$main,"1=1");
s_update("top=".$inputs5,PREF."_".$main,"id=".$tid);

}
	
insert_dop_info("content","Текст перед гостевой книгой",$main,1);		
		
	$rec=row_select_pages("","","top=0","id desc");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();
		
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("check");
		tpr("data",$row["data"],"","");
		tpr("title","<b>Сообщение:</b> ".$row["content"],"id=".$row["id"]."","text","Сообщение");
		tpr("icon","add","add=1&otvet=".$row["id"]."&top=".$row["id"]."".url_dop_param(1),"","Добавить ответ");
		tpr("email",$row["name"],$row["email"],"","Автор");
		tpr_fast_icon("del");
		tpr(1);
		$i++;
		
		$rec2=row_select("","","top=\"".$row["id"]."\"");
		while ($row2=$rec2->ga()) {			
		if ($i!=1) {

			tpr(0,"");
			tpr_fast_icon("check");
			tpr("data",$row2["data"],"","");
			tpr("title","<b>Ответ:</b> ".$row2["content"],"id=".$row2["id"]."&otvet=".$row2["top"]."&top=".$row2["top"],"text","Сообщение","ico-no-comment");
			tpr("title","","","","Ответ");
			tpr("email",$row2["name"],$row2["email"],"","Автор");
			tpr("icon","del",url_dop_param(1)."&did=".$row2["id"]);
			tpr(1);
			}}
		
		
		
		}
			
?>