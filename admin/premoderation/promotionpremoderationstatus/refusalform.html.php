<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	  <div class = "maincont"> 
	   <div class = "post" align = "center">
		  <p><?php htmlecho($premodYes); ?> "<?php htmlecho($posttitle); ?>"?</p>
	   <p>
	    <form action = "?<?php htmlecho($action); ?> " method = "post">		
			<p><label for = "reasonrefusal">Причина отказа </label>
			<textarea class = "descr" id = "reasonrefusal" name = "reasonrefusal" data-provide="markdown" rows="10"><?php htmlecho($reasonrefusal);?></textarea>  </p>	
		 <p> <input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		  <input type = "submit" name = "delete" class="btn btn-primary btn-sm" value = "<?php htmlecho($button); ?>">
		  <a href="#" onclick="history.back();" class="btn btn-primary btn-sm">Назад</a></p>
	    </form>
	   </p>
	   </div>
	</div>	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>