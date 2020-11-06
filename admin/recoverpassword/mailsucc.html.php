<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	 <div class = "post">
	  <p align="center"><?php echo $mailSucc; ?></p>    
	  <div align="center"><a href="<?php echo '//'.MAIN_URL;?>" class="btn btn-danger btn-sm">Главная страница</a> </div>
	 </div>
	</div>			

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>