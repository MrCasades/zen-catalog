<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont">
	<div class = "post">
	
	<strong><p id = "incorr" style="color: red"></p></strong>
	
	<form action = "?<?php htmlecho($action); ?> " method = "post">
	<table>
	 <div>
	  <tr>
		<td><label for = "author"> Автор:</label></td>
		<td>
		 <?php echo $authorname;?>
		</td>
	  </tr>
	 </div>
	<div>
	  <tr>
		<td><label for = "score"> Размер счёта:</label></td>
		<td id = "score">
		 <?php echo round($score, 2, PHP_ROUND_HALF_DOWN);?>
		</td>
	  </tr>
	 </div>
	 <div>
	  <tr>
		<td><label for = "paysystemname"> Платёжная система:</label></td>
		<td>
		 <?php echo $paysystemname;?>
		</td>
	  </tr>
	 </div>
	 <div>
	  <tr>
		<td><label for = "ewallet"> Номер счёта:</label></td>
		<td>
		 <?php echo $ewallet;?>
		</td>
	  </tr>
	 </div>
	<div>
	  <tr>
		<td><label for = "payment">Введите сумму </label></td>
		<td><input type = "payment" name = "payment" id = "payment" value = "<?php htmlecho($payment);?>"></td>
	  </tr>	
	</div>
	</table>
	 <div>
		<input type = "hidden" name = "id" value = "<?php htmlecho($idauthor); ?>">
		<input type = "submit" value = "<?php htmlecho($button); ?>" id = "confirm" class="btn btn-primary btn-sm">
	  </div>	  
	</form>	
	</div>
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>	