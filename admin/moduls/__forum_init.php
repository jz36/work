<?

#==========================================================	

# Таблица со смайликами
	$query="	
		img varchar(255),";  	
	create_MySQL_table($query,1,0,$main."_smiles");	
	

# Таблица с заменами слов
	$query="	
		replacement varchar(255),";  	
	create_MySQL_table($query,1,0,$main."_words");	

# Таблица с заменами слов
	$query="	
		ip varchar(50),";  	
	create_MySQL_table($query,1,0,$main."_bun");	

# Таблица самого форума
	$query="	
		author varchar(255),
		email varchar(255),	
		data date default 0,	
		time time default 0,	
		ip varchar(40),
		views int(5) default 0,
		count int(5) default 0,
		alert int(2) NOT NULL default 0,";  	
	create_MySQL_table($query,1,0);
	
#==========================================================
# Заносим подразделы форума в общую структуру сайта


$maxid=s_select("max(id)","admin_tree");
$topid=s_select("id","admin_tree","page=\"$main\"");
$tmp=s_select("id","admin_tree","page=\"".$main."_smiles\"");

if (s_select("name","admin_tree","page=\"".$main."_smiles\"")=="")	$sql[]="INSERT INTO ".PREF."_admin_tree (id, name, page, shablon, menu_top, visible, admin) VALUES ( ($maxid+1), \"Смайлики\", \"".$main."_smiles\", \"forum_smiles\", $topid, 0, \"100002\");";
if (s_select("name","admin_tree","page=\"".$main."_words\"")=="")	$sql[]="INSERT INTO ".PREF."_admin_tree (id, name, page, shablon, menu_top, visible, admin) VALUES ( ($maxid+2), \"Автоцензор\", \"".$main."_words\", \"forum_words\", $topid, 0, \"100002\");";
if (s_select("name","admin_tree","page=\"".$main."_bun\"")=="")	$sql[]="INSERT INTO ".PREF."_admin_tree (id, name, page, shablon, menu_top, visible, admin) VALUES ( ($maxid+3), \"Запрет юзеров\", \"".$main."_bun\", \"forum_bun\", $topid, 0, \"100002\");";


mysql_query("ALTER TABLE ".PREF."_admin_user_groups ADD ".$main."_smiles INT(2) DEFAULT 0 NOT NULL ");
mysql_query("ALTER TABLE ".PREF."_admin_user_groups ADD ".$main."_words INT(2) DEFAULT 0 NOT NULL ");
mysql_query("ALTER TABLE ".PREF."_admin_user_groups ADD ".$main."_bun INT(2) DEFAULT 0 NOT NULL ");			

#Для групп юзеров забиваем для нового раздела права -  сколько у них поставлено по умолчанию
$res=new recordset("select name,default_access from ".PREF."_admin_user_groups");
while ($r=$res->ga()) {				
	mysql_query("UPDATE ".PREF."_admin_user_groups SET ".$main."_smiles=\"".$r["default_access"]."\" WHERE name =\"".$r["name"]."\" LIMIT 1");
	mysql_query("UPDATE ".PREF."_admin_user_groups SET ".$main."_words=\"".$r["default_access"]."\" WHERE name =\"".$r["name"]."\" LIMIT 1");
	mysql_query("UPDATE ".PREF."_admin_user_groups SET ".$main."_bun=\"".$r["default_access"]."\" WHERE name =\"".$r["name"]."\" LIMIT 1");
}


#==========================================================
# Вставляем начальные данные
	
# -- Smilies
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100001, \":D\", \"icon_biggrin.gif\", \"Very Happy\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100002, \":-D\", \"icon_biggrin.gif\", \"Very Happy\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100003, \":grin:\", \"icon_biggrin.gif\", \"Very Happy\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 1000043, \":))\", \"icon_biggrin.gif\", \"Very Happy\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 1000043, \")))\", \"icon_biggrin.gif\", \"Very Happy\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100004, \":)\", \"icon_smile.gif\", \"Smile\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100005, \":-)\", \"icon_smile.gif\", \"Smile\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100006, \":smile:\", \"icon_smile.gif\", \"Smile\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 1000044, \"=)\", \"icon_smile.gif\", \"Smile\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100007, \":(\", \"icon_sad.gif\", \"Sad\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100008, \":-(\", \"icon_sad.gif\", \"Sad\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100009, \":sad:\", \"icon_sad.gif\", \"Sad\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100010, \":o\", \"icon_surprised.gif\", \"Surprised\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100011, \":-o\", \"icon_surprised.gif\", \"Surprised\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100012, \":eek:\", \"icon_surprised.gif\", \"Surprised\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100013, \":shock:\", \"icon_eek.gif\", \"Shocked\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100014, \":?\", \"icon_confused.gif\", \"Confused\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100015, \":-?\", \"icon_confused.gif\", \"Confused\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100016, \":???:\", \"icon_confused.gif\", \"Confused\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100017, \"8)\", \"icon_cool.gif\", \"Cool\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100018, \"8-)\", \"icon_cool.gif\", \"Cool\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100019, \":cool:\", \"icon_cool.gif\", \"Cool\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100020, \":lol:\", \"icon_lol.gif\", \"Laughing\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100021, \":x\", \"icon_mad.gif\", \"Mad\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100022, \":-x\", \"icon_mad.gif\", \"Mad\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100023, \":mad:\", \"icon_mad.gif\", \"Mad\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100024, \":P\", \"icon_razz.gif\", \"Razz\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100025, \":-P\", \"icon_razz.gif\", \"Razz\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100026, \":razz:\", \"icon_razz.gif\", \"Razz\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100027, \":oops:\", \"icon_redface.gif\", \"Embarassed\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100028, \":cry:\", \"icon_cry.gif\", \"Crying or Very sad\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100029, \":evil:\", \"icon_evil.gif\", \"Evil or Very Mad\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100030, \":twisted:\", \"icon_twisted.gif\", \"Twisted Evil\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100031, \":roll:\", \"icon_rolleyes.gif\", \"Rolling Eyes\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100032, \":wink:\", \"icon_wink.gif\", \"Wink\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100033, \";)\", \"icon_wink.gif\", \"Wink\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100034, \";-)\", \"icon_wink.gif\", \"Wink\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100035, \":!:\", \"icon_exclaim.gif\", \"Exclamation\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100036, \":?:\", \"icon_question.gif\", \"Question\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100037, \":idea:\", \"icon_idea.gif\", \"Idea\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100038, \":arrow:\", \"icon_arrow.gif\", \"Arrow\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100039, \":|\", \"icon_neutral.gif\", \"Neutral\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100040, \":-|\", \"icon_neutral.gif\", \"Neutral\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100041, \":neutral:\", \"icon_neutral.gif\", \"Neutral\");";
$sql[]="INSERT INTO ".PREF."_".$main."_smiles (id, content, img, name) VALUES ( 100042, \":mrgreen:\", \"icon_mrgreen.gif\", \"Mr. Green\");";

	
?>