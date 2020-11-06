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
		 <?php 
		 if (empty ($favourites)) 
				{
					echo '<p align = "center">Материалы отсутствуют</p>';
					$favourites = '';
				}
		 	
			else
		 
		 foreach ($favourites as $favourite): ?> 
		  
			<div>
				
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ($favourite['date']. ' | Автор: <a href="../../account/?id='.$favourite['idauthorpost'].'" style="color: white" >'.$favourite['authorname']).'</a>';?>
					<p>Рубрика: <a href="../../viewcategory/?id=<?php echo $favourite['categoryid']; ?>" style="color: white"><?php echo $favourite['categoryname'];?></a></p> 
				  </div>
				  	 
				   <div class = "newstext">
				    <h3 align = "center"><?php htmlecho ($favourite['title']); ?></h3>
				    <div class = "newsimg">
					   <?php if ($favourite['imghead'] == '')
						{
							$img = '';//если картинка в заголовке отсутствует
							echo $img;
						}
						 else 
						{
							$img = '<img width = "90%" height = "90%" src="../../images/'.$favourite['imghead'].'"'. ' alt="'.$favourite['imgalt'].'"'.'>';//если картинка присутствует
						}?>
					  <p><?php echo $img;?></p>
				     </div>
					<p align = "justify"><?php echomarkdown ($favourite['post']); ?> [...]</p>
					<?php echo $favourite['url']; ?> 
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
					echo "<a href='index.php?page=$i&id=$idAuthor' class='btn btn-info'>$i</a> ";
				} 
				else 
				{
					echo "<a href='index.php?page=$i&id=$idAuthor' class='btn btn-primary btn-sm'>$i</a> ";
				}
			}?>
		</div>	
		</div>
	</div>		

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>
