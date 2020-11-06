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
		 if (empty ($newsIn))
		 {
			 echo '<p align = "center">Новости у автора отсутствуют!</p>';
		 }
		 
		 else
			 
		 foreach ($newsIn as $news): ?> 
		  
			<div>
				
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ($news['newsdate']. ' | Автор: <a href="../../account/?id='.$news['idauthor'].'" style="color: white" >'.$news['authorname']).'</a>';?>
					<p>Рубрика: <a href="../../viewcategory/?id=<?php echo $news['categoryid']; ?>" style="color: white"><?php echo $news['categoryname'];?></a></p>
				  </div>
				  <div class = "newstext">
					<h3 align = "center"><?php htmlecho ($news['newstitle']); ?></h3>
					<div class = "newsimg">
					   <?php if ($news['imghead'] == '')
						{
							$img = '';//если картинка в заголовке отсутствует
							echo $img;
						}
						 else 
						{
							$img = '<img width = "90%" height = "90%" src="../../images/'.$news['imghead'].'"'. ' alt="'.$news['imgalt'].'"'.'>';//если картинка присутствует
						}?>
					<p><?php echo $img;?></p>
				   </div>
					<p><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($news['textnews'])), 0, 50))); ?> [...]</p>
					<p><a href="../../viewnews/?id=<?php htmlecho ($news['id']); ?>" class="btn btn-primary">Далее</a></p>
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
