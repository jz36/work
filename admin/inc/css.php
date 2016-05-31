<?
#========================================
# Вставка файлов с CSS

function add_css($folder="css") {
global $main;

if ($dir_index = dir($folder)) { 
	while($get_filename=$dir_index->read()) { 
		if ( $get_filename != '.' && $get_filename != '..' ){
			echo "<link	href='css/".$get_filename."' rel='stylesheet' type='text/css'> \n";
		}
	} 
	$dir_index->close();
}
	
}	


?>