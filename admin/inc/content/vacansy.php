<?

#выводим список вакансий
if (empty($id)){

$res=row_select("id,name,content,visible,conditions,oplata,data","","visible=1");
while ($r=$res->ga()){?>
	<h3><?=$r["name"]?></h3><table width=100% class=table1><?
	if (!empty($r["content"])) echo "<tr><td valign=top width=20%><b>Требования:</b></td><td>".$r["content"]."</td></tr>";
	if (!empty($r["conditions"])) echo "<tr><td valign=top><b>Условия:</b></td><td>".$r["conditions"]."</td></tr>";
	if (!empty($r["oplata"])) echo "<tr><td valign=top><b>Оплата:</b></td><td>".$r["oplata"]."</td></tr>";
	if (!empty($r["data"])) echo "<tr><td valign=top><b>Актуально&nbsp;до:</b></td><td><div style='float:left;'>".date_preobr($r["data"],2)." </div><div style='float:right;'><a href='".SPAGE."?main=".$main."&id=".$r["id"]."'>[отправить свое резюме »]</a></div></td></tr>";
	?>
	</table><hr>
	<?}

}

#==================================================================
# Обрабатываем форму, отправляем письмо

elseif (isset($_POST['hid'])) {
	if($_POST['name']!='' && $_POST['msg']!=''){
		$tes=row_select("","","content='".$_POST['msg']."'");
		if($test=$tes->gr()){echo "<br><b>Это сообщение уже добавлено</b><br>";}
		else {
			echo "<br><b>Спасибо, Ваше резюме отправлено. Мы свяжемся с вами в ближайшее время.</b><br>
			<script>
			alert('Спасибо, Ваше резюме отправлено. Мы свяжемся с вами в ближайшее время.'); 
			window.history.go(-2);</script>";			
			$name=addslashes(strip_tags($_POST['name']));
			$msg=addslashes(strip_tags($_POST['msg']));
			$tel=addslashes(strip_tags($_POST['tel']));
			$vac=addslashes(strip_tags($_POST['vac']));
			$email=addslashes(strip_tags($_POST['email']));
			
			# отправляем емайл
			$email_to=param("email",$main);
			if (empty($email_to)) $email_to=param("site_email"); 
			mail($email_to,"На сайте \"".$site_title."\" новое резюме в разделе 'вакансии'.","
На сайте \"".$site_title."\"  новое резюме в разделе 'вакансии'.
Резюме на должность: ".$vac.", \r\n
Автор: ".$name.", \r\n
Телефон: ".$tel.", \r\n
E-mail: ".$email.", \r\n
Резюме: ".$msg.", \r\n"
				
				);

		}
	}
	else
		echo "<br><b>Сообщение не добавлено.<br>Заполните все поля.</b>";
}



#==================================================================
# Выводим форму для отправки резюме

else {
?>



<p class="small white"><a name="quest">&nbsp;</a>Заполните поля, обязательно оставте контакты для связи с Вами:<p>
<table border="0" cellpadding="0" cellspacing="2" class=table2 width=100%>
<form name="adform" method="post" onSubmit="if (adform.name.value=='') {alert('Заполните поле Имя!');return false;} if (adform.msg.value=='') {alert('Заполните поле Сообщение!');return false;}">
<tr>
<td class="small" valign=top><label>Резюме на должность:</label></td>
<td><textarea name="vac" cols="50" rows="3"><?=s_select("name","","id=".$id)?></textarea></td>
</tr>
<tr>
<td class="small"><label>Имя:</label></td>
<td><input type="text" name="name" value="" size="40" maxlength="30"></td>
</tr>
<tr>
<td class="small"><label>Телефон:</label></td>
<td><input type="text" name="tel" size="40" value=""  maxlength="30"></td>
</tr>
<tr>
<td class="small"><label>e-mail:</label></td>
<td><input type="text" name="email" size="40" value=""  maxlength="30"></td>
</tr>
<tr>
<td class="small" valign=top><label>Резюме:</label></td>
<td><textarea name="msg" cols="80" rows="15">
Образование
Опыт работы
Предыдущее место работы
Причины перехода на новую работу
Немного о себе
и т.д.</textarea><br>
<input type="submit" name="Button" value="Отправить">
<input type='hidden' name='hid'>
</td>
</tr>
</form>
</table>

<?

}


#==================================================================

?>