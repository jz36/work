<?
#========================================
# ������� ������� �� ��������

if ($main=="error"){
	$title="������";
	switch ($num){
		case "401": echo "<H3 class=red>������ 401.</H3>
			<p>�������� ������ ��� ������ ������������ ������. 
			���� �� ������ ������ ������� � ������ ���������, ���������� � ��������������.</p>";break;
		case "403": echo "<H3 class=red>������ 403.</H3>
			<p>��������, �������� ������������ ���������������. � ��� ������ ��� �������.<br>
			�������	�� ���������";break;
		case "404": echo "<H3 class=red>������ 404.</H3>
			<p>��������, ������������� ���� ��������� �� ����������, ��� ��� ���� ����-�� ����������. 
			 �������� ��������� �� ����������� ����������";break;
		case "500": echo "<H3 class=red>������ 500.</H3>
			<p>������, ������� �� �������� ��������� ������ � ������. 
			�������� �� ����������� ����������. � ��������� ����� �� �������� �������������.";break;
		
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
		else	echo "������ ��������� � ����������";
	}
}
?>