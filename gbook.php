<?php
session_start();

# гостевая книга / вопрос-ответ, доп. поле - org
# версия от 20.01.2007
# strana-fantasy.ru

$adminmail='info@britannix.ru';
//$adminmail='s@d1.ru';
//$adminmail='kortes@e1.ru';


if (!defined("NX_PATH")) define ("NX_PATH", "./");
require_once(NX_PATH.'wedadmin/lib/global.lib.php');
require_once(NX_PATH.'wedadmin/config.inc.php');
require_once(NX_PATH.'wedadmin/lib/mysql.lib.php');
@set_magic_quotes_runtime(0);
//validate_post_vars();


$title='Вопрос-ответ';

$top="<h1><span class=centerMenu>$title</span></h1>";
$title_head=" - $title";
include ("header.new.inc.php");
print '<table cellpadding=10 border=0 cellspacing=0 width="100%"><TR><TD>';
db_open();
//error_reporting (E_ALL);
?>

<br>
<?php

function showForm()
{
global $title;
?>

<BR>

   <H3>ЗАКАЗАТЬ ЗВОНОК:</H3>
   <p>Если по какой-либо причине вы не можете нам позвонить, то воспользуйтесь услугой заказа обратного звонка. Просто укажите свое имя, номер телефона, предпочтительное время звонка и мы сами вам позвоним.</p>
   <form action="" method="POST">
      <table>
      <!-- tr><td>
      <span style="width:100;"><p>Организация:</p></span>
      </td>
      <td><input type="text" name="gbookorg" size="30" value="<?php echo "{$_REQUEST['gbookorg']}";?>"></td></tr -->

      <tr><td>
      <span style="width:100;"><p>Фамилия, Имя, Отчество*:</p></span>
      </td>
      <td><input type="text" name="gbookname" size="30" value="<?php echo "{$_REQUEST['gbookname']}";?>"></td></tr>
      <tr><td>
      <span style="width:100;"><p>Телефон с кодом города*:</p></span>
      </td>
      <td><input type="text" name="phone" size="30" value="<?php echo "{$_REQUEST['phone']}";?>"></td></tr>
      <tr><td>
      <span style="width:100;"><p>Удобное Вам время для звонка:</p></span>
      </td>
      <td><select name="time"><?php
	  echo "<option>Выбрать</option>";
	  for ($h = 9; $h<=18;$h++){
		$sh = $_REQUEST['time'];
		echo '<option value="'.$h.'"'.($h==$sh?' selected="selected"':'').'>'.($h<10?'0':'').$h.':00</option>';
	  }
	  ?></select></td></tr>
      <tr><td>
      <span style="width:100;"><p>E-mail:</p></span>
      </td>
      <td><input type="text" name="gbookemail" size="30" value="<?php echo "{$_REQUEST['gbookemail']}";?>"></td></tr>
      <tr><td>
      <p>Защитный код:</p><img src="/modules/captcha/imagekey.php" border="0" alt="Защитный код" />
      </td>
      <td><input type="text" name="captcha" size="5" value=""><br>
		<font size="-2">(номер на картинке)</font></td></tr>
      </table>
      <span><p>Ваш вопрос:</p></span><br>
      <textarea cols="40" rows="4" name="gbookmessage" ><?php echo "{$_REQUEST['gbookmessage']}";?></textarea>  
      <br><br>
      <input type="submit" name="gbooksendmessage" value="Отправить">       
      
   </form>  
<BR><BR><hr size=1>
<?php
}


function showMessages()
{
    
     //Извлекаем из базы открытые  сообщения



        $string="SELECT data, name, text FROM gbook WHERE hidden='0' ORDER BY id desc";

        $res= array();

        $i=0;

        if ($q = mysql_query($string)){

		        while ($row = mysql_fetch_array($q)){

		            

		        $res[$i]['data']=$row['data'];      

		        $res[$i]['name']=$row['name'];

		        $res[$i]['text']=$row['text'];            

                ++$i;      

                        

		        }

               }  


        //Вывод сообщений
        
        foreach ($res as $v) {

             echo "<h3>".$v[name]."</h3> <p>".$v[text]."</p>";

		  	 if ($v[answer]) echo "<BR>\n<h3>Ответ: </h3><p>".$v[answer]."</p>\n";

             echo "<br><br>";            
        }


     
   
}

function checkForm()
{
    
global $adminmail;    

    // Если  не ввели сообщение, ругаемся
    if (isset($_REQUEST['gbooksendmessage']))
    {
		if (md5(md5($_POST['captcha'])) != $_SESSION['captcha']) {
			print "<B><FONT COLOR=RED>Ошибка! Не верный защитный код!</FONT></B><BR><BR>";
			return 0;
		}
     if ($_REQUEST['gbookmessage'] == ''){
      print "<B><FONT COLOR=RED>Пожалуйста, введите текст сообщения!</FONT></B><BR><BR>";
      showForm();     
      return 0;
     }
     else 
     // ввели  сообщение
     {
        
        // сохраняем в базе
        $name= $_REQUEST['gbookname'];    
        $email= $_REQUEST['gbookemail'];
        //$org= $_REQUEST['gbookorg'];
        $text= 'Позвонить на номер '.$_REQUEST['phone'].' в '.$_REQUEST['time'].':00 '.$_REQUEST['gbookmessage'];
        $data= date('Y-m-d H:i:s');
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        //echo "name=$name\n";
        //echo "email=$email\n";
        //echo "text=$text\n";
        //echo "data=$data\n";
        //echo "user_ip=$user_ip\n";
        
        $string="INSERT INTO gbook (data,ip, name, email, text, hidden) VALUES ('$data','$user_ip','$name', '$email', '$text', '1')";
        //echo "string=$string";
        $query=mysql_query($string) or die('cant query '.mysql_error());
        $last_id= mysql_insert_id();
        
        //Посылаем письмо
        
        $text="
С сайта поступил новый вопрос:
Имя:                    $_REQUEST[gbookname]
E-mail:                 $_REQUEST[gbookemail]
Текст сообщения:        $text
            
         

        
";
/*Показать http://".$_SERVER['SERVER_NAME']."/wedadmin/gbook.php?way=show&id=$last_id\n 
Редактировать http://".$_SERVER['SERVER_NAME']."/wedadmin/gbook.php?way=edit&id=$last_id\n\n 

Удалить http://".$_SERVER['SERVER_NAME']."/wedadmin/gbook.php?way=delete&id=$last_id\n 

Логин:  britannix
Пароль: 3750000t
*/
 

  $subject="Новый вопрос с сайта";


  mail($adminmail, $subject, $text,'Content-type: text/plain; charset=windows-1251');

 
echo '<B><FONT COLOR="RED">Спасибо! Вам перезвонят.</FONT></B><BR><BR>';        
        
        return 0;
     }   
      
     }
     else 
     {        
        showForm();   
     }
        
}  
?>        
    
<?php        
     
     
     
checkForm();



//showMessages();




   print '</td></tr></table>';
   include ("footer.new.inc.php"); 

?>
