<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont">  
		<div class = "post" align="center">
		 <?php echo $error; ?> 
		</div>
		<div align="center"><a href="#" onclick="history.back();" class="btn btn-primary btn-sm">Назад</a></div>
	</div>	

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>