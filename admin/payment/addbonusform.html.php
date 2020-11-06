<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont">
	<div class = "post">
	
	<p align = "center"><strong><?php htmlecho($errorForm); ?></strong></p>
	
	<form action = "?<?php htmlecho($action); ?> " method = "post">
	<table>	
	<div>
	  <tr>
		<td><label for = "bonus">Размер бонуса (множитель): </label></td>
		<td><input type = "bonus" name = "bonus" id = "bonus" value = "<?php htmlecho($bonus);?>"></td>
	  </tr>	
	</div>
	<div>
	  <tr>
		<td><label for = "score">Премия: </label></td>
		<td><input type = "score" name = "score" id = "score" value = "<?php htmlecho($score);?>"></td>
	  </tr>	
	</div>
	</table>
	  <div>
		<input type = "hidden" name = "id" value = "<?php htmlecho($idauthor); ?>">
		<input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary btn-sm">
	  </div>
     	  
	</form>	
	</div>
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>	