<?
#����������� �������������#0
#===========================================================
# �������� � ������������ ������ �� �����

$table=$main;

$error="";
if(isset($_POST['subm'])){
  	$into=array();$values=array();$name=array();
  	#���������, ��� �� ��� ������ ������
  	//$login_id=s_select("id",$table,"name=\"�����\"");
  	//if (s_select("id","users","i$login_id=\"".$_POST['i'.$login_id]."\"")!="") {$error=1;}

  	#���� ��� �� ������� �����
  	$res=row_select("id,name,type,dop,alert",$table,"visible=1");
	while ($r=$res->ga()){
		if ($r["type"]!="data" && $r["type"]!="div") {
			if(!empty($_POST['i'.$r["id"]]) && $_POST['i'.$r["id"]]!="--") {
				$into[]="i".$r["id"];
				$values[]=addslashes(strip_tags($_POST['i'.$r["id"]]));
				$name[]=$r["name"];
				//echo $r["id"]." - ".$_POST['i'.$r["id"]]."<br>";	
			}
		}
		elseif($r["type"]=="data") {
			if($_POST['i'.$r["id"].'d']!="--" && $_POST['i'.$r["id"].'m']!="--" && $_POST['i'.$r["id"].'y']!="--"){
				//echo $r["id"]." - ".$_POST['i'.$r["id"].'d']." - ".$_POST['i'.$r["id"].'m']." - ".$_POST['i'.$r["id"].'y']."<br>";
				$into[]="i".$r["id"];
				$values[]=$_POST['i'.$r["id"].'y']."-".$_POST['i'.$r["id"].'m']."-".$_POST['i'.$r["id"].'d'];				
				$name[]=$r["name"];
			}
		}
		else{
			if(isset($_POST['i'.$r["id"]]) && $_POST['i'.$r["id"]]!="" && $_POST['i'.$r["id"]]!="--") {
				$into[]="";
				$values[]="";
				$name[]="";	
			}
		}
	}
	//s_insert("users",substr($into,0,-1).",schet,schet2,reg_time,last_time",substr($values,0,-1).",\"$schet\",\"$schet\",NOW(\"0000-00-00 00:00:00\"),NOW(\"0000-00-00 00:00:00\")");
	
	# ��������� ���� ������
	for ($i=0;$i<count($into);$i++){
		@$text.="<tr><td>".$name[$i]."</td><td>".$values[$i]."</td></tr>
		";
	}
	$email_to=array();
	$email_to[]=param("email",$main);
	
	# ���������� �����
	//====================================================
	// ������� ����� ������:
	$Email = new Email;
	
	// ��������� ������:
	$Email->EmailSubject = "����� ������/����� �� ����� '".PATH."'";
	
	// ������ �����������:
	$Email->Emails = $email_to;
	
	// E-mails �����������:
	$Email->EmailFrom = "zakaz@yamal-ekb.ru";
	
	// ��� ������ (text/html):
	$Email->EmailType = 'text/html';
	
	// ���� ������:
	$Email->EmailMessage = '<html>
	<head></head><body><table>'.
	$text
	.'</body></html>';
	
	//�������� ������:
	$Email->BuildMessage();
	
	//� ���������� ���, �������� �� ������:
	if($Email->SendEmail()){
	    //print "������ ����������, ����� �������� ���������.";
	    if($Email->EmailError !=0){
	        print $Email->EmailErrors;
	    }
	} else {
	    //print "������ ��� ����������� �����.\n<br>\n";
	    print $Email->EmailErrors;
	}
	
	
	
	echo "<p class='bold'>".s_select("content",$table,"id=100002","","","","1")."</p>";

}
#===========================================================
#������� ����� �����������

