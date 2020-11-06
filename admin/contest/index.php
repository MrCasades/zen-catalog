<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Управление конкурсом';//Данные тега <title>
$headMain = 'Управление конкурсом';
$robots = 'noindex, nofollow';
$descr = '';
$scriptJScode = '<script src="script.js"></script>';//добавить код JS

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

/*Добавление информации в таблицу contest*/
	
$padgeTitle = 'Новый конкурс';// Переменные для формы "Категория"
$action = 'addform';
$contestname = '';
$votingpoints = '';
$commentpoints = '';
$favouritespoints = '';
$idcontest = '';
$button = 'Добавить конкурс';

if (isset ($_GET['addform']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'INSERT INTO contest SET contestname = :contestname,
												votingpoints = :votingpoints,
												commentpoints = :commentpoints,
												favouritespoints = :favouritespoints';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':contestname', $_POST['contestname']);//отправка значения
		$s -> bindValue(':votingpoints', $_POST['votingpoints']);//отправка значения
		$s -> bindValue(':commentpoints', $_POST['commentpoints']);//отправка значения
		$s -> bindValue(':favouritespoints', $_POST['favouritespoints']);//отправка значения
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

/*Редактирование информации в таблице contest*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Upd'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, contestname, votingpoints, commentpoints, favouritespoints FROM contest WHERE id = :idcontest';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcontest', $_POST['idcontest']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error select contest: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$padgeTitle = 'Редактировать конкурс';// Переменные для формы "Рубрика"
	$action = 'editform';
	$contestname = $row['contestname'];
	$votingpoints = $row['votingpoints'];
	$commentpoints = $row['commentpoints'];
	$favouritespoints = $row['favouritespoints'];
	$idcontest = $row['id'];
	$button = 'Обновить конкурс';
	
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
		$sql = 'UPDATE contest SET contestname = :contestname, 
										  votingpoints = :votingpoints,
										  commentpoints = :commentpoints,
										  favouritespoints = :favouritespoints
										  WHERE id = :idcontest';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcontest', $_POST['idcontest']);//отправка значения
		$s -> bindValue(':contestname', $_POST['contestname']);//отправка значения
		$s -> bindValue(':votingpoints', $_POST['votingpoints']);//отправка значения
		$s -> bindValue(':commentpoints', $_POST['commentpoints']);//отправка значения
		$s -> bindValue(':favouritespoints', $_POST['favouritespoints']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error Update cotest: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	

/*Удаление из таблици contest*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Del'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	
	{
		$sql = 'DELETE FROM contest WHERE id = :idcontest';// - псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcontest', $_POST['idcontest']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка удаления contest'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	

/*Включить - выключить конкурс*/
/*Включение*/
if (isset ($_POST['action']) && ($_POST['action'] == 'ON'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE contest SET conteston = "YES" WHERE id = :idcontest';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcontest', $_POST['idcontest']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error Update coteston: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}

/*Выключение*/
if (isset ($_POST['action']) && ($_POST['action'] == 'OFF'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE contest SET conteston = "NO" WHERE id = :idcontest';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcontest', $_POST['idcontest']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error Update coteston: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}

/*Вывод панели участников*/
/*Включение*/
if (isset ($_POST['action']) && ($_POST['action'] == 'CP_ON'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE contest SET contestpanel = "YES" WHERE id = :idcontest';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcontest', $_POST['idcontest']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error Update contestpanel: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}

/*Выключение*/
if (isset ($_POST['action']) && ($_POST['action'] == 'CP_OFF'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE contest SET contestpanel = "NO" WHERE id = :idcontest';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcontest', $_POST['idcontest']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error Update contestpanel: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}

/*Обнулить конкурсные баллы*/
if (isset ($_POST['action']) && ($_POST['action'] == 'Обнулить баллы'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE author SET contestscore = 0 WHERE contestscore > 0';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error Update coteston: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
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
	$sql = 'SELECT * FROM contest';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора contest: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$contests[] =  array ('id' => $row['id'], 'contestname' => $row['contestname'], 'votingpoints' => $row['votingpoints'],
						  'commentpoints' => $row['commentpoints'], 'conteston' => $row['conteston'], 'contestpanel' => $row['contestpanel'],
						  'favouritespoints' => $row['favouritespoints']);
}

include 'contest.html.php';
exit();

