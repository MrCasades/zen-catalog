<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont_for_view"> 
		
		<div>	
			<?php foreach ($messages as $message): ?> 
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ($message['messagedate']. ' | Автор: <a href="../../../account/?id='.$message['idauthor'].'" style="color: white" >'.$message['authorname']).'</a>';?>
					<p>E-mail: <?php echo $message['email'];?></p>
				  </div>
					<div align = "right"><?php echo $delAndUpdNews; ?></div>
				   <div class = "newstext">
					<p align = "center"><a href="../../../admin/adminmail/viewadminnews/" class="btn btn-info btn-sm">Все новости</a></p>
					<p align = "justify"><?php echomarkdown ($message['text']);?></p>
				   </div>	
				 </div>
			</div>			
		 <?php endforeach; ?>
		
		</div>	

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>