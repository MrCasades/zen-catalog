<?php 

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	<form action = "?<?php htmlecho($action); ?> " method = "post">
	 <div>
		<label for = "comment">Введите текст комментария</label><br>
		<input type = "hidden" name = "idarticle" value = "<?php htmlecho($idArticle); ?>">
		<textarea class = "descr" id = "comment" name = "comment" data-provide="markdown" rows="10"><?php htmlecho($text);?></textarea>	
	 </div>
	 <hr/>
	  <div>
		<input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		<input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary">
	  </div>	  
	</form>	
	<p><a name="bottom"></a></p>	
	</div>
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>