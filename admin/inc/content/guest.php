
<div class=small><?=s_select("content",$main,"top=333","","","","1")?> [<a href="#quest" class=small>��������� ���������</a>]</div>
<hr>

<?

$error=0;
if(isset($_POST['hidd'])){
$pg=1;  
//echo $_POST['hid2']."<br>";
//echo md5($_POST['hid']."PREF".substr(time(),0,7).session_id())."<br>";
//echo md5($_POST['hid']."PREF".(substr(time(),0,7)-1).session_id());

	if($_POST['npole1']!='' && $_POST['mpole1']!='' && ($_POST['hidd2']==(md5($_POST['hidd']."PREF".(substr(time(),0,7)).session_id())) || $_POST['hidd2']==(md5($_POST['hidd']."PREF".(substr(time(),0,7)-1).session_id())) )){
		$tes=row_select("id","","content='".$_POST['mpole1']."'");
		//echo ($tes->nr()).$_POST['mpole1'];
		if(($tes->nr())!=0){echo "<br><b>��� ��������� ��� ���������</b><br>";}
		else {
			$pos="";$error=0;
			$test=$_POST['mpole1'];
			$pos .= strpos($test, "[img");
			$pos .= strpos($test, "[Img");
			$pos .= strpos($test, "[IMG");
			$pos .= strpos($test, "[url");
			$pos .= strpos($test, "[URL");
			$pos .= strpos($test, "[Url");
			$pos .= strpos($test, "www.");	
			$pos .= strpos($test, "http:");	
			$pos .= strpos($test, "ice site");
			$pos .= strpos($test, "ool site");
			$pos .= strpos($test, "ool design");
			$pos .= strpos($test, "error");
			$pos .= strpos($test, "xxx");
			$pos .= strpos($test, "XXX");
			$pos .= strpos($test, "sex");
			$pos .= strpos($test, "viagra");
			$pos .= strpos($test, "VIAGRA");
			$pos .= strpos($test, "����");
			$pos .= strpos($test, "��������");
			$pos .= strpos($test, "site");
			if (!empty($pos) || strlen($test)>300) {
				$error=1;
			}
			
			if ($error!=1) {
				echo "<br><b>��������� ���������</b><br>";																																																														
				$name=addslashes(strip_tags($_POST['npole1']));																																																											
				$msg=addslashes(strip_tags($_POST['mpole1']));																																																											
				$email=addslashes(strip_tags($_POST['epole1']));																																																											
				$max_id=s_select("max(id)",$main)+1;																																																											
	         $ip=$_SERVER['REMOTE_ADDR'];																																																											
																																																															
																																																															
																																																															
				s_insert("","id,name,content,email,data,admin,ip","$max_id,'".$name."','".$msg."																																																											','".$email."','".date("Y-m-d")."','1#".date("Y-m-d")."#".date("H:i")."','".$ip."'");
																																																															
				# ���������� �����																																																											
				$email_to=param("email",$main);																																																											
				if (empty($email_to)) $email_to=param("site_email"); 																																																											
				mail($email_to,"�� ����� \"".$site_title."\" ����� ��������� � �������� �����","																																																											
�� ����� \"".$site_title."\" ��������� ����� ��������� � �������� �����.
�����: ".$name.", \r\n
E-mail: ".$email.", \r\n
���������: ".$msg.", \r\n
���������� ����� �����: ".PATH.SPAGE."?main=".$main." \r\n"
				);
			
			}
			else {
				
				
				echo "<br><span class='bold red'>��������� �� ���������. ���������� ������� �����. � ������ ������, ���������� � ���������� �����.</span><br>";

			}

		}
	}
	else
		echo "<br><b>��������� �� ���������.</b>";
}

# ==============================================================================

# ������� ���������

# ���������, �������� ���� ������ ��� ���������� ���������
if (!empty($id)){ 
	$tid="AND id=".$id;
	echo 	"<p><a href='".SPAGE."?main=".$main."'>��������� � ������ ���� ���������</a></p>";
	} 
