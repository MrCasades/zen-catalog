<?php 

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка кнопки добавления статьи*/
if (!isset($_SESSION['loggIn']) || (!userRole('Администратор')) && (!userRole('Автор')) && (!userRole('Рекламодатель') && (!userRole('Супер-автор'))))//Если не выполнен вход в систему или роль не администратор
{
	$messageTitle = '';
	$messageDate = '';
	$messageText = '';	
	$idnews = '';
	$firstTags = '';
	$lastTags = '';
	$scoreTitle = '';
	$payForms = '';
	$addPost = '';
	$forAuthors = '';
	if (empty ($superUser)) $superUser = '';//если нет значения у переменной, отсутствует ранг супер-автора
}

elseif (userRole('Администратор'))
{
	/*Подсчёт количества непрочитанных сообщений обратной связи*/
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = "SELECT count(*) AS unread FROM adminmail WHERE viewcount = 0 AND adminnews = 'NO'";
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта новостей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$unread = $row['unread'];
	
	/*Вывод новостей порталаЫ*/
	if (!isset($_GET['idadminnews']))
	{
		try
		{
			$sql = 'SELECT * FROM adminmail WHERE adminnews = "YES" ORDER BY id DESC LIMIT 1';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка вывода новости администрации ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();

		$messageTitle = $row['messagetitle'];
		$messageDate = $row['messagedate'];
		$messageText = $row['message'];
		$idnews = $row['id'];

		$firstTags = '<div class = "post">
							  <div class = "posttitle" align="center"><strong>
								Сообщение администрации | Дата:  '.$messageDate.'</strong> 
							  </div>
								<p><h6 align="center">'.$messageTitle.'</h6></p>';
	
		$lastTags = '<p><a href="//'.MAIN_URL.'/admin/adminmail/viewadminnews/viewnews/?idadminnews='.$idnews.'" class="btn btn-primary btn-sm">Подробнее</a> | 
								   <a href="//'.MAIN_URL.'/admin/adminmail/viewadminnews/" class="btn btn-info btn-sm">Все сообщения</a> </p>
					 </div>';
	}
	
	else
	{
		$firstTags = '';
		$messageText = '';
		$lastTags = '';
	}
	
	/*Подсчёт количества материалов в премодерации и заявки на выплату*/
	try
	{
		$pdo->beginTransaction();//инициация транзакции
		
		$sql = "SELECT count(*) AS mypremodpost FROM posts WHERE premoderation = 'NO' AND refused = 'NO'";
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$premodPosts = $row['mypremodpost'];//статьи в премодерации
		
		$sql = "SELECT count(*) AS mypremodnews FROM newsblock WHERE premoderation = 'NO' AND refused = 'NO'";
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$premodNews = $row['mypremodnews'];//новости в премодерации
		
		$sql = "SELECT count(*) AS mypremodpromotion FROM promotion WHERE premoderation = 'NO' AND refused = 'NO'";
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$premodPromotion = $row['mypremodpromotion'];//новости в премодерации
		
		$sql = "SELECT count(*) AS payments FROM payments WHERE paymentstatus = 'NO'";
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$paymentsCount = $row['payments'];//новости в премодерации
		
		$pdo->commit();//подтверждение транзакции
	}

	catch (PDOException $e)
	{
		$pdo->rollBack();//отмена транзакции
		
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта материалов ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	/*Вывод размера счёта автора*/
	try
	{
		$sql = "SELECT score FROM author WHERE id = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта новостей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
		
	$scoreTitle = 'Размер счёта: <span id = "score">'.round($row['score'], 2, PHP_ROUND_HALF_DOWN).'</span>';//размер счёта
	
	$payForms = '<div align = "center"><form action = "//'.MAIN_URL.'/admin/payment/" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$selectedAuthor.'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Пополнить счёт">
								</div>
							</form></div>';
	
	$addPost = "<p align='center'><a href='//".MAIN_URL."/admin/addupdpost/?add' class='btn btn-primary btn-sm'>Добавить статью</a> | 
	<a href='//".MAIN_URL."/admin/addupdnews/?add' class='btn btn-primary btn-sm'>Добавить новость</a> |
	<a href='//".MAIN_URL."/admin/addupdpromotion/?add' class='btn btn-primary btn-sm'>Добавить промоушен</a> |
	<a href='//".MAIN_URL."/admin/viewalltask/' class='btn btn-primary btn-sm'>Получить задание</a> |
	<a href='//".MAIN_URL."/admin/addtask/?add' class='btn btn-primary btn-sm'>Добавить задание</a> |
	<a href='//".MAIN_URL."/admin/viewallauthortask/' class='btn btn-primary btn-sm'>Мои задания</a> |
	<a href='//".MAIN_URL."/admin/' class='btn btn-primary btn-sm'>Редактирование списков</a> |</p>
	
	<p align='center'><a href='//".MAIN_URL."/admin/premoderation/?posts' class='btn btn-primary btn-sm'>Премодерация статей (".$premodPosts.")</a> |
	<a href='//".MAIN_URL."/admin/premoderation/?news' class='btn btn-primary btn-sm'>Премодерация новостей (".$premodNews.")</a> | 
	<a href='//".MAIN_URL."/admin/premoderation/?promotion' class='btn btn-primary btn-sm'>Премодерация промоушена (".$premodPromotion.")</a> | 
	<a href='//".MAIN_URL."/admin/payment/viewallpayments/' class='btn btn-primary btn-sm'>Заявки на выплату (".$paymentsCount.")</a> |
	<a href='//".MAIN_URL."/admin/refused/' class='btn btn-danger btn-sm'>Отклонённые задания</a> |
	<a href='//".MAIN_URL."/admin/adminmail/viewallmessages/' class='btn btn-primary btn-sm'>Обратная связь (".$unread.")</a> |</p> 
	
	<p align='center'><a href='//".MAIN_URL."/viewallzenposts/' class='btn btn-primary btn-sm'>Каталог Дзен-статей</a></p>";
	
	$forAuthors = '';
	if (empty ($superUser)) $superUser = '';//если нет значения у переменной, отсутствует ранг супер-автора
}

elseif (userRole('Администратор') || userRole('Автор'))
{
	/*Если присвоен ранг супер-автора*/
	if (userRole('Супер-автор')) $superUser = "<a href='//".MAIN_URL."/admin/superuserpanel/' class='btn btn-danger btn-sm'>
												<strong>МЕНЮ СУПЕР-АВТОРА</strong></a>";
	
	/*Счётчики*/
	/*возврат ID автора*/
	
	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Подсчёт количества заданий*/
	try
	{
		$sql = "SELECT count(*) AS mytasks FROM task WHERE taskstatus = 'YES' AND readystatus = 'NO' AND idauthor = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта новостей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$myTasks = $row['mytasks'];
	
	/*Подсчёт количества материалов в премодерации*/
	try
	{
		$pdo->beginTransaction();//инициация транзакции
		
		$sql = "SELECT count(*) AS mypremodpost FROM posts WHERE premoderation = 'NO' AND refused = 'NO' AND idauthor = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$premodPosts = $row['mypremodpost'];//статьи в премодерации
		
		$sql = "SELECT count(*) AS mypremodnews FROM newsblock WHERE premoderation = 'NO' AND refused = 'NO' AND idauthor = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$premodNews = $row['mypremodnews'];//новости в премодерации
		
		$pdo->commit();//подтверждение транзакции
	}

	catch (PDOException $e)
	{
		$pdo->rollBack();//отмена транзакции
		
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта материалов ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
		
	$allPosts = $premodPosts + $premodNews;//общее количество
	
	/*Подсчёт количества отклонённых материалов*/
	try
	{
		$pdo->beginTransaction();//инициация транзакции
		
		$sql = "SELECT count(*) AS myrefusedpost FROM posts WHERE refused = 'YES' AND idauthor = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$refusedPosts = $row['myrefusedpost'];//отклонённые статьи
		
		$sql = "SELECT count(*) AS myrefusednews FROM newsblock WHERE refused = 'YES' AND idauthor = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$row = $s -> fetch();
		
		$refusedNews = $row['myrefusednews'];//отклонённые новости
		
		$pdo->commit();//подтверждение транзакции
	}

	catch (PDOException $e)
	{
		$pdo->rollBack();//отмена транзакции
		
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта материалов ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
		
	$allRefused = $refusedPosts + $refusedNews;//общее количество
	
	/*Вывод новостей порталаЫ*/
	if (!isset($_GET['idadminnews']))
	{
		try
		{
			$sql = 'SELECT * FROM adminmail WHERE adminnews = "YES" ORDER BY id DESC LIMIT 1';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}
//".MAIN_URL."
		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка вывода новости администрации ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();

		$messageTitle = $row['messagetitle'];
		$messageDate = $row['messagedate'];
		$messageText = $row['message'];
		$idnews = $row['id'];

		$firstTags = '<div class = "post">
							  <div class = "posttitle" align="center"><strong>
								Сообщение администрации | Дата:  '.$messageDate.'</strong> 
							  </div>
								<p><h6 align="center">'.$messageTitle.'</h6></p>';
	
		$lastTags = '<p><a href="//'.MAIN_URL.'/admin/adminmail/viewnews/?idadminnews='.$idnews.'" class="btn btn-primary btn-sm">Подробнее</a> | 
								   <a href="//'.MAIN_URL.'/admin/adminmail/viewadminnews/" class="btn btn-info btn-sm">Все сообщения</a> </p>
					 </div>';
	}
	
	else
	{
		$firstTags = '';
		$messageText = '';
		$lastTags = '';
	}
	
	/*Вывод размера счёта автора*/
	try
	{
		$sql = "SELECT score FROM author WHERE id = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта новостей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
		
	$scoreTitle = 'Размер счёта: <span id = "score">'.round($row['score'], 2, PHP_ROUND_HALF_DOWN).'</span>';//размер счёта
	
	$payForms = '<div align = "center"><form action = "//'.MAIN_URL.'/admin/payment/" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$selectedAuthor.'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Пополнить счёт">
								</div>
							</form></div>';
	
	$addPost = "| <a href='//".MAIN_URL."/admin/addupdpromotion/?add' class='btn btn-warning btn-sm'><strong>НАПИСАТЬ РЕКЛАМНУЮ СТАТЬЮ</strong></a> |	
				<a href='//".MAIN_URL."/admin/viewallauthortask/#bottom' class='btn btn-primary btn-sm'><strong>МОИ ЗАДАНИЯ (".$myTasks.")</strong></a> |	
				<a href='//".MAIN_URL."/admin/viewalltask/#bottom' class='btn btn-info btn-sm'><strong>ПОЛУЧИТЬ ЗАДАНИЕ</strong></a> |
				<a href='//".MAIN_URL."/admin/authorpremoderation/#bottom' class='btn btn-success btn-sm'><strong>В ПРЕМОДЕРАЦИИ (".$allPosts.")</strong></a> |
				<a href='//".MAIN_URL."/admin/refused/#bottom' class='btn btn-danger btn-sm'><strong>ОТКЛОНЁННЫЕ МАТЕРИАЛЫ (".$allRefused.")</strong></a> |";
	$forAuthors = "<strong><a href='//".MAIN_URL."/admin/adminmail/viewnews/?idadminnews=17'>ДЛЯ АВТОРОВ! ОБЯЗАТЕЛЬНО К ПРОЧТЕНИЮ!</a></strong>";
	if (empty ($superUser)) $superUser = '';//если нет значения у переменной, отсутствует ранг супер-автора
}

elseif (userRole('Администратор') || userRole('Автор') || userRole('Рекламодатель'))
{
	/*Счётчики*/
	/*возврат ID автора*/
	
	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	
	/*Подсчёт количества материалов в премодерации*/
	try
	{
		$sql = "SELECT count(*) AS mypremodpromotions FROM promotion WHERE premoderation = 'NO' AND refused = 'NO' AND idauthor = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$pdo->rollBack();//отмена транзакции
		
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта материалов ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
		
	$row = $s -> fetch();
		
	$mypremodPromotions = $row['mypremodpromotions'];//статьи в премодерации
	
	/*Подсчёт количества взаимных действий*/
	try
	{
		$sql = "SELECT count(*) AS tasks FROM task WHERE readystatus = 'NO' AND idauthor = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта материалов ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
		
	$row = $s -> fetch();
		
	$taskCount = $row['tasks'];//статьи в премодерации
	
	/*Вывод размера счёта автора*/
	try
	{
		$sql = "SELECT score FROM author WHERE id = ".$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта новостей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
		
	$scoreTitle = 'Размер счёта: <span id = "score">'.round($row['score'], 2, PHP_ROUND_HALF_DOWN).'</span>';//размер счёта
	
	$payForms = '<div align = "center"><form action = "//'.MAIN_URL.'/admin/payment/" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$selectedAuthor.'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Пополнить счёт">
								</div>
							</form></div>';
	
	
	$messageTitle = '';
	$messageDate = '';
	$messageText = '';	
	$idnews = '';
	$firstTags = '';
	$lastTags = '';
	
	$addPost = "| <a href='//".MAIN_URL."/admin/addupdpromotion/?add' class='btn btn-warning btn-sm'><strong>ДОБАВИТЬ КАНАЛ В КАТАЛОГ</strong></a> |	
				<a href='//".MAIN_URL."/viewallfavourites/#bottom' class='btn btn-success btn-sm'><strong>ИЗБРАННЫЕ МАТЕРИАЛЫ</strong></a> |
				<a href='//".MAIN_URL."/admin/viewallauthortask/#bottom' class='btn btn-danger btn-sm'><strong>ВЗАИМНЫЕ ДЕЙСТВИЯ <span id = 'countcolor_2'>".$taskCount."</span></strong></a> |";
	$forAuthors = '';
	
	if (empty ($superUser)) $superUser = '';//если нет значения у переменной, отсутствует ранг супер-автора
}
