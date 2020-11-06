<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont_for_view"> 
			<div align = "center"><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
			<script src="//yastatic.net/share2/share.js"></script>
			<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></div>
			
		<div class = "post">
		 <p align="center"><img width = "20%" height = "20%" src="<?php echo '//'.MAIN_URL;?>/logomain.jpg" alt="Ошибка 404!"></p>
		  <div align="center">
		   <h3><?php echo $pageNotFound; ?></h3>
		  </div>
		</div>	
	</div>		
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>	