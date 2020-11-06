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

/*Загрузка содержимого статьи*/
if (isset ($_GET['id']))
{
	$idPost = $_GET['id'];
	
	@session_start();//Открытие сессии для сохранения id статьи
	
	$_SESSION['idpost'] = $idPost;
	$select = 'SELECT posts.id AS postid, author.id AS idauthor, post, posttitle, imghead, videoyoutube, viewcount, averagenumber, description, imgalt, postdate, authorname, category.id AS categoryid, categoryname FROM posts 
			   INNER JOIN author ON idauthor = author.id 
			   INNER JOIN category ON idcategory = category.id WHERE premoderation = "YES" AND zenpost = "YES" AND posts.id = ';

	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = $select.$idPost;
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
		$posts[] =  array ('id' => $row['postid'], 'idauthor' => $row['idauthor'], 'text' => $row['post'], 'posttitle' =>  $row['posttitle'], 'imgalt' =>  $row['imgalt'], 'imghead' => $row['imghead'],
							'postdate' => $row['postdate'], 'viewcount' => $row['viewcount'], 'averagenumber' => $row['averagenumber'], 'description' => $row['description'],
							'authorname' => $row['authorname'], 'categoryname' =>  $row['categoryname'], 'categoryid' => $row['categoryid'], 'videoyoutube' => $row['videoyoutube']);
	}	
	
	@session_start();//Открытие сессии для сохранения categoryid
	
	$_SESSION['categoryid'] = $row['categoryid'];
	
	$title = $row['posttitle'].' | imagoz.ru';//Данные тега <title>
	$headMain = $row['posttitle'];
	$robots = 'noindex, nofollow';
	$descr = $row['description'];
	$authorComment = '';
	
	/*Вывод видео в статью*/
	if ((isset($row['videoyoutube'])) && ($row['videoyoutube'] != ''))
	{
		$video = '<iframe width="85%" height="320px" src="'.$row['videoyoutube'].'" frameborder="0" allowfullscreen></iframe>';
	}
	
	else
	{
		$video = '';
	}
	
	/*Обновление значения счётчика*/
	
	$updateCount = 'UPDATE posts SET viewcount = viewcount + 1 WHERE id = ';
	
	try
	{
		$sql = $updateCount.$idPost;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка счётчика ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	/*Вывод тематик(тегов)*/
	
	/*Команда SELECT*/
	try
	{
		$sql = 'SELECT meta.id, metaname FROM posts 
				INNER JOIN metapost ON posts.id = idpost 
				INNER JOIN meta ON meta.id = idmeta 
				WHERE posts.id = '.$idPost;//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода списка тегов ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$metas[] =  array ('id' => $row['id'], 'metaname' => $row['metaname']);
	}
	
 /*Скрипт оценки статьи*/

	/*Вывод панели оценок*/
		
	/*Возвращение id автора*/
		
	/*Подключение к базе данных*/
	if (isset($_SESSION['loggIn']))
	{
		MAIN_FILE . '/includes/db.inc.php';
		
		$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));;//id автора
	}
		
	else
	{
		$selectedAuthor = 0;//id автора
	}
	
	@session_start();//Открытие сессии для сохранения id автора
	
	$_SESSION['idauthor'] = $selectedAuthor;
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	$votedPost = (int)$_SESSION['idpost'];
	
	try
	{
		$sql = 'SELECT * FROM votedauthor WHERE idauthor = '.$selectedAuthor.' AND idpost = '.$votedPost;
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора данных из votedauthor ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
		$authorID2[] =  array ('idauthor' => $row['idauthor'], 'idpost' => $row['idpost']);
	}	
		
	if(!isset ($row['idauthor']))
	{		
		$votedAuthor = '';
	}
	
	else
	{
		$votedAuthor = (int)$row['idauthor'];//id автора, который проголосовал
	}	
	
	if (!isset ($row['idpost']))//если переменная отсутствует
	{
		$votedPost = '';
	}
	
	else
	{		
		$votedPost = (int)$row['idpost'];//id статьи, за которую проголосовали
	}
	
	/*Условия вывода панели голосования*/
	if (($votedAuthor == $selectedAuthor) && ($votedPost == $_SESSION['idpost']) || (!isset($_SESSION['loggIn'])))
	{
		$votePanel = '';
	}
	
	elseif ((isset($_SESSION['loggIn'])) && ($votedAuthor != $selectedAuthor))
	{
		$votePanel = '<form action=" " metod "post">
					  
					  Оцените статью: 
						<input type = "hidden" name = "id" value = "'.$_SESSION['idpost'].'">
						<input type = "submit" name = "vote" value = "5" class="btn btn-primary btn-sm"> 
						<input type = "submit" name = "vote" value = "4" class="btn btn-primary btn-sm"> 
						<input type = "submit" name = "vote" value = "3" class="btn btn-primary btn-sm"> 
						<input type = "submit" name = "vote" value = "2" class="btn btn-primary btn-sm"> 
						<input type = "submit" name = "vote" value = "1" class="btn btn-primary btn-sm"> 
					  </form>';
	}
	
	/*Оценка статьи*/
	if (isset($_GET['vote']))
	{
		$vote = $_GET['vote'];//значение оценки
		$averageNumber = 0;//среднее значение
		
		$updateVoteCount = 'UPDATE posts SET votecount = votecount + 1 WHERE id = '.$_SESSION['idpost'];//обновление числа проголосовавших
		$updateTotalNumber = 'UPDATE posts SET totalnumber = totalnumber + '.$vote.' WHERE id = '.$_SESSION['idpost'];//обновление общего числа
		$updateAverageNumber = 'UPDATE posts SET averagenumber = totalnumber/votecount WHERE id = '.$_SESSION['idpost'];//обновление среднего значения в БД
		$insertToVotedAuthor ='INSERT INTO votedauthor SET idpromotion = 0, idnews = 0, idpost = '.$_SESSION['idpost'].', idauthor = '.$_SESSION['idauthor'].', vote = '.$vote;//обновление таблицы проголосовавшего автора
							
		/*Подключение к базе данных*/
		include MAIN_FILE . '/includes/db.inc.php';
		
		try
		{
			$pdo->beginTransaction();//инициация транзакции
			
			$sql = $updateVoteCount;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			
			$sql = $updateTotalNumber;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			
			$sql = $updateAverageNumber;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			
			$sql = $insertToVotedAuthor;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			
			$pdo->commit();//подтверждение транзакции			
		}
		
		catch (PDOException $e)
		{
			$pdo->rollBack();//отмена транзакции
			
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Error transaction при голосовании '.$e -> getMessage();// вывод сообщения об ошибке в переменой $e;// вывод сообщения об ошибке в переменой $e;// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();		
		}
		
		header ('Location: ../viewpost/?id='.$_SESSION['idpost']);//перенаправление обратно в контроллер index.php
		exit();
	}
	
	/*Вывод кнопок "Обновить" | "Удалить" | "Снять с публикации"(Возможно убрать эту кнопку для всех, кромке админа и редактора)"*/
	
	if ((isset($_SESSION['loggIn'])) && (userRole('Администратор')))
	{
		$delAndUpd = "<form action = '../admin/addupdpost/' method = 'post'>
			
						Действия с материалом:
						<input type = 'hidden' name = 'id' value = '".$_SESSION['idpost']."'>
						<input type = 'submit' name = 'action' value = 'Upd' class='btn btn-primary btn-sm'>
						<input type = 'submit' name = 'action' value = 'Del' class='btn btn-primary btn-sm'>
					  </form>";
		
		$premoderation = "<form action = '../admin/premoderation/postpremoderationstatus/' method = 'post'>
			
						Статус публикации:
						<input type = 'hidden' name = 'id' value = '".$_SESSION['idpost']."'>
						<input type = 'submit' name = 'action' value = 'Снять с публикации' class='btn btn-primary btn-sm'>
					  </form>";				
	}
	
	else
	{
		$delAndUpd = '';
		$premoderation = '';
	}	
	
	/*Вывод кнопки "Рекомендовать статью"*/
	if (isset($_SESSION['loggIn']))
	{
		$recommendation = "<form action = '?' method = 'post'>
						<input type = 'hidden' name = 'id' value = '".$_SESSION['idpost']."'>
						<input type = 'submit' name = 'action' value = 'Рекомендовать статью' class='btn btn-primary btn-sm'>
					  </form>";
	}
	
	else
	{
		$recommendation = '<strong>Вы можете <a href="../admin/registration/?log">авторизироваться</a> в системе или 
						 <a href="../admin/registration/?reg">зарегестрироваться</a> для того, чтобы рекомендовать статью на главной странице!</strong>';
	}
	
	/*Вывод похожих материалов*/
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'SELECT id, posttitle FROM posts WHERE idcategory = '.$_SESSION['categoryid'].' AND premoderation = "YES" ORDER BY rand() LIMIT 3';
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода заголовка похожей статьи ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$similarPosts[] =  array ('id' => $row['id'], 'posttitle' =>  $row['posttitle']);
	}	
	
	/*Вывод комментариев*/
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Постраничный вывод информации*/
		
	$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
	$onPage = 10;// количество статей на страницу
	$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу

	try
	{
		$sql = 'SELECT comments.id, comment, commentdate, subcommentcount, authorname, avatar, author.id AS idauthor FROM comments 
		INNER JOIN author 
		ON idauthor = author.id 
		WHERE idpost = '.$idPost.' 
		ORDER BY comments.id DESC LIMIT '.$shift.' ,'.$onPage;//Вверху самое последнее значение
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка вывода комментариев ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$comments[] =  array ('id' => $row['id'], 'text' => $row['comment'], 'date' => $row['commentdate'], 'idauthor' => $row['idauthor'],
							  'authorname' => $row['authorname'], 'subcommentcount' => $row['subcommentcount'], 'avatar' => $row['avatar']);
	}
	
	/*Определение количества статей*/
	try
	{
		$sql = "SELECT count(*) AS all_articles FROM comments WHERE idpost = ".$idPost;
		$result = $pdo->query($sql);
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка подсчёта комментариев ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	foreach ($result as $row)
	{
			$numPosts[] = array('all_articles' => $row['all_articles']);
	}
	
	$countPosts = $row["all_articles"];
	$pagesCount = ceil($countPosts / $onPage);
	
	include 'viewzenpost.html.php';
	exit();		
}
	
