<? 
	/*Загрузка функций в шаблон*/
	include_once MAIN_FILE . '/includes/func.inc.php';

$content = '<?xml version="1.0" encoding="UTF-8"?>

<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">

<channel>

<title>Hi-Tech новости, игры, наука, интернет в отражении на imagoz.ru</title>

<link>https://imagoz.ru/</link>

<description>

Портал IMAGOZ. Место где мы рассматриваем мир Hi-Tech, игровую индустрию, науку и технику в оригинальном авторском отражении!

</description>
<image>
     <url>https://imagoz.ru/logomain.jpg</url>
</image>

<language>ru</language>'?>
	
	


	<?php foreach ($newsMain as $newsMain_3): ?>
	
<?php $content .= '<item>

<title>'. $newsMain_3['newstitle'].'</title>

<link>https://imagoz.ru/viewnews/?id='.$newsMain_3['id'].'</link>

<pubDate>'.date("D, j M Y G:i:s", strtotime($newsMain_3['newsdate'])).' +0300</pubDate>

<enclosure url="https://imagoz.ru/images/'.$newsMain_3['imghead'].'" type= "image/jpeg"/>

<description>

<![CDATA['.

markdown2html($newsMain_3['textnews']).'

]]>

</description>

</item>';?>

<?php endforeach; ?>

<?php foreach ($posts as $post): ?>

<?php $content .= '<item>

<title>'.$post['posttitle'].'</title>

<link>https://imagoz.ru/viewpost/?id='.$post['id'].'</link>

<pubDate>'.date("D, j M Y G:i:s", strtotime($post['postdate'])).' +0300</pubDate>

<enclosure url="https://imagoz.ru/images/'.$post['imghead'].'" type= "image/jpeg"/>

<description>

<![CDATA['.

markdown2html ($post['text']).'

]]>

</description>

</item>';?>

<?php endforeach; ?>

<?php $content .='</channel>

</rss>';

/*Генерация rss-ленты*/
$rssPulse = 'rssvk.xml';

file_put_contents($rssPulse, $content);

echo 'Файл создан!'

?>