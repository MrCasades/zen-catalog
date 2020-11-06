<?php

/*Загрузка функций в шаблон*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/func.inc.php';

/*Добавление информации о статье*/
if (isset($_GET['add']))//Если есть переменная add выводится форма
{
	$padgeTitle = 'Новая статья';// Переменные для формы "Новая статья"
	$action = 'addform';
	$posttitle = '';
	$text = '';
	$idauthor = '';
	$idcategory = '';
	//$description = '';
	$id = '';
	$button = 'Добавить статью';
	
	/*Вывод информации для формы добавления*/

	/*Подключение к базе данных*/
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	
	/*Список авторов*/
	try
	{
		$result = $pdo -> query ('SELECT id, authorname FROM author');
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка вывода author '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$authors_1[] = array('idauthor' => $row['id'], 'authorname' => $row['authorname']);
	}
	
	/*Список рубрик*/
	try
	{
		$result = $pdo -> query ('SELECT id, categoryname FROM category');
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка вывода category '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$categorys_1[] = array('idcategory' => $row['id'], 'categoryname' => $row['categoryname']);
	}
	
	/*Список тематик*/
	try
	{
		$result = $pdo -> query ('SELECT id, metaname FROM meta');
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка вывода meta '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$metas_1[] = array('idmeta' => $row['id'], 'metaname' => $row['metaname'], 'selected' => FALSE);
	}
	
	include 'form.html.php';
	exit();
	
}

/*Обновление информации о статье*/
if (isset ($_POST['action']) && $_POST['action'] == 'Upd')
{
	/*Подключение к базе данных*/
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, post, posttitle, idauthor, idcategory FROM posts WHERE id = :idpost';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpost', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$error = 'Error select book: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$padgeTitle = 'Редактировать статьи';// Переменные для формы "Обновление книги"
	$action = 'editform';
	$text = $row['post'];
	$posttitle = $row['posttitle'];
	$idauthor = $row['idauthor'];
	$idcategory = $row['idcategory'];
	//$description = $row['description'];
	$id = $row['id'];
	$button = 'Обновить информацию о статье';
	
	/*Список авторов*/
	try
	{
		$result = $pdo -> query ('SELECT id, authorname FROM author');
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка вывода author '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$authors_1[] = array('idauthor' => $row['id'], 'authorname' => $row['authorname']);
	}
	
	/*Список рубрик*/
	try
	{
		$result = $pdo -> query ('SELECT id, categoryname FROM category');
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка вывода category '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$categorys_1[] = array('idcategory' => $row['id'], 'categoryname' => $row['categoryname']);
	}
	
	/*Статьи по тематикам*/
	try
	{
		$sql = 'SELECT idmeta FROM metapost WHERE idpost = :idpost';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpost', $id);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$error = 'Ошибка вывода metapost ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($s as $row)
	{
		$selectedMeta[] = $row['idmeta'];
	}
	
	/*Список тематик*/
	try
	{
		$result = $pdo -> query ('SELECT id, metaname FROM meta');
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка вывода meta '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$metas_1[] = array('idmeta' => $row['id'],'metaname' => $row['metaname'], 'selected' => in_array($row['id'], $selectedMeta));
	}

	include 'form.html.php';
	exit();
}

/*команда INSERT  - добавление в базу данных*/
if (isset($_GET['addform']))//Если есть переменная addform выводится форма
{
	
	/*Загрузка скрипта фобавления файла*/
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/uploadfile.inc.php';
		
	/*Подключение к базе данных*/
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	
	if (($_POST['author']) && ($_POST['category']) == '')
	{
		$error = 'Один или несколько атрибутов не указаны. Выбирете все.';
		include 'error.html.php';
		exit();
	}
	
	try
	{
		$sql = 'INSERT INTO posts SET 
			post = :post,
			posttitle = :posttitle,	
			postdate = SYSDATE(),
			idauthor = :idauthor,
			idcategory = :idcategory';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':post', $_POST['text']);//отправка значения
		$s -> bindValue(':posttitle', $_POST['posttitle']);//отправка значения
		$s -> bindValue(':idauthor', $_POST['author']);//отправка значения
		$s -> bindValue(':idcategory', $_POST['category']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка добавления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$idpost_ind = $pdo->lastInsertId();//метод возвращает число, которое MySQL назначил последней автомнкрементной записи (INSERT INTO post - в данном случае)

	if (isset ($_POST['metas']))
	{
		try
		{
			$sql = 'INSERT INTO metapost SET 
				idpost = :idpost, 
				idmeta = :idmeta';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной

			foreach	($_POST['metas'] as $idmetas)
			{		
				$s -> bindValue(':idpost', $idpost_ind);//отправка значения
				$s -> bindValue(':idmeta', $idmetas);//отправка значения
				$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			}
		}
		catch (PDOException $e)
		{
			$error = 'Ошибка добавления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}
		
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}

/*UPDATE - обновление информации в базе данных*/

if (isset($_GET['editform']))//Если есть переменная editform выводится форма
{
	/*Подключение к базе данных*/
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	
	if (($_POST['author']) && ($_POST['category']) == '')
	{
		$error = 'Один или несколько атрибутов не указаны. Выбирете все.';
		include 'error.html.php';
		exit();
	}
	
	try
	{
		$sql = 'UPDATE posts SET 
			post = :post,
			posttitle = :posttitle,	
			idauthor = :idauthor,
			idcategory = :idcategory
			WHERE id = :idpost';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpost', $_POST['id']);//отправка значения
		$s -> bindValue(':post', $_POST['text']);//отправка значения
		$s -> bindValue(':posttitle', $_POST['posttitle']);//отправка значения
		$s -> bindValue(':idauthor', $_POST['author']);//отправка значения
		$s -> bindValue(':idcategory', $_POST['category']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка обновления информации post'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	try
	{
		$sql = 'DELETE FROM metapost WHERE idpost = :idpost';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpost', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка удаления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	if (isset ($_POST['metas']))
	{
		try
		{
			$sql = 'INSERT INTO metapost SET 
				idpost = :idpost, 
				idmeta = :idmeta';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной

			foreach	($_POST['metas'] as $idmetas)
			{		
				$s -> bindValue(':idpost', $_POST['id']);//отправка значения
				$s -> bindValue(':idmeta', $idmetas);//отправка значения
				$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			}
		}
		catch (PDOException $e)
		{
			$error = 'Ошибка обновления информации metapost'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}

/*DELETE - удаление книги и записей о ней*/

if (isset ($_POST['action']) && $_POST['action'] == 'Del')
{	
	/*Подключение к базе данных*/
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	try
	{
		$sql = 'DELETE FROM metapost WHERE idpost = :idpost';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpost', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка удаления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	try
	{
		$sql = 'DELETE FROM posts WHERE id = :idpost';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpost', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$error = 'Ошибка удаления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	