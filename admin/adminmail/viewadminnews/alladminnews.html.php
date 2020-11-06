<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont"> 
		<div>
			
		<?php if (empty ($messages))
		 {
			 echo $noPosts;
		 }
		 
		 else
			 
		 foreach ($messages as $message): ?> 
		  
			<div>
				
				<div class = "post">
				  <div class = "posttitle">
				    <?php echo ($message['messagedate']. ' | Автор: <a href="../../../account/?id='.$message['idauthor'].'" style="color: white" >'.$message['authorname']).'</a>';?>
					<p>E-mail: <?php echo $message['email'];?></p>
				  </div>
				   <div class = "newstext">
				    <h5 align = "center"><?php htmlecho ($message['messagetitle']); ?></h5>
					<p align = "justify"><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($message['text'])), 0, 50))); ?> [...]</p>
					<a href="../../../admin/adminmail/viewnews/?idadminnews=<?php htmlecho ($message['id']); ?>" class="btn btn-primary">Далее</a>
				   </div>	
				 </div>
			</div>			
		 <?php endforeach; ?>
		
		 <div align = "center">	
		 <?php
			/*Постраничный вывод информации*/
			for ($i = 1; $i <= $pagesCount; $i++) 
			{
				// если текущая старница
				if($i == $page)
				{
					echo "<a href='index.php?page=$i' class='btn btn-info'>$i</a> ";
				} 
				else 
				{
					echo "<a href='index.php?page=$i' class='btn btn-primary btn-sm'>$i</a> ";
				}
			}?>
		 </div>	
		</div>
	</div>		

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>