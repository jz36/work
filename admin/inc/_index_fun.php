<?

#========================================
#������� ������� ���������� ��� �����������

//print_r(get_included_files());
//exit;

if (!defined("MOD_REWRITE")) define("MOD_REWRITE","0");

# ��������� ��������� � ��������� ��������

	$site_title=param("site_title");

# ���������� �������� ��������� ��������, ����� ���� main
if (!isset($main)){

	if (isset($main_default)) $main=$main_default;
	else {
	
		$main="main";
		
		$title="����� ����������";
		$content="about";
	}
}
elseif ($main=="error") $title="������";

# ���������� ��������� ��� ������ ���������� �� ����
if (!empty($main)){

	$res=row_select("name,id,menu_top","admin_tree","page=\"$main\"");
	$r=$res->ga();

	$title=$r["name"];
	if (!empty($id)) { $page_title=s_select("name",$main,"id='".$id."'").". ".$title;} else $page_title=$title;
	$idtop=$r["id"];
	$menutop=$r["menu_top"];
	if ($menutop==0) $menutop=$idtop;
	$tmp=row_select("id,name","admin_tree","id='$menutop'");
	$r=$tmp->ga();
	$menutop=$r["id"];
	$menutopname=$r["name"];

}
#============================================
# �������� � ��������

# ������� ������ ��� ������ �� ����
if (!isset($admin_preview)){
	session_name(PREF."guest");
	session_start();
}


#���� ���� ������� �� ������
if ($main=="session_exit") {
	unset($_SESSION['guest_name'],$_SESSION['guest_pass'],$_SESSION['guest_info']); 	
	//session_destroy();
	?><script>location.href="<?=SPAGE?>?main=<?=@$to?>&id=<?=@$id?>"</script><?
	break;
}

# ������������� ��� ������ ������������� � ���������
if (isset($admin_preview)){

	$main="admin_preview";
	$title="������������";
	$content="admin_preview";
	$id=0;
}

#========================================
# ������� ������ � ���������� ���������


	$dir_index = dir("inc"); 
	while($get_filename=$dir_index->read()) { 
		if ( $get_filename != '.' && $get_filename != '..' && $get_filename != 'content' && $get_filename != '_index_fun.php' && $get_filename != 'content.php'){
			require("inc/".$get_filename);
	}} 
	$dir_index->close();
	
	
	$dir_index = dir(SITE_ADMIN_DIR."/inc");  
	while($get_filename=$dir_index->read()) { 
		if ( $get_filename != '.' && $get_filename != '..' && $get_filename != 'content' &&  $get_filename != '_index_fun.php' && $get_filename != 'content.php'){
			if (!file_exists("inc/".$get_filename))
			require(SITE_ADMIN_DIR."/inc/".$get_filename);
	}}
	$dir_index->close();






?>