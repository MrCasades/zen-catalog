<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

<div class = "maincont">
	<p><a href = '?add' class="btn btn-primary btn-sm">Добавить ранг</a></p>
	<br>
		<table>
		<tr><th>Название</th><th>Возможные действия</th></tr>
		<?php if (empty ($rangs))
		 {
			 echo '<p align = "center">Ранги не добавлены</p>';
		 }
		 
		 else
			 
		 foreach ($rangs as $rang): ?> 
			<tr> 
			  <form action = " " method = "post">
			   <div>
				<td><?php htmlecho($rang['rangname']);?></td>
				<td>
				<input type = "hidden" name = "idrang" value = "<?php echo $rang['id']; ?>">
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