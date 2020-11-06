<?php 
/*Загрузка главного пути*/
include_once '../includes/path.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';
	
if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Вывод всех статей автора*/

/*возврат ID автора*/
$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора

$selectPromoion = 'SELECT post, title, date, imghead, imgalt, idauthorpost, idcategory, url, authorname, categoryname FROM favourites 
					 INNER JOIN author ON idauthorpost = author.id 
			   		 INNER JOIN category ON idcategory = category.id WHERE idpromotion <> 0 AND idauthor = ';

$selectPosts = 'SELECT post, title, date, imghead, imgalt, idauthorpost, idcategory, url, authorname, categoryname FROM favourites 
					 INNER JOIN author ON idauthorpost = author.id 
			   		 INNER JOIN category ON idcategory = category.id WHERE idpost <> 0 AND idauthor = ';

$limit = ' ORDER BY adddate DESC LIMIT 5';

//$onPage = 5;// количество статей на страницу
	
/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

try
{
	$sql = $selectPromoion.$selectedAuthor.$limit;
	$result = $pdo->query($sql);
}
	
catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора избранных каналов ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$favourites_prom[] =  array ('post' => $row['post'], 'authorname' => $row['authorname'], 'title' => $row['title'],
						'date' => $row['date'], 'imghead' => $row['imghead'], 'imghead' => $row['imghead'], 'imgalt' => $row['imgalt'],
						'idauthorpost' => $row['idauthorpost'], 'idcategory' => $row['idcategory'], 'url' => $row['url'],
						'categoryname' => $row['categoryname']);
}	

/*Вывод ссылки на все избранные каналы*/
$viewAllChannalsLink = (!empty ($favourites_prom)) ? '<div class="for_allposts_link"><p align = "center"><a href="./viewallfavouritesfortypes/?promotion" style="color: white">Все избранные каналы</a></p></div>' : '';
		
try
{
	$sql = $selectPosts.$selectedAuthor.$limit;
	$result = $pdo->query($sql);
}
	
catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора избранных статей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$favourites_post[] =  array ('post' => $row['post'], 'authorname' => $row['authorname'], 'title' => $row['title'],
						'date' => $row['date'], 'imghead' => $row['imghead'], 'imghead' => $row['imghead'], 'imgalt' => $row['imgalt'],
						'idauthorpost' => $row['idauthorpost'], 'idcategory' => $row['idcategory'], 'url' => $row['url'],
						'categoryname' => $row['categoryname']);
}	

/*Вывод ссылки на все избранные статьи*/
$viewAllPostLink = (!empty ($favourites_post)) ? '<div class="for_allposts_link"><p align = "center"><a href="./viewallfavouritesfortypes/?posts" style="color: white">Все избранные статьи</a></p></div>' : '';
	
if (isset ($row['post']))		//если статьи в рубрике есть!	
{
	$name = authorLogin($_SESSION['email'], $_SESSION['password']);
		
	$title = 'Все избранные материалы пользователя ' . $name.' | imagoz.ru';//Данные тега <title>
	$headMain = 'Все избранные материалы пользователя ' . $name;
	$robots = 'noindex, follow';
	$descr = 'В данном разделе публикуются все статьи пользователя '. $name;
}
	
else		//если статьи отсутствуют!
{
	$title = 'Статьи отсутствуют | ImagozCMS';//Данные тега <title>
	$headMain = 'Статьи отсутствуют';
	$robots = 'noindex, follow';
	$descr = '';
}	
		
include 'viewallfavourites.html.php';
exit();