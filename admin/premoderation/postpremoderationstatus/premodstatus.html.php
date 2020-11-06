<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	  <div class = "maincont"> 
	   <div class = "post" align = "center">
		  <h3><?php htmlecho($premodYes); ?> "<?php htmlecho($posttitle); ?>"?</h3>
		   
		  <p>
		   <form action = "?<?php htmlecho($action); ?> " method = "post">
			 <table cellpadding = "5 %">
				 <tr>
			 		<th>Автор публикации:</th> <th><span style="color: green"><?php htmlecho($author);?></span></th>
				 </tr>
				 <tr>
		     		<th>Гонорар:</th> <th><span style="color: red" id = "pricetext"><?php htmlecho($pricetext);?></span></th>
				 </tr>
				 <tr>
		     		<th><label for = "editbonus">Бонус / штраф </label></th>
		     		<td><input type = "text" name = "editbonus" value = "0" id = "addbonus"></td>
				 </tr>
			 </table>
			 <p><label for = "editorcomment">Комментарий редактора </label>
			<textarea class = "descr" id = "editorcomment" name = "editorcomment" rows="10"><?php htmlecho($editorcomment);?></textarea>  </p>  
			 <label for = "points">Оценка статьи </label>
			 <input type = "text" name = "points" value = "100" id = "checknum">
		     <input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		     <input type = "submit" name = "delete" class="btn btn-primary btn-sm" value = "<?php htmlecho($button); ?>" id = "confirm">
			 <a href="#" onclick="history.back();" class="btn btn-primary btn-sm">Назад</a>
	       </form>
		   <p id = "incorr" style="color: red"></p>
	      </p>
	   </div>
	</div>	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>