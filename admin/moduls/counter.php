<?
#Ñ÷åò÷èêè íà ñàéòå#1
#param#count_div;Ğàçäåëèòåëü ìåæäó ñ÷åò÷èêàìè;&nbsp;#
# Áëîê ïğîâåğêè íàëè÷èÿ òàáëèöû table1
$query="";
  
create_MySQL_table($query,1,0);

# =======================================
	$this_element="ñ÷åò÷èê";
	define_edit_param();
	
	if (isset($add) || isset($id)) {
		
		$ed=new table_edit();
		$ed->input_names=array("name","content");
		$ed->input_komments=array("Íàçâàíèå ñ÷åò÷èêà:","Êîä:");
		$ed->input_types=array("text2","textarea");
		
		act_message($ed);}

#======================================	
	
	$rec=row_select("id,name,content,visible");
		table_if_empty($rec);
		$i=0;
		while (($rec->nr())>=$i) {
		if ($i!=1) $row=$rec->ga();

		$trans = array ("<br>" => "","&quot;" => "\"");
	
# 	$type, $name, $link, $icon, $i,

		tpr(0,"");
		tpr_fast_icon("check");
		tpr("title",$row["name"],"id=".$row["id"],"text","Ğàçäåë","","nowrap");
		tpr("title",strtr($row["content"],$trans),"","","Ïğèìåğ");
		tpr_fast_icon("del");
		tpr(1);
		$i++;	}
		
?>