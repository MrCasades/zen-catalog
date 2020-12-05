<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	<p><a name="bottom"></a></p>  
	<div class = "maincont_for_view">
	 <div class = "post_reg_log" align="center">
	  <?php if (isset($errLogin)): ?>
		<p style="color: red"><strong><?php htmlecho($errLogin); ?></strong></p>
	  <?php endif; ?>	
	  <form action = " " method = "post">
	   <table cellpadding = "2">	
		<tr>
		 <th>Email: </th><td><input type = "text" name = "email" id = "email"></td>
		</tr> 		
		<tr>
		 <th>Пароль: </th><td><input type = "password" name = "password" id = "password"></td>	
		<tr>
	   </table>		
		 <br><input type = "hidden" name = "action" value = "login">
		 <input type = "submit" value = "Вход" class="btn btn-primary">
	  </form>	
     </div>	 
	 <p align="center"><a href="#" onclick="history.back();" class="btn btn-primary btn-sm">Назад</a> | <a href="../../admin/recoverpassword/?send" class="btn btn-info btn-sm">Забыли пароль?</a></p>	
	</div>		

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>

