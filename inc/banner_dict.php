<?
#========================================
# ������� ��������� ������� ������ �� �������
function banner_dict() {
	$res=row_select("id,name,content","info_dict","visible=1","RAND()","1");
	$r=$res->ga();
	if ($res->nr()>0){
		echo "<div class=banner><h1>".$r["name"]."</h1><div class=content>".$r["content"]."</div><div align=center><a href='".SPAGE."?main=info_dict' class=small>[���������� ������� ��������]</a></div></div>";
	}
	

}
?>