<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "messenger"> 
		<div align = "center"><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
		<script src="//yastatic.net/share2/share.js"></script>
		<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></div>
	  <div class = "post">
		<h5 align = "center">Непрочитанные сообщения</h5>	 
		 <?php 
		  //var_dump($unreadMessages);
		  
		 if (!isset($unreadMessages))
		 {
			 $noNews = '<p align = "center">Нет непрочитанных сообщений</p>';
			 echo $noNews;
			 $unreadMessages = null;
			 $unrMessages = '';
		 }
			
		 else
			 
		 foreach ($unreadMessages as $unreadMessage): ?> 
		  
		  <?php 
		  			if ($unreadMessage['idfrom'] == $selectedAuthor)
					{
						
							$idAuthorUnr = '';
							$authorNameUnr = '';
							$avatarUnr = '';
							$dialogLinkUnr = '';
							$messageDateUnr = '';
							$unrMessages = '<hr/><p align = "center">Нет непрочитанных сообщений</p><hr/>';
					}
		  
		  
					elseif ($unreadMessage['idfrom'] != $selectedAuthor)
					{
						
							$idAuthorUnr = $unreadMessage['idfrom'];
							$authorNameUnr = $unreadMessage['authorfrom'];
							$avatarUnr = '<img width = "40 px" height = "40 px" src="../avatars/'.$unreadMessage['avafr'].'" alt="'.$authorNameUnr.'">';
							$dialogLinkUnr = '<a href="../mainmessages/viewmainmessages/?id='.$idAuthorUnr.'#bottom">'.$authorNameUnr.'</a>';
							$messageDateUnr = $unreadMessage['mainmessagedate'];
							$unrMessages = '';
					}
				?>
		  <table align = "center">	
			<td width = "150 px"><?php echo $messageDateUnr;?></td>
		 	<td width = "70 px"><?php echo $avatarUnr;?></td>
			<td width = "70 px"><?php echo $dialogLinkUnr;?></td>		
		  </table>	  
		 <?php endforeach; ?> 
		 <?php echo $unrMessages;?>
		  
	<h5 align = "center">Список всех диалогов</h5>	 
		  
		 <?php 
		  //var_dump($dialogs);
		  
		 if (!isset($dialogs))
		 {
			 $noNews = 'Список диалогов пуст';
			 echo $noNews;
			 $dialogs = null;
		 }
			
		 else
			 
		 foreach ($dialogs as $dialog): ?> 
		  
		  <?php 
		  
		  
					if (($dialog['idfrom'] != $selectedAuthor) && ($dialog['firstmessage'] == "YES"))
					{
						
							$idAuthor = $dialog['idfrom'];
							$authorName = $dialog['authorfrom'];
							$avatar = '<img width = "40 px" height = "40 px" src="../avatars/'.$dialog['avafr'].'" alt="'.$authorName.'">';
							$dialogLink = '<a href="../mainmessages/viewmainmessages/?id='.$idAuthor.'#bottom">'.$authorName.'</a>';
					}
					
					elseif (($dialog['idto'] != $selectedAuthor) && ($dialog['firstmessage'] == "YES"))
					{
						
							$idAuthor = $dialog['idto'];
							$authorName = $dialog['authorto'];
							$avatar = '<img width = "40 px" height = "40 px" src="../avatars/'.$dialog['avato'].'" alt="'.$authorName.'">';
							$dialogLink = '<a href="../mainmessages/viewmainmessages/?id='.$idAuthor.'#bottom">'.$authorName.'</a>';
					}
		  
				   else
					{
						$idAuthor = '';
							$authorName = '';
							$avatar = '';
							$dialogLink = '';
				    }
				?>
		  <table>	  
		 	<td width = "70 px"><?php echo $avatar;?></td>
			<td width = "70 px"><?php echo $dialogLink;?></td>
		  </table>	  
		 <?php endforeach; ?>
		 
		</div>   
		
		<p><a name="bottom"></a></p>
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>