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
		<h3 align = "center">Действия предложенные мне</h3>
		<?php if (empty ($tasksForMe))
		 {
			 echo '<h4 align = "center">Вам пока не предложили взаимных действий!</h4>';
		 }
		 
		 else
			 
		 foreach ($tasksForMe as $task): ?> 
		  
			<div>
				
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ('Дата выдачи: '. $task['taskdate']. ' | Предложил канал "'. $task['promotiontitle'].'"'); ?>
				  </div>
				   <div class = "newstext">
					<p align = "justify"><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($task['text'])), 0, 50))); ?> [...]</p>
					<a href="../../admin/viewallauthortask/viewtask/?id=<?php htmlecho ($task['id']); ?>" class="btn btn-primary">Далее</a>
				   </div>	
				 </div>
			</div>			
		 <?php endforeach; ?>
		<p><a name="bottom"></a></p>
			
		<h3 align = "center">Действия предложенные мной</h3>
		<?php if (empty ($myTasks))
		 {
			 echo '<h4 align = "center">Мной пока не было предложено взаимных действий!</h4>';
		 }
		 
		 else
			 
		 foreach ($myTasks as $task): ?> 
		  
			<div>
				
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ('Дата выдачи: '. $task['taskdate']. ' | От моего канала "'. $task['promotiontitle'].'"'); ?>
				  </div>
				   <div class = "newstext">
					<div align = "right">
						<p><form action = "./deltask/" method = "post">
						  <input type = "hidden" name = "id" value = "<?php echo $task['id']?>">
						  <input type = "submit" name = "action" value = "X" class="btn btn-danger btn-sm">
		      		    </form></p>
					</div>
					<p align = "justify"><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($task['text'])), 0, 50))); ?> [...]</p>
					<a href="../../admin/viewallauthortask/viewtask/?id=<?php htmlecho ($task['id']); ?>" class="btn btn-primary">Далее</a>
				   </div>	
				 </div>
			</div>			
		 <?php endforeach; ?>	
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>

