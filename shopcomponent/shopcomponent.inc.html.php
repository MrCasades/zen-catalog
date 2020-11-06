<div class = "titles_main_padge"><h4 align = "center">Купить лицензионные ключи игр</h4></div>
	<div class="columns_shop">
		<?php if (empty ($gamesView))
		 {
			 echo '<p align = "center">Игры отсутствуют</p>';
		 }
		 
		 else
			 
		 foreach ($gamesView as $game): ?> 
		<div class="columns__panel_shop">
         <div class="columns__panel-content_shop">
			<div align = "center">
				<p><a href="<?php htmlecho ($game['url']);?>" rel = "nofollow"><img width = "50%" height = "50%" src="<?php echo $game['images'];?>"></a></p>
				<p><strong><a href="<?php htmlecho ($game['url']); ?>" rel = "nofollow">Купить <?php htmlecho ($game['title']); ?></a></strong></p>
			    <p><?php if ($game['price'] == 0)
						 {
							 echo '<strong>Ожидается</strong>';
						 }
							
						 elseif ($game['old_price'] == '')
						 {
							 echo '<strong style = "color: green">'.$game['price'].' руб.</strong>';
							 //echo '<strong style = "color: green">'.$game['price'].' руб.</strong> (<strike>'.$game['old_price'].' руб.</strike>)';
						 }
								
						 else 
						 {
							 echo '<strong style = "color: green">'.$game['price'].' руб.</strong> (<strike>'.$game['old_price'].' руб.</strike>)';
						 }?></p>
			</div>
			 
		  </div>	 
		</div>	 
		 <?php endforeach; ?>
	</div>	
	<div class="for_allposts_link"><p align = "center"><a href="https://playo.ru/?s=c4a1r15p" style = "color: white" rel = "nofollow">Больше игр здесь!</a></p></div>