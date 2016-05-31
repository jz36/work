<?php
function new_key(){
	srand((double) microtime() * 1000000);
	return md5(uniqid(rand()));
}
function password_hash($password, $key){
	return md5(md5($password).$key);
}
?>