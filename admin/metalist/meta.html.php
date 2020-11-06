<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

	<div class = "maincont">
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
		<div>
		  <label for = "authorname">Название тематики: <input type = "text" name = "metaname" id = "metaname" value = "<?php htmlecho($metaname);?>"></label>	
		</div> 
		<div>
		  <input type = "hidden" name = "idmeta" value = "<?php htmlecho($idmeta);?>">
		  <input type = "submit" value = "<?php htmlecho($button);?>" class="btn btn-primary btn-sm">
		</div>
	</form>	
		<table>
		<tr><th>Название</th><th>Возможные действия</th></tr>
		<?php if (!isset($metas))
		 {
			 $noPosts = '<p align = "center">Теги не добавлены</p>';
			 echo $noPosts;
			 $metas = null;
		 }
		 
		 else
			 
		 foreach ($metas as $meta): ?> 
			<tr>
			  <form action = " " method = "post">
			   <div>
				<td><?php htmlecho($meta['metaname']);?></td>
				<td>
				<input type = "hidden" name = "idmeta" value = "<?php echo $meta['id']; ?>">
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