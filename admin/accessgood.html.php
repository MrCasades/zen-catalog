<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	 <div class = "post">
	  <p align="center"><?php htmlecho($loggood); ?> </p>
	  <div align="center"><a href="#" onclick="history.go(-2);" class="btn btn-primary btn-sm">Назад</a></div>
	 </div>
	</div>		
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>