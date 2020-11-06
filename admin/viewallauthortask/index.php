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

/*Загрузка сообщения об ошибке входа*/
if ((!userRole('Администратор')) && (!userRole('Рекламодатель')))
{	
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Вывод всех предложенных действий*/

/*возврат ID автора*/
$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
		
/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';
	
/*Команда SELECT. Выбор предложенных АВТОРУ взаимных действий*/
try
{
	$sql = 'SELECT task.id AS taskid, task.description AS taskdeskription, idpromotionfrom, author.id AS authorid, taskdate, authorname, promotion.id AS promotionid, promotiontitle FROM author 
			INNER JOIN task ON idauthor = author.id 
			INNER JOIN promotion ON idpromotionfrom = promotion.id
			WHERE author.id = '.$selectedAuthor.' AND readystatus = "NO" ORDER BY task.id DESC' ;//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода заданий ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$tasksForMe[] =  array ('id' => $row['taskid'], 'idauthor' => $row['authorid'], 'text' => $row['taskdeskription'], 
						'taskdate' =>  $row['taskdate'], 'authorname' =>  $row['authorname'], 
						'promotionid' => $row['promotionid'], 
						'promotiontitle' => $row['promotiontitle']);
}	

/*Команда SELECT. Выбор предложенных АВТОРОМ взаимных действий*/
try
{
	$sql = 'SELECT task.id AS taskid, task.description AS taskdeskription, idpromotionfrom, author.id AS authorid, taskdate, authorname, promotion.id AS promotionid, promotiontitle FROM author 
			INNER JOIN task ON idcreator = author.id 
			INNER JOIN promotion ON idpromotionfrom = promotion.id
			WHERE author.id = '.$selectedAuthor.' AND readystatus = "NO" ORDER BY task.id DESC' ;//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода заданий ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$myTasks[] =  array ('id' => $row['taskid'], 'idauthor' => $row['authorid'], 'text' => $row['taskdeskription'], 
						'taskdate' =>  $row['taskdate'], 'authorname' =>  $row['authorname'], 
						'promotionid' => $row['promotionid'], 
						'promotiontitle' => $row['promotiontitle']);
}	

/*Возврат имени автора для заголовка*/
if (isset($row['authorname'])) 
{
	$authorName = $row['authorname'];//имя автора для заголовка
}

else
{
	$authorName = '';
}

/*Выбор имени создателя задания*/
/*
if (isset($tasks))
{
	try
	{
		$sql = 'SELECT authorname AS creator FROM author WHERE id = '.$row['idcreator'];
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

	$creator = $row['creator'];
}
*/

$title = 'Список взаимных действий | imagoz.ru';//Данные тега <title>
$headMain = 'Все  взаимные действия';
$robots = 'noindex, nofollow';
$descr = 'В данном разделе размещается список всех взаимных действий.';

include 'allauthortask.html.php';
//exit();