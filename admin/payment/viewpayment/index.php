<?php
/*Загрузка главного пути*/
include_once '../../../includes/path.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

/*Загрузка формы входа*/
if (!loggedIn())
{
	include '../login.html.php';
	exit();
}

/*Загрузка сообщения об ошибке входа*/
if (!userRole('Администратор'))
{
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Вывод заявки на оплату*/

if (isset($_GET['id']))
{
	$paymentId = $_GET['id'];
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT payments.id AS paymentsid, author.id AS authorid, authorname, payment, email, ewallet, paysystemname, creationdate FROM payments
				INNER JOIN author ON payments.idauthor = author.id
				INNER JOIN paysystem ON payments.idpaysystem = paysystem.id 
				WHERE payments.id = '.$paymentId.' AND paymentstatus = "NO"';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода заявки на  : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Заявка на выплату';//Данные тега <title>
	$headMain = 'Заявка на выплату #'.$row['paymentsid'].' от '.$row['creationdate'];
	$action = 'editpayment';
	$authorname = $row['authorname'];
	$email = $row['email'];
	$paysystemname = $row['paysystemname'];
	$button = 'Подтвердить заявку';
	$payment = $row['payment'];
	$ewallet = $row['ewallet'];
	$robots = 'noindex, nofollow';
	$descr = '';
	
	@session_start();//Открытие сессии для сохранения id автора, платежа и суммы
	
	$_SESSION['idAuthor']   = $row['authorid'];
	$_SESSION['idPayment'] = $row['paymentsid'];
	$_SESSION['payment'] = $row['payment'];

	include 'viewpayment.html.php';
	exit();
	
}

/*Списание со счёта автора*/
if (isset ($_GET['editpayment']))
{
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	$updScore = 'UPDATE author SET 
				score = score - '.$_SESSION['payment'].
				', authorpaymentstatus = "YES" 
				WHERE author.id = '.$_SESSION['idAuthor'];//обновить счёт автора и статус наличия платежа
	
	$updPaymentAttribute = 'UPDATE payments SET 
							paymentdate = SYSDATE()
							, paymentstatus = "YES" 
							WHERE payments.id = '.$_SESSION['idPayment'];//обновить дату платежа и статус платежа
	
	try
	{
		$pdo->beginTransaction();//инициация транзакции
			
		$sql = $updScore;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			
		$sql = $updPaymentAttribute;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL

		$pdo->commit();//подтверждение транзакции			
	}
		
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$pdo->rollBack();//отмена транзакции
		$error = 'Error transaction payment '.$e -> getMessage();// вывод сообщения об ошибке в переменой $e;// вывод сообщения об ошибке в переменой $e;// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();		
	}
	
	unset($_SESSION['idAuthor'], $_SESSION['idPayment'], $_SESSION['payment']);//закрытие сессии
		
	header ('Location: //'.MAIN_URL.'/admin/payment/viewallpayments/'); //перенаправление обратно в контроллер index.php
	exit();
}