<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont">
	<div class = "post">
	
	<p align = "center"><strong><?php htmlecho($errorForm); ?></strong></p>
	
	<form action = "?<?php htmlecho($action); ?> " method = "post" enctype="multipart/form-data" autocomplete="on">
	<table>
	 <div>
	  <tr>
		<td><label for = "author"> Автор:</label></td>
		<td>
		 <?php echo $authorPost;?>
		</td>
	  </tr>
	 </div>	 
	 <div>
	  <tr>
		<td><label for = "upload">Загрузите файл изображения</label><input type = "file" name = "upload" id = "upload"></td>
		<td><input type = "hidden" name = "action" value = "upload"></td>
	  </tr>		 
	</div>
	</table>	
	 <div>
		<label for = "promotion">Введите текст сообщения</label><br>
		<textarea class = "descr" id = "text" name = "text" data-provide="markdown" rows="10"><?php htmlecho($text);?></textarea>	
	 </div>
	 <hr/>
	  <div>
		<input type = "hidden" name = "idto" value = "<?php htmlecho($idto); ?>">
		<input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		<input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary btn-sm">
	  </div>	
	</form>	
	<p><a name="bottom"></a></p>	
	</div>
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>	