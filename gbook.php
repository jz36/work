<?php
session_start();

# �������� ����� / ������-�����, ���. ���� - org
# ������ �� 20.01.2007
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


$title='������-�����';

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

   <H3>�������� ������:</H3>
   <p>���� �� �����-���� ������� �� �� ������ ��� ���������, �� �������������� ������� ������ ��������� ������. ������ ������� ���� ���, ����� ��������, ���������������� ����� ������ � �� ���� ��� ��������.</p>
   <form action="" method="POST">
      <table>
      <!-- tr><td>
      <span style="width:100;"><p>�����������:</p></span>
      </td>
      <td><input type="text" name="gbookorg" size="30" value="<?php echo "{$_REQUEST['gbookorg']}";?>"></td></tr -->

      <tr><td>
      <span style="width:100;"><p>�������, ���, ��������*:</p></span>
      </td>
      <td><input type="text" name="gbookname" size="30" value="<?php echo "{$_REQUEST['gbookname']}";?>"></td></tr>
      <tr><td>
      <span style="width:100;"><p>������� � ����� ������*:</p></span>
      </td>
      <td><input type="text" name="phone" size="30" value="<?php echo "{$_REQUEST['phone']}";?>"></td></tr>
      <tr><td>
      <span style="width:100;"><p>������� ��� ����� ��� ������:</p></span>
      </td>
      <td><select name="time"><?php
	  echo "<option>�������</option>";
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
      <p>�������� ���:</p><img src="/modules/captcha/imagekey.php" border="0" alt="�������� ���" />
      </td>
      <td><input type="text" name="captcha" size="5" value=""><br>
		<font size="-2">(����� �� ��������)</font></td></tr>
      </table>
      <span><p>��� ������:</p></span><br>
      <textarea cols="40" rows="4" name="gbookmessage" ><?php echo "{$_REQUEST['gbookmessage']}";?></textarea>  
      <br><br>
      <input type="submit" name="gbooksendmessage" value="���������">       
      
   </form>  
<BR><BR><hr size=1>
<?php
}


function showMessages()
{
    
     //��������� �� ���� ��������  ���������



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


        //����� ���������
        
        foreach ($res as $v) {

             echo "<h3>".$v[name]."</h3> <p>".$v[text]."</p>";

		  	 if ($v[answer]) echo "<BR>\n<h3>�����: </h3><p>".$v[answer]."</p>\n";

             echo "<br><br>";            
        }


     
   
}

function checkForm()
{
    
global $adminmail;    

    // ����  �� ����� ���������, ��������
    if (isset($_REQUEST['gbooksendmessage']))
    {
		if (md5(md5($_POST['captcha'])) != $_SESSION['captcha']) {
			print "<B><FONT COLOR=RED>������! �� ������ �������� ���!</FONT></B><BR><BR>";
			return 0;
		}
     if ($_REQUEST['gbookmessage'] == ''){
      print "<B><FONT COLOR=RED>����������, ������� ����� ���������!</FONT></B><BR><BR>";
      showForm();     
      return 0;
     }
     else 
     // �����  ���������
     {
        
        // ��������� � ����
        $name= $_REQUEST['gbookname'];    
        $email= $_REQUEST['gbookemail'];
        //$org= $_REQUEST['gbookorg'];
        $text= '��������� �� ����� '.$_REQUEST['phone'].' � '.$_REQUEST['time'].':00 '.$_REQUEST['gbookmessage'];
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
        
        //�������� ������
        
        $text="
� ����� �������� ����� ������:
���:                    $_REQUEST[gbookname]
E-mail:                 $_REQUEST[gbookemail]
����� ���������:        $text
            
         

        
";
/*�������� http://".$_SERVER['SERVER_NAME']."/wedadmin/gbook.php?way=show&id=$last_id\n 
������������� http://".$_SERVER['SERVER_NAME']."/wedadmin/gbook.php?way=edit&id=$last_id\n\n 

������� http://".$_SERVER['SERVER_NAME']."/wedadmin/gbook.php?way=delete&id=$last_id\n 

�����:  britannix
������: 3750000t
*/
 

  $subject="����� ������ � �����";


  mail($adminmail, $subject, $text,'Content-type: text/plain; charset=windows-1251');

 
echo '<B><FONT COLOR="RED">�������! ��� ����������.</FONT></B><BR><BR>';        
        
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
