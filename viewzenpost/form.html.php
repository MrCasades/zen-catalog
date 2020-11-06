<?php 

/*Загрузка функций для формы входа*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/access.inc.php';

/*Загрузка функций в шаблон*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/func.inc.php';

/*Загрузка header*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/header.inc.php';?>

	<div class = "maincont">
	<form action = "?<?php htmlecho($action); ?> " method = "post">
	 <div>
		<label for = "comment">Введите текст комментария</label><br>
		<textarea class = "descr" id = "comment" name = "comment" rows = "3" cols = "40"><?php htmlecho($text);?></textarea>	
	 </div>
	  <div>
		<input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		<input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary">
	  </div>	  
	</form>	
	</div>
	
<?php 
/*Загрузка footer*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/footer.inc.php';?>