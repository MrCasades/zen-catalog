<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont"> 
		
		<p align="center"><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
		<script src="//yastatic.net/share2/share.js"></script>
		<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></p>	
	
		<div>
		<p><?php echo $updAndDelAvatar; ?></p>
		<p><?php echo $mainMessagesForm; ?></p>
		
		 <?php foreach ($authors as $author): ?> 
		   <p><img width = "150 px" height = "150 px" src="../avatars/<?php echo $author['avatar'];?>" alt="<?php echo $author['authorname'];?>"></p>
		   <p><?php if (($authorRole == 'Автор') || ($authorRole == 'Администратор'))//если пользователю присвоен определённый статус, то выводятся его ранг
				
				{
					echo ('<strong> Авторский ранг: '.$rangView.' </strong>'.$score.
						  '<p><strong> Рейтинг: '.$rating.'</strong></p>'.
						  '<p>'.$payForm.'</p>'.$payFormIn);
					echo $prices;
					echo $openTable;
					echo $paysystemName;
					echo $ewallet;
					echo $updEwalletDate;
					echo $closeTable;
				}?></p>
			
			<p><?php if ($authorRole == 'Рекламодатель')//если пользователю присвоен определённый статус, то выводятся его ранг
				
				{
					echo ($score.$payFormIn);
				}?></p>
			
			<p><?php echo $addRole; ?></p>
			<p><?php echo $addBonus; ?></p>
			<div>
				    <strong>Сайт:</strong> <?php if ($author['www'] != '')//если автор приложил ссылку
						{
							$linkAuthor = '<a href="//'.$author['www'].'" rel = "nofollow">'.$author['www'].'</a>';
							echo $linkAuthor;
						}?> 
				<br/>
				<br/>
				<p><h4>Дополнительная информация:</h4></p>
				<p align="justify"><?php echomarkdown ($author['accountinfo']);?></p>	
			</div>			
		 <?php endforeach; ?>
		 <p><?php echo $changePass; ?></p>
		 <p><?php echo $updAccountInfo; ?></p>
		 	
		 <div class = "titles_main_padge"><h4 align = "center">Каналы автора</h4></div>
		<div>
		 <?php 
		 if ((empty ($promotions)) && ($idAuthor == authorID($_SESSION['email'], $_SESSION['password'])))
		 {
			 echo '<p align = "center"><strong>Не добавлено ни одного канала! <a href = "../admin/addupdpromotion/?add">Добавить канал в каталог?</a></strong></p>';
		 }
		 
		 elseif (empty ($promotions))
		 {
			 echo '<p align = "center">Не добавлено ни одного канала!</p>';
		 }
			
		 else
			 
		 foreach ($promotions as $promotion): ?> 
		  
			<div>
				
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ($promotion['promotiondate']. ' | Автор: <a href="../account/?id='.$promotion['idauthor'].'" style="color: white" >'.$promotion['authorname']).'</a>';?>
					<p>Рубрика: <a href="../viewcategory/?id=<?php echo $promotion['categoryid']; ?>" style="color: white"><?php echo $promotion['categoryname'];?></a></p>
				  </div>
				  <div class = "newstext">
					<h3 align = "center"><?php htmlecho ($promotion['promotiontitle']); ?></h3>
					 <div class = "newsimg">
					   <?php if ($promotion['imghead'] == '')
						{
							$img = '';//если картинка в заголовке отсутствует
							echo $img;
						}
						 else 
						{
							$img = '<img width = "90%" height = "90%" src="../images/'.$promotion['imghead'].'"'. ' alt="'.$promotion['imgalt'].'"'.'>';//если картинка присутствует
						}?>
					<p><?php echo $img;?></p>
				   </div>
					<p><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($promotion['text'])), 0, 50))); ?> [...]</p>
					<p><a href="../viewpromotion/?id=<?php htmlecho ($promotion['id']); ?>" class="btn btn-primary">Далее</a></p>
				  </div>
				 </div> 
			</div>			
		 <?php endforeach; ?>	
	</div>		
	


<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>