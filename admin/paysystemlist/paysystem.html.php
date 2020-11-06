<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
		<div>
		  <label for = "paysystemname">Название платежн. системы: <input type = "text" name = "paysystemname" id = "paysystemname" value = "<?php htmlecho($paysystemname);?>"> </label>	
		</div> 
		<div>
		  <input type = "hidden" name = "idpaysystem" value = "<?php htmlecho($idpaysystem);?>">
		  <input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>	
		<table>
		<tr><th>Название</th><th>Возможные действия</th></tr>
		<?php if (empty ($paysystems))
		 {
			 echo '<p align = "center">Категории не добавлены</p>';
		 }
		 
		 else
			 
		 foreach ($paysystems as $paysystem): ?> 
			<tr>
			  <form action = " " method = "post">
			   <div>
				<td><?php htmlecho($paysystem['paysystemname']);?></td>
				<td>
				<input type = "hidden" name = "idpaysystem" value = "<?php echo $paysystem['id']; ?>">
				<input type = "submit" name = "action" value = "Upd" class="btn btn-primary btn-sm">
				<input type = "submit" name = "action" value = "Del" class="btn btn-primary btn-sm">
				</td>
			   </div>
		      </form>
			</tr>
		 <?php endforeach; ?>	
		</table>
	</div>	
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>