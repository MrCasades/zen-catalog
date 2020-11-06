<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Цена промоушена';//Данные тега <title>
$headMain = 'Цена промоушена';
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

/*Добавление информации в таблицу promotionprice*/
	
$padgeTitle = 'Новая категория цены';// Переменные для формы "Категория"
$action = 'addform';
$pricename = '';
$promotionprice = '';
$idpromotionprice = '';
$button = 'Добавить категорию цены';

if (isset ($_GET['addform']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'INSERT INTO promotionprice SET pricename = :pricename,
												promotionprice = :promotionprice';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':pricename', $_POST['pricename']);//отправка значения
		$s -> bindValue(':promotionprice', $_POST['promotionprice']);//отправка значения
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

/*Редактирование информации в таблице category*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Upd'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, pricename, promotionprice FROM promotionprice WHERE id = :idpromotionprice';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpromotionprice', $_POST['idpromotionprice']);//отправка значения
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
	
	$padgeTitle = 'Редактировать цену промоушена';// Переменные для формы "Рубрика"
	$action = 'editform';
	$pricename = $row['pricename'];
	$promotionprice = $row['promotionprice'];
	$idpromotionprice = $row['id'];
	$button = 'Обновить информацию о цене промоушена';
	
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
		$sql = 'UPDATE promotionprice SET pricename = :pricename, 
										  promotionprice = :promotionprice
										  WHERE id = :idpromotionprice';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpromotionprice', $_POST['idpromotionprice']);//отправка значения
		$s -> bindValue(':pricename', $_POST['pricename']);//отправка значения
		$s -> bindValue(':promotionprice', $_POST['promotionprice']);//отправка значения
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

/*Удаление из таблици promotionprice*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Del'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	
	{
		$sql = 'DELETE FROM promotionprice WHERE id = :idpromotionprice';// - псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpromotionprice', $_POST['idpromotionprice']);//отправка значения
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
	$sql = 'SELECT * FROM promotionprice';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора promotionprice: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$promotionprices[] =  array ('id' => $row['id'], 'pricename' => $row['pricename'], 'promotionprice' => $row['promotionprice']);
}

include 'promotionprice.html.php';
exit();
