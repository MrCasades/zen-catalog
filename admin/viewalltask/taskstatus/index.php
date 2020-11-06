<?php
/*Загрузка главного пути*/
include_once '../../../includes/path.inc.php';

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
if ((!userRole('Администратор')) && (!userRole('Автор')))
{	
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Публикация статьи*/

if (isset ($_POST['action']) && $_POST['action'] == 'Взять задание')
{	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, tasktitle FROM task WHERE id = :idtask';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idtask', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации о задании: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Взять задание';//Данные тега <title>
	$headMain = 'Получение задания';
	$robots = 'noindex, nofollow';
	$descr = '';
	$action = 'taskyes';
	$taskYes = 'Вы хотите взять задание ';
	$tasktitle = $row['tasktitle'];
	$id = $row['id'];
	$button = 'Да!';
	
	include 'taskstatus.html.php';
}

if (isset ($_GET['taskyes']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Выбор статуса задания для предотвращения повторного взятия*/
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT taskstatus FROM task WHERE id = :idtask';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idtask', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации о задании: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$taskstatus = $row['taskstatus'];
	
	if ($taskstatus == 'YES')//если статус задания 'YES' значит его взял другой пользователь
	{
		$title = 'Ошибка взятия задания';//Данные тега <title>
		$headMain = 'Ошибка взятия задания';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Данное задание взял другой пользователь. Попробуйте выбрать другое';// вывод сообщения об ошибке в переменой $e
		
		include 'error.html.php';
		exit();
	}
	
	else
	{
		/*Скрипт получения задания*/
		
		/*возврат ID автора*/
		$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
		
		/*Подключение к базе данных*/
		include MAIN_FILE . '/includes/db.inc.php';
		
		/*Получение значения счётчика заданий*/
		/*Команда SELECT*/
		try
		{
			$sql = 'SELECT taskcount FROM author WHERE id = '.$selectedAuthor;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора информации числе взятых заданий автора: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();

		$taskcount = (int)$row['taskcount'];
		$takenTasks = 2;//Количество взятых заданий
		
		/*Количество отказанных новостей*/
		try
		{
			$sql = 'SELECT COUNT(*) AS refused_count FROM newsblock WHERE refused = "YES" 
					AND premoderation = "NO" AND idauthor = '.$selectedAuthor;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора информации числе взятых заданий автора: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();
		$refusedNews = $row['refused_count'];
		
		/*Количество отказанных статей*/
		try
		{
			$sql = 'SELECT COUNT(*) AS refused_count FROM posts WHERE refused = "YES" 
					AND premoderation = "NO" AND idauthor = '.$selectedAuthor;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора информации числе взятых заданий автора: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();
		$refusedPosts = $row['refused_count'];
		
		$allRefused = 1;//Все отказанные материалы
		
		/*Количество новостей в премодерации*/
		try
		{
			$sql = 'SELECT COUNT(*) AS premod_count FROM newsblock WHERE refused = "NO" 
					AND premoderation = "NO" AND idauthor = '.$selectedAuthor;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора информации числе взятых заданий автора: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();
		$premodNews = $row['premod_count'];
		
		/*Количество статей в премодерации*/
		try
		{
			$sql = 'SELECT COUNT(*) AS premod_count FROM posts WHERE refused = "NO" 
					AND premoderation = "NO" AND idauthor = '.$selectedAuthor;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора информации числе взятых заданий автора: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();
		$premodPosts = $row['premod_count'];
		
		$allPremod = 3;//Все материалы в премодерации
		
		if ($taskcount > $takenTasks)
		{
			$title = 'Ошибка взятия задания';//Данные тега <title>
			$headMain = 'Ошибка взятия задания';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Вы не можете взять более '.$taskcount.' заданий!';// вывод сообщения об ошибке в переменой $e

			include 'error.html.php';
			exit();
		}
		
		elseif ($refusedNews + $refusedPosts > $allRefused)
		{
			$title = 'Ошибка взятия задания';//Данные тега <title>
			$headMain = 'Ошибка взятия задания';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'У Вас отклонено более '.($refusedNews + $refusedPosts).' заданий! Переделайте их или удалите, чтобы взять новые!';// вывод сообщения об ошибке в переменой $e

			include 'error.html.php';
			exit();
		}
		
		elseif ($premodNews + $premodPosts > $allPremod)
		{
			$title = 'Ошибка взятия задания';//Данные тега <title>
			$headMain = 'Ошибка взятия задания';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'В премодерации находится '.($premodNews + $premodPosts).' материалов. Новое задание можно будет взять только когда на проверке будет оставаться максимум '.$allPremod.' материала!';// вывод сообщения об ошибке в переменой $e

			include 'error.html.php';
			exit();
		}
		
		else
		{
		
			try
			{
				$pdo->beginTransaction();//инициация транзакции
				
				/*Обновить статус задания*/
				$sql = 'UPDATE task SET taskstatus = "YES", 
									idauthor = '.$selectedAuthor.
									', takingdate = SYSDATE()
									WHERE id = :idtask';
				$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
				$s -> bindValue(':idtask', $_POST['id']);//отправка значения
				$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
				
				/*Обновить счётчик заданий автора*/
				$sql = 'UPDATE author SET taskcount =  taskcount + 1 WHERE id = '.$selectedAuthor;
				$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
				$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
				
				$pdo->commit();//подтверждение транзакции
			}

			catch (PDOException $e)
			{
				$pdo->rollBack();//отмена транзакции
				$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
				$headMain = 'Ошибка данных!';
				$robots = 'noindex, nofollow';
				$descr = '';
				$error = 'Ошибка взятия задания '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
				include 'error.html.php';
				exit();
			}
		}
	}
	
	$title = 'Задание успешно взято!';//Данные тега <title>
	$headMain = 'Задание успешно взято!';
	$robots = 'noindex, nofollow';
	$descr = '';
	
	include 'tasksucc.html.php';
	exit();
}		

/*Отказ от задания*/
if (isset ($_POST['action']) && $_POST['action'] == 'Отказаться')
{	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, tasktitle FROM task WHERE id = :idtask';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idtask', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации о задании: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Отказаться от задания';//Данные тега <title>
	$headMain = 'Отказаться от задания';
	$robots = 'noindex, nofollow';
	$descr = '';
	$action = 'taskno';
	$taskYes = 'Вы хотите отказаться от задания ';
	$tasktitle = $row['tasktitle'];
	$id = $row['id'];
	$button = 'Да!';
	
	include 'taskstatus.html.php';
}

if (isset ($_GET['taskno']))
{
	/*Скрипт отказа от задания*/
		
	/*возврат ID автора*/
	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$pdo->beginTransaction();//инициация транзакции
				
		/*Обновить статус задания*/
		$sql = 'UPDATE task SET taskstatus = "NO", 
								idauthor = '.$selectedAuthor.
								' WHERE id = :idtask';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idtask', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
				
		/*Обновить счётчик заданий автора*/
		$sql = 'UPDATE author SET taskcount =  taskcount - 1 WHERE id = '.$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
				
		$pdo->commit();//подтверждение транзакции
	}

	catch (PDOException $e)
	{
		$pdo->rollBack();//отмена транзакции
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка отказа от задания '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: //'.MAIN_URL.'/admin/viewallauthortask/'); //перенаправление обратно в контроллер index.php
	exit();
}