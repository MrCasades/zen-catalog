<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Список тематик';//Данные тега <title>
$headMain = 'Тематики в базе данных';
$robots = 'noindex, nofollow';
$descr = '';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка формы входа*/
if (!loggedIn())
{
	include '../login.html.php';
	exit();
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

/*Добавление информации в таблицу meta*/

$padgeTitle = 'Новая тематика';// Переменные для формы "Тематика"
$action = 'addform';
$metaname = '';
$idmeta = '';
$button = 'Добавить тематику';

if (isset ($_GET['addform']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'INSERT INTO meta SET metaname = :metaname';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':metaname', $_POST['metaname']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления информации в таблицу "Тематика" '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	

/*Редактирование информации в таблице meta*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Upd'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
	$sql = 'SELECT * FROM meta WHERE id = :idmeta';
	$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
	$s -> bindValue(':idmeta', $_POST['idmeta']);//отправка значения
	$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Error select : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
	}
	
	$row = $s -> fetch();
	
	$padgeTitle = 'Редактировать тематику';// Переменные для формы "Тематика"
	$action = 'editform';
	$metaname = $row['metaname'];
	$idmeta = $row['id'];
	$button = 'Обновить информацию об тематике';
	
	include 'form.html.php';
	exit();
	
}
	/*Команда UPDATE*/
if (isset ($_GET['editform']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE meta SET metaname = :metaname WHERE id = :idmeta';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmeta', $_POST['idmeta']);//отправка значения
		$s -> bindValue(':metaname', $_POST['metaname']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Error Update: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	

/*Удаление из таблици meta*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Del'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	/*Удаление записи об тематике*/
	{
		$sql = 'DELETE FROM metapost WHERE idmeta = :idmeta';// - псевдопеременная получающая значение из формы ВОЗМОЖНО ОШИБКА ЗДЕСЬ!
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmeta', $_POST['idmeta']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	
	{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка удаления '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
	}
	
	try
	
	{
		$sql = 'DELETE FROM meta WHERE id = :idmeta';// - псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmeta', $_POST['idmeta']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка удаления '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Команда SELECT*/
try
{
	$sql = 'SELECT * FROM meta';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора тематики: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$metas[] =  array ('id' => $row['id'], 'metaname' => $row['metaname']);
}

include 'meta.html.php';
