<?php
/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

$title = 'Список пользователей';//Данные тега <title>
$headMain = 'Зарегестрированные пользователи';
$robots = 'noindex, nofollow';
$descr = '';
$scriptJScode = '<script src="script.js"></script>';//добавить код JS

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
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Доступ запрещен';
	include '../accessfail.html.php';
	exit();
}

/*Добавление информации в таблицу author*/

	/*Загрузка шаблона form.html.php по ссылке*/
if (isset($_GET['add']))//Если есть переменная add выводится форма
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	$padgeTitle = 'Новый автор';// Переменные для формы "Новый автор"
	$action = 'addform';
	$authorname = '';
	$email = '';
	$www = '';
	$idauthor = '';
	$accountinfo = '';
	$button = 'Добавить автора';
	//$password = '';
	
	/*Формирование списка ролей*/
	
	try
	{
		$result = $pdo->query('SELECT id, descr FROM role');
	}
	
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка формирования списка ролей '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$roles[] = array('id' => $row['id'], 'descr' => $row['descr'], 'selected' => FALSE);
	}
	
	include 'form.html.php';
	exit();
}
if (isset ($_GET['addform']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'INSERT INTO author SET authorname = :authorname, email = :email, www = :www, accountinfo = :accountinfo, regdate = SYSDATE()';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':authorname', $_POST['authorname']);//отправка значения
		$s -> bindValue(':email', $_POST['email']);//отправка значения
		$s -> bindValue(':www', $_POST['www']);//отправка значения
		$s -> bindValue(':accountinfo', $_POST['accountinfo']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления информации автора'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$authorid = $pdo -> lastInsertid();//значение последнего автоинкрементного поля
	
	if ($_POST['password'] != '')
	{
		$password = md5($_POST['password'] . 'fgtn');
		
		try
		{
			$sql = 'UPDATE author SET password = :password WHERE id = :id';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> bindValue(':password', $password);//отправка значения
			$s -> bindValue(':id', $authorid);//отправка значения
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}
		catch (PDOException $e)
		{
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка назначения пароля '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}			
	}
	
	if(isset ($_POST['roles']))
	{
		foreach ($_POST['roles'] as $role)
		{
			
			try
			{
				$sql = 'INSERT INTO authorrole SET idauthor = :idauthor, idrole = :idrole';// псевдопеременная получающая значение из формы
				$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
				$s -> bindValue(':idauthor', $authorid);//отправка значения
				$s -> bindValue(':idrole', $role);//отправка значения
				$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			}
			catch (PDOException $e)
			{
				$robots = 'noindex, nofollow';
				$descr = '';
				$error = 'Ошибка назначения роли '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
				include 'error.html.php';
				exit();
			}	
		}
	}
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	

/*Редактирование информации в таблице author*/

if (isset ($_POST['action']) && ($_POST['action'] == 'Upd'))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT * FROM author WHERE id = :idauthor';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idauthor', $_POST['idauthor']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error select : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$padgeTitle = 'Редактировать автора';// Переменные для формы "Новый автор"
	$action = 'editform';
	$authorname = $row['authorname'];
	$email = $row['email'];
	//$password = $row['password'];
	$www = $row['www'];
	$accountinfo = $row['accountinfo'];
	$idauthor = $row['id'];
	$button = 'Обновить информацию об авторе';
	
	/*Список ролей для автора*/
	try
	{
		$sql = 'SELECT idrole FROM authorrole WHERE idauthor = :id';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $idauthor);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
		
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка получения списка ролей' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$selectedRoles = array();
	
	foreach ($s as $row)
	{
		$selectedRoles[] = $row['idrole'];
	}
	
	/*Формирование списка ролей*/
	try
	{
		$result = $pdo->query('SELECT id, descr FROM role');
	}
	
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка формирования списка ролей '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$roles[] = array('id' => $row['id'], 'descr' => $row['descr'], 'selected' => in_array($row['id'], $selectedRoles));
	}
	
	include 'form.html.php';
	exit();
	
}
	/*Команда UPDATE*/
if (isset ($_GET['editform']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE author SET authorname = :authorname, email = :email, www = :www, accountinfo = :accountinfo WHERE id = :id';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $_POST['id']);//отправка значения
		$s -> bindValue(':authorname', $_POST['authorname']);//отправка значения
		$s -> bindValue(':email', $_POST['email']);//отправка значения
		$s -> bindValue(':www', $_POST['www']);//отправка значения
		$s -> bindValue(':accountinfo', $_POST['accountinfo']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Error Update: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
	}
	
	if ($_POST['password'] != '')
	{
		$password = md5($_POST['password'] . 'fgtn');
	
		try
		{
			$sql = 'UPDATE author SET password = :password WHERE id = :id';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> bindValue(':password', $password);//отправка значения
			$s -> bindValue(':id', $_POST['id']);//отправка значения
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}
		catch (PDOException $e)
		{
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка назначения пароля '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}			
	}

	try
	{
		$sql = 'DELETE FROM authorrole WHERE idauthor = :id';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка удаления неактуальных записей об роле'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}	
	
	if(isset ($_POST['roles']))
	{
		foreach ($_POST['roles'] as $role)
		{
			
			try
			{
				$sql = 'INSERT INTO authorrole SET idauthor = :idauthor, idrole = :idrole';// псевдопеременная получающая значение из формы
				$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
				$s -> bindValue(':idauthor', $_POST['id']);//отправка значения
				$s -> bindValue(':idrole', $role);//отправка значения
				$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			}
			catch (PDOException $e)
			{
				$robots = 'noindex, nofollow';
				$descr = '';
				$error = 'Ошибка назначения роли '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
				include 'error.html.php';
				exit();
			}	
		}
	}

	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}		

/*Удаление из таблици author*/

if (isset ($_POST['action']) && $_POST['action'] == 'Del')
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Удаление записей о ролях автора*/
	try
	{
		$sql = 'DELETE FROM authorrole WHERE idauthor = :id';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка удаления ролей'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}	
	
	/*Команда SELECT для ID поста*/
	try
	{
		$sql = 'SELECT id FROM posts WHERE idauthor = :id';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $_POST['idauthor']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error select posts: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$result = $s -> fetchAll();
	
	try
	/*Удаление записи об издательстве*/
	{
		$sql = 'DELETE FROM metapost WHERE idpost = :id';// - псевдопеременная получающая значение из формы 
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		
		foreach ($result as $row)
		{
			$idpost = $row['id'];
			$s -> bindValue(':id', $idpost);//отправка значения
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}
	}
	
	catch (PDOException $e)
	
	{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка удаления '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
	}
	
	try
	/*Удаление записей, принадлежащих автору*/
	{
		$sql = 'DELETE FROM posts WHERE idauthor = :idauthor';// - псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idauthor', $_POST['idauthor']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка удаления '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
	}
	
	try
	/*Удаление имени автора*/
	{
		$sql = 'DELETE FROM author WHERE id = :idauthor';// - псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idauthor', $_POST['idauthor']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Ошибка удаления '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
	}
	
	
	header ('Location: .');//перенаправление обратно в контроллер index.php
	exit();
}	

/*Подключение к базе данных*/
include MAIN_FILE . '/includes/db.inc.php';

/*Команда SELECT*/
try
{
	$sql = 'SELECT id, authorname FROM author';
	$result = $pdo->query($sql);
}

catch (PDOException $e)
{
	$robots = 'noindex, nofollow';
	$descr = '';
	$error = 'Error table "Author": ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
	include 'error.html.php';
	exit();
}

/*Вывод результата в шаблон*/
foreach ($result as $row)
{
	$authors[] =  array ('idauthor' => $row['id'], 'authorname' => $row['authorname']);
}

include 'author.html.php';





	