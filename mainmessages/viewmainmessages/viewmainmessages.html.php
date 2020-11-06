<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	
		<?php if (!isset($mainmessages))
		 {
			 $noPosts = '<p align = "center">Сообщения отсутствуют.</p>';
			 echo $noPosts;
			 $mainmessages = null;
		 }
		 
		 else
			 
		 foreach ($mainmessages as $mainmessage): ?> 
			<div>
				
				<?php 
					/*Переменные для вывода Ваших сообщений и ответов на них*/
					if (isset($mainmessage['idfrom']))
					{
						$messDate = $mainmessage['mainmessagedate'];
						$idAuthor = $mainmessage['idfrom'];
						$authorName = $mainmessage['authorfrom'];
						$messText = $mainmessage['mainmessage'];
					}
					
					elseif (isset($mainmessage['idto']))
					{
						$messDate = $mainmessage['mainmessagedate'];
						$idAuthor = $mainmessage['idto'];
						$authorName = $mainmessage['authorto'];
						$messText = $mainmessage['mainmessage'];
					}
				
					/*Переменные для стилей заголовков*/
					if ($mainmessage['idto'] == $selectedAuthor)
					{
						$stylePost = 'posttitle';
						$textColor = 'white';
						$typeMessage = '<strong>Вам написали </strong>';
						$deleteForm = '';
					}

					elseif ($mainmessage['idfrom'] == $selectedAuthor)	
					{
						$stylePost = 'posttitle_from';
						$textColor = '#1E90FF';
						$typeMessage = '<strong>Вы ответили </strong>';	
						$deleteForm = '<div align = "right"><form action = "..\..\mainmessages\addupdmainmessage\ " method = "post">
										<input type = "hidden" name = "idmessage" value = "'.$mainmessage['idmess'].'">
						<input type = "submit" name = "action" value = "X" class="btn btn-danger btn-sm">
		      		</form></div>';
					}
				?>
						
				<div class = "post">
				  <div class = "<?php echo $stylePost;?>">
				    <?php echo ($typeMessage.$messDate. ' | Автор: <a href="../../account/?id='.$idAuthor.'" style="color: '.$textColor.'" >'.$authorName).'</a>';?>
				  </div>
					<?php echo $deleteForm;?>
				   <div class = "newstext">
					<?php if ($mainmessage['imghead'] == '')
					{
						$img = '';//если картинка в заголовке отсутствует
						echo $img;
					}
						else 
					{
						$img = '<p align="center"><img width = "60%" height = "40%" src="../../formessages/'.$mainmessage['imghead'].'"></p>';//если картинка присутствует
					}?>	
					<p><?php echo $img;?></p>
				    <p align = "center"><?php echomarkdown ($messText); ?></p>
				   </div>	
				 </div>	
			</div>		
		 <?php endforeach; ?>	
		
		<p><a name="bottom"></a></p>
		
  <form action = "?<?php htmlecho ($action); ?>" method = "post" enctype="multipart/form-data" autocomplete="on">
	<table>
	 <div>
	  <tr>
		<td><label for = "author"> Автор:</label></td>
		<td>
		 <?php echo $authorPost;?>
		</td>
	  </tr>
	 </div>	 
	 <div>
	  <tr>
		<td><label for = "upload">Загрузите файл изображения</label><input type = "file" name = "upload" id = "upload"></td>
		<td><input type = "hidden" name = "action" value = "upload"></td>
	  </tr>		 
	</div>
	</table>	
	 <div>
		<label for = "promotion">Введите текст сообщения</label><br>
		<textarea class = "descr" id = "text" name = "text" data-provide="markdown" rows="10"><?php htmlecho($text);?></textarea>	
	 </div>
	  <hr/>
	  <div>
		<input type = "hidden" name = "idto" value = "<?php echo $toDialog; ?>">
		<input type = "submit" name = "addform" value = "<?php htmlecho($button); ?>" class="btn btn-primary btn-sm">
	  </div>	
	</form>		
		
	</div>	
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>