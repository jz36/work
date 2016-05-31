<?
#Форум#0

if (!empty($id)){
	#Вычисляем уровень
	$level=s_select("top","","id=".$id);
	echo "<h2>".s_select("name","","id=".$id);
	echo "</h2>";
	
	}


#===========================================================
# Получаем и обрабатываем данные из формы

if(isset($_POST['sbm']))
        {
	    	$error="";
	    	if(!isset($_POST['fio']) || $_POST['fio']=="") $error="Заполните поле ФИО.";
	    	if ($level==0) if(!isset($_POST['topic']) || $_POST['topic']=="") $error="Заполните поле Название темы.";
	    	if(!isset($_POST['message']) || $_POST['message']=="") $error="Заполните поле Сообщение.";
	      if($_POST['email']!="" && !check_email($_POST['email'])) $error="Введите в поле E-mail корректный адрес, либо не вводите его совсем.";

	        $_POST['fio']=strip_tags($_POST['fio']);
	        if ($level==0) $_POST['topic']=strip_tags($_POST['topic']);
	        $_POST['email']=strip_tags($_POST['email']);
	        $_POST['message']=strip_tags($_POST['message']);

	        if(!get_magic_quotes_gpc())
	        {
		        $_POST['fio']=addslashes($_POST['fio']);
		        if ($level==0) $_POST['topic']=addslashes($_POST['topic']);
		        $_POST['email']=addslashes($_POST['email']);
		        $_POST['message']=addslashes($_POST['message']);
        	}

            if(strlen($_POST['fio'])>250) $_POST['fio']=substr($_POST['fio'],0,250);
            if ($level==0) if(strlen($_POST['topic'])>250) $_POST['topic']=substr($_POST['topic'],0,250);
            if(strlen($_POST['email'])>250) $_POST['email']=substr($_POST['email'],0,250);
            if(strlen($_POST['message'])>65000) $_POST['message']=substr($_POST['message'],0,65000);
            $_POST['message']=str_replace("\n","<br>",$_POST['message']);

           
            /*  проверим, не создана ли уже такая тема, то есть не нажали ли F5 чтобы обновить */
            if ($level==0)
            	$res=row_select("id","","author=\"".$_POST['fio']."\" and name=\"".$_POST['topic']."\" and top=$id");
            else
            	$res=row_select("id","","author=\"".$_POST['fio']."\" and name=\"".$_POST['message']."\" and top=$id");
            /*созданная тема: name- название темы; message- автор темы */
            if($res->nr()>0)
            {
            	$error="<span class=red>Вы уже создали эту тему</span>";
            }

            if($error=="")  // ошибок не было
            {
            	$cur_date=date("Y-m-d");
            	$cur_time=date("H:i");
            	$ip=$_SERVER['REMOTE_ADDR'];
	            if($level==0){
            		$lastID=s_select("max(id)");
	            	$res=s_insert("","id,name,author,email,data,time,top,ip",   "($lastID+1),\"".$_POST['topic']."\",\"".$_POST['fio']."\",\"".$_POST['email']."\",\"$cur_date\",\"$cur_time\",$id,'$ip'");
	            	$res=s_insert("","id,name,author,email,data,time,top,ip",   "($lastID+2),\"".$_POST['message']."\",\"".$_POST['fio']."\",\"".$_POST['email']."\",\"$cur_date\",\"$cur_time\",($lastID+1),'$ip'");
	            }
                if($level>0) {                	
            		$lastID=s_select("max(id)");
                  $res=s_insert("","id,name,author,email,data,time,top,ip",  "($lastID+1),\"".$_POST['message']."\",\"".$_POST['fio']."\",\"".$_POST['email']."\",\"$cur_date\",\"$cur_time\",$id,'$ip'");
                  $res=s_update("data=\"$cur_date\" ,time=\"$cur_time\"","","id=$id");
                }

            } // раздел выполняется если ошибок нет

        	if($error!="")
            {
            	echo "<b>$error</b><br><br>";
            }
        }




#=============================================
# Пришли на форум

