<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

else
{
	include '../login.html.php';
	exit();
}

/*Загрузка сообщения об ошибке входа*/
if (!userRole('Администратор'))
{
	$title = 'Ошибка доступа';//Данные тега <title>
	$headMain = 'Ошибка доступа';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'В данный раздел доступ запрещён!';
	include '../accessfail.html.php';
	exit();
}

/*Добавление комментария*/
if (isset ($_GET['addmessage']))
{
	$title = 'Форма добавления новостей администрации | imagoz.ru';//Данные тега <title>
	$headMain = 'Форма добавления новостей администрации';
	$robots = 'noindex, follow';
	$descr = 'Форма добавления новостей администрации';
	$padgeTitle = 'Новое сообщение';// Переменные для формы "Новое сообщение"
	$action = 'addform';
	$messagetitle = '';
	$text = '';
	$idauthor = '';
	$id = '';
	$button = 'Добавить сообщение';
	$errorForm = '';
	$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
		
	$authorMessage = authorLogin ($_SESSION['email'], $_SESSION['password']);//возвращает имя автора
		
	include 'addupdadminnews.html.php';
	exit();
}

/*Обновление информации о задании*/
if (isset ($_POST['action']) && $_POST['action'] == 'Upd')
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, message, messagetitle FROM adminmail WHERE id = :idmessage';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmessage', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора задания: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Обновление новости';//Данные тега <title>
	$headMain = 'Обновление новости';
	$robots = 'noindex, nofollow';
	$descr = '';
	$action = 'editform';
	$messagetitle = $row['messagetitle'];
	$text = $row['message'];
	$id = $row['id'];
	$button = 'Обновить новость';
	$errorForm = '';
	$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
	
	$authorMessage = authorLogin ($_SESSION['email'], $_SESSION['password']);//возвращает имя автора
	
	include 'addupdadminnews.html.php';
	exit();
}

/*команда INSERT  - добавление сообщения в базу данных*/
if (isset($_GET['addform']))//Если есть переменная addform выводится форма
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
		
	/*Возвращение id автора*/
	
	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
		
	try
	{
		$sql = 'INSERT INTO adminmail SET 
			messagetitle = :messagetitle,
			message  = :message,
			messagedate = SYSDATE(),
			idauthor = '.$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':messagetitle', $_POST['messagetitle']);//отправка значения
		$s -> bindValue(':message', $_POST['message']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления информации '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$idpost_ind = $pdo->lastInsertId();//возврат последнего автоинкрементного ID
	
	try
	{
		$sql = 'UPDATE adminmail SET 
			adminnews = "YES"
			WHERE id = :idnews';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idnews', $idpost_ind );//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления информации news'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}	
		
	header ('Location: /account/?id='.$selectedAuthor);//перенаправление обратно в контроллер index.php
	exit();		
}

if (isset($_GET['editform']))//Если есть переменная editform выводится форма
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	if (($_POST['message'] == '') || ($_POST['messagetitle'] == ''))
	{
		$title = 'В форме есть незаполненные поля!';//Данные тега <title>
		$headMain = 'В форме есть незаполненные поля!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Введите недостающую информацию';
		
		include 'error.html.php';
		exit();
	}
	
	try
	{
		$sql = 'UPDATE adminmail SET 
				messagetitle = :messagetitle,	
				message = :message
				WHERE id = :idmessage';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmessage', $_POST['id']);//отправка значения
		$s -> bindValue(':messagetitle', $_POST['messagetitle']);//отправка значения
		$s -> bindValue(':message', $_POST['message']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления информации adminmail'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: //'.MAIN_URL.'/admin/adminmail/viewadminnews/');//перенаправление обратно в контроллер index.php
	exit();
}

/*Удаление из таблицы adminmail*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Del'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	
	{
		$sql = 'DELETE FROM adminmail WHERE id = :idmessage';// - псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmessage', $_POST['id']);//отправка значения
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
	
	header ('Location: //'.MAIN_URL.'/admin/adminmail/viewadminnews/');//перенаправление обратно в контроллер index.php
	exit();
}	