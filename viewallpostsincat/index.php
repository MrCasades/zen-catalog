<?php 
/*Загрузка главного пути*/
include_once '../includes/path.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';
	
if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Вывод статей по категориям*/

if (isset ($_GET['id']))
{
		
	$idCategory = $_GET['id'];
	$selectPost = 'SELECT posts.id AS postid, author.id AS authorid, post, posttitle, imghead, imgalt, postdate, authorname, category.id AS categoryid, categoryname FROM posts 
			INNER JOIN author ON idauthor = author.id 
			INNER JOIN category ON idcategory = category.id 
			WHERE premoderation = "YES" AND zenpost = "NO" AND idcategory = ';
	$limit = ' ORDER BY postdate DESC LIMIT ';		
			
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Постраничный вывод информации*/
		
	$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
	$onPage = 15;// количество статей на страницу
	$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу
	
	try
	{
		$sql = $selectPost.$idCategory.$limit.$shift.' ,'.$onPage;
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора статей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
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
	
	/*Если страница отсутствует. Ошибка 404*/
	if (!$row)
	{
		header ('Location: ../page-not-found/');//перенаправление обратно в контроллер index.php
		exit();	
	}
	
	if (isset ($row['categoryname']))		//если статьи в рубрике есть!	
	{
		$title = $row['categoryname'].' | imagoz.ru';//Данные тега <title>
		$headMain = 'Все статьи рубрики '. '"'.$row['categoryname'].'"';
		$robots = 'noindex, follow';
		$descr = 'В данном разделе размещаются все статьи из рубрики '.$row['categoryname'];
	}
	
	else		//если статьи отсутствуют!
	{
		$title = 'Статьи в рубрике отсутствуют';//Данные тега <title>
		$headMain = 'Статьи в рубрике отсутствуют';
		$robots = 'noindex, follow';
		$descr = '';
	}
	
	/*Определение количества статей*/
	try
	{
		$sql = "SELECT count(*) AS all_articles FROM posts
				INNER JOIN category
				ON idcategory = category.id
				INNER JOIN author
				ON idauthor = author.id 
				WHERE premoderation = 'YES' AND zenpost = 'NO' AND idcategory = ".$idCategory;
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
	
	include 'postsincat.html.php';
	exit();
}