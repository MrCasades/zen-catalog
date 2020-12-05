<?php

/*Загрузка главного пути*/
include_once __DIR__ . '/includes/path.inc.php';

$title = 'Инновационный каталог каналов Яндекс.Дзен, а также сервис по их раскрутке - full-zen.imagoz.ru';//Данные тега <title>
$headMain = '';
$robots = 'all';
$descr = 'Проект FULL-ZEN - каталог дзен-каналов, а также сервис по их раскрутке.';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Определение нахождения пользователя в системе*/
if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Последний рекомендованный канал*/
/*Команда SELECT*/

try
{
	$sql = 'SELECT promotion.id AS promotionid, author.id AS idauthor, promotion, promotiontitle, imghead, imgalt, promotiondate, authorname, category.id AS categoryid, categoryname FROM promotion 
			INNER JOIN author ON idauthor = author.id 
			INNER JOIN category ON idcategory = category.id 
			WHERE premoderation = "YES" AND recommendationdate ORDER BY recommendationdate DESC LIMIT 4';//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода статей на главной странице ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$lastRecommPromotions[] =  array ('id' => $row['promotionid'], 'idauthor' => $row['idauthor'], 'text' => $row['promotion'], 'promotiontitle' =>  $row['promotiontitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
						'promotiondate' =>  $row['promotiondate'], 'authorname' =>  $row['authorname'], 
						'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid']);
}

/*Подсчёт статей для определения числа колонок в выводе*/
try
{
	$sql = 'SELECT count(*) AS count_all FROM promotion WHERE premoderation = "YES" AND recommendationdate';
	$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
	$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
}
	
catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода заголовка похожей статьи ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}
	
$row = $s -> fetch();
	
$columns_3 = $row['count_all'] > 1 ? 'columns' : 'columns_f1';

/*Команда SELECT для тегов статей*/
try
{
	$sql = 'SELECT DISTINCT metaname, meta.id FROM meta 
			INNER JOIN metapost ON idmeta = meta.id 
			INNER JOIN posts ON idpost = posts.id	
			ORDER BY rand() LIMIT 5';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора тегов статей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$metas_2[] =  array ('id' => $row['id'], 'meta' => $row['metaname']);
}

/*Команда SELECT для тегов промоушена*/
try
{
	$sql = 'SELECT DISTINCT metaname, meta.id FROM meta 
			INNER JOIN metapost ON idmeta = meta.id 
			INNER JOIN promotion ON idpromotion = promotion.id	
			ORDER BY rand() LIMIT 5';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора тегов статей ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$metas_3[] =  array ('id' => $row['id'], 'meta' => $row['metaname']);
}


/*Вывод топ-5*/

/*Вывод ТОП-5 статей*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT id, posttitle, viewcount, averagenumber FROM posts WHERE premoderation = "YES" AND zenpost = "NO" AND votecount > 1 ORDER BY averagenumber DESC LIMIT 5';//Вверху самое последнее значение
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
	$postsTOP[] =  array ('id' => $row['id'], 'posttitle' => $row['posttitle'], 'viewcount' => $row['viewcount'], 'averagenumber' => $row['averagenumber']);
}

/*Вывод ТОП-5 промоушен*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT id, promotiontitle, viewcount, averagenumber FROM promotion WHERE premoderation = "YES" AND votecount > 1 ORDER BY averagenumber DESC LIMIT 5';//Вверху самое последнее значение
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
	$promotionsTOP[] =  array ('id' => $row['id'], 'promotiontitle' => $row['promotiontitle'], 'viewcount' => $row['viewcount'], 'averagenumber' => $row['averagenumber']);
}

/*Подсчёт статей для определения числа колонок в выводе*/
try
{
	$sql = 'SELECT count(*) AS count_all FROM promotion WHERE premoderation = "YES" AND votecount > 1';
	$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
	$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
}
	
catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода заголовка похожей статьи ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}
	
$row = $s -> fetch();
	
$columns_2 = $row['count_all'] > 1 ? 'columns' : 'columns_f1';

