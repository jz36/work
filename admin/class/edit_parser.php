<?
$contents=split("<BODY", $text);	// разделим введенный ПИН на до <BODY> и после
$content=substr($contents[1], 0, strlen($contents[1])-2); // ?? наверно убили \n\r
$contents=split("</BODY", $content);  // отделим остатки введенный ПИН  до </BODY>
$content=$contents[0];					// теперь $content - это ПИН текст который мы ввели


if ($id=="add")			// если добавляем новый раздел
	{
	$maximg=0;
	$numb=max_count($table, $min);		// возвращает следующий id в таблице, либо $min=100001
	}
else						// если редактируем старый раздел
	{
	$numb=$id;
	$res=mysql_query("select maxris from $table where id=$id");
	$row=mysql_fetch_row($res);						// дергаем из таблицы максимальное кол-во картинок
	$maximg=$row[0];										// не актуально, т.к. сейчас по картинкам не ограничивается
	}

$textcont="";									// тут будет текст изо всех тэгов с текстом
$polutegs=split("<", $content);			// разбили по <

$tegs=array();							// массив тэгов  Exp: P, /P, IMG src=... alt=...
$textes=array();							// массив текстов от тэгов, то есть <P>DIMA, тут будет DIMA
for ($i=0; $i<count($polutegs); $i++)
	{
	$parts=split(">", $polutegs[$i]);	// бъем теперь по <

	if (count($parts)==1)				// ?? по-моему никогда не выполняется  DEAD_CODE
		{
		$textcont.=$parts[0];			//		надо глубже проанализить но вроде дэдкод
		$textes[$i]=$parts[0];		//	??? DEAD CODE !!!!
		}
	else
		{
		$textcont.=$parts[1];	// $part[1] - либо пустой, либо текст внутри тэга, например внутри Р тэга
		$textes[$i]=$parts[1];
		$tegs[$i]=$parts[0];		//  тут либо ТЭГ, либо ТЭГ вместе со свойствами своими
		$tegs[$i]=str_replace("'\"\"'", "''", $tegs[$i]);
		$tegs[$i]=str_replace("'\"", "'", $tegs[$i]);
		$tegs[$i]=str_replace("\"'", "'", $tegs[$i]);
		}								// Exp: P, /P, IMG src=... alt=...
		
	$textes[$i]=str_replace("&quot;", "\"", $textes[$i]);   // Вычищаем все ковычки в тексте, 
																				//заменяем их на &quot;
	DbgPrint($textes[$i],0,"pars_content");
	}

