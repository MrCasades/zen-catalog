<?php
/*Загрузка главного пути*/
include_once '../../../includes/path.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Загрузка сообщения об ошибке входа*/
if (!userRole('Администратор'))
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Загрузка содержимого статьи*/
if (isset ($_GET['promotion']))
{
	$idPost = $_GET['promotion'];
	
	@session_start();//Открытие сессии для сохранения id статьи
	
	$_SESSION['idpost'] = $idPost;
	$select = 'SELECT id, promotion, promotiontitle, imghead, videoyoutube, imgalt, promotiondate FROM promotion WHERE premoderation = "NO" AND refused = "NO" AND id = ';

	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = $select.$idPost;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода содержимого статьи ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();

	$articleId = $row['id'];
	$articleText = $row['promotion'];
	$imgHead = $row['imghead'];
	$imgAlt = $row['imgalt'];
	$date = $row['promotiondate'];
	$articleTitle = $row['promotiontitle'];
	
	$title = $row['promotiontitle'];//Данные тега <title>
	$headMain = $row['promotiontitle'];	
	$robots = 'noindex, nofollow';
	$descr = '';
	
	/*Вывод видео в статью*/
	if ((isset($row['videoyoutube'])) && ($row['videoyoutube'] != ''))
	{
		$video = '<iframe width="460px" height="290px" src="'.$row['videoyoutube'].'" frameborder="0" allowfullscreen></iframe>';
	}
	
	else
	{
		$video = '';
	}
	
	/*Вывод тематик(тегов)*/
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT meta.id, metaname FROM promotion 
				INNER JOIN metapost ON promotion.id = idpromotion 
				INNER JOIN meta ON meta.id = idmeta 
				WHERE promotion.id = '.$idPost;//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода списка тегов ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$metas[] =  array ('id' => $row['id'], 'metaname' => $row['metaname']);
	}
	
	/*Вывод кнопок "Обновить" | "Удалить" | "Опубликовать"*/
	
	if ((isset($_SESSION['loggIn'])) && (userRole('Администратор')))
	{
		$delAndUpd = "<form action = '../../../admin/addupdpromotion/' method = 'post'>
			
						Действия с материалом:
						<input type = 'hidden' name = 'id' value = '".$_SESSION['idpost']."'>
						<input type = 'submit' name = 'action' value = 'Upd' class='btn btn-primary btn-sm'>
						<input type = 'submit' name = 'action' value = 'Del' class='btn btn-primary btn-sm'>
					  </form>";
		$premoderation = "<form action = '../../../admin/premoderation/promotionpremoderationstatus/' method = 'post'>
			
						Статус публикации:
						<input type = 'hidden' name = 'id' value = '".$_SESSION['idpost']."'>
						<input type = 'submit' name = 'action' value = 'Опубликовать' class='btn btn-primary btn-sm'>
						<input type = 'submit' name = 'action' value = 'Отклонить' class='btn btn-danger btn-sm'>
					  </form>";			  
	}
	
	include 'viewpremodpromotion.html.php';
}