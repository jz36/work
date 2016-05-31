<?php

function QueryParent($id)
{
	$pid=mysql_query("SELECT `parent_id` FROM `wed_library` WHERE `id`=".$id);
	$pid_row=mysql_fetch_assoc($pid);

		if(!isset($_SESSION['PARENT'])) $_SESSION['PARENT']=array($id);
		else
		{
			$current=count($_SESSION['PARENT'])+1;
			$_SESSION['PARENT'][$current]=$id;
		}

		if($id!=0) QueryParent($pid_row['parent_id']);
}


function ShowMenu($pid,$left=15)
{
	mysql_query("SET CHARACTER SET cp1251");
	if($pid!=0) $qw=mysql_query("SELECT * FROM `wed_library` WHERE `parent_id`=".$pid." ORDER BY `order`");
	else $qw=mysql_query("SELECT * FROM `wed_library` WHERE `parent_id`=".$pid." AND `id`!=9 AND `id`!=2 ORDER BY `order`");
	$qc=mysql_num_rows($qw);

	if($qc>0)
	{
		if($pid!=0)
		{
			for($i=0;$i<$qc;$i++)
			{
				$qr=mysql_fetch_assoc($qw);
				if(isset($_SESSION['PARENT']) and in_array($qr['parent_id'], $_SESSION['PARENT']))
				{
					echo '<div style="padding-bottom:7px; padding-left:'.$left.'px;"><a href="article.php?cid='.$qr['id'].'" style="color:#000066;">'.$qr['title'].'</a></div>';
					ShowMenu($qr['id'],$left+15);
				}
			}
		}
		else
		{
			for($i=0;$i<$qc;$i++)
			{
				$qr=mysql_fetch_assoc($qw);
				if(isset($_SESSION['PARENT']) and in_array($qr['parent_id'], $_SESSION['PARENT']))
				{
					echo '<div style="padding-bottom:7px; padding-left:'.$left.'px;"><a href="article.php?" style="color:#000066;">'.$qr['title'].'</a></div>';
					ShowMenu($qr['id'],$left+15);
				}
			}			
		}
	}
	else
	{
		mysql_query("SET CHARACTER SET cp1251");
		$qw=mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=".$pid." ORDER BY `order`");
		$qc=mysql_num_rows($qw);

		if($qc>1)
		{
			for($i=0;$i<$qc;$i++)
			{
				$qr=mysql_fetch_assoc($qw);
				if(isset($_SESSION['PARENT']) and in_array($qr['category_id'], $_SESSION['PARENT']))
				{
					echo '<div style="padding-bottom:7px; padding-left:'.$left.'px;"><a href="article.php?cid='.$_GET['cid'].'&id='.$qr['id'].'" style="font-weight:normal; color:#000066;">'.$qr['title'].'</a></div>';
				}
			}
		}		
	}
}


function ShowAll($pid,$left=0)
{
	echo '<div>';

	mysql_query("SET CHARACTER SET cp1251");
	$qw=mysql_query("SELECT * FROM `wed_library` WHERE `parent_id`=".$pid." ORDER BY `order`");
	$qc=mysql_num_rows($qw);

	if($qc>0)
	{
		for($i=0;$i<$qc;$i++)
		{
			$qr=mysql_fetch_assoc($qw);
			echo '<div style="padding-bottom:7px; line-height:14px; padding-left:'.$left.'px;">';
			echo '<a href="article.php?cid='.$qr['id'].'" style="font-weight:bold; color:#000066;">'.$qr['title'].'</a><br/>'.$qr['short_text'].'</div>';
			ShowAll($qr['id'],$left+20);
		}

		mysql_query("SET CHARACTER SET cp1251");
		$qw=mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=".$pid." ORDER BY `order`");
		$qc=mysql_num_rows($qw);

		for($i=0;$i<$qc;$i++)
		{
			$qr=mysql_fetch_assoc($qw);
			echo '<div style="padding-bottom:7px; line-height:14px; padding-left:'.$left.'px;">';
			echo '<a href="article.php?cid='.$pid.'&id='.$qr['id'].'" style="font-weight:normal; color:#000066;">'.$qr['title'].'</a><br/>'.$qr['short_text'].'</div>';
		}
	}
	elseif($qc==0)
	{
		mysql_query("SET CHARACTER SET cp1251");
		$qw=mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=".$pid." ORDER BY `order`");
		$qc=mysql_num_rows($qw);

		for($i=0;$i<$qc;$i++)
		{
			$qr=mysql_fetch_assoc($qw);
			echo '<div style="padding-bottom:7px; line-height:14px; padding-left:'.$left.'px;">';
			echo '<a href="article.php?cid='.$_GET['cid'].'&id='.$qr['id'].'" style="font-weight:normal; color:#000066;">'.$qr['title'].'</a><br/>'.$qr['short_text'].'</div>';
		}
	}

	echo '</div>';
}
?>