#Если приходим на форум обсуждать какой нибудь раздел
if (!empty($_GET['topic'])){
	if(s_select("id","","name=\"$topic\"")) {?>
		<script>
		window.location.href="<?=SPAGE?>?main=<?=$main?>&id=<?=s_select("id","","name=\"$topic\"")?>";
		</script>
		<?}
	else $add=1;
	}


#=============================================
# В режиме просмотра форума

if (empty($add)){
#=============================================
# В самом начале

if (empty($id)){
	$res=row_select("id,name,content","","top=0 and visible=1");
	if($res && $res->nr()>1){?>
		<table class="table_forum" cellpadding=0 cellspacing=0>
		<tr>
			<th>Форум</th>
			<th>Тем</th>
			<th>Сообщений</th>
			<th>Последнее сообщ-е</th>
		</tr><?
		while($r=$res->ga()){
			$author_last="";$email_last="";$time_last="";
			$tmp=row_select("id,data,time","","top=".$r["id"],"data DESC,time DESC");
			$num_t=$tmp->nr();
			$data_last="";$time_last="";$num_s=0;
			while ($r2=$tmp->ga()){
				$tmp2=row_select("id,author,data,time,email","","top=".$r2["id"],"data DESC,time DESC");
				$tmp2_data=$tmp2->ga();
				$num_s+=($tmp2->nr())-1;
				if ($data_last<=$tmp2_data["data"]) {
					$data_last=$tmp2_data["data"];
					if ($time_last<=$tmp2_data["time"]) {
						$time_last=$tmp2_data["time"];
						$id_last=$tmp2_data["id"];
						$author_last=$tmp2_data["author"];
						$email_last=$tmp2_data["email"];
					}
				}
			}
			?>
			<tr>
			<td><a href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r['id']?>"><b><?=$r['name']?></b><div class=small><?=$r['content']?></div></a></td>
			<td align=center class="small  bgcolor2"><?=$num_t?></td>
			<td align=center class="small  bgcolor2"><?=$num_s?></td>
			<td align=center class="small  bgcolor2">
			<?if (!empty($time_last)){?>
			<a href="<?=SPAGE?>?main=<?=$main?>&id=<?=s_select("top","","id=$id_last")?>#<?=$id_last?>" title="Последнее сообщение" class="small">
			<? echo remakedata($data_last)."&nbsp;, ".substr($time_last,0,5);?><br>
			<?=$author_last?></a>
			<?} else echo "----"?>
			</td>
			</tr>			
			<?		
		}?>
		</table><?
	}
	else if ( $res->nr()==1){
		$r=$res->ga();
		?>
		<script>
		window.location.href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r["id"]?>";
		</script>
		<?
		
	}
	
}

#=============================================
# Зашли в конкретный форум

elseif (!empty($id) && $level==0){?>
		<div class=menu_in>[ <a href="<?=SPAGE?>?main=<?=$main?>&add=1&id=<?=$id?>">Начать новую тему</a> ]</div><?
	$res=row_select("id,name,author,email,alert,views","","top=$id and visible=1","alert DESC, data DESC,time DESC");
	if($res && $res->nr()>0){?>
		<table class="table_forum" cellpadding=0 cellspacing=0>
		<tr>
			<th>Тема</th>
			<th>Автор</th>
			<th>Сообщ-й</th>
			<th>Просмотров</th>
			<th>Последнее сообщ-е</th>			
		</tr><?
		$i=0;
		while($r=$res->ga()){
			$tmp=row_select("id,data,time,author,email","","top=".$r["id"],"data DESC,time DESC");
			$num_t=($tmp->nr())-1;
			$data_last=$tmp->ga()?>
			<tr class=bgcolor<?if ($i%2) echo "0"; else echo "0"; ?>>
			<td><a href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r['id']?>"><?if ($r["alert"]==1) echo "<b>";?><?=$r['name']?><?if ($r["alert"]==1) echo "</b>";?></a></td>
			<td align=center class="small bgcolor2"><?=email_echo($r['email'],$r['author'],"small");?></td>
			<td align=center class="small bgcolor2"><?=$num_t?></td>
			<td align=center class="small bgcolor2"><?=$r['views']?></td>
			<td align=center class="small"><a href="<?=SPAGE?>?main=<?=$main?>&id=<?=$r['id']?>#<?=$data_last["id"]?>" title="Последнее сообщение" class="small">
			<? echo remakedata($data_last["data"])."&nbsp;, ".substr($data_last["time"],0,5);?><br>
			<?=$data_last['author']?></a></td>
			</tr>			
			<?
			$i++;		
		}?>
		</table>
		<div class=menu_in>[ <a href="<?=SPAGE?>?main=<?=$main?>&add=1&id=<?=$id?>">Начать новую тему</a> ]</div><?
	}}

#=============================================
# Зашли в конкретную тему

elseif (!empty($id) && $level>0){
	$res=row_select("id,name,author,email,data,time","","top=$id and visible=1","id");
	if($res && $res->nr()>0){?>
		<div class=menu_in align=right>
		[ <a href="<?=SPAGE?>?main=<?=$main?>&add=1&id=<?=s_select("top","","id=$id")?>">Начать новую тему</a> ] 
		[ <a href="<?=SPAGE?>?main=<?=$main?>&add=2&id=<?=$id?>">Добавить сообщение</a> ]
		[ <a href="<?=SPAGE?>?main=<?=$main?>&id=<?=$level?>">Вернутся к списку тем</a> ]</div>
		<table class="table_forum" cellpadding=0 cellspacing=0>
		<tr>
			<th width=20%>Автор</th>
			<th width=80%>Сообщение</th>			
		</tr><?
		$i=0;
		while($r=$res->ga()){?>
			<tr class=bgcolor<?if ($i%2) echo "0"; else echo "2"; ?>>
			<td align=center  valign=top class="small"><a name=<?=$r['id']?>></a><?
				echo remakedata($r["data"])."&nbsp;, ".substr($r["time"],0,5)."<br>";
				if ($r['email']!="") {echo "<a href='mailto:".$r['email']."' class=small>";}
				echo $r['author'];
				if ($r['email']!="") {echo "</a>"; }?>
			</td>
			<td valign=top ><?
			$text=$r['name'];
			$text=str_replace("[quote]","<div class=quote>",$text);$text=str_replace("[/quote]","</div>",$text);
			$text=str_replace("[b]","<b>",$text);$text=str_replace("[/b]","</b>",$text);
			$res2=row_select("id,name,content,img",$main."_smiles");
			while($r2=$res2->ga()){
				$text=str_replace($r2["content"],"<img src=".SITE_ADMIN_DIR."/img/forum/smiles/".$r2["img"]." class=borderno>",$text);				
				}
			echo $text;
			?></td>
			</tr>
			<tr class="bgcolor<?if ($i%2) echo "0"; else echo "2"; ?> cit">
			<td><a href="#top" class=small title="наверх">.</a></td>
			<td valign=top align=right><a href="<?=SPAGE?>?main=<?=$main?>&add=2&id=<?=$id?>&cit=<?=$r['id']?>" class=small>Ответить с цитатой</a></td>
			</tr>
			<tr class="divider1"><td colspan=2 class="divider"><img src=img/0.gif width=1 height=1 class=borderno></td></tr>
			<?
			$i++;		
		}
		s_update("views=views+1","","id=$id")
		?>
		</table>
		<div class=menu_in align=right>
		[ <a href="<?=SPAGE?>?main=<?=$main?>&add=1&id=<?=s_select("top","","id=$id")?>">Начать новую тему</a> ] 
		[ <a href="<?=SPAGE?>?main=<?=$main?>&add=2&id=<?=$id?>">Добавить сообщение</a> ]
		[ <a href="<?=SPAGE?>?main=<?=$main?>&id=<?=$level?>">Вернутся к списку тем</a> ]</div><?
}}

}
#===========================================================
# В режиме добавления сообщений и тем
# Выводим форму

