<style type="text/css">
<!--
.style2 {font-size: xx-large}
-->
</style>
<br />
<!--
<div style="border: 1px solid rgb(193, 193, 193); padding: 6px; background:#fff; font-size: 11px;">
<table cellpadding="0" cellspacing="0"  class="tableno">
	<tr>
		<td>
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td height="63" align="center"><a href="/index.php?cid=34&id=199"><img src="/img/germany.jpg" border="0"></a></td>
	</tr>
	<tr>
		<td width="307"><font color="#000099">
			<h3 align="center"><strong>Групповые поездки<br /> школьников <br />с руководителем:</strong></h3>
			<p><strong><a href="http://britannix.ru/index.php?cid=41&id=250">Великобритания</a></strong></p>
			<p><strong> <a href="http://britannix.ru/index.php?cid=41&id=252">Мальта</a></strong></p>
			<p><strong> <a href="http://britannix.ru/index.php?cid=41&id=251">Канада
				
				</a></strong></p>
			<p><strong><a href="http://britannix.ru/index.php?cid=34&id=199">Германия</a> </strong></p>
		</font>
			</td>
	</tr>
</table>
</td>
	</tr>
</table>
</div>
<br /> -->

<? 

function print_wed_library_items_id($id){
	$Q="SELECT full_text FROM `wed_library_items` WHERE `id` = '$id'";
	$res=mysql_query($Q);
	$H=mysql_fetch_array($res);
	print $H[full_text];
}


 print_wed_library_items_id('302'); 

?>
<!-- div style="border: 1px solid rgb(193, 193, 193); padding: 6px; background:#fff; font-size: 11px;">
<table cellpadding="0" cellspacing="0"  class="tableno">
	<tr>
		<td><a href="http://britannix.ru/index.php?id=278"><img src="http://britannix.ru/img/baner.gif"  alt="Горячие новости для тех, кто хочет поехать по программе Work Travel USA!" /></a></td>
	</tr>
</table>
</div><BR><BR>
<div style="border: 1px solid rgb(193, 193, 193); padding: 6px; background:#fff; font-size: 11px;">
<table cellpadding="0" cellspacing="0"  class="tableno">
	<tr>
		<td><a href="/index.php?all_news&details=44"><img src="/img/english_club.jpg"  alt="Английский разговорный клуб" title="Английский разговорный клуб" border="0"/></a></td>
	</tr>
</table>


</div -->

<!--<div style="border: 1px solid rgb(193, 193, 193); padding: 6px; background:#fff; font-size: 11px;">
<table cellpadding="0" cellspacing="0"  class="tableno">
	<tr>
		<td><table border="0" cellspacing="0" cellpadding="5" ">
	<tr>
		<td width="307" valign="top"><p align="center"><img src="http://britannix.ur.ru/img/foto/Logo.gif" ></p>
				<h2 align="center"><strong><a href="http://britannix.ru/index.php?id=168">Дистанционное    изучение <br>
						английского языка</a><strong> </strong></h2></td>
	</tr>
	<tr>
		<td width="307" valign="top"><p> Изучайте    английский язык так же,&nbsp; как его изучают в языковых школах в Нью-Йорке,    Торонто или Лондоне - но у себя дома! Проходить обучение Вы можете в удобном    для Вас месте, в любое время, благодаря видео-урокам от Englishlink.</p></td>
	</tr>
	<tr>
		<td width="307" valign="top" ><p align="center"><a href="http://www.englishlink.tv/admin/secure/register_SampleCourse.asp?Rcode=BR1"><img src="http://britannix.ur.ru/img/foto/Try-a-Free-Lesson.gif" border="0"></a></p>
		</td>
	</tr>
	<tr>
		<td width="307" valign="top"><p align="center"><a href="http://www.englishlink.tv/admin/secure/register_SampleCourse.asp?Rcode=BR1">Drake training system/…….. </a></p></td>
	</tr>
</table></td>
	</tr>
</table>
</div> -->