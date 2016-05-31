<?
$form_id='zapisatsa';
$form_mail='3750000@britannix.ru';
//$form_mail='s@d1.ru';
//$form_mail='amendelev@gmail.com';
$form_subject=$_SERVER['HTTP_HOST'].', запись на консультацию';
ob_start();

$form=array(); $formname=array();
$form['Фамилия, имя, отчество*']='fio,text';
$form['Телефон*']='tel,text';
$form['e-mail']='email,text';
$form['Выберите учебное заведение*']=array(
'uz'
,'--выбрать--'
,'Cambridge Education Group'
,'EAC/ELC'
,'Eurasia Institute'
,'HTMi'
,'Itchen Sixth Form College'
,'Kings Colleges'
,'St Andrew’s / Select English'
,'Malvern House'
);
$form['Предпочтительное время встречи*']='time,text';
$form['Комментарии']='comment,textarea';

$msg='';
while ($_POST[$form_id]) {
	$tel=$_POST['tel']; $tel=trim($tel);// +7-922-606-72-04
//	$tel=strtr(" ","",$tel);
	
	if (preg_match("@[^\d()+-]@",$tel)) {
		$msg="Ошибка в телефоне! Используйте только цифры, скобки и знаки + и -";
		break;
	}
	if (preg_match_all("@[\d]@", $tel, $m)<5) {
		$msg="Ошибка в телефоне! Количество цифр меньше пяти!";
		break;
	};
	$fio=trim($_POST['fio']);
	if (strlen($fio)<2) {
		$msg="Заполните фамилию, имя, отчество!";
		break;
	};
//	$comment=trim($_POST['comment']);
	$uz=$_POST['uz'];
	
	if (!$uz || '--'===substr($uz,0,2)) {
		$msg="Выберите учебное заведение!";
		break;
	};
	
	ob_start();
?><table border="1" bordercolor="eeeeee"><?
foreach ($form as $label=>$d) {
	if (is_array($d)) {
		$tip='select';
		$name=array_shift($d);
		$select_opts=$d;
	}else{
		list($name,$tip)=explode(',',$d,2);
		if (!in_array($tip,array('text','textarea'))) $tip='text';
	}
	?>
<tr><td><?=$label?></td>
<td><?
if ('select'==$tip) {
	foreach ($select_opts as $val) {
		$sel=''; if ($val==$_REQUEST[$name]) echo hh($val);
	};
}elseif ('textarea'==$tip) {
	$val=@hh($_REQUEST[$name]);
	echo $val;
}else{
	$val=@hh($_REQUEST[$name]);
	echo $val;
}
?></td></tr>
<?}?></table><?
	$telo=ob_get_clean();
	
	
	$date=date("d.m.Y H:i");
	$uri=hh( $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] );
$body=<<<BODY
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>$form_subject</title>
</head>
<body>
<p>На <a href="$uri">{$_SERVER['HTTP_HOST']}</a> человек заполнил форму 
<br />Записаться на индивидуальную консультацию
<br />Заполнено $date</p>
$telo
<hr />
{$_SERVER['HTTP_HOST']}, заполнено с IP: {$_SERVER['REMOTE_ADDR']}
</body>
</html>

BODY;
	
	include_once dirname( __FILE__ ).'/lib/PHPMailer_v5.1/class.phpmailer.php';
	$mail             = new PHPMailer();
	$mail->CharSet='Windows-1251';
	$email=trim($_POST['email']);
	if (!preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9.-]+$/",$email)) $email='';
	if ($email) $mail->SetFrom($email, $fio);
	$mail->AddAddress($form_mail);
	$mail->Subject    = $form_subject;
	$mail->MsgHTML($body);
//echo "xxx ".$body;
	$mail->Send();
	$msg='ok';
	break;
};
if ($msg==='ok') {
	$url=$_SERVER['PHP_SELF'].'?id='.@intval($id).'&msg=ok';
	header("Location: ".$url);
	exit;
}


?>
<style type="text/css">
table#ftable td {border: none; padding: 2px;  margin:0 auto;}
table#ftable td.th {text-align:right;}
table#ftable {border: none;}
table#ftable td, table#ftable input,table#ftable textarea,table#ftable select {font-size:12px; width:250px;}
</style>
<form action="<?=$_SERVER['PHP_SELF'].'?id='.@intval($id);?>" method="post">
<input type="hidden" name="id" value="<?=@intval($id);?>" />


<?
$hide_form=false;
if ($_GET['msg']==='ok') {
	$msg='Спасибо!<br />Сотрудники Агентства образовательных путешествий Британикс<br />свяжутся с Вами в ближайшее время для подтверждения.';
	$hide_form=true;
}
if ($msg){?><div style="color:#aa0000; font-size:14px;"><?=$msg?></div><?}?>

<?if (!$hide_form) {?>

<table id="ftable">
<?

function hh($s) {return htmlspecialchars($s);};


foreach ($form as $label=>$d) {
	if (is_array($d)) {
		$tip='select';
		$name=array_shift($d);
		$select_opts=$d;
	}else{
		list($name,$tip)=explode(',',$d,2);
		if (!in_array($tip,array('text','textarea'))) $tip='text';
	}
	?>
<tr><td class="th"><?=$label?></td>
<td><?
if ('select'==$tip) {
	echo "<select name=\"$name\">";
	foreach ($select_opts as $val) {
		$sel=''; if ($val==$_REQUEST[$name]) $sel='selected="1"';
		echo "<option $sel>". hh($val)."</option>";
	};
	echo "</select>";
}elseif ('textarea'==$tip) {
	$val=@$_REQUEST[$name];
	$val=htmlspecialchars($val, ENT_NOQUOTES);
	echo "<textarea name=\"$name\">$val</textarea>";
}else{
	$val=@$_REQUEST[$name];
	$val=hh($val);
	echo "<input name=\"$name\" value=\"$val\">";
}
?></td></tr>
<?}?>
<tr><td>&nbsp;</td>
<td><input type="submit" name="<?=$form_id?>"  value="Записаться!" /></td></tr>
</table>

<?}//hide_form===?>

</form>
<?
$text=ob_get_clean();
return $text;
?>