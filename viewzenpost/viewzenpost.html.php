<?php 

/*Загрузка функций для формы входа*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/access.inc.php';

/*Загрузка функций в шаблон*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/func.inc.php';

/*Загрузка header*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/header.inc.php';?>

	<div class = "maincont_for_view">
		<div align="center">
		 <table>
		 <tr>
		  <td valign="top"><label for = "meta"> Теги:</label></td> 
		  <?php if (!isset($metas))
			  {
				 $noPosts = ' ';
				 echo $noPosts;
				 $metas = null;
		      }
		 
		      else 
		  
			  foreach ($metas as $meta): ?>	  
				<td><div>	 
					<a href="/viewmetapost/?metaid=<?php echo $meta['id']; ?>"><?php echomarkdown ($meta['metaname']); ?></a>	 
				</div></td> 	
				<?php endforeach; ?>
		  </tr>
		 </table>
		</div>
		
		<div class = "post">
		 <?php foreach ($posts as $post): ?> 	  
			<div  align="justify">
			
				<div class = "posttitle">
				  <?php echo ($post['postdate']. ' | Автор: <a href="/account/?id='.$post['idauthor'].'" style="color: white" >'.$post['authorname']).'</a>';?>
				  <p>Рубрика: <a href="../viewcategory/?id=<?php echo $post['categoryid']; ?>" style="color: white"><?php echo $post['categoryname'];?></a></p>
				</div>
				  <p><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
				  <script src="//yastatic.net/share2/share.js"></script>
				  <div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></p>
				  <hr>
				  <!-- Yandex.RTB R-A-448222-9 -->
                    <div id="yandex_rtb_R-A-448222-9"></div>
                    <script type="text/javascript">
                        (function(w, d, n, s, t) {
                            w[n] = w[n] || [];
                            w[n].push(function() {
                                Ya.Context.AdvManager.render({
                                    blockId: "R-A-448222-9",
                                    renderTo: "yandex_rtb_R-A-448222-9",
                                    async: true
                                });
                            });
                            t = d.getElementsByTagName("script")[0];
                            s = d.createElement("script");
                            s.type = "text/javascript";
                            s.src = "//an.yandex.ru/system/context.js";
                            s.async = true;
                            t.parentNode.insertBefore(s, t);
                        })(this, this.document, "yandexContextAsyncCallbacks");
                    </script>
				  <hr>
				    <p class="like"> 
				     <img width = "5%" height = "5%" src="<?php echo '//'.$_SERVER['SERVER_NAME'];?>/view.jpg" alt="Число просмотров материала" title="Просмотры"> <?php htmlecho ($post['viewcount']); ?> 
				     <img width = "3%" height = "3%" src="<?php echo '//'.$_SERVER['SERVER_NAME'];?>/like.jpg" alt="Оценка материала" title="Оценка"> <?php htmlecho ($post['averagenumber']); ?>
					</p>
					<?php if ($post['imghead'] == '')
					{
						$img = '';//если картинка в заголовке отсутствует
						echo $img;
					}
						else 
					{
						$img = '<p align="center"><img width = "60%" height = "40%" src="/images/'.$post['imghead'].'"'. ' alt="'.$post['imgalt'].'"'.'></p>';//если картинка присутствует
					}?>	
					<p><?php echo $img;?></p>	
					<p><?php echomarkdown ($post['text']); ?></p>
					<p align="center"><?php echo $video; ?></p>
					<p><?php echo $votePanel; ?></p>
					<p><?php echo $delAndUpd; ?></p>
					<p><?php echo $premoderation; ?></p>
					<div align="center"><?php echo $recommendation; ?></div>
			</div>			
		 <?php endforeach; ?>
		</div>
		<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- ForPosts -->
		<ins class="adsbygoogle"
			 style="display:block"
			 data-ad-client="ca-pub-1348880364936413"
			 data-ad-slot="7237613613"
			 data-ad-format="auto"
			 data-full-width-responsive="true"></ins>
		<script>
			 (adsbygoogle = window.adsbygoogle || []).push({});
		</script>
		<div>
		 <h5>Случайные статьи рубрики</h5>
		 <ul>
		  <?php foreach ($similarPosts as $post_1): ?>
		   
			<li><a href="/viewpost/?id=<?php htmlecho ($post_1['id']); ?>"><?php echo $post_1['posttitle'];?></a></li>
		   
		  <?php endforeach; ?>
		 </ul> 
		</div>
		 <div align="center"><h4>Комментарии (<?php echo $countPosts; ?>)</h4>
		 <a href="?addcomment" class="btn btn-primary">Добавить комментарий</a></div>
		<div>
		<?php if (!isset($comments))
				{
					$noComments = '<p align="center">Комментарии отсутствуют!</p>';
					echo $noComments;
					$comments = null;
				}
				
			  else
				
				foreach ($comments as $comment): ?> 	   		
				<div class = "post">
				 <div class = "posttitle">
				    Дата комментария: <?php echo ($comment['date']. ' | Автор: <a href="/account/?id='.$comment['idauthor'].'" style="color: white" >'.$comment['authorname']).'</a>';?>
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
						 if ($authorName == $comment['authorname'])
						 {
							 $updAnddel = '<form action = "?" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$comment ['id'].'">
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
						<td><img width = "90 px" height = "90 px" src="/avatars/<?php echo $comment['avatar'];?>" alt="<?php echo $comment['authorname'];?>"></td>
						<td ><?php echomarkdown (implode(' ', array_slice(explode(' ', strip_tags($comment['text'])), 0, 50))); ?> [...]</td>
					</table>	
				  </p>
				  <p><img width = "3%" height = "3%" src="<?php echo '//'.$_SERVER['SERVER_NAME'];?>/answers.jpg" alt="Ответы на комментарий" title="Количество ответов"> 
					  <strong>[<?php echo $comment['subcommentcount']; ?>]</strong></p>
				  <a href="/viewwallpost/?id=<?php echo $comment['id']; ?>" class="btn btn-primary btn-sm">Открыть</a>
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
						 echo "<a href='/viewpost/?id=".$idPost."&page=$i' class='btn btn-info'>$i</a> ";
					 } 
					 else 
					 {
						 echo "<a href='/viewpost/?id=".$idPost."&page=$i' class='btn btn-primary btn-sm'>$i</a> ";
					 }
				 }?>
				</div>	
		</div>		
	</div>		

<?php 
/*Загрузка footer*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/footer.inc.php';?>