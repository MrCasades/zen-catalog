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
			<th>Имя автора: </th><td><?php htmlecho($authorname);?></td>	
		</tr> 
		<tr>
			<th>E-mail: </th><td><?php htmlecho($email);?></td>	
		</tr>
		<tr>
			<th>WWW: </th><td><input type = "text" name = "www" id = "www" value = "<?php htmlecho($www);?>"></td>	
		</tr>
	  </table>	
	  <br>
		<div>
			<strong><label for = "post">Дополнительная информация:</label></strong>
			<textarea class = "descr" id = "accountinfo" name = "accountinfo" data-provide="markdown" rows="10"><?php htmlecho($accountinfo);?></textarea>	
		</div>		 
     <br>
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