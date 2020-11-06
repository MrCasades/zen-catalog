<?php 
/*Вывод списка рубрик*/

/*Загрузка главного пути*/
include_once '../includes/path.inc.php';

$title = 'Разделы каталога FULL-ZEN';//Данные тега <title>
$headMain = 'Разделы каталога FULL-ZEN';
$robots = 'noindex, nofollow';
$descr = 'Все разделы каталога';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Вывод рубрик*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT id, categoryname FROM category WHERE categoryname <> "Статья"';//Вверху самое последнее значение
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора рубрик ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$categorysMM[] =  array ('id' => $row['id'], 'category' => $row['categoryname']);
}

include 'cataloggroup.html.php';