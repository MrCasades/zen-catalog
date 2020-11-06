<?php 
/*Загрузка функций в шаблон*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/func.inc.php'?>


<!DOCKTYPE html>
<html>
<head> 

<title>Description</title>
<link href="http://localhost/mylibrary/styles.css" rel="stylesheet" type="text/css"> 
<meta charset = "utf-8">
</head>
<body>
<div><p class = "logo"><img width = "70%" height = "30%" src = "http://localhost/mylibrary/myliblogo_1.jpg" alt = "MyLibrary - Каталог домашней библиотеки"></p></div><br>
<br>
<div class="top-menu" align="center">
    <ul>
		<li><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/imagozcms/';?>">Главная страница</a></li>
        <li><a href='/imagozcms/searchbook/'>Поиск и управление списком</a></li>
		<li>
            <a href="#">Управление характеристиками</a>
            <ul>
                <li><a href='/imagozcms/authorlist/'>Управление списком авторов</a></li>
                <li><a href='/imagozcms/genrelist/'>Управление списком жанров</a></li>
				<li><a href='/imagozcms/publishinglist/'>Управление списком издательств</a></li>
            </ul>
        </li>
    </ul>
    </div>
<h1>Описание книги</h1>
<div class = "maincont">
<p><?php 
  $description = $_GET['description'];
  echomarkdown ($description);?> </p>
</div>

<?php 
/*Загрузка footer*/
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.inc.php';?>