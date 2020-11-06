<?php
/*Загрузка главного пути*/
include_once '../includes/path.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Загрузка статей по тематике*/
if (isset ($_GET['metaid']))
{
	$idMeta = $_GET['metaid'];
	
	/*Постраничный вывод информации*/
		
	$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
	$onPage = 15;// количество статей на страницу
	$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	try
	{
		$sql = 'SELECT posts.id AS postid, post, posttitle, author.id AS authorid, imghead, imgalt, postdate, authorname, category.id AS categoryid, categoryname, metaname FROM meta 
				INNER JOIN metapost	ON meta.id = idmeta
				INNER JOIN posts ON posts.id = idpost 
				INNER JOIN author ON author.id = idauthor 
				INNER JOIN category ON idcategory = category.id 
				WHERE premoderation = "YES" AND zenpost = "NO" AND meta.id = '.$idMeta.' ORDER BY postdate DESC LIMIT '.$shift.' ,'.$onPage;//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = ' ';
		$error = 'Error table in mainpage' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$metas_1[] =  array ('id' => $row['postid'], 'idauthor' => $row['authorid'], 'text' => $row['post'], 'posttitle' =>  $row['posttitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
							'postdate' =>  $row['postdate'], 'authorname' =>  $row['authorname'], 
							'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid'],
							'metaname' => $row['metaname']);
	}
	
	/*Если страница отсутствует. Ошибка 404*/
	if (!$row)
	{
		header ('Location: ../page-not-found/');//перенаправление обратно в контроллер index.php
		exit();	
	}
	
	if (isset($metas_1))
	{
		$title = $row['metaname'].' | imagoz.ru';//Данные тега <title>
		$headMain = 'Статьи по тегу '. '"'.$row['metaname'].'"';
		$robots = 'noindex, follow';
		$descr = 'В даном разделе отображаются все статьи, помеченные тегом '.$row['metaname'];
	}
	
	else
	{
		$title = 'Новости отсутствуют';//Данные тега <title>
		$headMain = 'Новости отсутствуют';
		$robots = 'noindex, follow';
		$descr = ' ';
	}
	
	/*Определение количества статей*/
	try
	{
		$sql = "SELECT count(*) AS all_articles FROM meta INNER JOIN metapost ON meta.id = idmeta
				INNER JOIN posts ON posts.id = idpost INNER JOIN author ON author.id = idauthor WHERE premoderation = 'YES' AND zenpost = 'NO' AND meta.id = ".$idMeta;
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
		
	include 'metapost.html.php';
	exit();		

}	
	