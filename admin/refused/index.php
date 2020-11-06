<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Отклонённые материалы';//Данные тега <title>
$headMain = 'Отклонённые материалы';
$robots = 'noindex, nofollow';
$descr = 'В данном разделе выводятся материалы которые были отклонены от публикации';
$scriptJScode = '<script src="script.js"></script>';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка формы входа*/
if (!loggedIn())
{
	include '../login.html.php';
	exit();
}

/*Загрузка сообщения об ошибке входа*/
if (!userRole('Администратор') && !userRole('Автор') && !userRole('Рекламодатель'))
{
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Вывод отклонённых материалов*/

/*возврат ID автора*/
$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора

if (userRole('Автор'))//Для автора
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	/*Вывод новостей*/
	/*Команда SELECT*/

	try
	{
		$sql = 'SELECT newsblock.id, newstitle, author.id AS idauthor, newsdate, authorname, reasonrefusal, idtask FROM newsblock 
		INNER JOIN author ON idauthor = author.id 
		INNER JOIN category ON idcategory = category.id
		WHERE premoderation = "NO" AND refused = "YES" AND idauthor = '.$selectedAuthor.' LIMIT 10';//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода новостей на главной странице ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$newsIn[] =  array ('id' => $row['id'], 'idauthor' => $row['idauthor'], 'newstitle' =>  $row['newstitle'], 'newsdate' =>  $row['newsdate'], 'authorname' =>  $row['authorname'], 'reasonrefusal' =>  $row['reasonrefusal'], 'idtask' =>  $row['idtask']);
	}

	/*Вывод стаей*/
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT posts.id, posttitle, author.id AS idauthor, postdate, authorname, reasonrefusal, idtask FROM posts 
		INNER JOIN author ON idauthor = author.id 
		INNER JOIN category ON idcategory = category.id
		WHERE premoderation = "NO" AND refused = "YES" AND idauthor = '.$selectedAuthor.' LIMIT 10';//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода статей на главной странице ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$posts[] =  array ('id' => $row['id'], 'idauthor' => $row['idauthor'], 'posttitle' =>  $row['posttitle'], 'postdate' =>  $row['postdate'], 'authorname' =>  $row['authorname'], 'reasonrefusal' =>  $row['reasonrefusal'], 'idtask' =>  $row['idtask']);
	}
	
	/*Вывод Промоушен*/
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT promotion.id, promotiontitle, author.id AS idauthor, promotiondate, authorname, reasonrefusal FROM promotion 
		INNER JOIN author ON idauthor = author.id 
		INNER JOIN category ON idcategory = category.id
		WHERE premoderation = "NO" AND refused = "YES" AND idauthor = '.$selectedAuthor.' LIMIT 10';//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода статей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$promotions[] =  array ('id' => $row['id'], 'idauthor' => $row['idauthor'], 'promotiontitle' =>  $row['promotiontitle'], 'promotiondate' =>  $row['promotiondate'], 
								'authorname' =>  $row['authorname'], 'reasonrefusal' =>  $row['reasonrefusal']);
	}

	include 'refused.html.php';
	exit();
}

if (userRole('Рекламодатель'))//Для рекламодателя
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	/*Вывод стаей*/
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT promotion.id, promotiontitle, author.id AS idauthor, promotiondate, authorname, reasonrefusal FROM promotion 
		INNER JOIN author ON idauthor = author.id 
		INNER JOIN category ON idcategory = category.id
		WHERE premoderation = "NO" AND refused = "YES" AND idauthor = '.$selectedAuthor.' LIMIT 10';//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода статей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$promotions[] =  array ('id' => $row['id'], 'idauthor' => $row['idauthor'], 'promotiontitle' =>  $row['promotiontitle'], 'promotiondate' =>  $row['promotiondate'], 
								'authorname' =>  $row['authorname'], 'reasonrefusal' =>  $row['reasonrefusal']);
	}

	include 'refusedpromotion.html.php';
	exit();
}