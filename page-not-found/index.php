<?php
/*Загрузка главного пути*/
include_once '../includes/path.inc.php';

/*Вывод текста о ошибке*/

$title = 'Страница не обнаружена | imagoz.ru';//Данные тега <title>
$headMain = 'Ошибка 404!';
$robots = 'noindex, nofollow';
$descr = 'Страница не обнаружена!';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';
	
if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Текст о сотрудничестве*/

$pageNotFound = 'Запрашиваемая Вами страница отсутствует на портале!';
	
include 'page-not-found.html.php';
exit();	