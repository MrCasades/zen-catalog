<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Список авторов и администраторов';//Данные тега <title>
$headMain = 'Зарегестрированные авторы';
$robots = 'noindex, nofollow';
$descr = '';

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
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Вывод списка авторов, редакторов и администраторов портала*/

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Формирование списка авторов*/
	
try
{
	$result = $pdo->query('SELECT author.id AS authorid, authorname, email, score, countposts, bonus, taskcount FROM role 
						  INNER JOIN authorrole ON role.id = idrole
				          INNER JOIN author ON idauthor = author.id
						  WHERE role.id = "Автор" OR role.id = "Администратор"');
}
	
catch (PDOException $e)
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка формирования списка ролей '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}
	
foreach ($result as $row)
{
	$authors[] = array('id' => $row['authorid'], 'authorname' => $row['authorname'], 'email' => $row['email'], 'score' => $row['score']
					  	, 'countposts' => $row['countposts'], 'bonus' => $row['bonus'], 'taskcount' => $row['taskcount']);
}
	
include 'authors.html.php';
exit();