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
	
	$select = 'SELECT posts.id AS postid, author.id AS idauthor, post, posttitle, imghead, videoyoutube, viewcount, votecount, averagenumber, favouritescount, description, imgalt, postdate, authorname, category.id AS categoryid, categoryname FROM posts 
			   INNER JOIN author ON idauthor = author.id 
			   INNER JOIN category ON idcategory = category.id WHERE premoderation = "YES" AND zenpost = "NO" AND posts.id = ';
	
	/*Канонический адрес*/
	if(!empty($_GET['utm_referrer']) || !empty($_GET['page']))
	{
		$canonicalURL = '<link rel="canonical" href="//'.MAIN_URL.'/viewpost/?id='.$idPost.'"/>';
	}

	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	try
	{
		$sql = $select.$idPost;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
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
	
	$row = $s -> fetch();
		
	$articleId = $row['postid'];
	$authorId = $row['idauthor'];
	$articleText = $row['post'];
	$imgHead = $row['imghead'];
	$imgAlt = $row['imgalt'];
	$date = $row['postdate'];
	$viewCount = $row['viewcount'];
	$averageNumber = $row['averagenumber'];
	$nameAuthor = $row['authorname'];
	$categoryName = $row['categoryname'];
	$categoryId = $row['categoryid'];
	$favouritesCount = $row['favouritescount'];
	
	/*Если страница отсутствует. Ошибка 404*/
	if (!$row)
	{
		header ('Location: ../page-not-found/');//перенаправление обратно в контроллер index.php
		exit();	
	}
	
	$categoryID = $row['categoryid'];//Сохранение id сатегории
	
	$title = $row['posttitle'].' | full-zen.imagoz.ru';//Данные тега <title>
	$headMain = $row['posttitle'];
	$robots = 'all';
	$descr = $row['description'];
	$authorComment = '';
	$jQuery = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>';
	$scriptJScode = '<script src="script.js"></script>
					 <script src="//'.MAIN_URL.'/js/jquery-1.min.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap-markdown.js"></script>
					 <script src="//'.MAIN_URL.'/js/bootstrap.min.js"></script>';//добавить код JS
	
	/*Микроразметка*/
	
	$dataMarkup = dataMarkup($row['posttitle'], $row['description'], $row['imghead'], $row['imgalt'], $row['postid'],
							$row['postdate'], $row['authorname'], $row['averagenumber'], $row['votecount'], 'viewpost');
	
	/*Вывод видео в статью*/
	if ((isset($row['videoyoutube'])) && ($row['videoyoutube'] != ''))
	{
		$video = '<iframe width="85%" height="320px" src="'.$row['videoyoutube'].'" frameborder="0" allowfullscreen></iframe>';
	}
	
	else
	{
		$video = '';
	}
	
	/*Кнопка добавления в избранное*/
	if (isset($_SESSION['loggIn']))
	{
		try
		{
			$sql = 'SELECT idpost FROM favourites WHERE idauthor = '.(authorID($_SESSION['email'], $_SESSION['password'])).' AND idpost = '.$idPost;
			$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
			$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		}
		
		catch (PDOException $e)
		{
			$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
			$headMain = 'Ошибка данных!';
			$robots = 'noindex, nofollow';
			$descr = '';
			$error = 'Ошибка выбора избранного ' . $e -> getMessage();// вывод сообщения об ошибке в переменой $e
			include 'error.html.php';
			exit();
		}

		$row = $s -> fetch();

		$favourites = $row['idpost'];

		if ($favourites != '')
		{
			$addFavourites = '<form action=" " metod "post" id = "ajax_form_fav">
								<input type = "hidden" name = "idauthor" value = "'.(authorID($_SESSION['email'], $_SESSION['password'])).'">
								<input type = "hidden" name = "id" value = "'.$idPost.'">
								<input type = "hidden" id = "val_fav" name = "val_fav" value = "delfav">
								<input type="image" src="like_2.gif" alt="Убрать из избранного" title="Убрать из избранного" id = "btn_fav">  
							 </form>
							 <strong><p id = "result_form_fav"></p></strong>';
		}

		else
		{
			$addFavourites = '<form action=" " metod "post" id = "ajax_form_fav">
								<input type = "hidden" name = "idauthor" value = "'.(authorID($_SESSION['email'], $_SESSION['password'])).'">
								<input type = "hidden" name = "id" value = "'.$idPost.'">
								<input type = "hidden" id = "val_fav" name = "val_fav" value = "addfav">
								<input type="image" src="like_1.gif" alt="Добавить в избранное" title="Добавить в избранное" id = "btn_fav"> 
							 </form>
							 <strong><p id = "result_form_fav"></p></strong>';
		}
	}
	
	else
	{
		$addFavourites = '';
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
		$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));;//id автора
	}
		
	else
	{
		$selectedAuthor = 0;//id автора
	}
	
	$votedPost = (int)$idPost;
	
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
	if (($votedAuthor == $selectedAuthor) && ($votedPost == $idPost) || (!isset($_SESSION['loggIn'])))
	{
		$votePanel = '';
	}
	
	elseif ((isset($_SESSION['loggIn'])) && ($votedAuthor != $selectedAuthor))
	{
		$votePanel = '<form action=" " metod "post" id = "confirmlike">
					  
					  Оцените статью: 
						<input type = "hidden" name = "id" id = "idarticle" value = "'.$idPost.'">
						<input type = "hidden" name = "idauthor" id = "idauthor" value = "'.$selectedAuthor.'">
						<input type = "submit" name = "vote" id = "btn_vot_5" value = "5" class="btn btn-primary btn-sm"> 
						<input type = "submit" name = "vote" id = "btn_vot_4" value = "4" class="btn btn-primary btn-sm"> 
						<input type = "submit" name = "vote" id = "btn_vot_3" value = "3" class="btn btn-primary btn-sm"> 
						<input type = "submit" name = "vote" id = "btn_vot_2" value = "2" class="btn btn-primary btn-sm"> 
						<input type = "submit" name = "vote" id = "btn_vot_1" value = "1" class="btn btn-primary btn-sm"> 
					  </form>
					  <strong><p id = "result_form_vot"></p></strong>';
	}
	
	/*Вывод кнопок "Обновить" | "Удалить" | "Снять с публикации"(Возможно убрать эту кнопку для всех, кромке админа и редактора)"*/
	
	if ((isset($_SESSION['loggIn'])) && (userRole('Администратор')))
	{
		$delAndUpd = "<form action = '../admin/addupdpost/' method = 'post'>
			
						Действия с материалом:
						<input type = 'hidden' name = 'id' value = '".$idPost."'>
						<input type = 'submit' name = 'action' value = 'Upd' class='btn btn-primary btn-sm'>
						<input type = 'submit' name = 'action' value = 'Del' class='btn btn-primary btn-sm'>
					  </form>";
		
		$premoderation = "<form action = '../admin/premoderation/postpremoderationstatus/' method = 'post'>
			
						Статус публикации:
						<input type = 'hidden' name = 'id' value = '".$idPost."'>
						<input type = 'submit' name = 'action' value = 'Снять с публикации' class='btn btn-primary btn-sm'>
					  </form>";				
	}
	
	else
	{
		$delAndUpd = '';
		$premoderation = '';
	}	
	
	/*Вывод кнопки "Рекомендовать статью"*/
	if (isset($_SESSION['loggIn']) && ((userRole('Администратор')) || (userRole('Автор')) || (userRole('Рекламодатель'))))
	{
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
	
		$recommendation = '<form action = "" method = "post" id = "ajax_form_recomm">
						<input type = "hidden" name = "id" id = "idarticle" value = "'.$idPost.'">
						<input type = "hidden" name = "recommprice" id = "recommprice" value = "'.$recommendationPrice.'">
						<input type = "hidden" name = "idauthor" id = "idauthor" value = "'.$selectedAuthor.'">
						<input type = "submit" id = "btn_recomm" name = "action" value = "Рекомендовать статью" class="btn btn-primary btn-sm">
					  </form>
					  <strong><p id = "result_form_recomm"></p></strong>';
	}
	
	elseif (isset($_SESSION['loggIn']) && ((!userRole('Администратор')) || (!userRole('Автор')) || (!userRole('Рекламодатель'))))
	{
		$recommendation = '<strong>Получите статус рекламодателя в профиле, чтобы получить возможность рекомендовать статью.</strong>';
	}
	
	elseif (!isset($_SESSION['loggIn']))
	{
		$recommendation = '<strong>Вы можете <a href="../admin/registration/?log">авторизироваться</a> в системе или 
						 <a href="../admin/registration/?reg">зарегестрироваться</a> для того, чтобы рекомендовать статью на главной странице!</strong>';
	}
	
	/*Вывод похожих материалов*/

	try
	{
		$sql = 'SELECT id, posttitle, imghead, imgalt FROM posts WHERE idcategory = '.$categoryID.' AND premoderation = "YES" ORDER BY rand() LIMIT 6';
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
		$similarPosts[] =  array ('id' => $row['id'], 'posttitle' =>  $row['posttitle'], 'imghead' =>  $row['imghead'], 'imgalt' =>  $row['imgalt']);
	}	
	
	/*Подсчёт статей для определения числа колонок в выводе*/
	try
	{
		$sql = 'SELECT count(*) AS count_all FROM posts WHERE idcategory = '.$categoryID.' AND premoderation = "YES"';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
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
	
	$row = $s -> fetch();
	
	$columns = $row['count_all'] > 1 ? 'columns' : 'columns_f1';
	
	/*Вывод комментариев*/	
	/*Постраничный вывод информации*/
		
	$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;// помещаем номер страницы из массива GET в переменую $page
	$onPage = 10;// количество статей на страницу
	$shift = ($page - 1) * $onPage;// (номер страницы - 1) * статей на страницу

	try
	{
		$sql = 'SELECT comments.id, comment, commentdate, subcommentcount, authorname, avatar, author.id AS idauthor, idpost FROM comments 
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
							  'authorname' => $row['authorname'], 'subcommentcount' => $row['subcommentcount'], 'avatar' => $row['avatar'], 'idarticle' => $row['idpost']);
	}
	
	/*Форма добавления комментария / Получение имени автора для вывода меню редактирования или удаления комментария*/
	if (isset($_SESSION['loggIn']))
	{
		$action = 'addform';
		$authorName = authorLogin ($_SESSION['email'], $_SESSION['password']);//имя автора вошедшего в систему
		$addComment = '<form action = "?'.$action.'" method = "post" align="center">
						 <div>
						 	<input type = "hidden" name = "idarticle" value = "'.$idPost.'">
							<textarea class = "descr" id = "comment" name = "comment" data-provide="markdown" rows="10" placeholder = "Напишите свой комментарий!"></textarea>	
						 </div>
						  <div>
							<input type = "submit" value = "Добавить комментарий" class="btn btn-info btn-sm">
						  </div>	  
						</form>
						<hr/>';	
	}
	
	else
	{
		$authorName = '';
		$_SESSION['email'] = '';
		$addComment = '<a href="/admin/registration/?log">Авторизируйтесь</a> в системе или 
						 <a href="/admin/registration/?reg">зарегестрируйтесь</a> для того, чтобы оставить комментарий!';//Вывод сообщения в случае невхода в систему
		
		$action = '';	
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
	
	include 'viewpost.html.php';
	exit();		
}
	
