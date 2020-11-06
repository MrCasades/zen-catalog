<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont">
	<br>
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
		<div>
			<label for = "contestname">Название конкурса: </label> 
			<input type = "text" name = "contestname" id = "contestname" value = "<?php htmlecho($contestname);?>">	
		</div> 
		<div>
			<label for = "votingpoints">Очки за голосование: </label> <input type = "text" name = "votingpoints" id = "votingpoints" value = "<?php htmlecho($votingpoints);?>">		
		</div>
		<div>
			<label for = "commentpoints">Очки за комментарии: </label><input type = "text" name = "commentpoints" id = "commentpoints" value = "<?php htmlecho($commentpoints);?>">		
		</div>
		<div>
			<label for = "favouritespoints">Очки за доб. в избранное: </label><input type = "text" name = "favouritespoints" id = "favouritespoints" value = "<?php htmlecho($favouritespoints);?>">		
		</div>
		<div>
			<input type = "hidden" name = "idcontest" value = "<?php htmlecho($idcontest);?>">
			<input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>	
	</div>
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>