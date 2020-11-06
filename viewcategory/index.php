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
	
	$selectPromotion = 'SELECT promotion.id AS promotionid, author.id AS authorid,  promotion, promotiontitle, promotiondate, imghead, imgalt, promotion.www, idauthor, idcategory, category.id AS categoryid, categoryname, authorname FROM promotion
			INNER JOIN category
			ON idcategory = category.id
			INNER JOIN author
			ON idauthor = author.id 
			WHERE premoderation = "YES" AND idcategory = ';
	$limitPost = ' LIMIT 5';
			
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	try
	{
		$sql = $selectPromotion.$idCategory. ' ORDER BY promotion.id DESC '. $limitPost;
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

	if (isset ($row['categoryname']))
	{		
	
		$title = $row['categoryname'].' | imagoz.ru';//Данные тега <title>
		$headMain = 'Каналы рубрики '. '"'.$row['categoryname'].'"';
		$robots = 'noindex, follow';
		$descr = 'В даном разделе отображаются все каналы рубрики '.$row['categoryname'];
	}
	
	else
	{
		$title = 'В рубрике отсутствуют материалы';//Данные тега <title>
		$headMain = 'В рубрике отсутствуют материалы';
		$robots = 'noindex, follow';
		$descr = '';
	}
	
	include 'categorypost.html.php';
	exit();
}