<?
$contents=split("<BODY", $text);	// �������� ��������� ��� �� �� <BODY> � �����
$content=substr($contents[1], 0, strlen($contents[1])-2); // ?? ������� ����� \n\r
$contents=split("</BODY", $content);  // ������� ������� ��������� ���  �� </BODY>
$content=$contents[0];					// ������ $content - ��� ��� ����� ������� �� �����


if ($id=="add")			// ���� ��������� ����� ������
	{
	$maximg=0;
	$numb=max_count($table, $min);		// ���������� ��������� id � �������, ���� $min=100001
	}
else						// ���� ����������� ������ ������
	{
	$numb=$id;
	$res=mysql_query("select maxris from $table where id=$id");
	$row=mysql_fetch_row($res);						// ������� �� ������� ������������ ���-�� ��������
	$maximg=$row[0];										// �� ���������, �.�. ������ �� ��������� �� ��������������
	}

$textcont="";									// ��� ����� ����� ��� ���� ����� � �������
$polutegs=split("<", $content);			// ������� �� <

$tegs=array();							// ������ �����  Exp: P, /P, IMG src=... alt=...
$textes=array();							// ������ ������� �� �����, �� ���� <P>DIMA, ��� ����� DIMA
for ($i=0; $i<count($polutegs); $i++)
	{
	$parts=split(">", $polutegs[$i]);	// ���� ������ �� <

	if (count($parts)==1)				// ?? ��-����� ������� �� �����������  DEAD_CODE
		{
		$textcont.=$parts[0];			//		���� ������ ������������ �� ����� ������
		$textes[$i]=$parts[0];		//	??? DEAD CODE !!!!
		}
	else
		{
		$textcont.=$parts[1];	// $part[1] - ���� ������, ���� ����� ������ ����, �������� ������ � ����
		$textes[$i]=$parts[1];
		$tegs[$i]=$parts[0];		//  ��� ���� ���, ���� ��� ������ �� ���������� ������
		$tegs[$i]=str_replace("'\"\"'", "''", $tegs[$i]);
		$tegs[$i]=str_replace("'\"", "'", $tegs[$i]);
		$tegs[$i]=str_replace("\"'", "'", $tegs[$i]);
		}								// Exp: P, /P, IMG src=... alt=...
		
	$textes[$i]=str_replace("&quot;", "\"", $textes[$i]);   // �������� ��� ������� � ������, 
																				//�������� �� �� &quot;
	DbgPrint($textes[$i],0,"pars_content");
	}

