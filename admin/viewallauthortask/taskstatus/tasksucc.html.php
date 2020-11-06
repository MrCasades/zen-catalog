<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont"> 
	 <div class = "post" align="center">
		<p>Вы успешно взяли задание! Перейдите в раздел "Мои задания" в профиле, для того, чтобы выполнить его.</p>
		<a href="/admin/viewallauthortask/" class="btn btn-primary btn-sm">К заданиям.</a>
	 </div>	
	</div> 
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>