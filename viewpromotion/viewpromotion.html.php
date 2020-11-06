<?php 

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func_promotion.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont_for_view">
		<div align="center">
		 <table>
		 <tr>
		  <td valign="top"><label for = "meta"> Теги:</label></td> 
		  <?php if (empty($metas))
			  {
				 echo '';
		      }
		 
		      else 
		  
			  foreach ($metas as $meta): ?>	  
				<td><div>	 
					<a href="../viewmetapromotion/?metaid=<?php echo $meta['id']; ?>"><?php echomarkdown ($meta['metaname']); ?></a>	 
				</div></td> 	
				<?php endforeach; ?>
		  </tr>
		 </table>
		</div>
		
		<div class = "post"> 	  
			<div  align="justify">
			
				<div class = "posttitle">
				  <?php echo ($date.' | Автор: <a href="../account/?id='.$authorId.'" style="color: white" >'.$nameAuthor).'</a>';?>
				  <p>Рубрика: <a href="../viewcategory/?id=<?php echo $categoryId; ?>" style="color: white"><?php echo $categoryName;?></a></p>
					
				</div>
				  <p><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
				  <script src="//yastatic.net/share2/share.js"></script>
				  <div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></p>
			   <hr>
				  <!--Здесь реклама yandex-->
				  <hr>
				    <p class="like"> 
				     <img width = "5%" height = "5%" src="../viewpromotion/view.jpg" alt="Число просмотров материала" title="Просмотры"> <?php htmlecho ($viewCount); ?> 
				     <img width = "3%" height = "3%" src="../viewpromotion/like.jpg" alt="Оценка материала" title="Оценка"> <?php htmlecho (round($averageNumber, 2, PHP_ROUND_HALF_DOWN)); ?>
					 <img width = "3%" height = "3%" src="./favourite.jpg" alt="Добавили в избранное" title="Добавили в избранное"> <?php htmlecho ($favouritesCount); ?>   
					</p>
					<?php if ($imgHead == '')
					{
						$img = '';//если картинка в заголовке отсутствует
						echo $img;
					}
						else 
					{
						$img = '<p align="center"><img width = "80%" height = "80%" src="../images/'.$imgHead.'"'. ' alt="'.$imgAlt.'"'.'></p>';//если картинка присутствует
					}?>
					
					<p><?php echo $img;?></p>	
					<p><?php echomarkdown ($articleText); ?></p>
					<p><?php echo $votePanel; ?></p>
					<p align="center"><?php echo $addFavourites;?></p>
					<p><?php echo $delAndUpd; ?></p>
					<p><?php echo $premoderation; ?></p>
					<div align="center">
		
						<div class="form-row"><?php echo $link;?><?php echo $recommendation;?>
						     <?php echo $setAction;?></div>
					</div>
			
			</div>			
		</div>
		<div>
		 <h4>Случайные статьи рубрики</h4>
			
		<div class="<?php echo $columns;?>">
		<?php if (empty($similarPosts))
		 {
			 echo '<p align = "center">Новости отсутствуют</p>';
		 }
		 
		 else
			 
		 foreach ($similarPosts as $post_1): ?> 
		<div class="columns__panel">
         <div class="columns__panel-content">
			<div class = "post_for_columns" style = "background: url(../images/<?php echo $post_1['imghead']; ?>); background-size: cover; ">
				<strong><a href="../viewpromotion/?id=<?php htmlecho ($post_1['id']); ?>" rel = "nofollow">.</a></strong> 
			</div>
			<strong><a href="../viewpromotion/?id=<?php htmlecho ($post_1['id']); ?>"><?php htmlecho ((implode(' ', array_slice(explode(' ', strip_tags($post_1['promotiontitle'])), 0, 7)))); ?>...</a></strong> 
		  </div>	 
		</div>	 
		 <?php endforeach; ?>
	   </div>
	  	
		 <h4 align="center">Комментарии (<?php echo $countPosts; ?>)</h4>
			<p align="center"><?php echo $addComment; ?></p>
		<div>
		<?php if (empty ($comments))
				{
					echo '<br/><p align="center">Комментарии отсутствуют!</p>';
				}
				
			  else
				
				foreach ($comments as $comment): ?> 	   		
				<div class = "post">
				 <div class = "posttitle">
				    Дата комментария: <?php echo ($comment['date']. ' | Автор: <a href="../account/?id='.$comment['idauthor'].'" style="color: white" >'.$comment['authorname']).'</a>';?>
				  </div>		
					<p><?php 
				   
						//Вывод панели обновления - удаления комментария!
						 if (($authorName == $comment['authorname']) || (userRole('Администратор')))
						 {
							 $updAnddel = '<form action = "?" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$comment ['id'].'">
									<input type = "hidden" name = "idarticle" value = "'.$comment ['idarticle'].'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Редактировать">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Del">
								</div>
							</form>';		 
						 }	
						 else
						 {
							 $updAnddel = '';
						 }							 
							
						 echo $updAnddel;?></p>
				  <p>
					<table cellpadding = "3 %">
						<td><img width = "90 px" height = "90 px" src="../avatars/<?php echo $comment['avatar'];?>" alt="<?php echo $comment['authorname'];?>"></td>
						<td ><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($comment['text'])), 0, 50))); ?> [...]</td>
					</table>	
				  </p>
				  <p><img width = "3%" height = "3%" src="<?php echo '//'.MAIN_URL;?>/answers.jpg" alt="Ответы на комментарий" title="Количество ответов"> 
					  <strong>[<?php echo $comment['subcommentcount']; ?>]</strong></p>
				  <a href="../viewwallpost/?id=<?php echo $comment['id']; ?>" class="btn btn-primary btn-sm">Открыть</a>
				</div>	  		   
				<?php endforeach; ?> 
				
				<div align = "center">
				 <?php
				 /*Постраничный вывод информации*/
				 for ($i = 1; $i <= $pagesCount; $i++) 
				 {
					 // если текущая старница
					 if($i == $page)
					 {
						 echo "<a href='../viewpost/?id=".$idPromotion."&page=$i' class='btn btn-info'>$i</a> ";
					 } 
					 else 
					 {
						 echo "<a href='../viewpost/?id=".$idPromotion."&page=$i' class='btn btn-primary btn-sm'>$i</a> ";
					 }
				 }?>
				</div>	
		</div>		
	</div>		

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>