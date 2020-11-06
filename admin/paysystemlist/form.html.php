<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont">
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
		<div>
			<label for = "paysystemname">Название тематики: <input type = "text" name = "paysystemname" id = "paysystemname" value = "<?php htmlecho($paysystemname);?>" 
			</label>	
		</div> 
		<div>
			<input type = "hidden" name = "idpaysystem" value = "<?php htmlecho($idpaysystem);?>">
			<input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>	
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>

