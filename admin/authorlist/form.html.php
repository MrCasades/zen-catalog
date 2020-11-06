<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	<div class = "post">
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
	  <table>	
		<tr>
			<th>Имя автора: </th><td><input type = "text" name = "authorname" id = "authorname" value = "<?php htmlecho($authorname);?>"></td>	
		</tr> 
		<tr>
			<th>E-mail: </th><td><input type = "text" name = "email" id = "email" value = "<?php htmlecho($email);?>"></td>	
		</tr>
		<tr>
			<th>WWW: </th><td><input type = "text" name = "www" id = "www" value = "<?php htmlecho($www);?>"></td>	
		</tr>
		
	  </table>	
	  <br>
		<div>
			<strong><label for = "post">Дополнительная информация:</label></strong>
			<textarea class = "descr" id = "accountinfo" name = "accountinfo" rows = "3" cols = "40"><?php htmlecho($accountinfo);?></textarea>	
		</div>		 
     <br>
		<fieldset>
			<legend>Roles:</legend>
			<?php for ($i = 0; $i < count($roles); $i++): ?>
			 <div>
			  <label for = "role<?php echo $i; ?>"><input type = "checkbox" name = "roles[]" id = "role<?php echo $i; ?>" 
			  value = "<?php htmlecho($roles[$i]['id']); ?>"
			  <?php 
				if ($roles[$i]['selected'])
				{
					echo ' checked';
				}
			   ?>> <?php htmlecho($roles[$i]['id']); ?>
			  </label>:
			  <?php htmlecho($roles[$i]['descr']); ?>
			 </div>
			<?php endfor; ?> 
		</fieldset>
		<div>
			<input type = "hidden" name = "id" value = "<?php htmlecho($idauthor);?>">
			<input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>
	</div>	
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>