$newstr="";
$images=array();
$rass=array();
$p_word=0;
$rowcount=0;						// ����� ���� � �������
$rowcountbackslash=0;						// ����� ���� � �������
for ($j=0; $j<$i; $j++)			// � $i �������� ���������� �����
	{
	$flag=0;
	if (isset($tegs[$j]))
		{
		if (strtoupper($tegs[$j])=="STRONG") $tegs[$j]="<B>";		// ������ ������ STRONG �� <B>
		elseif (strtoupper($tegs[$j])=="/STRONG") $tegs[$j]="</B>"; // ������ ������ /STRONG �� </B>
		if (strtoupper($tegs[$j])=="BLOCKQUOTE") $tegs[$j]="<BLOCKQUOTE>";		// ������ ������ BLOCKQUOTE �� <BLOCKQUOTE>
		elseif (strtoupper($tegs[$j])=="/BLOCKQUOTE") $tegs[$j]="</BLOCKQUOTE>"; // ������ ������ /BLOCKQUOTE �� </BLOCKQUOTE>
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="H1")  {
			$tegparts=split(" ", $tegs[$j]);				// ��������� ��� �� ���������
			for ($l=0;$l<count($tegparts);$l++)
				if (eregi("align=", $tegparts[$l]) || eregi("class=", $tegparts[$l]))	$tempstr=" ".$tegparts[$l]; // ��������� ������ ������� align
			$tegs[$j]="<H1".$tempstr.">";		// ������� ������� align
		}
		elseif (strtoupper(substr($tegs[$j], 0, 3))=="/H1") $tegs[$j]="</H1>"; // ������ ������ /h1 �� </h1>
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="H2")  {
			$tegparts=split(" ", $tegs[$j]);				// ��������� ��� �� ���������
			for ($l=0;$l<count($tegparts);$l++)
				if (eregi("align=", $tegparts[$l]))	$tempstr=" ".$tegparts[$l]; // ��������� ������ ������� align
			$tegs[$j]="<H2".$tempstr.">";		// ������� ������� align
		}
		elseif (strtoupper(substr($tegs[$j], 0, 3))=="/H2") $tegs[$j]="</H2>"; // ������ ������ /h2 �� </h2>
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="H3") {
			$tegparts=split(" ", $tegs[$j]);				// ��������� ��� �� ���������
			for ($l=0;$l<count($tegparts);$l++)
				if (eregi("align=", $tegparts[$l]))	$tempstr=" ".$tegparts[$l]; // ��������� ������ ������� align
			$tegs[$j]="<H3".$tempstr.">";		// ������� ������� align
		}
		elseif (strtoupper(substr($tegs[$j], 0, 3))=="/H3") $tegs[$j]="</H3>"; // ������ ������ /h3 �� </h3>
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="H4") {
			$tegparts=split(" ", $tegs[$j]);				// ��������� ��� �� ���������
			for ($l=0;$l<count($tegparts);$l++)
				if (eregi("align=", $tegparts[$l]))	$tempstr=" ".$tegparts[$l]; // ��������� ������ ������� align
			$tegs[$j]="<H4".$tempstr.">";		// ������� ������� align
		}
		elseif (strtoupper(substr($tegs[$j], 0, 3))=="/H4") $tegs[$j]="</H4>"; // ������ ������ /h4 �� </h4>
		
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="LI") $tegs[$j]="<LI>";		// ������ ������ LI �� <LI>
		if (strtoupper(substr($tegs[$j], 0, 2))=="OL") $tegs[$j]="<OL>";		// ������ ������ LI �� <LI>
		if (strtoupper(substr($tegs[$j], 0, 2))=="UL") $tegs[$j]="<UL>";		// ������ ������ LI �� <LI>
		if (strtoupper(substr($tegs[$j], 0, 2))=="B " || strtoupper(substr($tegs[$j], 0, 2))=="U " || strtoupper(substr($tegs[$j], 0, 2))=="I ")
			$tegs[$j]="<".strtoupper(substr($tegs[$j], 0, 1)).">";
		if (strtoupper($tegs[$j])=="EM") $tegs[$j]="<I>";			// ������ ������ EM �� <I>
		elseif (strtoupper($tegs[$j])=="/EM") $tegs[$j]="</I>";	// ������ ������ /EM �� </I>
		/*
		if (strtoupper(substr($tegs[$j], 0, 2))=="P " && $j!=1)				// ���� P �� ����� � ��������� - 0, ��...
			{
			$tegparts=split("MARGIN: 0cm 0cm 0pt", $tegs[$j]);
			if (isset($tegparts[1]))	
			$tegs[$j]="<BR>";
			$p_word=1;
			}
		if ((strtoupper(substr($tegs[$j], 0, 2))=="/P") && ($p_word==1))	// ������� /�, ���� � �� ����� ���������� � br
			{
			$tegs[$j]="";
			$p_word=0;
			}
		*/
		if (strtoupper(substr($tegs[$j], 0, 5))=="TABLE")			// ���� ��� - ��� TABLE, �� ���� �����
			{
			$tempstr="";
			$tegparts=split(" ", $tegs[$j]);				// ��������� ��� �� ���������
			for ($l=0;$l<count($tegparts);$l++){
				if (eregi("align=", $tegparts[$l]) ||  eregi("class=", $tegparts[$l]))	
					$tempstr=" ".$tegparts[$l]; // ��������� ������ �������s
				DbgPrint($tegs[$j],0,"parser");
			}
			$tegs[$j]=$table_top."<TABLE".$table_style." ".$tempstr."  name=name>"; // ��������� �����, � $table_top �����
			$rowcount=1;															// ������� ������� ����������� �������
			}
		if (strtoupper(substr($tegs[$j], 0, 2))=="TR")				// ���� ��������� ���, ��...
			{
			if ($rowcount>0)													// ���� �� ������ ���, ��..
				$tegs[$j]="<TR".$tr_style." name=name>";				// ����� $tr_style
		else
				$tegs[$j]="<TR".$trfirst_style." name=name>";			// ���� ������ ���, �� ����� ��� ������� ����
			}

  		if (strtoupper(substr($tegs[$j], 0, 2))=="TH")		// ���� TH ���
				{
				$tempstr="";
			$tegparts=split(" ", $tegs[$j]);						// ������� ������ ��������� ����
				$rowcount=1;												// ��� ����, ����� TD �� �������� ������ �� TH
				for ($l=0;$l<count($tegparts);$l++)
						{
					if (eregi("width=", $tegparts[$l]))			// ���� ������ ������, �� ��������� ��
					if (eregi("colspan=", $tegparts[$l]) || eregi("bgcolor=", $tegparts[$l]) || eregi("rowspan=", $tegparts[$l]) || eregi("class=", $tegparts[$l]))
							$tempstr.=" ".$tegparts[$l];				// ���� ������ COLSPAN � ROWSPAN BGCOLOR � CLASS �� ��������� ��
					}
				$tegs[$j]="<TH".$tempstr." ".$th_style." name=name>"; // ����� ��� TH
				}
		if (strtoupper(substr($tegs[$j], 0, 2))=="TD")		// �� �� ����� ����� ��� TD
				{
				$tempstr="";
				$tegparts=split(" ", $tegs[$j]);
			for ($l=0;$l<count($tegparts);$l++)
					{
					if (eregi("width=", $tegparts[$l]))
						$tempstr.=" ".$tegparts[$l];
				if (eregi("colspan=", $tegparts[$l]) || eregi("bgcolor=", $tegparts[$l]) || eregi("rowspan=", $tegparts[$l]) || eregi("class=", $tegparts[$l]))
						$tempstr.=" ".$tegparts[$l];
					}
			if ($rowcount>0)
					{
					$rowcountbackslash=1;
					$tegs[$j]="<TD".$tempstr." ".$td_style." name=name valign=top>";  // ���� ��� �� ������ ������, �� TD
					}
			else
					{
					$rowcountbackslash=0;
					$tegs[$j]="<TH".$tempstr." ".$th_style." name=name>";  // ���� ������ ������, �� ������ �� �� TH
					}
				}
		if (strtoupper(substr($tegs[$j], 0, 3))=="/TR")		// ������ /TR
				{
				$tegs[$j]="</TR name=name>";
				$rowcount=1;												// ���� �� ������ ����� ������ :)
				}
		if (strtoupper(substr($tegs[$j], 0, 3))=="/TH")
				{
				$tegs[$j]="</TH name=name>";
				$rowcountbackslash=1;											// �� ������ �������� ������
				}
		if (strtoupper(substr($tegs[$j], 0, 3))=="/TD")
				{
				if($rowcountbackslash==0)							// ���� ��� �� ���������� ���
					{
					$tegs[$j]="</TH name=name>";
					$rowcountbackslash=1;
					}
				else
					$tegs[$j]="</TD name=name>";
				}

		if (strtoupper(substr($tegs[$j], 0, 6))=="/TABLE")
				{
				$tegs[$j]="</TABLE name=name>";
				if ($table_bot!="")
						$tegs[$j].=$table_bot;		// ���� ���� �������-��������, �� �������� ��
				}
		if (strtoupper(substr($tegs[$j], 0, 4))=="SPAN") $tegs[$j]="";	// ������� SPAN
		if (strtoupper(substr($tegs[$j], 0, 5))=="/SPAN") $tegs[$j]="";	// ^^^^^^^^^^^^

		if (substr($tegs[$j],0,1)!="<")		// ���� ��� ��� �� ���������������� ���� ���, �� ...
				{
				if (strlen($tegs[$j])==1)	// ���� ��� - �� ����� �����
						{
					if (strtoupper($tegs[$j])!="P" && strtoupper($tegs[$j])!="B" && strtoupper($tegs[$j])!="U" && strtoupper($tegs[$j])!="I")
							$tegs[$j]="";	// ���� �� P B U I �� ������ ����� ���
					}
				elseif (strlen($tegs[$j])==2)	// ���� ��� ������� �� 2-� ��������, ��...
						{
					if (strtoupper(substr($tegs[$j], 0, 2))!="/B" && strtoupper(substr($tegs[$j], 0, 2))!="BR" && strtoupper(substr($tegs[$j], 0, 2))!="UL" && strtoupper(substr($tegs[$j], 0, 2))!="OL" && strtoupper(substr($tegs[$j], 0, 2))!="/P" && strtoupper(substr($tegs[$j], 0, 2))!="LI" && strtoupper(substr($tegs[$j], 0, 2))!="TR" && strtoupper(substr($tegs[$j], 0, 2))!="/U" && strtoupper(substr($tegs[$j], 0, 2))!="TD" && strtoupper(substr($tegs[$j], 0, 2))!="TH" && strtoupper(substr($tegs[$j], 0, 2))!="/I" && strtoupper(substr($tegs[$j], 0, 2))!="/A" && strtoupper(substr($tegs[$j], 0, 2))!="HR")
							$tegs[$j]="";	// ���� �� /B BR UL OL /P LI TR TH TD  � ��. �� �����
					}
				else							// ���� ���� ������ ��� ���
						{
					if (strtoupper(substr($tegs[$j], 0, 2))!="TA" && strtoupper(substr($tegs[$j], 0, 3))!="/OL" && strtoupper(substr($tegs[$j], 0, 3))!="/UL" && strtoupper(substr($tegs[$j], 0, 1))!="A" && strtoupper(substr($tegs[$j], 0, 3))!="/SU" && strtoupper(substr($tegs[$j], 0, 3))!="SUB" && strtoupper(substr($tegs[$j], 0, 3))!="SUP" && strtoupper(substr($tegs[$j], 0, 3))!="/LI" && strtoupper(substr($tegs[$j], 0, 2))!="TR" && strtoupper(substr($tegs[$j], 0, 2))!="TD" && strtoupper(substr($tegs[$j], 0, 2))!="TH" && strtoupper(substr($tegs[$j], 0, 2))!="BR"  && strtoupper(substr($tegs[$j], 0, 2))!="/T"  && strtoupper(substr($tegs[$j], 0, 2))!="IM"  && strtoupper(substr($tegs[$j], 0, 1))!="P"  && strtoupper(substr($tegs[$j], 0, 2))!="/P")
							$tegs[$j]="";		// ���� �� TABLE IMG � ��. �� �����
					}
					if (strtoupper(substr($tegs[$j], 0, 3))=="PRE"){
							$tegs[$j]="P";		// ���� PRE �� ������������ � P
					}
					if (strtoupper(substr($tegs[$j], 0, 4))=="/PRE"){
							$tegs[$j]="/P";		// ���� PRE �� ������������ � P
					}
				if (strtoupper(substr($tegs[$j], 0, 2))=="P ")	// ���� ��� P ��
						{
					$tempstr=" ";
					$tegparts=split(" ", $tegs[$j]);				// ��������� ��� � �� ���������
				for ($l=0;$l<count($tegparts);$l++)
							{
						if (eregi("align=", $tegparts[$l]))	// ��������� ������ ������� align
								$tempstr.=$tegparts[$l];
						}
				$tegs[$j]="<P".$tempstr.$p_style.">";		// ������� ������� align � ������� ���� $p_style
					}
			if (strtoupper(substr($tegs[$j], 0, 1))=="A")	// ���� ��� �
						{
					$tempstr=" ";
					$tegparts=split(" ", $tegs[$j]);
				for ($l=0;$l<count($tegparts);$l++)			// ��� �� �������� ��� ���������
							{
						if (eregi("href=", $tegparts[$l]))		// ��������� href
								$tempstr.=$tegparts[$l];
						if (eregi("name=", $tegparts[$l]))		// ���: ��������� name ��� ������
								$tempstr.=$tegparts[$l];
						}
					//if (substr($tempstr, strlen($tempstr)-1,1)!="\"" || substr($tempstr, strlen($tempstr)-1,1)!="'")	// ����� �� ��������� ��������� " ���� ����
						//	$tempstr.="\"";
					$tegs[$j]="<A".$tempstr.$a_style.">";				// ������� ���� ����� � � ���
					}
				}

		if (strtoupper(substr($tegs[$j], 0, 3))=="IMG")			// ���� ��������
				{
				$tegparts=split(" ", $tegs[$j]);						// �� �������� �������� �� ��������
				for ($l=0;$l<count($tegparts);$l++)
						{
					if (eregi("class=", $tegparts[$l]))			// ���� ������ ������, �� ��������� ��
							$tempstr.=" ".$tegparts[$l];
					if (eregi("width=", $tegparts[$l]))			// �������� ������ width=
							{
						$atrparts=split("=", $tegparts[$l]);		// ��������� �� �������� � ��������
						$atrparts[1]*=1;									// ����������� � �����
					if ($maxwidth>0 && $atrparts[1]>$maxwidth)	// ���� ������ �������� ������ ���������
								$error="������ ������ ��� ���������� ����������� �� ������������� ����������, <br>�������� ��� ������ ����� (������ - <i>$maxwidth px</i>, ������ - <i>$maxheight px</i>)!";
						}
				if (eregi("height=", $tegparts[$l]))			// �������� ������ height=
							{
						$atrparts=split("=", $tegparts[$l]);		// ��������� �� �������� � ��������
						$atrparts[1]*=1;											// ����������� � �����
					if ($maxheight>0 && $atrparts[1]>$maxheight)	// ���� ������ �������� ������ ���������
								$error="������ ������ ��� ���������� ����������� �� ������������� ����������, <br>�������� ��� ������ ����� (������ - <i>$maxwidth px</i>, ������ - <i>$maxheight px</i>)!";
						}
					}
			$partimg=split("src=", $tegs[$j]);		// ��������� �� �� ��� �� SCR � �����
			$srcimg=$partimg[1];
				if (substr($srcimg, 1, 4)=="http")		// ���� ��� ������ �� ����, ��...
						{
					if ($tegs[$j]!="")
							$newstr.="<".$tegs[$j].">";		// ������ ��������� ��� ���������
					else
							$newstr.="";
					}
				else												// ���� ������ �������� �� �����, ��...
						{
					$partsrc=split("\"", $srcimg);		// ��������� �� ��������
					$src=$partsrc[1];							// � $src ������ ���� � �����

				for ($k=0; $k<count($images); $k++)
							{
						if ($images[$k]==$src)
								{
								$flag=1;
								$schetim=$maximg+$k;
								$newstr.="<".$partimg[0]."src=\"".PATH."img/kat/".$kod.$numb.$schetim.".".$rass[$k]."\" ";
							for ($l=2; $l<count($partsrc); $l++)
										$newstr.=$partsrc[$l]."\"";
								$newstr=substr($newstr, 0, strlen($newstr)-1);
								$newstr.=">";
								}
						}

				if ($flag==0)										// ���� ����=0, � � ������ ��� �� ����� ����,��
							{
						$images[count($images)]=$src;			// � ������ $images[] ������� ���� � ��������
						$schetim=$maximg+count($images)-1;
						$ras="";
						for ($m=strlen($src)-1; $m>=0; $m--)	//------------------------
								{											//
								$simv=substr($src, $m, 1);			//
								if ($simv==".")						//	������� ���������� ��������
										break;								//
								else									//
										$ras=$simv.$ras;			// � ���������� $ras ���������� ��������
								}

						$rass[count($rass)]=$ras;			// ������� ���������� � ������
					$newstr.="<".$partimg[0]."src=\"".PATH."img/kat/".$kod.$numb.$schetim.".".$ras."\" ";
						for ($l=2; $l<count($partsrc); $l++)
								$newstr.=$partsrc[$l]."\"";
						$newstr=substr($newstr, 0, strlen($newstr)-1);
						$newstr.=">";
						}
				}		// ����� �������, ���� �������� �� �����, � �� �� �����
				}			// ����� �������, ��� ��� IMG
		else
				{
			if ($tegs[$j]!="")
							{
					if (substr($tegs[$j],0,1)=="<")
							$newstr.=$tegs[$j];
					else
							$newstr.="<".$tegs[$j].">";
						}
			else $newstr.="";
			}
		}
	$newstr.=addquotes($textes[$j]);
	}														// ����� �����, ������� ��������� �� �����
	# �����������, ������ ��� ���������� ���, � �������� ����� �����

