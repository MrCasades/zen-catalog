<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

if ((isset($_POST['operation_id'])) && ($_POST['unaccpted'] == false))
{
	$deposit = $_POST['withdraw_amount'];
	$idAuthor = $_POST['label'];
	$idOperation = $_POST['operation_id'];
		
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
		
	try
	{
		/*Обновить счёт автора и счётчик статей*/
		$sql = 'UPDATE author SET score = score + '.$deposit.' WHERE id = '.$idAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления счёта ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}	
	
	/*Добавление информации о платеже в базу данных*/
	try
	{
		$sql = 'INSERT INTO deposit SET 
			idoperation = "'.$idOperation.'", 
			idauthor = '.$idAuthor.',	
			depositdate  = SYSDATE(),
			deposit  = '.$deposit.',
			depositstatus = "paid"';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
}	

else
	
{
	echo 'Нет запроса!';
	
	/*Добавление информации о платеже в базу данных*/
	try
	{
		$sql = 'INSERT INTO deposit SET 
			idoperation = "###", 
			idauthor = '.$idAuthor.',	
			depositdate  = SYSDATE(),
			deposit  = 0,
			depositstatus = "not paid"';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
}

?>