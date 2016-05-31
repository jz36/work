		<hr>
		</div>
		</div>
		</td>
		<td class="right"><?
		require_once "./dist.php";
		//$q = mysql_query("SELECT * FROM `wed_library_items` WHERE `id`=179");
		//$row = mysql_fetch_assoc($q);

		//print '<h2 style="border: 0px solid rgb(255, 255, 255); margin: 10px -8px 0px; padding: 3px 5px; background: rgb(189, 0, 0); display: block; font-size: 14px; color: rgb(255, 255, 255);">Наши предложения</h2>';
		//print $row['full_text'];
		?></td>
	</tr>
	<tr>
		<td height="30" bgcolor="#FFFFFF">&nbsp;</td>
		<td align=left style="border-bottom: 1px solid #CFCFCF">
		<div class="body_foot"><a href="#">Наверх</a> <a href="javascript:window.history.back()">Назад</a> <a
			href="<?=SPAGE?>">Главная</a> <a class=small
			href="print.php?<?if ($main!="") echo "main=$main";if ($id!="") echo "&id=$id";if (isset($top)) echo "&top=$top";if (isset($sub)) echo "&sub=$sub";?>"
			target="_blank"> Версия для печати</a> <a href="index.php?main=site_map">Карта сайта</a> <a
			href="index.php?main=contacts">Контакты</a></div>
		</td>
		<td bgcolor='#F2EDDF' style="border-bottom: 1px solid #CFCFCF">&nbsp;</td>
	</tr>
</table>
<div class="foot">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top: 1px;">
	<tr>
		<td height="70" align="center" width=23%><noindex> <!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/go/britannix.ru' "+
"target=_blank rel=nofollow><img src='http://counter.yadro.ru/hit?t12.10;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border=0 width=88 height=31><\/a>")//--></script><!--/LiveInternet--> </noindex>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter34417590 = new Ya.Metrika({
                    id:34417590,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    trackHash:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/34417590" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-69087500-2', 'auto');
  ga('send', 'pageview');

</script>








</td>


		<td width=47%>
		<div class="foot2">Адрес: : г. Екатеринбург, ул. Гоголя, 15а, оф. 9<br />
		(Вход через крыльцо Генерального консульства США, первый этаж)<br />
		Тел: (343) 375-00-00, 310-10-45, 310-10-46<br />
		офисный мобильный номер +7 912 266 55 74<br>
		E-mail: <a href='mailto:info@britannix.ru'>info@britannix.ru</a><br />
		Skype:britannix<br />
		
		Информация, размещенная на сайте, является справочной и может быть изменена в одностороннем порядке.
		</div>
		</td>
		<td width=30%><?counter("")?><BR>
		<BR>

		<a href="http://d1.ru">Программирование сайта Екатеринбург - D1.ru</a><BR>
		<BR>
		<a href="http://y1.ru">Оптимизация сайта Екатеринбург - Y1.ru</a></td>
	</tr>
</table>
</div>
</div>
		<? // menu_popup()

		$q = mysql_query("SELECT * FROM `wed_library` WHERE `parent_id`=31 AND `hide`=0 ORDER BY `order`");
		$count = mysql_num_rows($q);

		for ($i=0;$i<$count;$i++) {

			$row = mysql_fetch_assoc($q);

			if($row['id'] != 36 and $row['id'] != 38) {

				$qi = mysql_query("SELECT * FROM `wed_library_items` WHERE `category_id`=".$row['id']." ORDER BY `order`");
				$counti = mysql_num_rows($qi);

				print '<div class=popup id=h'.($i+1).' style="top:0px; left: 0px;" onmouseover=clearTimeout(tid);m_over('.($i+1).',0); onmouseout=hiding('.($i+1).',1);m_out('.($i+1).',0);>';
				for ($j=0; $j<$counti; $j++) {

					$rowi = mysql_fetch_assoc($qi);
					print '<div class="popup-item"><a href="index.php?id='.$rowi['id'].'">'.$rowi['title'].'</a></div>';
				}
				print '</div>';
			}
		}
		?>
</body>
</html>
