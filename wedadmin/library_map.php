<?

@session_start();

if (!isset($_SESSION["WA_USER"])) { require ("index.php"); die(); }


if (!defined("NX_PATH")) define ("NX_PATH", "../");
require_once(NX_PATH.'wedadmin/lib/global.lib.php');
require_once(NX_PATH.'wedadmin/config.inc.php');
require_once(NX_PATH.'wedadmin/lib/mysql.lib.php');

if (!defined("WA_PATH")) define("WA_PATH", "./");
if (!defined("WA_URL")) define("WA_URL", "/wedadmin/");

@set_magic_quotes_runtime(0);


db_open();

require_once(NX_PATH.'wedadmin/header.inc.php');


showtree(0);


require_once(NX_PATH.'wedadmin/footer.inc.php');


function showtree($parent_id){
  $Q="select * from wed_library where parent_id='$parent_id'";
  $res=mysql_query($Q);
  print "<UL>";	
  while ($H=mysql_fetch_array($res)){
     # проверка что в этой рубрике только одна статья с таким же названием
     $Q2="select id,title from wed_library_items where category_id=$H[id]";
     $res2=mysql_query($Q2);
     $H2=mysql_fetch_array($res2);
     if ($H[title]==$H2[title] && mysql_num_rows($res2)==1) {
       print "<LI><B><a href=library.php?id=$H2[id]>$H2[title]</a></B> (f)";              
       print "&nbsp;&nbsp;<a href=/index.php?id=$H2[id] target=_blank><font color=gray>www</font></a>";
     }else{
       print "<LI><B><a href=library.php?cid=$H[id]>$H[title]</a></B> (d)";
       print "&nbsp;&nbsp;<a href=/index.php?id=$H[id] target=_blank><font color=gray>www</font></a>";
       showtree($H[id]);
       showpages($H[id]); 
     }
	

  }
  print "</UL>";

}

function showpages($parent_id){
  $Q="select * from wed_library_items where category_id='$parent_id'";
  $res=mysql_query($Q);
  print "<UL>";	
  while ($H=mysql_fetch_array($res)){
     print "<LI><a href=library.php?id=$H[id]>$H[title]</a>";
     print "&nbsp;&nbsp;<a href=/index.php?id=$H[id] target=_blank><font color=gray>www</font></a>";

  }
  print "</UL>";

}



?>