<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	 <div class = "post" align = "center">
	 <?php htmlecho($errLog);?>
		<form action = "?<?php htmlecho ($action); ?>" method = "post">
			<label for = "email">Введите код подтверждения из письма: <input type = "text" name = "rekey" id = "rekey" value = "<?php htmlecho($reKey);?>"></label>
			<input type = "hidden" name = "tikey" id = "tikey" value = "<?php htmlecho($tiKey);?>">
			<input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</form>	
	 </div>	
	</div>
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>