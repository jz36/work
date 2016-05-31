<?php
if (!defined("WA_PATH")) define ("WA_PATH", "./");
if (!defined("NX_PATH")) define ("NX_PATH", "./");
require_once(NX_PATH.'lib/global.lib.php');
require_once(NX_PATH.'config.inc.php');
require_once(NX_PATH.'lib/mysql.lib.php');
@set_magic_quotes_runtime(0);
//validate_post_vars();

require_once("header.inc.php");

    db_open(); 
if (isset($_GET[id]))
{
    $getid=intval($_GET[id]); 
}
else  
{        
    $getid=0;
}
     

function showEditForm($getid)
{
  if ($getid>0)
  {  
     $v=array(); 
     
            
       // Берём сообщение, которое хотим редактировать из базы
       $string="SELECT id, data, email, name, text, hidden FROM gbook WHERE id=$getid";
       $query= mysql_query($string) or die ('Не получилось взять сообщение'.mysql_error());
       
       
       $res=mysql_fetch_array($query);
 
             $v['id']=$res['id'];
             $v['data']=$res['data'];
             $v['email']=$res['email'];
             $v['name']=$res['name'];
             $v['text']=$res['text'];  
             $v['hidden']=$res['hidden'];   


       
?>    



    <form method="POST" action="/gbook/admin/">
      <table width="500" cols="2">
      <tr>
      <td>
          <span>Видимо:</span>           
      </td><td><input type="checkbox"  <?php if ($v['hidden']=='0') {echo " checked"; }?> name="newhidden"></td></tr>
      <tr><td width="50">
           &nbsp;      
      </td>
      <td>
      <input type="hidden" name="newid" value="<?php echo "$v[id]";?>">
      </td></tr>
      <tr><td width="50">
      <span>Имя:</span>
      </td>
      <td>   
      <input type="text" value="<?php echo "$v[name]";?>" name="newname">
      </td></tr>
      <tr><td width="50">
      <span>Дата:</span>
      </td>
      <td>   
      <input type="text" value="<?php echo "$v[data]";?>" name="newdate">
      </td></tr>
      <tr><td width="50">
      <span>E-mail:</span>
      </td>
      <td>   
      <input type="text" value="<?php echo "$v[email]";?>" name="newemail">
      </td></tr>
      </table>
      <span>Сообщение:</span><br>   
      <textarea rows="8" cols="50" name="newtext"><?php echo "$v[text]";?></textarea><br>
      <input type="submit" name="messedited" value="Сохранить">
   </form>
 
<?php    
}
}

print '<a href="gbook.php"><h2>Гостевая</h2></a>';


if ($_GET['way']=='show')
{
     $upd0="UPDATE gbook SET hidden='0' WHERE id=$getid";
     
            $query= mysql_query($upd0) or die ('cannot modify data:'.mysql_error());  
     echo "<B>Сообщение номер $getid показано</B><br><br>";
     $getid=0;   
}

if ($_GET['way']=='hide')
{
     $upd1="UPDATE gbook SET hidden='1' WHERE id=$getid";
            
            $query= mysql_query($upd1) or die ('cannot modify data:'.mysql_error());  
     echo "<B>Сообщение номер $getid скрыто</B><br><br>";      
     $getid=0;
}     


if (($_GET['way'])=='delete')
{
                 
        if (isset($_GET[id]))
        {       
            $string='DELETE FROM gbook WHERE id='.$getid;
            
            $query= mysql_query($string) or die ('cannot modify data:'.mysql_error());  
            echo "<B>Позиция номер $getid удалена</B><br><br>";
            $getid=0;
        }
        
     
     
}

// Если сообщение исправлено, тогда заносим в базу исправления
if (isset($_REQUEST['messedited']))
{
    echo "<FONT COLOR=\"RED\">Сообщение номер $_REQUEST[newid] исправлено</FONT>";
    
         

if (($_REQUEST['newhidden'])=='on')
     {   
         $hide=0;               
     }
     else 
     {
         $hide=1;
     }
     $string="UPDATE gbook SET data= '$_REQUEST[newdate]', name='$_REQUEST[newname]', email='$_REQUEST[newemail]', text='$_REQUEST[newtext]', hidden='$hide' ";
     $string.="WHERE id=$_REQUEST[newid]";

#print $string;
     
     
     $query=mysql_query($string) or die ('Не получилось редактировать:'.mysql_error());
          
$getid=$_REQUEST[newid];

}


    showEditForm($getid);
                         





?>

        <br>
<?php   
      // берём все сообщения из базы
   $string="SELECT id, data, email, name, text, hidden FROM gbook ORDER BY id DESC";
        
        $res= array();
        $i=0;
        if ($q = mysql_query($string)){
		        while ($row = mysql_fetch_array($q)){
		        $res[$i]['id']=$row['id'];          
		        $res[$i]['data']=$row['data'];
		        $res[$i]['email']=$row['email'];      
		        $res[$i]['name']=$row['name'];
		        $res[$i]['text']=$row['text'];
		        $res[$i]['hidden']=$row['hidden'];            
                ++$i;      
                        
		        }
               }


?>     
        <table width="500">
<?php        foreach($res as $v)
        {             
?>         
             
           <tr><td>
             <FONT COLOR="RED"><B><?php echo"$v[name]";?></B> [<?php echo "$v[data]]"; ?></FONT><BR>
           </td>
           <td>
                 <?php if ($v[hidden]=='1')
                 {?>
                  <a href="gbook.php?way=show&id=<?php echo "$v[id]";?>">Показать</a>
                 <?php }
                 else 
                 { ?>
                     <a href="gbook.php?way=hide&id=<?php echo "$v[id]";?>"><FONT color="Gray">Скрыть</font></a>
                 <?php } ?>
           </td></tr>
           <tr><td>
                   <?php echo "$v[email]\n";?>
           </td>
           <td>
                 
                 <a href="gbook.php?way=delete&id=<?php echo "$v[id]";?>" onClick="javascript: if (confirm('Удалить сообщение от <?php echo "$v[name]";?> ?')) { return true;} else { return false;}">Удалить</a>
           </td></tr>
           <tr><td>
                   <?php echo "$v[text]\n";?>
           </td>
           <td valign="top">
                 <a href="gbook.php?way=edit&id=<?php echo "$v[id]";?>">Редактировать</a>
           </td></tr>   
           <tr><td colspan="2"><hr></td></tr>
<?php   } ?>
</table>

<?php
require_once("footer.inc.php");
?>

