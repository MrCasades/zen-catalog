<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	
	<div class = "maincont_for_view">
	<div class = "post">
	
	<p align = "center"><strong><?php htmlecho($errorForm); ?></strong></p>
	
	<form action = "?<?php htmlecho($action); ?> " method = "post" enctype="multipart/form-data">
	 <div>
		<label for = "author"> Автор:</label>
		 <?php echo $authorPost;?>
		 <?php echo $addAuthor;?>
	 </div>
	<hr/>
	<div>
		<label for = "promotiontitle">Введите название канала <strong><span style = "color: red">*</span></strong> </label><br>
		<textarea class = "descr" id = "promotiontitle" name = "promotiontitle" rows = "3" cols = "40" placeholder = "Введите заголовок!"><?php htmlecho($promotiontitle);?></textarea>
		<p><span id="counttitlelen">0</span> / 200	</p>
	</div>
	<hr/>	
	 <div>
		<label for = "category"> Рубрика каталога:<strong><span style = "color: red">*</span></strong></label>
		<select name = "category" id = "category">
		  <option value = "">Выбрать</option>
			<?php foreach ($categorys_1 as $category): ?>
			 <option value = "<?php htmlecho($category['idcategory']); ?>"
			 <?php if ($category['idcategory'] == $idcategory)
			 {
				 echo 'selected';
			 }				 
			  ?>><?php htmlecho($category['categoryname']); ?></option>
			<?php endforeach; ?> 
		</select>
		 <?php echo $addCatigorys;?>	
	 </div>	
	 <fieldset>
		<legend>Теги <?php echo $addMetas;?></legend>
		 <?php if (empty ($metas_1))
		 { 
			 echo '<p align = "center">Теги не добавлены</p>';
		 }
		 
		 elseif (!userRole('Администратор'))
		 {
			 echo '<p align = "center">Теги будут добавлены модератором</p>';
		 }
		 
		 else
			 
		foreach ($metas_1 as $meta): ?>
		 <div>
		  <label for = "meta<?php htmlecho ($meta['idmeta']);?>">
		   <input type = "checkbox" name = "metas[]" id = "meta<?php htmlecho ($meta['idmeta']);?>"
		   value = "<?php htmlecho ($meta['idmeta']);?>"
		   <?php if ($meta['selected'])
		   {
			   echo ' checked';
		   }
		   ?>><?php htmlecho ($meta['metaname']);?>
		  </label>
		 </div>
		<?php endforeach; ?>
	 </fieldset>
	<hr/>	
	 <div>
		<label for = "upload">Загрузите файл изображения для шапки</label><input type = "file" name = "upload" id = "upload">
		<input type = "hidden" name = "action" value = "upload">
	</div>
	<hr/>		
	<div>
		<input type = "hidden" name = "imgalt" id = "imgalt" value = "<?php htmlecho($imgalt);?>">
	</div>
	<hr/>
	<div>
		<label for = "www">Добавьте ссылку на свой канал: <strong><span style = "color: red">*</span></strong></label>
		<input type = "www" name = "www" id = "www" value = "<?php htmlecho($www);?>" placeholder = "Без http://">
	</div>
	<hr/>		
	<div>
		<label for = "description">Краткое описание (не обязательно)</label><br>
		<textarea class = "descr" id = "description" name = "description" rows = "3" cols = "40" placeholder = "Опишите свой канал в паре предложений!"><?php htmlecho($description);?></textarea>	
	 </div>
	 <hr/>	
	 <div>
		<label for = "post">Введите полный текст описания канала<strong><span style = "color: red">*</span></strong></label><br>
		 <p><em>Напишите как можно более подробное и развёрнутое описание своего канала. Это лучше скажется на видимости его поисковыми системами!</em></p>
		<textarea class = "descr" id = "text" name = "text" data-provide="markdown" rows="10" placeholder = "Добавьте текст"><?php htmlecho($text);?></textarea>	
	 </div>
	 <hr/>	
	  <div>
		<input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		<p align = "center"><button class="btn btn-danger btn-sm" id = "confirm"><strong><h4><?php htmlecho($button); ?></h4></strong></button></p> 
	  </div>	  
	</form>	
	</div>
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>	