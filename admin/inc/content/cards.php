<?
#�������� �� �����#0

//==============================================================================
// ������ �����, ��� �����������
//if (!isset($_SESSION['guest_name'])) 
// �����, � ������������
//elseif (isset($_SESSION['guest_name'])) 
//$card_id=$_SESSION['guest_info']["id"];

//==============================================================================
	
	$error="";
	$card_id=session_id();
	$folder="img/cards";
	$makefolder=@mkdir($folder, 0755);
	
	// ���� �������� ����� ���� � ����, �� ������� �� ��������� ����� ��
	$res=row_select("id","","top='0'");
	if (($res->nr())==1) {
		$r=$res->ga(); 
		$id=$r["id"];
	}
	
	$card_type=".".substr(getimg($main,$id,"b"),-3);
	if (!empty($id)) {
		$card_blank=$main.$id."b".$card_type;
		$card_this=$main.$id."b".$card_id.$card_type;
		
		$res=row_select("id,name,content,email_from,email_to,card_title,card_text,fraza","","id=".$id);
		$r0=$res->ga();
		// ���������, ���� �� ��� ����� �������� � ����� � ����������, ���� ���, �� ��������
		//if (!test_ris($card_blank,"img/cards")) 
		copy(IMG_KAT."/".$card_blank, $folder."/".$card_blank); 
			
		
	}
	
	echo "<center>";
	
	// ��������� ������ �� �����, ������������
	if (isset($_POST["send"]) || isset($_POST["post_send"])) {
		$res=row_select("id,name","","top=".$id);
		while ($r=$res->ga()){
			$line[$r["id"]]=str_replace("<br>","\n",$_POST['line'][$r["id"]]);
			$line[$r["id"]]=stripslashes (strip_tags($line[$r["id"]]));
		
		}
			$email_from=stripslashes (strip_tags($_POST['email_from']));
			if (empty($email_from)) $email_from=$r0["email_from"];
			$email_to=stripslashes (strip_tags($_POST['email_to']));
			$theme=stripslashes (strip_tags($_POST['theme']));
			$msg=stripslashes (strip_tags($_POST['msg']));
	}
	else {
		$email_from=$r0["email_from"];
		$theme=$r0["card_title"];
		$msg=$r0["card_text"];
	
	}
	
	
	// ��������� ������������ e-mail'��
	if (isset($_POST["post_send"]) ){
		if (!check_email($email_to)) $error.="������� ���������� �-���� ����������.<br>";
		if (!check_email($email_from)) $error.="������� ���������� �-���� �����������, ��� �� ���������� ��� ������.<br>";
		//s_select("id","","email_from='".$email_from."' AND email_to='".$email_to."' AND name='".$theme."' AND content='".$msg."' AND tlines='".$line1." | ".$line2." | ".$line3." | ".$line4."'");
		if (empty($id)) $error.="��� �������� ��� ���� ���� ����������.";
		
		if (empty($error)) echo "<br><b>".$r0["fraza"]."</b><br>";	
		else echo "<p class='red bold'> !!! ".$error."</p>";
	}
	
	// ��������� �������� ���� �� ������ ���� �����
	if (isset($_POST["send"])) {
		
		if(!extension_loaded('gd')) dl('php_gd2.dll');
		if ($card_type==".gif"){
			$im_res=imagecreatefromgif(getcwd()."/".$folder."/".$card_blank);
		}
		if ($card_type==".jpg"){
			$im_res=imagecreatefromJPEG(getcwd()."/".$folder."/".$card_blank);
			
		}
		$width=imagesx($im_res); $height=imagesy($im_res);
		
		$im = ImageCreate($width, $height);
		$font = getcwd()."/img/fonts/graffity.ttf"; 
		$black = imagecolorclosest   ($im_res, 0, 0, 600);
		$white = imagecolorexact ($im_res, 255, 255, 255);
		
		//imageColorAllocate($image, 0xAA, 0xBB, 0xCC); 

		// ��������� ��������� �������
		$res=row_select("","","top=".$id);
		while ($r=$res->ga()){
			$font = getcwd()."/".SITE_ADMIN_DIR."/files/fonts/".$r["font_name"]; 
			if ($r["coord_x"]>=0) $coord_x=$r["coord_x"]; else $coord_x=$width+$r["coord_x"]; 
			if ($r["coord_y"]>=0) $coord_y=$r["coord_y"]; else $coord_y=$height+$r["coord_y"]; 
			if (!empty($r["color2"])){
				$color2="";
				$color2[1]=hexdec(substr($r["color2"],0,2));	$color2[2]=hexdec(substr($r["color2"],2,2));	$color2[3]=hexdec(substr($r["color2"],4,2));			
				$color2 = ImageColorAllocate($im_res, $color2[1],$color2[2], $color2[3]);
				ImageTTFText ($im_res, $r["font_size"], $r["ungle"], ($coord_x+2), ($coord_y+2), $color2, $font, win2uni($line[$r["id"]]));
			}
			if (!empty($r["color1"])){
				$color1="";
				$color1[1]=hexdec(substr($r["color1"],0,2));	$color1[2]=hexdec(substr($r["color1"],2,2));	$color1[3]=hexdec(substr($r["color1"],4,2));
				$color1 = ImageColorAllocate($im_res,$color1[1],$color1[2], $color1[3]);
				ImageTTFText ($im_res, $r["font_size"], $r["ungle"], $coord_x, $coord_y, $color1, $font, win2uni($line[$r["id"]]));
			}

			
		}		
		if ($card_type==".gif"){
			ImageGif ($im_res,getcwd()."/".$folder."/".$card_this);
		}
		if ($card_type==".jpg"){
			ImageGif ($im_res,getcwd()."/".$folder."/".$card_this);
			
		}
		ImageDestroy ($im_res);
	}
	else {
		// ���� �������� ���� ����� �� ���������� ��
		//if (!test_ris("ny_card".@$card_id,"img/cards"))
		$card_id="";
	
	}
	

	if (isset($_POST["post_send"]) && empty($error)){
			
			# ��������� ���� ������
			
			# ���������� �����
			//====================================================
			// ������� ����� ������:
			$Email = new Email;
			
			// ��������� ������:
			$Email->EmailSubject = $theme;
			
			// ������ �����������:
			$Email->Emails = array($email_to);
			
			// E-mails �����������:
			$Email->EmailFrom = $email_from;
			
			// ��� ������ (text/html):
			$Email->EmailType = 'text/html';
			
			// ���� ������:
			$Email->EmailMessage = '<html>
			<head></head><body>
			<center>'.$msg.'<br><br><img src="'.$card_this.'"></center>
			</body></html>';
			
			// ������ ������ ��� ��������:
			// ����� ������ ��������� � ���������� ������������ �������, ���� ��������� ������ ���� �� �����:
			// /home/trinex/files/testing.gif ��������.
			$Email->EmailFiles = array(getcwd()."/".$folder."/".$card_this);
			
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
			//====================================================
			
			// ��������� ������ � �������, � ��� ��� ��������� ��������
				$max_id=s_select("max(id)","")+1;
				//s_insert("","id,user_id,name,content,email_to,email_from,tlines,data",($max_id).",'".$card_id."','".$theme."','".$msg."','".$email_to."','".$email_from."','".$line1." | ".$line2." | ".$line3." | ".$line4."','".date("Y-m-d")."'");
	
			
	}
	

