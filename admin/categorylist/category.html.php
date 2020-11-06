<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
		<div>
		  <label for = "categoryname">Название рубрики: <input type = "text" name = "categoryname" id = "categoryname" value = "<?php htmlecho($categoryname);?>"> </label>	
		</div> 
		<div>
		  <input type = "hidden" name = "idcategory" value = "<?php htmlecho($idcategory);?>">
		  <input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>	
		<table>
		<tr><th>Название</th><th>Возможные действия</th></tr>
		<?php if (!isset($categorys))
		 {
			 $noPosts = '<p align = "center">Категории не добавлены</p>';
			 echo $noPosts;
			 $categorys = null;
		 }
		 
		 else
			 
		 foreach ($categorys as $category): ?> 
			<tr>
			  <form action = " " method = "post">
			   <div>
				<td><?php htmlecho($category['categoryname']);?></td>
				<td>
				<input type = "hidden" name = "idcategory" value = "<?php echo $category['id']; ?>">
				<input type = "submit" name = "action" value = "Upd" class="btn btn-primary btn-sm">
				<input type = "submit" name = "action" value = "Del" class="btn btn-primary btn-sm">
				</td>
			   </div>
		      </form>
			</tr>
		 <?php endforeach; ?>	
		</table>
	</div>	
	
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>