/*Обновление комментария*/
if (isset ($_POST['action']) && $_POST['action'] == 'Редактировать')
{		
	updCommentData($_POST['id'], $_POST['idarticle']);
	
	include 'form.html.php';
	exit();
}
	
/*команда INSERT  - добавление комментария в базу данных*/
if (isset($_GET['addform']))//Если есть переменная addform выводится форма
{
	/*Если поле комментария пустое*/
	if ($_POST['comment'] == '')
	{
		$title = 'Напишите текст комментария!';//Данные тега <title>
		$headMain = 'Напишите текст комментария!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Поле комментария не может быть пустым!';
		include 'error.html.php';
		exit();
	}
	
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
		
	/*Загрузка функций для формы входа*/
	require_once MAIN_FILE . '/includes/access.inc.php';
		
	/*Возврат id автора*/
	
	$selectedAuthor = (int)(authorID($_SESSION['email'], $_SESSION['password']));//id автора
	
	$SELECTCONTEST = 'SELECT conteston FROM contest WHERE id = 1';//проверка включения/выключения конкурса
		
	try
	{
		$pdo->beginTransaction();//инициация транзакции
		
		$sql = 'INSERT INTO comments SET 
			comment = :comment,	
			commentdate = SYSDATE(),
			idauthor = '.$selectedAuthor.','.
			'idpost = '.$_POST['idarticle'];
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':comment', $_POST['comment']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$sql = $SELECTCONTEST;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			
		$row = $s -> fetch();
		
		$contestOn = $row['conteston'];//проверка на включение конкурса
		
		$pdo->commit();//подтверждение транзакции
	}
	
	catch (PDOException $e)
	{
		$pdo->rollBack();//отмена транзакции
		
		$title = 'ImagozCMS | Ошибка данных!';//Данные тега <title>
		$headMain = 'Ошибка данных!';
		$robots = 'noindex, nofollow';
		$descr = '';
		$error = 'Ошибка добавления информации '. ' Error: '. $e -> getMessage();// вывод сообщения об ошибке в переменой $e
		include 'error.html.php';
		exit();
	}
	
	/*Если конкурс включён, происходит изменение конкурсного счёта*/	
	if (($contestOn == 'YES') && (!userRole('Автор')) && (!userRole('Администратор'))) delOrAddContestScore('add', 'commentpoints');//если конкурс включен
	
	header ('Location: ../viewpost/?id='.$_POST['idarticle']);//перенаправление обратно в контроллер index.php
	exit();	
}
	
