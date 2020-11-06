<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
		<div>
		  <label for = "contest">Название конкурса: <input type = "text" name = "contestname" id = "contestname" value = "<?php htmlecho($contestname);?>"> </label>	
		</div> 
		<div>
		  <label for = "votingpoints">Очки за голосование: <input type = "text" name = "votingpoints" id = "votingpoints" value = "<?php htmlecho($votingpoints);?>"> </label>	
		</div>
		<div>
		<div>
		  <label for = "commentpoints">Очки за комментарий: <input type = "text" name = "commentpoints" id = "commentpoints" value = "<?php htmlecho($commentpoints);?>"> </label>	
		 </div>
		 <div>
		  <label for = "favouritespoints">Очки за доб. в избранное: <input type = "text" name = "favouritespoints" id = "favouritespoints" value = "<?php htmlecho($favouritespoints);?>"> </label>	
		 </div>
		<div>
		  <input type = "hidden" name = "idcontest" value = "<?php htmlecho($idcontest);?>">
		  <input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>	
			
		<table>
		<tr>
			<th>Название</th>
			<th>За голос</th>
			<th>За комментарий</th>
			<th>За избранное</th>
			<th>Возможные действия</th>
		</tr>
		<?php if (empty ($contests))
		 { 
			 echo '<p align = "center">Категории не добавлены</p>';
		 }
		 
		 else
			 
		 foreach ($contests as $contest): ?> 
			
			<?php if ($contest['conteston'] == 'NO')
					{
						$contestOn = 'ON';
						$buttonClass_1 = 'btn-primary';
					}
					
					else
					{
						$contestOn = 'OFF';
						$buttonClass_1 = 'btn-danger';
					}
			
				  if ($contest['contestpanel'] == 'NO')
				    {
					 	$contestPanel = 'CP_ON'; 
					  	$buttonClass_2 = 'btn-primary';
				    }
			
					else
					{
						$contestPanel = 'CP_OFF'; 
						$buttonClass_2 = 'btn-danger';
					}?>
			
			<tr>
			  <form action = " " method = "post">
			   <div>
				<td><?php htmlecho($contest['contestname']);?></td>
				<td><?php htmlecho($contest['votingpoints']);?></td>
				<td><?php htmlecho($contest['commentpoints']);?></td>
				<td><?php htmlecho($contest['favouritespoints']);?></td>
				<td>
				<input type = "hidden" name = "idcontest" value = "<?php echo $contest['id']; ?>">
				<input type = "submit" name = "action" value = "Upd" class="btn btn-primary btn-sm">
				<input type = "submit" name = "action" value = "Del" id = "delobject"  class="btn btn-primary btn-sm">
				<input type = "submit" name = "action" value = "<?php echo $contestOn; ?>" class="btn <?php echo $buttonClass_1; ?> btn-sm">
				<input type = "submit" name = "action" value = "<?php echo $contestPanel; ?>" class="btn <?php echo $buttonClass_2; ?> btn-sm">
				</td>
			   </div>
		      </form>
			</tr>
		 <?php endforeach; ?>	
		</table>
	</div>
		<hr/>
		<form action = " " method = "post">
				<input type = "submit" name = "action" value = "Обнулить баллы" id = "resetcontest" class="btn btn-primary btn-sm">
		</form>	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>