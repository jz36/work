<?
require"".SITE_ADMIN_DIR."/functions.php";
set_connection();
session_name(PREF."guest");
session_start();
	
$main="cart";
$dbg=1;

# ���������� $sid, ������� � �������,�� ��������� �� �� ����� � ������� �� � ��� ������
if (!empty($_COOKIE['cart_sid']))
	{$sid=$_COOKIE['cart_sid']; }
else 
	{$sid=session_id();}
# ���������� , �������������� �� �� ����� ��� ���
if (!empty($_SESSION['guest_id'])) $user_id=$_SESSION['guest_id']; else $user_id=0;



	//setcookie("".$pref."100001");
	//unset($_COOKIE[''.$pref.'100001']);


/////////////////////////////
# ��������� ����� � �������
if (!empty($add)){
	# ���������� � ���� sid, ���� ��� ��� ��� ����
	if (empty($_COOKIE['cart_sid'])) { setcookie("cart_sid",$sid,0x7FFFFFFF); }
	# ���� ���������� ������ 0 , �� ������� 1
	if ($kolvo==0) $kolvo=1;
	# ������� ����������� ����� � ����, ��� ��������� ��� ���������� ���� �� ��� ��������
	if(s_select("id","","top_table='".$top_table."' AND top_id=".$top_id)!=""){
		s_update("kolvo=kolvo+".$kolvo,"","top_table='".$top_table."' AND top_id=".$top_id);
	}
	else {
		s_insert("","id,top_table,top_id,kolvo,sid,user_id","'".(s_select("max(id)")+1)."','".$top_table."','".$top_id."','".$kolvo."','".$sid."','".$user_id."'");
	}
}
/////////////////////////////
# ������� ����� �� �������
if (!empty($did)){
	s_delete("","top_table='".$top_table."' AND top_id=".$top_id);
	?>	<script>//top.location.reload();</script><?
}

# ������� �������� �� ������� �� ������� � �� ������� ����������
if ($action=="view"){
	?>
	<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 class=cart>
	<?
	 $res=row_select("","","sid='".$sid."'");
	 $kol_in=$res->nr();	
	?>
	<div><a href="javascript:void(0);" onClick="top.location.href='<?=SPAGE?>?main=cart'" class=small><img src="img/i_cart.gif" width="24" height="22" align="absbottom" border=0>&nbsp; ������� ������� [<?=$kol_in?>]</a></div>
	</body></html>
	<?

}
?>
<?print_r($dbg_listing);?>