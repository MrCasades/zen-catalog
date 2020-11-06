<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
		<div>
		<table align = "center" border = "1">
		  <tr>
				<th>#id</th>
				<th>Дата публикации</th>
				<th>Заголовок</th>	
				<th>Автор</th>
				<th>E-mail</th>				
		  </tr> 
		  
		   <?php if (empty ($promotions))
		 {
			 echo '<p align = "center">Материалы для премодерации отсутствуют</p>';
		 }
		 
		 else
			 
		 foreach ($promotions as $promotion): ?> 
		  <tr>
				<td><?php echo '# '.$promotion['id'];?></td>
				<td><?php echo $promotion['promotiondate'];?></td>
				<td><a href="../../admin/premoderation/viewpremodpromotion/?promotion=<?php echo $promotion['id'];?>"><?php echo $promotion['promotiontitle'];?></a></td>
				<td><?php echo $promotion['authorname'];?></td>
				<td><?php echo $promotion['email'];?></td>
		  </tr> 				
		 <?php endforeach; ?> 
		</table>
		</div>	
	</div>
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>	