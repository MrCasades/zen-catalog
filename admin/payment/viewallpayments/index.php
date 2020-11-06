<?php
/*Загрузка главного пути*/
include_once '../../../includes/path.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

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
	$error = 'В данный раздел доступ запрещён!';
	include 'error.html.php';
	exit();
}

/*Загрузка сообщения об ошибке входа*/
if (!userRole('Администратор'))
{
	$title = 'Ошибка доступа';//Данные тега <title>
	$headMain = 'Ошибка доступа';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'В данный раздел доступ запрещён!';
	include 'error.html.php';
	exit();
}

/*Вывод платёжных реквизитов для администратора*/

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Команда SELECT*/
try
{
	$sql = 'SELECT payments.id AS paymentsid, authorname, payment, email, ewallet, paysystemname, creationdate FROM payments
			INNER JOIN author ON payments.idauthor = author.id
			INNER JOIN paysystem ON payments.idpaysystem = paysystem.id 
			WHERE paymentstatus = "NO"';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка выбора информации для вывода заявок на платёж : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

foreach ($result as $row)
{
	$payments[] = array('id' => $row['paymentsid'],'authorname' => $row['authorname'], 'payment' => $row['payment'], 'email' => $row['email'],
						'ewallet' => $row['ewallet'], 'paysystemname' => $row['paysystemname'], 'creationdate' => $row['creationdate']);
}

$title = 'Список заявок на выплату';//Данные тега <title>
$headMain = 'Список заявок на выплату';
$robots = 'noindex, nofollow';
$descr = '';

include 'viewallpayments.html.php';
exit();
