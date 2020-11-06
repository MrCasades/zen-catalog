<?php

/*Загрузка главного пути*/
include_once '../../includes/path.inc.php';

/*Загрузка функций для формы входа*/
include_once MAIN_FILE . '/includes/access.inc.php';

/*КЛЮЧИ*/
define('SITE_KEY', '6Le8cswUAAAAANIKzxmwHehiR6-jKRJnUeqw5JRB');
define('SECRET_KEY', '6Le8cswUAAAAAG6x81r5pO1YpMwyqktm9m5clOgv');

/*Авторизация в системе*/
if (isset($_GET['log']))
{
	$title = 'Вход в систему';//Данные тега <title>
	$headMain = 'Вход в систему';
	$robots = 'noindex, nofollow';
	$descr = 'Авторизация пользователя в системе';
	
	/*Ошибки авторизации*/
	$GLOBALS['loginError'] = '';
	$errLogin = '';
	
	if (!loggedIn())
	{
		$errLogin = $GLOBALS['loginError'];
		include MAIN_FILE.'/admin/login.html.php';
		exit();
	}	
	
	elseif ($_SESSION['loggIn'] = TRUE)
	{
		$loggood = 'Вы успешно вошли в систему!';
		include MAIN_FILE.'/admin/accessgood.html.php';
		exit();
	}
}

/*Регистрация в системе*/
if (isset($_GET['reg']))
{	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	/*Загрузка функций в шаблон*/
	include_once MAIN_FILE . '/includes/func.inc.php';
	
	defaultRegFormData();//данные для формы регистрации по умолчанию
	
	$errLog = '';
	
	include 'registration.html.php';
	exit();
}

if (isset ($_GET['addform']))
{
	
	/*Загрузка функций в шаблон*/
	include_once MAIN_FILE . '/includes/func.inc.php';
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Проверка на дубль E-mail*/
	try
	{
		$sql = 'SELECT email FROM author
				WHERE email = :email';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':email', $_POST['email']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$error = 'Ошибка поиска автора: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s-> fetch();
	
	$email = $row['email'];
		
	/*Вывод сообщения об ошибке при дубле email*/	
	if ($email != '')	
	{
			$title = 'Такой пользователь уже зарегестрирован в системе!';//Данные тега <title>
			$headMain = 'Такой пользователь уже зарегестрирован в системе!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Пользователь с таким адресом электронной почты уже зарегестрирован в системе. Если Вы забыли свой пароль, воспользуйтесь <a href = "/admin/recoverpassword/?send">функцией восстановления</a>!';
			include 'error.html.php';
			exit();
	}
	
	/*Вывод сообщения об ошибке, если не заполнены поля email или "Пароль"*/
	if (($_POST['email'] == '') || ($_POST['password'] == '') || ($_POST['authorname'] == ''))
	{
		defaultRegFormData();//данные для формы регистрации по умолчанию
		
		$errLog = 'Заполните все обязательные поля';
		
		include 'registration.html.php';
		exit();
	}
	
	/*Вывод сообщения об ошибке, введённые пароли не совпадают*/
	if ($_POST['password'] != $_POST['password2'])
	{
		defaultRegFormData();
		
		$errLog = 'Пароли должны совпадать!';
		
		include MAIN_FILE.'/admin/registration/registration.html.php';
		exit();
	}
	
	/*google capcha*/
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
  		$recaptcha=$_POST['g-recaptcha-response'];
		
    	if(!empty($recaptcha))
		{

			$google_url="https://www.google.com/recaptcha/api/siteverify";
			//$secret='6Le8cswUAAAAANIKzxmwHehiR6-jKRJnUeqw5JRB';
			$ip=$_SERVER['REMOTE_ADDR'];
			$url=$google_url."?secret=".SECRET_KEY."&response=".$recaptcha."&remoteip=".$ip;
			$res=SiteVerify($url);
			$res= json_decode($res, true);
			
			if($res['success'])
        	{
				try
				{
					$sql = 'INSERT INTO author SET authorname = :authorname, email = :email, regdate = SYSDATE()';// псевдопеременная получающая значение из формы
					$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
					$s -> bindValue(':authorname', $_POST['authorname']);//отправка значения
					$s -> bindValue(':email', $_POST['email']);//отправка значения
					$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
				}
				catch (PDOException $e)
				{
					$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
					$headMain = 'Ошибка данных!';
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
						$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
						$headMain = 'Ошибка данных!';
						$robots = 'noindex, nofollow';
						$descr = '';
						$error = 'Ошибка назначения пароля '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
						include 'error.html.php';
						exit();
					}			
				}
				
				/*Присвоение роли рекламодателя по умолчанию*/
				try
				{
					$sql = 'INSERT INTO authorrole SET idauthor = :idauthor, idrole = :idrole';// псевдопеременная получающая значение из формы
					$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
					$s -> bindValue(':idauthor', $authorid);//отправка значения
					$s -> bindValue(':idrole', 'Рекламодатель');//отправка значения
					$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
				}
				
				catch (PDOException $e)
				{
					$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
					$headMain = 'Ошибка данных!';
					$robots = 'noindex, nofollow';
					$descr = '';
					$error = 'Ошибка назначения роли '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
					include 'error.html.php';
					exit();
				}	

				$title = 'Регистрация прошла успешно';//Данные тега <title>
				$headMain = 'Поздравляем, Вы успешно зарегестрировались в системе!';
				$robots = 'noindex, nofollow';
				$descr = 'Сообщение об успешной регистрации нового пользователя';
				$loggood = 'Вы успешно зарегестрировались!';
				include MAIN_FILE.'/admin/accessgood.html.php';
				exit();
				
			}
			
			else
			{
				defaultRegFormData();//данные для формы регистрации по умолчанию
				
				$errLog = 'Проверка не пройдена';
				
				include 'registration.html.php';
				exit();
			}
		}
		
		else
		{
			defaultRegFormData();//данные для формы регистрации по умолчанию
			
			$errLog = 'Проверка не пройдена';
			
			include 'registration.html.php';
			exit();
		}
	}
}	