/*Вывод ТОП-5 авторов*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT id, authorname, avatar, countposts, rating FROM author WHERE countposts > 2 AND id <> 1 ORDER BY countposts DESC LIMIT 7';//Вверху самое последнее значение
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
	$authorsTOP[] =  array ('id' => $row['id'], 'authorname' => $row['authorname'], 'avatar' => $row['avatar'],
						    'countposts' => $row['countposts'], 'rating' => $row['rating']);
}

/*Вывод конкурсной статистики*/
/*Команда SELECT проверка запуска конкурса*/
try
{
	$sql = 'SELECT contestpanel FROM contest WHERE id = 1';//Вверху самое последнее значение
	$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
	$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
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

$row = $s -> fetch();
		
$contestPanel = $row['contestpanel'];//проверка на включение конкурса

if ($contestPanel == 'YES')//Если конкурс включен, выводится блок в панели
{
	$hederContest = '<div class = "titles_main_padge"><h4 align = "center">Результаты конкурса</h4></div>';
	
	try
	{
		$sql = 'SELECT id, authorname, avatar, contestscore FROM author WHERE contestscore > 0 ORDER BY contestscore DESC LIMIT 7';//Вверху самое последнее значение
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
		$contestsTOP[] =  array ('id' => $row['id'], 'authorname' => $row['authorname'], 'avatar' => $row['avatar'], 'contestscore' => $row['contestscore']);
	}

}

else
{
	$hederContest = '';
}


/*Вывод изображения дня*/
/*Команда SELECT*/

try
{
	$sql = 'SELECT posts.id AS postid, post, posttitle, imghead, imgalt, postdate, authorname, category.id AS categoryid, categoryname 
			FROM category 
			INNER JOIN posts ON idcategory = category.id
			INNER JOIN author ON idauthor = author.id			
			WHERE categoryname = "Изображение дня" AND premoderation = "YES" AND zenpost = "NO" ORDER BY postdate DESC LIMIT 1';//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода новостей на главной странице ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$postsIMG[] =  array ('id' => $row['postid'], 'text' => $row['post'], 'posttitle' =>  $row['posttitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
						'postdate' =>  $row['postdate'], 'authorname' =>  $row['authorname'], 
						'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid']);
}

/*Вывод промоушена*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT promotion.id AS promotionid, author.id AS idauthor, promotion, promotiontitle, imghead, imgalt, promotion.www, promotiondate, authorname, category.id AS categoryid, categoryname FROM promotion 
			INNER JOIN author ON idauthor = author.id 
			INNER JOIN category ON idcategory = category.id 
			WHERE premoderation = "YES" ORDER BY promotiondate DESC LIMIT 8';//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода статей промоушена на главной странице ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$promotions[] =  array ('id' => $row['promotionid'], 'idauthor' => $row['idauthor'], 'text' => $row['promotion'], 'promotiontitle' =>  $row['promotiontitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
						'promotiondate' =>  $row['promotiondate'], 'authorname' =>  $row['authorname'], 'www' =>  $row['www'],
						'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid']);
}

/*Вывод стаей*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT posts.id AS postid, author.id AS idauthor, post, posttitle, imghead, imgalt, postdate, authorname, category.id AS categoryid, categoryname FROM posts 
			INNER JOIN author ON idauthor = author.id 
			INNER JOIN category ON idcategory = category.id 
			WHERE premoderation = "YES" ORDER BY postdate DESC LIMIT 7';//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода статей на главной странице ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$posts[] =  array ('id' => $row['postid'], 'idauthor' => $row['idauthor'], 'text' => $row['post'], 'posttitle' =>  $row['posttitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
						'postdate' =>  $row['postdate'], 'authorname' =>  $row['authorname'], 
						'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid']);
}

/*Подсчёт статей для определения числа колонок в выводе*/
try
{
	$sql = 'SELECT count(*) AS count_all FROM promotion WHERE premoderation = "YES"';
	$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
	$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
}
	
catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка вывода заголовка похожей статьи ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}
	
$row = $s -> fetch();
	
$columns_1 = $row['count_all'] > 1 ? 'columns' : 'columns_f1';

include 'posts.html.php';
exit();



	