<?

# создает нужные таблицы

require 'config.inc.php';
require 'lib/mysql.lib.php';
db_open();




#-- 
#-- Структура таблицы `wed_accounts`
#-- 

$Q="
CREATE TABLE `wed_accounts` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `login` varchar(255) NOT NULL default '',
  `key` varchar(32) NOT NULL default '',
  `hash` varchar(32) NOT NULL default '',
  `fullname` varchar(255) NOT NULL default '',
  `is_admin` tinyint(1) unsigned NOT NULL default '0',
  `untouchible` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM";

mysql_query($Q);

#-- --------------------------------------------------------

#-- 
#-- Структура таблицы `wed_docs`
#-- 

$Q="
CREATE TABLE `wed_docs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `type` tinyint(3) unsigned NOT NULL default '0',
  `name` tinytext NOT NULL,
  `ext` varchar(5) NOT NULL default '',
  `order` int(10) unsigned NOT NULL default '0',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM";

mysql_query($Q);


$Q="INSERT INTO `wed_accounts` VALUES (2, 'ikc', '7110b9f55d9708fbf9727786ca149e45', 'e4ce986b3ed03291914712d9b4dc7601', '', 1, 0)";
mysql_query($Q);
#-- --------------------------------------------------------

#-- 
#-- Структура таблицы `wed_library`
#-- 

$Q="
CREATE TABLE `wed_library` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `parent_id` bigint(20) unsigned NOT NULL default '0',
  `pagetype` bigint(20) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `alt_title` varchar(255) NOT NULL default '',
  `author` varchar(255) NOT NULL default '',
  `html` longblob NOT NULL,
  `link_id` bigint(20) unsigned NOT NULL default '0',
  `path` varchar(255) NOT NULL default '',
  `show_navbar` tinyint(1) unsigned NOT NULL default '0',
  `hide_title` tinyint(1) unsigned NOT NULL default '0',
  `icon` varchar(255) NOT NULL default '',
  `icons_size` int(10) unsigned NOT NULL default '0',
  `its` varchar(10) NOT NULL default '',
  `uts` varchar(10) NOT NULL default '',
  `order` int(11) NOT NULL default '0',
  `hide` tinyint(1) unsigned NOT NULL default '0',
  `lid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `order` (`order`)
) TYPE=MyISAM";

mysql_query($Q);
#-- --------------------------------------------------------

#-- 
#-- Структура таблицы `wed_library_items`
#-- 

$Q="CREATE TABLE `wed_library_items` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `category_id` int(10) unsigned NOT NULL default '0',
  `title` text NOT NULL,
  `short_text` text NOT NULL,
  `full_text` longtext NOT NULL,
  `wysiwyg` tinyint(1) unsigned NOT NULL default '1',
  `meta_description` text NOT NULL,
  `date` varchar(10) NOT NULL default '',
  `large_picture` varchar(255) NOT NULL default '',
  `small_picture` varchar(255) NOT NULL default '',
  `its` varchar(10) NOT NULL default '',
  `uts` varchar(10) NOT NULL default '',
  `producer_id` bigint(20) unsigned NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  `se_ts` varchar(10) NOT NULL default '',
  `lid` int(11) NOT NULL default '0',
  `price` varchar(50) NOT NULL default '',
  `spec` int(2) NOT NULL default '0',
  `exist` int(2) NOT NULL default '1',
  `driver` varchar(200) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `order` (`order`),
  KEY `se_ts` (`se_ts`),
  KEY `producer_id` (`producer_id`),
  KEY `category_id` (`category_id`),
  KEY `order_2` (`order`)
) TYPE=MyISAM";

mysql_query($Q);
#-- --------------------------------------------------------

#-- 
#-- Структура таблицы `wed_photogallery_albums`
#-- 

$Q="
CREATE TABLE `wed_photogallery_albums` (
  `id` bigint(20) NOT NULL auto_increment,
  `parent_id` bigint(20) NOT NULL default '0',
  `name` varchar(250) NOT NULL default '',
  `description` text NOT NULL,
  `picture` varchar(200) NOT NULL default '',
  `rows` int(11) NOT NULL default '3',
  `columns` int(11) NOT NULL default '3',
  `width` int(11) NOT NULL default '250',
  `height` int(11) NOT NULL default '0',
  `order` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM";

mysql_query($Q);
#-- --------------------------------------------------------

#-- 
#-- Структура таблицы `wed_photogallery_items`
#-- 

$Q="
CREATE TABLE `wed_photogallery_items` (
  `id` bigint(20) NOT NULL auto_increment,
  `parent_id` bigint(20) NOT NULL default '0',
  `name` varchar(250) NOT NULL default '',
  `comment` text NOT NULL,
  `picture` varchar(250) NOT NULL default '',
  `order` bigint(20) NOT NULL default '0',
  `width` int(11) NOT NULL default '0',
  `height` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM";

mysql_query($Q);
#-- --------------------------------------------------------

#-- 
#-- Структура таблицы `wed_news_items`
#-- 

$Q="
CREATE TABLE `wed_news_items` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `category_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `short_text` text NOT NULL,
  `full_text` text NOT NULL,
  `date` varchar(10) NOT NULL default '',
  `large_picture` varchar(255) NOT NULL default '',
  `small_picture` varchar(255) NOT NULL default '',
  `its` varchar(10) NOT NULL default '',
  `uts` varchar(10) NOT NULL default '',
  `is_favorite` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM";
        
mysql_query($Q);
        


?>