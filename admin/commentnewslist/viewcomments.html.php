<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont_for_view"> 
			<div align = "center"><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
			<script src="//yastatic.net/share2/share.js"></script>
			<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></div>
		<hr/>
		<p align = "center"> <a href="<?php echo '//'.MAIN_URL.'/admin/commentnewslist/';?>" class="btn btn-info">Комментарии новостей</a> | 
		<a href="<?php echo '//'.MAIN_URL.'/admin/commentpostlist/';?>" class="btn btn-primary btn-sm">Комментарии статей</a> |
		<a href="<?php echo '//'.MAIN_URL.'/admin/commentpromotionlist/';?>" class="btn btn-primary btn-sm">Комментарии промоушен статей</a></p>
		
		<div>
		<?php if (empty ($newsComments))
				{
					echo '<p align="center">Комментарии отсутствуют!</p>';
				}
				
			  else
				
				foreach ($newsComments as $newsComment): ?> 	   		
				<div class = "post">
				 <div class = "posttitle">
				    Дата комментария: <?php echo ($newsComment['date']. ' | Автор: <a href="../account/?id='.$newsComment['idauthor'].'" style="color: white" >'.$newsComment['authorname']).'</a>';?>
				  </div>	
					<p><?php 
				   
						/*Вывод меню редактирования и удаления комментария для автора*/
						 if (isset($_SESSION['loggIn']))
						 {
							$authorName = authorLogin ($_SESSION['email'], $_SESSION['password']);//имя автора вошедшего в систему
						 }
						 else
						 {
							 $authorName = '';
						 }
						 if (($authorName == $newsComment['authorname']) || (userRole('Администратор')))
						 {
							 $updAnddel = '<form action = "../../viewnews/" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$newsComment ['id'].'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Редактировать">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Del">
								</div>
							</form>';		 
						 }	
						 else
						 {
							 $updAnddel = '';
						 }							 
							
						 echo $updAnddel;?></p>
				  <p>
					<table cellpadding = "3 %">
						<td><img width = "90 px" height = "90 px" src="../../avatars/<?php echo $newsComment['avatar'];?>" alt="<?php echo $newsComment['authorname'];?>"></td>
						<td ><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($newsComment['text'])), 0, 50))); ?> [...]</td>
					</table>	
				  </p>
				  <p><img width = "3%" height = "3%" src="<?php echo '//'.MAIN_URL;?>/answers.jpg" alt="Ответы на комментарий" title="Количество ответов"> 
					  <strong>[<?php echo $newsComment['subcommentcount']; ?>]</strong></p>
				  <a href="../../viewwallpost/?id=<?php echo $newsComment['id']; ?>" class="btn btn-primary btn-sm">Открыть</a>
				  <div><strong>К материалу:</strong> <a href="../../viewnews/?id=<?php htmlecho ($newsComment['idnews']); ?>"><?php htmlecho ($newsComment['newstitle']); ?></a></div>	
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

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>