if (!isset($_POST['subm']) || $error>0){
	
	$res=row_select("id,name,type,content",$table,"visible=1");	
	if (($res->nr())!=0) {
	?>
	
	<p><?=s_select("content",$table,"id=100001","","","","1");?></p>
	<?if ($error==1) echo "<p class='bold'>��������, �� ���� �����  ��� �����, ���������� ��������� ������!</p>";?>
	<form action="<?=SPAGE?>?main=<?=$main?>" method=post enctype="multipart/form-data" name=adform 
	onSubmit="<? 
	while ($r=$res->ga()){?>
		if (document.adform.i<?=$r["id"]?>.value=='' || document.adform.i<?=$r["id"]?>.value=='--') {alert('��������� ���� `<?=$r["name"]?>`');return false;}
	<? if ($r["type"]=="email"){?>
		if(!check_email(document.adform.i<?=$r["id"]?>.value)) {alert('������� � ���� `<?=$r["name"]?>` ���������� �����, ���� �� ������� ��� ������');return false;}
	<?}if ($r["type"]=="data"){?>
		if (document.adform.i<?=$r["id"]?>d.value=='--' || document.adform.i<?=$r["id"]?>m.value=='--' || document.adform.i<?=$r["id"]?>y.value=='--') {alert('��������� ���� `<?=$r["name"]?>`');return false;}
	<?}if ($r["type"]=="select"){?>
		if (document.adform.i<?=$r["id"]?>.value=='--') {alert('��������� ���� `<?=$r["name"]?>`');return false;}
	<?}if ($r["type"]=="password2"){?>
		if (document.adform.i<?=$r["id"]?>.value!=document.adform.i<?=s_select("id",$table,"visible=1 AND type=\"password\"")?>.value) {alert('�� ����������� ����� ���� `<?=$r["name"]?>`');return false;}
	<?}}?>
	"> 
	<table class=table1 align=center> 
	<? 
	$res=row_select("id,name,type,content,dop,alert",$table,"visible=1");
	while ($r=$res->ga()){?>
		<tr>
		<td width=40%><?if ($r["type"]=="div") echo "<hr>"; else { echo $r["name"].":"; if ($r["alert"]==1) echo "<span class=red>*</span>"; }?></td>
		<td valign=top><?
		if ($error>0 && isset($_POST['i'.$r["id"]])) $content=$_POST['i'.$r["id"]];
		else $content=$r["content"];
		switch ($r["type"]){
			case "div":
				echo "<hr>";break;
			case "input":
				echo "<input name='i$r[0]' size=30 value='".$content."' maxlength=40>";break;
			case "textarea":
				echo "<textarea name='i$r[0]' rows=4 style='width:90%'>".$content."</textarea>";break;
			case "email":
				echo "<input name='i$r[0]' size=30 value='".$content."'>";break;
			case "select":
				echo "<select name='i$r[0]'>";
				$parts=explode("<br>",$r["content"]);
				for ($i=0;$i<count($parts);$i++){
					if (ereg ("--", $parts[$i])) $tmp="--"; else $tmp=$parts[$i];
					if ($tmp==$_POST['i'.$r["id"]]) $selected="selected"; else $selected="";
					echo "<option value='$tmp' $selected>$parts[$i]</option>";
				}
				echo "</select>";break;
			case "password":
				echo "<input name='i$r[0]' size=30 type='password' value='$content'>";break;
			case "password2":
				echo "<input name='i$r[0]' size=30 type='password' value='$content'>";break;
			case "data":
			if (isset($_POST['i'.$r["id"].'m'])) $post_m=$_POST['i'.$r["id"].'m'];else $post_m="";
			?><nobr>
				<select name="i<?=$r[0]?>d"><option value="--">--</option><?for ($i=1;$i<=31;$i++) {if ($i==$_POST['i'.$r["id"].'d']) $selected="selected"; else $selected=""; echo "<option value='$i' $selected>$i</option>";}?></select><select name="i<?=$r[0]?>m">
				<option value="--">--</option>
				<option value="01" <?if ('01'==$post_m) echo "selected"?>>������</option>
				<option value="02" <?if ('02'==$post_m) echo "selected"?>>�������</option>
				<option value="03" <?if ('03'==$post_m) echo "selected"?>>����</option>
				<option value="04" <?if ('04'==$post_m) echo "selected"?>>������</option>
				<option value="05" <?if ('05'==$post_m) echo "selected"?>>���</option>
				<option value="06" <?if ('06'==$post_m) echo "selected"?>>����</option>
				<option value="07" <?if ('07'==$post_m) echo "selected"?>>����</option>
				<option value="08" <?if ('08'==$post_m) echo "selected"?>>������</option>
				<option value="09" <?if ('09'==$post_m) echo "selected"?>>��������</option>
				<option value="10" <?if ('10'==$post_m) echo "selected"?>>�������</option>
				<option value="11" <?if ('11'==$post_m) echo "selected"?>>������</option>
				<option value="12" <?if ('12'==$post_m) echo "selected"?>>�������</option>
				</select><select name="i<?=$r[0]?>y"><option value="--">--</option><?for ($i=(date("Y")+1);$i>=1940;$i--) {if ($i==$_POST['i'.$r["id"].'y']) $selected="selected"; else $selected=""; echo "<option value='$i' $selected>$i</option>";}?></select>
				</nobr><?break;
				
		}
		?></td>
		</tr>
	
	<?
	}
	?>
</table>
<div style="padding:5px;padding-left:20px;text-align:center;" >
<input type=submit class=button value="��������� ������" name="save">
<input type=hidden name=subm value=1> 
<input type=hidden name=save value=1>
</div>
</form>
<?
	}
	else 
	{
	echo "������ ��������� � ����������";
	}
}

?>


