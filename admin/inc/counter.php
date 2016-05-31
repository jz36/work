<?
#========================================
# Чужие счетчика на сайте

function counter($name="") {

if (substr_count(PATH,"localhost2")==""){
	$trans = array ("<br>" => "","&quot;" => "\"");
	if (!empty($name)) {
		echo strtr(s_select("content","counters","name='$name' AND visible=1"),$trans);
	}
	else {
		$res=row_select("content,name","counters","visible=1");
		while ($r=$res->ga()){
			echo strtr($r["content"],$trans).param("count_div","counters");	
		
		}		
	}

}}
?>
