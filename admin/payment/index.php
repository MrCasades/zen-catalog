<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Обновление платёжных реквизитов*/

if (isset ($_POST['action']) && $_POST['action'] == 'Обновить платёжные реквизиты')
{
	@session_start();//Открытие сессии для сохранения id автора
	
	$_SESSION['idAuthor'] = $_POST['id'];
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT author.id, ewallet, idpaysystem FROM author WHERE author.id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации об электронном кошельке : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Редактирование платёжных реквизитов';//Данные тега <title>
	$headMain = 'Редактировать платёжные реквизиты';
	$robots = 'noindex, nofollow';
	$descr = '';
	$padgeTitle = 'Редактировать платёжные реквизиты';// Переменные для формы "Новый автор"
	$action = 'updewallet';
	$ewallet = $row['ewallet'];
	$idauthor = $row['id'];
	$idpaysystem = $row['idpaysystem'];
	$button = 'Обновить реквизиты';
	$errorForm ='';
	
	/*Список платёжных систем*/
	try
	{
		$result = $pdo -> query ('SELECT id, paysystemname FROM paysystem');
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода paysystem '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$paysystems[] = array('idpaysystem' => $row['id'], 'paysystemname' => $row['paysystemname']);
	}
	
	include 'updpaysystem.html.php';
	exit();
	
}

/*UPDATE - обновление информации в базе данных*/

if (isset($_GET['updewallet']))//Если есть переменная updewallet выводится форма
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE author SET 
			ewallet = :ewallet,
			updewalletdate = SYSDATE(),
			idpaysystem = :idpaysystem
			WHERE author.id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':ewallet', $_POST['ewallet']);//отправка значения
		$s -> bindValue(':idpaysystem', $_POST['paysystem']);//отправка значения
		
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления информации ewallet'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: //'.MAIN_URL.'/account/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
	exit();
	
}

/*Вывод средств*/
if (isset ($_POST['action']) && $_POST['action'] == 'Вывести средства')
{
	@session_start();//Открытие сессии для сохранения id автора
	
	$_SESSION['idAuthor'] = $_POST['id'];
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Проверка на создание автором заявки на вывод средств*/
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT authorpaymentstatus FROM author
				WHERE author.id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации о статусе оплаты заявки для автора : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$authorpaymentstatus = $row['authorpaymentstatus'];
	
	if ($authorpaymentstatus == 'YES')
	{
	
		/*Команда SELECT*/
		try
		{
			$sql = 'SELECT author.id AS authorid, paysystem.id AS paysystemid, authorname, paysystemname, score, ewallet FROM author
					INNER JOIN paysystem ON idpaysystem = paysystem.id 
					WHERE author.id = '.$_SESSION['idAuthor'];
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора информации об электронном кошельке : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}
	
		$row = $s -> fetch();

		$title = 'Формирование заявки на вывод средств';//Данные тега <title>
		$headMain = 'Формирование заявки на вывод средств';
		$robots = 'noindex, nofollow';
		$descr = '';
		$padgeTitle = 'Заявка на вывод';// Переменные для формы "Новый автор"
		$action = 'editpayment';
		$authorname = $row['authorname'];
		$paysystemname = $row['paysystemname'];
		$score = $row['score'];
		$ewallet = $row['ewallet'];
		$payment = '';
		$idauthor = $row['authorid'];
		$idpaysystem = $row['paysystemid'];
		$button = 'Создать заявку';
		$scriptJScode = '<script src="script.js"></script>';
		
		if ((!isset ($paysystemname)) || (!isset ($ewallet)))
		{
			$title = 'Отсутствуют платёжные реквизиты';//Данные тега <title>
			$headMain = 'Отсутствуют платёжные реквизиты';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Платёжные реквизиты отсутствуют. Обновите их в своём приофиле!';// вывод сообщения об ошибке 
			include 'error.html.php';
			exit();
		}

		@session_start();//Открытие сессии для сохранения платёжной системы

		$_SESSION['idpaysystem'] = $idpaysystem;

		include 'paymentform.html.php';
		exit();	
	}
	
	else//если заявка была ранее сформирована, но не подтверждена
	{
		$title = 'Заявка уже сформирована';//Данные тега <title>
		$headMain = 'Заявка уже сформирована';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Вы ранее сформировали заявку на вывод средств. Новую можно будет создать после подтверждения предыдущей';// вывод сообщения об ошибке 
		include 'error.html.php';
		exit();
		
	}
}

