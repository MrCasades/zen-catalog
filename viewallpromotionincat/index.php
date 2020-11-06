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
	$selectPost = 'SELECT promotion.id AS promotionid, author.id AS authorid,  promotion, promotiontitle, promotiondate, imghead, imgalt, promotion.www, idauthor, idcategory, category.id AS categoryid, categoryname, authorname FROM promotion
			INNER JOIN category
			ON idcategory = category.id
			INNER JOIN author
			ON idauthor = author.id 
			WHERE premoderation = "YES" AND idcategory = ';
	$limit = ' ORDER BY promotiondate DESC LIMIT ';		
			
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
		$promotions[] =  array ('id' => $row['promotionid'], 'idauthor' => $row['authorid'], 'text' => $row['promotion'], 'promotiontitle' =>  $row['promotiontitle'], 'promotiondate' => $row['promotiondate'],
						'categoryname' => $row['categoryname'], 'authorname' => $row['authorname'],'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'], 'www' =>  $row['www'],
						'categoryid' => $row['categoryid']);
	}	
	
	if (isset ($row['categoryname']))		//если статьи в рубрике есть!	
	{
		$title = $row['categoryname'].' | imagoz.ru';//Данные тега <title>
		$headMain = 'Все Каналы рубрики '. '"'.$row['categoryname'].'"';
		$robots = 'noindex, follow';
		$descr = 'В данном разделе размещаются все Каналы из рубрики '.$row['categoryname'];
		$addInCat = '<form action = "../admin/addupdpromotion/?add" method = "post">
													<input type = "hidden" name = "add" value = "add">
													<input type = "hidden" name = "idcat" value = "'.$idCategory.'">
													<input type = "submit" name = "action" value = "Добавить канал в этот раздел" class="btn btn-danger btn-sm">
												  </form>';
	}
	
	else		//если статьи отсутствуют!
	{
		$title = 'Каналы в рубрике отсутствуют';//Данные тега <title>
		$headMain = 'Каналы в рубрике отсутствуют';
		$robots = 'noindex, follow';
		$descr = '';
		$addInCat = '<form action = "../admin/addupdpromotion/?add" method = "post">
													<input type = "hidden" name = "add" value = "add">
													<input type = "hidden" name = "idcat" value = "'.$idCategory.'">
													<input type = "submit" name = "action" value = "Добавить канал в этот раздел" class="btn btn-danger btn-sm">
												  </form>';
	}
	
	/*Определение количества статей*/
	try
	{
		$sql = "SELECT count(*) AS all_articles FROM promotion
				INNER JOIN category
				ON idcategory = category.id
				INNER JOIN author
				ON idauthor = author.id 
				WHERE premoderation = 'YES' AND idcategory = ".$idCategory;
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
	
	include 'promotionincat.html.php';
	exit();
}