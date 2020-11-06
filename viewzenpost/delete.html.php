<?php 
/*Загрузка функций в шаблон*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/func.inc.php';

/*Загрузка header*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/header.inc.php';?>
	
	<div class = "maincont"> 
	 <div class = "post" align="center">
		<p>Удалить материал "<?php htmlecho($posttitle); ?>"?</p>
		<p><form action = "?<?php htmlecho($action); ?> " method = "post">
		  <input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		  <input type = "submit" name = "delete" class="btn btn-primary btn-sm" value = "<?php htmlecho($button); ?>">
		</form></p>
		
		<a href="#" onclick="history.back();" class="btn btn-primary btn-sm">Назад</a>
	 </div>	 
	</div>	
<?php 
/*Загрузка footer*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/footer.inc.php';?>