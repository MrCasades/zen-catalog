<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont_for_view">
	<div class = "post">
	
	<p align = "center"><strong><?php htmlecho($errorForm); ?></strong></p>
	
	<form action = "?<?php htmlecho($action); ?> " method = "post">
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
		<td><label for = "promotion"> Выберете свой канал для совместного действия:</label></td>
		<td>
		<select name = "idpromotionfrom" id = "idpromotionfrom">
		  <option value = "" >Мои каналы</option>
			<?php foreach ($authorPromotions as $authorPromotion): ?>
			 <option value = "<?php htmlecho($authorPromotion['idpromotion']); ?>"
			 <?php if ($authorPromotion['idpromotion'] == $idtasktype)
			 {
				 echo 'selected';
			 }				 
			  ?>><?php htmlecho($authorPromotion['promotiontitle']); ?></option>
			<?php endforeach; ?> 
		</select>
		</td>
		</tr>		
	 </div>
	 </table>
	 <p id = "incorr" style="color: red"></p>
	<div>
		<label for = "description">Опишите суть совместного действия в нескольких предложениях. (Подписка, лайк, дочитка и т. д.)</label><br>
		<p id = "incorrdesc" style="color: red"></p>
		<textarea class = "descr" id = "description" name = "description" data-provide="markdown" rows="10"><?php htmlecho($description);?></textarea>	
	 </div>
	 <hr/>
	  <div>
		<input type = "hidden" name = "idauthorto" value = "<?php htmlecho($authorId); ?>">
		<input type = "hidden" name = "idarticle" value = "<?php htmlecho($idPromotion); ?>">
		<input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		<input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary btn-sm" id = "confirm">
	  </div>	  
	</form>	
	</div>
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>	