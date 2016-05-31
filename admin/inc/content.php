<?
#========================================
# Выводим контент на страницу

if ($main=="error"){
	$title="Ошибка";
	switch ($num){
		case "401": echo "<H3 class=red>Ошибка 401.</H3>
			<p>Документ закрыт при помощи специального пароля. 
			Если вы забыли пароль доступа к данной страничке, обратитесь к администратору.</p>";break;
		case "403": echo "<H3 class=red>Ошибка 403.</H3>
			<p>Извините, документ заблокирован администратором. У вас просто нет доступа.<br>
			Спасибо	за понимание";break;
		case "404": echo "<H3 class=red>Ошибка 404.</H3>
			<p>Извините, запрашиваемой вами странички не существует, или она была куда-то перемещена. 
			 Приносим извинения за причиненные неудобства";break;
		case "500": echo "<H3 class=red>Ошибка 500.</H3>
			<p>Скрипт, который вы пытались выполнить привел к ошибке. 
			Извините за причиненные неудобства. В ближайшее время мы устраним неисправность.";break;
		
	}
}
else {
	$res=row_select("shablon,shablon_out","admin_tree","page=\"$main\"");
	$r=$res->ga();
		if ($r["shablon_out"]=="") $content=$r["shablon"];
		if ($r["shablon_out"]!="") $content=$r["shablon_out"];
	
	if(file_exists("inc/content/".$content.".php"))
		require"inc/content/".$content.".php";
	elseif(file_exists(SITE_ADMIN_DIR."/inc/content/".$content.".php")){
		require(SITE_ADMIN_DIR."/inc/content/".$content.".php");
	}
	else{
	
		if(file_exists("inc/content/".$main.".php"))
			require"inc/content/".$main.".php";
		elseif(file_exists(SITE_ADMIN_DIR."/inc/content/".$main.".php"))
			require(SITE_ADMIN_DIR."/inc/content/".$main.".php");
		else	echo "Раздел находится в разработке";
	}
}
?>