<?
#Одиночная страница#0
# Блок проверки наличия таблицы table1
$query="";
create_MySQL_table($query,1,1);


mysql_query("INSERT INTO ".PREF."_$main (id,name) VALUES (100001,\"".s_select("name","admin_tree","page=\"$main\"")."\")");
# =======================================

	$nobutton=1;$noback=1;$id=100001;
	//define_edit_param();

if	(empty($_POST["preview"])) {
	echo "
	<table width=30% cellpadding=5 class=table2 style='padding:3px;'>
	<tr>
	<td class='small alert'>К разделу можно прикрепить:";
	//if (empty($save) || empty($prop)) echo "<br><a href='?main=".$main."&prop=1'>Редактировать свойства раздела</a>";
	
	echo "</td>";
			$rec=row_select("id,name,visible","","id=100001");
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
	
	if (empty($prop)){

		$ed=new edit();
		$ed->input_names=array();
		$ed->input_komments=array();
		$ed->input_types=array();
		$ed->input_data_types=array();
		$ed->id=100001;
		$ed->edit="1";
		act_message($ed);
	}


	if (!empty($save) || !empty($prop)) echo "<p><a href='".PAGE."?main=".$main."&rand=".$rand."'>>> Продолжить редактирование</p>";
	
	

?>