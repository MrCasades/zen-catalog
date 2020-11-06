<?php
/*Загрузка главного пути*/
include_once './includes/path.inc.php';


/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Вывод стаей*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT id, postdate FROM posts WHERE premoderation = "YES" AND zenpost = "NO" ORDER BY postdate DESC';//Вверху самое последнее значение
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
	$posts[] =  array ('id' => $row['id'], 'postdate' => $row['postdate']);
}

/*Вывод промоушена*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT id, promotiondate FROM promotion WHERE premoderation = "YES" ORDER BY promotiondate DESC';//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода промоушена ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$promotions[] =  array ('id' => $row['id'], 'promotiondate' => $row['promotiondate']);
}

/*Вывод аккаунтов*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT id, regdate FROM author ORDER BY id DESC';//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода авторов ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$authors[] =  array ('id' => $row['id'], 'regdate' => $row['regdate']);
}

include 'sitemap.html.php';
exit();

