	<div>
	  <div>
	   <table>
		<tr>
		    <td><a href="../account/allauthornews/?id=<?php htmlecho ($idAuthor); ?>">Новости автора</a></td>
		    <td><a href="../account/allauthorpost/?id=<?php htmlecho ($idAuthor); ?>">Статьи автора</a></td>
		</tr>
		<tr>
		<?php if (!isset($newsIn))
		 {
			 $noPosts = '<td><p align = "center">Автор не написал ни одной новости</p></td>';
			 echo $noPosts;
			 $newsIn = null;
		 }
		 
		 else
			 
		 echo '<td>'; 
		 foreach ($newsIn as $news): ?> 
		  <ul>
			<li><a href="../viewnews/?id=<?php htmlecho ($news['id']); ?>"><?php htmlecho ($news['newstitle']); ?></a></li> 
		  </ul>				
		 <?php endforeach; ?></td>
		 
	  </div>
      <div>	  
		 
		<?php if (!isset($posts))
		 {
			 $noPosts = '<td><p align = "center">Автор не написал ни одной новости</p></td>';
			 echo $noPosts;
			 $posts = null;
		 }
		 
		 else
			
		 echo '<td>';
		 foreach ($posts as $post): ?> 
		  <ul>
			<li><a href="../viewpost/?id=<?php htmlecho ($post['id']); ?>"><?php htmlecho ($post['posttitle']); ?></a></li>
		  </ul>			
		 <?php endforeach; ?></td>
		 </tr>
	    </table>	 
	  </div>	 
	</div>