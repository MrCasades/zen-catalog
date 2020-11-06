<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<rss xmlns:yandex="http://news.yandex.ru"
     xmlns:media="http://search.yahoo.com/mrss/"
     xmlns:turbo="http://turbo.yandex.ru"
     version="2.0">

<channel>

<title>Hi-Tech новости, игры, наука, интернет в отражении на imagoz.ru</title>

<link>https://imagoz.ru/</link>

<description>

Портал IMAGOZ. Место где мы рассматриваем мир Hi-Tech, игровую индустрию, науку и технику в оригинальном авторском отражении!

</description>

<language>ru</language>
	
	
	<?php 
	/*Загрузка функций в шаблон*/
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/func.inc.php';?>


	<?php foreach ($newsMain as $newsMain_3): ?>
	
<item turbo="true">

<title><?php echo $newsMain_3['newstitle']; ?></title>

<link>https://imagoz.ru/viewnews/?id=<?php htmlecho ($newsMain_3['id']); ?></link>

<author><?php echo $newsMain_3['authorname']; ?></author>	
	
<pubDate><?php echo date("D, j M Y G:i:s", strtotime($newsMain_3['newsdate'])); ?> +0300</pubDate>
	


<turbo:content>
        <![CDATA[
                     <header>
                        <h1><?php echo $newsMain_3['newstitle']; ?></h1>
                        <figure>
                            <img src="https://imagoz.ru/images/<?php echo $newsMain_3['imghead']; ?>">
                        </figure>
                        <menu>
                            <a href="https://www.imagoz.ru/viewcategory/?id=1">Мир игр</a>
                            <a href="https://www.imagoz.ru/viewcategory/?id=2">Hi-Tech world</a>
                            <a href="https://www.imagoz.ru/viewcategory/?id=3">Популярная наука</a>
                            <a href="https://www.imagoz.ru/viewcategory/?id=4">Gadgets</a>
                            <a href="https://www.imagoz.ru/viewcategory/?id=5">Изображение дня</a>
                        </menu>
                    </header>
					<?php echomarkdown ($newsMain_3['textnews']); ?>
        ]]> 
 </turbo:content>

</item>

<?php endforeach; ?>


<?php foreach ($posts as $post): ?>
	
<item turbo="true">

<title><?php echo $post['posttitle']; ?></title>

<link>https://imagoz.ru/viewpost/?id=<?php htmlecho ($post['id']); ?></link>

<author><?php echo $post['authorname']; ?></author>	

<pubDate><?php echo date("D, j M Y G:i:s", strtotime($post['postdate'])); ?> +0300</pubDate>

<turbo:content>
        <![CDATA[
                     <header>
                        <h1><?php echo $post['posttitle']; ?></h1>
                        <figure>
                            <img src="https://imagoz.ru/images/<?php echo $post['imghead']; ?>">
                        </figure>
                        <menu>
                            <a href="https://www.imagoz.ru/viewcategory/?id=1">Мир игр</a>
                            <a href="https://www.imagoz.ru/viewcategory/?id=2">Hi-Tech world</a>
                            <a href="https://www.imagoz.ru/viewcategory/?id=3">Популярная наука</a>
                            <a href="https://www.imagoz.ru/viewcategory/?id=4">Gadgets</a>
                            <a href="https://www.imagoz.ru/viewcategory/?id=5">Изображение дня</a>
                        </menu>
                    </header>
					<?php echomarkdown ($post['text']); ?>
        ]]> 
 </turbo:content>

</item>

<?php endforeach; ?>

</channel>

</rss>