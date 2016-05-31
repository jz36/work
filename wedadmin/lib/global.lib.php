<?php
function validate_post_vars($a = NULL){
	if ($a == NULL) $a =& $_POST;
	if (is_array($a)){
		foreach($a as $key => $value){
			if (is_array($value)) {
				$valid_value = validate_post_vars($value);
			}else{
				if (get_magic_quotes_gpc()){
					$valid_value = stripslashes($value);
				}else{
					$valid_value = $value;
				}
			}
			$a[$key] = $valid_value;
		}
	}
	return $a;
}
set_magic_quotes_runtime(0);
validate_post_vars();
@session_start();

if (isset($_SERVER["HTTP_REFERER"])){
	$_SESSION["HTTP_REFERER"] = $_SERVER["HTTP_REFERER"];
}else{
	$_SESSION["HTTP_REFERER"] = FALSE;
}
?>