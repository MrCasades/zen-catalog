<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	 <div class = "post" align = "center">
	 <?php htmlecho($errLog);?>
		<form action = "?<?php htmlecho ($action); ?>" method = "post">
			<label for = "email">Введите E-mail, который Вы использовали для регистрации: <input type = "text" name = "email" id = "email" value = "<?php htmlecho($email);?>"></label>	
			<input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</form>	
	 </div>	
	</div>
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>