<?

#������� ������ ��������
if (empty($id)){

$res=row_select("id,name,content,visible,conditions,oplata,data","","visible=1");
while ($r=$res->ga()){?>
	<h3><?=$r["name"]?></h3><table width=100% class=table1><?
	if (!empty($r["content"])) echo "<tr><td valign=top width=20%><b>����������:</b></td><td>".$r["content"]."</td></tr>";
	if (!empty($r["conditions"])) echo "<tr><td valign=top><b>�������:</b></td><td>".$r["conditions"]."</td></tr>";
	if (!empty($r["oplata"])) echo "<tr><td valign=top><b>������:</b></td><td>".$r["oplata"]."</td></tr>";
	if (!empty($r["data"])) echo "<tr><td valign=top><b>���������&nbsp;��:</b></td><td><div style='float:left;'>".date_preobr($r["data"],2)." </div><div style='float:right;'><a href='".SPAGE."?main=".$main."&id=".$r["id"]."'>[��������� ���� ������ �]</a></div></td></tr>";
	?>
	</table><hr>
	<?}

}

#==================================================================
# ������������ �����, ���������� ������

elseif (isset($_POST['hid'])) {
	if($_POST['name']!='' && $_POST['msg']!=''){
		$tes=row_select("","","content='".$_POST['msg']."'");
		if($test=$tes->gr()){echo "<br><b>��� ��������� ��� ���������</b><br>";}
		else {
			echo "<br><b>�������, ���� ������ ����������. �� �������� � ���� � ��������� �����.</b><br>
			<script>
			alert('�������, ���� ������ ����������. �� �������� � ���� � ��������� �����.'); 
			window.history.go(-2);</script>";			
			$name=addslashes(strip_tags($_POST['name']));
			$msg=addslashes(strip_tags($_POST['msg']));
			$tel=addslashes(strip_tags($_POST['tel']));
			$vac=addslashes(strip_tags($_POST['vac']));
			$email=addslashes(strip_tags($_POST['email']));
			
			# ���������� �����
			$email_to=param("email",$main);
			if (empty($email_to)) $email_to=param("site_email"); 
			mail($email_to,"�� ����� \"".$site_title."\" ����� ������ � ������� '��������'.","
�� ����� \"".$site_title."\"  ����� ������ � ������� '��������'.
������ �� ���������: ".$vac.", \r\n
�����: ".$name.", \r\n
�������: ".$tel.", \r\n
E-mail: ".$email.", \r\n
������: ".$msg.", \r\n"
				
				);

		}
	}
	else
		echo "<br><b>��������� �� ���������.<br>��������� ��� ����.</b>";
}



#==================================================================
# ������� ����� ��� �������� ������

else {
?>



<p class="small white"><a name="quest">&nbsp;</a>��������� ����, ����������� ������� �������� ��� ����� � ����:<p>
<table border="0" cellpadding="0" cellspacing="2" class=table2 width=100%>
<form name="adform" method="post" onSubmit="if (adform.name.value=='') {alert('��������� ���� ���!');return false;} if (adform.msg.value=='') {alert('��������� ���� ���������!');return false;}">
<tr>
<td class="small" valign=top><label>������ �� ���������:</label></td>
<td><textarea name="vac" cols="50" rows="3"><?=s_select("name","","id=".$id)?></textarea></td>
</tr>
<tr>
<td class="small"><label>���:</label></td>
<td><input type="text" name="name" value="" size="40" maxlength="30"></td>
</tr>
<tr>
<td class="small"><label>�������:</label></td>
<td><input type="text" name="tel" size="40" value=""  maxlength="30"></td>
</tr>
<tr>
<td class="small"><label>e-mail:</label></td>
<td><input type="text" name="email" size="40" value=""  maxlength="30"></td>
</tr>
<tr>
<td class="small" valign=top><label>������:</label></td>
<td><textarea name="msg" cols="80" rows="15">
�����������
���� ������
���������� ����� ������
������� �������� �� ����� ������
������� � ����
� �.�.</textarea><br>
<input type="submit" name="Button" value="���������">
<input type='hidden' name='hid'>
</td>
</tr>
</form>
</table>

<?

}


#==================================================================

?>