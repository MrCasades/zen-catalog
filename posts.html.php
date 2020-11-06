<?php

/*Загрузка функций в шаблон*/
include_once __DIR__ . '/includes/func.inc.php';

/*Загрузка header*/
include_once __DIR__ . '/header.inc.php';

/*Загрузка adminnews*/
include_once __DIR__ . '/admin/adminnews.inc.html.php';

?>

 <div class = "maincont_for_view">
		<div align = "center" ><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
		<script src="//yastatic.net/share2/share.js"></script>
		<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></div>

   <div class = "titles_main_padge"><h4 align = "center">Последние добавленные каналы</h4></div>
	<div class="<?php echo $columns_1;?>">
		<?php if (empty ($promotions))
		 {
			 echo '<p align = "center">Каналы отсутствуют</p>';
		 }
		 
		 else
			 
		 foreach ($promotions as $promotion): ?> 
		<div class="columns__panel">
         <div class="columns__panel-content">
			<div class = "post_for_columns" style = "background: url(./images/<?php echo $promotion['imghead']; ?>); background-size: cover; ">
				<div  class = "posttitle"><strong><?php echo date("Y.m.d H:i", strtotime($promotion['promotiondate'])); ?> </strong></div>
				<a href="./viewpromotion/?id=<?php htmlecho ($promotion['id']); ?>" rel = "nofollow">.</a>
			</div>
			<strong><a href="./viewpromotion/?id=<?php htmlecho ($promotion['id']); ?>"><?php htmlecho ((implode(' ', array_slice(explode(' ', strip_tags($promotion['promotiontitle'])), 0, 7)))); ?>...</a></strong> 
		  </div>	 
		</div>	 
		 <?php endforeach; ?>
	</div>	
	<div class="for_allposts_link"><p align = "center"><a href="./viewallpromotion/" style = "color: white">Все каналы</a></p></div>
	  
	 	<div>
		 <table cellspacing="5">
		 <tr>
		  <td valign="top"><label for = "meta">Теги:</label></td>
			   <?php if (empty ($metas_3))
		 {
			 echo '<p align = "center">Нет тегов</p>';
		 }
		 
		 else
		
		foreach ($metas_3 as $meta): ?> 	  
				<td><div>	 
					<strong><a href="./viewmetapromotion/?metaid=<?php echo $meta['id']; ?>"> <strong><?php echomarkdown ($meta['meta']); ?></strong></a></strong>	 
				</div></td> 	
				<?php endforeach; ?>
		  </tr>
		 </table>
		</div>
	 	 
	 <h4 align = "center">Топ-5 каналов</h4>
	 <div class="<?php echo $columns_2;?>">
	 <?php if (empty($promotionsTOP))
		 {
			 echo '<p align = "center">Нет статей</p>';
		 }
		 
		 else
		
		foreach ($promotionsTOP as $promotionTOP): ?> 
	  <div class="columns__panel">
       <div class="columns__panel-content">	   
		<div class = "fortop5">  
          <img width = "8%" height = "8%" src="./view.jpg" alt="Число просмотров материала" title="Просмотры"> <?php htmlecho ($promotionTOP['viewcount']); ?> 
		  <img width = "5%" height = "5%" src="./like.jpg" alt="Оценка материала" title="Оценка"> <?php htmlecho ($promotionTOP['averagenumber']); ?>			
			<a href="./viewpromotion/?id=<?php echo $promotionTOP['id']; ?>"> <?php echomarkdown ($promotionTOP['promotiontitle']); ?></a>
		</div>
	  </div>
	</div>
	 <?php endforeach; ?>
	 <div class="columns__panel">
       <div class="columns__panel-content">	   
		<div class = "fortop5">  
          <p align = "center"><a href="./viewfullpromotiontop/">Вывести весь топ</a></p>
		</div>
	  </div>
	 </div>	 
	</div> 	
	
    <div class = "titles_main_padge"><h4 align = "center">Пользователи рекомендуют:</h4></div>	

	<div class="<?php echo $columns_3;?>">
		<?php if (empty($lastRecommPromotions))
		 {
			 echo '<p align = "center">Рекомендации отсутствуют</p>';
		 }
		 
		 else
			 
		 foreach ($lastRecommPromotions as $lastRecommPromotion): ?> 
		<div class="columns__panel">
         <div class="columns__panel-content">
			<div class = "post_for_columns" style = "background: url(./images/<?php echo $lastRecommPromotion['imghead']; ?>); background-size: cover; ">
				<div  class = "posttitle"><strong><?php echo date("Y.m.d H:i", strtotime($lastRecommPromotion['promotiondate'])); ?> </strong></div>
				<a href="./viewpromotion/?id=<?php htmlecho ($lastRecommPromotion['id']); ?>" rel = "nofollow">.</a>
			</div>
			<strong><a href="./viewpromotion/?id=<?php htmlecho ($lastRecommPromotion['id']); ?>"><?php htmlecho ((implode(' ', array_slice(explode(' ', strip_tags($lastRecommPromotion['promotiontitle'])), 0, 7)))); ?>...</a></strong> 
		  </div>	 
		</div>	 
		 <?php endforeach; ?>
	</div>	
	<div class="for_allposts_link"><p align = "center"><a href="./viewallrecommpromotion/" style = "color: white">Все рекомендации</a></p></div>
	 		 	
		<div class="for_mainpage_direct">
		 <!--Реклама тут-->
	 	</div>
		<div class = "titles_main_padge"><h4 align = "center">Последние статьи</h4></div>
		
		<div>
		
		<?php if (empty ($posts))
		 {
			 echo '<p align = "center">Статьи отсутствуют</p>';
		 }
		 
		 else
			 
		 foreach ($posts as $post): ?> 
		  
			<div>
				
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ($post['postdate']. ' | Автор: <a href="./account/?id='.$post['idauthor'].'" style="color: white" >'.$post['authorname']).'</a>';?>
				  </div>
				  	
				   <div class = "newstext">
				    <h3 align = "center"><?php htmlecho ($post['posttitle']); ?></h3>
				     <div class = "newsimg">
					   <?php if ($post['imghead'] == '')
						{
							$img = '';//если картинка в заголовке отсутствует
							echo $img;
						}
						 else 
						{
							$img = '<img width = "90%" height = "90%" src="./images/'.$post['imghead'].'"'. ' alt="'.$post['imgalt'].'"'.'>';//если картинка присутствует
						}?>
					  <p><?php echo $img;?></p>
				     </div>
					<p align = "justify"><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($post['text'])), 0, 50))); ?> [...]</p>
					<a href="./viewpost/?id=<?php htmlecho ($post['id']); ?>" class="btn btn-primary">Далее</a>
				   </div>	
				 </div>
			</div>			
		 <?php endforeach; ?>
		 <div class="for_allposts_link"><p align = "center"><a href="./viewallposts/" style="color: white">Все статьи</a></p></div>
		
		<div>
		 <table cellspacing="5">
		 <tr>
		  <td valign="top"><label for = "meta">Теги:</label></td>
			   <?php if (empty ($metas_2))
		 {
			 echo '<p align = "center">Нет тегов</p>';
		 }
		 
		 else
		
		foreach ($metas_2 as $meta): ?> 	  
				<td><div>	 
					<strong><a href="./viewmetapost/?metaid=<?php echo $meta['id']; ?>"> <strong><?php echomarkdown ($meta['meta']); ?></strong></a></strong>	 
				</div></td> 	
				<?php endforeach; ?>
		  </tr>
		 </table>
		</div>	
						 					
		</div>
		<div align = "justify" class = "post">
		  <div class = "posttitle"><h4>О проекте FULL-ZEN</h4></div>
			<p>Приветствуем Вас на проекте <strong>FULL-ZEN</strong>! Мы инновационный каталог каналов системы <strong>Яндекс.Дзен</strong>. Это прекрасная блогерская платформа, которая даёт неплохой шанс заявить о себе. Тем не менее, довольно сложно раскрутить свой дзен-канал.</p>
			
			<p>Наш проект поможет Вам приблизиться к успеху в этом нелёгком деле. Добавляйте свой дзен-канал в наш каталог, соханяйте интересные каналы прочих пользователей в избранном, чтобы не терять и оценивайте их. Предлагайте свою взаимность другим авторам в продвижении. Также проект <strong>FULL-ZEN</strong> публикует интересные статьи по раскрутке каналов Яндекс.Дзен и прочие сведения об этой платформе. Удачи в продвижении!</p>  
		</div>

<?php 
/*Загрузка footer*/
include_once __DIR__ . '/footer.inc.php';?>

