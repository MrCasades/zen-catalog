<?php
/*Получение данных XML с сайта playo.ru*/

try
{
	$xml = simplexml_load_file ('https://playo.ru/static/xmlfeed/?ref=q4y1l15f');
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Не удалось получить данные ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($xml as $row)
{
	$gamesAll[] =  array ('title' => $row->title, 'url' => $row->url, 'price' =>  $row->price, 'old_price' =>  $row->old_price,
					   'disount' => $row->disount, 'images' => $row->images->big);
}

/*Вывод рандомных игр*/

$shuffleKeys = array_keys($gamesAll);

shuffle($shuffleKeys);
$newGamesAll = array();
foreach($shuffleKeys as $key) 

{
    $newGamesAll[$key] = $gamesAll[$key];
}

$gamesView = array_slice($newGamesAll, 0, 8);
	

include 'shopcomponent.inc.html.php';