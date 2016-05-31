<?
#Одиночная страница#0
$id=100001;
$res=row_select("name,content,id","","id=\"100001\"");
$r=$res->ga();

if ($title!=$r["name"]){
echo "<h2>".$r["name"]."</h2>";}
echo $r["content"];

	require(SITE_ADMIN_DIR."/inc/content/inner_desc.php");
	require(SITE_ADMIN_DIR."/inc/content/inner_links.php");
	require(SITE_ADMIN_DIR."/inc/content/inner_files.php");
	require(SITE_ADMIN_DIR."/inc/content/photogal-inner.php");
?>