//==============================================================================
// ����� ������ � ���������

// ���� �� ������� ���������� ��������
if (empty($id) ) {
	
	
	
	
	}

// ���� ������� ��������
else {
	$res=row_select("id,name,content,email_from,email_to,card_title,card_text","","id=".$id);
	
	//print_r($line);
?>
	
	<form name="adform" method="post" onSubmit="">
	<img src="<?=$folder."/".$main.$id."b".$card_id.$card_type."?".rand(0,10000)?>" align=center>	
	<br>
	<input type="submit" name="Button" value="��������">
	<?if (!empty($card_id)) echo "<input type='submit' name='post_send' value='��������� �������� &gt;'>"?>
	<input type="reset" name="Button" value="��������"></center>
	<table border="0" cellpadding="0" cellspacing="2" class=table1 style="width:80%" align=center >
	<?
	$res2=row_select("id,name,content, fwidth","","top=".$id);
	while($r2=$res2->ga()) {
		if (isset($_POST["send"])) $value=$line[$r2["id"]]; else $value=$r2["content"];
		$value=str_replace("<br>","\n",$value);
		echo "<tr><td class=''><label>".$r2["name"]."</label></td>\n";
		echo "<td><textarea  style='width:100%' rows='3' name='line[".$r2["id"]."]' maxlength='".$r2["fwidth"]."'>".$value."</textarea></td>\n</tr>\n";
	
	}
	?>
	
	<tr>
	<td class="text9px" width=50% ><label>E-mail �� ������� �������� ������:</label></td>
	<td><input type="text" name="email_to" style='width:100%' value="<?=@$email_to?>"  maxlength="40"></td>
	</tr>
	<tr>
	<td class="text9px" ><label>� ������ e-mail ����� ���������� ������:</label></td>
	<td><input type="text" name="email_from" value="<?=@$email_from?>"  style='width:100%' maxlength="40"></td>
	</tr>
	<tr>
	<td class="text9px"><label>���� ������:</label></td>
	<td><input type="text" name="theme"  style='width:100%' value="<?=@$theme?>"  maxlength="50"></td>
	</tr>
	<tr>
	<td class="text9px" valign=top><label>���������:</label></td>
	<td><textarea name="msg"  style='width:100%' rows="5"><?=@$msg?></textarea><br>
	<input type='hidden' name='send'>
	</td>
	</tr>
	</table>	
	<div align=center>
	<input type="submit" name="Button" value="��������">
	<?if (!empty($card_id)) echo "<input type='submit' name='post_send' value='��������� �������� &gt;'>"?>
	<input type="reset" name="Button" value="��������">
	</div>
	</form>
<?
}
?>


