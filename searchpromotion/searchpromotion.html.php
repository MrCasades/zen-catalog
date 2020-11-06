<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<p align = "center"><a href="#" onclick="history.back();" class="btn btn-primary btn-sm">Назад</a></p>	
	<div class = "maincont_for_view">
	<?php if (isset ($promotions)):?>
		
		 <?php foreach ($promotions as $promotion): ?>
	
			<div>
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ($promotion['promotiondate']. ' | Автор: <a href="../account/?id='.$promotion['idauthor'].'" style="color: white" >'.$promotion['authorname']).'</a>';?>
					<p>Рубрика: <a href="../viewcategory/?id=<?php echo $promotion['categoryid']; ?>" style="color: white"><?php echo $promotion['categoryname'];?></a>
					  <?php if ($promotion['www'] != '')//если автор приложил ссылку
						{
							$link = '| <a href="//'.$promotion['www'].'" style="color: white" rel = "nofollow">Ссылка на ресурс</a>';
							echo $link;
						}?></p>
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
					<p align = "justify"><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($promotion['text'])), 0, 50))); ?> [...]</p>
					<a href="../viewpromotion/?id=<?php htmlecho ($promotion['id']); ?>" class="btn btn-primary">Далее</a>
				   </div>	
				 </div>
			</div>		
		  	 
		 <?php endforeach; ?>
		
	<?php endif;?>
	</div>
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>
