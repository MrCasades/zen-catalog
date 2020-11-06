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

/*Добавление информации о задании*/
if (isset($_POST['action']) && ($_POST['action'] = 'ПРЕДЛОЖИТЬ ВЗАИМНОЕ ДЕЙСТВИЕ'))//Если есть переменная add выводится форма
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Подстчёт количеста невыполненных взаимных действий*/
	
	$outstandingTtasks = 2; 
	
	try
	{
		$sql = 'SELECT count(*) AS countTasks FROM task WHERE readystatus = "NO" AND idcreator = '.authorID($_SESSION['email'], $_SESSION['password']);
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта сообщений ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
		
	$countTasks = $row['countTasks'];//счётчик невыполненных действий
	
	/*Количество невыполненных заданий у автора*/
	try
	{
		$sql = 'SELECT count(*) AS countTasksTo FROM task WHERE readystatus = "NO" AND idauthor = '. $_POST['idauthorto'];;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта сообщений ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$countTasksTo = $row['countTasksTo'];//счётчик невыполненных у автора!
	
	/*Проверка на автора статьи*/
	/*
	try
	{
		$sql = 'SELECT idauthor FROM promotion WHERE id = :idpromotion';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpromotion', $_POST['idarticle']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта сообщений ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$idAuthor = $row['idauthor'];
	
	if ($idAuthor == authorID($_SESSION['email'], $_SESSION['password']))
	{
		$title = 'Нельзя предложить действие.';//Данные тега <title>
		$headMain = 'Нельзя предложить действие.';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Вы не можете предложить взаимное действие самому себе!';
		include 'error.html.php';
		exit();
	}
	*/
	if ($countTasks > $outstandingTtasks)
	{
		$title = 'Количество предложенных заданий превышено!';//Данные тега <title>
		$headMain = 'Количество предложенных заданий превышено!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Количество предложенных взаимных действий не может быть больше '.($outstandingTtasks - 1).'! При необходимости удалите действия, которые давно никто не выполняет!';
		include 'error.html.php';
		exit();
	}
	
	if ($countTasksTo > $outstandingTtasks)
	{
		$title = 'Нельзя предложить действие';//Данные тега <title>
		$headMain = 'Нельзя предложить действие';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Количество предложенных взаимных действий предложенных этому автору превышает '.($outstandingTtasks - 1).'!';
		include 'error.html.php';
		exit();
	}
	
	/*Вывод информации для формы добавления*/
	/*Список типов*/
	$select = 'SELECT id, promotiontitle FROM promotion WHERE 
				idauthor = '.authorID($_SESSION['email'], $_SESSION['password']).
				' AND premoderation = "YES"';
	
	try
	{
		$result = $pdo -> query ($select);
	}
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода tasktype '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$authorPromotions[] = array('idpromotion' => $row['id'], 'promotiontitle' => $row['promotiontitle']);
	}
	
	/*Если не добавлено ни одного канала*/
	
	if (empty ($authorPromotions))
	{
		$title = 'Добавьте канал!';//Данные тега <title>
		$headMain = 'Добавьте канал!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Для предложения совместных действий у вас должен быть хотябы 1 одобренный канал в каталоге!';
		
		include 'error.html.php';
		exit();
	}
	
	$errorForm = '';
	$title = 'Предложить совместое действие';//Данные тега <title>
	$headMain = 'Предложить совместое действие';
	$robots = 'noindex, nofollow';
	$descr = '';
	$action = 'addform';
	$tasktitle = '';
	$description = '';
	$idtasktype = '';
	$idrang = 1;
	$idPromotion = $_POST['idarticle'];
	$authorId = $_POST['idauthorto'];
	$id = '';
	$button = 'Добавить действие';
	$authorPost = authorLogin ($_SESSION['email'], $_SESSION['password']);//возвращает имя автора
	$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
	
	@session_start();//Открытие сессии для сохранения id автора
	
	$_SESSION['authorname'] = $authorPost;

	include 'addtask.html.php';
	exit();
	
}

/*команда INSERT  - добавление в базу данных*/
if (isset($_GET['addform']))//Если есть переменная addform выводится форма

{
	/*Загрузка функций для формы входа*/
	require_once MAIN_FILE . '/includes/access.inc.php';
	
	/*Возвращение id автора*/
	
	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
	
	if (($_POST['description'] == '') || ($_POST['idpromotionfrom'] == ''))
	{
		$title = 'В форме есть незаполненные поля!';//Данные тега <title>
		$headMain = 'В форме есть незаполненные поля!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Введите недостающую информацию';
		
		include 'error.html.php';
		exit();
	}
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'INSERT INTO task SET 
			description = :description,		
			taskdate = SYSDATE(),
			idcreator = :idcreator,
			idauthor = :idauthor,
			idpromotion = :idpromotion,
			idpromotionfrom = :idpromotionfrom';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcreator', $selectedAuthor);//отправка значения
		$s -> bindValue(':description', $_POST['description']);//отправка значения
		$s -> bindValue(':idauthor', $_POST['idauthorto']);//отправка значения
		$s -> bindValue(':idpromotion', $_POST['idarticle']);//отправка значения
		$s -> bindValue(':idpromotionfrom', $_POST['idpromotionfrom']);//отправка значения
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
	
	
	$title = 'Действие добавлено';//Данные тега <title>
	$headMain = 'Действие добавлено';
	$robots = 'noindex, nofollow';
	$descr = '';
	
	include 'tasksucc.html.php';
	exit();
}

/*DELETE - удаление материала*/

if (isset ($_POST['action']) && ($_POST['action'] == 'X'))
{	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id FROM task WHERE id = :idtask';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idtask', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора id и заголовка task : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Удаление задания';//Данные тега <title>
	$headMain = 'Удаление задания';
	$robots = 'noindex, nofollow';
	$descr = '';
	$action = 'delete';
	$posttitle = '111';
	$id = $row['id'];
	$button = 'Удалить';
	
	include 'delete.html.php';
}

if (isset ($_GET['delete']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'DELETE FROM task WHERE id = :idtask';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idtask', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка удаления информации newsblock '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: //'.MAIN_URL);//перенаправление обратно в контроллер index.php
	exit();
}	