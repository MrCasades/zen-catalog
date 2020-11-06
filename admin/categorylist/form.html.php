<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	<br>
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
		<div>
			<label for = "categoryname">Название рубрики: <input type = "text" name = "categoryname" id = "categoryname" value = "<?php htmlecho($categoryname);?>" 
			</label>	
		</div> 
		<div>
			<input type = "hidden" name = "idcategory" value = "<?php htmlecho($idcategory);?>">
			<input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>	
	</div>
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>