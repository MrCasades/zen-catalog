<?php

/*Загрузка файла (тест)*/
	
	/*Извлечение расширения файла*/
	
if (preg_match ('/^image\/p?jpeg$/i', $_FILES['upload']['type']))
{
	$ext = '.jpg';
}
	
elseif (preg_match ('/^image\/p?gif$/i', $_FILES['upload']['type']))
{
	$ext = '.gif';
}
	
elseif (preg_match ('/^image\/p?png$/i', $_FILES['upload']['type']))
{
	$ext = '.png';
}
	
else	
{
	$ext = '.unk';
}
		
$fileName = $fileNameScript . $ext;//присвоение имени файла
$filePath = MAIN_FILE . $filePathScript . $fileName;//путь загрузки
		
if (!is_uploaded_file($_FILES['upload']['tmp_name']) or !copy($_FILES['upload']['tmp_name'], $filePath))
{
	$fileName = '';
}