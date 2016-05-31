<?php

$fio = htmlspecialchars($_POST["fio"]);
$phone = htmlspecialchars($_POST["phone"]);
$bezspama = htmlspecialchars($_POST["bezspama"]);
 

$address = "info@britannix.ru";
$address2 = "joni@d1.ru";
$sub = "Заявка с сайта britannix.ru Зимние каникулы";
 

$mes = "Заявка с сайта britannix.ru Зимние каникулы\n
Имя: $fio 
Телефон: $phone
";
 
 
if (empty($bezspama)) 
{

$from  = "From: $fio <$phone> \r\n Reply-To: $phone \r\n Content-type: text/html; charset=\"windows-1251\""; 
//mail($address, $sub, $mes, $from);
//mail($address2, $sub, $mes, $from);


}
else {exit;}

echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $_SERVER['HTTP_REFERER'] . "\">";

?>