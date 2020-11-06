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
	$title = 'Ошибка добавления сообщения';//Данные тега <title>
	$headMain = 'Нельзя добавить сообщение';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Для того, чтобы отправлять сообщения пользователю Вам нужно <a href="//'.MAIN_URL.'/admin/registration/?log">авторизироваться</a> в системе или пройти 
						 <a href="//'.MAIN_URL.'/admin/registration/?reg">регистрацию</a>!';
	include 'error.html.php';
	exit();
}


/*Добавление сообщения*/
if (isset ($_POST['action']) && $_POST['action'] == 'Написать сообщение')
{
	$title = 'Написать сообщение';//Данные тега <title>
	$headMain = 'Написать сообщение';
	$robots = 'noindex, nofollow';
	$descr = '';
	$action = 'addform';
	$text = '';
	$idauthor = '';
	$idto = $_POST['idto'];
	$id = '';
	$button = 'Отправить';
	$errorForm = '';
	$authorPost = authorLogin ($_SESSION['email'], $_SESSION['password']);//возвращает имя автора
	$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS


	include 'addupdform.html.php';
	exit();	
}

/*команда INSERT  - добавление в базу данных*/
if (isset($_GET['addform']))//Если есть переменная addform выводится форма
{
	$fileNameScript = 'formess-'. time();//имя файла новости/статьи
	$filePathScript = '/formessages/';//папка с изображениями для новости/статьи
	
	/*Загрузка функций для формы входа*/
	require_once MAIN_FILE . '/includes/access.inc.php';
	
	/*Загрузка скрипта добавления файла*/
	include MAIN_FILE . '/includes/uploadfile.inc.php';
		
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Возвращение id автора*/
	
	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
	
	if ($_POST['text'] == '')
	{
		$title = 'Написать сообщение';//Данные тега <title>
		$headMain = 'Написать сообщение';
		$robots = 'noindex, nofollow';
		$descr = '';
		$action = 'addform';
		$text = '';
		$idauthor = '';
		$idto = $_POST('id');
		$id = '';
		$button = 'Отправить';
		$errorForm = '';
		$authorPost = authorLogin ($_SESSION['email'], $_SESSION['password']);//возвращает имя автора
		$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
		
		@session_start();//Открытие сессии для сохранения id автора
	
		$_SESSION['text'] = $_POST['text'];
		
		$text = $_SESSION['text'];
	
		include 'addupdform.html.php';
		exit();
	}
	
	/*INSERT - добавление информации в базу данных*/
	
	try
	{
		$pdo->beginTransaction();//инициация транзакции
		
		$sql = 'INSERT INTO mainmessages SET 
			mainmessage = :mainmessage,
			mainmessagedate = SYSDATE(),
			imghead = '.'"'.$fileName.'"'.', '.
			'idfrom = '.$selectedAuthor.','.
			'idto = :idto';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':mainmessage', $_POST['text']);//отправка значения
		$s -> bindValue(':idto', $_POST['idto']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$idmessage_ind = $pdo->lastInsertId();//метод возвращает число, которое MySQL назначил последней автомнкрементной записи (INSERT INTO mainmessages - в данном случае)
		
		$sql = 'SELECT count(idfrom) AS idfrom_count, count(idto) AS idto_count FROM mainmessages 
																				WHERE (idfrom = '.$selectedAuthor.' AND idto = :idto) OR 
																				(idto = '.$selectedAuthor.' AND idfrom = :idto)';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idto', $_POST['idto']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$idFromCount = $row['idfrom_count'];
		$idtoCount = $row['idto_count'];

		$pdo->commit();//подтверждение транзакции		
	}
	
	catch (PDOException $e)
	{		
		$pdo->rollBack();//отмена транзакции
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления сообщения '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	/*Присвоение статуса первого сообщения*/
	if (($idFromCount == 1) && ($idtoCount == 1))
	{
		try
		{
			$sql = 'UPDATE mainmessages SET firstmessage  = "YES" WHERE id = '.$idmessage_ind;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка обновления статуса первого сообщения '.$e -> getMessage();// вывод сообщения об ошибке в переменой $e;// вывод сообщения об ошибке в переменой $e;// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();		
		}
	}
	
	$title = 'Ваше сообщение отправлено';//Данные тега <title>
	$headMain = 'Ваше сообщение отправлено';
	$robots = 'noindex, nofollow';
	$descr = '';
	
	include 'messagesucc.html.php';
	exit();
}

/*DELETE - удаление материала*/

if (isset ($_POST['action']) && $_POST['action'] == 'X')
{	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT firstmessage FROM mainmessages WHERE id = :idmainmessage';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmainmessage', $_POST['idmessage']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error select book: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$firstMess = $row['firstmessage'];
	
	if ($firstMess == 'YES')
	{
		$title = 'Ошибка удаления сообщения';//Данные тега <title>
		$headMain = 'Ошибка удаления сообщения';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Нельзя удалить первое сообщение в диалоге!';// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, idfrom, idto, imghead, firstmessage FROM mainmessages WHERE id = :idmainmessage';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmainmessage', $_POST['idmessage']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error select book: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Удаление сообщения';//Данные тега <title>
	$headMain = 'Удаление сообщения';
	$robots = 'noindex, nofollow';
	$descr = '';
	$action = 'delete';
	$id = $row['id'];
	$button = 'Удалить';
	
	include 'delete.html.php';
}

if (isset ($_GET['delete']))
{
	/*Удаление изображения заголовка*/
	$fileName = $_SESSION['imghead'];
	$delFile = MAIN_FILE . '/formessages/'.$fileName;//путь к файлу для удаления
	unlink($delFile);//удаление файла
		
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	try
	{
		$sql = 'DELETE FROM mainmessages WHERE id = :idmainmessage';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmainmessage', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка удаления информации mainmessages'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: //'.MAIN_URL);//перенаправление обратно в контроллер index.php
	exit();
}	
