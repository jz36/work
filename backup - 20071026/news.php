<?php

include "wedadmin/config.inc.php";
include "wedadmin/lib/mysql.lib.php";

db_open();

if(!isset($_GET['details']))
{

	// NEWS
	
	mysql_query("SET CHARACTER SET cp1251");
	$news=mysql_query("SELECT * FROM `wed_news_items` WHERE `is_favorite`=1 ORDER BY `date` DESC");
	$news_count=mysql_num_rows($news);

	switch (date("m")) 
	{
	    case 1: $month="������";
	        	break;
	    case 2: $month="�������";
	        	break;
	    case 3: $month="����";
	        	break;
	    case 4: $month="������";
	        	break;
	    case 5: $month="���";
	        	break;
	    case 6: $month="����";
	        	break;
	    case 7: $month="����";
	        	break;
	    case 8: $month="������";
	        	break;
	    case 9: $month="��������";
	        	break;
	    case 10: $month="�������";
	        	break;
	    case 11: $month="������";
	        	break;
	    case 12: $month="�������";
	        	break;
	}

	echo '<TR ><TD width="100%" style="background-color: #999999; color:#FFFFFF; padding:3px;  padding-left:30px; height:20px;  border-left:#cc0066 solid 1px; border-right:#cc0066 solid 1px;" colspan="2"><strong>������� � �������������<br/>('.$month.' 2007)</strong></TD></TR>';	
	echo '<tr><TD width="100%" colspan="2" style="padding:6px; text-align:left;  border-bottom:#cc0066 solid 1px; border-left:#cc0066 solid 1px; border-right:#cc0066 solid 1px; ">';
	for($i=0;$i<$news_count;$i++)
	{
		$news_row=mysql_fetch_assoc($news);
		echo '<p align="left"><a href="news.php?details='.$news_row['id'].'" class="menu" style="color:#cc0066; font-size:13px;"><IMG height="9" src="/img/arrow-grey.gif" width="9" border="0"> <strong>'.$news_row['title'].'</strong></a><br/>';
		//if(trim($news_row['small_picture'])!='') echo '<p><img src="'.$news_row['small_picture'].'" width="170"></p>';
		echo '<a href="news.php?details='.$news_row['id'].'" class="menu" style="color:#012e89;">'.$news_row['short_text'].'</a><BR>';
		//if(trim($news_row['full_text'])!='') echo '<a href="news.php?details='.$news_row['id'].'"><U>���������...</U></a>';
		echo '</p>';                            
	}
	echo '</td></tr>';
}
else
{

	$news=mysql_query("SELECT * FROM `wed_news_items` WHERE `id`=".$_GET['details']);
	$news_row=mysql_fetch_assoc($news);

	global $title;
	$title=$news_row['title'];
	$titleadd=1;
	include "header.inc.php";


	echo '<span class="style1"><h1 style="font-size:16px;"><font face="Arial">'.$news_row['title'].'</h1><BR></p></span>';
	echo $news_row['short_text'].'<br/>';
	echo $news_row['full_text'].'<br/>';
	echo '<br/><a href="./" style="text-decoration:none;">�����...</a></p><br/><br/><br/><br/>';

	include "footer.inc.php";
}
?>