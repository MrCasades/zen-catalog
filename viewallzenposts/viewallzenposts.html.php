<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont_for_view"> 
			<div align = "center"><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
			<script src="//yastatic.net/share2/share.js"></script>
			<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></div>
		
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
				    <?php echo ($post['postdate']. ' | Автор: <a href="../account/?id='.$post['idauthor'].'" style="color: white" >'.$post['authorname']).'</a>';?>
					<p>Рубрика: <a href="../viewcategory/?id=<?php echo $post['categoryid']; ?>" style="color: white"><?php echo $post['categoryname'];?></a></p>
				  </div>
				  	 
				   <div class = "newstext">
				    <h5 align = "center"><?php htmlecho ($post['posttitle']); ?></h5>
					   <div class = "newsimg">
					   <?php if ($post['imghead'] == '')
						{
							$img = '';//если картинка в заголовке отсутствует
							echo $img;
						}
						 else 
						{
							$img = '<img width = "90%" height = "90%" src="../images/'.$post['imghead'].'"'. ' alt="'.$post['imgalt'].'"'.'>';//если картинка присутствует
						}?>
					  <p><?php echo $img;?></p>
				     </div>
					<p align = "justify"><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($post['text'])), 0, 50))); ?> [...]</p>
					<a href="../viewzenpost/?id=<?php htmlecho ($post['id']); ?>" class="btn btn-primary">Далее</a>
				   </div>	
				 </div>
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
					echo "<a href='index.php?page=$i' class='btn btn-info'>$i</a> ";
				} 
				else 
				{
					echo "<a href='index.php?page=$i' class='btn btn-primary btn-sm'>$i</a> ";
				}
			}?>
		 </div>	
		</div>
	</div>		

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>
