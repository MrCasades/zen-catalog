<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Премодерация материалов';//Данные тега <title>
$headMain = 'Материалы в премодерации';
$robots = 'noindex, nofollow';
$descr = 'Вданном разделе выводятся материалы для премодерации';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

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

if (isset ($_GET['news']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	/*Вывод новостей*/
	/*Команда SELECT*/

	try
	{
		$sql = 'SELECT newsblock.id, newstitle, newsdate, authorname, email FROM newsblock INNER JOIN author 
				ON idauthor = author.id WHERE premoderation = "NO" AND refused = "NO" LIMIT 20';//Вверху самое последнее значение
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
		$newsIn[] =  array ('id' => $row['id'], 'newstitle' =>  $row['newstitle'], 'newsdate' =>  $row['newsdate'], 
							'authorname' =>  $row['authorname'], 'email' =>  $row['email']);
	}
	
	
	include 'premoderationnews.html.php';
	exit();

}

if (isset ($_GET['posts']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Вывод стаей*/
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT posts.id, posttitle, postdate, authorname, email FROM posts INNER JOIN author 
		ON idauthor = author.id WHERE premoderation = "NO" AND refused = "NO" LIMIT 20';//Вверху самое последнее значение
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
		$posts[] =  array ('id' => $row['id'], 'posttitle' =>  $row['posttitle'], 'postdate' =>  $row['postdate'], 
							'authorname' =>  $row['authorname'], 'email' =>  $row['email']);
	}

	include 'premoderationposts.html.php';
	exit();
}

if (isset ($_GET['promotion']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Вывод стаей*/
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT promotion.id, promotiontitle, promotiondate, authorname, email FROM promotion INNER JOIN author 
		ON idauthor = author.id WHERE premoderation = "NO" AND refused = "NO" LIMIT 20';//Вверху самое последнее значение
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
		$promotions[] =  array ('id' => $row['id'], 'promotiontitle' =>  $row['promotiontitle'], 'promotiondate' =>  $row['promotiondate'], 
							'authorname' =>  $row['authorname'], 'email' =>  $row['email']);
	}

	include 'premoderationpromotion.html.php';
	exit();
}