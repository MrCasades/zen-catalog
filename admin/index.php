<?php
$title = 'Панель администратора';//Данные тега <title>
$headMain = 'Разделы';
$robots = 'noindex, nofollow';
$descr = 'Старая панель администрироваения';?>

<?php 
/*Загрузка главного пути*/
include_once '../includes/path.inc.php';

/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>



	<div class = "maincont">
		<ul>
		 <li><a href='../admin/authorlist/'>Управление списком авторов</a></li>
         <li><a href='../admin/categorylist/'>Управление списком рубрик</a></li>
		 <li><a href='../admin/metalist/'>Управление списком тематик</a></li>
		 <li><a href='../admin/searchpost/'>Управление статьями</a></li>
		 <li><a href='../admin/ranglist/'>Управление рангами</a></li>
		 <li><a href='../admin/paysystemlist/'>Управление списком платёжных систем</a></li>
		 <li><a href='../admin/tasktypelist/'>Управление типами задания</a></li>
		 <li><a href='../admin/promotionpricelist/'>Управление ценой промоушена</a></li>
		 <li><a href='../admin/contest/'>Управление конкурсом</a></li>
		 <li><a href='../admin/commentnewslist/'>Просмотр комментариев</a></li>
		 <li><a href='../sitemap.php' target="_blank">Обновить карту сайта</a></li>	
		</ul>
	</div>

<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>