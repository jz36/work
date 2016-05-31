<?php

$titleadd=1;
if(!isset($id) and !isset($cid))
{
	include "wedadmin/config.inc.php";
	include "wedadmin/lib/mysql.lib.php";
	include "functions.php";
	
	db_open();
	
	if(isset($_GET['id'])) $id=intval($_GET['id']);
	if(isset($_GET['cid'])) $cid=intval($_GET['cid']);

	mysql_query("SET CHARACTER SET cp1251");
	$cat=mysql_query("SELECT * FROM `wed_library` WHERE `parent_id`=5 ORDER BY `order`");
	$cat_count=mysql_num_rows($cat);
	
	echo '<table width="100%" border="0" cellpadding="5" cellspacing="5">
			<tr> 
				<td width="100%" bgcolor="#FFFFCC" valign="TOP"> 
				<span class="style1"><p align="left">';

	for($i=0;$i<$cat_count;$i++)
	{
		$cat_row=mysql_fetch_assoc($cat);	
		echo '<div style="padding:3px;"><a href="article.php?cid='.$cat_row['id'].'" style="font-weight:bold;"><U>'.$cat_row['title'].'</U></a></div>';
	}

	echo '	</p></td>
			</tr>
		 </table><BR>';
}

elseif(isset($id))
{
	include "header.inc.php";

	unset($_SESSION['PARENT']);
	if(isset($cid)) QueryParent($cid);

	mysql_query("SET CHARACTER SET cp1251");
	$art=mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=".$id);
	$art_row=mysql_fetch_assoc($art);

	echo '<table border="0" cellpadding="0" cellspacing="0">';
	echo '<tr>';
	echo '<td width="200" valign="top">';
	echo ShowMenu(5,0);
	echo '</td>';
	echo '<td width="800" valign="top" style="padding-left:30px;">';
	echo '<h2>'.$art_row['title'].'</h2>';
	echo '<table border="0" cellpadding="0" cellspacing="0" style="font-size:12px;"><tr>';
	if(trim($art_row['small_picture'])!='') echo '<td valign="top" style="padding-right:20px;"><img src="library/images/'.$art_row['small_picture'].'"></td>';
	echo '<td>'.$art_row['full_text'].'</td>';
	echo '</tr></table>';
	echo '</td>';
	echo '</tr>';
	echo '</table>';

	include "footer.inc.php";
}

elseif(isset($cid))
{
	include "header.inc.php";

	unset($_SESSION['PARENT']);
	if(isset($cid)) QueryParent($cid);

	mysql_query("SET CHARACTER SET cp1251");
	$cat=mysql_query("SELECT * FROM `wed_library` WHERE `id`=".$cid);
	$cat_row=mysql_fetch_assoc($cat);

	echo '<table border="0" cellpadding="0" cellspacing="0">';
	echo '<tr>';
	echo '<td width="200" valign="top">';
	echo ShowMenu(5,0);
	echo '</td>';
	echo '<td width="800" valign="top" style="padding-left:30px;"><h2 style="color:#000066;">'.$cat_row['title'].'</h2>';
	echo ShowAll($cid);
	echo '</td>';
	echo '</tr>';
	echo '</table>';

	include "footer.inc.php";
}
?>