<?php 

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont_for_view">
		
		<div class = "post">
		 <?php foreach ($tasks as $task): ?> 	  
			<div  align="justify">
			
				<div class = "posttitle">
				  <?php echo ('Дата выдачи: '.$task['taskdate']. ' | Задание выдал: <a href="../../../account/?id='.$task['idauthor'].'" style="color: white" >'.$task['authorname']).'</a>';?>
					<p>Тип: <?php echo $task['tasktypename'];?> | Для ранга не ниже: <?php echo $task['rangname'];?></p>
				</div>	
					<p><?php echomarkdown ($task['text']); ?></p>
					<p><?php echo $delAndUpd; ?></p>
					<p><?php echo $changeTaskStatus; ?></p>
			</div>			
		 <?php endforeach; ?>
		</div>	
	  </div>				

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>