/*Добавление комментария*/
if (isset ($_GET['addcomment']))
{
	$title = 'Добавление комментария | imagoz.ru';//Данные тега <title>
	$headMain = 'Добавление комментария';
	$robots = 'noindex, follow';
	$descr = 'Форма добавления комментария';
	$padgeTitle = 'Новый комментарий';// Переменные для формы "Новая статья"
	$action = 'addform';	
	$text = '';
	$idauthor = '';
	$id = '';
	$button = 'Добавить комментарий';
	
	if (isset($_SESSION['loggIn']))
	{
		$authorComment = authorLogin ($_SESSION['email'], $_SESSION['password']);//возвращает имя автора
		
		include 'form.html.php';
		exit();
	}	
	
	else
	{
		$title = 'Ошибка добавления комментария';//Данные тега <title>
		$headMain = 'Ошибка добавления комментария';
		$robots = 'noindex, follow';
		$descr = '';
		$commentError = '<a href="/admin/registration/?log">Авторизируйтесь</a> в системе или 
						 <a href="/admin/registration/?reg">зарегестрируйтесь</a> для того, чтобы оставить комментарий!';//Вывод сообщения в случае невхода в систему
		
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
		$error = 'Ошибка выбора комментария для обновления ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();	
	
	$title = 'Редактирование комментария | imagoz.ru';//Данные тега <title>
	$headMain = 'Редактирование комментария';
	$robots = 'noindex, follow';
	$descr = 'Форма редактирования комментария';
	$action = 'editform';	
	$text = $row['comment'];
	$id = $row['id'];
	$button = 'Обновить комментарий';
	
	include 'form.html.php';
	exit();
}
	
/*команда INSERT  - добавление комментария в базу данных*/
if (isset($_GET['addform']))//Если есть переменная addform выводится форма
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
		
	/*Возвращение id автора*/
	try
	{
		$selectComment = 'SELECT id FROM author WHERE authorname = ';//запрос, возвращающий id
		$authorComment = authorLogin ($_SESSION['email'], $_SESSION['password']);//возвращает имя автора
		
		/*Подключение к базе данных*/
		include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
	
		$sql = $selectComment.'"'.$authorComment.'"';
		$result = $pdo->query($sql);
	}

	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка выбора id ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
		
	/*Вывод результата в шаблон*/
	foreach ($result as $row)
	{
		$authorID[] =  array ('idauthor' => $row['id']);
	}	
		
	$selectedAuthor = (int)$row['id'];//id автора комментария
		
	try
	{
		$sql = 'INSERT INTO comments SET 
			comment = :comment,	
			commentdate = SYSDATE(),
			idauthor = '.$selectedAuthor.','.
			'idpost = '.$_SESSION['idpost'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':comment', $_POST['comment']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}
	
	catch (PDOException $e)
	{
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления информации '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	header ('Location: ../viewzenpost/?id='.$_SESSION['idpost']);//перенаправление обратно в контроллер index.php
	exit();	
}
	
/*UPDATE - обновление текста комментария*/

if (isset($_GET['editform']))//Если есть переменная editform выводится форма
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = 'UPDATE comments SET 
			comment = :comment
			WHERE id = :idcomment';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcomment', $_POST['id']);//отправка значения
		$s -> bindValue(':comment', $_POST['comment']);//отправка значения
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
	header ('Location: ../viewzenpost/?id='.$_SESSION['idpost']);//перенаправление обратно в контроллер index.php
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
		$sql = 'SELECT id FROM comments WHERE id = :idcomment';
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
	
	$title = 'Удаление комментария';//Данные тега <title>
	$headMain = 'Удаление комментария';
	$action = 'delete';
	$posttitle = 'Комментарий';
	$id = $row['id'];
	$button = 'Удалить';
	
	include 'delete.html.php';
}
	
