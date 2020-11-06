<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont_for_view"> 
	 <div class = "post" align="center">
		<p>Материал отправлен на премодерацию. После проверки администратором будет опубликован!</p>
		<a href="<?php echo '//'.MAIN_URL;?>" id = "confirmok" class="btn btn-danger btn-sm">Подтвердить отправку</a> 
		 <em>(В случае закрытия страницы материал автоматически будет отправлен на премодерацию!)</em>
	 </div>	
		<h2>Предварительный просмотр</h2>
		<div align="center">
		 <table>
		 <tr>
		  <td valign="top"><label for = "meta"> Теги:</label></td>
			   <?php if (empty ($metas))
			  {
				 echo ' ';
		      }
		 
		      else
				  
			  foreach ($metas as $meta): ?>	  
				<td><div>	 
					<a href="../../viewmetapromotion/?metaid=<?php echo $meta['id']; ?>"><?php echomarkdown ($meta['metaname']); ?></a>	 
				</div></td> 	
				<?php endforeach; ?>
		  </tr>
		 </table>
		</div>
		
		<div class = "post">
		 <?php foreach ($promotions as $promotion): ?> 	  
			<div  align="justify">
			
				<div class = "posttitle">
				  <?php echo ($promotion['promotiondate']. ' | Автор: <a href="../../account/?id='.$promotion['idauthor'].'" style="color: white" >'.$promotion['authorname']).'</a>';?>
				  <p>Рубрика: <a href="../../viewcategory/?id=<?php echo $promotion['categoryid']; ?>" style="color: white"><?php echo $promotion['categoryname'];?></a></p>
				</div>
				   			 
				<?php if ($promotion['imghead'] == '')
					{
						$img = '';//если картинка в заголовке отсутствует
						echo $img;
					}
						else 
					{
						$img = '<p align="center"><img width = "60%" height = "40%" src="../../images/'.$promotion['imghead'].'"'. ' alt="'.$promotion['imgalt'].'"'.'></p>';//если картинка присутствует
					}?>
					<p><?php echo $img;?></p>
					<p><?php echomarkdown ($promotion['text']); ?></p>
					<p><?php echo $delAndUpd; ?></p>
			</div>	   	
		 <?php endforeach; ?>
		</div>
	</div> 
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>