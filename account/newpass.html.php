<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	 <div class = "post" align = "center">
	  <?php htmlecho($errLog);?>
	   <form action = "?<?php htmlecho ($action); ?>" method = "post">
		 
		  <table>
		   <tr>
			<th>Пароль: </th><td><input type = "password" name = "password" id = "password" value = "<?php htmlecho($password);?>"></td> 
		   </tr>
		   <tr>	 
			 <th>Повторить пароль: </th><td><input type = "password" name = "password2" id = "password2" value = "<?php htmlecho($password2);?>"></td>
		   </tr>
		   <tr>	
			 <td><input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm"></td>
		   </tr>	 
		  </table>	 
	   </form>
	  </div>
	</div>
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>