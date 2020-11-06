<?php 
/*Загрузка главного пути*/
include_once '../includes/path.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

$title = 'Все сообщения | imagoz.ru';
$headMain = 'Все сообщения';
$robots = 'noindex, follow';
$descr = 'В данном разделе отображаются сообщения пользователей';
	
if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

else
{
	$title = 'Ошибка доступа';//Данные тега <title>
	$headMain = 'Ошибка доступа';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '//'.MAIN_URL.'/admin/accessfail.html.php';
	exit();
}

/*Возвращение id автора*/

$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора


/*Вывод имён пользователей для диалогов*/
			
/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Вывод имён пользователей с непрочитанными сообщениями*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT mmess.id AS mainmessageid, mmess.firstmessage, mmess.mainmessagedate, mmess.unread, authorfrom.authorname AS afr, authorfrom.id AS idfr, 
			authorto.id AS idt, authorto.authorname AS ato, authorfrom.avatar AS avafr, authorto.avatar AS avato 
												FROM mainmessages AS mmess 
												INNER JOIN author AS authorfrom ON mmess.idfrom = authorfrom.id 
												INNER JOIN author AS authorto ON mmess.idto = authorto.id 		
	WHERE (idfrom = '.$selectedAuthor.' OR idto = '.$selectedAuthor.') AND unread = "YES" ORDER BY mainmessageid';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора сообщений: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{

	$unreadMessages[] =  array ('idmess' => $row['mainmessageid'], 'firstmessage' => $row['firstmessage'],
								  'authorfrom' => $row['afr'], 'authorto' => $row['ato'], 'idfrom' => $row['idfr'], 'idto' => $row['idt'],
								  'avafr' => $row['avafr'], 'avato' => $row['avato'], 'unread' => $row['unread'], 'mainmessagedate' => $row['mainmessagedate']);
}


/*Вывод имён пользователей, с которыми ведётся диалог*/
/*Команда SELECT*/
try
{
	$sql = 'SELECT mmess.id AS mainmessageid, mmess.firstmessage, mmess.unread, authorfrom.authorname AS afr, authorfrom.id AS idfr, 
			authorto.id AS idt, authorto.authorname AS ato, authorfrom.avatar AS avafr, authorto.avatar AS avato 
												FROM mainmessages AS mmess 
												INNER JOIN author AS authorfrom ON mmess.idfrom = authorfrom.id 
												INNER JOIN author AS authorto ON mmess.idto = authorto.id 		
	WHERE idfrom = '.$selectedAuthor.' OR idto = '.$selectedAuthor.' ORDER BY mainmessageid';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора сообщений: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}


/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$dialogs[] =  array ('idmess' => $row['mainmessageid'], 'firstmessage' => $row['firstmessage'],
								  'authorfrom' => $row['afr'], 'authorto' => $row['ato'], 'idfrom' => $row['idfr'], 'idto' => $row['idt'],
								  'avafr' => $row['avafr'], 'avato' => $row['avato'], 'unread' => $row['unread']);
}
			
include 'mainmessages.html.php';
exit();