<?php 

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont_for_view">
		
		<div class = "post">
		 <?php if (empty ($tasks))
		 {
			 echo '<h4 align = "center">Вы выполнили это задание! <a href = "//'.MAIN_URL.'/admin/viewallauthortask/#bottom">Выбирете другое!</a></h4>';
		 }
		 
		 else
			
			foreach ($tasks as $task): ?> 	  
			<div  align="justify">
			
				<div class = "posttitle">
				  <?php echo ('Дата выдачи: '. $task['taskdate']. ' | Предложил канал "'. $task['promotiontitle'].'"');?>
				</div>	
					<p><?php echomarkdown ($task['text']); ?></p>
					<form action = " " metod = "post" id = "ajax_form_conf">
						<input type = "hidden" name = "idtask" id = "idtask" value = "<?php echo $task['id'];?>">
						<input type="submit" value="Выполнить действие" onclick="window.open ('<?php echo $task['promotionwww'];?>');" class="btn btn-primary btn-sm" id = "confirm" name = "confirm"/>
						<strong><p id = "result_form_conf"></p></strong>
					</form>	
			</div>			
		 <?php endforeach; ?>
		</div>	
	  </div>				

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>