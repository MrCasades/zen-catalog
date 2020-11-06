<?php
/*Вывод информации для формы поиска*/

	$title = 'Поиск';
	$headMain = 'Поиск статей';
	$robots = 'noindex, nofollow';
	$descr = '';
	
	/*Загрузка функций для формы входа*/
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/access.inc.php';

	/*Загрузка формы входа*/
	if (!loggedIn())
	{
		include '../login.html.php';
		exit();
	}

	/*Загрузка сообщения об ошибке входа*/
	if (!userRole('Администратор'))
	{
		$error = 'Доступ запрещен';
		include '../accessfail.html.php';
		exit();
	}

	/*Подключение к базе данных*/
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	
	try
	{
		$result = $pdo -> query ('SELECT id, authorname FROM author');
	}
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода author '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$authors[] = array('id' => $row['id'], 'authorname' => $row['authorname']);
	}
	
	try
	{
		$result = $pdo -> query ('SELECT id, categoryname FROM category');
	}
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода category '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$categorys[] = array('id' => $row['id'], 'categoryname' => $row['categoryname']);
	}
	
	try
	{
		$result = $pdo -> query ('SELECT id, metaname FROM meta');
	}
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода meta '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$metas[] = array('id' => $row['id'], 'metaname' => $row['metaname']);
	}
	
	include 'searchform.html.php';
	
	/*Формирование запроса SELECT*/
	
	if (isset($_GET['action']) && ($_GET['action']) == 'search')
	{
		/*Подключение к базе данных*/
		include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
		
		/*Переменные для выражения SELECT*/
		$select = 'SELECT posts.id, post, posttitle';
		$from = ' FROM posts';
		$where = ' WHERE TRUE';
		
		/*Выбор автора*/
		$forSearch = array();//массив заполнения запроса
		
		if ($_GET['author'] != '')//Если выбран автор
		{
			$where .= " AND idauthor = :idauthor";
			$forSearch[':idauthor'] = $_GET['author'];
		}
		
		/*Выбор рубрики*/
		if ($_GET['category'] != '')//Если выбрана рубрика
		{
			$where .= " AND idcategory = :idcategory";
			$forSearch[':idcategory'] = $_GET['category'];
		}
		
		/*Выбор тематики*/
		if ($_GET['meta'] != '')//Если выбрана тематика
		{
			$from .= ' INNER JOIN metapost ON posts.id = idpost';
			$where .= " AND metapost.idmeta = :idmeta";
			$forSearch[':idmeta'] = $_GET['meta'];
		}
		
		/*Поле строки*/
		if ($_GET['text'] != '')//Если выбрана какая-то строка
		{
			$where .= " AND post LIKE :post";
			$forSearch[':post'] = '%'. $_GET['text']. '%';	
		}
		
		/*Объеденение переменных в запрос*/
		try
		{
			$sql = $select.$from.$where;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute($forSearch);// метод дает инструкцию PDO отправить запрос MySQL. Т. к. массив $forSearch хранит значение всех псевдопеременных 
										// не нужно указывать их по отдельности с помощью bindValue									
		}

		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка поиска : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}
		
		foreach ($s as $row)
		{
			$posts[] = array('id' => $row['id'], 'text' => $row['post'], 'title' => $row ['posttitle']);
		}
		
		include 'searchpost.html.php';
		exit();
	}
	
	include 'add_and_upd_post.inc.php';//загрузка скрипта добавления и редактирования статей
	