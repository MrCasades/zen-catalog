<?php 

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
		<div>
		 <table>
		 <tr>
		  <td valign="top"><label for = "meta"> Теги:</label></td> 
		  <?php if (empty($metas))
			  {
				 echo 'Теги отсутствуют';
		      }
		 
		      else
				  
		      foreach ($metas as $meta): ?>	  
				<td><div>	 
					<a href="../../../viewmetapromotion/?metaid=<?php echo $meta['id']; ?>"><?php echomarkdown ($meta['metaname']); ?></a>	 
				</div></td> 	
				<?php endforeach; ?>
		  </tr>
		 </table>
		</div>
		
		<div>	  
			<div  align="justify">
			
				<h3><?php echo ($articleTitle. 'Post #'.$articleId); ?></h3>
					<?php if ($imgHead == '')
					{
						$img = '';//если картинка в заголовке отсутствует
						echo $img;
					}
						else 
					{
						$img = '<img width = "60%" height = "40%" src="../../../images/'.$imgHead.'"'. ' alt="'.$imgAlt.'"'.'>';//если картинка присутствует
					}?>
					<p><?php echo $img;?></p>
					<p><?php echomarkdown ($articleText); ?></p>	
					<p align="center"><?php echo $video; ?></p>
					<p><?php echo $delAndUpd; ?></p>
					<p align="center"><?php echo $premoderation; ?></p>
			</div>	
		</div>	
	</div>		
					
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>