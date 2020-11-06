<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Список заданий | imagoz.ru';//Данные тега <title>
$headMain = 'Все задания';
$robots = 'noindex, nofollow';
$descr = 'В данном разделе размещаются список всех технических заданий';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Определение нахождения пользователя в системе*/
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
if ((!userRole('Администратор')) && (!userRole('Автор')))
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Постраничный вывод информации*/
		
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
$onPage = 10;// количество статей на страницу
$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу

/*Вывод заданий*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT task.id AS taskid, description, author.id AS authorid, tasktitle, taskdate, authorname, tasktype.id AS tasktypeid, rangname, tasktypename FROM task 
			INNER JOIN author ON idcreator = author.id 
			INNER JOIN tasktype ON idtasktype = tasktype.id 
			INNER JOIN rang ON task.idrang = rang.id
			WHERE taskstatus = "NO" ORDER BY task.id DESC LIMIT '.$shift.' ,'.$onPage;//Вверху самое последнее значение
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
	$tasks[] =  array ('id' => $row['taskid'], 'idauthor' => $row['authorid'], 'text' => $row['description'], 'tasktitle' =>  $row['tasktitle'],
						'taskdate' =>  $row['taskdate'], 'authorname' =>  $row['authorname'], 
						'tasktypename' =>  $row['tasktypename'], 'tasktypeid' => $row['tasktypeid'], 'rangname' => $row['rangname']);
}

/*Определение количества заданий*/
try
{
	$sql = "SELECT count(*) AS all_articles FROM task WHERE taskstatus = 'NO'";
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка подсчёта заданий ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}
	
foreach ($result as $row)
{
	$numPosts[] = array('all_articles' => $row['all_articles']);
}
	
$countPosts = $row["all_articles"];
$pagesCount = ceil($countPosts / $onPage);

include 'alltask.html.php';
exit();

