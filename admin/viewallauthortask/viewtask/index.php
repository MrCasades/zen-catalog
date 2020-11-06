<?php
/*Загрузка главного пути*/
include_once '../../../includes/path.inc.php';

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
if ((!userRole('Администратор')) && (!userRole('Рекламодатель')))
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Загрузка содержимого статьи*/
if (isset ($_GET['id']))
{
	$idTask = $_GET['id'];
		
	$select = 'SELECT task.id AS taskid, task.description AS taskdeskription, promotion.www AS promotionwww, idpromotionfrom, author.id AS authorid, promotion.id AS promotionid, promotiontitle, taskdate FROM task 
			   INNER JOIN author ON idcreator = author.id 
			   INNER JOIN promotion ON idpromotionfrom = promotion.id  
			   WHERE readystatus = "NO" AND task.id = ';

	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = $select.$idTask;
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода содержимого задания ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$tasks[] =  array ('id' => $row['taskid'], 'idauthor' => $row['authorid'], 'text' => $row['taskdeskription'],
							'taskdate' =>  $row['taskdate'], 'promotionwww' =>  $row['promotionwww'], 'promotionid' => $row['promotionid'], 'promotiontitle' => $row['promotiontitle']);
	}	

	$title = !empty ($row) ? 'Взаимное действие от канала "'.$row['promotiontitle'].'"' : 'Действие выполнено!' ;//Данные тега <title>
	$headMain = !empty ($row) ? 'Взаимное действие от канала "'.$row['promotiontitle'].'"' : 'Действие выполнено!' ;	
	$robots = 'noindex, nofollow';
	$descr = '';
	$jQuery = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>';
	$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
	
	include 'task.html.php';
}
