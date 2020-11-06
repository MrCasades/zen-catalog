<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Список рангов автора';//Данные тега <title>
$headMain = 'Ранги в базе данных';
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

/*Добавление информации в таблицу rang*/

if (isset($_GET['add']))//Если есть переменная add выводится форма
{	
	$padgeTitle = 'Новый ранг';// Переменные для формы "Категория"
	$action = 'addform';
	$rangname = '';
	$lastNumber = '';
	$priceNews = '';
	$pricePost = '';
	$idrang = '';
	$button = 'Добавить ранг';
	
	include 'form.html.php';
	exit();
}

/*Редактирование информации в таблице rang*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Upd'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT * FROM rang WHERE id = :idrang';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idrang', $_POST['idrang']);//отправка значения
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
	
	$padgeTitle = 'Редактировать рубрику';// Переменные для формы "Ранг"
	$action = 'editform';
	$rangname = $row['rangname'];
	$lastNumber = $row['lastnumber'];
	$priceNews = $row['pricenews'];
	$pricePost = $row['pricepost'];
	$idrang = $row['id'];
	$button = 'Обновить информацию об ранге';
	
	include 'form.html.php';
	exit();
	
}

	/*Команда INSERT*/
if (isset ($_GET['addform']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'INSERT INTO rang SET rangname = :rangname, 
									lastnumber = :lastnumber, pricenews = :pricenews, 
									pricepost = :pricepost';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':rangname', $_POST['rangname']);//отправка значения
		$s -> bindValue(':lastnumber', $_POST['lastnumber']);//отправка значения
		$s -> bindValue(':pricenews', $_POST['pricenews']);//отправка значения
		$s -> bindValue(':pricepost', $_POST['pricepost']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка добавления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	

	/*Команда UPDATE*/
if (isset ($_GET['editform']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE rang SET rangname = :rangname, 
									lastnumber = :lastnumber, pricenews = :pricenews, 
									pricepost = :pricepost WHERE id = :id';;// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $_POST['id']);//отправка значения
		$s -> bindValue(':rangname', $_POST['rangname']);//отправка значения
		$s -> bindValue(':lastnumber', $_POST['lastnumber']);//отправка значения
		$s -> bindValue(':pricenews', $_POST['pricenews']);//отправка значения
		$s -> bindValue(':pricepost', $_POST['pricepost']);//отправка значения
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

/*Удаление из таблици rang*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Del'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	
	{
		$sql = 'DELETE FROM rang WHERE id = :idrang';// - псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idrang', $_POST['idrang']);//отправка значения
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
	$sql = 'SELECT * FROM rang';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора ранга: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$rangs[] =  array ('id' => $row['id'], 'rangname' => $row['rangname'], 'lastnumber' => $row['lastnumber'],
						'pricenews' => $row['pricenews'], 'pricepost' => $row['pricepost']);
}

include 'rang.html.php';
exit();