else  { 
	pager(1,"","7","","","��������:");
	$tid=""; 
}
#========

?>
<table width="100%" height="1" border="0" cellspacing="0" cellpadding="5" class=table1>
<?
$res=row_select_pages("id,data,email,name,content","","top=0 AND visible=1 ".$tid,"id DESC");
while($row=$res->ga()){
	$pos="";$error=0;
	$test=$row["content"];
	$pos .= strpos($test, "[img");
	$pos .= strpos($test, "[Img");
	$pos .= strpos($test, "[IMG");
	$pos .= strpos($test, "[url");
	$pos .= strpos($test, "[URL");
	$pos .= strpos($test, "[Url");
	$pos .= strpos($test, "www.");	
	$pos .= strpos($test, "http:");	
	$pos .= strpos($test, "ice site");
	$pos .= strpos($test, "ool site");
	$pos .= strpos($test, "ool design");
	$pos .= strpos($test, "error");
	$pos .= strpos($test, "xxx");
	$pos .= strpos($test, "XXX");
	$pos .= strpos($test, "sex");
	$pos .= strpos($test, "viagra");
	$pos .= strpos($test, "VIAGRA");
	$pos .= strpos($test, " ����");
	$pos .= strpos($test, "��������");
	$pos .= strpos($test, "site");
	if (!empty($pos) || strlen($test)>600) {
		$error=1;
	}
	
	if ($error!=1){
	$rr=row_select("email,name,content","","top=".$row["id"]);?>
	<tr>
	<td width=22% align=left valign=top bgcolor="#F5F5F5" class="small bgcolor2" >
	<div class="small">[<?=remakedata($row["data"])?>] �����: <?if (!empty($row["email"])) {?><a href="mailto:<?=$row["email"]?>" class="small"><?}?><?=$row["name"]?></a><div style="float:right" class=small><a href="<?=SPAGE."?main=".$main."&id=".$row["id"]?>">_</a></div></div>
	
	</td></tr><tr>
	<td align="left" height=40>
	<?=$row["content"]?>
	<?if($row2=$rr->ga()) { ?>
	<div class=comment><b>�������� </b><a href="mailto:<?=$row2["email"]?>"><?=$row2["name"]?></a>:<br><?=$row2["content"]?></div>
	<?}?>
	</td>
	</tr>
<?	}
}?>
<tr>
<td>
<p class="small"><a name="quest">&nbsp;</a>������� ����� ���� ������ ��� �������� ���������:<p>
<table border="0" cellpadding="0" cellspacing="2" class=table2 width=100%>
<form name="addform" method="post" onSubmit="if (addform.npole1.value=='') {alert('��������� ���� ���!');return false;} if (addform.mpole1.value=='') {alert('��������� ���� ���������!');return false;}">
<tr>
<td class="text9px"><label>���:</label></td>
<td><input type="text" name="npole1" value="" size="40" maxlength="30"></td>
</tr>
<tr>
<td class="text9px"><label>e-mail:</label></td>
<td><input type="text" name="epole1" size="40" value=""  maxlength="30"></td>
</tr>
<tr>
<td class="text9px" valign=top><label>���������:</label></td>
<td><textarea name="mpole1" style="width:98%;" rows="10" maxlength='500'></textarea><br>
<?
if ($ris=test_ris("_butt_send","img","../")) { 
	$razm=getimagesize("img/".$ris);				
	$input="type='image' src='img/".$ris."' class='noborder submit' style='width:".$razm[0]."px;height:".$razm[1]."px'"; 
	
}
else  
	$input="type=submit value='���������' class=submit";

$rand=RAND();
?>
<input <?=$input?> 
<input type="submit" name="Button" value="���������" class=button>
<input type='hidden' name='hidd' value="<?=$rand?>">
<input type='hidden' name='hidd2' value="<?=md5($rand."PREF".substr(time(),0,7).session_id())?>">
</td>
</tr>
</form>
</table>
</td>
</tr>
</table>

<?
if (empty($id)) {
	pager(1,"","7","","","��������:");
}


?>