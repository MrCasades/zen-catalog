<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	 <div class = "post" align="center">
	  <p><?php htmlecho($error); ?> </p>
	  <a href="<?php echo '//'.MAIN_URL;?>" class="btn btn-primary btn-sm">Главная страница</a>
	 </div>
	</div>		
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>

