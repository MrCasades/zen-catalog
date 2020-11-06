<?php 
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';
	
if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Вывод всех новостей автора*/

if (isset ($_GET['id']))
{
	$idAuthor = $_GET['id'];
	$selectNews = 'SELECT newsblock.id AS newsid, author.id AS authorid, news, newstitle, newsdate, imghead, imgalt, idauthor, idcategory, category.id AS categoryid, categoryname, authorname, imghead FROM newsblock
			INNER JOIN author
			ON author.id = idauthor
			INNER JOIN category
			ON idcategory = category.id
			WHERE premoderation = "YES" AND idauthor = ';
	$limit = ' ORDER BY newsdate DESC LIMIT ';
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Постраничный вывод информации*/
		
	$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
	$onPage = 15;// количество статей на страницу
	$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу
	
	try
	{
		$sql = $selectNews.$idAuthor.$limit.$shift.', '.$onPage;
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора всех новостей автора ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$newsIn[] =  array ('id' => $row['newsid'], 'idauthor' => $row['authorid'], 'textnews' => $row['news'], 'newstitle' =>  $row['newstitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
						'newsdate' =>  $row['newsdate'], 'authorname' =>  $row['authorname'], 
						'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid']);
	}
	
	if (isset ($row['news']))		//если статьи в рубрике есть!	
	{
		$title = 'Все новости автора ' . $row['authorname'].' | imagoz.ru';//Данные тега <title>
		$headMain = 'Все новости автора ' . $row['authorname'];
		$robots = 'noindex, follow';
		$descr = 'В данном разделе публикуются все новости автора '.$row['authorname'];
	}
	
	else		//если статьи отсутствуют!
	{
		$title = 'Новости отсутствуют | ImagozCMS';//Данные тега <title>
		$headMain = 'Новости отсутствуют';
		$robots = 'noindex, follow';
		$descr = '';
	}	
	
	/*Определение количества новостей*/
	try
	{
		$sql = "SELECT count(*) AS all_articles FROM newsblock
				WHERE premoderation = 'YES' AND idauthor = ". $idAuthor;
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
	
	include 'allauthornews.html.php';
	exit();
}