if (isset ($_GET['delete']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Удаление комментариев*/
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
	
	header ('Location: ../viewpost/?id='.$_SESSION['idpost']);//перенаправление обратно в контроллер index.php
	exit();
}	

/*Скрипт рекомендации статьи*/
if (isset ($_POST['action']) && $_POST['action'] == 'Рекомендовать статью')
{	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	/*Команда SELECT выбор цены промоушена*/
	try
	{
		$sql = 'SELECT promotionprice FROM promotionprice WHERE id = 2';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'ошибка выбора цены рекомендации: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$recommendationPrice = $row['promotionprice'];
	
	@session_start();//Открытие сессии для сохранения id задания

	$_SESSION['promotionprice'] = $recommendationPrice;
	
	/*Возвращение id автора*/
	
	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
	
	@session_start();//Открытие сессии для сохранения id задания

	$_SESSION['idauthor'] = $selectedAuthor;
	
	/*Команда SELECT выбор счёа автора для сравнения*/
	try
	{
		$sql = 'SELECT score FROM author WHERE id = '.$selectedAuthor;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
	}

	catch (PDOException $e)
	{
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Error select book: ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	$row = $s -> fetch();
	
	$score = $row['score'];
	
	if ($recommendationPrice > $score)//Если на счету нет достаточной суммы для написания статьи.
	{
		$title = 'Ошибка доступа';//Данные тега <title>
		$headMain = 'Ошибка доступа';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = '<p>Для того, чтобы рекомендовать статью на Вашем счету должно быть сумма больше или равная '.$recommendationPrice.'. Пополните счёт в своём профиле!<p>
				  <p><em>Чтобы получить возможность пополнять счёт, получите статус рекламодателя в профиле.</em></p>';
		
		unset ($_SESSION['promotionprice']);
			
		include 'error.html.php';
		exit();
	}
	
	else
	{
		/*Подключение к базе данных*/
		include MAIN_FILE . '/includes/db.inc.php';
		
		/*Команда SELECT*/
		try
		{
			$sql = 'SELECT id, posttitle FROM posts WHERE id = :idpost';
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> bindValue(':idpost', $_POST['id']);//отправка значения
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}

		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора id и заголовка posts : ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();

		$title = 'Рекомендовать материал';//Данные тега <title>
		$headMain = 'Рекомендация материала';
		$action = 'recomm';
		$posttitle = $row['posttitle'];;
		$id = $row['id'];
		$button = 'Рекомендовать';

		include 'reccomendationok.html.php';
		
	}
}	

if (isset ($_GET['recomm']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';

	try
	{
		$pdo->beginTransaction();//инициация транзакции
			
		$sql = 'UPDATE posts SET 
			recommendationdate = SYSDATE()
			WHERE id = :idpost';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idpost', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			
		$sql = 'UPDATE author SET score  = score - '.$_SESSION['promotionprice'].'
								  WHERE id = '.$_SESSION['idauthor'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$pdo->commit();//подтверждение транзакции
	}

	catch (PDOException $e)
	{
		$pdo->rollBack();//отмена транзакции
			
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка обновления информации о рекомендации'. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}

	header ('Location: ../viewzenpost/?id='.$_SESSION['idpost']);//перенаправление обратно в контроллер index.php
	exit();
		
}
