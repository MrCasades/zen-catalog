<?php
/*Загрузка главного пути*/
include_once '../../../includes/path.inc.php';

$title = 'Сообщения для администрации';//Данные тега <title>
$headMain = 'Сообщения для администрации';
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
if ((!userRole('Администратор')) && (!userRole('Автор')))
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Удаление из таблицы adminmail*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Del'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	
	{
		$sql = 'DELETE FROM adminmail WHERE id = :idmessage';// - псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idmessage', $_POST['idmessage']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка удаления '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	

/*Вывод всех сообщений*/

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Постраничный вывод информации*/
		
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
$onPage = 10;// количество статей на страницу
$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу

/*Команда SELECT*/
try
{
	$sql = 'SELECT adminmail.id AS adminmailid, author.id AS idauthor, message, messagetitle, messagedate, email, authorname FROM adminmail 
			INNER JOIN author ON idauthor = author.id 
			WHERE adminnews = "NO"
			ORDER BY adminmail.id DESC LIMIT 10';//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода сообщений формы обратной связи ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$messages[] =  array ('id' => $row['adminmailid'], 'idauthor' => $row['idauthor'], 'text' => $row['message'], 'messagetitle' =>  $row['messagetitle'], 
						'messagedate' =>  $row['messagedate'], 'authorname' =>  $row['authorname'], 'email' =>  $row['email']);
}

/*Определение количества сообщений*/
try
{
	$sql = "SELECT count(*) AS all_articles FROM adminmail WHERE adminnews = 'NO'";
	$result = $pdo->query($sql);
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
	
foreach ($result as $row)
{
	$numPosts[] = array('all_articles' => $row['all_articles']);
}
	
$countPosts = $row["all_articles"];
$pagesCount = ceil($countPosts / $onPage);

include 'messages.html.php';
exit();