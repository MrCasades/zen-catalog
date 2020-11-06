<?php 

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	<form action = "?<?php htmlecho($action); ?> " method = "post" enctype="multipart/form-data">
	 <table>	
	 <div>
	  <tr>
		<td><label for = "upload">Загрузите файл</label></td>
	  </tr>	
	  <tr>	 
		<td><input type = "file" name = "upload" id = "upload"></td>
		<td><input type = "hidden" name = "action" value = "upload"></td>
	  </tr>	
	</div>
	<div>
	  <tr>
		<td><label for = "imgalt">Введите alt-текст для изображения</label></td>
	  </tr>
	  <tr>	
		<td><input type = "imgalt" name = "imgalt" id = "imgalt" value = "<?php htmlecho($imgalt);?>"></td>
	  </tr>	
	</div>
	</table>	
		<label for = "comment">Введите текст записи</label><br>
		<textarea class = "descr" id = "comment" name = "comment" data-provide="markdown" rows="10"><?php htmlecho($text);?></textarea>	
	  <div>
		<input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		<input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary">
	  </div>	  
	</form>	
	</div>
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>