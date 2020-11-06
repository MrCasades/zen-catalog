<?php 

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<style>
		html {background-color: #ffffff;}
	</style>
	
	<link href="<?php echo '//'.MAIN_URL.'/styles.css';?>" rel= "stylesheet" type="text/css">
	
    <title><?php echo $title; ?> </title>
	
</head>
<body>
	
		<div class = "maincont_for_window">
		<h1><?php htmlecho ($headMain); ?> </h1>
		<div class = "post">
		 <?php foreach ($tasks as $task): ?> 	  
			<div  align="justify">
			
				<div class = "posttitle">
				  <?php echo ('Дата выдачи: '.$task['taskdate']. ' | Задание выдал: <a href="../../../account/?id='.$task['idauthor'].'" style="color: white" >'.$task['authorname']).'</a>';?>
					<p>Тип: <?php echo $task['tasktypename'];?> | Для ранга не ниже: <?php echo $task['rangname'];?></p>
				</div>	
					<p><?php echomarkdown ($task['text']); ?></p>
			</div>			
		 <?php endforeach; ?>
		</div>	
	  </div>
</body>
</html>