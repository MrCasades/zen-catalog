
<?php 
/*Загрузка функций в шаблон*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/func.inc.php';

/*Загрузка header*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/header.inc.php';?>

	<div class = "maincont">
	<p><a href = '?add'>Добавить статью</a></p>
	<form action = " " method = "get">
	<p>Список статей по параметрам:</p>
	 <table>
	 <div>
		<tr>
		<td><label for = "author"> По автору:</label></td>
		<td>
		<select name = "author" id = "author">
		  <option value = "">Любой автор</option>
			<?php foreach ($authors as $author): ?>
			 <option value = "<?php htmlecho($author['id']); ?>"><?php htmlecho($author['authorname']); ?></option>
			<?php endforeach; ?> 
		</select>
		</td>
		</tr>	
	 </div>	
	 <div>
		<tr>
		<td><label for = "category"> По рубрике:</label></td>
		<td>
		<select name = "category" id = "category">
		  <option value = "">Любая рубрика</option>
			<?php foreach ($categorys as $category): ?>
			 <option value = "<?php htmlecho($category['id']); ?>"><?php htmlecho($category['categoryname']); ?></option>
			<?php endforeach; ?> 
		</select>
		</td>		
		</tr>
	 </div>	
	 <div>
		<tr>
		<td><label for = "meta"> По тематике:</label></td>
		<td>
		<select name = "meta" id = "meta">
		  <option value = "">Любая тематика</option>
			<?php foreach ($metas as $meta): ?>
			 <option value = "<?php htmlecho($meta['id']); ?>"><?php htmlecho($meta['metaname']); ?></option>
			<?php endforeach; ?> 
		</select>
		</td>
		</tr>	
	 </div>
	  <div>
		<tr>
		<td><label for = "text">Содержит текст </label></td>
		<td><input type = "text" name = "text" id = "text"></td>
		</tr>
	  </div>
	  <div>
	    <tr>
		<td>
		<input type = "hidden" name = "action" value = "search">
		<input type = "submit" value = "Search">
		</td>
		<tr>
	  </div>
	 </table>	
	</form>	
	</div>
	
<?php 
/*Загрузка footer*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/footer.inc.php';?>