for ($x=1;$x<=3;$x++){
	$newstr=str_replace(chr(13).chr(10),"", $newstr);
	$newstr=str_replace("&nbsp;&nbsp;&nbsp;&nbsp;", "&nbsp;", $newstr);
	$newstr=str_replace("&nbsp;&nbsp;", "&nbsp;", $newstr);
	$newstr=str_replace("<BR>&nbsp; <BR>", "<P>", $newstr);
	$newstr=str_replace("<BR>&nbsp;<BR>", "<P>", $newstr);
	$newstr=str_replace("</H1><BR>", "</H1>", $newstr);
	$newstr=str_replace("</H2><BR>", "</H2>", $newstr);
	$newstr=str_replace("</H3><BR>", "</H3>", $newstr);
	$newstr=str_replace("</H4><BR>", "</H4>", $newstr);
	$newstr=str_replace("</UL><BR>", "</UL>", $newstr);
	$newstr=str_replace("</OL><BR>", "</OL>", $newstr);
	$newstr=str_replace("<BR>&nbsp;<", "<", $newstr);
	$newstr=str_replace("<P>&nbsp;", "<P>", $newstr);
	$newstr=str_replace("<P></P>", "", $newstr);
	$newstr=str_replace("<B></B>", "", $newstr);
	$newstr=str_replace("<I></I>", "", $newstr);
	$newstr=str_replace("<B>&nbsp;</B>", "", $newstr);
	$newstr=str_replace("<B>&nbsp; </B>", "", $newstr);
	$newstr=str_replace("�&nbsp;", "<li>", $newstr);
	$newstr=ereg_replace('&amp;', '&', $newstr);
}
?>