/*команда INSERT  - добавление в базу данных*/
if (isset($_GET['editpayment']))//Если есть переменная editpayment выводится форма
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Проверка на превышение фактического счёта суммы в заявке*/
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT score FROM author
				WHERE author.id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации о размере счёта автора : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$score = $row['score'];
	
	if (($_POST['payment'] <= $score) && ($_POST['payment'] > 0) && $_POST['payment'] >= 30)
	{
	
		try
		{
			$pdo->beginTransaction();//инициация транзакции

			/*Добавление данных о платеже*/
			$sql = 'INSERT INTO payments SET 
				payment = :payment,	
				creationdate = SYSDATE(),
				idauthor = '.$_SESSION['idAuthor'].','.
				'idpaysystem = '.$_SESSION['idpaysystem'];
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> bindValue(':payment', $_POST['payment']);//отправка значения
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL

			/*Обновить данные о статусе платёжки автора для предотвращения повторного создания заявки*/
			$sql = 'UPDATE author SET authorpaymentstatus = "NO" WHERE id = '.$_SESSION['idAuthor'];
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL

			$pdo->commit();//подтверждение транзакции	


		}
		catch (PDOException $e)
		{
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка добавления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			$pdo->rollBack();//отмена транзакции
			include 'error.html.php';
			exit();
		}
		
		/*Отправка сообщений (тест)*/

		$titleMessage = 'Вы успешно сформировали заявку на вывод средств!';
		$mailMessage = 'Вы успешно сформировали заявку на вывод денежных средств в размере '.$_POST['payment']. 'руб! Она будет обработана в течении 2-х суток.';

		toEmail_1($titleMessage, $mailMessage);//отправка письма
		
		$title = 'Заявка на вывод средств успешно создана!';//Данные тега <title>
		$headMain = 'Заявка на вывод средств успешно создана!';
		$robots = 'noindex, nofollow';
		$descr = '';

		include 'paymentok.html.php';
		exit();	
	}
	
	else
	{
		$title = 'Ошибка ввода суммы';//Данные тега <title>
		$headMain = 'Ошибка ввода суммы';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Введите сумму больше "0" и меньше или равную той что есть на Вашем счёте. Либо выводимая сумма меньше 30 баллов.';// вывод сообщения об ошибке 
		include 'error.html.php';
		exit();
		
	}
}

/*Назначение бонусов и премий*/
if (isset ($_POST['action']) && $_POST['action'] == 'Назначить премию или бонус')
{
	@session_start();//Открытие сессии для сохранения id автора
	
	$_SESSION['idAuthor'] = $_POST['id'];
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, bonus FROM author WHERE author.id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации о бонусе : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Назначение бонусов и премий';//Данные тега <title>
	$headMain = 'Назначение бонусов и премий';
	$robots = 'noindex, nofollow';
	$descr = '';
	$padgeTitle = 'Назначение бонусов и премий';// Переменные для формы "Новый автор"
	$action = 'addbonus';
	$bonus = $row['bonus'];
	$idauthor = $row['id'];
	$score = 0;
	$button = 'Назначить';
	$errorForm ='';
	
	include 'addbonusform.html.php';
	exit();
}

/*Обновить бонус или назначить премию*/
if (isset($_GET['addbonus']))//Если есть переменная editpayment выводится форма
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE author SET 
			bonus = :bonus,
			score = score + :score
			WHERE author.id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':bonus', $_POST['bonus']);//отправка значения
		$s -> bindValue(':score', $_POST['score']);//отправка значения
		
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления информации о бонусе или счёте'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: //'.MAIN_URL.'/account/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
	exit();
}

/*Пополнение счёта*/
if (isset ($_POST['action']) && $_POST['action'] == 'Пополнить счёт')
{
	$title = 'Пополнить счёт';//Данные тега <title>
	$headMain = 'Пополнение счёта';
	$robots = 'noindex, nofollow';
	$descr = '';
	$padgeTitle = 'Пополнение счёта';// Переменные для формы "Новый автор"
	$action = 'editdeposit';
	$payment = '';
	$idauthor = $_POST['id'];
	$button = 'Создать заявку';
	$errorForm ='';
	
	include 'deposit.html.php';
	exit();

}

/*Вывод истории платежей*/
if (isset ($_POST['action']) && $_POST['action'] == 'История платежей')
{
	$title = 'История платежей';//Данные тега <title>
	$headMain = 'История платежей';
	$robots = 'noindex, nofollow';
	$descr = '';
	$padgeTitle = 'История платежей';// Переменные для формы "Новый автор"
	$action = '';
	$payment = '';
	$idauthor = $_POST['id'];
	$errorForm ='';
	
	/*Команда SELECT Вывод истории платежей*/
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	try
	{
		$sql = 'SELECT * FROM deposit WHERE idauthor = '.$idauthor.' LIMIT 10';//Вверху самое последнее значение
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
		$deposits[] =  array ('id' => $row['id'], 'idoperation' =>  $row['idoperation'], 'deposit' =>  $row['deposit'], 
								'depositdate' =>  $row['depositdate'], 'depositstatus' =>  $row['depositstatus']);
	}
	
	include 'depositlist.html.php';
	exit();
}

header ('Location: //'.MAIN_URL);//перенаправление обратно в контроллер index.php
exit();