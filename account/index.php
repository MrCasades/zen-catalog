<?php
/*Загрузка главного пути*/
include_once '../includes/path.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка функций для формы входа*/
require_once MAIN_FILE . '/includes/access.inc.php';

if (loggedIn())
{
	/*Если loggedIn = TRUE, выводится имя пользователя иначе меню авторизации*/
}

/*Загрузка данных автора*/
if (isset ($_GET['id']))
{
	$idAuthor = $_GET['id'];
	$selectedAuthor = '';//если автор не выбран
	
	@session_start();//Открытие сессии для сохранения id автора
	
	$_SESSION['idAuthor'] = $idAuthor;
	$select = 'SELECT * FROM author WHERE id = ';
	$selectPosts = 'SELECT promotion.id AS promotionid, author.id AS authorid, promotion, promotiontitle, promotiondate, imghead, imgalt, idauthor, idcategory, category.id AS categoryid, categoryname, authorname, imghead FROM promotion
			INNER JOIN author
			ON author.id = idauthor
			INNER JOIN category
			ON idcategory = category.id
			WHERE premoderation = "YES" AND idauthor = ';

	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = $select.$idAuthor;
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода содержимого статьи ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$authors[] =  array ('id' => $row['id'], 'authorname' => $row['authorname'], 'www' => $row['www'],
							'accountinfo' => $row['accountinfo'], 'avatar' => $row['avatar']);
	}	
	
	/*Если страница отсутствует. Ошибка 404*/
	if (empty ($authors))
	{
		header ('Location: ../page-not-found/');//перенаправление обратно в контроллер index.php
		exit();	
	}
	
	try
	{
		$sql = $selectPosts.$idAuthor;
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода содержимого статьи ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$promotions[] =  array ('id' => $row['promotionid'], 'idauthor' => $row['authorid'], 'text' => $row['promotion'], 'promotiontitle' =>  $row['promotiontitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt'],
						'promotiondate' =>  $row['promotiondate'], 'authorname' =>  $row['authorname'], 
						'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid']);
	}	
	
	$title = 'Профиль пользователя '.$row['authorname'];//Данные тега <title>
	$headMain = $row['authorname'];
	$robots = 'all';
	$descr = 'Вся информация о пользователе '.$row['authorname']. ' портала imagoz.ru';
	
	/*Возвращение id автора для вызова функции изменения пароля*/
		
	if (isset($_SESSION['loggIn']))
	{
		$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
	}
	
	/*Управление аккаунтом*/
	if ($selectedAuthor == $idAuthor)
	{
		$updAndDelAvatar = '<form action = "?" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$selectedAuthor.'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Обновить аватар">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Удалить аватар">
								</div>
							</form>';//Вывод кнопок удаления-обновления аватара
		
		$changePass = '<a href="../account/?change">Сменить пароль</a>';//запуск ф-ции "Смена пароля"
		
		$updAccountInfo = '<form action = "?" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$selectedAuthor.'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Обновить информацию профиля">
								</div>
							</form>';//запуск обновления информации профиля
		
		/*Команда SELECT, вывод счёта автора*/
		try
		{
			$sql = 'SELECT score FROM author WHERE author.id = '.$_SESSION['idAuthor'];
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора информации о счёте автора : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();
	
		$score = '<strong> | Мой счёт '.round($row['score'], 2, PHP_ROUND_HALF_DOWN).'</strong>';	//вывод счёта
		
		/*Команда SELECT, Вывод платёжной системы и кошелька*/
		try
		{
			$sql = 'SELECT idpaysystem, paysystemname, ewallet, updewalletdate FROM author 
					INNER JOIN paysystem ON idpaysystem = paysystem.id 
					WHERE author.id = '.$_SESSION['idAuthor'];
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора информации о счёте автора : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}
		
		$row = $s -> fetch();
		
		$idPaySystem = $row['idpaysystem'];
		
		if (isset ($idPaySystem))
		{	
			$openTable = '<table>';
			$ewallet = '<tr> <td> Номер счёта </td><td>'.$row['ewallet'].'</td> </tr>';
			$paysystemName = '<tr> <td> Платёжная система </td><td>'.$row['paysystemname'].'</td> </tr>';
			$updEwalletDate = '<tr> <td> Дата обновления </td><td>'.$row['updewalletdate'].'</td> </tr>';
			$closeTable = '</table>';
		}
		
		else
		{
			$ewallet = '';
			$paysystemName = '';
			$updEwalletDate = '';
			$openTable = '';
			$closeTable = '';
		}
		
		$payForm = '<form action = "../admin/payment/" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$selectedAuthor.'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Вывести средства">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Обновить платёжные реквизиты">
								</div>
							</form>';// вывод средств и обновление реквизитов
		
		$payFormIn = '<form action = "../admin/payment/" method = "post">
								<div>
									<input type = "hidden" name = "id" value = "'.$selectedAuthor.'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Пополнить счёт">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "История платежей">
								</div>
							</form>';// перечислить средства на счёт
	}
	
	else
	{
		$updAndDelAvatar = '';
		$changePass ='';
		$updAccountInfo = ''; 
		$addRoleAdvertiser ='';
		$score = '';
		$payForm = '';	
		$payFormIn = '';
		$ewallet = '';
		$paysystemName = '';
		$updEwalletDate = '';
		$openTable = '';
		$closeTable = '';
	}
	
	/*Вывод кнопки "Написать сообщение"*/
	if ($selectedAuthor != $idAuthor)
	{
		$mainMessagesForm = '<form action = "../mainmessages/addupdmainmessage/#bottom" method = "post">
								<div>
									<input type = "hidden" name = "idto" value = "'.$idAuthor.'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Написать сообщение">
								</div>
							</form>';// написать сообщение!
	}
	
	else
	{
		$mainMessagesForm = '';
	}
	
	/*Вывод новостей и статей автора*/
	/*Команда SELECT, возвращение роли автора*/
	try
	{
		$sql = 'SELECT idrole FROM author 
				INNER JOIN authorrole ON author.id = idauthor
				INNER JOIN role ON idrole = role.id WHERE author.id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации о роли автора : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	$row = $s -> fetch();
	$authorRole = $row['idrole'];

	/*Если у автора роль автора, администратора и т. д., то выводится список его новостей и статей*/

	if	(($authorRole == 'Автор') || ($authorRole == 'Администратор'))
	{
		include MAIN_FILE . '/includes/db.inc.php';
		
		/*Выбор новостей автора*/
		try
		{
			$sql = 'SELECT newsblock.id AS newsid, newstitle FROM author
					INNER JOIN newsblock ON author.id = idauthor 
					WHERE premoderation = "YES" AND author.id = '.$_SESSION['idAuthor'].' ORDER BY newsblock.id DESC LIMIT 3';
			$result = $pdo->query($sql);
		}
	
		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка вывода статей автора ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		/*Вывод результата в шаблон*/
		foreach ($result as $row)
		{
			$newsIn[] =  array ('id' => $row['newsid'], 'newstitle' => $row['newstitle']);
		}	
		
		/*Если массив пустой для избежания ошибки "Warning: Invalid argument supplied for foreach()"*/
		if (!isset ($newsIn))
		{
			$newsIn[] =  array ('id' => 'Нет значения', 'newstitle' => '');
		}
		
		/*Выбор статей автора*/
		try
		{
			$sql = 'SELECT posts.id AS postid, posttitle FROM author
					INNER JOIN posts ON author.id = idauthor 
					WHERE premoderation = "YES" AND author.id = '.$_SESSION['idAuthor'].' ORDER BY posts.id DESC LIMIT 3';
			$result = $pdo->query($sql);
		}
	
		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка вывода статей автора ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		/*Вывод результата в шаблон*/
		foreach ($result as $row)
		{
			$posts[] =  array ('id' => $row['postid'], 'posttitle' => $row['posttitle']);
		}	
		
		/*Если массив пустой для избежания ошибки "Warning: Invalid argument supplied for foreach()"*/
		if (!isset ($posts))
		{
			$posts[] =  array ('id' => 'Нет значения', 'posttitle' => '');
		}
		
		/*Вывод ранга автора*/
		try
		{
			$sql = 'SELECT rangname, pricenews, pricepost, rating FROM author
					INNER JOIN rang ON rang.id = idrang 
					WHERE author.id = '.$_SESSION['idAuthor'];
			$result = $pdo->query($sql);
		}
	
		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка вывода ранга ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}
		
		foreach ($result as $row)
		{
			$rangName[] =  array ('rangname' => $row['rangname'], 'pricenews' => $row['pricenews'], 'pricepost' => $row['pricepost'],
								  'rating' => $row['rating']);
		}	
		
		$rangView = (string) $row['rangname'];//Если присвоен соответствующий статус, то выводиться ранг
		$rating = (string) $row['rating'];//Если присвоен соответствующий статус, то выводиться рейтинг
		
		if ($selectedAuthor == $idAuthor)
		{
			$prices = '<p><strong> Цена новости: '.$row['pricenews'].' за 1000 зн. | Цена статьи: '.$row['pricepost'].' за 1000 зн. </strong></p>';
		}
		
		else
		{
			$prices = '';
		}
	}
	
	/*Присвоить - удалить ранг "Автор" / Назначить премию или бонус автору*/
	
	if (!isset($_SESSION['loggIn']))//Если пользователь не вошёл в систему
	{	
		$addRole = '';
		$addBonus = '';
	}
	
	elseif (!userRole('Администратор'))//Если пользователь не Администратор
	{
		$addRole = '';
		$addBonus = '';
	}
		
	else
	{
		if	($authorRole == 'Автор')
		{		
			$addRole = '<form action=" " metod "post">
							<input type = "hidden" name = "id" value = "'.$_SESSION['idAuthor'].'">
							<input type = "submit" name = "delrang" value = "Удалить ранг Автор" class="btn btn-primary btn-sm"> 
					 	 </form>';
			
			$addBonus = '<form action = "../admin/payment/" method = "post">
									<input type = "hidden" name = "id" value = "'.$_SESSION['idAuthor'].'">
									<input type = "submit" name = "action" class="btn btn-primary btn-sm" value = "Назначить премию или бонус">
							</form>';//если у автора статус "Автор", то ему можно назначить премию или бонус
		}
		
		else
		{		
			$addRole = '<form action=" " metod "post">
							<input type = "hidden" name = "id" value = "'.$_SESSION['idAuthor'].'">
							<input type = "submit" name = "addrang" value = "Присвоить ранг Автор" class="btn btn-primary btn-sm"> 
					 	 </form>';
			
			$addBonus = '';//кнопка не отображается
		}
	}
	
	/*Присвоить статус автора*/
	if (isset($_GET['addrang']))
	{
		include MAIN_FILE . '/includes/db.inc.php';
		
		try
		{
			$sql = 'INSERT authorrole SET 
					idauthor = '.$_SESSION['idAuthor'].' ,
					idrole = "Автор"';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}
		
		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка обновления информации news'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
			
		}
		
		header ('Location: ../account/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
		exit();	
	}
	
	/*Присвоить статус автора*/
	if (isset($_GET['delrang']))
	{
		include MAIN_FILE . '/includes/db.inc.php';
		
		try
		{
			$sql = 'DELETE FROM authorrole WHERE 
					idauthor = '.$_SESSION['idAuthor'].' AND
					idrole = "Автор"';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}
		
		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка обновления информации news'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
			
		}
		
		header ('Location: ../account/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
		exit();	
	}
	
	/*Вывод комментариев*/
	/*Постраничный вывод информации*/
		
	$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
	$onPage = 10;// количество статей на страницу
	$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу

	try
	{
		$sql = 'SELECT comments.id, author.id AS idauthor, comment, imghead, imgalt, subcommentcount, commentdate, authorname FROM comments 
		INNER JOIN author 
		ON idauthor = author.id 
		WHERE idaccount = '.$idAuthor.' 
		ORDER BY comments.id DESC LIMIT '.$shift.' ,'.$onPage;//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error table in mainpage' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$comments[] =  array ('id' => $row['id'], 'idauthor' => $row['idauthor'], 'text' => $row['comment'], 'date' => $row['commentdate'], 'authorname' => $row['authorname'],
								'subcommentcount' => $row['subcommentcount'], 'imghead' => $row['imghead'], 'imgalt' => $row['imgalt']);
	}
	
	//@session_start();//Открытие сессии для сохранения id комментария
	
	//$_SESSION['idCommentForDel'] = (int) $row['id'];
	
	/*Определение количества статей*/
	$sql = "SELECT count(*) AS all_articles FROM comments WHERE idaccount = ".$idAuthor;
	$result = $pdo->query($sql);
	
	foreach ($result as $row)
	{
			$numPosts[] = array('all_articles' => $row['all_articles']);
	}
	
	$countPosts = $row["all_articles"];
	$pagesCount = ceil($countPosts / $onPage);
	
	include 'account.html.php';
	exit();
}

/*Добавление комментария*/
if (isset ($_GET['addcomment']))
{
	$title = 'Добавление записи на стену | imagoz.ru';//Данные тега <title>
	$headMain = 'Добавление записи';
	$robots = 'noindex, follow';
	$descr = 'Форма добавления записи';
	$padgeTitle = 'Новая запись';// Переменные для формы "Новая статья"
	$action = 'addform';	
	$text = '';
	$imgalt = '';
	$idauthor = '';
	$id = '';
	$button = 'Добавить запись';
	$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
	
	if (isset($_SESSION['loggIn']))
	{
		$authorComment = authorLogin ($_SESSION['email'], $_SESSION['password']);//возвращает имя автора
		
		include 'formwall.html.php';
		exit();
	}	
	
	else
	{
		$title = 'Ошибка добавления записи';//Данные тега <title>
		$headMain = 'Ошибка добавления записи';
		$robots = 'noindex, follow';
		$descr = '';
		$commentError = $commentError = '<a href="//'.MAIN_URL.'/admin/registration/?log">Авторизируйтесь</a> в системе или 
						 <a href="//'.MAIN_URL.'/admin/registration/?reg">зарегестрируйтесь</a> для того, чтобы добавить сообщение на стену!';//Вывод сообщения в случае невхода в систему
		
		include 'commentfail.html.php';
		exit();
	}	
}

/*Обновление комментария*/
if (isset ($_POST['action']) && $_POST['action'] == 'Редактировать')
{		
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	try
	{
		$sql = 'SELECT * FROM comments  
		WHERE id = :idcomment';//Вверху самое последнее значение
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcomment', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error table in mainpage' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();	
	
	$title = 'Редактирование записи | imagoz.ru';//Данные тега <title>
	$headMain = 'Редактирование записи';
	$robots = 'noindex, follow';
	$descr = 'Форма редактирования записи';
	$action = 'editform';	
	$text = $row['comment'];
	$imgalt = $row['imgalt'];
	$id = $row['id'];
	$button = 'Обновить запись';
	$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
	
	@session_start();//Открытие сессии для сохранения названия файла изображения
	
	$_SESSION['imghead'] = $row['imghead'];
	
	include 'formwall.html.php';
	exit();
}
	
/*команда INSERT  - добавление комментария в базу данных*/
if (isset($_GET['addform']))//Если есть переменная addform выводится форма
{
	/*Загрузка изображения на стену*/
	
	$fileNameScript = 'comm-'. time();//имя файла новости/статьи
	$filePathScript = '/images/';//папка с изображениями для новости/статьи
	
	/*Загрузка скрипта добавления файла*/
	include MAIN_FILE . '/includes/uploadfile.inc.php';
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
		
	/*Возвращение id автора*/
		
	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
		
	try
	{
		$sql = 'INSERT INTO comments SET 
			comment = :comment,	
			commentdate = SYSDATE(),
			imgalt = :imgalt,
			imghead = '.'"'.$fileName.'"'.', '.
			'idauthor = '.$selectedAuthor.','.
			'idaccount = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':comment', $_POST['comment']);//отправка значения
		$s -> bindValue(':imgalt', $_POST['imgalt']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: ../account/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
	exit();	
}
	
/*UPDATE - обновление текста комментария*/

if (isset($_GET['editform']))//Если есть переменная editform выводится форма
{
	if (!is_uploaded_file($_FILES['upload']['tmp_name']))//если файл не загружен, оставить старое имя
	{
		$fileName = $_SESSION['imghead'];
	}
	
	else
	{
		/*Удаление старого файла изображения*/
		$fileName = $_SESSION['imghead'];
		$delFile = $_SERVER['DOCUMENT_ROOT'] . '/images/'.$fileName;//путь к файлу для удаления
		unlink($delFile);//удаление файла
		
		$fileNameScript = 'comm-'. time();//имя файла новости/статьи
		$filePathScript = '/images/';//папка с изображениями для новости/статьи
		
		/*Загрузка скрипта добавления файла*/
		include MAIN_FILE . '/includes/uploadfile.inc.php';
	}
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE comments SET 
			comment = :comment,
			imgalt = :imgalt,
			imghead = '.'"'.$fileName.'"'.
			' WHERE id = :idcomment';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcomment', $_POST['id']);//отправка значения
		$s -> bindValue(':comment', $_POST['comment']);//отправка значения
		$s -> bindValue(':imgalt', $_POST['imgalt']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
		
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления информации comment'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	header ('Location: ../account/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
	exit();
}

/*DELETE - удаление комментария*/

if (isset ($_POST['action']) && $_POST['action'] == 'Del')	
{	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, imghead FROM comments WHERE id = :idcomment';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcomment', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора id и заголовка newsblock : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Удаление записи';//Данные тега <title>
	$headMain = 'Удаление записи';
	$robots = 'noindex, follow';
	$descr = '';
	$action = 'delete';
	$posttitle = 'Запись';
	$id = $row['id'];
	$button = 'Удалить';
	
	@session_start();//Открытие сессии для сохранения названия файла изображения
	
	$_SESSION['imghead'] = $row['imghead'];
	
	include 'delete.html.php';
}
	
if (isset ($_GET['delete']))
{
	/*Удаление изображения заголовка*/
	$fileName = $_SESSION['imghead'];
	$delFile = $_SERVER['DOCUMENT_ROOT'] . '/images/'.$fileName;//путь к файлу для удаления
	unlink($delFile);//удаление файла
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'DELETE FROM comments WHERE id = :idcomment';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcomment', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка удаления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	/*Удаление ответов*/
	try
	{
		$sql = 'DELETE FROM subcomments WHERE idcomment = :idcomment' ;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcomment', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка удаления ответов '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: ../account/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
	exit();
}	

/*Смена пароля*/

if (isset($_GET['change']))
{
	$title = 'Введите старый пароль!';//Данные тега <title>
	$headMain = 'Введите старый пароль';
	$robots = 'noindex, nofollow';
	$descr = '';
	$action = 'addnewpass';
	$errLog ='';
	$password = ' ';
	$button = 'Ввод';
	
	include 'oldpass.html.php';
}
	
if (isset ($_GET['addnewpass']))
{	
	if ($_POST['password'] != '')
	{
		$password = md5($_POST['password'] . 'fgtn');
	}
	
	/*Если старый пароль не совпадает*/
	if ($password != $_SESSION['password'])
	{
		$title = 'Введите старый пароль!';//Данные тега <title>
		$headMain = 'Введите старый пароль';
		$robots = 'noindex, nofollow';
		$descr = '';
		$action = 'addnewpass';
		$errLog ='Пароли не совпадают';
		$password = ' ';
		$button = 'Ввод';
		
		include 'oldpass.html.php';
	}	
	
	else
	{
		$title = 'Введите новый пароль!';//Данные тега <title>
		$headMain = 'Введите новый пароль';
		$robots = 'noindex, nofollow';
		$descr = '';
		$action = 'changepass';
		$errLog ='';
		$password1 = ' ';
		$password2 = '';
		$button = 'Ввод';
		
		include 'newpass.html.php';
	}
}

if (isset ($_GET['changepass']))
{
	if (($_POST['password'] != $_POST['password2']) || ($_POST['password'] == ''))
	{
		$title = 'Введите новый пароль!';//Данные тега <title>
		$headMain = 'Введите новый пароль';
		$robots = 'noindex, nofollow';
		$descr = '';
		$action = 'changepass';
		$errLog ='Пароли должны совпадать или поле не должно быть пустым';
		$password1 = ' ';
		$password2 = '';
		$button = 'Ввод';
		
		include 'newpass.html.php';
	}
	
	elseif ($_POST['password'] != '')
	{
		/*Обновление пароля*/
		/*Подключение к базе данных*/
		
		include MAIN_FILE . '/includes/db.inc.php';
	
		$password = md5($_POST['password'] . 'fgtn');
		
		try
		{
			$sql = 'UPDATE author SET password = :password WHERE id = :id';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> bindValue(':password', $password);//отправка значения
			$s -> bindValue(':id', $_SESSION['idAuthor']);//отправка значения
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

		try
		{
			$sql = 'SELECT password FROM author WHERE id = :id';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> bindValue(':id', $_SESSION['idAuthor']);//отправка значения
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
		
		$row = $s -> fetch();
			
		$_SESSION['password'] = $row['password'];
		
		$title = 'Смена пароля прошла успешно';//Данные тега <title>
		$headMain = 'Смена пароля прошла успешно!';
		$robots = 'noindex, nofollow';
		$descr = 'Сообщение об успешной смене пароля';
		$loggood = 'Вы успешно сменили пароль!';
	
		include MAIN_FILE.'/admin/accessgood.html.php';
		exit();
	}
}

/*Обновить аватар*/

if (isset ($_POST['action']) && $_POST['action'] == 'Обновить аватар')
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, avatar FROM author WHERE id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idnews', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора данных аватара: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$title = 'Обновление аватара';//Данные тега <title>
	$headMain = 'Обновление аватара';
	$robots = 'noindex, nofollow';
	$descr = '';
	$action = 'updavatar';
	$avatar = $row['avatar'];
	$id = $row['id'];
	$button = 'Обновить аватар';
	$errorForm = '';
	
	@session_start();//Открытие сессии для сохранения названия файла изображения
	
	$_SESSION['avatar'] = $row['avatar'];
	
	include 'updavatar.html.php';
	exit();
}

/*UPDATE - обновление аватара*/

if (isset($_GET['updavatar']))//Если есть переменная editform выводится форма
{
	if (!is_uploaded_file($_FILES['upload']['tmp_name']))//если файл не загружен, оставить старое имя
	{
		$fileName = $_SESSION['avatar'];
	}
	
	else
	{
		/*Удаление старого файла изображения*/
		
		if ($_SESSION['avatar'] != 'ava-def.jpg')
		{
			$fileName = $_SESSION['avatar'];
			$delFile = MAIN_FILE . '/avatars/'.$fileName;//путь к файлу для удаления
			unlink($delFile);//удаление файла
		}
		
		$fileNameScript = 'ava-'. time();//имя файла новости/статьи
		$filePathScript = '/avatars/';//папка с изображениями для новости/статьи
		
		/*Загрузка скрипта добавления файла*/
		include MAIN_FILE . '/includes/uploadfile.inc.php';
	}
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE author SET 
			avatar = '.'"'.$fileName.'"'.'WHERE id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления аватара'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: //'.MAIN_URL);//перенаправление обратно в контроллер index.php
	exit();
}

/*Удаление аватара*/
if (isset ($_POST['action']) && $_POST['action'] == 'Удалить аватар')
{	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT id, avatar FROM author WHERE id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idnews', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора аватара : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	if ($row['avatar'] == "ava-def.jpg")
	{
		$title = 'Удаление аватара';//Данные тега <title>
		$headMain = 'Удаление аватара';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Нельзя удалить аватар по умолчанию!';
	
		include 'error.html.php';
	}
	
	else
	{
		
		$title = 'Удаление аватара';//Данные тега <title>
		$headMain = 'Удаление аватара';
		$robots = 'noindex, nofollow';
		$descr = '';
		$action = 'delava';
		$posttitle = 'Аватар';
		$button = 'Удалить';
		
		@session_start();//Открытие сессии для сохранения названия файла изображения
	
		$_SESSION['avatar'] = $row['avatar'];
	
		include 'delete.html.php';
	}
}

if (isset ($_GET['delava']))
{
	
	/*Удаление аватара*/
	$fileName = $_SESSION['avatar'];
	$delFile = MAIN_FILE . '/avatars/'.$fileName;//путь к файлу для удаления
	unlink($delFile);//удаление 
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE author SET 
			avatar = "ava-def.jpg" WHERE id = '.$_SESSION['idAuthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка удаления аватара '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: //'.MAIN_URL);//перенаправление обратно в контроллер index.php
	exit();
}
/*Обновление информации профиля*/

/*Обновление информации о профиле*/
if (isset ($_POST['action']) && $_POST['action'] == 'Обновить информацию профиля')
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT author.id, authorname, email, www, accountinfo FROM author WHERE author.id = :id';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации аккаунта : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}	

	$row = $s -> fetch();
	
	$title = 'Редактирование профиля';//Данные тега <title>
	$headMain = 'Редактировать профиль пользователя '.$row['authorname'];
	$robots = 'noindex, nofollow';
	$descr = '';
	$padgeTitle = 'Редактировать данные пользователя';// Переменные для формы "Новый автор"
	$action = 'updacc';
	$authorname = $row['authorname'];
	$email = $row['email'];
	$www = $row['www'];
	$accountinfo = $row['accountinfo'];
	$idauthor = $row['id'];
	$button = 'Обновить информацию об авторе';
	$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
	
	include 'form.html.php';
	exit();
	
}

	/*Команда UPDATE*/
if (isset ($_GET['updacc']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE author SET www = :www, accountinfo = :accountinfo WHERE author.id = :id';// псевдопеременная получающая значение из формы
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $_POST['id']);//отправка значения
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
	
	header ('Location: ..'.'/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
	exit();
}

/*Присвоение роли "Рекламодатель"*/
if (isset ($_POST['action']) && $_POST['action'] == 'Стать рекламодателем')
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT author.id FROM author WHERE author.id = :id';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации аккаунта : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}	

	$row = $s -> fetch();
	
	$title = 'Стать рекламодателем';//Данные тега <title>
	$headMain = 'Стать рекламодателем';
	$robots = 'noindex, nofollow';
	$descr = '';
	$padgeTitle = 'Стать рекламодателем портала';// Переменные для формы "Новый автор"
	$action = 'addrole';
	$idauthor = $row['id'];
	$button = 'Получить статус рекламодателя';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT promotionprice FROM promotionprice';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора цены промоушена : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}	
	
	$row = $s -> fetch();
	
	$promotionPrice = $row['promotionprice'];
	
	$annotation = '<p>Вы собираетесь присвоить себе ранг рекламодателя на портале. Это позволит Вам писать платные материалы рекламного характера для продвиения своего бренда, 
			товара, услуги и т. п. Стоимость публикации составляет на данный момент <strong>'.$promotionPrice.' рубля</strong>. </p>
			
			 <p>Чтобы получить возможность разместить статью Вам нужно пополнить свой счёт минимум на сумму достаточную для одного материала. Публикация должна максимально 
			 соответствовать ряду требований:</p>
			 <ul>
			 	<li>Органично вписываться в тематику портала, должна хотябы косвенно подходить под одну из рубрик (продвижение высокотехнологичного товара, 
					реклама научного, игрового, hi-tech-портала, продажа ретро-игр, игровых журналов и т.п., главное оформить это в статью и интересно преподать).</li>
				<li>Публикация должна быть написана грамотным русским языком.</li>
				<li>Уникальность материала 90-100%.</li>
			</ul>
			<p>Все публикации проходят премодерацию, и в случае не соответствия требоаниям - отклоняться. В этом случае деньги вернуться на Ваш счёт и при необходимости 
		 	могут быть выведены обратно, либо использованы для повторной попытки опубликоать статью.</p>';
	
	include 'becomadvertiser.html.php';
	exit();
}

	/*Команда UPDATE - обновление роли*/
if (isset ($_GET['addrole']))
{
	include MAIN_FILE . '/includes/db.inc.php';
		
		try
		{
			$sql = 'INSERT authorrole SET 
					idauthor = '.$_SESSION['idAuthor'].' ,
					idrole = "Рекламодатель"';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}
		
		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка обновления информации news'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
			
		}
		
		header ('Location: ../account/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
		exit();	
}

/*Отказаться от роли "Рекламодатель"*/
if (isset ($_POST['action']) && $_POST['action'] == 'Отказаться от роли рекламодателя')
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT author.id FROM author WHERE author.id = :id';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':id', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора информации аккаунта : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}	

	$row = $s -> fetch();
	
	$title = 'Отказаться от роли рекламодателя';//Данные тега <title>
	$headMain = 'Отказаться от роли рекламодателя';
	$robots = 'noindex, nofollow';
	$descr = '';
	$padgeTitle = 'Отказаться от роли рекламодателя';// Переменные для формы "Новый автор"
	$action = 'delrole';
	$idauthor = $row['id'];
	$button = 'Отказаться от роли рекламодателя';
	$annotation = '';
	
	include 'becomadvertiser.html.php';
	exit();
}

	/*Команда UPDATE - обновление роли*/
if (isset ($_GET['delrole']))
{
	include MAIN_FILE . '/includes/db.inc.php';
		
	try
	{
		$sql = 'DELETE FROM authorrole WHERE 
				idauthor = '.$_SESSION['idAuthor'].' AND
				idrole = "Рекламодатель"';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
		
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления информации news'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
			
	}
		
	header ('Location: ../account/?id='.$_SESSION['idAuthor']);//перенаправление обратно в контроллер index.php
	exit();	
}