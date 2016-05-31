<?
# Это файл который развертывает сайт, создает изначальные необходимые разделы, юзеров и т.п.
# Работает только один раз, если не cоздана база _admin_tree
$echo="<div class='red bold'>Создание базы данных успешно завершено</div>";

$query="
CREATE TABLE IF NOT EXISTS ".PREF."_admin_site (
  id int(7) NOT NULL default 0,
  name text,
  content text,
  param varchar(255) default NULL,
  page varchar(100) default NULL,
  visible int(2) NOT NULL default 1,
  top int(11) NOT NULL default 0,
  ord int(6) NOT NULL default 0,
  admin varchar(255) NOT NULL default 0,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";  
$res=mysql_query($query);

mysql_query("INSERT INTO ".PREF."_admin_site VALUES (100001, 'Заголовок сайта', '', 'site_title','', 1, 0, 10, '0')");
mysql_query("INSERT INTO ".PREF."_admin_site VALUES (100004, 'Основной е-майл для связи с сайта', '', 'site_email','', 1, 0, 20, '0')");  
mysql_query("INSERT INTO ".PREF."_admin_site VALUES (100005, 'Разбивка на страницы по количеству', '30', 'kfp','', 1, 0, 50, '0')");  
mysql_query("INSERT INTO ".PREF."_admin_site VALUES (100005, 'Копирайт', 'Copyright © 2004 ', 'copyright','', 1, 0, 50, '0')");  
mysql_query("INSERT INTO ".PREF."_admin_site VALUES (100006, 'Ширина маленькой картинки', '150', 'size_m','', 1, 0, 60, '0')");  
mysql_query("INSERT INTO ".PREF."_admin_site VALUES (100007, 'Размер большой картинки (по большей стороне)', '600', 'size_b','', 1, 0, 70, '0')");  
if (mysql_errno()!=0) $echo.="<BR>".mysql_errno().": ".mysql_error()."<BR>";

$query="
CREATE TABLE IF NOT EXISTS ".PREF."_admin_tree (
  id int(7) NOT NULL default 0,
  name varchar(255) NOT NULL default '',
  page varchar(255) NOT NULL default '',
  shablon varchar(255) NOT NULL default '',
  shablon_out varchar(255) default NULL,
  content text,
  global_id int(6) NOT NULL default 0,
  visible int(2) NOT NULL default 1,
  menu_top text,
  ord int(10) default 0,
  top int(7) default 0,
  alert int(2) NOT NULL default 1,
  search int(2) NOT NULL default 0,
  admin varchar(255) NOT NULL default 0,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";  
$res=mysql_query($query);

mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100040, 'Группы пользователей', 'admin_user_groups', 'admin_user_groups', '', '', 3, 0, '0', 220, 0, 1, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100003, 'Структура сайта', 'admin_tree', 'admin_tree', NULL, '', 2, 0, '0', 170, 0, 0, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100010, 'Колонтитул', 'footer', 'footer', '', '', 2, 0, '0', 200, 0, 0, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100036, 'Мета-данные', 'meta', 'meta', '', '', 2, 0, '0', 190, 0, 0, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100045, 'Поиск по сайту', 'search', 'search', NULL, '', 1, 1, '0', 120, 0, 0, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100051, 'Счетчики', 'counters', 'counter', NULL, '', 1, 1, '0', 100, 0, 0, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100017, 'Шаблоны ввода', 'admin_shabl', 'admin_shabl', '', '', 3, 0, '0', 230, 0, 1, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100001, 'Обьекты на сайте', 'site_objects', '0', NULL, '', 1, 0, '0', 150, 0, 0, 1, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100019, 'Файлы', 'files', 'all_files', NULL, '', 1, 0, '100001', 150, 0, 0, 1, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100039, 'Ссылки', 'links', 'all_links', NULL, '', 1, 0, '100001', 140, 0, 0, 1, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100035, 'Изображения', 'images', 'all_images', NULL, '', 1, 0, '100001', 160, 0, 0, 1, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100053, 'Оценки и коментарии', 'all_feedback', 'all_feedback', NULL, '', 1, 0, '100001', 170, 0, 0, 1, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100054, 'Доп. описания', 'all_desc', 'all_desc', NULL, '', 1, 0, '100001', 180, 0, 0, 1, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100037, 'Пользователи', 'admin_users', 'admin_users', '', '', 3, 0, '0', 210, 0, 1, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100052, 'Бэкап базы данных', 'admin_db_backup', 'admin_db_backup', '', '', 3, 0, '0', 200, 0, 1, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100046, 'Настройка сайта', 'admin_site', 'admin_site', NULL, '', 2, 0, '0', 180, 0, 0, 0, '100002')");
mysql_query("INSERT INTO ".PREF."_admin_tree VALUES (100050, 'Протокол администрирования', 'admin_change_stat', 'admin_change_stat', '', '', 3, 1, '0', 0, 0, 1, 0, '100002')");
if (mysql_errno()!=0) $echo.="<BR>".mysql_errno().": ".mysql_error()."<BR>";

$query="
CREATE TABLE IF NOT EXISTS ".PREF."_admin_user_groups (
  id int(7) NOT NULL default 0,
  name text,
  content text,
  default_access int(2) NOT NULL default 0,
  visible int(2) NOT NULL default 1,
  top int(11) NOT NULL default 0,
  ord int(6) NOT NULL default 0,
  admin varchar(255) NOT NULL default 0,

  footer int(2) NOT NULL default 0,
  meta int(2) NOT NULL default 0,
  links int(2) NOT NULL default 0,
  files int(2) NOT NULL default 0,
  images int(2) NOT NULL default 0,
  all_feedback int(2) NOT NULL default 0,
  all_desc int(2) NOT NULL default 0,
  admin_tree int(2) NOT NULL default 0,
  admin_shabl int(2) NOT NULL default 0,
  admin_users int(2) NOT NULL default 0,
  admin_user_groups int(2) NOT NULL default 0,
  search int(2) NOT NULL default 0,
  admin_site int(2) NOT NULL default 0,
  admin_change_stat int(2) NOT NULL default 0,
  site_objects int(2) NOT NULL default 0,
  counters int(2) NOT NULL default 0,
  admin_db_backup int(2) NOT NULL default 0,

  PRIMARY KEY  (id)
) TYPE=MyISAM;";  
$res=mysql_query($query);

mysql_query("INSERT INTO ".PREF."_admin_user_groups VALUES (100001, 'Администратор сайта', 'Может все.', 3, 1, 0, 0, '100002', 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3)");
mysql_query("INSERT INTO ".PREF."_admin_user_groups VALUES (100002, 'root', '', 3, 1, 0, 0, '100002', 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3)");
if (mysql_errno()!=0) $echo.="<BR>".mysql_errno().": ".mysql_error()."<BR>";

$query="
CREATE TABLE IF NOT EXISTS ".PREF."_admin_users (
  id int(7) NOT NULL default 0,
  name text,
  content text,
  password varchar(150) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  fio varchar(255) NOT NULL default '',
  dolznost text,
  user_group int(6) NOT NULL default 0,
  visible int(2) NOT NULL default 1,
  top int(11) NOT NULL default 0,
  ord int(6) NOT NULL default 0,
  admin varchar(255) NOT NULL default 0,
  PRIMARY KEY  (id)
) TYPE=MyISAM;";  
$res=mysql_query($query);

mysql_query("INSERT INTO ".PREF."_admin_users VALUES (100001, 'root', NULL, password('passworr".PREF."'), 'igor@e-mail66.ru', 'Супервайзер', 'Супервайзер', 100002, 1, 0, 0, '0')");
mysql_query("INSERT INTO ".PREF."_admin_users VALUES (100002, 'admin', NULL, password('passworr".PREF."'), 'igor@e-mail66.ru', 'Администратор', 'Администратор сайта', 100001, 1, 0, 0, '0')");  
if (mysql_errno()!=0) $echo.="<BR>".mysql_errno().": ".mysql_error()."<BR>";

echo "<div align=center><br>$echo</div>";
?>
    