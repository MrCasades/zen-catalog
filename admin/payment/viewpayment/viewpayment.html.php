<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont">
	<div class = "post">
	
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
		<td><label for = "email"> E-mail:</label></td>
		<td>
		 <?php echo $email;?>
		</td>
	  </tr>
	 </div>
	<div>
	  <tr>
		<td><label for = "payment"> Сумма платежа:</label></td>
		<td>
		 <?php echo $payment;?>
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
	</table>	
	  <div>
		<input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary btn-sm">
	  </div>
	
	</form>	
	</div>
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>	