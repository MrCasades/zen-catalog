<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont_for_view">
		<div>
			<h3 align = "center">Промоушен</h3>
		  
		  <?php if (empty ($promotions))
		 { 
			 echo '<p align = "center">Отклонённые материалы отсутствуют</p>';
		 }
		 
		 else
		  
		 foreach ($promotions as $promotion): ?> 
		  <div class = "post">
			  <div class = "posttitle">
				  <?php echo ($promotion['promotiondate']. ' | Автор: <a href="../../account/?id='.$promotion['idauthor'].'" style="color: white" >'.$promotion['authorname']).'</a>';?>
			  </div>
			  <div>
				  <h3 align = "center"><?php echo $promotion['promotiontitle'];?></h3>		  	
			  </div> 
			  <div class = "posttitle" align="center">
				  Причина отказа
			  </div>
			  <div>
				  <p align="justify"><?php echomarkdown ($promotion['reasonrefusal']);?></p>
				  <form action = "../../admin/addupdpromotion/" method = "post">
						<input type = "hidden" name = "id" value = "<?php echo $promotion['id'];?>">
						<input type = "submit" name = "action" value = "Переделать" class="btn btn-primary">
						<input type = "submit" name = "action" value = "Del" class="btn btn-danger btn-sm">
					</form>
			  </div>
		  </div>			
		 <?php endforeach; ?> 
	</div>	
		<p><a name="bottom"></a></p>
		</div>	
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>		