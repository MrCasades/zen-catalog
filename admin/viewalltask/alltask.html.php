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
		<?php if (empty ($tasks))
		 {
			 echo '<h4 align = "center">Задания пока отсутствуют!</h4>';
		 }
		 
		 else
			 
		 foreach ($tasks as $task): ?> 
		  
			<div>
				
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ('Дата выдачи: '.$task['taskdate']. ' | Задание выдал: <a href="../../account/?id='.$task['idauthor'].'" style="color: white" >'.$task['authorname']).'</a>';?>
					<p>Тип: <?php echo $task['tasktypename'];?> | Для ранга не ниже: <?php echo $task['rangname'];?></p>
				  </div>
				   <div class = "newstext">
				    <h5 align = "center"><?php htmlecho ($task['tasktitle']); ?></h5>
					<p align = "justify"><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($task['text'])), 0, 50))); ?> [...]</p>
					<a href="../../admin/viewalltask/viewtask/?id=<?php htmlecho ($task['id']); ?>" class="btn btn-primary">Далее</a>
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
		<p><a name="bottom"></a></p>
	</div>		

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>

