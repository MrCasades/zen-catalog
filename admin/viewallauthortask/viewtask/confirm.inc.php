<?php
/*Загрузка главного пути*/
include_once '../../../includes/path.inc.php';

/*Подтверждение взаимного действия*/

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';
	
try
{
/*Обновить статус задания*/
	$sql = 'UPDATE task SET readystatus = "YES", readydate = SYSDATE() WHERE id = :idtask';
	$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
	$s -> bindValue(':idtask', $_POST['idtask']);//отправка значения
	$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
}
	
catch (PDOException $e)
{
	$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
	$headMain = 'Ошибка данных!';
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка взятия задания '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}
?>