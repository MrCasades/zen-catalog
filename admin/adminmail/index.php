<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Добавление комментария*/
if (isset ($_GET['addmessage']))
{
	$title = 'Форма обратной связи | imagoz.ru';//Данные тега <title>
	$headMain = 'Форма обратной связи';
	$robots = 'noindex, follow';
	$descr = 'Форма добавления сообщений';
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
	
	if (isset($_SESSION['loggIn']))
	{
		$authorMessage = authorLogin ($_SESSION['email'], $_SESSION['password']);//возвращает имя автора
		
		include 'addmessageform.html.php';
		exit();
	}	
	
	else
	{
		$title = 'Форма обратной связи | imagoz.ru';//Данные тега <title>
		$headMain = 'Форма обратной связи';
		$robots = 'noindex, follow';
		$descr = '';
		$commentError = '<a href="//'.MAIN_URL.'/admin/registration/?log">Авторизируйтесь</a> в системе или <a href="//'.MAIN_URL.'/admin/registration/?reg">зарегестрируйтесь</a> для добавления сообщения!';//Вывод сообщения в случае невхода в систему
		
		include 'messagefail.html.php';
		exit();
	}	
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
	
	/*Отправка сообщений (тест)*/
	
	$titleMessage = 'Вы отправили сообщение по форме обратной связи "'. $_POST['messagetitle']."'";
	$mailMessage = 'Вы отправили сообщение: "'. $_POST['message'].'"';
		
	toEmail_1($titleMessage, $mailMessage);//отправка письма
	
	header ('Location: //'.MAIN_URL);//перенаправление обратно в контроллер index.php
	exit();		
}