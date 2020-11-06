<?php 
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';
	
if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Вывод всех статей автора*/

if (isset ($_GET['id']))
{
	$idAuthor = $_GET['id'];
	$selectPosts = 'SELECT posts.id AS postid, author.id AS authorid, post, posttitle, postdate, imghead, imgalt, idauthor, idcategory, category.id AS categoryid, categoryname, authorname, imghead FROM posts
			INNER JOIN author
			ON author.id = idauthor
			INNER JOIN category
			ON idcategory = category.id
			WHERE premoderation = "YES" AND zenpost = "NO" AND idauthor = ';
	$limit = ' ORDER BY postdate DESC LIMIT ';
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Постраничный вывод информации*/
		
	$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
	$onPage = 15;// количество статей на страницу
	$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу
	
	try
	{
		$sql = $selectPosts.$idAuthor.$limit.$shift.', '.$onPage;
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора всех статей автора ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$posts[] =  array ('id' => $row['postid'], 'idauthor' => $row['authorid'], 'text' => $row['post'], 'posttitle' =>  $row['posttitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
						'postdate' =>  $row['postdate'], 'authorname' =>  $row['authorname'], 
						'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid']);
	}
	
	if (isset ($row['post']))		//если статьи в рубрике есть!	
	{
		$title = 'Все статьи автора ' . $row['authorname'].' | imagoz.ru';//Данные тега <title>
		$headMain = 'Все статьи автора ' . $row['authorname'];
		$robots = 'noindex, follow';
		$descr = 'В данном разделе публикуются все статьи автора '.$row['authorname'];
	}
	
	else		//если статьи отсутствуют!
	{
		$title = 'Статьи отсутствуют | ImagozCMS';//Данные тега <title>
		$headMain = 'Статьи отсутствуют';
		$robots = 'noindex, follow';
		$descr = '';
	}	
	
	/*Определение количества статей*/
	try
	{
		$sql = "SELECT count(*) AS all_articles FROM posts
				WHERE premoderation = 'YES' AND zenpost = 'NO' AND idauthor = ". $idAuthor;
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта статей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$numPosts[] = array('all_articles' => $row['all_articles']);
	}
	
	$countPosts = $row["all_articles"];
	$pagesCount = ceil($countPosts / $onPage);
	
	include 'allauthorpost.html.php';
	exit();
}