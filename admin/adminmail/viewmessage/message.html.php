<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont_for_view"> 
			<div align = "center"><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
			<script src="//yastatic.net/share2/share.js"></script>
			<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></div>
		
		<div>	
			<?php foreach ($messages as $message): ?> 
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ($message['messagedate']. ' | Автор: <a href="../../../account/?id='.$message['idauthor'].'" style="color: white" >'.$message['authorname']).'</a>';?>
					<p>E-mail: <?php echo $message['email'];?></p>
				  </div>
				   <div class = "newstext">
					<p align = "justify"><?php echomarkdown ($message['text']);?></p>
				   </div>	
				 </div>
			</div>			
		 <?php endforeach; ?>
		
		</div>	

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>