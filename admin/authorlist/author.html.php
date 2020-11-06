<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>

<div class = "maincont">
	<p><a href = '?add' class="btn btn-primary btn-sm">Добавить автора</a></p>
	<hr/>
	<p><a href = '../../admin/authors/'>Список авторов портала</a></p>
	<hr/>
		<table>
		<tr><th>Название</th><th>Возможные действия</th></tr>
		 <?php foreach ($authors as $author): ?> 
			<tr> 
			  <form action = " " method = "post">
			   <div>
				<td><a href="/account/?id=<?php htmlecho($author['idauthor'])?>"><?php htmlecho($author['authorname']);?></a></td>
				<td>
				<input type = "hidden" name = "idauthor" value = "<?php echo $author['idauthor']; ?>">
				<input type = "submit" name = "action" value = "Upd" class="btn btn-primary btn-sm">
				<input type = "submit" name = "action" value = "Del" class="btn btn-primary btn-sm" id = "delauthor" onclick = "return confirm('Вы уверены?')">
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

