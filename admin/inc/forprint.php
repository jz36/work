<?
#========================================
# Ссылка на версию для печати

function forprint() {?>
<div class=small><a href="print.php?<?if ($main!="") echo "main=$main";if ($id!="") echo "&id=$id";if (isset($top)) echo "&top=$top";if (isset($sub)) echo "&sub=$sub";?>" target="_blank"><img src="<?SITE_ADMIN_DIR?>/img/forprint.gif" width="19" height="18" border=0 align=absbottom>&nbsp;Версия для печати</a></div>
<?
}
?>