/*UPDATE - обновление текста комментария*/

if (isset($_GET['editform']))//Если есть переменная editform выводится форма
{
	updComment($_POST['id'], $_POST['comment'], $_POST['idarticle'], 'post');
}

/*DELETE - удаление комментария*/

if (isset ($_POST['action']) && $_POST['action'] == 'Del')	
{	
	delCommentData($_POST['id'], $_POST['idarticle']);
	
	include 'delete.html.php';
}
	
if (isset ($_GET['delete']))
{
	/*Подключение к базе данных*/
	include MAIN_FILE . '/includes/db.inc.php';
	
	$SELECTCONTEST = 'SELECT conteston FROM contest WHERE id = 1';//проверка включения/выключения конкурса
	
	/*Если конкурс включён, происходит изменение конкурсного счёта*/
	if ($contestOn == 'YES') delOrAddContestScore('del', 'commentpoints');//если конкурс включен
	
	/*Удаление комментариев*/
	try
	{
		$pdo->beginTransaction();//инициация транзакции
		
		$sql = 'DELETE FROM comments WHERE id = :idcomment';
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> bindValue(':idcomment', $_POST['id']);//отправка значения
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
		
		$sql = $SELECTCONTEST;
		$s = $pdo->prepare($sql);// подготавливает запрос для отправки в бд и возвр объект запроса присвоенный переменной
		$s -> execute();// метод дает инструкцию PDO отправить запрос MySQL
			
		$row = $s -> fetch();
		
		$contestOn = $row['conteston'];//проверка на включение конкурса
		
		/*Если конкурс включён, происходит изменение конкурсного счёта*/
		if ($contestOn == 'YES') delOrAddContestScore('del', 'commentpoints');//если конкурс включен
		
		$pdo->commit();//подтверждение транзакции	
	}
	
	catch (PDOException $e)
	{
		$pdo->rollBack();//отмена транзакции
		
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
	
	header ('Location: ../viewpost/?id='.$_POST['idarticle']);//перенаправление обратно в контроллер index.php
	exit();
}	