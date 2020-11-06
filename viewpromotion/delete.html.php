<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func_promotion.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont"> 
	 <div class = "post" align="center">
		<p>Удалить материал "<?php htmlecho($posttitle); ?>"?</p>
		<p><form action = "?<?php htmlecho($action); ?> " method = "post">
		  <input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		  <input type = "hidden" name = "idarticle" value = "<?php htmlecho($idArticle); ?>">
		  <input type = "submit" name = "delete" class="btn btn-primary btn-sm" value = "<?php htmlecho($button); ?>">
		</form></p>
		
		<a href="#" onclick="history.back();" class="btn btn-primary btn-sm">Назад</a>
	 </div>	 
	</div>	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>