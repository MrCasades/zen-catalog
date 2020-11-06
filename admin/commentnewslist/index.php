<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Каталог комментариев к новостям | imagoz.ru';//Данные тега <title>
$headMain = 'Все комментарии к новостям';
$robots = 'noindex, follow';
$descr = 'В данном разделе отображаются все комментарии к новостям портала';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Определение нахождения пользователя в системе*/
if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Постраничный вывод информации*/
		
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
$onPage = 10;// количество статей на страницу
$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Вывод комментариев к новостям*/
/*Команда SELECT*/

try
{
	$sql = 'SELECT newsblock.id AS newsid, comments.id AS commentsid, author.id AS authorid, avatar, newstitle, authorname, comment, 
					commentdate, subcommentcount 
					FROM comments 
					INNER JOIN author ON idauthor = author.id 
					INNER JOIN newsblock ON idnews = newsblock.id 
					ORDER BY commentdate DESC LIMIT '.$shift.' ,'.$onPage;//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода комментариев ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$newsComments[] =  array ('id' => $row['commentsid'], 'idauthor' => $row['authorid'], 'text' => $row['comment'], 'newstitle' =>  $row['newstitle'], 
						'date' =>  $row['commentdate'], 'idnews' =>  $row['newsid'], 'authorname' =>  $row['authorname'], 'avatar' =>  $row['avatar'],
						'subcommentcount' =>  $row['subcommentcount'] );
}

/*Определение количества статей*/
try
{
	$sql = "SELECT count(*) AS all_articles FROM comments WHERE idnews IS NOT NULL";
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка подсчёта комментариев ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}
	
foreach ($result as $row)
{
	$numPosts[] = array('all_articles' => $row['all_articles']);
}
	
$countPosts = $row["all_articles"];
$pagesCount = ceil($countPosts / $onPage);

include 'viewcomments.html.php';
exit();