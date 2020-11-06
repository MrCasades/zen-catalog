<?php 

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

<div class = "maincont_for_view">
	<div align = "center"><script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
	<script src="//yastatic.net/share2/share.js"></script>
	<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div></div>
<div class = "post">
	<div style="padding-left: 20%">
		<?php 
		 if (empty ($categorysMM))
		 {
			  echo '<p align = "center">Категории отсутствуют</p>';
		 }
			
		 else
			 
			  foreach ($categorysMM as $category): ?>
                <h3><a href="<?php echo '//'.MAIN_URL;?>/viewallpromotionincat/?id=<?php echo $category['id']; ?>"><?php echomarkdown ($category['category']); ?></a></h3>
			  <?php endforeach; ?>	
	</div>	
</div>		 
		 
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>		


