<? 
	/*Загрузка функций в шаблон*/
	include_once MAIN_FILE . '/includes/func.inc.php';

$content = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>
      <loc>https://'.MAIN_URL.'/</loc>
	  <lastmod>2020-01-01</lastmod>
	  <priority>1.0</priority>
   </url>
   <url>
      <loc>https://'.MAIN_URL.'/project/</loc>
	  <lastmod>2020-01-01</lastmod>
	  <priority>0.65</priority>
   </url>
'?>
	
<?php foreach ($posts as $post): ?>

<?php $content .= '<url>
      <loc>https://'.MAIN_URL.'/viewpost/?id='.$post['id'].'</loc>
	  <lastmod>'.date("Y-m-d", strtotime($post['postdate'])).'</lastmod>
	  <priority>0.8</priority>
   </url>';?>

<?php endforeach; ?>

<?php foreach ($promotions as $promotion): ?>

<?php $content .= '<url>
      <loc>https://'.MAIN_URL.'/viewpromotion/?id='.$promotion['id'].'</loc>
	  <lastmod>'.date("Y-m-d", strtotime($promotion['promotiondate'])).'</lastmod>
	  <priority>0.8</priority>
   </url>';?>

<?php endforeach; ?>

<?php foreach ($authors as $author): ?>

<?php $content .= '<url>
      <loc>https://'.MAIN_URL.'/account/?id='.$author['id'].'</loc>
	  <lastmod>'.date("Y-m-d", strtotime($author['regdate'])).'</lastmod>
	  <priority>0.7</priority>
   </url>';?>

<?php endforeach; ?>

<?php $content .='</urlset>';

/*Генерация rss-ленты*/
$sitemap = 'sitemap.xml';

file_put_contents($sitemap, $content);

echo 'Файл sitemap создан!'

?>