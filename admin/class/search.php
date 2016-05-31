<?
/********************************************************\
*  ������ ������ mod_search.php									*
*																			*
*  �����: ������ ������� (������ 0x100)						*
*																			*
*  ����: 13 ������ 2004											*
*																			*
*  �����������:														*
*  11 ������ 2004 �������� �.�.									*
*  ������� �������� �� ������� bSite							*
*																			*
\********************************************************/

Function search_init($save_table){
	#	������������� ������� ��� ���������� �����������
	#	(������ 0x100 - ������ �������� ���� �������

	if($save_table!='')
	{
		$init_query="delete from ".PREF."_".$save_table;
		DbgPrint("INIT SEARCH TABLE: $init_query");
		$init_res=mysql_query($init_query);
		return $init_res;
	}}

function search_start($search_text,$tables,$fields,$save_table,$search_title,$search_title_numchars,$search_content,$search_content_numchars,$search_redirect_page)
{
	/********************************************************
	*  $search_text - ����� ��� ������
	*  $tables - ������ ������ � ������� ������������ �����
	*  $fields - ������ ������� � ��������
	*
	*  $search_title[] - ������ � ������� ������������ �������� ���� � ����
	*							������� �������� ����������
	*							���� ������ ���������� � @ �� ��� � ��������� ��� ���������!!!
	*  $search_title_numchars - ����� �������� ��� ������ ���������
	*  $search_content[] - ������ � ������� ������������ �������� ���� � ����
	*							������� �������� ���������
	*  $search_content_numchars - ����� �������� ��� ������ ��������
	*
	*  $save_table - ������� ��� ���������� �����������
	*  $search_redirect_page - ������ �������, �� ������� ����������
	*									�������������� ��������
	*
	*  $id � $top ������ �������������� � ������� ��������!!!
	*
	*********************************************************/

	$find_words=explode(" ",$search_text); // ��������� �� �������� ������� ������
	$find_num_words=sizeof($find_words);	// ���������� ���� ������

	foreach($tables as $tbl)				// �������� ������� �� �������
	{
			foreach((array)$fields[$tbl] as $key=>$value)  // �������� ���� ������ ��� ������
			{

				$tmp_res=row_select("id,top,$value",$tbl);			// ������ ������� �� ����
				$tmp_kol=$tmp_res->nr();	// �������� ����� �������
				//echo "<p>".$tbl;
				
				for($i=0;$i<$tmp_kol;$i++)				// ��������� �� ���������� �������
				{
					$tmp_r=$tmp_res->gr();	// �������� ������

					/*
					*  ���������� ��������� �� ���� ������
					*/
					$result=strip_tags($tmp_r[2]);
					$result=strtr($result,"QWERTYUIOPASDFGHJKLZXCVBNM���������������������������������","qwertyuiopasdfghjklzxcvbnm���������������������������������");
					$result=stripslashes($result);
					//echo $result;

					$relevancy=0;  // �������������
				foreach($find_words as $word)		// $word - ���� ����� �� ������� ����
					{
						/*
							*  ����� �� ������ � ������ �������
							*/
							$cursor=0;	// ������� � ������� ������ ����� � ������ �� ����

							if($word=='')	// ���� ������ �����
								continue;

							while(strpos($result,$word,$cursor)!==false)
							{
							/*
							*	���-�� �����, ������� ������ ������ ����� ����� �����
								*	� �������� ������
							*/
				
								$cursor=strpos($result,$word,$cursor)+1;
								$relevancy++; // �������� �������������

						} //  while(strpos($result,$word,$cursor)!==false)

					}  // foreach($find_words as $word)

					/*
					*	��� ������� ���������� ������ ������
					*/
					if($relevancy!=0)
					{
						/*
							*  ���� ���� ���� ����� �������, �� ������� ��������� � ����
							*/

							/*
							*	������� ������� ��������� � ������������ � ������� ������ � ������� �������� �����
							*	��� ���������
							*/
							if($search_title[$tbl][0]!='@')  // ���� ������ ������ ��� �������
							{
								$title_res=row_select($search_title[$tbl],$tbl,"id=$tmp_r[0]");
								$title_r=$title_res->gr();
							}
							else	// ���� ������ ������ ������, ������� ��������
							{
								$tmp_name_str=str_replace("@","",$search_title[$tbl]);
								$title_r[0]=$tmp_name_str;
							}

							$title_r[0]=strip_tags($title_r[0]);
							$title_r[0]=stripslashes($title_r[0]);

							$read_length=strlen($title_r[0]); // ����� �������� � ������� ���������
							if($read_length>$search_title_numchars[$tbl])
							{
								/*
								*	���� �������� � ��������� ������ ��� ��������, �� ����� �� ��� ����
								*/
								$read_length=$search_title_numchars[$tbl];
							}
							$title=substr($title_r[0],0,$read_length);	//  ���������
							$title=addslashes($title);						// ������������� ���������

							/*
							*	������ ��������� �� �� ����� � ���������

							*	������� ������� ������� � ������������ � ������� ������ � ������� �������� �����
							*	��� ��������
							*/
							$content_r=s_select($search_content[$tbl],$tbl,"id=$tmp_r[0]");
							$content_r=str_replace("&nbsp;"," ",$content_r);
							$content_r=strip_tags($content_r);
							$content_r=stripslashes($content_r);
							$content_r=strtr($content_r,"QWERTYUIOPASDFGHJKLZXCVBNM���������������������������������","qwertyuiopasdfghjklzxcvbnm���������������������������������");

							$read_length=strlen($content_r); // ����� �������� � ������� ��������
							
							if($read_length>$search_content_numchars[$tbl])
							{
								/*
								*	���� �������� � �������� ������ ��� ��������, �� ����� �� ��� ����
								*/
								$read_length=$search_content_numchars[$tbl];
							}
							$content=substr($content_r,0,$read_length);	//  ���������
							$content=addslashes($content);						// ������������� �������
							$content=trim($content);

							$id_path="";
							
							$idd=$tmp_r[0];
							$ttop=1;
							/*
							* ���� � ��� �������� ��� ������ ��� ����, �� ��������� ������ ���
							*/
							if ($tbl=="links" || $tbl=="files" || $tbl=="images") {

								$tops_res=row_select("top_table,top_id","$tbl","id=$idd");
								$tops_r=$tops_res->gr();
								$id_path.="$idd#$tops_r[0]#$tops_r[1]";
							}
							else {
								$id_path.="$idd##";
							}						
							/*
							*  ����� ��������������� ���������� �� ����� �������� � ����
							*  ���������� ������ ������
							*/
							$tmp=s_select("id",$save_table,"id=1");
							s_insert($save_table,"name,content,tops,page,relevancy","\"$title\",\"$content\",\"$id_path\",\"$search_redirect_page[$tbl]\",$relevancy");

					}  

				}  

			}  

	}  

}

?>