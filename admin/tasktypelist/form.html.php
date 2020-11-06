<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont">
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
		<div>
			<label for = "tasktypename">Название тематики: <input type = "text" name = "tasktypename" id = "tasktypename" value = "<?php htmlecho($tasktypename);?>"</label>	
		</div> 
		<div>
			<input type = "hidden" name = "idtasktype" value = "<?php htmlecho($idtasktype);?>">
			<input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>	
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>

