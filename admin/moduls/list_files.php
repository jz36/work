<?
#������ ������#0
#param#kfp;���������� ������ �� ��������;10#
# ���� �������� ������� ������� table1
$query=""; 
create_MySQL_table($query,1,1);

mysql_query("INSERT INTO ".PREF."_$main (id,name) VALUES (100001,\"".s_select("name","admin_tree","page=\"$main\"")."\")");
# =======================================
?>
<script>
window.location.href="<?=PAGE?>?main=files&top_table=<?=$main?>&top_id=100001&rand=26150&delcookie=1";
</script>