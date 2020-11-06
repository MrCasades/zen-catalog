
<?php 
/*Загрузка функций в шаблон*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/func.inc.php'?>

<!DOCKTYPE html>
<html>
<head> 

<title>Поиск статьи</title> 
<meta charset = "utf-8">
</head>
<body>
		
	<h1>Результаты поиска</h1>
	<div class = "maincont">
	<?php if (isset ($posts)):?>
		<table>
		 <tr><th>Статья</th><th>Возможные действия</th></tr>
		 <?php foreach ($posts as $post): ?>
		 <tr>
		  <td><?php htmlecho ($post ['text']); ?></td>
		  <td>
		   <form action = "?" method = "post">
			<div>
			 <input type = "hidden" name = "id" value = "<?php htmlecho ($post ['id']);?>">
			 <input type = "submit" name = "action" value = "Upd">
			 <input type = "submit" name = "action" value = "Del">
			</div>
		   </form>
		  </td>
		 </tr> 
		 <?php endforeach; ?>
		</table> 
	<?php endif;?>
	</div>
</body>
</html>