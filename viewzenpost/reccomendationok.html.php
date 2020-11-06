<?php 
/*Загрузка функций в шаблон*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/func.inc.php';

/*Загрузка header*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/header.inc.php';?>
	
	<div class = "maincont"> 
	 <div class = "post" align="center">
		<p>Рекомендовать статью "<?php htmlecho($posttitle); ?>"? С Вашего счёта будет списано <?php htmlecho($recommendationPrice); ?> баллов.</p>
		<p>Данный материал навсегда останется в разделе рекомендованных и высветится на главной странице. Вы в любой момент можете поднять его на 1-ю позицию снова.</p>
		<p><form action = "?<?php htmlecho($action); ?>" method = "post">
		  <input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		  <input type = "submit" name = "delete" class="btn btn-primary btn-sm" value = "<?php htmlecho($button); ?>">
		</form></p>
		
		<a href="#" onclick="history.back();" class="btn btn-primary btn-sm">Назад</a>
	 </div>	 
	</div>	
<?php 
/*Загрузка footer*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/footer.inc.php';?>