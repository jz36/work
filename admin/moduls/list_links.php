<?
#Список ссылок#0
#param#kfp;Количество ссылок на странице;20#
# Блок проверки наличия таблицы table1
$query=""; 
create_MySQL_table($query,1,1);

mysql_query("INSERT INTO ".PREF."_$main (id,name) VALUES (100001,\"".s_select("name","admin_tree","page=\"$main\"")."\")");
# =======================================
?>
<script>
window.location.href="<?=PAGE?>?main=links&top_table=<?=$main?>&top_id=100001&rand=26150&delcookie=1";
</script>