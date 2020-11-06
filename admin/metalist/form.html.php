<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<?php 
	/*Загрузка списка рубрик*/
	include_once $_SERVER['DOCUMENT_ROOT'] . '/imagozcms/categorypanel/categorypanel.inc.php'; ?>
	
	<div class = "maincont">
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
		<div>
			<label for = "authorname">Название тематики: <input type = "text" name = "metaname" id = "metaname" value = "<?php htmlecho($metaname);?>" 
			</label>	
		</div> 
		<div>
			<input type = "hidden" name = "idmeta" value = "<?php htmlecho($idmeta);?>">
			<input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>	
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>