if (!empty($add)){
$res=row_select("name,content,ip",$main."_bun","ip='".$_SERVER['REMOTE_ADDR']."'","name");
if ($res->nr()!=0){
	$r=$res->ga();
	echo "<p class=red>Извините, но вы не можете создавать темы и писать сообщения на нашем форуме. 
			Нам пришлось забанить ваш IP адрес.</p>
			<p>Забанен IP: <b>".$r["ip"]."</b></p>
			<p>Забанен автор: <b>".$r["name"]."</b></p>
			<p>По причине: <b>".$r["content"]."</b></p>
			<p>Если произошла ошибка, или вы не согласны 
			с нашим решением напишите письмо администратору сайта 
			".email_echo(param("site_email"))." с просьбой разобраться в ситуации.</p>
			<p><a href='".SPAGE."?main=$main&id=$id'>Вернуться назад к просмотру форума</a></p>";
}
else {
?>
	<br><table width="80%" border="0" cellspacing="0" cellpadding="0" class=table_forum_form>
	<form name="post" method="post" action="<?=SPAGE?>?main=<?=$main?>&id=<?=$id?>" onSubmit="
	<?if ($level==0){?>
	if(document.post.topic.value=='') {alert('Заполните поле Название темы');return false;}
	<?}?>
	if(document.post.fio.value=='') {alert('Заполните поле ФИО');return false;}
	if(document.post.email.value!='' && !check_email(document.post.email.value)) {alert('Введите в поле E-mail корректный адрес, либо не вводите его совсем');return false;}
	if(document.post.message.value=='') {alert('Заполните поле Сообщение');return false;}
	">
	<?if ($level==0){?>
	<tr><td align=right nowrap>Название темы:&nbsp;&nbsp;</td><td><input type="text" name="topic" style="width:97%" maxlength="100" value="<?if (!empty($topic)) echo $topic;?>"></td></tr>
	<?}
	if (!empty($_SESSION['guest_name'])) {
		$name=$_SESSION['guest_fio'];
		$email=$_SESSION['guest_email'];
		$disabled="readonly";
	}
	elseif (!empty($_COOKIE['forum_name'])) {
		$name=$_COOKIE['forum_name'];
		@$email=$_COOKIE['forum_email'];
		$disabled="";	
	}
	else {
		$name="";
		$email="";	
		$disabled="";	
	}
	
	
	?>
	<tr><td align=right width="1%" nowrap>Ф.И.О.:&nbsp;&nbsp;</td><td width=99%><input type="text" name="fio"  style="width:97%" maxlength="41" value="<?=$name?>" <?=$disabled?>></td></tr>
	<tr><td align=right nowrap>E-mail:&nbsp;&nbsp;</td><td><input type="text" name="email"  style="width:97%" maxlength="41" value="<?=@$email?>" <?=$disabled?>></td></tr>
	<tr><td align=right valign=top nowrap>Сообщение:&nbsp;&nbsp;
	<div class="forum_icons"><table width="100" border="0" cellspacing="0" cellpadding="5" class=tableno>
	<tr><?
	$res=row_select("distinct img",$main."_smiles");$i=0;
	while($r=$res->ga()){
		echo "<td><a href=\"javascript:emoticon('".s_select("content",$main."_smiles","img='".$r["img"]."'")."')\"><img src=".SITE_ADMIN_DIR."/img/forum/smiles/".$r["img"]." class=borderno alt=\"".s_select("content",$main."_smiles","img='".$r["img"]."'")."\" width=15 height=15></a></td>";
		if (($i%4)==3) echo "</tr><tr>";
		$i++;
		
		}
	?></tr></table></div>
	</td>
	<td><textarea name="message" style="width:97%" rows=15 onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" wrap="virtual" ><?
	if (isset($cit)) echo "[quote][b]".s_select("author","","id=$cit").":[/b] ".s_select("name","","id=$cit")."[/quote]
";
	?></textarea></td></tr>
	<tr><td>&nbsp;</td><td height="25">
	<input name="sbm" type="hidden" value="">

	<input type="submit"  style="1width:99%" name="Button" value="<?if ($level==0) echo "Создать"; else echo "Добавить";?>"></td></tr>
	</form></table>
<?
}}
?>



