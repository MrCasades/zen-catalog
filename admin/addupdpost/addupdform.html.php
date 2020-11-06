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
		<label for = "posttitle">Введите заголовок <span style = "color: red"> *</span> </label><br>
		<textarea class = "descr" id = "posttitle" name = "posttitle" rows = "3" cols = "40" placeholder = "Введите заголовок!"><?php htmlecho($posttitle);?></textarea>
		<p><span id="counttitlelen">0</span> / 200	</p>
	</div>
	<hr/>	
	 <div>
		<label for = "category"> Рубрика:<span style = "color: red"> *</span></label>
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
		<legend>Тематика <?php echo $addMetas;?></legend>
		 <?php if (empty ($metas_1))
		 { 
			 echo '<p align = "center">Теги не добавлены</p>';
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
		<label for = "imgalt">Введите alt-текст для изображения:</label>
		<input type = "imgalt" name = "imgalt" id = "imgalt" value = "<?php htmlecho($imgalt);?>">
	</div>
	<hr/>		
	<div>
		<label for = "videoyoutube">Ссылка на видео Youtube: </label>
		<input type = "videoyoutube" name = "videoyoutube" id = "videoyoutube" value = "<?php htmlecho($videoyoutube);?>">
	</div>
	<hr/>		
	<div>
		<label for = "description">Краткое описание</label><br>
		<textarea class = "descr" id = "description" name = "description" rows = "3" cols = "40" placeholder = "Опишите в паре предложений суть материала"><?php htmlecho($description);?></textarea>	
	 </div>
		<h5>Подсказка по разметке текста</h5>
		 <ul>
			<li>Синтаксис ссылки на сторонний ресурс: [текст ссылки](ссылка)</li>
			<li>Выделение <em>курсивом</em>: _текст_</li>
			<li>Выделение <strong>жирным шрифтом</strong>: **текст**</li>
			<li><p><strong>Для вставки изображения</strong> в текст воспользуйтесь любым файловым хостингом (например <strong>https://ipic.su/</strong>, главное получить 
				   прямую ссылку на картинку вида "сайт.ru/картинка.jpg")</p>
				<p><strong>Синтаксис вставки:</strong> ![подпись](прямая ссылка на изображение)</p>
				<p>ВАЖНО! На картинках не должно быть водяных знаков сторонних ресурсов. Само изображение желательно минимально обработать, если оно неоригинальное.
				   (Хотябы немного обрезать, отзеркалить и т.п.)</p></li>
		 </ul>	
	 <hr/>	
	 <div>
		<label for = "post">Введите текст статьи <span style = "color: red"> *</span></label><br>
		<textarea class = "descr" id = "text" name = "text" data-provide="markdown" rows="10" placeholder = "Добавьте текст"><?php htmlecho($text);?></textarea>	
	 </div>
	 <hr/>	
	  <div>
		<input type = "hidden" name = "id" value = "<?php htmlecho($id); ?>">
		<input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary btn-sm" id = "confirm">
	  </div>	  
	</form>	
	</div>
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>	