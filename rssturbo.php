<?php


/*Подключение к базе данных*/
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

/*Вывод  новостей*/
/*Команда SELECT*/

try
{
	$sql = 'SELECT newsblock.id AS newsid, author.id AS idauthor, news, newstitle, imghead, description, imgalt, newsdate, authorname, category.id AS categoryid, categoryname FROM newsblock 
			INNER JOIN author ON idauthor = author.id 
			INNER JOIN category ON idcategory = category.id 
			WHERE premoderation = "YES" ORDER BY newsdate DESC LIMIT 500';//Вверху самое последнее значение
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
	$newsMain[] =  array ('id' => $row['newsid'], 'idauthor' => $row['idauthor'], 'textnews' => $row['news'], 'newstitle' =>  $row['newstitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
						'newsdate' =>  $row['newsdate'], 'authorname' =>  $row['authorname'], 
						'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid'], 'description' => $row['description']);
}

/*Вывод стаей*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT posts.id AS postid, author.id AS idauthor, post, posttitle, imghead, imgalt, postdate, authorname, category.id AS categoryid, categoryname FROM posts 
			INNER JOIN author ON idauthor = author.id 
			INNER JOIN category ON idcategory = category.id 
			WHERE premoderation = "YES" ORDER BY postdate DESC LIMIT 500';//Вверху самое последнее значение
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
	$posts[] =  array ('id' => $row['postid'], 'idauthor' => $row['idauthor'], 'text' => $row['post'], 'posttitle' =>  $row['posttitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
						'postdate' =>  $row['postdate'], 'authorname' =>  $row['authorname'], 
						'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid']);
}

include 'rssturbo.html.php';
exit();
