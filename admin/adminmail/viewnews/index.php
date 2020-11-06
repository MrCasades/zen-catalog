<?php
/*Загрузка главного пути*/
include_once '../../../includes/path.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка формы входа*/
if (!loggedIn())
{
	include '../login.html.php';
	exit();
}

/*Загрузка сообщения об ошибке входа*/
if ((!userRole('Администратор')) && (!userRole('Автор')))
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Загрузка содержимого статьи*/
if (isset ($_GET['idadminnews']))
{
	$idMessage = $_GET['idadminnews'];
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Обновить счётчик*/
	
	try
	{	
		/*Обновить счётчик просмотров*/
		$sql = 'UPDATE adminmail SET viewcount =  viewcount + 1 WHERE id = '.$idMessage;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления счётчика '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT adminmail.id AS adminmailid, author.id AS idauthor, message, messagetitle, messagedate, email, authorname FROM adminmail 
				INNER JOIN author ON idauthor = author.id 
				WHERE adminmail.id = '.$idMessage;//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода сообщений формы обратной связи ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$messages[] =  array ('id' => $row['adminmailid'], 'idauthor' => $row['idauthor'], 'text' => $row['message'], 'messagetitle' =>  $row['messagetitle'], 
							  'messagedate' =>  $row['messagedate'], 'authorname' =>  $row['authorname'], 'email' =>  $row['email']);
	}
	
	/*Вывод кнопок удалить / обновить*/
if (userRole('Администратор'))
{

	$delAndUpdNews = '<form action = "../../../admin/addadminnews/" method = "post">
							<input type = "hidden" name = "id" value = "'.$row['adminmailid'].'">
							<input type = "submit" name = "action" value = "Del" class="btn btn-danger btn-sm">
							<input type = "submit" name = "action" value = "Upd" class="btn btn-info btn-sm">
						</form>';
}

else
{
	$delAndUpdNews = '';
}

	
	$title = 'Сообщение для администрации';//Данные тега <title>
	$headMain = $row['messagetitle'];
	$robots = 'noindex, nofollow';
	$descr = '';
	
	include 'viewnews.html.php';
	exit();

}