$newstr="";
$images=array();
$rass=array();
$p_word=0;
$rowcount=0;						// номер ряда в таблице
$rowcountbackslash=0;						// номер ряда в таблице
for ($j=0; $j<$i; $j++)			// в $i осталось количество тегов
	{
	$flag=0;
	if (isset($tegs[$j]))
		{
		if (strtoupper($tegs[$j])=="STRONG") $tegs[$j]="<B>";		// просто меняет STRONG на <B>
		elseif (strtoupper($tegs[$j])=="/STRONG") $tegs[$j]="</B>"; // просто меняет /STRONG на </B>
		if (strtoupper($tegs[$j])=="BLOCKQUOTE") $tegs[$j]="<BLOCKQUOTE>";		// просто меняет BLOCKQUOTE на <BLOCKQUOTE>
		elseif (strtoupper($tegs[$j])=="/BLOCKQUOTE") $tegs[$j]="</BLOCKQUOTE>"; // просто меняет /BLOCKQUOTE на </BLOCKQUOTE>
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="H1")  {
			$tegparts=split(" ", $tegs[$j]);				// разбиваем тег на аттрибуты
			for ($l=0;$l<count($tegparts);$l++)
				if (eregi("align=", $tegparts[$l]) || eregi("class=", $tegparts[$l]))	$tempstr=" ".$tegparts[$l]; // сохраняем только атрибут align
			$tegs[$j]="<H1".$tempstr.">";		// допишем атрибут align
		}
		elseif (strtoupper(substr($tegs[$j], 0, 3))=="/H1") $tegs[$j]="</H1>"; // просто меняет /h1 на </h1>
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="H2")  {
			$tegparts=split(" ", $tegs[$j]);				// разбиваем тег на аттрибуты
			for ($l=0;$l<count($tegparts);$l++)
				if (eregi("align=", $tegparts[$l]))	$tempstr=" ".$tegparts[$l]; // сохраняем только атрибут align
			$tegs[$j]="<H2".$tempstr.">";		// допишем атрибут align
		}
		elseif (strtoupper(substr($tegs[$j], 0, 3))=="/H2") $tegs[$j]="</H2>"; // просто меняет /h2 на </h2>
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="H3") {
			$tegparts=split(" ", $tegs[$j]);				// разбиваем тег на аттрибуты
			for ($l=0;$l<count($tegparts);$l++)
				if (eregi("align=", $tegparts[$l]))	$tempstr=" ".$tegparts[$l]; // сохраняем только атрибут align
			$tegs[$j]="<H3".$tempstr.">";		// допишем атрибут align
		}
		elseif (strtoupper(substr($tegs[$j], 0, 3))=="/H3") $tegs[$j]="</H3>"; // просто меняет /h3 на </h3>
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="H4") {
			$tegparts=split(" ", $tegs[$j]);				// разбиваем тег на аттрибуты
			for ($l=0;$l<count($tegparts);$l++)
				if (eregi("align=", $tegparts[$l]))	$tempstr=" ".$tegparts[$l]; // сохраняем только атрибут align
			$tegs[$j]="<H4".$tempstr.">";		// допишем атрибут align
		}
		elseif (strtoupper(substr($tegs[$j], 0, 3))=="/H4") $tegs[$j]="</H4>"; // просто меняет /h4 на </h4>
		
		
		if (strtoupper(substr($tegs[$j], 0, 2))=="LI") $tegs[$j]="<LI>";		// просто меняет LI на <LI>
		if (strtoupper(substr($tegs[$j], 0, 2))=="OL") $tegs[$j]="<OL>";		// просто меняет LI на <LI>
		if (strtoupper(substr($tegs[$j], 0, 2))=="UL") $tegs[$j]="<UL>";		// просто меняет LI на <LI>
		if (strtoupper(substr($tegs[$j], 0, 2))=="B " || strtoupper(substr($tegs[$j], 0, 2))=="U " || strtoupper(substr($tegs[$j], 0, 2))=="I ")
			$tegs[$j]="<".strtoupper(substr($tegs[$j], 0, 1)).">";
		if (strtoupper($tegs[$j])=="EM") $tegs[$j]="<I>";			// просто меняет EM на <I>
		elseif (strtoupper($tegs[$j])=="/EM") $tegs[$j]="</I>";	// просто меняет /EM на </I>
		/*
		if (strtoupper(substr($tegs[$j], 0, 2))=="P " && $j!=1)				// если P из ворда с отступами - 0, то...
			{
			$tegparts=split("MARGIN: 0cm 0cm 0pt", $tegs[$j]);
			if (isset($tegparts[1]))	
			$tegs[$j]="<BR>";
			$p_word=1;
			}
		if ((strtoupper(substr($tegs[$j], 0, 2))=="/P") && ($p_word==1))	// Убиваем /Р, если Р из ворда превратили в br
			{
			$tegs[$j]="";
			$p_word=0;
			}
		*/
		if (strtoupper(substr($tegs[$j], 0, 5))=="TABLE")			// если тэг - это TABLE, то едем далее
			{
			$tempstr="";
			$tegparts=split(" ", $tegs[$j]);				// разбиваем тег на аттрибуты
			for ($l=0;$l<count($tegparts);$l++){
				if (eregi("align=", $tegparts[$l]) ||  eregi("class=", $tegparts[$l]))	
					$tempstr=" ".$tegparts[$l]; // сохраняем только атрибутs
				DbgPrint($tegs[$j],0,"parser");
			}
			$tegs[$j]=$table_top."<TABLE".$table_style." ".$tempstr."  name=name>"; // добавляем стиль, в $table_top можно
			$rowcount=1;															// вписать верхнюю обрамляющую таблицу
			}
		if (strtoupper(substr($tegs[$j], 0, 2))=="TR")				// если начальный ряд, то...
			{
			if ($rowcount>0)													// если не первый ряд, то..
				$tegs[$j]="<TR".$tr_style." name=name>";				// стиль $tr_style
		else
				$tegs[$j]="<TR".$trfirst_style." name=name>";			// если первый ряд, то стиль для первого ряда
			}

  		if (strtoupper(substr($tegs[$j], 0, 2))=="TH")		// если TH тег
				{
				$tempstr="";
			$tegparts=split(" ", $tegs[$j]);						// получим массив атрибутов тега
				$rowcount=1;												// для того, чтобы TD не поменять дальше на TH
				for ($l=0;$l<count($tegparts);$l++)
						{
					if (eregi("width=", $tegparts[$l]))			// если задана ШИРИНА, то сохранить ее
					if (eregi("colspan=", $tegparts[$l]) || eregi("bgcolor=", $tegparts[$l]) || eregi("rowspan=", $tegparts[$l]) || eregi("class=", $tegparts[$l]))
							$tempstr.=" ".$tegparts[$l];				// если заданы COLSPAN и ROWSPAN BGCOLOR и CLASS то сохранить их
					}
				$tegs[$j]="<TH".$tempstr." ".$th_style." name=name>"; // стили для TH
				}
		if (strtoupper(substr($tegs[$j], 0, 2))=="TD")		// то же самое мутим для TD
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
					$tegs[$j]="<TD".$tempstr." ".$td_style." name=name valign=top>";  // если это не первая строка, то TD
					}
			else
					{
					$rowcountbackslash=0;
					$tegs[$j]="<TH".$tempstr." ".$th_style." name=name>";  // если первая строка, то меняем ее на TH
					}
				}
		if (strtoupper(substr($tegs[$j], 0, 3))=="/TR")		// просто /TR
				{
				$tegs[$j]="</TR name=name>";
				$rowcount=1;												// одна то строка точно прошла :)
				}
		if (strtoupper(substr($tegs[$j], 0, 3))=="/TH")
				{
				$tegs[$j]="</TH name=name>";
				$rowcountbackslash=1;											// на всякий пожарный случай
				}
		if (strtoupper(substr($tegs[$j], 0, 3))=="/TD")
				{
				if($rowcountbackslash==0)							// если еще не закрывался тэг
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
						$tegs[$j].=$table_bot;		// если есть таблица-оболочка, то добавить ее
				}
		if (strtoupper(substr($tegs[$j], 0, 4))=="SPAN") $tegs[$j]="";	// убиваем SPAN
		if (strtoupper(substr($tegs[$j], 0, 5))=="/SPAN") $tegs[$j]="";	// ^^^^^^^^^^^^

		if (substr($tegs[$j],0,1)!="<")		// если это еще не модифицированные нами тег, то ...
				{
				if (strlen($tegs[$j])==1)	// если тег - из одной буквы
						{
					if (strtoupper($tegs[$j])!="P" && strtoupper($tegs[$j])!="B" && strtoupper($tegs[$j])!="U" && strtoupper($tegs[$j])!="I")
							$tegs[$j]="";	// если не P B U I то просто мочим тег
					}
				elseif (strlen($tegs[$j])==2)	// если тэг состоит из 2-х символов, то...
						{
					if (strtoupper(substr($tegs[$j], 0, 2))!="/B" && strtoupper(substr($tegs[$j], 0, 2))!="BR" && strtoupper(substr($tegs[$j], 0, 2))!="UL" && strtoupper(substr($tegs[$j], 0, 2))!="OL" && strtoupper(substr($tegs[$j], 0, 2))!="/P" && strtoupper(substr($tegs[$j], 0, 2))!="LI" && strtoupper(substr($tegs[$j], 0, 2))!="TR" && strtoupper(substr($tegs[$j], 0, 2))!="/U" && strtoupper(substr($tegs[$j], 0, 2))!="TD" && strtoupper(substr($tegs[$j], 0, 2))!="TH" && strtoupper(substr($tegs[$j], 0, 2))!="/I" && strtoupper(substr($tegs[$j], 0, 2))!="/A" && strtoupper(substr($tegs[$j], 0, 2))!="HR")
							$tegs[$j]="";	// если не /B BR UL OL /P LI TR TH TD  и др. то мочим
					}
				else							// если букв больше чем две
						{
					if (strtoupper(substr($tegs[$j], 0, 2))!="TA" && strtoupper(substr($tegs[$j], 0, 3))!="/OL" && strtoupper(substr($tegs[$j], 0, 3))!="/UL" && strtoupper(substr($tegs[$j], 0, 1))!="A" && strtoupper(substr($tegs[$j], 0, 3))!="/SU" && strtoupper(substr($tegs[$j], 0, 3))!="SUB" && strtoupper(substr($tegs[$j], 0, 3))!="SUP" && strtoupper(substr($tegs[$j], 0, 3))!="/LI" && strtoupper(substr($tegs[$j], 0, 2))!="TR" && strtoupper(substr($tegs[$j], 0, 2))!="TD" && strtoupper(substr($tegs[$j], 0, 2))!="TH" && strtoupper(substr($tegs[$j], 0, 2))!="BR"  && strtoupper(substr($tegs[$j], 0, 2))!="/T"  && strtoupper(substr($tegs[$j], 0, 2))!="IM"  && strtoupper(substr($tegs[$j], 0, 1))!="P"  && strtoupper(substr($tegs[$j], 0, 2))!="/P")
							$tegs[$j]="";		// если не TABLE IMG и др. то мочим
					}
					if (strtoupper(substr($tegs[$j], 0, 3))=="PRE"){
							$tegs[$j]="P";		// если PRE то переделываем в P
					}
					if (strtoupper(substr($tegs[$j], 0, 4))=="/PRE"){
							$tegs[$j]="/P";		// если PRE то переделываем в P
					}
				if (strtoupper(substr($tegs[$j], 0, 2))=="P ")	// елси тэг P то
						{
					$tempstr=" ";
					$tegparts=split(" ", $tegs[$j]);				// разбиваем тег Р на аттрибуты
				for ($l=0;$l<count($tegparts);$l++)
							{
						if (eregi("align=", $tegparts[$l]))	// сохраняем только атрибут align
								$tempstr.=$tegparts[$l];
						}
				$tegs[$j]="<P".$tempstr.$p_style.">";		// допишем атрибут align и вставим свои $p_style
					}
			if (strtoupper(substr($tegs[$j], 0, 1))=="A")	// если тег А
						{
					$tempstr=" ";
					$tegparts=split(" ", $tegs[$j]);
				for ($l=0;$l<count($tegparts);$l++)			// так же получаем его аттрибуты
							{
						if (eregi("href=", $tegparts[$l]))		// сохраняем href
								$tempstr.=$tegparts[$l];
						if (eregi("name=", $tegparts[$l]))		// МОЕ: сохраняем name для ссылок
								$tempstr.=$tegparts[$l];
						}
					//if (substr($tempstr, strlen($tempstr)-1,1)!="\"" || substr($tempstr, strlen($tempstr)-1,1)!="'")	// зачем то добавляем последнюю " если есть
						//	$tempstr.="\"";
					$tegs[$j]="<A".$tempstr.$a_style.">";				// добавим свой стиль в А тег
					}
				}

		if (strtoupper(substr($tegs[$j], 0, 3))=="IMG")			// если картинка
				{
				$tegparts=split(" ", $tegs[$j]);						// то пытаемся получить ее атрибуты
				for ($l=0;$l<count($tegparts);$l++)
						{
					if (eregi("class=", $tegparts[$l]))			// если задана ШИРИНА, то сохранить ее
							$tempstr.=" ".$tegparts[$l];
					if (eregi("width=", $tegparts[$l]))			// получаем ширину width=
							{
						$atrparts=split("=", $tegparts[$l]);		// разбиваем на параметр и значение
						$atrparts[1]*=1;									// преобразуем в число
					if ($maxwidth>0 && $atrparts[1]>$maxwidth)	// если размер картинки больше заданного
								$error="Размер одного или нескольких изображений не соответствуют стандартам, <br>принятым для Вашего сайта (ширина - <i>$maxwidth px</i>, высота - <i>$maxheight px</i>)!";
						}
				if (eregi("height=", $tegparts[$l]))			// получаем высоту height=
							{
						$atrparts=split("=", $tegparts[$l]);		// разбиваем на параметр и значение
						$atrparts[1]*=1;											// преобразуем в число
					if ($maxheight>0 && $atrparts[1]>$maxheight)	// если размер картинки больше заданного
								$error="Размер одного или нескольких изображений не соответствуют стандартам, <br>принятым для Вашего сайта (ширина - <i>$maxwidth px</i>, высота - <i>$maxheight px</i>)!";
						}
					}
			$partimg=split("src=", $tegs[$j]);		// разбиваем на то что до SCR и после
			$srcimg=$partimg[1];
				if (substr($srcimg, 1, 4)=="http")		// если уже залито на сайт, то...
						{
					if ($tegs[$j]!="")
							$newstr.="<".$tegs[$j].">";		// просто оставляем без изменений
					else
							$newstr.="";
					}
				else												// если ссылка картинки на диске, то...
						{
					$partsrc=split("\"", $srcimg);		// разбиваем по кавычкам
					$src=$partsrc[1];							// в $src теперь путь к файлу

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

				if ($flag==0)										// если флаг=0, а в первый раз он точно ноль,то
							{
						$images[count($images)]=$src;			// в массив $images[] добавим путь к картинке
						$schetim=$maximg+count($images)-1;
						$ras="";
						for ($m=strlen($src)-1; $m>=0; $m--)	//------------------------
								{											//
								$simv=substr($src, $m, 1);			//
								if ($simv==".")						//	дергаем расширение картинки
										break;								//
								else									//
										$ras=$simv.$ras;			// в переменной $ras расширение картинки
								}

						$rass[count($rass)]=$ras;			// заносим расширение в массив
					$newstr.="<".$partimg[0]."src=\"".PATH."img/kat/".$kod.$numb.$schetim.".".$ras."\" ";
						for ($l=2; $l<count($partsrc); $l++)
								$newstr.=$partsrc[$l]."\"";
						$newstr=substr($newstr, 0, strlen($newstr)-1);
						$newstr.=">";
						}
				}		// конец условия, если картинка на диске, а не на хосте
				}			// конец условия, что тег IMG
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
	}														// конец цикла, который пробегает по тэгам
	# послепарсер, чистит уже почищенный код, в основном после ворда

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
	$newstr=str_replace("·&nbsp;", "<li>", $newstr);
	$newstr=ereg_replace('&amp;', '&', $newstr);
}
?>