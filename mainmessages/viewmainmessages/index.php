<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

$title = 'Диалог';//Данные тега <title>
$headMain = 'Диалог';
$robots = 'noindex, nofollow';
$descr = '';

/*Загрузка формы входа*/
if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

else
{
	include MAIN_FILE .'/admin/login.html.php';
	exit();
}

/*Возвращение id автора для вызова функции изменения пароля*/

$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора

$padgeTitle = 'Новая категория';// Переменные для формы "Категория"
$action = 'addform';
$button = 'Ответить';
$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
	
/*Имя и текст для формы*/
$authorPost = authorLogin($_SESSION['email'], $_SESSION['password']);
$text = '';

if (isset($_GET['addform']))//Если есть переменная addform выводится форма
{
	$fileNameScript = 'formess-'. time();//имя файла новости/статьи
	$filePathScript = 'formessages/';//папка с изображениями для сообщений
	
	/*Загрузка функций для формы входа*/
	require_once MAIN_FILE . '/includes/access.inc.php';
	
	/*Загрузка скрипта добавления файла*/
	include MAIN_FILE . '/includes/uploadfile.inc.php';
		
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*INSERT - добавление информации в базу данных и списание средств со счёта*/
	
	try
	{
		$sql = 'INSERT INTO mainmessages SET 
			mainmessage = :mainmessage,
			mainmessagedate = SYSDATE(),
			imghead = '.'"'.$fileName.'"'.', '.
			'idfrom = '.$selectedAuthor.',
			idto = '.$_SESSION['toDialog'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':mainmessage', $_POST['text']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL	
		
		$idmessage_ind = $pdo->lastInsertId();//метод возвращает число, которое MySQL назначил последней автомнкрементной записи (INSERT INTO mainmessages - в данном случае)
		
		$sql = 'SELECT count(idfrom) AS idfrom_count, count(idto) AS idto_count FROM mainmessages 
																				WHERE idfrom = '.$selectedAuthor.' AND idto = '.$_SESSION['toDialog'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$idFromCount = $row['idfrom_count'];
		$idtoCount = $row['idto_count'];
	}
	
	catch (PDOException $e)
	{		
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления сообщения '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
			
	header ('Location: ../viewmainmessages/?id='.$_SESSION['toDialog'].'#bottom');//перенаправление обратно в контроллер index.php
	exit();
}

/*Удаление из таблици category*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Del'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	
	{
		$sql = 'DELETE FROM category WHERE id = :idcategory';// - псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcategory', $_POST['idcategory']);//отправка значения
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

/*Вывод диалога*/
if (isset($_GET['id']))
{
	/*Возвращение id автора для вызова функции изменения пароля*/

	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
	
	$toDialog = $_GET['id'];//id первого сообщения
	
	@session_start();//Открытие сессии для сохранения id автора
	
	$_SESSION['toDialog'] = $toDialog;
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT mmess.mainmessage, mmess.id AS mainmessageid, mmess.mainmessagedate, mmess.imghead, authorfrom.authorname AS afr, authorfrom.id AS idfr,  authorto.id AS idt, authorto.authorname AS ato 
													FROM mainmessages AS mmess 
													INNER JOIN author AS authorfrom ON mmess.idfrom = authorfrom.id 
													INNER JOIN author AS authorto ON mmess.idto = authorto.id 		
		WHERE (idfrom = '.$selectedAuthor.' AND idto = '.$toDialog.') OR (idto = '.$selectedAuthor.' AND idfrom = '.$toDialog.') ORDER BY mainmessagedate';
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора сообщений: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$mainmessages[] =  array ('mainmessage' => $row['mainmessage'], 'idmess' => $row['mainmessageid'], 'mainmessagedate' => $row['mainmessagedate'], 'imghead' => $row['imghead'],
								 'authorfrom' => $row['afr'], 'authorto' => $row['ato'], 'idfrom' => $row['idfr'], 'idto' => $row['idt'],);
	}
	
	if ($row['ato'] != $selectedAuthor)
	{
		/*Обновить статус непрочитанных сообщений*/
		try
		{
			$sql = 'UPDATE mainmessages SET unread = "NO"		
			WHERE idto = '.$selectedAuthor.' AND idfrom = '.$toDialog;
			$result = $pdo->query($sql);
		}

		catch (PDOException $e)
		{
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка обновления статуса непрочитанных сообщений: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}
	}
	
	include 'viewmainmessages.html.php';
	exit();
}
