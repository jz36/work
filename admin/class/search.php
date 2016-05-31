<?
/********************************************************\
*  Модуль поиска mod_search.php									*
*																			*
*  Автор: Горнов Дмитрий (версия 0x100)						*
*																			*
*  Дата: 13 апреля 2004											*
*																			*
*  Модификации:														*
*  11 ноября 2004 Панченко И.А.									*
*  Функция изменена по систему bSite							*
*																			*
\********************************************************/

Function search_init($save_table){
	#	инициализация таблицы для сохранения результатов
	#	(версия 0x100 - просто удаление всех записей

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
	*  $search_text - текст для поиска
	*  $tables - массив таблиц в которых осуществлять поиск
	*  $fields - массив колонок в таблицах
	*
	*  $search_title[] - массив в который записываются название поля в базе
	*							которое является заголовком
	*							Если строка начинается с @ то она и выводится как заголовок!!!
	*  $search_title_numchars - число символов для записи заголовка
	*  $search_content[] - массив в который записываются название поля в базе
	*							которое является контентом
	*  $search_content_numchars - число символов для записи контента
	*
	*  $save_table - таблица для сохранения результатов
	*  $search_redirect_page - массив страниц, на который переходить
	*									соответственно таблицам
	*
	*  $id и $top должны присутствовать в искомых таблицах!!!
	*
	*********************************************************/

	$find_words=explode(" ",$search_text); // разбиваем по пробелам искомую строку
	$find_num_words=sizeof($find_words);	// количество слов поиска

	foreach($tables as $tbl)				// выбираем таблицы по очереди
	{
			foreach((array)$fields[$tbl] as $key=>$value)  // получаем поля таблиц для поиска
			{

				$tmp_res=row_select("id,top,$value",$tbl);			// делаем выборку из базы
				$tmp_kol=$tmp_res->nr();	// получаем число записей
				//echo "<p>".$tbl;
				
				for($i=0;$i<$tmp_kol;$i++)				// пробегаем по полученным записям
				{
					$tmp_r=$tmp_res->gr();	// получаем запись

					/*
					*  Подготовим считанные из базы данные
					*/
					$result=strip_tags($tmp_r[2]);
					$result=strtr($result,"QWERTYUIOPASDFGHJKLZXCVBNMЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ","qwertyuiopasdfghjklzxcvbnmёйцукенгшщзхъфывапролджэячсмитьбю");
					$result=stripslashes($result);
					//echo $result;

					$relevancy=0;  // релевантность
				foreach($find_words as $word)		// $word - одно слово из массива слов
					{
						/*
							*  Поиск по словам в данных таблицы
							*/
							$cursor=0;	// позиция с которой искать слово в тексте из базы

							if($word=='')	// если пустое слово
								continue;

							while(strpos($result,$word,$cursor)!==false)
							{
							/*
							*	Что-то нашли, сдвинем курсор поиска далее этого слова
								*	и двинемся дальше
							*/
				
								$cursor=strpos($result,$word,$cursor)+1;
								$relevancy++; // увеличим релевантность

						} //  while(strpos($result,$word,$cursor)!==false)

					}  // foreach($find_words as $word)

					/*
					*	Тут запишем результаты нашего поиска
					*/
					if($relevancy!=0)
					{
						/*
							*  Если хоть одно слово совпало, то запишем результат в базу
							*/

							/*
							*	Сначала получим заголовок в соответствии с данными откуда и сколько символов взять
							*	для заголовка
							*/
							if($search_title[$tbl][0]!='@')  // если просто задано имя таблицы
							{
								$title_res=row_select($search_title[$tbl],$tbl,"id=$tmp_r[0]");
								$title_r=$title_res->gr();
							}
							else	// если задано просто строка, которую выводить
							{
								$tmp_name_str=str_replace("@","",$search_title[$tbl]);
								$title_r[0]=$tmp_name_str;
							}

							$title_r[0]=strip_tags($title_r[0]);
							$title_r[0]=stripslashes($title_r[0]);

							$read_length=strlen($title_r[0]); // число символов в текущем заголовке
							if($read_length>$search_title_numchars[$tbl])
							{
								/*
								*	Если символов в заголовке меньше чем заказали, то брать то что есть
								*/
								$read_length=$search_title_numchars[$tbl];
							}
							$title=substr($title_r[0],0,$read_length);	//  формируем
							$title=addslashes($title);						// окончательный заголовок

							/*
							*	Сейчас проделаем то же самое с контентом

							*	Сначала получим контент в соответствии с данными откуда и сколько символов взять
							*	для контента
							*/
							$content_r=s_select($search_content[$tbl],$tbl,"id=$tmp_r[0]");
							$content_r=str_replace("&nbsp;"," ",$content_r);
							$content_r=strip_tags($content_r);
							$content_r=stripslashes($content_r);
							$content_r=strtr($content_r,"QWERTYUIOPASDFGHJKLZXCVBNMЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ","qwertyuiopasdfghjklzxcvbnmёйцукенгшщзхъфывапролджэячсмитьбю");

							$read_length=strlen($content_r); // число символов в текущем контенте
							
							if($read_length>$search_content_numchars[$tbl])
							{
								/*
								*	Если символов в контенте меньше чем заказали, то брать то что есть
								*/
								$read_length=$search_content_numchars[$tbl];
							}
							$content=substr($content_r,0,$read_length);	//  формируем
							$content=addslashes($content);						// окончательный контент
							$content=trim($content);

							$id_path="";
							
							$idd=$tmp_r[0];
							$ttop=1;
							/*
							* Если у нас картинка или ссылка или файл, то вычисляем откуда они
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
							*  После предварительной подготовки мы можем записать в базу
							*  результаты нашего поиска
							*/
							$tmp=s_select("id",$save_table,"id=1");
							s_insert($save_table,"name,content,tops,page,relevancy","\"$title\",\"$content\",\"$id_path\",\"$search_redirect_page[$tbl]\",$relevancy");

					}  

				}  

			}  

	}  

}

?>