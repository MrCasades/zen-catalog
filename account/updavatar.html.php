<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont">
	 <div class = "post">
		<form action = "?<?php htmlecho($action); ?> " method = "post" enctype="multipart/form-data">
		 <table>
		  <tr>	
			<td><label for = "upload">Загрузите файл</label><input type = "file" name = "upload" id = "upload"></td>
			<td><input type = "hidden" name = "action" value = "upload"></td>
		  </tr>	
		  <tr>
			<td><input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>"></td>
			<td><input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary btn-sm"></td>
		  </tr>	
		 </table>	
		</form>		
	 </div>
	</div>
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>		